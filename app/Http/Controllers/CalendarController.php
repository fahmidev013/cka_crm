<?php
namespace App\Http\Controllers;

use App\Http\Start\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\SaleOrder;
use App\Models\Task;
use App\Models\CalendarEvent;

class CalendarController extends Controller
{

    public function index(Request $request)
    {
        $data['menu'] = 'calendar';
        $data['header'] = 'Calendar';
        $data['page_title'] = __('Calendar');
        $information = [];
        $checked = ['quotation', 'invoice', 'purchase', 'project', 'task', 'custom'];
        if ($request->isMethod('post')) {
            $checked = [];
            if ($request->quotation == 1) {
                $checked[] = 'quotation';
            }

            if ($request->invoice == 1) {
                $checked[] = 'invoice';
            }

            if ($request->purchase == 1) {
                $checked[] = 'purchase';
            }

            if ($request->project == 1) {
                $checked[] = 'project';
            }

            if ($request->task == 1) {
                $checked[] = 'task';
            }

            if ($request->custom == 1) {
                $checked[] = 'custom';
            }
        }

        if (in_array('quotation', $checked) || in_array('invoice', $checked)) {
            $saleOrders = SaleOrder::get(['id', 'transaction_type', 'reference', 'order_date']);
            if (!empty($saleOrders)) {
                foreach ($saleOrders as $saleOrder) {
                    $groupId = $color = $url = "";
                    if ($saleOrder->transaction_type == "SALESORDER" && in_array('quotation', $checked)) {
                        $information[] = [
                        'groupId' => 1,
                        'title' => formatText($saleOrder->reference),
                        'start' => Carbon::parse($saleOrder->order_date)->toDateString(),
                        'end' => Carbon::parse($saleOrder->order_date)->toDateString(),
                        'color' => "#561627",
                        'url' => url('order/view-order-details/' . $saleOrder->id)
                    ];
                    } else if ($saleOrder->transaction_type == "SALESINVOICE" && in_array('invoice', $checked)) {
                        $information[] = [
                        'groupId' => 2,
                        'title' => formatText($saleOrder->reference),
                        'start' => Carbon::parse($saleOrder->order_date)->toDateString(),
                        'end' => Carbon::parse($saleOrder->order_date)->toDateString(),
                        'color' => "#165653",
                        'url' => url('invoice/view-detail-invoice/' . $saleOrder->id)
                    ];
                    }
                }
            }
        }

        
        if (in_array('purchase', $checked)) {
            $purchaseOrders = PurchaseOrder::get(['id', 'reference', 'order_date']);
            if (!empty($purchaseOrders)) {
                foreach ($purchaseOrders as $purchaseOrder) {
                    $information[] = [
                        'groupId' => 3,
                        'title' => formatText($purchaseOrder->reference),
                        'start' => Carbon::parse($purchaseOrder->order_date)->toDateString(),
                        'end' => Carbon::parse($purchaseOrder->order_date)->toDateString(),
                        'color' => "#966e57",
                        'url' => url('purchase/view-purchase-details/' . $purchaseOrder->id)
                    ];
                }
            }
        }

        if (in_array('project', $checked)) {
            $projects = Project::get(['id', 'name', 'begin_date', 'due_date']);
            if (!empty($projects)) {
                foreach ($projects as $project) {
                    $information[] = [
                        'groupId' => 4,
                        'title' => formatText($project->name),
                        'start' => Carbon::parse($project->begin_date)->toDateString(),
                        'end' => Carbon::parse($project->due_date)->toDateString(),
                        'color' => "#575996",
                        'url' => url('project/details/' . $project->id)
                    ];
                }
            }
        }

        if (in_array('task', $checked)) {
            $tasks = Task::get(['id', 'name', 'start_date', 'due_date']);
            if (!empty($tasks)) {
                foreach ($tasks as $task) {
                    $information[] = [
                        'groupId' => 5,
                        'title' => formatText($task->name),
                        'start' => Carbon::parse($task->start_date)->toDateString(),
                        'end' => Carbon::parse($task->due_date)->toDateString(),
                        'color' => "#1f827b",
                        'url' => url('task/v/' . $task->id)
                    ];
                }
            }
        }

        if (in_array('custom', $checked)) {
            $events = CalendarEvent::get(['id', 'title', 'description', 'start_date', 'end_date', 'event_color']);
            if (!empty($events)) {
                foreach ($events as $event) {
                    $information[] = [
                        'id' => $event->id,
                        'groupId' => 6,
                        'title' => formatText($event->title),
                        'start' => Carbon::parse($event->start_date)->toDateString(),
                        'end' => Carbon::parse($event->end_date)->toDateString(),
                        'color' => $event->event_color,
                        'description' => formatText($event->description),
                    ];
                }
            }
        }

        $data['information'] = json_encode($information);
        $data['initialDate'] = Carbon::today()->toDateString();
        $data['checked'] = $checked;

        return view('admin.calender.calendar', $data);
    }

    public function storeEvent(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'start_date' => 'required',
        ]);
        $data = ['status' => 'fail', 'message' => __('Something went wrong, please try again.') ];
        if (isset($request->eventId) && !empty($request->eventId)) {
            $eventUpdate = CalendarEvent::where('id', $request->eventId)
                           ->update([
                                'title' => stripBeforeSave($request->title),
                                'description' => $request->description,
                                'start_date' => DbDateFormat($request->start_date),
                                'end_date' => !empty($request->end_date) ? DbDateFormat($request->end_date) : DbDateFormat($request->start_date),
                                'event_color' => $request->event_color,
                                'created_by' => Auth::user()->id,
                           ]);
            $data['status'] = 'success';
            $data['message'] = __('Successfully updated');
        } else {
            $event  = new CalendarEvent;
            $event->title = stripBeforeSave($request->title);
            $event->description = $request->description;
            $event->start_date = DbDateFormat($request->start_date);
            $event->end_date = !empty($request->end_date) ? DbDateFormat($request->end_date) : DbDateFormat($request->start_date);
            $event->event_color = $request->event_color;
            $event->created_by = Auth::user()->id;
            if ($event->save()) {
                $data['status'] = 'success';
                $data['message'] = __('Successfully Saved');
            } 
        }
        Session::flash($data['status'], $data['message']);
        return redirect()->back();
    }

    public function deleteEvent(Request $request)
    {
        $data['status'] = 0;
        if (!empty($request->id)) {
            if (CalendarEvent::where('id', $request->id)->delete()) {
                $data['status'] = 1;
            }
        }
        return $data;
    }
    
}