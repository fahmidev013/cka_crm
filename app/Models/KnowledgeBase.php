<?php
/**
 * @package KnowledgeBase
 * @author tehcvillage <support@techvill.org>
 * @contributor Sakawat Hossain Rony <[sakawat.techvill@gmail.com]>
 * @created 19-06-2021
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Validator;
use DB;

class KnowledgeBase extends Model
{
    public function group()
    {
        return $this->belongsTo("App\Models\Group", 'group_id');
    }

    /**
     * @return KnowledgeBase[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getAll()
    {
        $data = Cache::get('gb-knowledge_base');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-knowledge_base', $data, 30 * 86400);
        }
        return $data;
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function storeValidation($data = [])
    {
        $validator = Validator::make($data, [
            'subject' => 'required|max:290',
            'group_id' => 'required',
            'status' => 'required',
        ]);
        return $validator;
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function updateValidation($data = [], $id = null)
    {
        $validator = Validator::make($data, [
            'subject' => 'required|max:290',
            'slug' => ['required','max:290','unique:knowledge_bases,slug,' . $id],
            'group_id' => 'required',
            'status' => 'required',
        ]);
        return $validator;
    }

    /**
     * @param array $data
     * @return null
     */
    public function store($data = [])
    {
        $id = parent::insertGetId($data) ?? null;
        if (!empty($id)) {
            Cache::forget('gb-knowledge_base');
            return true;
        }
        return false;
    }

    /**
     * @param array $data
     * @param $id
     * @return bool
     */
    public function updateKnowledgeBase($data = [], $id)
    {
        $knowledgeBase = parent::where('id', $id);
        if ($knowledgeBase->exists()) {
            $knowledgeBase->update($data);
            Cache::forget('gb-knowledge_base');
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function remove($id)
    {
        $knowledgeBase = parent::find($id);
        if ($knowledgeBase->exists()) {
            $knowledgeBase->delete();
            Cache::forget('gb-knowledge_base');
            return true;
        } else {
            return false;
        }
    }

    public function groupCount()
    {
        $data = parent::where('status','publish')
                        ->groupBy('group_id')->select('group_id', DB::raw('count(*) as total'))
                        ->pluck('total', 'group_id')
                        ->toArray();
        return $data;
    }
}
