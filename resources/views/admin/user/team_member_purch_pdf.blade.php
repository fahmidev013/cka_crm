@extends('layouts.list_pdf')

@section('pdf-title')
<title>{{ __('Team Member Purchases') }}</title>
@endsection

@section('header-info')
<td colspan="2" class="tbody-td">
    <p class="title">
      <span class="title-text"></span><strong>{{ __('Team Member Lists') }}</strong>
    </p>
    <p class="title">
      <span class="title-text">{{ __('User:') }} </span>{{ isset($teamMembers) ? $teamMembers->full_name : __('All') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Supplier:') }} </span>{{ isset($suppliers) ? $suppliers->name : __('All') }}
    </p>
    <p class="title">
      <span class="title-text">{{ __('Location:') }} </span>{{ isset($locations) ? $locations->name : __('All') }}
    </p>
    @if (isset($date_range) && !empty($date_range))
        <p class="title">
          <span class="title-text">{{ __('Period:') }} </span> {{ $date_range }}
        </p>
    @endif
    <p class="title">
      <span class="title-text">{{ __('Print Date:') }} </span> {{ formatDate(date('d-m-Y')) }}
    </p>
</td>
@endsection

@section('list-table')
<table class="list-table">
    <thead class="list-head">
        <tr>
          <td class="text-center list-th"> {{ __('Purchase No') }} #</td>
          <td class="text-center list-th"> {{ __('Supplier Name') }}</td>
          <td class="text-center list-th"> {{ __('Total') }}</td>
          <td class="text-center list-th"> {{ __('Paid Amount') }}</td>
          <td class="text-center list-th"> {{ __('Currency') }}</td>
          <td class="text-center list-th"> {{ __('Status') }}</td>
          <td class="text-center list-th"> {{ __('Date') }}</td>
        </tr>
    </thead>
    <?php $total_amount = 0; ?>
    @foreach($purchData as $data) 
    <?php $total_amount += $data->total; ?>
    <tr>
      <td class="text-center list-td"> {{ $data->reference }} </td>
      <td class="text-center list-td"> {{ $data->name }} </td>
      <td class="text-center list-td"> {{ formatCurrencyAmount($data->total) }} </td>
      <td class="text-center list-td"> {{ formatCurrencyAmount($data->paid) }} </td>
      <td class="text-center list-td"> {{ $data->currency_name }} </td>
      <td class="text-center list-td"> 
        <?php 
          if ($data->paid <= 0 && $data->total != 0) {
            $status = __('Unpaid');
          } else if ($data->paid > 0 && $data->total > $data->paid) {
            $status = __('Partially Paid');
          } else if ($data->total <= $data->paid) {
            $status = __('Paid');
          }
        ?>
        {{ $status }}
       </td>
      <td class="text-center list-td"> {{ formatDate($data->order_date) }} </td>
    </tr>
    @endforeach
</table>
@endsection
