<?php

namespace App\Http\Controllers;

use Cache;
use Excel;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Preference;
use App\Models\TaskTimer;
use App\DataTables\TimesheetDataTable;
use App\Exports\TimesheetExport;

class TimesheetController extends Controller
{
    public function index(TimesheetDataTable $timesheetDataTable)
    {
        $data = $this->getValueFromView();
        $data['menu']      = 'time_sheet';
        $data['page_title'] = __('Timesheets');

        $taskTimer = new TaskTimer();
        $timesheetDetails = $taskTimer->getTaskTime(['from' => $data['from'], 'to' => $data['to'], 'assinee_id' => $data['assignee'], 'project_id' => $data['project'], 'running' => $data['running']])->select('task_timers.start_time', 'task_timers.end_time')->get();

        $totalTime = 0;
        foreach ($timesheetDetails as $timesheetDetail) {
            if (!empty($timesheetDetail->end_time)) {
                $diff = ($timesheetDetail->end_time > $timesheetDetail->start_time) ? ($timesheetDetail->end_time - $timesheetDetail->start_time) : null;
            } else {
                $timesheetDetail->start_time = (int) $timesheetDetail->start_time;
                $diff = (time() > $timesheetDetail->start_time) ?  time() - $timesheetDetail->start_time : null;
            }
            $totalTime = $totalTime + $diff;
        }

        $data['hours']    = floor($totalTime / 3600) > 0 ? floor($totalTime / 3600) : 0;
        $data['minutes']  = floor(($totalTime / 60) % 60) > 0 ? floor(($totalTime / 60) % 60) : 0;
        $data['seconds']  = $totalTime % 60;

        $data['assignees'] = DB::table('users')->where('is_active', 1)->get();
        $data['projects']  = DB::table('projects')->where('project_status_id', '!=', 6)->select('id', 'name')->get();

    	$row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;
        return $timesheetDataTable->with('row_per_page', $row_per_page)->render('admin.timeSheet.list', $data);
    }

    public function getValueFromView()
    {
        $data['from']      = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to']        = isset($_GET['to']) ? $_GET['to'] : null;
        $data['project']   = isset($_GET['project']) ? $_GET['project'] : null;
        $data['assignee']  = isset($_GET['assignee']) ? $_GET['assignee'] : null;
        $data['running']   = isset($_GET['running']) ? $_GET['running'] : null;
        return $data;
    }

    public function timesheetListCsv()
    {
    	return Excel::download(new TimesheetExport(), 'time_sheet'.time().'.csv');
    }

    public function getTotalTimeSheet($timerDetails)
    {
        $totalWork = 0;
        $timerDetails = $timerDetails->toArray();

        foreach ($timerDetails as $timesheetDetails) {
            if (!empty($timesheetDetails->end_time)) {
                $diff = ($timesheetDetails->end_time > $timesheetDetails->start_time) ? ($timesheetDetails->end_time - $timesheetDetails->start_time) : null;
            } else {
                $timesheetDetails->start_time = (int) $timesheetDetails->start_time;
                $diff = (time() > $timesheetDetails->start_time) ?  time() - $timesheetDetails->start_time : null;
            }
            $totalWork += $diff;
        }
        $hours    = floor($totalWork / 3600) > 0 ? floor($totalWork / 3600) . 'h ' : '';
        $minutes  = floor(($totalWork / 60) % 60) > 0 ? floor(($totalWork / 60) % 60) . 'm ' : '';
        $seconds  = $totalWork % 60;
        $diffTime = $hours . $minutes . $seconds . 's';

        return $diffTime;
    }

    public function timesheetListPdf()
    {
        $taskTimer = new TaskTimer();
        $data = $this->getValueFromView();

        $data['timerDetails'] = $sheetTimerDetails = $taskTimer->getTaskTime(['from' => $data['from'], 'to' => $data['to'], 'assinee_id' => $data['assignee'], 'project_id' => $data['project'], 'running' => $data['running'] ])->select('task_timers.*', 'users.id as user_id', 'users.full_name', 'tasks.id','tasks.name as taskName', 'projects.name')->get();
        
        $data['totalTimesheet'] = $this->getTotalTimeSheet($sheetTimerDetails);

        $data['date_range'] = ($data['from'] && $data['to']) ? formatDate($data['from']) . __('To') . formatDate($data['to']) : 'No Date Selected';
        $data['company_logo']   = Preference::getAll()->where('category','company')->where('field', 'company_logo')->first('value');
        return printPDF($data, 'time_sheet_list_pdf' . time() . '.pdf', 'admin.timeSheet.list_pdf', view('admin.timeSheet.list_pdf', $data), 'pdf', 'domPdf');
    }

    public function deleteTimesheet($id)
    {
        if (isset($id)) {
            DB::table('task_timers')->where(['id' => $id])->delete();
            Session::flash('success', __('Deleted Successfully.'));
            return redirect()->intended('time-sheet/list');
        }

    }
}
