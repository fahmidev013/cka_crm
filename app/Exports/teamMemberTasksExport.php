<?php

namespace App\Exports;
use Auth;
use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class teamMemberTasksExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $userId = isset($_GET['userId']) ? $_GET['userId'] : null;
        $from     = isset($_GET['from']) ? $_GET['from'] : null ;
        $to       = isset($_GET['to']) ? $_GET['to'] : null ;
        $status   = isset($_GET['status']) ? $_GET['status'] : null ;
        $priority = isset($_GET['priority']) ? $_GET['priority'] : null ;
        $taskList = (new Task())->getUserTaskForDT($from, $to, $status, $userId,  $priority)->orderBy('tasks.id','desc');

        return $taskList;
    }

    public function headings(): array
    {
        return [
            __('Task Name'), 
           __('Assignee'), 
           __('Start date'), 
           __('Due date'), 
           __('Status'),
           __('Priority')
        ];
    }
    
    public function map($task): array
    {  
        return [
            $task->name,
            $this->allAssignee($task->id),
            formatDate($task->start_date),
            $task->due_date ? formatDate($task->due_date) : '',
            $task->priorityName,
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
