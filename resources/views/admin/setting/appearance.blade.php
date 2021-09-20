@extends('layouts.app')
@section('css')
  <link rel="stylesheet" href="{{ asset('public/datta-able/css/theme-style.css') }}">
@endsection

@section('content')
  <!-- Main content -->
<div class="col-sm-12" id="appearance-settings-container">
  <div class="row">
    <div class="col-sm-3">
      @include('layouts.includes.preference_menu')
    </div>
    <div class="col-sm-9">
      <div class="card">
        <div class="card-header">
          <h5>{{ __('Theme Preferences') }}</h5>
          <div class="card-header-right">
            <a href="#" class="btn btn-outline-danger custom-btn-small reset_defaults" id="reset-appearance">{{ __('Reset to Default') }}</a>
          </div>
        </div>
        <div class="card-block table-border-style">
        <form action="{{ url('save-appearance') }}" method="post" id="myform1" class="form-horizontal">
        {!! csrf_field() !!}
          <div class="card-body">
            

          <div class="form-group row">
            <div class="col-sm-12">
              <h6>{{ __('Theme Mode') }}</h6>
              <hr>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="theme_mode" id="default_theme" value="default" @isset($appear['theme_mode']) {{ $appear['theme_mode'] == 'default' ? 'checked' : ''}} @endisset>
                    <label for="default_theme" class="cr">{{ __('Default Theme') }}</label>
                    <div class="theme-color layout-type pl-5">
                      <a href="#!" data-value="menu-dark"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="theme_mode" id="light_theme" value="menu-light" @isset($appear['theme_mode']) {{ $appear['theme_mode'] == 'menu-light' ? 'checked' : ''}} @endisset>
                    <label for="light_theme" class="cr">{{ __('Light Theme') }}</label>
                    <div class="theme-color layout-type pl-5">
                      <a href="#!" data-value="menu-light"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="theme_mode" id="dark_theme" value="navbar-dark brand-dark" @isset($appear['theme_mode']) {{ $appear['theme_mode'] == 'navbar-dark brand-dark' ? 'checked' : ''}} @endisset>
                    <label for="dark_theme" class="cr">{{ __('Dark Theme') }}</label>
                    <div class="theme-color layout-type pl-5">
                      <a href="#!" data-value="dark"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12">
              <h6>{{ __('Header Background') }}</h6>
              <hr>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="header_background" id="default_header" value="default" @isset($appear['header_background']) {{ $appear['header_background'] == 'default' ? 'checked' : '' }} @endisset>
                    <label for="default_header" class="cr">{{ __('Default Header') }}</label>
                    <div class="theme-color header-color pl-5">
                      <a href="#!" data-value="header-default"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="header_background" id="blue_header" value="header-blue" @isset($appear['header_background']) {{ $appear['header_background'] == 'header-blue' ? 'checked' : '' }} @endisset>
                    <label for="blue_header" class="cr">{{ __('Blue Header') }}</label>
                    <div class="theme-color header-color pl-5">
                      <a href="#!" data-value="header-blue"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="header_background" id="red_header" value="header-red" @isset($appear['header_background']) {{ $appear['header_background'] == 'header-red' ? 'checked' : '' }} @endisset>
                    <label for="red_header" class="cr">{{ __('Red Header') }}</label>
                    <div class="theme-color header-color pl-5">
                      <a href="#!" data-value="header-red"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="header_background" id="purple_header" value="header-purple" @isset($appear['header_background']) {{ $appear['header_background'] == 'header-purple' ? 'checked' : '' }} @endisset>
                    <label for="purple_header" class="cr">{{ __('Purple Header') }}</label>
                    <div class="theme-color header-color pl-5">
                      <a href="#!" data-value="header-purple"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="header_background" id="lightblue_header" value="header-lightblue" @isset($appear['header_background']) {{ $appear['header_background'] == 'header-lightblue' ? 'checked' : '' }} @endisset>
                    <label for="lightblue_header" class="cr">{{ __('Lightblue Header') }}</label>
                    <div class="theme-color header-color pl-5">
                      <a href="#!" data-value="header-lightblue"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="header_background" id="dark_header" value="header-dark" @isset($appear['header_background']) {{ $appear['header_background'] == 'header-dark' ? 'checked' : '' }} @endisset>
                    <label for="dark_header" class="cr">{{ __('Dark Header') }}</label>
                    <div class="theme-color header-color pl-5">
                      <a href="#!" data-value="header-dark"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12">
              <h6>{{ __('Menu Background') }}</h6>
              <hr>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_background" id="default_menu" value="default" {{ (isset($appear['menu_background']) && $appear['menu_background'] == 'default') ? 'checked' : '' }}>
                    <label for="default_menu" class="cr">{{ __('Default Menu') }}</label>
                    <div class="theme-color navbar-color pl-5">
                      <a href="#!" data-value="navbar-default"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_background" id="blue_menu" value="navbar-blue" {{ (isset($appear['menu_background']) &&  $appear['menu_background'] == 'navbar-blue') ? 'checked' : '' }}>
                    <label for="blue_menu" class="cr">{{ __('Blue Menu') }}</label>
                    <div class="theme-color navbar-color pl-5">
                      <a href="#!" data-value="navbar-blue"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_background" id="red_menu" value="navbar-red" {{ $appear['menu_background'] == 'navbar-red' ? 'checked' : '' }}>
                    <label for="red_menu" class="cr">{{ __('Red Menu') }}</label>
                    <div class="theme-color navbar-color pl-5">
                      <a href="#!" data-value="navbar-red"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_background" id="purple_menu" value="navbar-purple" {{ $appear['menu_background'] == 'navbar-purple' ? 'checked' : '' }}>
                    <label for="purple_menu" class="cr">{{ __('Purple Menu') }}</label>
                    <div class="theme-color navbar-color pl-5">
                      <a href="#!" data-value="navbar-purple"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_background" id="lightblue_menu" value="navbar-lightblue" {{ $appear['menu_background'] == 'navbar-lightblue' ? 'checked' : '' }}>
                    <label for="lightblue_menu" class="cr">{{ __('Lightblue Menu') }}</label>
                    <div class="theme-color navbar-color pl-5">
                      <a href="#!" data-value="navbar-lightblue"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_background" id="dark_menu" value="navbar-dark" {{ $appear['menu_background'] == 'navbar-dark' ? 'checked' : '' }}>
                    <label for="dark_menu" class="cr">{{ __('Dark Menu') }}</label>
                    <div class="theme-color navbar-color pl-5">
                      <a href="#!" data-value="navbar-dark"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12">
              <h6>{{ __('Menu Brand Color') }}</h6>
              <hr>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_brand_background" id="default_menu_brand" value="default" {{ $appear['menu_brand_background'] == 'default' ? 'checked' : '' }}>
                    <label for="default_menu_brand" class="cr">{{ __('Default Brand Color') }}</label>
                    <div class="theme-color brand-color pl-5">
                      <a href="#!" data-value="brand-default"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_brand_background" id="blue_menu_brand" value="brand-blue" {{ $appear['menu_brand_background'] == 'brand-blue' ? 'checked' : '' }}>
                    <label for="blue_menu_brand" class="cr">{{ __('Blue Brand Color') }}</label>
                    <div class="theme-color brand-color pl-5">
                      <a href="#!" data-value="brand-blue"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_brand_background" id="red_menu_brand" value="brand-red" {{ $appear['menu_brand_background'] == 'brand-red' ? 'checked' : '' }}>
                    <label for="red_menu_brand" class="cr">{{ __('Red Brand Color') }}</label>
                    <div class="theme-color brand-color pl-5">
                      <a href="#!" data-value="brand-red"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_brand_background" id="purple_menu_brand" value="brand-purple" {{ $appear['menu_brand_background'] == 'brand-purple' ? 'checked' : '' }}>
                    <label for="purple_menu_brand" class="cr">{{ __('Purple Brand Color') }}</label>
                    <div class="theme-color brand-color pl-5">
                      <a href="#!" data-value="brand-purple"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_brand_background" id="lightblue_menu_brand" value="brand-lightblue" {{ $appear['menu_brand_background'] == 'brand-lightblue' ? 'checked' : '' }}>
                    <label for="lightblue_menu_brand" class="cr">{{ __('Lightblue Brand Color') }}</label>
                    <div class="theme-color brand-color pl-5">
                      <a href="#!" data-value="brand-lightblue"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_brand_background" id="dark_menu_brand" value="brand-dark" {{ $appear['menu_brand_background'] == 'brand-dark' ? 'checked' : '' }}>
                    <label for="dark_menu_brand" class="cr">{{ __('Dark Brand Color') }}</label>
                    <div class="theme-color brand-color pl-5">
                      <a href="#!" data-value="brand-dark"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12">
              <h6>{{ __('Menu Item Active Color') }}</h6>
              <hr>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_item_color" id="default_menu_item" value="default" {{ $appear['menu_item_color'] == 'default' ? 'checked' : '' }}>
                    <label for="default_menu_item" class="cr">{{ __('Default Color') }}</label>
                    <div class="theme-color active-color small pl-5">
                      <a href="#!" data-value="active-default"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_item_color" id="blue_menu_item" value="active-blue" {{ $appear['menu_item_color'] == 'active-blue' ? 'checked' : '' }}>
                    <label for="blue_menu_item" class="cr">{{ __('Blue Color') }}</label>
                    <div class="theme-color active-color small pl-5">
                      <a href="#!" data-value="active-blue"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_item_color" id="red_menu_item" value="active-red" {{ $appear['menu_item_color'] == 'active-red' ? 'checked' : '' }}>
                    <label for="red_menu_item" class="cr">{{ __('Red Color') }}</label>
                    <div class="theme-color active-color small pl-5">
                      <a href="#!" data-value="active-red"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_item_color" id="purple_menu_item" value="active-purple" {{ $appear['menu_item_color'] == 'active-purple' ? 'checked' : '' }}>
                    <label for="purple_menu_item" class="cr">{{ __('Purple Color') }}</label>
                    <div class="theme-color active-color small pl-5">
                      <a href="#!" data-value="active-purple"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_item_color" id="lightblue_menu_item" value="active-lightblue" {{ $appear['menu_item_color'] == 'active-lightblue' ? 'checked' : '' }}>
                    <label for="lightblue_menu_item" class="cr">{{ __('Lightblue Color') }}</label>
                    <div class="theme-color active-color small pl-5">
                      <a href="#!" data-value="active-lightblue"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="menu_item_color" id="dark_menu_item" value="active-dark" {{ $appear['menu_item_color'] == 'active-dark' ? 'checked' : '' }}>
                    <label for="dark_menu_item" class="cr">{{ __('Dark Color') }}</label>
                    <div class="theme-color active-color small pl-5">
                      <a href="#!" data-value="active-dark"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12">
              <h6>{{ __('Navbar Image') }}</h6>
              <hr>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="navbar_image" id="navbar_image_none" value="default" {{ $appear['navbar_image'] == 'default' ? 'checked' : '' }}>
                    <label for="navbar_image_none" class="cr">{{ __('None') }}</label>
                    <div class="theme-color navbar-images pl-5">
                      <a href="#!" data-value="navbar-image-0"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="navbar_image" id="navbar_image_1" value="navbar-image-1" {{ $appear['navbar_image'] == 'navbar-image-1' ? 'checked' : '' }}>
                    <label for="navbar_image_1" class="cr">{{ __('Image 1') }}</label>
                    <div class="theme-color navbar-images pl-5">
                      <a href="#!" data-value="navbar-image-1"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="navbar_image" id="navbar_image_2" value="navbar-image-2" {{ $appear['navbar_image'] == 'navbar-image-2' ? 'checked' : '' }}>
                    <label for="navbar_image_2" class="cr">{{ __('Image 2') }}</label>
                    <div class="theme-color navbar-images pl-5">
                      <a href="#!" data-value="navbar-image-2"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="navbar_image" id="navbar_image_3" value="navbar-image-3" {{ $appear['navbar_image'] == 'navbar-image-3' ? 'checked' : '' }}>
                    <label for="navbar_image_3" class="cr">{{ __('Image 3') }}</label>
                    <div class="theme-color navbar-images pl-5">
                      <a href="#!" data-value="navbar-image-3"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="navbar_image" id="navbar_image_4" value="navbar-image-4" {{ $appear['navbar_image'] == 'navbar-image-4' ? 'checked' : '' }}>
                    <label for="navbar_image_4" class="cr">{{ __('Image 4') }}</label>
                    <div class="theme-color navbar-images pl-5">
                      <a href="#!" data-value="navbar-image-4"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4">
                  <div class="radio radio-primary d-inline">
                    <input type="radio" name="navbar_image" id="navbar_image_5" value="navbar-image-5" {{ $appear['navbar_image'] == 'navbar-image-5' ? 'checked' : '' }}>
                    <label for="navbar_image_5" class="cr">{{ __('Image 5') }}</label>
                    <div class="theme-color navbar-images pl-5">
                      <a href="#!" data-value="navbar-image-5"><span></span><span></span></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12">
              <h6>{{ __('Theme Layout') }}</h6>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <div class="switch switch-primary d-inline m-r-10">
                    <input type="checkbox" name="menu-icon-colored" id="menu-icon-colored" value="icon-colored" @isset($appear['menu-icon-colored']) {{ $appear['menu-icon-colored'] == 'icon-colored' ? 'checked' : '' }} @endisset>
                    <label for="menu-icon-colored" class="cr"></label>
                  </div>
                  <label for="menu-icon-colored">{{ __('Icon Color') }}</label>
                </div>
                <div class="col-sm-3">
                  <div class="switch switch-primary d-inline m-r-10">
                    <input type="checkbox" name="menu-fixed" id="menu-fixed" value="menupos-static" @isset($appear['menu-fixed']) {{ $appear['menu-fixed'] == 'menupos-static' ? 'checked' : '' }} @endisset>
                    <label for="menu-fixed" class="cr"></label>
                  </div>
                  <label for="menu-fixed">{{ __('Menu Fixed') }}</label>
                </div>
                <div class="col-sm-3">
                  <div class="switch switch-primary d-inline m-r-10">
                    <input type="checkbox" name="header-fixed" id="header-fixed" value="headerpos-fixed" @isset($appear['header-fixed']) {{ $appear['header-fixed'] == 'headerpos-fixed' ? 'checked' : '' }} @endisset>
                    <label for="header-fixed" class="cr"></label>
                  </div>
                  <label for="header-fixed">{{ __('Head Fixed') }}</label>
                </div>
                <div class="col-sm-3">
                  <div class="switch switch-primary d-inline m-r-10">
                    <input type="checkbox" name="box-layout" id="box-layout" value="container box-layout" @isset($appear['box-layout']) {{ $appear['box-layout'] == 'container box-layout' ? 'checked' : '' }} @endisset>
                    <label for="box-layout" class="cr"></label>
                  </div>
                  <label for="box-layout">{{ __('Box Layout') }}</label>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12">
              <h6>{{ __('Menu List Icon') }}</h6>
              <hr>
              <div class="row">
                <div class="theme-color pl-3">
                  <div class="form-group d-inline">
                    <div class="radio radio-primary d-inline col-sm-4 responsive-icon-1">
                      <input type="radio" name="menu_list_icon" id="menu_list_icon-1" value="default" @isset($appear['menu_list_icon']) {{ $appear['menu_list_icon'] == 'default' ? 'checked' : '' }} @endisset>
                      <label for="menu_list_icon-1" class="cr"><i class=" ">{{ __('None') }}</i>     </label>
                    </div>
                  </div>
                  <div class="form-group d-inline  col-sm-4 responsive-icon-2">
                    <div class="radio radio-primary d-inline">
                      <input type="radio" name="menu_list_icon" id="menu_list_icon-2" value="menu-item-icon-style2" @isset($appear['menu_list_icon']) {{ $appear['menu_list_icon'] == 'menu-item-icon-style2' ? 'checked' : '' }} @endisset>
                      <label for="menu_list_icon-2" class="cr"><i class="feather icon-minus"></i></label>
                    </div>
                  </div>
                  <div class="form-group d-inline  col-sm-4 responsive-icon-3">
                    <div class="radio radio-primary d-inline">
                      <input type="radio" name="menu_list_icon" id="menu_list_icon-3" value="menu-item-icon-style3" @isset($appear['menu_list_icon']) {{ $appear['menu_list_icon'] == 'menu-item-icon-style3' ? 'checked' : '' }} @endisset>
                      <label for="menu_list_icon-3" class="cr"><i class="feather icon-check"></i></label>
                    </div>
                  </div>
                  <div class="form-group d-inline  col-sm-4 responsive-icon-4">
                    <div class="radio radio-primary d-inline">
                      <input type="radio" name="menu_list_icon" id="menu_list_icon-4" value="menu-item-icon-style4" @isset($appear['menu_list_icon']) {{ $appear['menu_list_icon'] == 'menu-item-icon-style4' ? 'checked' : '' }} @endisset>
                      <label for="menu_list_icon-4" class="cr"><i class="icon feather icon-corner-down-right"></i></label>
                    </div>
                  </div>
                  <div class="form-group d-inline  col-sm-4 responsive-icon-5">
                    <div class="radio radio-primary d-inline">
                      <input type="radio" name="menu_list_icon" id="menu_list_icon-5" value="menu-item-icon-style5" @isset($appear['menu_list_icon']) {{ $appear['menu_list_icon'] == 'menu-item-icon-style5' ? 'checked' : '' }} @endisset>
                      <label for="menu_list_icon-5" class="cr"><i class="icon feather icon-chevrons-right"></i></label>
                    </div>
                  </div>
                  <div class="form-group d-inline  col-sm-4 responsive-icon-6">
                    <div class="radio radio-primary d-inline">
                      <input type="radio" name="menu_list_icon" id="menu_list_icon-6" value="menu-item-icon-style6" @isset($appear['menu_list_icon']) {{ $appear['menu_list_icon'] == 'menu-item-icon-style6' ? 'checked' : '' }} @endisset>
                      <label for="menu_list_icon-6" class="cr"><i class="icon feather icon-chevron-right"></i></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-sm-12">
              <h6>{{ __('Menu Dropdown Icon') }}</h6>
              <hr>
              <div class="row">
                <div class="theme-color pl-3">
                  <div class="form-group d-inline col-sm-4 zero_padding">
                    <div class="radio radio-primary d-inline">
                      <input type="radio" name="menu_dropdown_icon" id="dropdown_icon-1" value="default" @isset($appear['menu_dropdown_icon']){{ $appear['menu_dropdown_icon'] == 'default' ? 'checked' : '' }}@endisset>
                      <label for="dropdown_icon-1" class="cr"><i class="feather icon-chevron-right"></i></label>
                    </div>
                  </div>
                  <div class="form-group d-inline col-sm-4">
                    <div class="radio radio-primary d-inline">
                      <input type="radio" name="menu_dropdown_icon" id="dropdown_icon-2" value="drp-icon-style2" @isset($appear['menu_dropdown_icon']){{ $appear['menu_dropdown_icon'] == 'drp-icon-style2' ? 'checked' : '' }}@endisset>
                      <label for="dropdown_icon-2" class="cr"><i class="feather icon-chevrons-right"></i></label>
                    </div>
                  </div>
                  <div class="form-group d-inline col-sm-4 icon-3">
                    <div class="radio radio-primary d-inline">
                      <input type="radio" name="menu_dropdown_icon" id="dropdown_icon-3" value="drp-icon-style3" @isset($appear['menu_dropdown_icon']){{ $appear['menu_dropdown_icon'] == 'drp-icon-style3' ? 'checked' : '' }}@endisset>
                      <label for="dropdown_icon-3" class="cr"><i class="feather icon-plus"></i></label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="col-sm-12">
              <div class="no-padding float-left">
                <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Submit') }}</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    </div>
  </div>
</div>

@endsection
@section('js')
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('public/dist/js/custom/preference.min.js') }}"></script>
@endsection