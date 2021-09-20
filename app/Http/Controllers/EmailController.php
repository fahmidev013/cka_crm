<?php
/**
 * Email Controller
 */
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Config;
use DB;
use Mail;
use PHPMailer\PHPMailer\PHPMailer;
use Session;
use App\Models\Preference;
use App\Models\EmailConfiguration;
use App\Models\File;



class EmailController extends Controller

{
    /**
     * The array of success message if email sent.
     *
     * @var array
     */
    protected $successResponse = [];

    /**
     * The array of fail message if email not sent.
     *
     * @var array
     */
    protected $failResponse = [];

    /**
     * The array of message of email, either send email or not.
     *
     * @var array
     */
    protected $response = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->successResponse = [
            'status'  => true,
            'message' => __(':? sent successfully.', ['?' => __('Email')])
        ];

        $this->failResponse = [
            'status'  => false,
            'message' => __(':? can not be sent, please check email configuration or try agian.', ['?' => __('Email')])
        ];

        $this->response = $this->successResponse;
    }

    public function sendEmail($to, $subject, $messageBody, $attachments = null, $companyName = null)
    {
        $mail     = new \App\libraries\MailService();
        $dataMail = [];
        if (!empty($attachments)) {
            $relatedFiles = array();
            if (!empty($attachments)) {
                $files = File::whereIn('id', $attachments)->get(['file_name']);
                if (!empty($files)) {
                    foreach ($files as $file) {
                        if (!empty($file->file_name) && file_exists('public/uploads/tasks/'. $file->file_name)) {
                            $relatedFiles[] = url('public/uploads/tasks/'. $file->file_name);
                        }
                    }
                }

            }
            $dataMail = array(
                'to'      => array($to),
                'subject' => $subject,
                'content' => $messageBody,
                'attach'  => $relatedFiles
            );
        } else {
            $dataMail = array(
                'to'      => array($to),
                'subject' => $subject,
                'content' => $messageBody,
            );
        }

        $emailInfo = EmailConfiguration::getAll()->first();
        if (isset($emailInfo->protocol) && $emailInfo->protocol == 'smtp')
        {
            try {
                $this->setupEmailConfig($companyName);
                $mail->send($dataMail, 'emails.paymentReceipt');
            } catch (\Exception $e) {
                $this->response = $this->failResponse;
            }
        } else {
            $retrun = $this->sendPhpEmail($to, $subject, $messageBody, $emailInfo, '');
            if (empty($return)) {
                $this->response = $this->failResponse;
            }
        }

        return $this->response;
    }

    public function sendTicketEmail($to, $subject, $messageBody, $data, $type = null, $attachments = null)
    {
        $mail     = new \App\libraries\MailService();
        $relatedFiles = array();
        if (!empty($attachments)) {
            $files = File::whereIn('id', $attachments)->get(['file_name']);
            if (!empty($files)) {
                foreach ($files as $file) {
                    if (!empty($file->file_name) && file_exists('public/uploads/tickets/'. $file->file_name)) {
                        $relatedFiles[] = url('public/uploads/tickets/'. $file->file_name);
                    }
                }
            }
        }

        $dataMail = [];
        $dataMail = array(
            'to'      => array($to),
            'subject' => $subject,
            'content' => $messageBody,
            'attach'  => $relatedFiles
        );

        $emailInfo = EmailConfiguration::getAll()->first();
        if (isset($emailInfo->protocol) && $emailInfo->protocol == 'smtp') {
            try {
                $type == "assignee" ? $this->setupTicketEmailConfig($data->full_name) : $this->setupTicketEmailConfig($data->name);
                $mail->send($dataMail, 'emails.paymentReceipt');
            } catch (\Exception $e) {
                $this->response = $this->failResponse;
            }
        } else {
            $this->response = $this->failResponse;
        }

        return $this->response;
    }

    public function sendCustomerTicketEmail($to, $subject, $messageBody, $attachments = null)
    {
        $mail     = new \App\libraries\MailService();
        $relatedFiles = array();
        if (!empty($attachments)) {
            $files = File::whereIn('id', $attachments)->get(['file_name']);
            if (!empty($files)) {
                foreach ($files as $file) {
                    if (!empty($file->file_name) && file_exists('public/uploads/tickets/'. $file->file_name)) {
                        $relatedFiles[] = url('public/uploads/tickets/'. $file->file_name);
                    }
                }
            }

        }

        $dataMail = [];
        $dataMail = array(
            'to'      => array($to),
            'subject' => $subject,
            'content' => $messageBody,
            'attach'  => $relatedFiles
        );

        $emailInfo = EmailConfiguration::getAll()->first();
        if ($emailInfo->protocol == 'smtp') {
            try {
                $this->setupTicketEmailConfig();
                $mail->send($dataMail, 'emails.paymentReceipt');
            } catch (\Exception $e) {
                $this->response = $this->failResponse;
            }
        } else {
            $return = $this->sendPhpEmail($to, $subject, $messageBody, $emailInfo, '');
            if (empty($return)) {
                $this->response = $this->failResponse;
            }
        }

        return $this->response;
    }


    public function sendEmailWithAttachment($to, $subject, $messageBody, $invoiceName, $companyName = null)
    {
        $mail     = new \App\libraries\MailService();
        $dataMail = [];
        $dataMail = array(
            'to'      => array($to),
            'subject' => $subject,
            'content' => $messageBody,
            'attach'  => url("public/uploads/invoices/$invoiceName"),
        );

        $emailInfo = EmailConfiguration::getAll()->first();
        if ($emailInfo->protocol == 'smtp') {
            try {
                $this->setupEmailConfig($companyName);
                $mail->send($dataMail, 'emails.paymentReceipt');
            } catch (\Exception $e) {
                $this->response = $this->failResponse;
            }
        } else {
            $return = $this->sendPhpEmail($to, $subject, $messageBody, $emailInfo, $invoiceName);
            if (empty($return)) {
                $this->response = $this->failResponse;
            }
        }

        @unlink(public_path('/uploads/invoices/' . $invoiceName));

        return $this->response;
    }



    public function setupEmailConfig($companyName = null)
    {
        $result = EmailConfiguration::getAll()->first();
        $value = ['address' => isset($result->from_address) ? $result->from_address : '', 'name' => isset($result->from_name) ? $result->from_name : ''];
        if (!empty($companyName)) {
           $value = ['address' => isset($result->from_address) ? $result->from_address : '', 'name' => $companyName];
        }
        Config::set([
            'mail.driver'     => isset($result->protocol) ? $result->protocol : '',
            'mail.host'       => isset($result->smtp_host) ? $result->smtp_host : '',
            'mail.port'       => isset($result->smtp_port) ? $result->smtp_port : '',
            'mail.from'       => $value,
            'mail.encryption' => isset($result->encryption) ? $result->encryption : '',
            'mail.username'   => isset($result->smtp_username) ? $result->smtp_username : '',
            'mail.password'   => isset($result->smtp_password) ? $result->smtp_password : '',

        ]);

    }


    public function setupTicketEmailConfig($name = null)
    {
        $preference = Preference::getAll()->where('category', 'company')->where('field', 'company_name')->first();
        $result = EmailConfiguration::getAll()->first();

        if (!empty($name)) {
            $value =  ['address' => isset($result->from_address) ? $result->from_address : '', 'name' => $name];
        } else {
            $value = ['address' => isset($result->from_address) ? $result->from_address : '', 'name' => $preference->value];

        }
        Config::set([
            'mail.driver'     => isset($result->protocol) ? $result->protocol : '',
            'mail.host'       => isset($result->smtp_host) ? $result->smtp_host : '',
            'mail.port'       => isset($result->smtp_port) ? $result->smtp_port : '',
            'mail.from'       => $value,
            'mail.encryption' => isset($result->encryption) ? $result->encryption : '',
            'mail.username'   => isset($result->smtp_username) ? $result->smtp_username : '',
            'mail.password'   => isset($result->smtp_password) ? $result->smtp_password : '',
        ]);

    }



    public function sendPhpEmail($to, $subject, $message, $emailInfo, $invoiceName)

    {
        require 'vendor/autoload.php';
        $preference = Preference::getAll()->pluck('value', 'field')->toArray();
        $mail           = new PHPMailer();
        $cus_name       = DB::table('customers')->where('email', $to)->first();
        $mail->From     = $preference['company_email'];
        $mail->FromName = $preference['company_name'];
        $mail->AddAddress($to);
        $mail->WordWrap = 50;
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags("Message");
        if (!empty($invoiceName))
        {
            return $mail->AddAttachment(public_path() . ("/uploads/invoices" . '/' . $invoiceName), "Invoice", 'base64', 'application/pdf');
        }

        return $mail->Send();

    }


}

