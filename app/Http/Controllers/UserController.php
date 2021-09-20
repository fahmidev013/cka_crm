<?php

namespace App\Http\Controllers;

use App\DataTables\{
  UserInvoiceListDataTable,
  UserListDataTable,
  UserOrderListDataTable,
  UserPaymentListDataTable,
  UserProjectDataTable,
  UserPurchPaymentDataTable,
  UserQuotationListDataTable,
  UserTaskDataTable,
  UserTransferDataTable
};
use App\Exports\{
  teamMemberInvoiceExport,
  teamMemberInvoicePaymentExport,
  teamMemberPurchasePaymentExport,
  teamMemberQuotationExport,
  teamMemberTasksExport,
  TeamMemberListExport,
  teamMemberProjectExport,
  TeamMemberLedgerCsv
};
use App\Models\{
  CustomerTransaction,
  Country,
  Department,
  SaleOrder,
  Preference,
  PurchaseOrder,
  Shipment,
  Rule,
  Supplier,
  SupplierTransaction,
  Task,
  File,
  Location,
  Customer,
  TaskStatus,
  User,
  Currency,
  PaymentMethod,
  EmailTemplate,
  StockTransfer,
  Role
};
use App\Http\Requests;
use App\Http\Start\Helpers;
use App\Rules\CheckValidEmail;
use Auth;
use DB;
use Excel;
use Input;
use Illuminate\Http\Request;
use PDF;
use Exception;
use Session;
use Cache;
use Validator;
use App\Models\ProjectMember;
use App\Models\Project;
use Intervention\Image\ImageManagerStatic as Image;
use App\Rules\CheckValidFile;

class UserController extends Controller
{
     public function __construct(UserOrderListDataTable $userOrderListDataTable,UserQuotationListDataTable $userQuotationListDataTable,UserInvoiceListDataTable $userInvoiceListDataTable, UserPaymentListDataTable $userPaymentListDataTable, UserPurchPaymentDataTable $userPurchPaymentDataTable,UserProjectDataTable $userProjectDataTable, UserTaskDataTable $userTaskDataTable, UserTransferDataTable $userTransferDataTable, SaleOrder $saleOrder, EmailController $email, Project $project)
     {
            if (request()->segment(1) == 'edit-user') {
                $id = request()->segment(2);
                $user = User::find($id);
                if (empty($user)) {
                    Session::flash('fail', __('Customer does not exist.'));
                    header('Location:'. url('users'));
                    exit;
                }
            } elseif (request()->segment(1) == "user") {
                $action = request()->segment(2);
                if (in_array($action, [ 'purchase-list', 'sales-order-list', 'sales-invoice-list', 'user-transfer-list', 'user-payment-list', 'user-purchase-payment-list', 'team-member-profile', 'project-list'])) {
                    $id = request()->segment(3);
                    $user = User::find($id);
                    if (empty($user)) {
                        Session::flash('fail', __('User not found'));
                        header('Location:'. url('users'));
                        exit;
                    }
                }
            }
            $this->userOrderListDataTable = $userOrderListDataTable;
            $this->userQuotationListDataTable = $userQuotationListDataTable;
            $this->userInvoiceListDataTable = $userInvoiceListDataTable;
            $this->userPaymentListDataTable = $userPaymentListDataTable;
            $this->userPurchPaymentDataTable = $userPurchPaymentDataTable;
            $this->userProjectDataTable = $userProjectDataTable;
            $this->userTaskDataTable = $userTaskDataTable;
            $this->userTransferDataTable = $userTransferDataTable;
            $this->saleOrder = $saleOrder;
            $this->email = $email;
            $this->project = $project;
      }

    /**
     * Display a listing of the Users.
     *
     * @return User List page view
     */
    public function index(UserListDataTable $dataTable)
    {
        $id = Auth::user()->id;
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'users';
        $data['page_title'] = __('Users');
        $data['userCount'] = User::all()->count();
        $data['userActive'] = User::where('is_active', 1)->count();
        $data['userInActive'] = User::where('is_active', 0)->count();
        $row_per_page = Preference::getAll()
                        ->where('category', 'preference')
                        ->where('field', 'row_per_page')
                        ->first('value')
                        ->value;
        return $dataTable->with('row_per_page', $row_per_page)->render('admin.user.user_list', $data);
    }

    public function changeActiveStatus(Request $request)
    {
        $user           = User::find($request->id);
        $user->is_active = $request->status;
        $user->save();

        $data['userCount']    = User::all()->count();
        $data['userActive']   = User::where('is_active', 1)->count();
        $data['userInActive'] = User::where('is_active', 0)->count();
        $data['status']       = 'success';
        return $data;
    }

