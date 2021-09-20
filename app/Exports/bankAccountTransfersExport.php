<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\Transfer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class bankAccountTransfersExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
      $from_bank_id = isset($_GET['from_bank_id']) ? $_GET['from_bank_id'] : null ;
      $to_bank_id   = isset($_GET['to_bank_id']) ? $_GET['to_bank_id'] : null ;
      $from         = isset($_GET['from']) ? $_GET['from'] : null ;
      $to           = isset($_GET['to']) ? $_GET['to'] : null ;

      $bankTransfer = Transfer::with(['fromBank', 'toBank', 'currency', 'transactionReference'])->select('transfers.*')->orderBy('transaction_date', 'desc');
      if (!empty($from)) {
        $bankTransfer->where('transaction_date', '>=', DbDateFormat($from));
      }
      if (!empty($to)) {
        $bankTransfer->where('transaction_date', '<=', DbDateFormat($to));
      }
      if (!empty($from_bank_id)) {
        $bankTransfer->where('from_account_id', '=', $from_bank_id);
      }
      if (!empty($to_bank_id)) {
        $bankTransfer->where('to_account_id', '=', $to_bank_id);
      }
        return $bankTransfer;

    }

    public function headings(): array
    {
        return [
            'Reference',
            'From Bank Account',
            'To Bank Account',
            'Description',
            'Transfer Amount',
            'Bank Charge',
            'Incoming Amount',
            'Transaction Date'
        ];
    }
    
    public function map($transfer): array
    {  
        $fromCurrency = isset($transfer->fromBank->currency) ? $transfer->fromBank->currency->name : '';
        $toCurrency = isset($transfer->toBank->currency) ? $transfer->toBank->currency->name : '';
        $currency = isset($transfer->currency) ? $transfer->currency->name : '';
        return [
            $transfer->transactionReference->code,
            isset($transfer->fromBank) ? $transfer->fromBank->name." ".$fromCurrency : '',
            isset($transfer->toBank) ? $transfer->toBank->name." ".$toCurrency : '',
            $transfer->description,
            formatCurrencyAmount($transfer->amount).' '.$currency,
            formatCurrencyAmount($transfer->fee).' '.$fromCurrency,
            formatCurrencyAmount($transfer->incoming_amount)." ".$toCurrency,
            formatDate($transfer->transaction_date),
        ];
    }

    
}
