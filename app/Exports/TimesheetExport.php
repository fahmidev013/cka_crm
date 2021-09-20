<?php

namespace App\Exports;

use App\Models\TaskTimer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TimesheetExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $taskTimer = new TaskTimer();
        $from      = isset($_GET['from']) ? $_GET['from'] : null;
        $to        = isset($_GET['to']) ? $_GET['to'] : null;
        $project   = isset($_GET['project']) ? $_GET['project'] : null;
        $assignee  = isset($_GET['assignee']) ? $_GET['assignee'] : null;
        $running   = isset($_GET['running']) ? $_GET['running'] : null;

        $timesheetDetails = $taskTimer->getTaskTime(['from' => $from, 'to' => $to, 'assinee_id' => $assignee, 'project_id' => $project, 'running' => $running])->select('task_timers.*', 'users.id as user_id', 'users.full_name', 'tasks.name as name', 'projects.name as project_name')->orderBy('task_id','asc');
        return $timesheetDetails;
    }

    public function headings(): array
    {
        return [
            __('Assignee'),
            __('Task Name'),
            __('Start Time'),
            __('End Time'),
            __('Time Spent'),
            __('Note')
        ];
    }

    public function getTimeSpent($timesheetDetails) 
    {
        if (!empty($timesheetDetails->end_time)) {
            $diff = ($timesheetDetails->end_time > $timesheetDetails->start_time) ? ($timesheetDetails->end_time - $timesheetDetails->start_time) : null;
        } else {
            $timesheetDetails->start_time = (int) $timesheetDetails->start_time;
            $diff = (time() > $timesheetDetails->start_time) ?  time() - $timesheetDetails->start_time : null;
        }
        $hours    = floor($diff / 3600) > 0 ? floor($diff / 3600) . 'h ' : '';
        $minutes  = floor(($diff / 60) % 60) > 0 ? floor(($diff / 60) % 60) . 'm ' : '';
        $seconds  = $diff % 60;
        $diffTime = $hours . $minutes . $seconds . 's';

        return $diffTime;
    }
    
    public function map($timesheetDetails): array
    {  
        return [
            $timesheetDetails->full_name,
            $timesheetDetails->name,
            timeZoneformatDate(date("m/d/Y h:i:s A T", $timesheetDetails->start_time)).'    '.timeZonegetTime(date("m/d/Y h:i:s A T", $timesheetDetails->start_time)),
            ($timesheetDetails->end_time != "") ? timeZoneformatDate(date("m/d/Y h:i:s A T", $timesheetDetails->end_time)).'    '.timeZonegetTime(date("m/d/Y h:i:s A T", $timesheetDetails->end_time)) : " ",
            $this->getTimeSpent($timesheetDetails),
            $timesheetDetails->note
        ];
    }
}
