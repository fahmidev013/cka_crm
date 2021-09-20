@extends('layouts.list_pdf')
@section('pdf-title')
<title>{{ __('Expense list') }}</title>
@endsection
@section('header-info')
  <td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text">{{ __('Expense List') }}</span>
    </p>
    <p class="title">
      <span class="title-text">{{ __('Category') }}: </span>{{ $categoryName }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Payment Method') }}: </span>{{ $paymentMethod }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Period') }}: </span> {{ $date_range }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Print Date') }}: </span>{{ formatDate(date('d-m-Y')) }}
    </p>
  </td>
@endsection

@section('list-table')
  <table class="list-table">
    <thead class="list-head">
        <tr>   
          <td class="text-center list-th">{{ __('Category') }}</td>
          <td class="text-center list-th">{{ __('Payment method') }}</td>
          <td class="text-center list-th">{{ __('Description') }}</td>
          <td class="text-center list-th">{{ __('Amount') }}</td>
          <td class="text-center list-th">{{ __('Currency') }}</td>
          <td class="text-center list-th">{{ __('Date') }}</td>
        </tr>
     </thead>   
        <?php $total_amount = 0; ?>
        @foreach($expenseList as $data)
        <tr>
              <td class="text-center list-td">{{ $data->incomeExpenseCategory->name }}</td>
              <td class="text-center list-td">{{ isset($data->paymentMethod->name) ? $data->paymentMethod->name : '-' }}</td>
              <td class="text-center list-td">{{ $data->note }}</td>
              <td class="text-center list-td">{{ formatCurrencyAmount(abs($data->amount), 2, '.', ',') }}</td>
              <td class="text-center list-td">{{ $data->incomeExpenseCurrency->name }}</td>      
              <td class="text-center list-td">{{ formatDate($data->transaction_date) }}</td>      
        </tr>
        @endforeach 
    </table>
@endsection