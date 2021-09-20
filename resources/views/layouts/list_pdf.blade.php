<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <?php $fontFamily = getPdfFont(); ?>
    <link href="{{ $fontFamily['link'] }}" rel="stylesheet">
    @yield('pdf-title')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/dist/css/pdf/list_pdf.min.css') }}">
    <style>
      body { font-family: {{ $fontFamily['name'] }}; }
    </style>
</head>

<body>
<div class="content-wraper">
    <div>
    <div>
      <table class="table">
        <tbody>
          <tr class="tbody-tr">
            @yield('header-info')
            <td class="td-off"></td>
            <td colspan="2" class="tbody-td">
                <div class="d-block pdf-logo-mb">
              @if(!empty($company_logo))
                @if(file_exists("public/uploads/companyPic/". $company_logo) == true)
                  <img src="{{ url('/') . '/public/uploads/companyPic/' . $company_logo}}" alt="{{ url('/') . 'public/uploads/companyPic/' . $company_logo}}" class="mt-1p5">
                @endif
              @endif
                </div>
              <div class="d-block">
                  <span class="company-name">{{ $company_name }}</span>
              </div>
              <div class="d-block">
                <span class="company-info">{{ $company_street }},  {{ $company_city }}</span>
                <span class="company-info">{{ $company_country_name }}, {{ $company_zipCode }}</span>
              </div>

            <div>
              <span class="company-info">
              {{ __('Web') }}: <a class="company-info-url" href="{{ url('/') }}">{{ url('/') }}</a>
              </span>
            </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="mt-30">
        @yield('list-table')
    </div>
</div>
</body>
</html>
