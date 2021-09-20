<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Validator;
use App\Models\SmsConfig;
use Twilio\Http\CurlClient;

class SmsController extends Controller
{
  public function sendSms(Request $request)
  {
    $data = [];
    $client = "";
    $configs = SmsConfig::where(['status' => 'Active', 'type' => 'twilio'])->first();

    if (empty($configs)) {
      $data = ['status' => 'fail', 'message' => __('Please configure SMS setup.')];
      \Session::flash($data['status'], $data['message']);
      return redirect()->back();
    } else {
      $sid    = $configs["key"];
      $token  = $configs["secretkey"];
      $curlOptions = [ CURLOPT_SSL_VERIFYHOST => false, CURLOPT_SSL_VERIFYPEER => false];
      $client = new Client($sid, $token);
      $client->setHttpClient(new CurlClient($curlOptions));

      $validator = Validator::make($request->all(), [
        'phoneno' => 'required',
        'message' => 'required'
      ]);

      if ($validator->passes()) {
        $errorMsg = "";
        try {
          if (!empty($client)) {
            $client->messages->create(
              $request->phoneno,
              [
                'from' => $configs["default_number"],
                'body' => $request->message,
              ]
            );

            $data['status'] = 'success';
            $data['message'] = __('SMS has been sent successfully.');
          }
        } catch (\Exception $e) {
          // getting error status code
          $statusCode = $e->getStatusCode();

          // if id, token or phone number does not match, show authentication error message
          if ($statusCode == 400 || $statusCode == 401 || $statusCode == 404) {
            $errorMsg = __("Authentication failed. Please provide valid credentials.");
          } else {
            // if some other error occurs, show a common error message
            $errorMsg = __("SMS send failed. Please try later.");
          }

          $data['status'] = 'fail';
          $data['message'] = $errorMsg;
        }
        \Session::flash($data['status'], $data['message']);
        return redirect()->back();
      } else {
        return back()->withErrors($validator);
      }
    }
  }
}
