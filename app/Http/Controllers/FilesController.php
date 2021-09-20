<?php

namespace App\Http\Controllers;

use App\Http\Start\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\hasFile;
use App\Models\File;
use App\Models\Activity;
use App\Models\Preference;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\DataTables\ProjectFileDataTable;


class FilesController extends Controller
{

    public function __construct(ProjectFileDataTable $projectFileDataTable)
    {
        $this->projectFileDataTable  = $projectFileDataTable; 
    }

    public function projectFiles($id)
    {
        $data = [];
        $data['page_title'] = __('Files of project');
        if (isset($_GET['customer'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'customer';
        } else if (isset($_GET['users'])) {
            $data['menu'] = 'relationship';
            $data['sub_menu'] = 'users';
        } else {
            $data['menu']       = 'project';
        }
        $data['header'] = 'project';
        $data['navbar'] = 'file';
        $data['projectId'] = $id;
        $data['project'] = DB::table('projects')->select('projects.id', 'projects.name', 'ps.name as status_name')
                          ->leftJoin('project_statuses as ps', 'ps.id', '=', 'projects.project_status_id')
                          ->where('projects.id', $id)->first();
        
        if (empty($data['project'])) {
            \Session::flash('fail', __('The data you are trying to access is not found.'));
            return redirect()->back();
        }
        
        $row_per_page = Preference::getAll()->where('category', 'preference')->where('field', 'row_per_page')->first('value')->value;
        
        return $this->projectFileDataTable->with('row_per_page', $row_per_page)->with('project_id', $id)->render('admin.project.file.list', $data);
        
    }

    public function store(Request $request)
    {
        $files = $request->attachment;

        if (!empty($files)) {
            $fileDirectory = createDirectory("public/uploads/project_files");
            $extension = $files->getClientOriginalExtension();
            if ($files->getError() <> 0 || !is_uploaded_file($_FILES['attachment']['tmp_name'])) {
                $this->_returnJSON(false, __('Error in uploading file. Please try again.'));
            }

            $maxFileSize = maxFileSize($_FILES['attachment']["size"]);
            if (isset($maxFileSize['status']) && $maxFileSize['status'] == 0) {
                $this->_returnJSON(false, $maxFileSize['message']);
            }

            $validator = Validator::make($request->all(), []);

            $validator->after(function ($validator) use ($request) {
                $files  = $request->file('attachment');
                if (empty($files)) {
                    return true;
                }

                if (checkFileValidationOne($files->getClientOriginalExtension()) == false) {
                    $validator->errors()->add('upload_File', __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf'));
                }
            });

            if ($validator->fails()) {
                $this->_returnJSON(false, __('Invalid file type'));
            }
            
            $uploadedFiles = (new File)->store([$request->attachment], $fileDirectory, 'PROJECT', $request->uploader_id, ['isUploaded' => false, 'isOriginalNameRequired' => true, 'uploaded_by' => Auth::user()->id]);
            if ($uploadedFiles) {
                // Insert Activity
                (new Activity)->store('Project', $request->project_id, 'user', Auth::user()->id, __('New file added'));
               
                $uploadedFile = File::find($uploadedFiles[0]);
                $attachmentPath = $fileDirectory ."/". $uploadedFile->file_name;

                $this->_returnJSON(true, array('message' => __('Uploaded successfully'), 'attachment' => $uploadedFile->id, 'attachment_name' => $uploadedFile->original_file_name, 'attachment_type' => getFileIcon($uploadedFile->file_name), 'attachment_path' => $attachmentPath));
            }
        }
        
        $this->_returnJSON(false, __('Error in uploading photo. Please try again.'));
    }

    public function delete(Request $request)
    {
        $id    = $request->id;
        $project_id = $request->project_id;
        
        // If file exeist then delete
        if (!empty($id)) {
            $file = File::find($id);
            $deleteAttachments = $this->deleteEventAttachment($request);
            if (!empty($file) && json_decode($deleteAttachments)->status == 1 ) {

                // Insert Activity
                (new Activity)->store('Project', $project_id, 'user', Auth::user()->id, __('File deleted'));

                if (isset($request->deleteFromList) &&  $request->deleteFromList === "List") {
                    \Session::flash('success', __('Deleted Successfully'));
                    return redirect()->back();
                }
            }
            return json_decode($deleteAttachments)->status;
        }
        return false;
    }

    /**
     * upload event attachments
     * @param boolean $save
     * @return json string
    **/
    public function uploadEventAttachments(Request $request, $save = false) 
    {
        if (!empty($_FILES['attachment'])) {
            $uploaderId = $request->uploader_id;
            $tempDirectory = "public/contents/temp";
            if (!file_exists($tempDirectory)) {
                mkdir($tempDirectory, config('app.filePermission'), true);
            }
            $files = $request->attachment;
            if ($_FILES['attachment']['error'] <> 0 || !is_uploaded_file($_FILES['attachment']['tmp_name'])) {
                $this->_returnJSON(false, __('Error in uploading file. Please try again.'));
            }
            
            $maxFileSize = maxFileSize($_FILES['attachment']["size"]);
            if (isset($maxFileSize['status']) && $maxFileSize['status'] == 0) {
                $this->_returnJSON(false, $maxFileSize['message']);
            }

            $validator = Validator::make($request->all(), []);

            $validator->after(function ($validator) use ($request) {
                $files  = $request->file('attachment');
                if (empty($files)) {
                    return true;
                }
                
                if (checkFileValidationOne($files->getClientOriginalExtension()) == false) {
                    $validator->errors()->add('upload_File', __('Allowed File Extensions: jpg, png, gif, docx, xls, xlsx, csv and pdf'));
                }
            });

            if ($validator->fails()) {
                $this->_returnJSON(false, __('Invalid file type'));
            }
            
            $attachment = md5(time()) .'_'. $uploaderId .'_'. $_FILES["attachment"]["name"];
            $attachment = str_replace(array('/',' '), '_', $attachment);
            $attachmentPath = $tempDirectory .'/'. $attachment;
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $attachmentPath)) {
                $this->_returnJSON(true, array('message' => __('Uploaded successfully'), 'attachment' => $attachment, 'attachment_type' => getFileIcon($_FILES["attachment"]["name"]), 'attachment_path' => $attachmentPath, 'attachment_name' => $_FILES["attachment"]["name"]));
            }
        }
        $this->_returnJSON(false, __('Error in uploading file. Please try again.'));
    }

    public function deleteEventAttachment(Request $request)
    {
        $request->filePath = isset($request->filePath) && !empty($request->filePath) ? $request->filePath : $request->attachment;
        if ($request->id != 0) {
            return (new File)->deleteFiles(null, null, ['ids' => [$request->id]], $request->filePath);
        } else {
            return (new File)->unlinkFile($request->filePath);
        }
    }
	
    public function downloadAttachment($id)
    {
        $id = base64_decode($id);
        $paths = array(
                    'Invoice Payment' => 'public/uploads/invoice_payment',
                    'Direct Order' => 'public/uploads/invoice_order', 
                    'Direct Invoice' => 'public/uploads/invoice_order',
                    'EXPENSE' => 'public/uploads/expense',
                    'PROJECT' => 'public/uploads/project_files',
                    'Ticket Reply' => 'public/uploads/tickets',
                    'DEPOSIT' => 'public/uploads/deposit',
                    'Task' => 'public/uploads/tasks',
                    'Purchase Order' => 'public/uploads/purchase_order',
                    'Purchase Payment' => 'public/uploads/purchase_payment',
                    'Item' => 'public/uploads/items',
                    'USER' => 'public/uploads/user',
                ); 

        $file = DB::table('files')->where('id', '=', $id)->first();
        $dir_path = $paths[$file->object_type];
        $doc_path = $dir_path ."/". $file->file_name;
        $path = url('/') . $file->file_name;

        if (file_exists($doc_path)) {

             // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }
            // get the file mime type using the file extension
            switch (strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION))) {
                case 'pdf':
                    $mime = 'application/pdf';
                    break;
                case 'zip':
                    $mime = 'application/zip';
                    break;
                case 'jpeg':
                case 'jpg':
                    $mime = 'image/jpg';
                    break;
                default:
                    $mime = 'application/force-download';
            }
            header('Pragma: public');   // required
            header('Expires: 0');    // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($doc_path)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);
            header('Content-Disposition: attachment; filename="' . basename($path) . '"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($doc_path));  // provide file size*/
            header('Connection: close');
            ob_clean();
            flush();
            readfile($doc_path);    // push it out
            exit();
        } else {
            echo __("This file does not exist."); 
			exit();
        }
    }

