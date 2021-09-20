<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class projectTaskExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $from     = isset($_GET['from']) ? $_GET['from'] : null;
        $to       = isset($_GET['to']) ? $_GET['to'] : null;
        $project  = isset($_GET['project']) ? $_GET['project'] : null;
        $assignee = isset($_GET['assignee']) ? $_GET['assignee'] : null;
        $status   = isset($_GET['status']) ? $_GET['status'] : null;
        $priority  = isset($_GET['priority']) ? $_GET['priority'] : null;

        $taskList = (new Task)->getAllTaskForDT($from, $to, $status, $project, $assignee, $priority)->orderBy('start_date', 'desc');

        return $taskList;
    }

    public function headings(): array
    {
        return [
            __('Task name'),
            __('Related To'),
            __('Assignee'),
            __('Start date'),
            __('Due date'),
            __('Priority'),
            __('Status')
        ];
    }
    
    public function map($task): array
    {  
        $relatedTo = '';
        if ($task->related_to_type == 1) {
            $relatedTo = $task->project_name;
        } else if ($task->related_to_type == 2) {
            $relatedTo = $task->customer_name;
        } else if ($task->related_to_type == 3) {
            $relatedTo = $task->subject;
        }
        return [
            $task->name,
            $relatedTo,
            $this->allAssignee($task->id),
            formatDate($task->start_date),
            $task->due_date ? formatDate($task->due_date) : '',
            $task->priority_name,
            $task->status_name,
        ];
    }

    public function allAssignee($taskId) {
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
