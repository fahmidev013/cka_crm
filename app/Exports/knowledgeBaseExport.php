<?php
/**
 * @package knowledgeBaseExport
 * @author tehcvillage <support@techvill.org>
 * @contributor Sakawat Hossain Rony <[sakawat.techvill@gmail.com]>
 * @created 19-06-2021
 */
namespace App\Exports;

use App\Models\{
    Group,
    KnowledgeBase,
};
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class knowledgeBaseExport implements WithHeadings, WithMapping, FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $knowledges = KnowledgeBase::getAll();
        $group      = isset($_GET['group_id']) && !empty($_GET['group_id']) ? $_GET['group_id'] : null;
        $from       = isset($_GET['from']) && !empty($_GET['from']) ? DbDateFormat($_GET['from']) : null;
        $to         = isset($_GET['to']) && !empty($_GET['to']) ? DbDateFormat($_GET['to']) : null;
        $status     = isset($_GET['status']) && !empty($_GET['status']) ? $_GET['status'] : null;
        if (!empty($group)) {
            $knowledges = $knowledges->where('group_id', $group);
        }
        if (!empty($status)) {
            $knowledges = $knowledges->where('status', $status);
        }
        if (!empty($from)) {
            $knowledges = $knowledges->where('publish_date', '>=', $from);
        }
        if (!empty($to)) {
            $knowledges = $knowledges->where('publish_date', '<=', $to);
        }
        return $knowledges;
    }

    public function headings(): array
    {
        return [
            'Group',
            'Subject',
            'Status',
            'Date Published',
        ];
    }

    public function map($knowledge): array
    {
        $groupName = "";
        if (!empty($knowledge->group_id)) {
            $group      = Group::getAll()->where('id', $knowledge->group_id)->first();
            $groupName  = isset($group->name) ? $group->name : "";
        }
        $date_publish = !empty($knowledge->publish_date) ? formatDate($knowledge->publish_date) : null;
        $status       = ucfirst($knowledge->status);
        return [
            $groupName,
            $knowledge->subject,
            $status,
            $date_publish,
        ];
    }
}