    /**
     * Return JSON Encoded output for ajax call
     *
     * @param boolean $result
     * @param mixed $data
     * @param boolean $return if set the json encoded string will be returned
     * @return string
     */
    protected function _returnJSON($result, $data = null, $options = array()) {
        $options = array_merge(array('return' => false, 'render' => false, 'layout' => false), $options);
        if (is_string($data)) {
            $data = array('errorMessage' => $data);
        }
        // render the current action and add to html data key
        if (!empty($options['render'])) {
            if (!empty($options['layout'])) {
                $this->layout = $options['layout'];
            }
            if (empty($options['view'])) {
                $options['view'] = $this->action;
            }
            $data['html'] = $this->render($options['view']);
            if (is_object($data['html'])) {
                if (($options['layout'] == 'paginated-html')) {
                    $data['html'] = json_decode($data['html']->body(), true);
                } else {
                    $data['html'] = $data['html']->body();
                }
            }
        }
        if (!empty($options['sqlLogs'])) {
            $data['_sqlLogs'] = $options['sqlLogs'];
        }
        if ($result !== false) {
            if (!empty($options['return'])) {
                return json_encode(array('result' => true, 'serverTime' => date('Y-m-d H:i:s'), 'data' => $data));
            } else {
                if (!empty($options['jsonResponse'])) {
                    // send josn response without exiting the code
                    $this->autoRender = false;
                    $this->response->body(json_encode(array('result' => true, 'serverTime' => date('Y-m-d H:i:s'), 'data' => $data)));
                    return $this->response;
                } else {
                    // @deprecated, should be removed in the future, this is currently default way
                    echo json_encode(array('result' => true, 'serverTime' => date('Y-m-d H:i:s'), 'data' => $data));
                    exit;
                }
            }
        } else {
            $errorMessage = false;
            if (!empty($data['errorMessage'])) {
                $errorMessage = $data['errorMessage'];
            }
            if (!empty($options['return'])) {
                return json_encode(array('result' => false, 'serverTime' => date('Y-m-d H:i:s'), 'errorMessage' => $errorMessage, 'data' => $data));
            } else {
                if (!empty($options['jsonResponse'])) {
                    // send josn response without exiting the code
                    $this->autoRender = false;
                    $this->response->body(json_encode(array('result' => false, 'serverTime' => date('Y-m-d H:i:s'), 'errorMessage' => $errorMessage, 'data' => $data)));
                    return $this->response;
                } else {
                    // @deprecated, should be removed in the future, this is currently default way
                    echo json_encode(array('result' => false, 'serverTime' => date('Y-m-d H:i:s'), 'errorMessage' => $errorMessage, 'data' => $data));
                    exit;
                }
            }
        }
    }

    public function isValidFileSize()
    {
        $maxFileSize = maxFileSize($_GET['filesize']);
        if (isset($maxFileSize['status']) && $maxFileSize['status'] == 0) {
            return json_encode($maxFileSize['message']);
        }
        return  json_encode(true);
    }
}
