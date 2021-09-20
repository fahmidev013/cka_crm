<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TagAssign;
use DB;
class TagController extends Controller
{
      public function store(Request $request) {
        $task_id   = $request->task_id;
        $tag_name  = $request->tag_name;
        $newTag    = explode(',', $tag_name);
        $newTagIds = [];
        if (!empty($task_id) && !empty($tag_name)) {
            $oldTag = DB::table('tags')->pluck('name', 'id')->toArray();
            if (!empty($oldTag)) {
                foreach ($oldTag as $key => $value) {
                    if (in_array($value, $newTag)) {
                        $newTagIds[] = $key;
                    }
                }
                if ($newTagIds) {
                    $newTagIds = implode(" ", $newTagIds);
                    $tagExist = TagAssign::where(['tag_type' => 'task', 'tag_id' => $newTagIds, 'reference_id' => $task_id])->exists();
                    if ( $tagExist == false) {
                        DB::table('tag_assigns')->insert(['tag_type' => 'task', 'tag_id' => $newTagIds, 'reference_id' => $task_id]);
                    }
                }

                if (!in_array($tag_name, $oldTag)) {
                    $lastInsertId = DB::table('tags')->insertGetId(['name' => $tag_name]);
                    if ($lastInsertId) {
                        $datas = [];
                        $datas['tag_type']    = 'task';
                        $datas['tag_id']  = $lastInsertId;
                        $datas['reference_id'] = $task_id;
                        DB::table('tag_assigns')->insert($datas);
                    }
                }
            } else {
                $lastInsertId = DB::table('tags')->insertGetId(['name' => $tag_name]);
                if ($lastInsertId) {
                    $datas = [];
                    $datas['tag_type']    = 'task';
                    $datas['tag_id']  = $lastInsertId;
                    $datas['reference_id'] = $task_id;
                    DB::table('tag_assigns')->insert($datas);
                }
            }
        }
    }

    public function delete(Request $request) {
        $task_id   = $request->task_id;
        $tag_name  = $request->tag_name;
        $newTag    = explode(',', $tag_name);
        $oldTag    = DB::table('tags')->pluck('name', 'id')->toArray();
        $newTagIds = [];
        foreach ($oldTag as $key => $value) {
            if (in_array($value, $newTag)) {
                $newTagIds[] = $key;
                $newTagIds   = implode(" ", $newTagIds);
            }
        }
        if ($task_id && $tag_name) {
            DB::table('tag_assigns')->where(['reference_id' => $task_id, 'tag_id' => $newTagIds, 'tag_type' => 'task'])->delete();
            DB::table('tags')->where(['id' => $newTagIds])->delete();
        }
    }

    /**
     * [getTaskAllTag description]
     * @return [type] [description]
     */
    public function getAll() 
    {
        $id = $_GET['task_id'];
        $results = DB::table('tag_assigns')
            ->leftJoin('tags', 'tags.id', '=', 'tag_assigns.tag_id')
            ->where(['tag_assigns.reference_id' => $id, 'tag_type' => 'task'])
            ->select('name')
            ->get();
        $tags = [];
        foreach ($results as $key => $value) {
            $tags[$key] = $value->name;
        }
        return $tags;
    }
}
