<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChecklistItem;
use App\Models\Task;


class CheckListController extends Controller
{

	// when task is edited, To Add(AJAX)
    public function add(Request $request) {
        $data = ['message' => __("Checklist item already exist")];
        if(!empty($request->task_id)) {
            $task = Task::find($request->task_id);
            $newChecklistToInsert = new ChecklistItem;
            $newChecklistToInsert->task_id      = $request->task_id;
            $newChecklistToInsert->title        = $request->title;
            $newChecklistToInsert->is_checked       = 0;
            $newChecklistToInsert->save();
            $data['item'] = $newChecklistToInsert;
            $data['message']   = "success";
        } 
        
        return $data;
    }

    public function edit(Request $request)
    {
        if (ChecklistItem::where('title', $request->title)->where('id' , '!=' , $request->id)->where('task_id', $request->task_id)->exists()) {
            return -1;
        }
        if (!empty($request->id)) {
	        $relatedCheckList= ChecklistItem::find($request->id);
	        $relatedCheckList->title = $request->title;
	        $relatedCheckList->save();
	        return $relatedCheckList;
        }
    }

    // when changing status from view(AJAX)
    public function statusChange(Request $request) {
        $data = ['message' => "fail"];
        $relatedCheckList = ChecklistItem::find($request->id);
        if ($relatedCheckList) {
            $isChecked = $relatedCheckList->status;
            if($request->status == "true") {
                $relatedCheckList->is_checked = 1;
                $relatedCheckList->checked_at = now();
            } else {
                $relatedCheckList->is_checked = 0;

            }
            if ($relatedCheckList->save()) {
                $data['message']   = "success";
                $data['item'] = $relatedCheckList;
            }
        }
        
        return $data;
    }

    public function destroy(Request $request)
    {
        $data = ['status' => 0, 'message' => "fail"];
        $relatedCheckList= ChecklistItem::find($request->id);
        if($relatedCheckList) {
            if ($relatedCheckList->delete()) {
                $data['status']   = 1;
                $data['message']   = "success";
            }
        }
        
        return $data;
    }
    

    // when task is edited, To change status(AJAX)
    public function checkListEditChangeStatus(Request $request) {
        $data = ['message' => "fail"];
        $relatedCheckList = ChecklistItem::where("task_id", $request->task_id)
                                        ->where("title", $request->item_name)
                                        ->first();
        if ($relatedCheckList) {
            $isChecked = $relatedCheckList->status;
            if($isChecked == 'unchecked') {
                $relatedCheckList->status = "checked";
                $relatedCheckList->checked_at = now();
            } else if($isChecked == 'checked') {
                $relatedCheckList->status = "unchecked";
                $relatedCheckList->checked_at = now();
            }
            if ($relatedCheckList->save()) {
                $data['message']   = "success";
            }
        }
        
        return $data;
    }

}