    /**
     * Show the form for creating a new User.
     *
     * @return User cerate page view
     */
    public function create()
    {
        $data['menu'] = 'relationship';
        $data['list_menu'] = 'users';
        $data['sub_menu'] = 'users';
        $data['page_title'] = __('Create User');
        $data['departments'] = DB::table('departments')->get();
        $data['roleData'] = DB::table('roles')->get();

        return view('admin.user.user_add', $data);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return User List page view
     */
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'email' => ['required','unique:users,email', new CheckValidEmail],
        'first_name' => 'required|max:30',
        'last_name' => 'required|max:30',
        'password' => 'required',
        'role_id' => 'required',
        'status_id' => 'required',
        'attachment'  => [new CheckValidFile(getFileExtensions(2))],
      ]);
      if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
      }
      try {
        DB::beginTransaction();
        if ($request->is_installer) {
          $data['id'] = 1;
        }
        $data['added_by'] = empty($request->is_installer) ? auth()->user()->id : null;
        $data['password'] = \Hash::make($request->password);
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['full_name'] = $request->first_name . ' ' . $request->last_name;
        $data['role_id'] = $request->role_id;
        $data['phone'] = $request->phone;
        $data['email'] = validateEmail($request->email) ? trim($request->email) : null;
        $data['is_active'] = $request->status_id;
        $data['created_at'] = date('Y-m-d H:i:s');

        $id = DB::table('users')->insertGetId($data);

        if (isset($request->sendMail) && isset($request->email) && !empty($request->email) && validateEmail($request->email)) {
            // Retrive preference value and field name
            $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
            // Retrive Set Password email template
            $data['emailInfo'] = EmailTemplate::where(['template_id' => 24, 'language_short_name' => $prefer['dflt_lang']])->first(['subject', 'body']);
            $subject =  $data['emailInfo']['subject'];
            $message =  $data['emailInfo']['body'];
            // Replacing template variable
            $subject = str_replace('{company_name}', $prefer['company_name'], $subject);
            $message = str_replace('{user_name}', $request->first_name.' '.$request->last_name, $message);
            $message = str_replace('{user_id}', $request->email, $message);
            $message = str_replace('{user_pass}', $request->password, $message);
            $message = str_replace('{assigned_by_whom}', empty($request->is_installer) ? auth()->user()->first_name.' '.auth()->user()->last_name: null, $message);
            $message = str_replace('{company_url}', url('/admin'), $message);
            $message = str_replace('{company_name}', $prefer['company_name'], $message);
            // Send Mail to the customer
            $emailResponse = $this->email->sendEmail($request->email, $subject, $message, null, $prefer['company_name']);

            if ($emailResponse['status'] == false) {
                 \DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['fail' => $emailResponse['message']]);
            }
        }

        unset($data);
        // File Upload
        if (isset($request->attachment) && !empty($request->attachment) && $request->hasFile('attachment')) {
          $path = createDirectory("public/uploads/user");
          $fileIdList = (new File)->store([$request->attachment], $path, 'USER', $id, ['isUploaded' => false, 'isOriginalNameRequired' => true]);

          if (isset($fileIdList[0]) && !empty($fileIdList[0])) {
            $uploadedFileName = File::find($fileIdList[0])->file_name;
            $uploadedFilePath = asset($path . '/' . $uploadedFileName);
            $thumbnailPath = createDirectory("public/uploads/user/thumbnail");
            (new File)->resizeImageThumbnail($uploadedFilePath, $uploadedFileName, $thumbnailPath);
          }
        }
        // File Upload End
        if (empty($request->is_installer)) {
          DB::table('role_users')->insertGetId(['user_id'=>$id,'role_id'=>$request->role_id]);
        }
        Cache::forget('gb-role_users');
        $department = isset($request->departments) ? $request->departments : NULL;
        if ($id && ! empty($department)) {
            foreach ($department as $value) {
              $data['user_id'] = $id;
              $data['department_id'] = $value;
              DB::table('user_departments')->insert($data);
          }
        }
        DB::commit();

        \Session::flash('success', __('Successfully Saved'));
        return redirect()->intended('users');
      } catch (Exception $e) {
        DB::rollBack();
        return back()->withInput()->withErrors(['email' => __('Invalid Request')]);
      }
    }


    /**
     * Remove the specified User from storage.
     *
     * @param  int  $id
     * @return User List page view
     */
    public function destroy($id)
    {
      if (($id == 1) && (Auth::guard('user')->user()->id != $id)) {
        \Session::flash('fail', __('Your are not authorize to change admin information.'));
        return back();
      }

      $user = User::find($id);
      if (!empty($user)) {
        try {
            $fileInfo = File::where(['object_type' => 'USER', 'object_id' => $id])->first();
            $oldFileName = isset($fileInfo) && !empty($fileInfo) ? $fileInfo->file_name : null;
            $thumbnailPath = createDirectory('public/uploads/user/thumbnail');

            DB::beginTransaction();
            DB::table('activities')->where('user_id', '=', $id)->delete();
            DB::table('notes')->where('user_id', '=', $id)->delete();
            DB::table('project_members')->where('user_id', '=', $id)->delete();
            DB::table('task_assigns')->where('user_id', '=', $id)->delete();
            DB::table('task_comments')->where('user_id', '=', $id)->delete();
            DB::table('task_timers')->where('user_id', '=', $id)->delete();
            DB::table('ticket_replies')->where('user_id', '=', $id)->delete();
            DB::table('user_departments')->where('user_id', '=', $id)->delete();
            DB::table('role_users')->where('user_id', '=', $id)->delete();
            DB::table('calendar_events')->where('created_by', '=', $id)->delete();

            DB::table('customer_transactions')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('deposits')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('expenses')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('general_ledger_transactions')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('leads')->where('assignee_id', $id)->update(['assignee_id' => null]);
            DB::table('leads')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('projects')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('purchase_orders')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('received_orders')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('sale_orders')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('stock_adjustments')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('stock_moves')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('stock_transfers')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('supplier_transactions')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('tasks')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('tickets')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('tickets')->where('assigned_member_id', $id)->update(['assigned_member_id' => null]);
            DB::table('transactions')->where('user_id', $id)->update(['user_id' => null]);
            DB::table('transfers')->where('user_id', $id)->update(['user_id' => null]);

            $res = $user->delete();
            DB::commit();

            if ($res) {
              #delete file region
              $fileIds = array_column(json_decode(json_encode(File::Where(['object_type' => 'USER', 'object_id' => $id])->get(['id'])), true), 'id');
              if (isset($fileIds) && !empty($fileIds)) {
                  (new File)->deleteFiles('USER', $id, ['ids' => [$fileIds], 'isExceptId' => false], $path = 'public/uploads/user');
                  if (isset($oldFileName) && !empty($oldFileName)) {
                    if (file_exists($thumbnailPath . '/' . $oldFileName)) {
                      unlink(base_path().'/'.$thumbnailPath . '/' . $oldFileName);
                    }
                  }
                  (new File)->deleteFiles('USER', $id, ['ids' => [$fileIds], 'isExceptId' => false], $path = 'public/uploads/user/thumbnail');
              }
              #end region
            }
            \Session::flash('success', __('Deleted Successfully.'));
            return redirect()->intended('users');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
      }
    }

    /**
     * Import user.
     *
     * @return import user page view
     */
    public function importUser()
    {
        $data['menu'] = 'relationship';
        $data['sub_menu'] = 'users';
        $data['page_title'] = __('User Import');

        return view('admin.user.user_import', $data);
    }

    public function importUserCSV(Request $request)
    {
        if ($request->hasFile('item_image')) {

            $file = $request->file('item_image');

            $validator = Validator::make(
                [
                    'file'      => $file,
                    'extension' => strtolower($file->getClientOriginalExtension()),
                ],
                [
                    'file'      => 'required',
                    'extension' => 'required|in:csv',
                ]
            );

            if ($validator->fails()) {
                return back()->withErrors(['email' => __("File type Invalid")]);
            }

            if (Input::hasFile('item_image')) {
                $path = Input::file('item_image')->getRealPath();
                $csv = [];

                if (is_uploaded_file($path)) {
                    $csv = readCSVFile($path, true);
                }

                if (empty($csv)) {
                    return back()->withErrors(__("Your CSV has no data to import"));
                }

                $requiredHeader  = array("First Name", "Last Name", "Email", "Password", "Role", 'Status');
                $header = array_keys($csv[0]);

                // Check if required headers are available or not
                if (!empty(array_diff($requiredHeader, $header))) {
                    return back()->withErrors( __("Please Check CSV Header Name."));
                }

                $errorMessages = [];
                $availableRoles = Role::getAll()->pluck('id', 'name')->toArray();
                $availableRoles = array_change_key_case($availableRoles, CASE_LOWER);
                $emails = User::pluck('email')->toArray();
                $emails = array_change_key_case($emails, CASE_LOWER);

                foreach ($csv as $key => $value) {
                    $errorFails = [];

                    $value = array_map('trim', $value);
                    // check if first name is empty
                    if (empty($value['First Name'])) {
                        $errorFails[] = __(':? is required', ['?' => __('First Name')]);
                    }

                    // check if the last name is empty
                    if (empty($value['Last Name'])) {
                        $errorFails[] = __(':? is required', ['?' => __('Last Name')]);
                    }

                    // check if there is any value in the email field then the email is not used and a valid email
                    if (empty($value['Email'])) {
                        $errorFails[] = __(':? is required', ['?' => __('Email')]);
                    } else if (empty(validateEmail($value['Email']))) {
                        $errorFails[] = __('Enter a valid email');
                    } else if (in_array(strtolower($value['Email']), $emails)) {
                        $errorFails[] = __(':? is already taken.', ['?' => __('Email')]);
                    }

                    // check if the password is not empty and contains at least five characters
                    if (empty($value['Password'])) {
                        $errorFails[] = __(':? is required', ['?' => __('Password')]);
                    } else if (strlen($value['Password']) < 5) {
                        $errorFails[] = __('Password should be at least 5 characters');
                    }

                    // check if role is not empty and the inserted role is available
                    if (empty($value['Role'])) {
                        $errorFails[] = __(':? is required', ['?' => __('Role')]);
                    } else if (!array_key_exists(strtolower($value['Role']), $availableRoles)) {
                        $errorFails[] = __(':? not found', ['?' => __('Role')]);
                    }

                    // check if the status is valid
                    if (empty($value['Status'])) {
                        $errorFails[] = __(':? is required', ['?' => __('Status')]);
                    } else if (!(strtolower($value["Status"]) == 'active' || strtolower($value["Status"]) == 'inactive')) {
                        $errorFails[] = __('Status can be either :? or :x', ['?' => __('Active'), 'x' => __('Inactive')]);
                    }

                    // check if the phone number is a valid phone number
                    if (!empty($value['Phone']) && empty(validatePhoneNumber($value['Phone']))) {
                      $errorFails[] = __('Enter a valid :?.', ['?' => __('phone number')]);
                    }
                    if (empty($errorFails)) {
                        try {
                            DB::beginTransaction();

                            $userData = new User;
                            $userData->first_name = $value["First Name"];
                            $userData->last_name = $value["Last Name"];
                            $userData->full_name = $userData->first_name . ' ' . $userData->last_name;
                            $userData->added_by = Auth::id();
                            $userData->email = $value["Email"];
                            $userData->password = $value["Password"];
                            $userData->phone = !empty($value["Phone"]) ? $value["Phone"] : null;
                            $userData->role_id = $availableRoles[strtolower($value['Role'])];
                            $userData->is_active = strtolower($value["Status"]) == 'active' ? 1 : 0;
                            $userData->save();

                            array_push($emails, $userData->email);

                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            $errorFails[] = $e->getMessage();
                      }
                    }

                    // set the error messages
                    if (!empty($errorFails)) {
                        $errorMessages[$key] = ['fails' => $errorFails, 'data' => $value];
                    }
                }

                // redirect with success message if no error found.
                if (empty($errorMessages)) {
                    \Session::flash('success', "Total Imported row: ". count($csv));
                    return redirect()->intended('users');
                } else {
                    $data['menu'] = 'relationship';
                    $data['list_menu'] = 'users';
                    $data['page_title'] = __('User Import Issues');
                    $data['sub_menu'] = 'users';
                    $data['totalRow'] = count($csv);

                    return view('layouts.includes.csv_import_errors', $data)->with('errorMessages', $errorMessages);
                }
            }
        } else {
            return back()->withErrors(['fail' => __("Please upload a CSV file.")]);
        }
    }

    public function change_status(Request $request)
    {
        $customer = User::find($request->id);
        $customer->inactive = $request->status;
        $customer->save();
        $data['customerCount'] = Customer::all()->count();
        $data['customerActive'] = Customer::where('is_active', 1)->count();
        $data['customerInActive'] = Customer::where('is_active', 0)->count();
        $data['status'] = 'success';
        return $data;
    }

    /**
     * Validate email address while creating a new User.
     *
     * @return true or false
     */
    public function validEmail(Request $request)
    {
        if (isset($request->userId)) {
          $value = DB::table('users')
                ->where('email', $request->email)
                ->where('id', "!=", $request->userId)
                ->first();
        } else {
          $value = DB::table('users')
                ->where('email', $request->email)
                ->first();
        }

        if (isset($value) && !empty($value)) {
          echo json_encode(__('This email is already existed.'));
          exit;
        } else {
          return "true";
        }
    }


    /**
     * Show and manage user profile CRUD opration
     *
     * @return User profile page view
     */
    public function profile()
    {
      $data = [];
      $id = Auth::user()->id;
      $data['profile'] = 'active';
      $data['menu'] = 'relationship';
      $data['list_menu'] = 'users';
      $data['sub_menu'] = 'users';
      $data['page_title'] = __('User Edit');
      $data['user_id'] = $id;
      $data['roleData'] = Role::getAll();
      $data['userData'] = User::with('avatarFile:object_id,object_type,file_name')->where('id', $id)->first();
      $data['departments'] = Department::getAll();
      $data['userDept'] = DB::table('user_departments')->where('user_id',$id)->pluck('department_id')->toArray();

      return view('admin.user.team_member_profile', $data);
    }

    /**
     * show user change password operation
     *
     * @return change password page view
     */
    public function changePassword(Request $request)
    {
        $data['menu'] = 'NULL';
        $data['header'] = 'profile';
        $data['breadcrumb'] = 'change/password';
        $data['userData'] = DB::table('users')->where('id', '=', Auth::user()->id)->first();

        return view('admin.user.change_password', $data);
    }

    /**
     * Change user password operation perform
     *
     * @return change password page view
     */

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'old_pass' => 'required',
            'new_pass' => 'required | min:6',
            'r_pass' => 'same:new_pass',
        ]);

        $id = Auth::user()->id;

        $v = User::find($id);

        $data['password'] = \Hash::make($request->new_pass);
        $data['updated_at'] = date('Y-m-d H:i:s');

        if (\Hash::check($request->old_pass, $v->password)) {
            DB::table('users')->where('id', $id)->update($data);
            \Session::flash('success', __('Password update successfully'));
            return redirect()->intended("change-password");
        } else {
            return back()->withInput()->withErrors(['email' => __("Old password is wrong.")]);
        }

    }

    public function userPurchaseOrderList($id)
    {
        $data['menu'] = 'relationship';
        $data['list_menu'] = 'users';
        $data['sub_menu'] = 'users';
        $data['po_status'] = 'active';
        $data['user_id'] = $id;

        $fromDate = DB::table('purchase_orders')->select('order_date')->where('user_id',$id)->orderBy('order_date','asc')->first();
        $toDate = DB::table('purchase_orders')->select('order_date')->where('user_id',$id)->orderBy('order_date','desc')->first();

        $data['user'] = User::find($id);
        $data['supplierList'] = Supplier::get(['id','name']);
        $data['locationList'] = Location::getAll()->pluck('name','id')->toArray();

        $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = isset($_GET['to']) ? $_GET['to'] : null;

        $data['supplier'] = isset($_GET['supplier']) ? $_GET['supplier'] : null;
        $data['location'] = isset($_GET['location']) ? $_GET['location'] : null;
        $data['status']   = $status      = isset($_GET['status']) ? $_GET['status'] : null;

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $this->userOrderListDataTable->with('row_per_page', $row_per_page)->with('user_id',$id)->render('admin.user.po-orders', $data);
    }

    public function userPurchaseOrderListPdf()
    {
      $to       = isset($_GET['to']) ? $_GET['to'] : null ;
      $from     = isset($_GET['from']) ? $_GET['from'] : null ;
      $data['supplier'] = $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null;
      $data['suppliers'] = DB::table('suppliers')->where('id', $supplier)->first();
      $data['location'] = $location = isset($_GET['location']) ? $_GET['location'] : null;
      $data['locations'] = DB::table('locations')->where('id', $location)->first();
      $team_member = isset($_GET['team_member']) ? $_GET['team_member'] : null ;
      $data['teamMembers'] = DB::table('users')->where('id', $team_member)->first();
      $status = isset($_GET['status']) ? $_GET['status'] : null;
      $data['purchData'] = (new PurchaseOrder)->getAllPurchOrderByUserId($from, $to, $supplier, $location, $team_member, $status)->orderBy('purchase_orders.id','DESC')->get();
      $data['date_range'] = ($from && $to) ? formatDate($from) .' To '. formatDate($to) : 'No Date Selected';

      return printPDF($data, 'team_member_purchases_list_' . time() . '.pdf', 'admin.user.team_member_purch_pdf', view('admin.user.team_member_purch_pdf', $data), 'pdf', 'domPdf');
    }

    public function userPurchaseOrderListCsv()
    {
      return Excel::download(new TeamMemberLedgerCsv(), 'team_member_purchases_list'.time().'.csv');
    }

    public function userSalesOrderList($id)
    {
      $data['so_status'] = 'active';
      $data['menu'] = 'relationship';
      $data['list_menu'] = 'users';
      $data['sub_menu'] = 'users';
      $data['user_id'] = $id;
      $fromDate = DB::table('sale_orders')->select('order_date')->where('user_id',$id)->orderBy('order_date','asc')->first();
      $data['user'] = User::find($id);
      $data['customerList'] = DB::table('customers')
                              ->select('id','name')
                              ->get();
      $data['locationList'] = Location::getAll()->pluck('name','id')->toArray();
      $data['from'] = isset($_GET['from']) ? $_GET['from'] : null;
      $data['to'] = isset($_GET['to']) ? $_GET['to'] : null;

      $data['customer'] = isset($_GET['customer']) ? $_GET['customer'] : null;
      $data['location'] = isset($_GET['location']) ? $_GET['location'] : null;

      $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
      return $this->userQuotationListDataTable->with('row_per_page', $row_per_page)->with('user_id',$id)->render('admin.user.so-orders', $data);

    }

    public function userSalesInvoiceList($id){

        $data['invoice'] = 'active';
        $data['menu'] = 'relationship';
        $data['list_menu'] = 'users';
        $data['sub_menu'] = 'users';
        $data['user_id'] = $id;

        $data['user'] = User::find($id);

        $fromDate = DB::table('sale_orders')->select('order_date')->where('user_id',$id)->orderBy('order_date','asc')->first();

        $data['from']         = $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $data['to']           = $to = isset($_GET['to']) ? $_GET['to'] : null ;
        $data['customer']     = $customer = isset($_GET['customer']) ? $_GET['customer'] : null;
        $data['location']     = $location = isset($_GET['location']) ? $_GET['location'] : null;
        $data['status']       = isset($_GET['status']) ? $_GET['status'] : null ;
        $data['customerList'] = Customer::get(['id','name']);
        $data['locationList'] = Location::getAll()->pluck('name','id')->toArray();
        $row_per_page = Preference::getAll()->where('field', 'row_per_page')->first()->value;
        $data['amounts'] = $amounts = $this->saleOrder->getMoneyStatus(['user_id' => $id ,'from' => $from, 'to' => $to, 'location' => $data['location'], 'status' => $data['status']]);

        $allCurrency = [];
        $overdueCurrency = [];
        foreach ($amounts['amounts'] as $amount) {
            if (isset($amount->currency->symbol) && !empty($amount->currency->symbol)) {
                $allCurrency[] =  $amount->currency->symbol;
            }
        }
        foreach ($amounts['overDue'] as $amount) {
            if (isset($amount->currency->symbol) && !empty($amount->currency->symbol)) {
                $overdueCurrency[] =  $amount->currency->symbol;
            }
        }
        $data['allCurrency'] = array_diff($allCurrency, $overdueCurrency);
        return $this->userInvoiceListDataTable->with('row_per_page', $row_per_page)->with('user_id', $id)->render('admin.user.invoices', $data);
    }

    public function userTransferList($id)
    {
        $data['transfer'] = 'active';
        $data['menu'] = 'relationship';
        $data['list_menu'] = 'users';
        $data['sub_menu'] = 'users';
        $data['user_id'] = $id;
        $data['source'] = isset($_GET['source']) ? $_GET['source'] : NULL;
        $data['destination'] = isset($_GET['destination']) ? $_GET['destination'] : NULL;

        $data['user'] = User::find($id);

        $data['from']         = isset($_GET['from']) ? $_GET['from'] : NULL;
        $data['to']           = isset($_GET['to']) ? $_GET['to'] : NULL;
        $data['locationList'] = Location::getAll()->where('is_active', 1);

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $this->userTransferDataTable->with('row_per_page', $row_per_page)->with('user_id',$id)->render('admin.user.transfer', $data);
    }

    public function userPaymentList($id){

        $data['invoicePayment'] = 'active';
        $data['menu'] = 'relationship';
        $data['list_menu'] = 'users';
        $data['sub_menu'] = 'users';
        $data['user_id'] = $id;
        $data['customer'] = $customer = 'all';
        $data['user'] = User::find($id);
        $data['customerList'] = DB::table('customers')
                                ->select('id','name')
                                ->get();
        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null;
        $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null;
        $data['customer'] = $customer = isset($_GET['customer']) ? $_GET['customer'] : 'all';
        $data['method']       = $method   = isset($_GET['method']) ? $_GET['method'] : null;
        $data['currency']     = $method   = isset($_GET['currency']) ? $_GET['currency'] : null;
        $data['methodList']   = PaymentMethod::getAll();
        $data['currencyList'] = Currency::getAll();

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $this->userPaymentListDataTable->with('row_per_page', $row_per_page)->with('user_id',$id)->render('admin.user.payments', $data);

    }

    /**
     * Display a listing of the Users.
     *
     * @return User Purchase List By Team Member
     */
    public function userPurchasePaymentList($id)
    {
      $data['purchPayment'] = 'active';
      $data['menu'] = 'relationship';
      $data['list_menu'] = 'users';
      $data['sub_menu'] = 'users';
      $data['user_id'] = $id;
      $data['user'] = User::find($id);
      $data['suppliers'] = Supplier::where('is_active','!=', 0)->select('id','name')->get();
      $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null ;
      $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null ;
      $data['supplier'] = $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null ;
      $data['method']   = $method   = isset($_GET['method']) ? $_GET['method'] : null;
      $data['currency'] = $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
      $data['methodList']   = PaymentMethod::getAll();
      $data['currencyList'] = Currency::getAll();


      $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
      return $this->userPurchPaymentDataTable->with('row_per_page', $row_per_page)->with('user_id', $id)->render('admin.user.purchase_payments', $data);
    }

    public function userProjectList($id)
    {
        $data['projectAssign'] = 'active';
        $data['menu'] = 'relationship';
        $data['list_menu'] = 'users';
        $data['sub_menu'] = 'users';
        $data['user_id'] = $id;
        $data['user'] = User::find($id);
        $data['suppliers'] = Supplier::where('is_active','!=',0)->select('id','name')->get();
        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null ;
        $data['supplier'] = $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null ;
        $data['status'] = DB::table('project_statuses')->select('id','name')->get();
        $data['allstatus']    = $allstatus = isset($_GET['status']) ? $_GET['status'] : '';
        $data['project_type'] = (isset($_GET['project_type']) && $_GET['project_type'] != '') ? $_GET['project_type'] : ['customer', 'product', 'in_house'];

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        return $this->userProjectDataTable->with('row_per_page', $row_per_page)->with('user_id',$id)->render('admin.user.project_list', $data);
    }
    public function userProjectListPdf()
    {
      $id = request()->userId;
      $project_type = isset(request()->project_type) ? request()->project_type : null;
      $status = isset(request()->status) ? request()->status : null;
      $from = isset(request()->from) ? request()->from : null;
      $to = isset(request()->to) ? request()->to : null;

      $data['date_range'] = (!empty($from) && !empty($to)) ? formatDate($from) .' To '. formatDate($to) : 'No Date Selected';
      $result = [];
      $finalRes = [];
      $projects = (new Project())->getAllProjectByUser($from, $to, $id, $status, $project_type)->orderBy('begin_date', 'desc')->get()->toArray();
      if (!empty($projects)) {
        foreach($projects as $val) {
            $result[$val->project_id][] = $val;
        }
      }

      if (!empty($result)) {
        foreach ($result as $key => $value) {
          $completedTask = 0;
          $totalTask = 0;
          if (!empty($value)) {
            foreach ($value as $k => $v) {
              if ($v->task_status_id == 5) {
                $completedTask += 1;
              }
              if (isset($v->task_id) && !empty($v->task_id)) {
                $totalTask += 1;
              }
              $finalRes[$key]['project_id'] = $v->project_id;
              $finalRes[$key]['name'] = $v->name;
              $finalRes[$key]['project_type'] = $v->project_type;
              $finalRes[$key]['first_name'] = $v->first_name;
              $finalRes[$key]['last_name'] = $v->last_name;
              $finalRes[$key]['begin_date'] = $v->begin_date;
              $finalRes[$key]['due_date'] = $v->due_date;
              $finalRes[$key]['charge_type'] = $v->charge_type;
              $finalRes[$key]['status_name'] = $v->status_name;
              $finalRes[$key]['totalTask'] = $totalTask;
              $finalRes[$key]['completedTask'] = $completedTask;
            }
          }
        }
      }
      arsort($finalRes);
      $data['project'] = $finalRes;
      return printPDF($data, 'team_member_project_list_' . time() . '.pdf', 'admin.user.team_member_projectList', view('admin.user.team_member_projectList', $data), 'pdf', 'domPdf');
    }

    public function userProjectListCsv()
    {
      return Excel::download(new teamMemberProjectExport(), 'team_member_project_list'.time().'.csv');
    }

    public function userTaskList($id)
    {
        $data['taskAssign']     = 'active';
        $data['menu']           = 'relationship';
        $data['list_menu']      = 'users';
        $data['sub_menu']       = 'users';
        $tags                   = DB::table('tags')->pluck('name');
        $data['tags']           = json_encode($tags);

        //user_id
        $data['user_id']        = $id;
        $data['user'] = User::find($id);
        $data['task_status_all'] = TaskStatus::getAll();
        $taskList = TaskStatus::getAll();
        $data['priorities'] = DB::table('priorities')->select('id', 'name')->get();
        $data['allpriority'] = isset($_GET['priority'])? $_GET['priority'] : null;
        $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null ;
        $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null ;
        $data['allstatus'] = $allstatus = isset($_GET['status']) ? $_GET['status'] : '' ;

        $task_summary = (new Task())->getUserTaskSummary([ 'user_id' => $id])->get();
        $summary = [];
        $stack   = [];
        for ($i = 0; $i < count($taskList); $i++) {
            for ($j = 0; $j < count($task_summary); $j++) {
                if ($taskList[$i]->id == $task_summary[$j]->id) {
                    $summary[$i]['id']           = $task_summary[$j]->id;
                    $summary[$i]['name']         = $task_summary[$j]->name;
                    $summary[$i]['color']        = $task_summary[$j]->color;
                    $summary[$i]['total_status'] = $task_summary[$j]->total_status;
                    $stack[]                     = $task_summary[$j]->id;
                } else {
                    if (!in_array($taskList[$i]->id, $stack)) {
                        $summary[$i]['id']           = $taskList[$i]->id;
                        $summary[$i]['name']         = $taskList[$i]->name;
                        $summary[$i]['color']        = $taskList[$i]->color;
                        $summary[$i]['total_status'] = 0;
                    }
                }
            }
        }
        if (count($task_summary) === 0) {
            for ($i = 0; $i < count($taskList); $i++) {
                $summary[$i]['id']           = $taskList[$i]->id;
                $summary[$i]['name']         = $taskList[$i]->name;
                $summary[$i]['color']        = $taskList[$i]->color;
                $summary[$i]['total_status'] = 0;
            }
        }
        $data['summary'] = $summary;

        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;

        return $this->userTaskDataTable->with('row_per_page', $row_per_page)->with('user_id', $id)->render('admin.user.user_task_list', $data);
    }

    public function teamMemberProfile($id)
    {
      $data['profile'] = 'active';
      $data['menu'] = 'relationship';
      $data['list_menu'] = 'users';
      $data['sub_menu'] = 'users';
      $data['user_id'] = $id;
      $data['roleData'] = Role::getAll();
      $data['userData'] = User::with('avatarFile:object_id,object_type,file_name')->where('id', $id)->first();
      $data['departments'] = Department::getAll();
      $data['userDept'] = DB::table('user_departments')->where('user_id',$id)->pluck('department_id')->toArray();

      return view('admin.user.team_member_profile', $data);
    }

  public function teamMemberUpdate(Request $request, $id)
    {
      if (! $request->from_installer) {
        if (($id == 1) && (Auth::guard('user')->user()->id != $id)) {
          \Session::flash('fail', __('Your are not authorize to change admin information.'));
          return back();
        }
      }

      $validator = Validator::make($request->all(), [
        'email' => ['required','unique:users,email,'.$request->user_no, new CheckValidEmail],
        'first_name' => 'required|max:30',
        'last_name' => 'required|max:30',
        'attachment'  => [new CheckValidFile(getFileExtensions(2))],
      ]);

      if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
      }

      try {
        DB::beginTransaction();
        $data['first_name'] = $request->first_name;
        $data['last_name'] = $request->last_name;
        $data['full_name'] = $request->first_name . " " . $request->last_name;
        $data['role_id'] = !empty($request->role_id) ? $request->role_id : 1;
        $data['is_active'] = !empty($request->role_id) ? $request->status_id : 1;
        $data['phone'] = $request->phone;
        $data['email'] = validateEmail($request->email) ? trim($request->email) : null;
        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($request->from_installer == true) {
          $data['password'] = \Hash::make($request->password);
        }

        DB::table('users')->where('id', $id)->update($data);
        DB::table('role_users')->where('user_id', $id)->update(['user_id'=>$id, 'role_id'=> !empty($request->role_id) ? $request->role_id : 1]);
        Cache::forget('gb-role_users');

        $departments  = isset($request->departments) ? $request->departments : null;
        $old_departments = DB::table('user_departments')->where('user_id',$id)->pluck('department_id')->toArray();
        $department_req = isset($request->departments) ? $request->departments : [];

        // Delete department_id from userdepartment if no exists on updated department
        if (!empty($old_departments)) {
          foreach ($old_departments as $old_department) {
            if (! in_array($old_department, $department_req)) {
              DB::table('user_departments')->where(array('department_id'=>$old_department,'user_id'=>$id))->delete();
            }
          }
        }

        if (!empty($department_req)) {
          foreach ($departments as $key=> $value) {
            if (!in_array($value, $old_departments)) {
              DB::table('user_departments')->insert(['user_id' => $id, 'department_id' =>$departments[$key]]);
            }
          }
        }

        if (isset($request->attachment) && ! empty($request->attachment)) {
          #delete file region
          $fileIds = array_column(json_decode(json_encode(File::Where(['object_type' => 'USER', 'object_id' => $request->id])->get(['id'])), true), 'id');
          $oldFileName = isset($fileIds) && !empty($fileIds) ? File::find($fileIds[0])->file_name : null;
          if (isset($fileIds) && !empty($fileIds)) {
              (new File)->deleteFiles('USER', $request->id, ['ids' => [$fileIds], 'isExceptId' => false], $path = 'public/uploads/user');
          }
          #end region

          #region store files
          if (isset($request->id) && !empty($request->id) && $request->hasFile('attachment')) {
              $path = createDirectory("public/uploads/user");
              $fileIdList = (new File)->store([$request->attachment], $path, 'USER', $request->id, ['isUploaded' => false, 'isOriginalNameRequired' => true, 'resize' => false]);

              if (isset($fileIdList[0]) && !empty($fileIdList[0])) {
                $uploadedFileName = File::find($fileIdList[0])->file_name;
                $uploadedFilePath = asset($path . '/' . $uploadedFileName);
                $thumbnailPath = createDirectory("public/uploads/user/thumbnail");
                (new File)->resizeImageThumbnail($uploadedFilePath, $uploadedFileName, $thumbnailPath, $oldFileName);

                Cache::forget('gb-user-0-avatar-'. $id);
                Cache::forget('gb-user-1-avatar-'. $id);
              }
          }
          #end region
        }

        DB::commit();

        if ($request->from_installer == true) {
          return $this;
        }

        \Session::flash('success', __('Successfully updated'));
        if (Helpers::has_permission(Auth::user()->id, 'edit_role')) {
          return redirect()->intended("user/team-member-profile/$id");
        } else {
          return redirect()->intended("profile");
        }
    } catch(Exception $e) {
      DB::rollBack();
      return back()->withInput()->withErrors(['email' => __('Invalid Request')]);
    }
  }

  public function updateMemberPassword(Request $request, $id)
    {
      if (($id == 1) && (Auth::guard('user')->user()->id != $id)) {
        \Session::flash('fail', __('Your are not authorize to change admin information.'));
        return back();
      }

      $rules = [
        'new_pass'    => 'required',
        'con_new_pass'=> 'required|same:new_pass'
      ];

      $customMessages = [
        'same'=> "Password does not match.",
      ];
      $this->validate($request, $rules, $customMessages);
      $email = Auth::guard('user')->user()->email;
      $userInfo = User::find($id);

      $data['password'] = $new_pass = \Hash::make(trim($request->new_pass));

      $data['updated_at'] = date('Y-m-d H:i:s');

      if ($data['password']) {
          DB::table('users')->where('id', $id)->update($data);
          if (isset($request->sendMail) && !empty($request->sendMail)) {
            $prefer = Preference::getAll()->pluck('value', 'field')->toArray();
            // Retrive Set Password email template
            $data['emailInfo'] = EmailTemplate::where(['template_id' => 18, 'language_short_name' => $prefer['dflt_lang']])->first(['subject', 'body']);

            $subject =  (!empty($data['emailInfo']['subject'])) ? $data['emailInfo']['subject'] : "Set Password!";
            $message =  $data['emailInfo']['body'];
            // Replacing template variable
            $message = str_replace('{user_name}', $userInfo->first_name . ' ' . $userInfo->last_name, $message);
            $message = str_replace('{user_id}', $userInfo->email , $message);
            $message = str_replace('{user_pass}', $request->new_pass , $message);
            $message = str_replace('{company_name}', $prefer['company_name'], $message);
            $message = str_replace('{company_email}', $prefer['company_email'], $message);
            $message = str_replace('{company_url}', url('/admin'), $message);
            $message = str_replace('{company_phone}', $prefer['company_phone'], $message);
            $message = str_replace('{company_street}', $prefer['company_street'], $message);
            $message = str_replace('{company_city}', $prefer['company_city'], $message);
            $message = str_replace('{company_state}', $prefer['company_state'], $message);

            if (!empty($email)) {
              $emailResponseFirst = $this->email->sendEmail($email, $subject, $message, null, $prefer['company_name']);
            }
            if (isset($userInfo->email) && !empty($userInfo->email)) {
              $emailResponseFirst = $this->email->sendEmail($userInfo->email, $subject, $message, null, $prefer['company_name']);
            }
            if ($emailResponseFirst['status'] == false) {
              return redirect()->back()->withInput()->withErrors(['fail' => $emailResponseFirst['message']]);
            }
          }

          Session::flash('success', __('Password update successfully.'));
          return redirect()->intended("user/team-member-profile/$id");
      } else {
          return back()->withInput()->withErrors(['email' => __("Password does not match.")]);
      }

  }

    public function userQuotationListPdf()
    {
      $data['customer'] = $customer = isset($_GET['customerId']) ? $_GET['customerId'] : null;
      $data['customers'] = DB::table('customers')->where('id', $customer)->first();
      $data['location'] = $location = isset($_GET['location']) ? $_GET['location'] : null;
      $data['locations'] = Location::where('id', $location)->first();
      $team_member = isset($_GET['team_member']) ? $_GET['team_member'] : null;
      $data['teamMembers'] = DB::table('users')->where('id', $team_member)->first();

      $data['locationList'] = Location::getAll()->pluck('name','id')->toArray();

      $from = isset($_GET['from']) ? $_GET['from'] : null ;
      $to = isset($_GET['to']) ? $_GET['to'] : null ;

      $data['salesData'] = (new SaleOrder)->getAllQuotation($from, $to, $location, $customer, null, $team_member);
      $data['salesData'] = $data['salesData']->get();

      $data['date_range'] = ($from && $to) ? formatDate($from) . __('To') . formatDate($to) : __('No Date Selected');
      return printPDF($data, 'team_member_quotation_list_' . time() . '.pdf', 'admin.user.team_member_quotation_pdf', view('admin.user.team_member_quotation_pdf', $data), 'pdf', 'domPdf');
    }

    public function userQuotationListCsv(){

      return Excel::download(new teamMemberQuotationExport(), 'team_member_quotation_list'.time().'.csv');
    }

    public function userInvoiceListPdf()
    {
       $to       = isset($_GET['to']) ? $_GET['to'] : formatDate(date('d-m-Y'));
       $from     = isset($_GET['from']) ? $_GET['from'] : formatDate(date('Y-m-d', strtotime("-30 days")));
       $data['customer'] = $customer = isset($_GET['customerId']) ? $_GET['customerId'] : null ;
       $data['customers'] = DB::table('customers')->where('id', $customer)->first();
       $data['location'] = $location = isset($_GET['location']) ? $_GET['location'] : null ;
       $data['locations'] = DB::table('locations')->where('id', $location)->first();
       $team_member = isset($_GET['team_memberId']) ? $_GET['team_memberId'] : null ;
       $data['teamMembers'] = DB::table('users')->where('id', $team_member)->first();
       $status   = isset($_GET['status']) ? $_GET['status'] : null;
       $data['salesData'] = (new SaleOrder)->getAllInvoices($from, $to, $customer, $location, null , $status, $team_member)->orderBy('id', 'desc')->get();
       $data['date_range'] = !empty($from && $to) ? formatDate($from) . __('To') . formatDate($to) : __('No Date Selected');
       return printPDF($data, 'team_member_invoice_list_' . time() . '.pdf', 'admin.user.team_member_invoice_pdf', view('admin.user.team_member_invoice_pdf', $data), 'pdf', 'domPdf');
    }

   public function userInvoiceListCsv(){

      return Excel::download(new teamMemberInvoiceExport(), 'team_member_invoice_list'.time().'.csv');

    }

    public function userInvoicePaymentListPdf()
    {
      $to       = isset($_GET['to']) ? $_GET['to'] : formatDate(date('d-m-Y'));
      $from     = isset($_GET['from']) ? $_GET['from'] :formatDate(date('Y-m-d', strtotime("-30 days")));
      $data['customer'] = $customer = isset($_GET['customerId']) ? $_GET['customerId'] : 'all';
      $data['customers'] = DB::table('customers')->where('id', $customer)->first();
      $method   = isset($_GET['method']) ? $_GET['method'] : null;
      $currency   = isset($_GET['currency']) ? $_GET['currency'] : null;
      $team_member = isset($_GET['team_memberId']) ? $_GET['team_memberId'] : 'all';
      $data['teamMembers'] = DB::table('users')->where('id', $team_member)->first();

      $salesPayment = CustomerTransaction::where('user_id',$team_member)->orderBy('id','DESC')
                ->select('customer_transactions.*');
                if ($from) {
                  $salesPayment->where('transaction_date', '>=', DbDateFormat($from));
                }
                if ($to) {
                   $salesPayment->where('transaction_date', '<=', DbDateFormat($to));
                }
                if ($customer && $customer !='all') {

                    $salesPayment->where('customer_id', '=', $customer);
                }
                if (!empty($method) && $method != 'all') {
                    $salesPayment->where('payment_method_id', '=', $method);
                }
                if (!empty($currency) && $currency != 'all') {
                  $salesPayment->where('customer_transactions.currency_id', '=', $currency);
                }
      $data['paymentList'] = $salesPayment->get();
      $data['date_range'] = ($from && $to) ? formatDate($from) .' To '. formatDate($to) : 'No Date Selected';
      return printPDF($data, 'team_member_invoice_payment_list_' . time() . '.pdf', 'admin.user.team_mem_inv_pay_pdf', view('admin.user.team_mem_inv_pay_pdf', $data), 'pdf', 'domPdf');
    }

  public function userInvoicePaymentListCsv(){

    return Excel::download(new teamMemberInvoicePaymentExport(), 'team_member_invoice_payment_details'.time().'.csv');

  }

    public function userPurchasePaymentListPdf()
    {
      $to       = isset($_GET['to']) ? $_GET['to'] : null ;
      $from     = isset($_GET['from']) ? $_GET['from'] : null ;
      $data['supplier'] = $supplier = isset($_GET['supplierId']) ? $_GET['supplierId'] : null ;
      $team_member = isset($_GET['team_memberId']) ? $_GET['team_memberId'] : null ;
      $data['teamMembers'] = DB::table('users')->where('id', $team_member)->first();
      $data['supplierData'] = Supplier::where('id', $supplier)->select('id','name')->first();
      $data['method']   = $method   = isset($_GET['method']) ? $_GET['method'] : null;
      $data['currency'] = $currency = isset($_GET['currency']) ? $_GET['currency'] : null;
      $data['methodList']   = PaymentMethod::getAll();
      $data['currencyList'] = Currency::getAll();
      $data['date_range'] = (!empty($from) && !empty($to)) ? formatDate($from) .' To '. formatDate($to) : 'No Date Selected';
      $purchasesPayment = SupplierTransaction::with('supplier','currency','paymentMethod')
                        ->select('supplier_transactions.*')->where('user_id',$team_member);
      if (!empty($from) && !empty($to)) {
        $purchasesPayment->where('transaction_date', '>=', DbDateFormat($from));
        $purchasesPayment->where('transaction_date', '<=', DbDateFormat($to));
      }
      if (!empty($supplier) && $supplier != 'all') {
           $purchasesPayment->where('supplier_id', '=', $supplier);
      }
      if (!empty($currency)) {
           $purchasesPayment->where('currency_id', '=', $currency);
      }
      if (!empty($method)) {
           $purchasesPayment->where('payment_method_id', '=', $method);
      }

      $data['purchPaymentList'] = $purchasesPayment = $purchasesPayment->orderBy('id', 'desc')->get();
      return printPDF($data, 'team_member_purchase_payment_list_' . time() . '.pdf', 'admin.user.team_mem_purch_pay_pdf', view('admin.user.team_mem_purch_pay_pdf', $data), 'pdf', 'domPdf');
    }

    public function userPurchasePaymentListCsv()
    {
      return Excel::download(new teamMemberPurchasePaymentExport(), 'team_member_purchase_payment_list'.time().'.csv');
    }

    public function teamMemberListCsv()
    {
      return Excel::download(new TeamMemberListExport(), 'team_member_list'.time().'.csv');
    }

    public function teamMemberListPdf()
    {
      $url_components = parse_url(url()->previous());
      $url_components = !empty($url_components['query']) ? explode('=', $url_components['query']) : null ;
      $data['company_logo']   = Preference::getAll()
                                          ->where('category','company')
                                          ->where('field', 'company_logo')
                                          ->first('value');
      $data['teamMemberList'] = User::with('role:id,name,display_name')->orderBy('id','desc')->select();
      if (!empty($url_components) && $url_components[1] == "active") {
          $data['teamMemberList']->where('is_active', 1);
      }
      if (!empty($url_components) && $url_components[1] == "inactive") {
          $data['teamMemberList']->where('is_active', 0);
      }
      $data['teamMemberList'] = $data['teamMemberList']->get();
      return printPDF($data, 'team_member_list_' . time() . '.pdf', 'admin.user.user_list_pdf', view('admin.user.user_list_pdf', $data), 'pdf', 'domPdf');
    }

    public function userTaskPdf()
    {
      $data['from'] = $from = isset($_GET['from']) ? $_GET['from'] : null;
      $data['to'] = $to = isset($_GET['to']) ? $_GET['to'] : null;
      $data['status'] = $status = isset($_GET['status']) ? $_GET['status'] : null;
      $data['taskStatus'] = DB::table('task_statuses')->where('id', $status)->select('id','name')->first();
      $data['priority'] = $priority = isset($_GET['priority']) ? $_GET['priority'] : null;
      $data['userId'] = $userId = isset($_GET['userId']) ? $_GET['userId'] : null;
      $id = Auth::user()->id;
      $data['date_range'] =  (!empty($from) && !empty($to)) ? formatDate($from) .' To '. formatDate(date('Y-m-d')) : 'No Date Selected';
      $data['tasks']  = (new Task())->getUserTaskForDT($from, $to, $status, $userId,  $priority)->orderBy('tasks.id','desc')->get();
      foreach ($data['tasks'] as $task) {
        $assigne = (new Task())->taskAssignsList($task->id)->pluck('user_name');
        $list = "";
        if (!empty($assigne)) {
            foreach ($assigne as $counter => $assign) {
                $list .= $assign;
                if ($counter < count($assigne) - 1) {
                    $list .= ", ";
                }
            }
        }
        $task->assignee = $list;
      }
      return printPDF($data, 'team_member_task_list_' . time() . '.pdf', 'admin.user.teamMember_tasks_pdf', view('admin.user.teamMember_tasks_pdf', $data), 'pdf', 'domPdf');
    }

    public function userTaskCSV(){
      return Excel::download(new teamMemberTasksExport(), 'team_member_task_list'.time().'.csv');
    }

}
