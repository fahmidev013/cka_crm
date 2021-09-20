<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class TransactionReference extends Model
{
    public $timestamps = false;

    public function generalLedgers()
    {
        return $this->hasMany("App\Models\GeneralLedger", 'reference_id');
    }
    public function customerTransactions()
    {
    	return $this->hasMany("App\Models\CustomerTransaction", 'transaction_reference_id');
    }
    public function transactions()
    {
        return $this->hasMany("App\Models\Transaction", 'transaction_reference_id');
    }
    public function expense()
    {
       return $this->hasOne("App\Models\Expense", 'transaction_reference_id');
    }
    public function deposit()
    {
      return $this->hasOne("App\Models\Deposit",'transaction_reference_id');
    }
    public function transfer()
    {
         return $this->hasOne("App\Models\Transfer", 'transaction_reference_id');
    }
    public function createReference($reference_type, $object_id)
    {
        $reference = $this->where('reference_type', $reference_type)->latest('id')->first();
        $newReference = new TransactionReference();
        $newReference->object_id = $object_id;
        $newReference->reference_type = $reference_type;
        if (!empty($reference)) {
            $refNo = (int)explode('/', $reference->code)[0];
            $newReference->code = sprintf("%03d", $refNo + 1) . '/' . date('Y');
        } else {
            $newReference->code = sprintf("%03d", 1) . '/' . date('Y');
        }

        if ($newReference->save()) {
            return $newReference;
        };
        return null;
    }
    public static function getRefernceCode($id)
    {
        $transactionReference = TransactionReference::where('id', $id)->first();
        return $transactionReference->code;
    }
}
