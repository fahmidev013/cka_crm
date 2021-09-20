<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
Use Auth;

class customerTaskExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) ? $_GET['to'] : null;
        $id = isset($_GET['customer']) ? $_GET['customer'] : null;
        $status    = isset($_GET['status'])   ? $_GET['status']    : null;
        $assignee  = isset($_GET['assignee']) ? $_GET['assignee']  : Auth::user()->id;
        $priority  = isset($_GET['priority'])? $_GET['priority'] : null;
        $data = (new Task())->getAllTaskForDT($from, $to, $status, null,  $assignee, $priority, $id, 2)->orderBy('id', 'DESC');
        
        return $data;
    }

    public function headings(): array
    {
        return [
           __('Task Name'), 
           __('Assignee'), 
           __('Start date'), 
           __('Due date'), 
           __('Priority'), 
           __('Status') 
        ];
    }

    public function map($task): array
    {
        return [
            $task->name,
            $this->allAssignee($task->id),
            $task->start_date,
            !empty($task->due_date) ? formatDate($task->due_date) : '-',
            $task->priority_name,
            $task->status_name, 
        ];
    }

    public function allAssignee($taskId)
    {
        $assigne = (new Task())->taskAssignsList($taskId)->pluck('user_name');
        $list = $allAssignee = "";
        if (!empty($assigne)) {
            foreach ($assigne as $counter => $assign) {
                $list .= $assign;
                if ($counter < count($assigne) - 1) {
                    $list .= ", ";
                }
            }
        $allAssignee = $list;
        }
        return $allAssignee;
    }

    
}
