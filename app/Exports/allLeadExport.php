<?php

namespace App\Exports;



use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class allLeadExport implements WithHeadings, WithMapping, FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $from = isset($_GET['from']) ? $_GET['from'] : null;
        $to = isset($_GET['to']) ? $_GET['to'] : null;

        $leads = Lead::all();
        
        if ($from && $to) {
            $from   = DbDateFormat($from);
            $to     = DbDateFormat($to);
        }
        $assignee   = isset($_GET['assignee']) ? $_GET['assignee'] : null;
        $leadStatus = isset($_GET['leadStatus']) ? $_GET['leadStatus'] : null; 
        $leadSource = isset($_GET['leadSource']) ? $_GET['leadSource'] : null;

        if (isset($from) && $from != null) {
            $leads  = $leads->where('created_at', '>=', $from .' 00:00:00');
        }
        if (isset($to) && $to != null) {
            $leads  = $leads->where('created_at', '<=', $to .' 23:59:59');
        }
        if (isset($assignee) && $assignee) {
            $leads  = $leads->where('assignee_id', $assignee);            
        }
        if (isset($leadStatus) && $leadStatus) {
            $leads  = $leads->whereIn('lead_status_id', $leadStatus);                    
        }
        if (isset($leadSource) && $leadSource) {
            $leads  = $leads->where('lead_source_id', $leadSource);            
        }
        return $leads;
    }



    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Company',
            'Website',
            'Country',
            'Status',
            'Source',
            'Last Contact',
            'Created At',
        ];
    }

    public function map($lead): array
    {   if (!empty($lead->country)) {
            $str= $lead->country->name;
        } else {
            $str=" ";
        }
        $lastContact = (@strtotime($lead->last_contact)) > 0 ? $lead->last_contact : '-';
        return [
            $lead->first_name." ".$lead->last_name ,
            $lead->email,
            $lead->phone,
            $lead->company,
            $lead->website,
            $str,
            isset($lead->leadStatus->name) ? $lead->leadStatus->name : '',
            $lead->leadSource->name,
            $lastContact,
            $lead->created_at,
            
        ];
    }

}
