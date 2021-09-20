<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Milestone;

class MileStoneExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $milestones = Milestone::where('milestones.project_id', intval($_GET['project']));
        return $milestones;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Due Date",
            "Created",
        ];
    }

    public function map($milestones): array
    {  
        return [
            $milestones->name,
            formatDate($milestones->due_date),
            formatDate($milestones->created_at),
        ];
    }
}
