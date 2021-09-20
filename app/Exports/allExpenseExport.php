<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class allExpenseExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
      $to         = $_GET['to'];
      $from       = $_GET['from'];
      $categoryId = $_GET['categoryName'];
      $methodId   = $_GET['methodName'];
        $expense = Expense::query();
        if ($from) {
        $expense->where('transaction_date', '>=', DbDateFormat($from));
      }
      if ($to) {
        $expense->where('transaction_date', '<=', DbDateFormat($to));
      }
      if ($categoryId && $categoryId != 'all') {
        $expense->where('expenses.income_expense_category_id', $categoryId);
      }
      if ($methodId && $methodId != 'all') {
        $expense->where('expenses.payment_method_id', $methodId);
      }
      return $expense;
       
    }

    public function headings(): array
    {
        return [
            'Category',
            'Payment Method',
            'Description',
            'Amount',
            'Currency',
            'Date'
        ];
    }

    public function map($expense): array
    {
        return [
            $expense->incomeExpenseCategory->name,
            isset($expense->paymentMethod->name) ? $expense->paymentMethod->name : '',
            $expense->note,
            formatCurrencyAmount($expense->amount),
            $expense->incomeExpenseCurrency->name,
            formatDate($expense->transaction_date),
        ];
    }
}
