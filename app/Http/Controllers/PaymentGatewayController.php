<?php

namespace App\Http\Controllers;

use URL;
use Illuminate\Http\Request;
use Stripe;
use Session;
use Redirect;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\GeneralLedgerController;
use Auth;
use Cache;
use App\Models\Transaction as Trans;
use App\Models\CustomerTransaction;
use App\Models\SaleOrder;
use App\Models\PaymentMethod;
use App\Models\Preference;
use App\Models\Currency;
use DB;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment ;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaymentGatewayController extends Controller
{
    public function __construct()
    {
    	$options = PaymentMethod::getAll()->where('id', 1)->first()->toArray();
		$configs = $this->initializePaypal($options);
    	$clientId = $configs['client_id'];
		$clientSecret = $configs['secret'];
		if ($configs['settings']['mode'] == 'sandbox') {
			$environment = new SandboxEnvironment($clientId, $clientSecret);
		} else if ($configs['settings']['mode'] == 'live') {
			$environment = new ProductionEnvironment($clientId, $clientSecret);
		}
		$this->client = new PayPalHttpClient($environment);
	}

	public function payWithpaypal(Request $request)
    {
    	$companyName = Preference::getAll()->where('category', 'company')->where('field', 'company_name')->first()->value;
    	$data = $request->all();
        $allowedCurrencies = ['AUD', 'BRL', 'CAD', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'INR', 'JPY', 'MYR', 'MXN', 'NOK', 'NZD', 'PHP', 'PLN', 'GBP', 'SGD', 'SEK', 'CHF', 'TWD', 'THB', 'USD', 'RUB'];

        // Check if provided currency is valid.
        if (!in_array($request->curencyName, $allowedCurrencies, true)) {
            \Session::flash('fail', 'This currency is not supported by PayPal.');
            return redirect('customer-panel/view-detail-invoice/'. $request->invoiceId);
        }

        Cache::put('gb-paypal-payment-info-'. $request->invoiceId, $data, 3600);

        $request = new OrdersCreateRequest();
		$request->prefer('return=representation');
		$request->body = [
	                     	"intent" => "CAPTURE",
	                     	"purchase_units" => [
		                     	[
		                         	"reference_id" => $data['invoiceId'] . '#' . $data['invoiceRef'],
		                         	"amount" => [
		                             	"value" => number_format($data['payPalAmount'], 2, '.', ''),
		                             	"currency_code" => $data['curencyName'],
		                         	],
		                         	"invoice_number" => $data['invoiceRef'],
		                     	]
	                 		],

	                     	"application_context" => [
	                          	"cancel_url" => URL::to('capture-order'),
	                          	"return_url" => URL::to('capture-order'),
	                     	]
	                 	];
		try {
			$response = $this->client->execute($request);
			if (isset($response->result->id) && !empty($response->result->id)) {
				Session::put('orderId', $response->result->id);
			}
			if (isset($response->result->links[1]->href) && !empty($response->result->links[1]->href)) {
				return redirect($response->result->links[1]->href);
			}
		} catch (PayPalHttp\HttpException $ex) {
			\Session::flash('fail', $ex->getMessage());
            return redirect()->intended('customer-panel/view-detail-invoice/'. $request->invoiceId);
		}

	}


	public function captureOrder() {
		$value = Session::get('orderId');
		if (!empty($value)) {
			$request = new OrdersCaptureRequest($value);
			$request->prefer('return=representation');
			try {
			    // Call API with your client and get a response for your call
			    $response = $this->client->execute($request);
			    if ( isset($response->result->status) &&  $response->result->status == "COMPLETED") {
			    	if (isset($response->result->purchase_units[0]->reference_id) && !empty($response->result->purchase_units[0]->reference_id)) {
			    		if (isset(explode('#', $response->result->purchase_units[0]->reference_id)[0])) {
			    			$id = explode('#', $response->result->purchase_units[0]->reference_id)[0];
	         				$data = Cache::get('gb-paypal-payment-info-'. $id);
			    			$this->storePaypalPayment($data);
			    			return redirect()->intended('customer-panel/view-detail-invoice/'. $data['invoiceId']);
			    		}
			    	}
			    }
			} catch (HttpException $ex) {
			    \Session::flash('fail', $ex->getMessage());
	        	return redirect()->intended('customer-panel/invoice');
			}
		}
	}

	public function storePaypalPayment($data) {
		if (!empty($data)) {
	     	try {
	     		DB::beginTransaction();
	     		$reference_id = (new GeneralLedgerController)->createReference($data['payPalReference'], 'INVOICE_PAYMENT', $data['invoiceId']); 
	     		if ($reference_id) {
	                 $bankTrans                              = new Trans;
	 		        $bankTrans->currency_id                 = $data['curencyId'];
	 		        $bankTrans->amount                      = $data['payPalAmount'];
	 		        $bankTrans->transaction_type            = 'cash-in-by-sale';
	 		        $bankTrans->transaction_date            = date("Y-m-d") ;
	 		        $bankTrans->transaction_reference_id    = $reference_id;
	 		        $bankTrans->transaction_method          = 'INVOICE_PAYMENT';
	 		        $bankTrans->payment_method_id           = 1; 
	 		        $bankTrans->save();
	                
	                $customer_transaction                           = new CustomerTransaction;
	 		        $customer_transaction->payment_method_id        = 1;
	 		        $customer_transaction->customer_id              = $data['customerId'];
	 		        $customer_transaction->sale_order_id            = $data['invoiceId'];
	 		        $customer_transaction->transaction_reference_id = $reference_id;
	 		        $customer_transaction->currency_id              = $data['curencyId'];
	 		        $customer_transaction->transaction_date         = date("Y-m-d");
	 		        $customer_transaction->amount                   = $data['payPalAmount'];
	 		        $customer_transaction->exchange_rate            = 1;
	 		        $customer_transaction->save();

	 		        // update paid amount
	                $old_paid_amount = SaleOrder::find($data['invoiceId']);
	                $sum = ( (float) $old_paid_amount->paid + $data['payPalAmount']);
	                $old_paid_amount->paid = $sum;
	                $old_paid_amount->save();

	                DB::commit();
	         		\Session::flash('success', __('Payment success!'));
	         		
	 		    }
	     	} catch (\Exception $e) {
	     		DB::rollBack();
	     		\Session::flash('fail', __('Payment failed'));
	    	}
	        Cache::forget('gb-paypal-payment-info-'. $data['invoiceId']);
     	}
     	\Session::flash('fail', __('Payment failed'));
	}
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
    	$secret = PaymentMethod::getAll()->where('id', 3)->first()->consumer_secret;
    	$companyName = Preference::getAll()->where('category', 'company')->where('field', 'company_name')->first()->value;
        Stripe\Stripe::setApiKey($secret);

        $allowedCurrencies = ['USD', 'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BIF', 'BMD', 'BND', 'BOB', 'BRL', 'BSD', 'BWP', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'ISK', 'JMD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KRW', 'KYD', 'KZT', 'LAK', 'LBP','LKR', 'LRD', 'LSL', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'STD', 'SZL', 'THB', 'TJS', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'UYU', 'UZS', 'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW'];

     	// Check if provided currency is valid.
        if (!in_array($request->stripeCurencyName, $allowedCurrencies, true)) {
            \Session::flash('fail', 'This currency is not supported by Stripe.');
            return redirect('customer-panel/view-detail-invoice/'.$request->stripeInvoiceId);
        } 

        try {
		    $charge = Stripe\Charge::create ([
		                "amount" => round(round($request->stripeAmount, 2) * 100, 2),
		                "currency" => $request->stripeCurencyName,
		                "source" => $request->stripeToken,
		                "description" => $companyName 
        	]);

        	if ($charge->status == "succeeded") {
        		try {
	        		DB::beginTransaction();
	        		$reference_id = (new GeneralLedgerController)->createReference($request->stripeReference, 'INVOICE_PAYMENT', $request->stripeInvoiceId); 
	        		if ($reference_id) {
	                    $bankTrans                              = new Trans;
				        $bankTrans->currency_id                 = $request->stripeCurencyId;
				        $bankTrans->amount                      = $request->stripeAmount;
				        $bankTrans->transaction_type            = 'cash-in-by-sale';
				        $bankTrans->transaction_date            = date("Y-m-d") ;
				        $bankTrans->transaction_reference_id    = $reference_id;
				        $bankTrans->transaction_method          = 'INVOICE_PAYMENT';
				        $bankTrans->payment_method_id           = 3; 
				        $bankTrans->save();
	                    

	                    $customer_transaction                           = new CustomerTransaction;
				        $customer_transaction->payment_method_id        = 3;
				        $customer_transaction->customer_id              = $request->stripeCustomerId;
				        $customer_transaction->sale_order_id            = $request->stripeInvoiceId;
				        $customer_transaction->transaction_reference_id = $reference_id;
				        $customer_transaction->currency_id              = $request->stripeCurencyId;
				        $customer_transaction->transaction_date         = date("Y-m-d");
				        $customer_transaction->amount                   = $request->stripeAmount;
				        $customer_transaction->exchange_rate            = 1;
				        $customer_transaction->save();
				        // update paid amount
	                    $old_paid_amount = SaleOrder::find($request->stripeInvoiceId);
	                    $sum = ( (float) $old_paid_amount->paid + $request->stripeAmount);
	                    $old_paid_amount->paid = $sum;
	                    $old_paid_amount->save();

	                    DB::commit();
	            		\Session::flash('success', __('Payment success!'));
	            		return back();
				    }
	        	} catch (\Exception $e) {
	        		DB::rollBack();
	        		\Session::flash('fail', __('Payment failed'));
	        		return back();
	        	}
        	}
		} catch(Stripe\Exception\InvalidRequestException $e) {
		  	$error = $e->getMessage();
		  	Session::flash('fail', $error);
		    return back();
		}
	}

	protected function initializePaypal($options = []) 
	{
		$configs = [ 
		    'client_id' => $options['client_id'],
		    'secret' => $options['consumer_secret'],
		    'settings' => array(
		        'mode' => $options['mode'],
		        'http.ConnectionTimeOut' => 30,
		        'log.LogEnabled' => true,
		        'log.FileName' => storage_path() . '/logs/paypal.log',
		        'log.LogLevel' => 'ERROR'
		    ),
		];

		return $configs;
	}

	public function bankPayment(Request $request)
    {
    	$exchangeRate = (new Currency)->getExchangeRate($request->toCurrency, $request->invoice_currency_id);
    	try {
    		DB::beginTransaction();
    		$reference_id = (new GeneralLedgerController)->createReference($request->order_reference, 'INVOICE_PAYMENT', $request->order_no); 
    		if ($reference_id) {                
                $customer_transaction                           = new CustomerTransaction;
		        $customer_transaction->payment_method_id        = 2;
		        $customer_transaction->customer_id              = $request->customer_id;
		        $customer_transaction->sale_order_id            = $request->order_no;
		        $customer_transaction->transaction_reference_id = $reference_id;
		        $customer_transaction->currency_id              = $request->invoice_currency_id;
		        $customer_transaction->transaction_date         = DbDateFormat($request->payment_date);
		        $customer_transaction->amount                   = validateNumbers($request->amount);
		        $customer_transaction->exchange_rate            = $exchangeRate;

		        $bankAccount = PaymentMethod::getAll()->where('name', 'Bank')->first();
		        if ($bankAccount->approve == "manual") {
		        	$customer_transaction->account_id = $bankAccount->client_id;
		        	$customer_transaction->status = "Pending";
		        	$customer_transaction->save();
		        } else {
		        	$customer_transaction->save();
		        	$bankTrans                              = new Trans;
			        $bankTrans->currency_id                 = $request->toCurrency;
			        $bankTrans->amount                      = validateNumbers($request->amount) * $exchangeRate;
					$bankTrans->transaction_type            = 'cash-in-by-sale';
			        $bankTrans->account_id           	    = $request->account_id;
			        $bankTrans->transaction_date            = $request->payment_date;
			        $bankTrans->transaction_reference_id    = $reference_id;
			        $bankTrans->transaction_method          = 'INVOICE_PAYMENT';
			        $bankTrans->payment_method_id           = 2; 
			        $bankTrans->save();
			        // update paid amount
	                $old_paid_amount = SaleOrder::find($request->order_no);
	                $sum = ( (float) $old_paid_amount->paid + validateNumbers($request->amount));
	                $old_paid_amount->paid = $sum;
	                $old_paid_amount->save();
		        }

                DB::commit();
        		\Session::flash('success', __('Payment success!'));
        		return back();
		    }
    	} catch (\Exception $e) {
    		DB::rollBack();
    		\Session::flash('fail', __('Payment failed'));
    		return back();
    	}
	}

}
