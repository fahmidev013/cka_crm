<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class GeneralLedgerTransaction extends Model
{
    public $timestamps = false;

    public function reference()
    {
        return $this->belongsTo("App\Models\Reference", 'reference_id');
    }

    public function incomeExpenseCategory()
    {
        return $this->belongsTo("App\Models\IncomeExpenseCategory", 'gl_account_id');
    }
}
