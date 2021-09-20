@extends('layouts.app')
@section('css')
    {{-- Data table --}}
    <link rel="stylesheet" href="{{ asset('public/dist/plugins/Responsive-2.2.5/css/responsive.dataTables.css') }}">
@endsection
@section('content')
    <!-- Main content -->
    <div class="col-sm-12" id="canned-message-settings-container">
        <div class="row">
            <div class="col-md-3">
                @include('layouts.includes.sub_menu')
            </div>
            <div class="col-md-9">
                <div class="card card-info">
                    <div class="card-header">
                        <h5><a href="{{ url('item-category') }}">{{ __('General Settings') }}</a> >> {{ __('Canned Message') }}</h5>
                        <div class="card-header-right">
                            @if(Helpers::has_permission(Auth::user()->id, 'add_canned_message'))
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-canned" class="btn btn-outline-primary custom-btn-small"><span class="fa fa-plus"> &nbsp;</span>{{ __('New') }} {{ __('Message') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row p-l-15">
                            <div class="table-responsive">
                                <table id="dataTableBuilder" class="table table-bordered table-hover table-striped dt-responsive canned_list_table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Message') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th width="5%">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($cannedMessageData as $data)
                                        <tr>
                                            <td>{{ $data->title }}</td>
                                            <td>{{ strlen($data->message) < 150 ? strip_tags($data->message) : substr(strip_tags($data->message), 0, 150)."..." }}</td>
                                            <td>{{formatDate($data->created_at)}}</td>
                                            <td>
                                                @if(Helpers::has_permission(Auth::user()->id, 'edit_canned_message'))
                                                <a title="{{ __('Edit') }}" href="javascript:void(0)" class="btn btn-xs btn-primary edit_canned_message" data-toggle="modal" data-target=".edit-canned-message-mdl" id="{{$data->id}}"><span class="feather icon-edit"></span></a>
                                                @endif
                                                @if(Helpers::has_permission(Auth::user()->id, 'delete_canned_message'))
                                                    <form method="POST" action="{{ url('canned/messages/delete/'. $data->id) }}" id="delete-canned-{{ $data->id }}" accept-charset="UTF-8" class="display_inline">
                                                        {!! csrf_field() !!}
                                                        <button title="{{ __('Delete') }}" class="btn btn-xs btn-danger" data-id="{{ $data->id }}" data-label="Delete" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{ __('Delete Canned Message') }}" data-message="{{ __('Are you sure you want to delete this Message?') }}">
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
        {{-- Canned Message Modal Start--}}
        <div class="modal fade" id="modal-canned"  role="dialog">
            <div class="modal-dialog">
                <form  method="POST" action="" id="cannedForm">
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ __('Canned Message') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input  type="text" name="title" id="title" class="form-control" placeholder="{{ __('Provide Title') }}">
                                <label id="title-error" class="error" for="title"></label>
                            </div>
                            <div class="form-group">
                                <textarea  id="txtAreaCanned" name="canned_message" class="ticket_message form-control"></textarea>
                                <label id="txtAreaCanned-error" class="error" for="txtAreaCanned"></label>
                                <label id="reply-modal-error" class="error" for="reply-modal"></label>
                            </div>
                        </div>
                        <input type="hidden" name="type" id="type" value="list">
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary custom-btn-small">{{ __('Save') }}</button>
                            <button type="button" class="btn btn-danger custom-btn-small" data-dismiss="modal">{{ __('Cancel') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="edit-canned-message" class="modal fade edit-canned-message-mdl display_none" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Edit') }} {{ __('Canned Message') }}</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="editMessageForm" class="form-horizontal">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                 <input type="text" placeholder="{{ __('Provide Title') }}" class="form-control" id="edit_title" name="title">
                                 <label id="edit_title-error" class="error" for="edit_title"></label>
                            </div>
                            <div class="form-group">
                                 <textarea  id="txtAreaCannedEdit" name="canned_message" class="txtAreaCanned ticket_message form-control"></textarea>
                                 <label id="txtAreaCannedEdit-error" class="error" for="txtAreaCannedEdit"></label>
                            </div>
                            <input type="hidden" name="canned_id" id="canned_id">
                            <div class="modal-footer">
                                  <button type="submit" class="btn btn-primary custom-btn-small float-right">{{ __('Update') }}</button>
                                  <button type="button" class="btn btn-secondary custom-btn-small float-right" data-dismiss="modal">{{ __('Close') }}</button>
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
    <script src="{{ asset('public/dist/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('public/datta-able/plugins/ckeditor/js/ckeditor.js') }}"></script>
    <script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>
    {!! translateValidationMessages() !!}
    <script src="{{ asset('public/dist/js/custom/canned.min.js') }}"></script>
    <script src="{{ asset('public/dist/js/custom/general-settings.min.js') }}"></script>
@endsection
