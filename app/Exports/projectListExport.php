<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class projectListExport implements FromCollection, WithHeadings, WithMapping 
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $i = 1;
    public function collection()
    {
        
        $from         = $_GET['from'];
        $to           = $_GET['to'];
        $status       = $_GET['status'];
        $project_type = explode(',', $_GET['project_type']);

        $result = [];
        $finalRes = [];
        $projects = (new Project)->getAllProjectPdf($from, $to, $status, $project_type)->get()->toArray();
        if (!empty($projects)) {
          foreach ($projects as $val) {
              $result[$val->project_id][] = $val;
          }
        }
        
        if (!empty($result)) {
          foreach ($result as $key => $value) {
            $completedTask = 0;
            $totalTask = 0;
            if (!empty($value)) {
              foreach ($value as $k => $v) {
                if ($v->task_status_id == 5) {
                  $completedTask += 1;
                }
                if (isset($v->task_id) && !empty($v->task_id)) {
                  $totalTask += 1;
                }
                $finalRes[$key]['project_id'] = $v->project_id;
                $finalRes[$key]['name'] = $v->name;
                $finalRes[$key]['project_type'] = $v->project_type;
                $finalRes[$key]['first_name'] = $v->first_name;
                $finalRes[$key]['last_name'] = $v->last_name;
                $finalRes[$key]['begin_date'] = $v->begin_date;
                $finalRes[$key]['due_date'] = $v->due_date;
                $finalRes[$key]['charge_type'] = $v->charge_type;
                $finalRes[$key]['status_name'] = $v->status_name;
                $finalRes[$key]['totalTask'] = $totalTask;
                $finalRes[$key]['completedTask'] = $completedTask;
              }
            }
          }
        }
        arsort($finalRes);
        $data = collect($finalRes);
        
        return $data;
    }

    public function headings(): array
    {
        return [
            __('Project Name'),
            __('Customers'),
            __('Start Date'),
            __('Due Date'),
            __('Billing Type'),
            __('Total Task'),
            __('Status')
        ];
    }
    
    public function map($data): array
    {  
        return [
            $data['name'],
            $data['first_name'].' '.$data['last_name'],
            formatDate($data['begin_date']),
            strtotime($data['begin_date']) < strtotime($data['due_date']) ? formatDate($data['due_date']) : '-',
            ($data['charge_type'] == 1) ? 'Fixed Rate' : 'Project Hour',
            ' ' . $data['totalTask'] . ($data['completedTask'] > 0 ? '/'. $data['completedTask'] : ''),
            $data['status_name']
        ];
    }
}
