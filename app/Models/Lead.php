<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
	public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'assignee_id');
    }

    public function leadStatus()
    {
        return $this->belongsTo('App\Models\LeadStatus', 'lead_status_id');
    }

    public function leadSource()
    {
        return $this->belongsTo('App\Models\LeadSource', 'lead_source_id');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function generateMonthlyLeadData($from = null, $to = null) 
    {   
        $leadData = [];
        $finalData = [];
        $dataFilter = [];
        $data = [];
        
        $from = DbDateFormat($from);
        $to = DbDateFormat($to);
        $data = $this->where('is_lost', 0);
        $data->with(['leadStatus:id,name,color,status', 'leadSource:id,name,status']);
        $data->whereBetween('created_at', [$from, $to]);
        $data = $data->get()->toArray();

        for ($i=0; $i < count($data); $i++) { 
            if ($data[$i]['lead_status']['status'] == 'active' && $data[$i]['lead_source']['status'] == 'active' ) {
                $dataFilter[$i]['first_name'] = $data[$i]['first_name'];
                $dataFilter[$i]['last_name'] = $data[$i]['last_name'];
                $dataFilter[$i]['email'] = $data[$i]['email'];
                $dataFilter[$i]['date'] = date('M Y', strtotime($data[$i]['created_at']));
                $dataFilter[$i]['lead_status_name'] = $data[$i]['lead_status']['name'];
                $dataFilter[$i]['lead_status_color'] = $data[$i]['lead_status']['color'];
                $dataFilter[$i]['lead_source_name'] = $data[$i]['lead_source']['name'];
            }
        }
        for ($i=0; $i < count($dataFilter); $i++) { 
            if (array_key_exists($dataFilter[$i]['lead_status_name'], $leadData)) {
                $leadData[$dataFilter[$i]['lead_status_name']][$dataFilter[$i]['date']][] = $dataFilter[$i];
            } else {
                $leadData[$dataFilter[$i]['lead_status_name']][$dataFilter[$i]['date']][] = $dataFilter[$i];
            }
        }

        foreach ($leadData as $label => $data) {
            foreach ($data as $month => $value) {
                $finalData[$label][$month] = count($value);
            }
        }

        return $finalData;
    }
}
