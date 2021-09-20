{!! translateValidationMessages() !!}
<script type="text/javascript" src="{{ asset('public/dist/js/html5lightbox/html5lightbox.js?v=1.0') }}"></script>
<script src="{{ asset('public/datta-able/plugins/sweetalert/js/sweetalert.min.js') }}"></script>

<script type="text/javascript">
   'use strict';
    let global_start_date;
    let global_due_date;

    var dateFormat = '{!! $date_format_type !!}';
    dateFormat = dateFormat.replace(/M/g, "MMM").toUpperCase();
    var dateTimeFormat = 'YYYY-MM-DD H:mm:s';

    $('#start_time').daterangepicker({
      startDate: moment().format(dateTimeFormat),
      "timePicker": true,
      "timePicker24Hour": true,
      "singleDatePicker": true,
      "locale": { "format": dateTimeFormat },
    }, function(start, end){
      var startDateTime        = moment(start, 'MMMM D, YYYY').format(dateTimeFormat);
      $('#start_time').val(startDateTime);
    });

    $('#end_time').daterangepicker({
      startDate: moment().format(dateTimeFormat),
      "timePicker": true,
      "timePicker24Hour": true,
      "singleDatePicker": true,
      "locale": { "format": dateTimeFormat },
    }, function(start, end){
      var startDateTime        = moment(start, 'MMMM D, YYYY').format(dateTimeFormat);
      $('#end_time').val(startDateTime);
    });

    $(".js-example-responsive").select2({
      tags: true,
    });

    // get task all tag
    $('#dataTableBuilder, .task-v-preview').on( 'click', '.task_class', function () {
      $(".js-example-responsive").val('');
      $('#comments_error').hide();
      var task_id = $(this).attr('data-id');
      var url   = "{{ url('project/task/get-tag') }}";
      $.ajax({
        url: url,
        method: "GET",
        data: {'task_id': task_id},
        success: function(data) {
          var html = '';
          $.each(data, function(key, value) {
            html += "<option selected='selected' value='"+value+"'>"+value+"</option>"; 
          })
          $('#tags').append(html);
          $('#tags').select2().trigger('change');
          $('#tag_area .select2-container--default').not(":first").hide();
          $(".js-example-responsive").select2({
            tags: true,
          });
        }
      });
    });  

    // Add Tag      
    $('.js-example-responsive').on('change', function() {
      var value = $(this).val();
      var tag_name = value[value.length-1];
      var task_id = $('#assignee_dropdown').attr('task_id');
      var url   = "{{url('project/task/store-tag')}}";
      var token = "{!!csrf_token()!!}";
        $.ajax({
          url:url,
          method:"POST",
          data:{'tag_name':tag_name, 'task_id':task_id, '_token':token},
          dataType:"json",
          success:function(data) {
            $('#tags').select2().trigger('change');
            $(".js-example-responsive").select2({
              tags: true,
            });
          }
        });
      });
             
    // Delete Tag
    $(document).on("click", '.select2-selection__choice__remove', function(e) {
      var string = $(this).parent().text();
      var tag_name = string.substring(1, string.length);
      var task_id = $('#assignee_dropdown').attr('task_id');
      var url   = "{{ url('project/task/delete-tag') }}";
      var token = "{!! csrf_token() !!}";
      $.ajax({
        url:url,
        method:"POST",
        data:{
          'tag_name':tag_name, 
          'task_id':task_id, 
          '_token':token
        },
        dataType:"json",
        success:function(data) {

        }
      });
    });

    function getStatus(status_id, project_id, task_id){
      var url   = "{{url('project/task/get-status')}}";
      $.ajax({
        url:url,
        method:"GET",
        data:{'status_id':status_id, 'project_id': project_id, 'task_id' : task_id},
        success:function(data){
          $('#all_status').html(data.output);
          $('#task_status').popover({
            html : true,
            content: function() {
              return $('#all_status').html();
            }
          });
          $("#dataTableBuilder").DataTable().ajax.reload( null, false );;
        }
      });
    }


    function getTimer(task_id) {
      $.ajax({
        url: "{{url('task_timer/check')}}",
        type:'post',
        data:{ '_token': "{{ csrf_token() }}",  'task_id':task_id},
        dataType:'json',

        success:function(data) {
          if (data.timer) {
            if (!data.timer.end_time) {
              $('.timer').attr('data-timer-id',data.timer.id);
              $('.timer').removeClass('btn-success');
              $('.timer').addClass('btn-danger');
              $('#timer_text').text("{{ __('Stop Timer') }}");
              $('#timer_text').attr('data-input','note_att');
            } else {
              $('#timer_text').text("{{ __('Start Timer') }}");
              $('.timer').removeClass('btn-danger');
              $('.timer').addClass('btn-success'); 
              $('.note-submit').attr('timer_id', data.timer.id);
              $('#timer_text').removeAttr('data-input');
            }
          } else {
            $('#timer_text').text("{{ __('Start Timer') }}");
            $('.timer').removeClass('btn-danger');
            $('.timer').addClass('btn-success'); 
            $('#timer_text').removeAttr('data-input');
          }
        }
      });
    }

    // Get all priority without select priority 
    function getPriority(priority_id, project_id, task_id) {
      var url   = "{{url('project/task/get-priority')}}";
      $.ajax({
        url:url,
        method:"GET",
        data:{'priority_id':priority_id, 'project_id': project_id, 'task_id' : task_id},
        success:function(data){
          $('#all_priority').html(data.output);
          $('#priority').popover({
            html : true,
            content: function() {
              return $('#all_priority').html();
            }
          });
        }
      });
    }

    // Change Status
    $(document).on('click',".status_change",function () {
      var status_id = $(this).attr('data-id');
      if (status_id != '') {
        if (status_id == 4) {
          unmark_check();
          $('#task_status').html('Complete');
          $('#task_status').attr('data-rel', '4');
        } else {
          mark_check();
          $('#task_status').html('Not Started');
          $('#task_status').attr('data-rel', '1');
        }
      }
      var status_name = $(this).attr('data-value');
      var project_id = $(this).attr('project_id');
      var task_id = $(this).attr('task_id');
      var url   = "{{url('project/task/change-status')}}";
      var token = "{!!csrf_token()!!}";
      $.ajax({
        url:url,
        method:"POST",
        data:{'status_id':status_id, 'task_id':task_id,'project_id':project_id, '_token':token},
        dataType:"json",
        success:function(data) {
          if (data.status==1) {
            var previousTotal = parseInt($('#'+ data.preStatusName).html()) - parseInt(1);
            var newTotal = parseInt($('#'+ data.newStatusName).html()) + parseInt(1);
            $('#'+ data.preStatusName).html(previousTotal);
            $('#'+ data.newStatusName).html(newTotal);
            $("#dataTableBuilder").DataTable().ajax.reload(null, false);
          } else {
            $('#task_status').html();
          }
        }
      });
    });

    // Change Priority
    $(document).on('click',".priority_change",function () {
      var priority_id = $(this).attr('data-id');
      var project_id = $(this).attr('project_id');
      var priority_name = $(this).attr('data-value');
      var task_id = $(this).attr('task_id');
      var url   = "{{url('project/task/change-priority')}}";
      var token = "{!!csrf_token()!!}";
      $.ajax({
        url:url,
        method:"POST",
        data:{'priority_id':priority_id, 'task_id':task_id,'project_id':project_id, '_token':token},
        dataType:"json",
        success:function(data) {
          if (data.status == 1) {
            $('.popover-dismiss').popover({
              trigger: 'focus'
            });
            // load all priority without selested priority again
            getPriority(priority_id,project_id, task_id);
          } else {
            $('#priority').html();
          }
          $("#dataTableBuilder").DataTable().ajax.reload( null, false );
        }
      });
    });

    // Load Modal Data 
    $('#dataTableBuilder, .task-v-preview').on( 'click', '.task_class', function () {
      $('#comments_error').hide();
      var priority_id = $(this).attr('data-priority-id');
      var status_id  = $(this).attr('data-status-id');
      var project_id = $(this).attr('project_id');
      var task_id    = $(this).attr('data-id');
      getStatus(status_id, project_id, task_id);
      getTimer(task_id);
      getPriority(priority_id, project_id, task_id);
      $("#btn_comment").removeAttr("disabled");
      $("#comment_text").text("{{ __('Add comment') }}");
      $(".spinner").hide();
      $("#related_div").removeAttr("style");
      // Loading
      $('#files, #comments_area, #all_assignee, #task-details, #checklist_items').html('<img class="task_loading" src="'+SITE_URL+'/public/dist/img/loader/spiner.gif" />');
      $('#subject, #related_to').html('<img class="ml-36" src="'+SITE_URL+'/public/dist/img/loader/bar.gif" />');
      
      var task_id = $(this).attr('data-id');
      $("#task_id").val(task_id);
      $.ajax({
        url: '{{ URL::to("project/task/view")}}',
        data:{
          id:task_id
        },
        type: 'GET',
        dataType: 'JSON',
        success: function (data) {
          var today   = new Date().toISOString().slice(0, 10);
          var dateOne = Date.parse(today.replace('-', '/').replace('-', '/'));
          var mydate=data.return_arr.due_date;
          var dateTwo = Date.parse(mydate.replace('-', '/').replace('-', '/'));

          var related_to_id = data.return_arr.related_to_id;
          var related_to_type = data.return_arr.related_to_type;
          
          if (related_to_type == null) {
            $('#related_div').css('display','none');
          } else if (related_to_type == '1') {
            $("#related_div a").attr("href", "{{URL::to('project/details/')}}"+"/"+related_to_id);   
          } else if (related_to_type == '2') {
            $("#related_div a").attr("href", "{{URL::to('customer/edit/')}}"+"/"+related_to_id);  
          } else if (related_to_type == '3') {
            $("#related_div a").attr("href", "{{URL::to('ticket/reply/')}}"+"/"+btoa(related_to_id));  
          }
          $('#subject').html(data.return_arr.subject);
          $('#related_to').html(data.return_arr.related_to);
          $('#project_id').html(data.return_arr.related_to_id);
          $('#task-details').html(data.return_arr.description);
          $('#task-details-textarea').text(data.return_arr.editDescription);
          $('#task-details-textarea').attr('data-id', task_id);
          $('#recuring').html(data.return_arr.recuring);
          $('#created_at').html(data.return_arr.created_at.date+"<br>"+data.return_arr.created_at.time);
          $('#task_status').html(data.return_arr.status_name);
          if (data.return_arr.status_id == 4) {
            $('#timerDiv').css('display', 'none');
          } else {
            $('#timerDiv').css('display', 'block');
          }
          $('#task_status').attr('data-rel',data.return_arr.status_id);
          $('#start_date').val( data.return_arr.start_date);

          if (data.return_arr.start_date) {
            global_start_date = data.return_arr.start_date;
          }
          if (data.return_arr.due_date) {
            global_due_date = data.return_arr.due_date;
          }
          
          $('#due_date').val(data.return_arr.due_date);
          $('.priorityText').html(data.return_arr.priority);
          $('.priorityText').attr('data-rel',data.return_arr.priority_id);
          $('.hourly_rate_text').html(getDecimalNumberFormat(data.return_arr.hourly_rate));
          $('#member_id').attr('task_id',data.return_arr.task_id);
          $('#assignee_list').attr('project_id',data.return_arr.related_to_id);
          $('#assignee_dropdown').attr('task_id',data.return_arr.task_id);
          $('#mark_status').attr('task_id',data.return_arr.task_id);
          $('#mark_status').attr('project_id',data.return_arr.related_to_id);   
          $('.add_attachment').attr('task_id',data.return_arr.task_id);
          $('#total_logged_time').html(data.logged_time.total_logged_time);   
          $('#individual_logged_time').html(data.logged_time.user_logged_time);
          $('#relatedToId').val(data.return_arr.related_to_id);
          $('#relatedToType').val(data.return_arr.related_to_type);
          $('#chargeType').val(data.return_arr.chargeType);
          $('#ratePerHour').val(data.return_arr.ratePerHour);
          $("#task-details").show();
          $("#task-details-write").hide();

          $('#start_date').daterangepicker({
            startDate: moment(global_start_date),
            "autoUpdateInput": false,
            "singleDatePicker": true,
            "applyButtonClasses": "btn-primary start_date",
            "locale": { "format": dateFormat },
          }, function(start, end){
            var startDate        = moment(start, 'MMMM D, YYYY').format(dateFormat);
            $('#start_date').val(startDate);
          });
          if (!data.return_arr.start_date) {
            $('#start_date').val('');
          }

          $('#due_date').daterangepicker({
            startDate: moment(global_due_date),
            "autoUpdateInput": false,
            "singleDatePicker": true,
            "applyButtonClasses": "btn-primary due_date",
            "locale": { "format": dateFormat},
          }, function(start, end){
            var dueDate        = moment(start, 'MMMM D, YYYY').format(dateFormat);
            $('#due_date').val(dueDate);
          });

          if (!data.return_arr.due_date) {
            $('#due_date').val('');
          }
          
          if (!data.return_arr.hourly_rate) {
            $('.hourly_rate_div').hide();
          } else {
            $('.hourly_rate_div').show();
          }
          if (!data.return_arr.recuring) {
            $('.recuring_div').hide();
          } else {
            $('.recuring_div').show();
          }
          if (data.return_arr.status_id != 4) {
            mark_check();
          } else {
            unmark_check();
          }
          $('#modal-default').modal("show");

          // get all comment first
          var comments = '';
          $.each(data.comment, function(key, value){
            var tm = value.created_at;
            var delete_comment = ''; 
            if ((data.delete_task_comment)) {
                delete_comment = '<span class="float-right delete-comment m-l-10 cursor_pointer color_red" comment_id="'+value.id+'" task_id="'+value.task_id+'" >'+
                  '<i  class="fa fa-times">'+
                  '</i>'+
                  '</span>';
            }

            var edit_comment = '';
            if ((data.edit_task_comment)) {
                edit_comment = '<span class="float-right edit_comment cursor_pointer" comment_id="'+value.id+'" >'+
                  '<i  class="feather icon-edit">'+
                  '</i>'+
                  '</span>';
            }

            comments += 
              '<div class="card task-card task_border" panel_id="'+value.id+'" >'+
                '<div class="card-header comment-card-header p-1">'+ value.user_name +
                  '<span class="ml-50">'+
                    '<i class="feather icon-clock">'+
                    '</i>&nbsp;'+value.created_at+
                  '</span>'+
                  delete_comment+
                  edit_comment+
                '</div>'+
                '<div class="card-body comment-card-body comment_color">'+'<span class="content_area" id="content_area_'+value.id+'"  content="'+value.id+'">'+value.content+'</span>'+
                  '<span class="comment_textarea display_none" text_area_id ="'+value.id+'" >'+
                    '<textarea class="text_editor_edit form-control" rows="5" id="txt_'+value.id+'">'+value.content+
                    '</textarea>'+
                    '<div class="form-group">'+
                      '<button class="btn btn-danger btn-close-update custom-btn-small task-custom-btn-small mt-10" comment_id="'+value.id+'">{{ __('Cancel') }}</button>'+
                      '<button type="button" comment_task_id="'+value.task_id+'" comment_id="'+value.id+'"  class="btn btn-primary custom-btn-small task-custom-btn-small pull-right btn-update-comment mt-10">{{ __('Update') }}</button>'+
                      '<div id="update_comment_error'+value.id+'" class="float-right mt-10"></div>'+
                    '</div>'+
                  '</span>'+
                '</div>'+
              '</div>';
          });
          $('#comments_area').html(comments);

          var checklists = '';
          $.each(data.return_arr.checklist_items, function (key, value) {
            if (value.is_checked == 1) {
              var status = 'checked';
            } else if (value.is_checked == 0) {
              var status = '';
            }
            
            checklists += '<div class="form-group mr-3 m-0 mb-2" id="checklist_row_'+value.id+'">'+
                            '<div class="checkbox checkbox-primary d-inline">'+
                              '<input type="checkbox" name="checklist_item" class="checklist_item" id="checklist_item_'+value.id+'" data-id="'+value.id+'" '+ status +'/>'+
                              '<label for="checklist_item_'+value.id+'" class="cr"></label>'+
                            '</div>';

            if (status == 'checked') {
              checklists += '<span class="checklist_label line-through break-all" title="{{ __('Click to edit') }}"  data-id="'+value.id+'" id="checklist_label_'+value.id+'">'+ value.title +'</span>';
            } else {
              checklists += '<span class="checklist_label break-all" title="{{ __('Click to edit') }}" data-id="'+value.id+'" id="checklist_label_'+value.id+'">'+ value.title +'</span>';
            }
            checklists += '<i class="feather icon-trash-2 cursor_pointer text-danger clickable ml-2 delete-checklist-item col-form-label" data-id="'+value.id+'"></i></div>';
            
          })
          $("#checklist_items").html(checklists);
        }
      });
      
      // Get All assignee
      $("#due_date_error").hide();
      $('.timer').attr('data-task-id',task_id);
      $('.timesheet').attr('data-task-id',task_id);
      $.ajax({
        url: '{{ URL::to('project/task/get_all_assignee')}}',
        data:{
          task_id :task_id
        },
        type: 'GET',
        dataType: 'JSON',
        success: function (data) {
          if (data.status == 1) {
            var assignee_members = '';
            $.each(data.oldAssignees, function(key, value) {
              var assigneePic = "";
              if (value.picture != null) {
                assigneePic = '<div class="task-modal-assignee-first-circle mr-2"><a target="_blank" href="{{URL::to("user/team-member-profile")}}/'+value.user_id+'">'+
                '<img alt="User profile picture" src="'+SITE_URL+'/public/uploads/user/'+ value.picture +'" class="user-img mx-1" title='+ value.user_name +'>'
              +'</a></div>'+
              '</div>';
              } else {
                var userName = '';
                $.each(value.user_name.split(' '), function(key, value){
                  userName += value.substring(0, 1);
                });
                userName = userName.trim();
                userName = userName.substring(0, 2);
                assigneePic = '<div class="task-modal-assignee-first-circle mr-2"><a target="_blank" href="{{URL::to("user/team-member-profile")}}/'+value.user_id+'">'+
                '<span class="alpha f-12 mx-1" title='+ value.user_name +'>'+ userName +'</span>'
                +'</a></div>'+
                '</div>';
              }
              assignee_members +='<div class="assigned float-left" span_id = "'+value.user_id+'">'+ 
              @if(Helpers::has_permission(Auth::user()->id, 'delete_task_assignee'))
                '<span task_id="'+task_id+'" assignee_id = "'+value.user_id+'" assignee_name = "'+value.user_name+'" class="deleteMember color_red cursor_pointer display_none">'+
                  '<i class="feather icon-x" title="{{ __('Remove') }}"></i>'+
                '</span>'+
              @endif
              assigneePic;
            });
            $('#all_assignee').html(assignee_members);
          }
        }
      });

    });

    $(document).on('click', '.delete-checklist-item', function () {
      var id = $(this).attr('data-id');
      swal({
          title: "{{ __('Are you sure?') }}",
          text: "{{ __('Are you sure to delete this checklist item?') }}",
          icon: "warning",
          buttons: ["{{ __('Cancel') }}", "{{ __('Ok') }}"],
          dangerMode: true,
      })
      .then((willDelete) => {
          if (willDelete) {
            $.ajax({
              url: "{{url('checklist/delete')}}",
              method: "POST",
              data: {'id': id, '_token': "{{csrf_token()}}"},
              success: function (data) {
                if (data.status == true) {
                  $("#checklist_row_"+id).remove();
                }
              }
            })
          }
      });
    })

    $(document).on('click', '.start_date', function (argument) {
      var date = $("#start_date").val();
      var task_id = $("#task_id").val();
      $.ajax({
        url: "{{url('task/start-date/edit')}}",
        method: "POST",
        data: { 'task_id': task_id, 'date': date, '_token':  "{{csrf_token()}}"},
        success: function (data) {
          if (data.result == 0) {
            $("#start_date").val(date);
          }
        }
      })
    })

    $(document).on('click', '.due_date', function (argument) {
      var date = $("#due_date").val();
      var task_id = $("#task_id").val();
      if (new Date($("#start_date").val()) > new Date(date)) {
        $("#due_date").val(global_due_date);
        $("#due_date").addClass("text-danger");
        $("#due_date_error").html("{{ __('Due date can not before start date.') }}").addClass('text-danger');
        $("#due_date_error").show();
      } else {
        global_due_date = date;
        $.ajax({
          url: "{{url('task/due-date/edit')}}",
          method: "POST",
          data: { 'task_id': task_id, 'date': date, '_token':  "{{csrf_token()}}"},
          success: function (data) {
            if (data.result == 0) {
              $("#due-date").val(date);
            }
          }
        });
        $("#due_date_error").removeClass("text-danger").hide();
        $("#due_date").removeClass("text-danger");
      }
    })

    $(document).on('click', '.checklist_label', function () {
      var id = $(this).attr('data-id');
      var title = $('#checklist_label_'+id).text();
      $(this).before('<input type="text" class="f-14 checklist-input w-85" data-id="'+id+'" id="checklist-input-'+id+'" value="'+title+'">');
      $(this).hide();
    })

    $(document).on('focusout', '.checklist-input', function () {
      var id = $('.checklist-input').attr('data-id');
      var title = $("#checklist-input-"+id).val();
      var task_id = $("#task_id").val();
      $.ajax({
        url: "{{url('checklist/edit')}}",
        method: "POST",
        data: {'id': id, 'task_id': task_id, 'title': title, '_token': "{{csrf_token()}}"},
        success: function (data) {
          if (data < 0) {
            swal({
              text: jsLang('Checklist already exist'),
              icon: "error",
              buttons: [false, jsLang('Ok')],
              dangerMode: true,
            });
          } else {
            var description = title;
            if (description != null) {
              if (description.length > 50) {
                description = description.trim();
                description = description.substring(0, 50) + "...";
              }
            }
            $('#checklist_label_'+id).text(data.title);
            $('#checklist_label_'+id).css('word-break', 'break-all');
            if (data.status == 'checked') {
              $('#checklist_label_'+id).css('text-decoration:line-through');
            }
          }
        }
      });
      $("#checklist-input-"+id).remove();
      $(".checklist_label").show();
    })

    $(document).on('click', '#add-checklist', function () {
      if ($(".add-checklist-item").length == 0) {
        var newChekclist =  '<div class="form-group row add-checklist-item mx-0">'+
                              '<div class="col-sm-11">'+
                                '<input class="form-control" id="add-checklist-input" type="text">'+
                              '</div>'+
                            '</div>';
        $("#checklist_items").prepend(newChekclist);
        $("#add-checklist-input").focus();
      }
    })

    $(document).on("blur", "#add-checklist-input", function () {
      var title = $("#add-checklist-input").val();
      var task_id = $("#task_id").val();
      if (title != '') {
        $.ajax({
          url: "{{url('checklist_edit/add')}}",
          method: "POST",
          data: {'title': title, 'task_id': task_id, '_token': "{{csrf_token()}}"},
          success: function (data) {
            if (data.message == "success") {
              var value = data.item;
              var item = '<div class="form-group mr-3 m-0 mb-2" id="checklist_row_'+value.id+'">'+
                            '<div class="checkbox checkbox-primary d-inline">'+
                              '<input type="checkbox" name="checklist_item" class="checklist_item" id="checklist_item_'+value.id+'" data-id="'+value.id+'"/>'+
                              '<label for="checklist_item_'+value.id+'" class="cr"></label>'+
                            '</div>'+
                            '<span title="{{ __('Click to edit') }}" class="checklist_label" data-id="'+value.id+'" id="checklist_label_'+value.id+'">'+ value.title +'</span>'+
                            '<i class="feather icon-trash-2 cursor_pointer text-danger clickable delete-checklist-item col-form-label ml-2" data-id="'+value.id+'"></i>'+
                          '</div>';
              $("#checklist_items").append(item);
            } else {
              swal({
                text: data.message,
                icon: "error",
                buttons: [false, jsLang('Ok')],
                dangerMode: true,
              });
            }
          }
        });
        $(".add-checklist-item").remove();
      }
    })

    $(document).on('change', '.checklist_item', function () {
      var id = $(this).attr('data-id');
      var status = $(this).is(":checked");
      $.ajax({
        url: "{{url('checklist/change_status')}}",
        method: "POST",
        data: {'id':id, 'status': status, '_token': "{{csrf_token()}}"},
        success:function(data){
          if (status) {
            $('#checklist_label_' + id).attr('style', 'text-decoration:line-through');
          } else {
            $('#checklist_label_' + id).attr('style', '');
          }
        }
      });
    })
    
    $(document).on('blur', '#task-details-textarea', function (e) {
      e.preventDefault();
      updateTaskDescription();
    })

    function updateTaskDescription() {
      var description = $('#task-details-textarea').val();
      var task_id = $('#mark_status').attr('task_id');
      $.ajax({
        url: "{{url('task/update/description')}}",
        method: "POST",
        data: { 'task_id': task_id, 'description': description, '_token':  "{{csrf_token()}}"},
        success: function (data) {
          var str = data.description;
          $('#task-details').html(data.description);
          $("#task-details").show();
          $("#task-details-write").hide();
        }
      })
    }

    $(document).on('click', '#task-details-edit', function () {
      $("#task-details").hide();
      $("#task-details-write").show();
      $("#task-details-textarea").focus();
    })

    $(document).on('click', '.edit_comment', function() {
      var  comment_id  = $(this).attr('comment_id');
      $(".comment_textarea").hide();
      $(".content_area").show();
      $("span[content="+comment_id+"]").hide();
      $("span[text_area_id="+comment_id+"]").show();
    });

    $(document).on('click','.btn-update-comment',function() {
      var flag=true;
      var  comment_id      = $(this).attr('comment_id');
      var  comment_task_id = $(this).attr('comment_task_id');
      var  project_id      = $('#assignee_list').attr('project_id');
      var  update_comment  = $("#txt_"+comment_id).val();
      $('#update_comment_error'+comment_id).hide();
      if (!update_comment) {
        $('#update_comment_error'+comment_id).show();
        $('#update_comment_error'+comment_id).text("{{ __('Please Leave a Comment') }}").css('color','red');
        flag = false;
        return false; 
      }
      var url   = "{{url('project/task/update-comment')}}";
      var token = "{{csrf_token()}}";
      $.ajax({
        url:url,
        method:"POST",
        data:{'update_comment':update_comment, 'comment_id':comment_id, 'project_id':project_id, 'comment_task_id': comment_task_id, '_token':token},
        dataType:"json",
        success:function(data){
          if (data.status == 1) {
            $('#content_area_'+comment_id).html(update_comment);
            $('#content_area_'+comment_id).show();
            $('.comment_textarea').hide();
          } else {
            swal(data.message, {
             icon: "error",
             buttons: [false, "{{ __('Ok') }}"],
            });
          }
        }
      });
    });

    // Delete Comment
    $(document).on('click','.delete-comment',function(){
      var comment_id = $(this).attr('comment_id');
      var task_id = $(this).attr('task_id');
      var  project_id = $('#assignee_list').attr('project_id');
      swal({
          title: "{{ __('Are you sure?') }}",
          text: "{{ __('Are you sure to remove this comment from this task?') }}",
          icon: "warning",
          buttons: ["{{ __('Cancel') }}", "{{ __('Ok') }}"],
          dangerMode: true,
      })
      .then((willDelete) => {
          if (willDelete) {
              var url   = "{{url('project/task/delete-comment')}}";
              var token = "{!!csrf_token()!!}";
              $.ajax({
                url:url,
                method:"POST",
                data:{ 'comment_id':comment_id, 'task_id':task_id, 'project_id':project_id, '_token':token },
                dataType:"json",
                success:function(data){
                  if (data.status == 1) {
                    $("div[panel_id="+comment_id+"]").hide();  
                  }
                }
              });
          } else {
              return false;
          }
      });
    });

    // Close Update comment
    $(document).on('click','.btn-close-update',function(e){
      e.preventDefault();
      var  comment_id = $(this).attr('comment_id');
      $('#content_area_'+comment_id).show();
      $('.comment_textarea').hide();
    });

    

    function fileExists(url) {
      $.ajax({
          url: url,
          type:'HEAD',
          error: function()
          {
            return '0';
          },
          success: function()
          {
            return '1';
          }
      });
    }
        
    // Get Rest Assignee
    $('#assignee_list').on('click',function() {
      var project_id = $(this).attr('project_id');
      var task_id = $('#assignee_dropdown').attr('task_id');
      var url   = "{{url('project/task/get_rest_assignee')}}";
      var token = "{!!csrf_token()!!}";
      $.ajax({
        url:url,
        method:"POST",
        data:{'project_id':project_id, 'task_id':task_id, '_token':token},
        dataType:"json",
        success:function(data){
          if (data.old_member_status == 1) {
            var assignee_members = '';
            $.each(data.old_members, function(key, value){
              var pic = value.picture ?  'public/uploads/user/'+ value.picture : 'public/dist/img/avatar.jpg'; 
                assignee_members +='<div class="row m-0" span_id = "'+value.user_id+'">'+ 

                '<a class="col-md-2" target="_blank" href="{{URL::to("user/team-member-profile")}}/'+value.user_id+'">'+
                  '<img alt="User profile picture" src="'+SITE_URL+'/'+pic+'" class="user-img m-2" width="30" height="30" title='+ value.user_name +'>'
                +'</a>'+

                '<a class="col-md-5 m-t-10" target="_blank" href="{{URL::to("user/team-member-profile")}}/'+value.user_id+'">'+ value.user_name +'</a>'+

                @if(Helpers::has_permission(Auth::user()->id, 'delete_task_assignee'))
                  '<span class="col-md-1 m-t-10 deleteMember cursor_pointer" task_id="'+task_id+'" assignee_id = "'+value.user_id+'" assignee_name = "'+value.user_name+'">'+
                    '<i class="feather icon-minus-square color_red" data-toggle="tooltip" data-placement="right" title="{{ __('Remove Assignee') }}">'+'</i>'+
                  '</span>'+
                @endif
              '</div>';
            });
            $('#assignees_modal_assignee_list').html(assignee_members);
          }

          if (data.project_member_status == 1) {
            var project_member = '<option value=""> {{ __('Please select assignees') }}</option>';
            $.each(data.project_member, function(key, value){
              project_member += '<option value="'+value.user_id+'">'+value.user_name+'</option>';
            }) 
            $('#assignee_dropdown').html(project_member);
          } else {
            $('#assignee_dropdown').html( '<option value="">{{ __('No member found') }}</option>');
          }
        }
      });
      $("#assignee_dropdown").select2({
        dropdownParent: $("#assignee-modal")
      });
    });

    // Insert Assignee
    $('#assignee_dropdown').on('change',function() {
      var assignee_id = $(this).val();
      var task_id = $(this).attr('task_id');
      var project_id = $('#assignee_list').attr('project_id');

      var url   = "{{url('project/task/assign_assignee')}}";
      var token = "{!!csrf_token()!!}";
      $.ajax({
        url:url,
        method:"POST",
        data:{'task_id':task_id,'assignee_id': assignee_id,'project_id':project_id, '_token':token},
        dataType:"json",
        success:function(data){
          if (data.status == 1) {
            var assignee_members = '';
            var pic = data.assinee_details.picture ?  'public/uploads/user/'+ data.assinee_details.picture : 'public/dist/img/avatar.jpg';

            var assigneePic = "";
            if (data.assinee_details.picture != null) {
              assigneePic = '<div class="task-modal-assignee-first-circle mr-2"><a target="_blank" href="{{URL::to("user/team-member-profile")}}/'+data.assinee_details.id+'">'+
              '<img alt="User profile picture" src="'+SITE_URL+'/public/uploads/user/'+ data.assinee_details.picture +'" class="user-img mx-1" title='+ data.assinee_details.full_name +'>'
              +'</a></div>'+
              '</div>';
            } else {
              var userName = '';
              $.each(data.assinee_details.full_name.split(' '), function(key, value){
                userName += value.substring(0, 1);
              });
              userName = userName.trim();
              userName = userName.substring(0, 2);
              assigneePic = '<div class="task-modal-assignee-first-circle mr-2"><a target="_blank" href="{{URL::to("user/team-member-profile")}}/'+data.assinee_details.id+'">'+
              '<span class="alpha f-12 mx-1" title='+ data.assinee_details.full_name +'>'+ userName +'</span>'
              +'</a></div>'+
              '</div>';
            }

            assignee_members +='<div class="assigned" span_id = "'+data.assinee_details.id+'">'+ 
            @if(Helpers::has_permission(Auth::user()->id, 'delete_task_assignee'))
              '<span task_id="'+task_id+'" assignee_id = "'+data.assinee_details.id+'" class="deleteMember cursor_pointer">'+
                '<i class="feather icon-x color_red" title="{{ __('Remove ') }}"></i>'+
              '</span>'+
            @endif
            assigneePic;
            $('#all_assignee').append(assignee_members); 

            assignee_members = '';
            var pic = data.assinee_details.picture ?  'public/uploads/user/'+ data.assinee_details.picture : 'public/dist/img/avatar.jpg'; 
            assignee_members +='<div class="row m-0" span_id = "'+data.assinee_details.id+'">'+ 

              '<a class="col-md-2" target="_blank" href="{{URL::to("user/team-member-profile")}}/'+data.assinee_details.id+'">'+
                '<img alt="User profile picture" src="'+SITE_URL+'/'+pic+'" class="user-img m-2" width="30" height="30" title='+ data.assinee_details.full_name +'>'
              +'</a>'+

              '<a class="col-md-5 m-t-10" target="_blank" href="{{URL::to("user/team-member-profile")}}/'+data.assinee_details.id+'">'+ data.assinee_details.full_name +'</a>'+

              @if(Helpers::has_permission(Auth::user()->id, 'delete_task_assignee'))
                '<span class="col-md-1 m-t-10 deleteMember cursor_pointer" task_id="'+task_id+'" assignee_id = "'+data.assinee_details.id+'" assignee_name = "'+data.assinee_details.full_name+'">'+
                  '<i class="feather icon-minus-square color_red" data-toggle="tooltip" data-placement="right" title="{{ __('Remove Assignee') }}">'+'</i>'+
                '</span>'+
              @endif
            '</div>';
            $('#assignees_modal_assignee_list').append(assignee_members);
            $('#assignee_dropdown  option:selected').remove();
            $("#dataTableBuilder").DataTable().ajax.reload( null, false );   
          }
        }
      });
    });
      
    // Delete Assignee
    $(document).on('click','.deleteMember',function(){
      var task_id = $(this).attr('task_id');
      var user_id = $(this).attr('assignee_id');
      var user_name = $(this).attr('assignee_name');
      var project_id = $('#assignee_list').attr('project_id');
      swal({
          title: "{{ __('Are you sure?') }}",
          text: "{{ __('Are you sure to remove this member from this task?') }}",
          icon: "warning",
          buttons: ["{{ __('Cancel') }}", "{{ __('Ok') }}"],
          dangerMode: true,
      })
      .then((willDelete) => {
          if (willDelete) {
              var url   = "{{url('project/task/delete-assignee')}}";
              var token = "{!!csrf_token()!!}";
              $.ajax({
                url:url,
                method:"POST",
                data:{ 'task_id':task_id,'user_id':user_id,'project_id':project_id,'_token':token},
                dataType:"json",
                success:function(data){
                  if (data.status == 1) {
                    $("span[span_id="+user_id+"]").hide();
                    $("div[span_id="+user_id+"]").hide();
                    $('#assignee_dropdown').append('<option value="'+user_id+'">'+user_name+'</option>')
                    $("#dataTableBuilder").DataTable().ajax.reload( null, false );
                  }
                }
              });
          }
      });
    });

    // Hide Assignee dropdowm
    $('#modal-default').on('click',function(event){
      if (event.target.id != 'assignee_list' && event.target.id != 'select2-assignee_dropdown-container') {
        $('#assignee_dropdown').next(".select2-container").hide();
      }
    });

    // Insert New Comment and prepend to old commend
    $(document).on('click','#btn_comment',function(){
      $(this).attr("disabled", true);
      $(".spinner").show();
      $("#comment_text").text("{{ __('Please wait...') }}");
      var flag=true;
      var comment = $('#comment').val();
      var task_id = $('#mark_status').attr('task_id');
      var project_id = $('#mark_status').attr('project_id');
      $('#comments_error').hide();
      if (!comment) {
        $('#comments_error').show();
        $('#comments_error').text("{{ __('Please Leave a Comment') }}").css('color','red');
        $(".spinner").hide();
        $("#comment_text").text("{{ __('Add Comment') }}");
        $('#btn_comment').attr("disabled", false);
        flag = false;
        return false; 
      }     
      var url   = "{{url('project/task/store-comment')}}";
      var token = "{!!csrf_token()!!}";
      $.ajax({
        url:url,
        method:"POST",
        data:{'comment':comment, 'task_id':task_id, 'project_id':project_id, '_token':token},
        dataType:"json",
        success:function(data){
          if (data.status == 1) {
            addComment(data);
          } else {
            swal(data.message, {
             icon: "error",
             buttons: [false, "{{ __('Ok') }}"],
            });
            addComment(data)
          }
        }
      });
    });

    function addComment(data) {
      $('#btn_comment').attr("disabled", false);
            $(".spinner").hide();
            $("#comment_text").text("{{ __('Add Comment') }}");
            
            // start remove text editor 
            $('#comment').css("display", "block");

            if (data.comment_id) {
            $('#comment').val('');
            // end remove text editor
            var delete_comment = '';
            if (data.delete_task_comment) {
                delete_comment = '<span class="float-right delete-comment m-l-10 cursor_pointer color_red"  comment_id="'+data.comment_id+'" task_id="'+data.task_id+'" >'+
                  '<i  class="fa fa-times">'+
                  '</i>'+
                '</span>';
            }

            var edit_comment = ''; 
            if (data.edit_task_comment) {
                edit_comment = '<span class="float-right edit_comment cursor_pointer" comment_id="'+data.comment_id+'" >'+
                  '<i  class="feather icon-edit">'+
                  '</i>'+
                '</span>';
            }

            var comments = '<div class="card task-card task-card-border" panel_id="'+data.comment_id+'" >'+
                '<div class="card-header comment-card-header p-1">'+ data.user_name +
                '<span class="ml-50">'+
                '<i class="feather icon-clock ">'+
                '</i>'+data.created_at+
                '</span>'+
                delete_comment+
                edit_comment+
                '</div>'+
                '<div class="card-body comment-card-body comment_color">'+'<span class="content_area" id="content_area_'+data.comment_id+'"  content="'+data.comment_id+'">'+data.content+'</span>'+
                '<span class="comment_textarea display_none" text_area_id ="'+data.comment_id+'" >'+
                '<textarea class="text_editor_edit form-control" rows="5" id="txt_'+data.comment_id+'">'+data.content+
                '</textarea>'+
                 '<div class="form-group">'+
                    '<button class="btn btn-danger btn-sm btn-close-update mt-10" comment_id="'+data.comment_id+'">{{ __('Cancel') }}</button>'+
                    '<button type="button" comment_task_id="'+data.task_id+'" comment_id="'+data.comment_id+'"  class="btn btn-primary custom-btn-small task-custom-btn-small pull-right btn-update-comment mt-10">{{ __('Update') }}</button>'+
                    ' <div id="update_comment_error'+data.comment_id+'" class="float-right mt-10"></div>'+
                  '</div>'+
                '</span>'+
                '</div>'+
                '</div>';

            $('#comments_area').prepend(comments);
          }
    }

    // task timer script
    $(document).on('click','.timer',function(){
      var task_id     = $(this).attr('data-task-id');
      var taskStatusId =  $('#task_status').attr('data-rel');
      if ($(this).find("#timer_text").text() == "{{ __('Start Timer') }}") {
        if (task_id) {
          $.ajax({
            url:SITE_URL+"/start_task/timer",
            type:'post',
            data:{ '_token': "{{ csrf_token() }}",
                    'task_id':task_id,
                    'taskStatusId':taskStatusId,
                  },
            dataType:'json',

            success:function(data){
              if (data.success==1) {
                $('.timer').attr('data-timer-id',data.timer_id);
                $(".timer").removeClass("btn-success").addClass("btn-danger");
                $(".timer").html("<i class='feather icon-clock'></i><span id='timer_text'>{{ __("Stop Timer") }}</span>")
                $('#timer_text').attr('data-input','note_att');
                $('#task_status').html(data.statusName);
                $('#task_status').attr('data-rel', data.statusId);
                $("#dataTableBuilder").DataTable().ajax.reload(null, false);
              } else if (data.success == 2) {
                swal(" {{ __('You are not assigned to this task.') }} ", {
                 icon: "error",
                 buttons: [false, "{{ __('Ok') }}"],
                });
              } else {
                swal(" {{ __('One of your started task is running.') }} ", {
                 icon: "error",
                 buttons: [false, "{{ __('Ok') }}"],
                }); 
              }
            }
          });
        }
      } else {
        var timer_id = $(this).attr('data-timer-id');
        if (timer_id) {
          var title = $('#timer_text').attr('data-input');
          if (title == 'note_att') {
            $('#task-end-modal').modal('show');
            $("#task-end-submit").attr('data-task-id', task_id);
            $("#task-end-submit").attr('data-timer-id', timer_id);
          }
        }
      }; 
    });
    $("#task-end-modal").on('show.bs.modal', function () {
      $("#task-note-error").html("").hide();
      $("#task-end-note").val("");
    })
    $(document).on('click', '#task-end-submit', function () {
      var task_id  = $(this).attr('data-task-id');
      var timer_id = $(this).attr('data-timer-id');
      var chargeType = $('#chargeType').val();  
      var ratePerHour = $('#ratePerHour').val();
      var relatedToId = $('#relatedToId').val();
      var relatedToType = $('#relatedToType').val();
      var note = $("#task-end-note").val();
      if (note == "") {
        $("#task-note-error").html("* {{ __('Task note needed') }}").show();
      }
      else if (timer_id && task_id) {
        $("#task-note-error").html("").hide();
        $.ajax({
          url : SITE_URL+"/end_task/timer",
          type : 'post',
          data : {'_token' : '{{ csrf_token() }}', 'chargeType': chargeType, 'ratePerHour': ratePerHour, 'task_id' : task_id, 'relatedToId' : relatedToId, 'relatedToType' : relatedToType, 'timer_id' : timer_id, 'note' : note},
          dataType : 'json',
          success : function (response) {
            if (response.success == 1) {
              $('#task-end-modal').modal('toggle');
              $(".timer").removeClass("btn-danger").addClass("btn-success");
              $(".timer").html("<i class='feather icon-clock'></i><span id='timer_text'>{{ __("Start Timer") }}</span>")
              $("#individual_logged_time").html(response.logged_time.user_logged_time);
              $("#total_logged_time").html(response.logged_time.total_logged_time);
              $("#dataTableBuilder").DataTable().ajax.reload(null, false);
            }
          },
        })
      }
    })

    $(document).ready(function() {
      $(document).on("click", "#close_note_popover", function() {
        $('.timer').popover('dispose');
        $('#timer_text').attr('data-input','note_att');
      });
    });

    $(document).on('click','.timesheet',function(){
      var task_id = $(this).attr('data-task-id');
      timeSheetData(task_id);
      $('#manual_time_add_form').validate().resetForm();
      $('#start_time').removeClass('error');
      $('#end_time').removeClass('error');
    });


    function timeSheetData(task_id) {
      if (task_id) {
        $.ajax({
          url:SITE_URL+"/get_task/timer",
          type:'post',
          data:{'_token': '{{ csrf_token() }}', 'task_id':task_id},
          dataType:'json',
          success:function(reply){
            if (reply.success==1) {
              var $Modal  = $('#DemoModal2');
              $("#start_time").val("");
              $("#end_time").val("");
              $("#note").val("");
              $('#time_sheet_assignee_list').select2({
                dropdownParent: $("#DemoModal2-body")
              });
              $Modal.modal('show');
              if ($Modal.parent().get(0).tagName != 'BODY') {
                $('.modal-backdrop').insertAfter($Modal);
              }
              var assignee_list = reply.assignee_list;
              var record = reply.data;
              var trHTML = '';
              $.each(record, function (i, timer) {
                if (timer.end_time !='<br>') {
                  if (reply.delete_task_timer) {
                    var delet ='<a id="deleteButton" data-toggle="modal" data-target="#confirm_delete_modal" data-id='+timer.id+' data-task_id='+task_id+' class="btn btn-xs btn-danger"><i class="feather icon-trash-2 color_white"></i></button></a>';
                  } else {
                    var delet = '';  
                  }
                } else {
                  var delet ='';
                }
                trHTML += '<tr id="rowid'+timer.id+'">';
                trHTML += '<td class="text-center">'+timer.full_name+'</td>';
                trHTML += '<td class="text-center">'+timer.start_time+'</td>';
                trHTML += '<td class="text-center">'+timer.end_time+'</td>';
                trHTML += '<td class="text-center">'+timer.spent_time+'</td>';
                trHTML += '<td class="text-center">'+delet+'</td>';
                trHTML +='</tr>';
              });
              $('#timer_table tr td').each(function(){
                $(this).remove();
              });
              $('#timer_table').append(trHTML);
              // set assignee at timesheet modal
              var option = '<option value="">{{ __('Please select a member') }}</option>';
              $.each(assignee_list, function (i, value) {
                  option += '<option value="'+value.user_id+'">'+value.full_name+'</option>';
              });
              $('#time_sheet_assignee_list').html(option);
              if (reply.add_task_timer) {
                $('#add_custom_task_timer').show();
              }  
            } else {
              $('#timer_table tr td').each(function(){
                $(this).remove();
              });  
            }
          }
        });
      }
    }

    $(document).on('click','#deleteButton',function(){
      var timerId = $(this).data('id');
      var task_id = $(this).data('task_id');
      var chargeType = $('#chargeType').val();  
      var ratePerHour = $('#ratePerHour').val();
      var relatedToId = $('#relatedToId').val();
      var relatedToType = $('#relatedToType').val();
      swal({
          title: "{{ __('Are you sure?') }}",
          text: "{{ __('Are you sure to delete this timer?') }}",
          icon: "warning",
          buttons: ["{{ __('Cancel') }}", "{{ __('Ok') }}"],
          dangerMode: true,
      })
      .then((willDelete) => {
          if (willDelete) {
              $.ajax({
                url:SITE_URL+"/task/timer/delete",
                type:'post',
                data:{'_token': "{{ csrf_token() }}", 'task_timer_id': timerId, 'task_id': task_id, 'chargeType': chargeType, 'ratePerHour': ratePerHour, 'relatedToId': relatedToId, 'relatedToType': relatedToType},
                dataType:'json',
                success:function(data){
                  if (data.success) {
                    $("#confirm_delete_modal").modal('hide');
                    $('#total_logged_time').html(data.logged_time.total_logged_time);   
                    $('#individual_logged_time').html(data.logged_time.user_logged_time); 
                    $("#rowid"+timerId).remove();
                  }
                }
              });
          }
      });
    });

    // script for modal  over scrolling
    $('.modal').on('hidden.bs.modal', function (e) {
      if ($('.modal').hasClass('in')) {
        $('body').addClass('modal-open');
      }    
    });
    
    function mark_check(){
      $('#mark_status').removeClass('btn-default');
      $('#mark_status').attr('title', "{{ __('Mark as a complete') }}");
      $('#mark_status').attr('data-id','4');
      $('#mark_status').attr('data-value','Complete');
      $('#mark_status').addClass('btn-info task-btn-info');
      $('#timerDiv').css('display', 'block');
      return true;               
    }

    function unmark_check(){
      $('#mark_status').removeClass('btn-info task-btn-info'); 
      $('#mark_status').attr('title', "{{ __('Unmark as a complete') }}");
      $('#mark_status').attr('data-id','1');
      $('#mark_status').attr('data-value', 'Not Started');
      $('#mark_status').addClass('btn-success');
      $('#timerDiv').css('display', 'none');
      return true;
    }

    // Task Timer End Note Input Temple
    $(document).on('click','.note-submit',function(e){
      var timer_id  = $(this).attr('timer_id');
      var task_id   = $(this).attr('task_id');
      var task_note = $('#task_note').val();
      $.ajax({
        url:SITE_URL+"/end_task/timer",
        type:'post',
        data:{'timer_id':timer_id,'task_id':task_id,'task_note':task_note},
        dataType:'json',
        success:function(data){
          if (data.success==1) {
            $('#timer_text').text("{{ __('Start Timer') }}");
            $('.timer').removeClass('btn-danger');
            $('.timer').addClass('btn-success'); 
            $('#timer_text').removeAttr('data-input');
            $('#total_logged_time').html(data.logged_time.total_logged_time);   
            $('#individual_logged_time').html(data.logged_time.user_logged_time);
            $(".timer").popover('hide');
            $("#dataTableBuilder").DataTable().ajax.reload(null, false);
          }
        }
      });
    });

    $(document).on('mouseover', '.list-attachments', function () {
      $(this).find(".attachment-item-delete").show();
    })

    $(document).on('mouseout', '.list-attachments', function () {
      $(this).find(".attachment-item-delete").hide();
    })

    $(document).on('mouseover', '.assigned', function () {
      $(this).find(".deleteMember").show();
    })

    $(document).on('mouseout', '.assigned', function () {
      $(this).find(".deleteMember").hide();
    })

    $(document).on('change', '#validatedCustomFile', function (e) {
      var task_id = $('.add_attachment').attr('task_id');
      var file_data = $(".attach_files").prop("files")[0];
      var name      = file_data.name;
      var size      = file_data.size;
      var form_data = new FormData();
      var ext = name.split('.').pop().toLowerCase();
      if (jQuery.inArray(ext, ['gif','png','jpg','jpeg','pdf','doc','docx','ppt','csv','xlsx','jpeg']) == -1) {
        $('.file_type_error').addClass('text-danger').html("{{ __('Allowed File Extensions: .jpg, .gif, .jpeg, .png,.docx ,.xls,.xlsx,.pdf') }}");
        return  false;
      }
      if (size > 5e+6) {
        $('.file_type_error').addClass('text-danger ').html("{{ __('File Size Must Less Than or Equal 5mb') }}");
        return false;
      }
      form_data.append("file", file_data);
      form_data.append("task_id", task_id);
      form_data.append('_token', '{{ csrf_token() }}');
      var url   = "{{url('task_file/store')}}";
      $.ajax({
        url:url,
        method:"POST",
        data:form_data,
        dataType:"json",
        cache: false,
        contentType: false,
        processData: false,
        success:function(data){  
          if (data.status == 'success') {
            var image_ext = ['gif','png','jpg','jpeg'];
            if (jQuery.inArray(data.file_type, image_ext)!='-1') {
              var pic = 'public/uploads/task_files/'+ data.file_name;
              var img_file = '<a class="cursor_pointer" href="'+SITE_URL+'/'+pic+'"  data-lightbox="image-1"><img src="'+SITE_URL+'/'+pic+'" class="attachment-styles" alt="'+data.original_file_name+'"></a>';
            } else {
              var img_file = '<p class="attachment_name">'+data.file_type+'</p>';            
            }
            var download_path = 'public/uploads/task_files/'+ data.file_name;
                      
            files ='<table class="mb-5" class="table-hover" id="table_id_'+data.file_id+'" >'+
            '<tr >'+
            '<td class="w-25">'+
             img_file+
            '</td>'+
            '<td>&nbsp;&nbsp;</td>'+
            '<td class="align-left cursor_pointer">'+
              data.original_file_name+
              '&nbsp;&nbsp;&nbsp;<a href="'+SITE_URL+'/'+download_path+'" download="'+data.original_file_name+'"><i class="fa fa-download" data-toggle="tooltip" data-placement="top" title="Download"></i><a/>&nbsp;&nbsp;&nbsp;&nbsp;<a class="remove_attachment cursor_pointer" data-toggle="tooltip" data-placement="top" title="{{ __('Delete') }}" data-file_id="'+data.file_id+'"><i class="fa fa-times color_red;"></i></a>'+
              '<br/>'+
               'File Added:&nbsp;&nbsp;'+data.created_at+
              '<br/>'+
              'Uploaded by, '+data.uploader_name+
              '<br/>'+
            '</td>'+
            '</tr>'+
            '</table>'; 
            $('#files').append(files);
            $(".add_attachment").popover('hide');
          }
        }
      });
    });

    // Delete File Attachment
    $(document).on('click','.remove_attachment',function(e){
      var file_id = $(this).data('file_id');
      var url     = "{{url('task_file/delete')}}";
      var token   = "{!!csrf_token()!!}";
      var checkstr =  confirm("{{ __('Are you sure to delete this file?') }}");
      if (checkstr) {
        $.ajax({
          url:url,
          method:"POST",
          data:{'file_id':file_id, '_token':token},
          dataType:"json",
          success:function(data){
            if (data.status == 'success') {
              $('#table_id_'+file_id).remove();
            }
          }
        });
      } 
    });

    // timer manual time add 
    $(document).ready(function () {
      $("#manual_time_add_form").validate({
        ignore: ":hidden",
        rules: {
          start_time: {
            required: true
          },
          end_time: {
            required: true
          },
          assignee: {
            required: true
          }
        },
        submitHandler: function (form) {
          var start_actual_time  =  $('#start_time').val();
          var end_actual_time    =  $('#end_time').val();
          var note    =  $('#note').val();
          var task_id    =  $('#task_id').val();
          var start_time    =  $('#start_time').val();
          var end_time    =  $('#end_time').val();
          start_actual_time = new Date(start_actual_time);
          end_actual_time = new Date(end_actual_time);
          var diff = end_actual_time - start_actual_time; 
          if (diff < 0) {
            $('#end_time-error').text("{{ __('End date must be greater then start date.') }}").css('display', 'inline-block');
            return  false;
          }
          var chargeType = $('#chargeType').val();  
          var ratePerHour = $('#ratePerHour').val();
          var relatedToId = $('#relatedToId').val();
          var relatedToType = $('#relatedToType').val();
          var assignee = $('#time_sheet_assignee_list').val();
          $.ajax({
            type: "POST",
            url : "{{url('manual_time/store')}}",
            data: {
              'chargeType': chargeType, 
              'ratePerHour': ratePerHour, 
              'relatedToId': relatedToId, 
              'relatedToType': relatedToType, 
              'start_time': start_time, 
              'end_time': end_time,
              'note': note, 
              'task_id': task_id, 
              'assignee': assignee, 
              '_token': "{!!csrf_token()!!}", 
            },
            dataType:'json',
            success: function (data) {
              if (data.status == 'success') {
                timeSheetData(data.task_id); 
                $('#start_time').val(' ');
                $('#end_time').val(' ');
                $('#note').val(' ');
                $('#total_logged_time').html(data.logged_time.total_logged_time);   
                $('#individual_logged_time').html(data.logged_time.user_logged_time); 
                $("#manual_time_add_form").collapse('hide');
              }      
            }
          });
          // required to block normal submit since you used ajax
          return false;
        }
      });
    });

  $(document).on('change', '#time_sheet_assignee_list', function() {
    if ($(this).val() != '') {
      $('#time_sheet_assignee_list-error').css('display', 'none');
    } else {
      $('#time_sheet_assignee_list-error').css('display', 'inline-block');
    }
  });
  <?php 
    if (isset($task) && !empty($task)) {
  ?>
  $('.task-v-preview').find('a').trigger('click');
  <?php } ?>

</script>