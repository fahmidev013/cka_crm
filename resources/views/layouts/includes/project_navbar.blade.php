<ul class="nav nav-pills" id="project-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link h-lightblue {{$navbar=='details' ? 'active':''}}" id="project-overview-tab" href='{{url("project/details/".$project->id)}}'>{{ __('Project Overview') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link h-lightblue {{$navbar=='task' ? 'active':''}}" id="project-tasks-tab" href='{{url("project/tasks/".$project->id)}}{{isset($sub_menu) ? "?$sub_menu" : ""}}'>{{ __('Tasks') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link h-lightblue {{$navbar=='timesheet' ? 'active':''}}" id="project-timesheet-tab"href='{{url("project/tasks/timesheet/".$project->id)}}{{isset($sub_menu) ? "?$sub_menu" : ""}}'>{{ __('Timesheets') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link h-lightblue {{$navbar=='milestone' ? 'active':''}}" id="project-milestone-tab" href='{{url("project/milestones/".$project->id)}}{{isset($sub_menu) ? "?$sub_menu" : ""}}'>{{ __('Milestones') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link h-lightblue {{$navbar=='file' ? 'active':''}}" id="project-file-tab" href='{{url("project/files/".$project->id)}}{{isset($sub_menu) ? "?$sub_menu" : ""}}'>{{ __('Files') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link h-lightblue {{$navbar=='ticket' ? 'active':''}}" id="project-ticket-tab" href='{{ url("project/tickets/".$project->id) }}{{ isset($sub_menu) && !empty($sub_menu) ? "?$sub_menu" : ""}}'>{{ __('Tickets') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link h-lightblue {{$navbar=='invoice' ? 'active':''}}" id="project-invoice-tab" href='{{url("project/invoice/".$project->id)}}{{isset($sub_menu) ? "?$sub_menu" : ""}}'>{{ __('Invoices') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link h-lightblue {{$navbar=='note' ? 'active':''}}" id="project-note-tab" href='{{url("project/notes/".$project->id)}}'>{{ __('Notes') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link h-lightblue {{$navbar=='activity' ? 'active':''}}" id="project-activity-tab" href='{{url("project/activities/".$project->id)}}{{isset($sub_menu) ? "?$sub_menu" : ""}}'>{{ __('Activities') }}</a>
  </li>
</ul>