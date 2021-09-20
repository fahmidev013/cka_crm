<?php
/**
 * @package Group
 * @author tehcvillage <support@techvill.org>
 * @contributor Sakawat Hossain Rony <[sakawat.techvill@gmail.com]>
 * @created 19-06-2021
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Validator;

class Group extends Model
{
    /**
     * @return group[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getAll()
    {
        $data = Cache::get('gb-groups');
        if (empty($data)) {
            $data = parent::all();
            Cache::put('gb-groups', $data, 30 * 86400);
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
            'name' => 'required|max:50|unique:groups,name',
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
        if (parent::insert($data)) {
            Cache::forget('gb-groups');
            return true;
        }

        return false;
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function updateValidation($data = [], $id = null)
    {
        $validator = Validator::make($data, [
            'name' => ['required','max:50','unique:groups,name,' . $id],
            'status' => 'required',
        ]);
        return $validator;
    }

    /**
     * @param array $data
     * @param $id
     * @return bool
     */
    public function updateGroup($data = [], $id)
    {
        $group = parent::where('id', $id);
        if ($group->exists()) {
            $group->update($data);
            Cache::forget('gb-groups');
            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function remove($id)
    {
        $group = parent::find($id);
        if ($group->exists()) {
            KnowledgeBase::where('group_id', $id)->delete();
            $group->delete();
            Cache::forget('gb-groups');
            Cache::forget('gb-knowledge_base');
            return true;
        }

        return false;
    }
}
