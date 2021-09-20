<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['subject', 'content'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getAllNoteByCustomer($from, $to, $id)
    {
        $noteData = Note::where(['related_to_id' => $id, 'related_to_type' => 'customer']);
        if (!empty($from)) {
            $noteData = $noteData->whereDate('created_at', '>=', DbDateTimeFormat($from))
                                 ->whereDate('created_at', '<=', DbDateTimeFormat($to))->orderBy('id', 'DESC');
        }

    	return $noteData;
    }

    public function getAllNoteByCustomerCSV($from, $to, $customer_id)
    {
        $data  = Note::where(['related_to_id'=>$customer_id, 'related_to_type' => 'customer']);
        if ($from && $to) {
            $data=null;
            $from = DbDateFormat($from);
            $to   = DbDateFormat($to);
            $data  = Note::where(['related_to_id' => $customer_id, 'related_to_type' => 'customer'])
            ->whereDate('created_at', '>=', DbDateTimeFormat($from))
            ->whereDate('created_at', '<=', DbDateTimeFormat($to))->orderBy('id', 'DESC');
        }

        return $data;
    }
}
