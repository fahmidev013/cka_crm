@extends('layouts.app')
@section('css')
    {{-- Data table --}}
    <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
    {{-- Select2  --}}
    <link rel="stylesheet" href="{{ asset('public/datta-able/plugins/select2/css/select2.min.css') }}">
@endsection
@section('content')
    <!-- Main content -->
    <div class="col-sm-12" id="group-settings-container">
        <div class="row">
            <div class="col-md-3">
                @include('layouts.includes.sub_menu')
            </div>
            <div class="col-md-9">
                <div class="card card-info">
                    <div class="card-header">
                        <h5><a href="{{ url('item-category') }}">{{ __('General Settings') }}</a> >> {{ __('Group') }}</h5>
                        <div class="card-header-right">
                            @if(Helpers::has_permission(Auth::user()->id, 'add_lead_source'))
                                <a href="javascript:void(0)" data-toggle="modal" data-target=".add-lead-status-mdl" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New Group') }}</a>
                            @endif

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row p-l-15">
                            <div class="table-responsive">
                                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive group_list_table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Group Name') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th width="5%">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($groupData as $data)
                                        <tr>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ mb_strlen($data->description) > 150 ? mb_substr($data->description, 0, 150) . "..." :  $data->description }}</td>
                                            <td>
                                                <?php
                                                $color = $data->status == 'active' ? '#28a745' : '#dc3545';
                                                ?>
                                                <span class="badge f-12 active_inactive_checking" style="color:{{ $color }} !important;">{{  $data->status  }}</span>
                                            </td>
                                            <td>
                                                @if(Helpers::has_permission(Auth::user()->id, 'edit_group') )


                                                    <a title="{{ __('Edit') }}" href="javascript:void(0)" class="btn btn-xs btn-primary edit_group" data-toggle="modal" data-target=".edit-group-mdl" id="{{$data->id}}"><span class="feather icon-edit"></span></a> &nbsp;
                                                @endif

                                                @if(Helpers::has_permission(Auth::user()->id, 'delete_group'))
                                                    <form method="POST" action="{{ url('groups/delete/'.$data->id) }}" id="delete-group-{{$data->id}}" accept-charset="UTF-8" class="display_inline">
                                                        {!! csrf_field() !!}
                                                        <button title="{{ __('Delete') }}" class="btn btn-xs btn-danger" data-id="{{$data->id}}" data-label="Delete" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ __('Delete Group') }}" data-message="{{ __('Are you sure you want to delete this Group?') }}">
                                                            <i class="feather icon-trash-2"></i>
                                                        </button>
                                                    </form>

                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary custom-btn-small" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="button" id="confirmDeleteSubmitBtn" data-task="" class="btn btn-danger custom-btn-small">{{ __('Submit') }}</button>
                        <span class="ajax-loading"></span>
                    </div>
                </div>
            </div>
        </div>

        <div id="add-group" class="add-lead-status-mdl modal fade display_none" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Add New') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('groups/save') }}" method="post" id="myform1" class="form-horizontal">
                            {!! csrf_field() !!}
                            <div class="form-group row">
                                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Name') }}</label>
                                <div class="col-sm-6">
                                    <input type="text" placeholder="{{ __('Group Name') }}" class="form-control" name="name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 control-label" for="inputDescription">{{ __('Description') }}</label>
                                <div class="col-sm-6">
                                    <textarea placeholder="{{ __('Description') }}" class="form-control" name="description"> </textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 control-label" for="inputStatus">{{ __('Status') }}</label>

                                <div class="col-sm-6">
                                    <select class="form-control js-example-basic-single-1" id="status" name="status">
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="inactive">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="btn_save" class="col-sm-3 control-label"></label>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary custom-btn-small float-right">{{ __('Submit') }}</button>
                                    <button type="button" class="btn btn-secondary custom-btn-small float-right" data-dismiss="modal">{{ __('Close') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="edit-group" class="modal fade edit-group-mdl display_none" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Edit Group') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('groups/update') }}" method="post" id="editGroup" class="form-horizontal">
                            {!! csrf_field() !!}
                            <div class="form-group row">
                                <label class="col-sm-3 control-label require" for="inputEmail3">{{ __('Name') }}</label>
                                <div class="col-sm-6">
                                    <input type="text" placeholder="{{ __('Group Name') }}" class="form-control" id="name" name="name">
                                    <span id="val_name" class="color_red"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 control-label" for="inputDescription">{{ __('Description') }}</label>
                                <div class="col-sm-6">
                                    <textarea placeholder="{{ __('Description') }}" class="form-control" name="description" id="description"> </textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 control-label" for="inputEmail3">{{ __('Status') }}</label>
                                <div class="col-sm-6">
                                    <select class="form-control js-example-basic-single-2" id="my_status" name="my_status">
                                        <option value="active">{{ __('Active') }}</option>
                                        <option value="inactive">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="group_id" id="group_id">
                            <div class="form-group row">
                                <label for="btn_save" class="col-sm-3 control-label"></label>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary custom-btn-small float-right">{{ __('Submit') }}</button>
                                    <button type="button" class="btn btn-secondary custom-btn-small float-right" data-dismiss="modal">{{ __('Close') }}</button>
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
    <script src="{{ asset('public/dist/plugins/DataTables-1.10.21/js/jquery.dataTablesCus.min.js') }}"></script>
    <script src="{{ asset('public/dist/plugins/Responsive-2.2.5/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/datta-able/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
    {!! translateValidationMessages() !!}
    <script src="{{ asset('public/dist/js/custom/general-settings.min.js') }}"></script>
@endsection
