<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class customerProjectExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $customer = isset($_GET['customer']) ? $_GET['customer'] : null ;
        $from    = isset($_GET['from']) ? ($_GET['from']) : null ;
        $to      = isset($_GET['to']) ? ($_GET['to']) : null ;
        $status =  !empty($_GET['status']) ? $_GET['status'] : null; 
        $query = Project::with(['customer', 'projectStatuses'])->orderBy('created_at', 'desc');
        if (!empty($status)) {
            $query->where('project_status_id', $status);
        } else {
            $query->where('project_status_id', '!=', 6);
        }
        if (!empty($from)) {
            $query->where('begin_date', '>=', DbDateFormat($from));
        }
        if (!empty($to)) {
            $query->where('begin_date', '<=', DbDateFormat($to));
        }
        if(!empty($customer)) {
            $query->where('customer_id', '=', $customer);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Project Name',
            'Start Date',
            'Deadline',
            'Billing Type',
            'Status',

        ];
    }

    public function map($project): array
    {
        return [
            $project->name,
            formatDate($project->begin_date),
            !empty($project->due_date) ? formatDate($project->due_date) : '-',
            $this->getChargeType($project->charge_type),
            !empty($project->projectStatuses) ? $project->projectStatuses->name : '',  
        ];
    }

    public function getChargeType($var)
    {
        if ($var == '1') {
            $type = 'Fixed Rate';
        } elseif ($var == '2') {
            $type = 'Project Hours';
        } elseif ($var == '3') {
            $type = 'Task Hours';
        }
        return $type;
    }
}
