<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Role;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Http\Controllers\DateTime;

class TeamMemberListExport implements FromCollection, WithHeadings, WithMapping 
{
    /**
     * [Here we need to fetch data from data source]
     * @return [Database Object] [Here we are fetching data from User table and also role table through Eloquent Relationship]
     */
    public function collection()
    {
        $url_components = parse_url(url()->previous()); 
        $url_components = !empty($url_components['query']) ? explode('=', $url_components['query']) : null ;
        $data = User::select();
        if (!empty($url_components) && $url_components[1] == "active") {
          $data->where('is_active', 1);
        }
        if (!empty($url_components) && $url_components[1] == "inactive") {
          $data->where('is_active', 0);
        }
        return $data->orderBy('id', 'desc')->get();
    }

    /**
     * [Here we are putting Headinngs of The CSV]
     * @return [array] [Exel Headings]
     */
    public function headings(): array
    {
        return[
            'Name',
            'Email',
            'Role',
            'Phone',
            'Status',
            'Created At'
        ];
    }
    /**
     * [By adding WithMapping you map the data that needs to be added as row. This way you have control over the actual source for each column. In case of using the Eloquent query builder]
     * @param  [object] $userList [It has users table info and roles table info]
     * @return [array]            [comma separated value will be produced]
     */
    public function map($userList): array
    {
        return[
            $userList->full_name,
            $userList->email,
            !empty($userList->role) ? $userList->role->display_name : '',
            $userList->phone,
            ($userList->is_active == 1) ? "Active" : "Inactive",
            formatDate($userList->created_at).' '.timeZonegetTime($userList->created_at),
        ];
    }
}
