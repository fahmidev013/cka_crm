<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class IncomeExpenseCategory extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'category_type'];

    public function generalLedgers()
    {
        return $this->hasMany("App\Models\GeneralLedger", 'gl_account_id');
    }

    public function expense() 
    {
      return $this->hasOne("App\Models\Expense", 'income_expense_category_id');
    }
    
    public function deposit() 
    {
      return $this->hasOne("App\Models\Deposit", 'income_expense_category_id');
    }
}
