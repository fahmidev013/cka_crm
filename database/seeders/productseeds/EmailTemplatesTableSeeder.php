<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class EmailTemplatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('email_templates')->delete();
        
        \DB::table('email_templates')->insert(array (
            0 => 
            array (
                'id' => 1,
                'template_id' => 1,
                'subject' => 'Payment information for Quotation#{order_reference_no} and Invoice#{invoice_reference_no}.',
                'body' => '<html>
                    <head>
                    <body style="background-color:#e0e0e2; font-size:100%;font-weight:400;line-height:1.4;color:#000;">
                        <table style="max-width:700px;margin:50px auto 10px;border: 1px solid black;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px #1028d4;">
                            <thead>
                                <tr>
                                    <td colspan="2" style="text-align: justify;">
                                        <p style="text-align:center"></p>
                                        <p style="font-size: 16px;"><strong>Hi {customer_name},</strong></p>
                                        <p style="font-size: 16px;" style="line-height: 2rem; text-align: justify;">Thanks for purchasing our products and making your payments. We just want to confirm a few details about payment information. Please, check if it is okay or not.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:35px;"></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <p><u><b>Customer Information:</b></u></p>
                                        <p>{billing_street}, {billing_city}, {billing_state}, {billing_zip_code}, {billing_country}</p><br />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><u><b>Payment Summary:</b></u></p>
                                        <p>Payment No : <span>{payment_id}</span></p>
                                        <p>Payment Date : <span>{payment_date}</span></p>
                                        <p>Total Amount : <span>{total_amount}</span></p>
                                        <p>Quotation No  : <span>{order_reference_no}</span></p>
                                        <p>Invoice No  : <span>{invoice_reference_no}</span></p>
                                    </td>
                                </tr>
                                <tr style="margin: 0; padding: 0;">
                                    <td colspan="2">
                                        <p style="font-size:16px;margin-top:50px;"><strong>Regards,</strong></p>
                                        <p style="font-size:16px;">{company_name}</p>
                                        <br />
                                        <hr>
                                        <p style="text-align: center;">©{company_name}, all rights reserved</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'template_id' => 4,
                'subject' => 'Your Invoice # {invoice_reference_no} from {company_name} has been created.',
                'body' => '<html>
                    <body style="background-color:#e2e1e0;color:#000;">
                      <table style="max-width:700px;margin:50px auto 10px;border: 1px solid black;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
                        <thead>
                          <tr>
                            <td colspan="2" style="text-align: justify;">
                              <p style="font-size: 16px"><strong>Hi {customer_name},</strong></p>
                              <p style="font-size: 16px" style="line-height: 2rem; text-align: justify;">Thank you for your order. Here’s a brief overview of your
                                invoice.<br />
                                Please, take a careful look-</p>
                            </td>
                          </tr>
                          <tr>
                            <td style="height:35px;"></td>
                          </tr>
                          <tr>
                            <th style="text-align:left; font-size: 16px">{company_name}</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td style="height:35px;"></td>
                          </tr>
                          <tr>
                            <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
                              <p style="font-size:16px;margin:0 0 10px 0;"><span style="font-weight:bold;display:inline-block;min-width:150px">Order status:</span><b style="color:green;font-weight:normal;margin:0">Success</b></p>
                              <p style="font-size:16px;margin:0 0 10px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Invoice No:</span>
                                {invoice_reference_no}</p>
                              <p style="font-size:16px;margin:0 0 10px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Name:</span> {customer_name}
                              </p>
                              <p style="font-size:16px;margin:0 0 10px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Grand Total:</span>
                                {currency}{total_amount}</p>
                              <p style="font-size:16px;margin:0 0 10px 0;"><span style="font-weight:bold;display:inline-block;min-width:146px">Due Date:</span>
                                {due_date}</p>
                            </td>
                          </tr>
                          <tr style="margin: 0; padding: 0;">
                            <td colspan="2">
                              <p style="margin-top: 20px; font-size: 16px; line-height: 1.5rem;  text-align: justify;">Please go to the admin panel, open
                                your
                                customer entity to view the details and pay the due amount by <strong style="color: red">{due_date}</strong>. If you have any confusion, feel free to contact by just replying
                                this email.</p>

                              <p style="font-size:16px;margin:0 0 10px 0;"><span style="font-weight:bold;font-size:16px;display:inline-block;min-width:146px;"><u>Address:</u> 
                                </span></p>
                                <p style="font-size: 16px">
                                {billing_street}, {billing_city}, {billing_state}, {billing_zip_code},
                                {billing_country}</p>
                              <p style="font-size: 16px">To view the invioces, click on the below button:</p>
                              <p style="font-size:16px;margin-top:50px;"><strong>Regards,</strong></p>
                              <p style="font-size:16px;">{company_name}</p>
                              <br />
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2">
                              <p style="font-size:16px; line-height: 1.5rem; text-align:justify"><strong>Disclaimer:</strong> This
                                confidential email with attached files are intended to use for the selected individual merely. If you got
                                this message wrongly or you are not the addressee of this concern, kindly erase this information from your
                                folder and inform us through a kind reply. Please, do not proclaim or copy this email to any other entity.
                              </p>
                              <br />
                              <hr>
                              <p style="text-align: center;">©{company_name}, all rights reserved</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'template_id' => 5,
                'subject' => 'Your Quotation # {order_reference_no} from {company_name} has been created.',
                'body' => '<html>
                    <head>
                        <link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">
                    </head>

                    <body
                        style="background-color:#e2e1e0;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
                        <table style="max-width:670px;margin:50px auto 10px;border: 1px solid black;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px green;">
                            <thead>
                                <tr>
                                    <td colspan="2">
                                        <p><strong>Hi {customer_name},</strong></p>
                                        <p style="line-height: 2rem; text-align: justify;">Thank you for your order. Here’s a brief overview of your Quotation
                                            #{order_reference_no} that was created on {order_date}.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:35px;" colspan="2"></td>
                                </tr>
                                <tr>
                                    <th style="text-align:left;">{company_name}</th>
                                    <th style="text-align:right;font-weight:400;">{order_date}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="height:35px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="border: solid 1px #ddd; padding:10px 20px;">
                                        <p style="font-size:14px;margin:0 0 10px 0;">
                                            <span style="font-weight:bold;display:inline-block;min-width:150px">Order status:</span>
                                            <b style="color:green;font-weight:normal;margin:0">Success</b>
                                        </p>
                                        <p style="font-size:14px;margin:0 0 10px 0;">
                                            <span style="font-weight:bold;display:inline-block;min-width:146px">Reference No:</span>
                                            {order_reference_no}
                                        </p>
                                        <p style="font-size:14px;margin:0 0 10px 0;">
                                            <span style="font-weight:bold;display:inline-block;min-width:146px">Name:</span>
                                            {customer_name}
                                        </p>
                                        <p style="font-size:14px;margin:0 0 10px 0;">
                                            <span style="font-weight:bold;display:inline-block;min-width:146px">Total amount:</span>
                                            {currency}{total_amount}
                                        </p>
                                        <p style="font-size:14px;margin:0 0 10px 0;">
                                            <span style="font-weight:bold;display:inline-block;min-width:146px">Billing Address:</span>
                                            {billing_street}, {billing_city}, {billing_state},
                                        </p>
                                        <p style="font-size:14px;margin:0 0 10px 0;">
                                            <span style="font-weight:bold;display:inline-block;min-width:146px"></span> {billing_zip_code},
                                            {billing_country}
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p style="margin-top: 20px;">If you have any questions, please feel free to reply to this email.</p>
                                        <p><strong>Regards,</strong></p>
                                        <p>{company_name}</p>
                                        <br />
                                        <hr>
                                        <p style="text-align: center;">©{company_name}, all rights reserved</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'template_id' => 6,
                'subject' => 'A new purchase # {invoice_reference_no} has been created from {company_name}.',
                'body' => '<html>
                    <body style="background-color:#e2e1e0; font-size:100%;font-weight:400;line-height:1.4;color:#000;">
                    <table style="max-width:700px;margin:50px auto 10px;border: 1px solid black;background-color:#fff;padding:50px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24); border-top: solid 10px #1028d4;">
                        <thead>
                        <tr>
                            <td colspan="2" style="text-align: justify;">
                                <p style="text-align: center;"></p>
                                <p style="font-size: 16px"><strong>Hi {supplier_name},</strong></p>
                                <p style="font-size: 16px" style="line-height: 2rem; text-align: justify;">A purchase has been made. A brief overview of the purchase is given below,</p>
                                <p><strong>Purchase no:</strong> <span>#{invoice_reference_no}</span></p>
                                <p><strong>Total amount:</strong> <span>{currency}{total_amount}</span></p>
                                <p><strong>Due date:</strong> <span>{due_date}</span></p>
                                <br />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong><u>Billing address:</u></strong></p>
                                <p>{billing_street}, {billing_city}, {billing_state}, {billing_zip_code}, {billing_country}</p>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr style="margin: 0; padding: 0;">
                            <td>
                            <p style="font-size:16px;margin-top:50px;"><strong>Regards,</strong></p>
                            <p style="font-size:16px;">{company_name}</p>
                            <br />
                            <hr>
                            <p style="text-align: center;">©{company_name}, all rights reserved</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'template_id' => 7,
                'subject' => '{ticket_subject} #Ticket ID: {ticket_no}',
                'body' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
                        <head>
                            <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
                            <meta content="width=device-width" name="viewport"/>
                            <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
                            <title>Ticket</title>
                            <style type="text/css">
                                    body {
                                        margin: 0;
                                        padding: 0;
                                    }
                                    table,
                                    td,
                                    tr {
                                        vertical-align: top;
                                        border-collapse: collapse;
                                    }
                                    * {
                                        line-height: inherit;
                                    }
                                    a[x-apple-data-detectors=true] {
                                        color: inherit !important;
                                        text-decoration: none !important;
                                    }
                                    .myButton {
                                        background-color:#1aa19c;
                                        border-radius:5px;
                                        display:inline-block;
                                        cursor:pointer;
                                        color:#ffffff;
                                        font-family:Trebuchet MS;
                                        font-size:17px;
                                        font-weight:bold;
                                        padding:9px 28px;
                                        text-decoration:none;
                                        text-shadow:0px 1px 2px #3d768a;
                                    }
                                    .myButton:hover {
                                        background-color:#408c99;
                                    }
                                    .myButton:active {
                                        position:relative;
                                        top:1px;
                                    }
                            </style>
                            <style id="media-query" type="text/css">
                                    @media (max-width: 660px) {
                                        .block-grid,
                                        .col {
                                            min-width: 320px !important;
                                            max-width: 100% !important;
                                            display: block !important;
                                        }
                                        .block-grid {
                                            width: 100% !important;
                                        }
                                        .col {
                                            width: 100% !important;
                                        }
                                        .col>div {
                                            margin: 0 auto;
                                        }
                                        img.fullwidth,
                                        img.fullwidthOnMobile {
                                            max-width: 100% !important;
                                        }
                                        .no-stack .col {
                                            min-width: 0 !important;
                                            display: table-cell !important;
                                        }
                                        .no-stack.two-up .col {
                                            width: 50% !important;
                                        }
                                        .no-stack .col.num4 {
                                            width: 33% !important;
                                        }
                                        .no-stack .col.num8 {
                                            width: 66% !important;
                                        }
                                        .no-stack .col.num4 {
                                            width: 33% !important;
                                        }
                                        .no-stack .col.num3 {
                                            width: 25% !important;
                                        }
                                        .no-stack .col.num6 {
                                            width: 50% !important;
                                        }
                                        .no-stack .col.num9 {
                                            width: 75% !important;
                                        }
                                        .video-block {
                                            max-width: none !important;
                                        }
                                        .mobile_hide {
                                            min-height: 0px;
                                            max-height: 0px;
                                            max-width: 0px;
                                            display: none;
                                            overflow: hidden;
                                            font-size: 0px;
                                        }
                                        .desktop_hide {
                                            display: block !important;
                                            max-height: none !important;
                                        }
                                    }

                            </style>
                        </head>
                        <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f8f8f9;">
                            <table bgcolor="#f8f8f9" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f8f8f9; width: 100%;" valign="top" width="100%">
                                <tbody>
                                    <tr style="vertical-align: top;" valign="top">
                                        <td style="word-break: break-word; vertical-align: top;" valign="top">
                                            <div style="background-color:transparent;">
                                                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                        <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                            <div style="width:100% !important;">
                                                                <div style="color:#555555;font-family: Pacifico , cursive;line-height:1.2;padding-top:35px;padding-right:0px;padding-bottom:20px;padding-left:30px;">
                                                                    <div style="line-height: 1.2; font-size: 18px; color: #555555; font-family: Pacifico, cursive; mso-line-height-alt: 14px;">
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 30px;">{company_name}</span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="background-color:transparent;">
                                                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f3fafa;">
                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f3fafa;">
                                                        <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 580px;">
                                                            <div style="width:100% !important;">
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                <div style="border-top:0px solid transparent; border-left:30px solid #FFFFFF; border-bottom:0px solid transparent; border-right:30px solid #FFFFFF; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                    <!--<![endif]-->
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 4px solid #1AA19C; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 35px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:15px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                            <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"><strong>Hello {assignee_name},</strong></span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:0px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                            <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 18px;">A new support ticket has been assigned to you.</span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:0px;padding-right:30px;padding-bottom:20px;padding-left:30px;">
                                                                        <div style="line-height: 1.5; font-size: 16px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                            <p style="font-size: 16px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"> {ticket_message} </span></p>
                                                                        </div>
                                                                    </div>
                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 25px; padding-bottom: 10px; padding-left: 25px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:20px;padding-left:25px;">
                                                                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Ticket ID : <span style="color: #008080;"><strong>{ticket_no}</strong></span></span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Customer ID : <span style="color: #008080;"><strong> {customer_id}</strong></span></span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Project Name : <span style="color: #008080;"><strong>{project_name}</strong></span></span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Subject : <span style="color: #008080;"><strong> {ticket_subject}</strong></span></span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Status : <span style="color: #008080;"><strong> {ticket_status}</strong></span></span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div style="text-align: center; width: 100%;">
                                                                        <a href="{details}" class="myButton" style="color: #ffffff">View Tickets</a>
                                                                    </div>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:20px;padding-right:30px;padding-bottom:40px;padding-left:30px;">
                                                                    <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">Thank you,</span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{assigned_by_whom}</span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{company_name}</span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 60px; padding-right: 0px; padding-bottom: 12px; padding-left: 0px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f8f8f9;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f8f8f9;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'template_id' => 8,
                'subject' => '{ticket_subject} #Ticket ID: {ticket_no}',
                'body' => '                  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
                        <head>
                            <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
                            <meta content="width=device-width" name="viewport"/>
                            <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
                            <title>Ticket</title>
                            <style type="text/css">
                                    body {
                                        margin: 0;
                                        padding: 0;
                                    }
                                    table,
                                    td,
                                    tr {
                                        vertical-align: top;
                                        border-collapse: collapse;
                                    }
                                    * {
                                        line-height: inherit;
                                    }
                                    a[x-apple-data-detectors=true] {
                                        color: inherit !important;
                                        text-decoration: none !important;
                                    }
                                    .myButton {
                                        background-color:#1aa19c;
                                        border-radius:5px;
                                        display:inline-block;
                                        cursor:pointer;
                                        color:#ffffff;
                                        font-family:Trebuchet MS;
                                        font-size:17px;
                                        font-weight:bold;
                                        padding:9px 28px;
                                        text-decoration:none;
                                        text-shadow:0px 1px 2px #3d768a;
                                    }
                                    .myButton:hover {
                                        background-color:#408c99;
                                    }
                                    .myButton:active {
                                        position:relative;
                                        top:1px;
                                    }
                            </style>
                            <style id="media-query" type="text/css">
                                    @media (max-width: 660px) {
                                        .block-grid,
                                        .col {
                                            min-width: 320px !important;
                                            max-width: 100% !important;
                                            display: block !important;
                                        }
                                        .block-grid {
                                            width: 100% !important;
                                        }
                                        .col {
                                            width: 100% !important;
                                        }
                                        .col>div {
                                            margin: 0 auto;
                                        }
                                        img.fullwidth,
                                        img.fullwidthOnMobile {
                                            max-width: 100% !important;
                                        }
                                        .no-stack .col {
                                            min-width: 0 !important;
                                            display: table-cell !important;
                                        }
                                        .no-stack.two-up .col {
                                            width: 50% !important;
                                        }
                                        .no-stack .col.num4 {
                                            width: 33% !important;
                                        }
                                        .no-stack .col.num8 {
                                            width: 66% !important;
                                        }
                                        .no-stack .col.num4 {
                                            width: 33% !important;
                                        }
                                        .no-stack .col.num3 {
                                            width: 25% !important;
                                        }
                                        .no-stack .col.num6 {
                                            width: 50% !important;
                                        }
                                        .no-stack .col.num9 {
                                            width: 75% !important;
                                        }
                                        .video-block {
                                            max-width: none !important;
                                        }
                                        .mobile_hide {
                                            min-height: 0px;
                                            max-height: 0px;
                                            max-width: 0px;
                                            display: none;
                                            overflow: hidden;
                                            font-size: 0px;
                                        }
                                        .desktop_hide {
                                            display: block !important;
                                            max-height: none !important;
                                        }
                                    }

                            </style>
                        </head>
                        <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f8f8f9;">
                            <table bgcolor="#f8f8f9" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f8f8f9; width: 100%;" valign="top" width="100%">
                                <tbody>
                                    <tr style="vertical-align: top;" valign="top">
                                        <td style="word-break: break-word; vertical-align: top;" valign="top">
                                            <div style="background-color:transparent;">
                                                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                        <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                            <div style="width:100% !important;">
                                                                <div style="color:#555555;font-family: Pacifico, cursive;line-height:1.2;padding-top:35px;padding-right:0px;padding-bottom:20px;padding-left:30px;">
                                                                    <div style="line-height: 1.2; font-size: 18px; color: #555555; font-family: Pacifico, cursive; mso-line-height-alt: 14px;">
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 30px;">{company_name}</span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="background-color:transparent;">
                                                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f3fafa;">
                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f3fafa;">
                                                        <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 580px;">
                                                            <div style="width:100% !important;">
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                <div style="border-top:0px solid transparent; border-left:30px solid #FFFFFF; border-bottom:0px solid transparent; border-right:30px solid #FFFFFF; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                    <!--<![endif]-->
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 4px solid #1AA19C; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 35px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:15px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                            <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"><strong>Hello, {customer_name}</strong></span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:0px;padding-right:30px;padding-bottom:40px;padding-left:30px;">
                                                                        <div style="line-height: 1.5; font-size: 16px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                            <p style="font-size: 16px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"> {ticket_message} </span></p>
                                                                        </div>
                                                                    </div>
                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 25px; padding-bottom: 10px; padding-left: 25px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:20px;padding-left:25px;">
                                                                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Ticket ID : <span style="color: #008080;"><strong>{ticket_no}</strong></span></span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Project Name : <span style="color: #008080;"><strong>{project_name}</strong></span></span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Subject : <span style="color: #008080;"><strong> {ticket_subject}</strong></span></span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Status : <span style="color: #008080;"><strong> {ticket_status}</strong></span></span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div style="text-align: center; width: 100%;">
                                                                        <a href="{details}" class="myButton" style="color: #ffffff">View Tickets</a>
                                                                    </div>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:20px;padding-right:30px;padding-bottom:40px;padding-left:30px;">
                                                                    <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">Thank you,</span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{team_member}</span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{company_name}</span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 60px; padding-right: 0px; padding-bottom: 12px; padding-left: 0px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f8f8f9;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f8f8f9;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </body>
                    </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'template_id' => 9,
                'subject' => '{ticket_subject} #Ticket ID: {ticket_no}',
                'body' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
                        <head>
                            <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
                            <meta content="width=device-width" name="viewport"/>
                            <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
                            <title>Ticket</title>
                            <style type="text/css">
                                    body {
                                        margin: 0;
                                        padding: 0;
                                    }
                                    table,
                                    td,
                                    tr {
                                        vertical-align: top;
                                        border-collapse: collapse;
                                    }
                                    * {
                                        line-height: inherit;
                                    }
                                    a[x-apple-data-detectors=true] {
                                        color: inherit !important;
                                        text-decoration: none !important;
                                    }
                                    .myButton {
                                        background-color:#1aa19c;
                                        border-radius:5px;
                                        display:inline-block;
                                        cursor:pointer;
                                        color:#ffffff;
                                        font-family:Trebuchet MS;
                                        font-size:17px;
                                        font-weight:bold;
                                        padding:9px 28px;
                                        text-decoration:none;
                                        text-shadow:0px 1px 2px #3d768a;
                                    }
                                    .myButton:hover {
                                        background-color:#408c99;
                                    }
                                    .myButton:active {
                                        position:relative;
                                        top:1px;
                                    }
                            </style>
                            <style id="media-query" type="text/css">
                                    @media (max-width: 660px) {
                                        .block-grid,
                                        .col {
                                            min-width: 320px !important;
                                            max-width: 100% !important;
                                            display: block !important;
                                        }
                                        .block-grid {
                                            width: 100% !important;
                                        }
                                        .col {
                                            width: 100% !important;
                                        }
                                        .col>div {
                                            margin: 0 auto;
                                        }
                                        img.fullwidth,
                                        img.fullwidthOnMobile {
                                            max-width: 100% !important;
                                        }
                                        .no-stack .col {
                                            min-width: 0 !important;
                                            display: table-cell !important;
                                        }
                                        .no-stack.two-up .col {
                                            width: 50% !important;
                                        }
                                        .no-stack .col.num4 {
                                            width: 33% !important;
                                        }
                                        .no-stack .col.num8 {
                                            width: 66% !important;
                                        }
                                        .no-stack .col.num4 {
                                            width: 33% !important;
                                        }
                                        .no-stack .col.num3 {
                                            width: 25% !important;
                                        }
                                        .no-stack .col.num6 {
                                            width: 50% !important;
                                        }
                                        .no-stack .col.num9 {
                                            width: 75% !important;
                                        }
                                        .video-block {
                                            max-width: none !important;
                                        }
                                        .mobile_hide {
                                            min-height: 0px;
                                            max-height: 0px;
                                            max-width: 0px;
                                            display: none;
                                            overflow: hidden;
                                            font-size: 0px;
                                        }
                                        .desktop_hide {
                                            display: block !important;
                                            max-height: none !important;
                                        }
                                    }

                            </style>
                        </head>
                        <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f8f8f9;">
                            <table bgcolor="#f8f8f9" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f8f8f9; width: 100%;" valign="top" width="100%">
                                <tbody>
                                    <tr style="vertical-align: top;" valign="top">
                                        <td style="word-break: break-word; vertical-align: top;" valign="top">
                                            <div style="background-color:transparent;">
                                                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                        <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                            <div style="width:100% !important;">
                                                                <div style="color:#555555;font-family: Pacifico, cursive;line-height:1.2;padding-top:35px;padding-right:0px;padding-bottom:20px;padding-left:30px;">
                                                                    <div style="line-height: 1.2; font-size: 18px; color: #555555; font-family: Pacifico, cursive; mso-line-height-alt: 14px;">
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 30px;">{company_name}</span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="background-color:transparent;">
                                                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f3fafa;">
                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f3fafa;">
                                                        <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 580px;">
                                                            <div style="width:100% !important;">
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                <div style="border-top:0px solid transparent; border-left:30px solid #FFFFFF; border-bottom:0px solid transparent; border-right:30px solid #FFFFFF; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                    <!--<![endif]-->
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 4px solid #1AA19C; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 35px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:15px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                            <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"><strong>Hello {member_name},</strong></span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:0px;padding-right:30px;padding-bottom:20px;padding-left:30px;">
                                                                        <div style="line-height: 1.5; font-size: 16px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                            <p style="font-size: 16px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"> {ticket_message} </span></p>
                                                                        </div>
                                                                    </div>
                                                                    <!--[if mso]></td></tr></table><![endif]-->
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 25px; padding-bottom: 10px; padding-left: 25px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:20px;padding-left:25px;">
                                                                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Ticket ID : <span style="color: #008080;"><strong>{ticket_no}</strong></span></span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Project Name : <span style="color: #008080;"><strong>{project_name}</strong></span></span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Subject : <span style="color: #008080;"><strong> {ticket_subject}</strong></span></span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Status : <span style="color: #008080;"><strong> {ticket_status}</strong></span></span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div style="text-align: center; width: 100%;">
                                                                        <a href="{details}" class="myButton" style="color: #ffffff">View Tickets</a>
                                                                    </div>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:20px;padding-right:30px;padding-bottom:40px;padding-left:30px;">
                                                                    <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">Thank you,</span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{customer_name}</span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 60px; padding-right: 0px; padding-bottom: 12px; padding-left: 0px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f8f8f9;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f8f8f9;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'template_id' => 10,
                'subject' => 'New Task Assigned #{task_name}.',
                'body' => '<!DOCTYPE html
                PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:v="urn:schemas-microsoft-com:vml">
            
                <head>
                    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
                    <meta content="width=device-width" name="viewport" />
                    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
                    <title>Task</title>
                    <style type="text/css">
                        body {
                            margin: 0;
                            padding: 0;
                        }
                
                        table,
                        td,
                        tr {
                            vertical-align: top;
                            border-collapse: collapse;
                        }
                
                        * {
                            line-height: inherit;
                        }
                
                        a[x-apple-data-detectors=true] {
                            color: inherit !important;
                            text-decoration: none !important;
                        }
                
                        .myButton {
                            background-color: #1aa19c;
                            border-radius: 5px;
                            display: inline-block;
                            cursor: pointer;
                            color: #ffffff;
                            font-family: Trebuchet MS;
                            font-size: 17px;
                            font-weight: bold;
                            padding: 9px 28px;
                            text-decoration: none;
                            text-shadow: 0px 1px 2px #3d768a;
                        }
                
                        .myButton:hover {
                            background-color: #408c99;
                        }
                
                        .myButton:active {
                            position: relative;
                            top: 1px;
                        }
                    </style>
                    <style id="media-query" type="text/css">
                        @media (max-width: 660px) {
                
                            .block-grid,
                            .col {
                                min-width: 320px !important;
                                max-width: 100% !important;
                                display: block !important;
                            }
                
                            .block-grid {
                                width: 100% !important;
                            }
                
                            .col {
                                width: 100% !important;
                            }
                
                            .col>div {
                                margin: 0 auto;
                            }
                
                            img.fullwidth,
                            img.fullwidthOnMobile {
                                max-width: 100% !important;
                            }
                
                            .no-stack .col {
                                min-width: 0 !important;
                                display: table-cell !important;
                            }
                
                            .no-stack.two-up .col {
                                width: 50% !important;
                            }
                
                            .no-stack .col.num4 {
                                width: 33% !important;
                            }
                
                            .no-stack .col.num8 {
                                width: 66% !important;
                            }
                
                            .no-stack .col.num4 {
                                width: 33% !important;
                            }
                
                            .no-stack .col.num3 {
                                width: 25% !important;
                            }
                
                            .no-stack .col.num6 {
                                width: 50% !important;
                            }
                
                            .no-stack .col.num9 {
                                width: 75% !important;
                            }
                
                            .video-block {
                                max-width: none !important;
                            }
                
                            .mobile_hide {
                                min-height: 0px;
                                max-height: 0px;
                                max-width: 0px;
                                display: none;
                                overflow: hidden;
                                font-size: 0px;
                            }
                
                            .desktop_hide {
                                display: block !important;
                                max-height: none !important;
                            }
                        }
                    </style>
                </head>
            
                <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f8f8f9;">
                    <table bgcolor="#f8f8f9" cellpadding="0" cellspacing="0" class="nl-container" role="presentation"
                        style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f8f8f9; width: 100%;"
                        valign="top" width="100%">
                        <tbody>
                            <tr style="vertical-align: top;" valign="top">
                                <td style="word-break: break-word; vertical-align: top;" valign="top">
                                    <div style="background-color:transparent;">
                                        <div class="block-grid"
                                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                <div class="col num12"
                                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                    <div style="width:100% !important;">
                                                        <div style="color:#555555;font-family:" Pacifico",
                                                            cursive;line-height:1.2;padding-top:35px;padding-right:0px;padding-bottom:20px;padding-left:30px;">
                                                            <div style="line-height: 1.2; font-size: 18px; color: #555555; font-family: "
                                                                Pacifico", cursive; mso-line-height-alt: 14px;">
                                                                <p
                                                                    style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;">
                                                                    <span style="color: #008080; font-size: 30px;">{company_name}</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background-color:transparent;">
                                        <div class="block-grid"
                                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f3fafa;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f3fafa;">
                                                <div class="col num12"
                                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 580px;">
                                                    <div style="width:100% !important;">
                                                        <!--[if (!mso)&(!IE)]><!-->
                                                        <div
                                                            style="border-top:0px solid transparent; border-left:30px solid #FFFFFF; border-bottom:0px solid transparent; border-right:30px solid #FFFFFF; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                            <!--<![endif]-->
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider"
                                                                role="presentation"
                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner"
                                                                            style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;"
                                                                            valign="top">
                                                                            <table align="center" border="0" cellpadding="0"
                                                                                cellspacing="0" class="divider_content"
                                                                                role="presentation"
                                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 4px solid #1AA19C; width: 100%;"
                                                                                valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                                            valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider"
                                                                role="presentation"
                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner"
                                                                            style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 35px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;"
                                                                            valign="top">
                                                                            <table align="center" border="0" cellpadding="0"
                                                                                cellspacing="0" class="divider_content"
                                                                                role="presentation"
                                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;"
                                                                                valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                                            valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div
                                                                style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:15px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                <div
                                                                    style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                    <p
                                                                        style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;">
                                                                        <span style="color: #2b303a; font-size: 18px;"><strong>Hello
                                                                                {assignee_name},</strong></span></p>
                                                                </div>
                                                            </div>
                                                            <div
                                                                style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:0px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                <div
                                                                    style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                    <p
                                                                        style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0; padding-right: 5%; text-align: justify ">
                                                                        <span style="color: #008080; font-size: 18px;">A new support
                                                                            task has been assigned to you. Here is a brief overview of
                                                                            the task. Please have a look.</span></p><br />
                                                                </div>
                                                            </div>
                                                            <!--[if mso]></td></tr></table><![endif]-->
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider"
                                                                role="presentation"
                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner"
                                                                            style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 25px; padding-bottom: 10px; padding-left: 25px;"
                                                                            valign="top">
                                                                            <table align="center" border="0" cellpadding="0"
                                                                                cellspacing="0" class="divider_content"
                                                                                role="presentation"
                                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;"
                                                                                valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                                            valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <div
                                                                style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:20px;padding-left:25px;">
                                                                <div
                                                                    style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                    <p
                                                                        style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;">
                                                                        <u><b>Task Details:</b></u></p>
                                                                    <p
                                                                        style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;">
                                                                        <span style="font-size: 15px;">Task Name: <span
                                                                                style="color: #555555;"><strong>
                                                                                    {task_name}</strong></span></span></p>
                                                                    <p
                                                                        style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;">
                                                                        <span style="font-size: 15px;">Task Details: <span
                                                                                style="color: #555555;"><strong>
                                                                                    {task_details}</strong></span></span></p>
                                                                    <p
                                                                        style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;">
                                                                        <span style="font-size: 15px;">Start Date: <span
                                                                                style="color: #555555;"><strong>
                                                                                    {start_date}</strong></span></span></p>
                                                                    <p
                                                                        style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;">
                                                                        <span style="font-size: 15px;">Due Date: <span
                                                                                style="color: #555555;"><strong>
                                                                                    {due_date}</strong></span></span></p>
                                                                    <p
                                                                        style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;">
                                                                        <span style="font-size: 15px;">Priority: <span
                                                                                style="color: #555555;"><strong>
                                                                                    {priority}</strong></span></span></p>
                                                                    <p
                                                                        style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;">
                                                                        <span style="font-size: 15px;">Checklist: <span
                                                                                style="color: #555555;"><strong>
                                                                                    {task_checklist}</strong></span></span></p>
                                                                    <p
                                                                        style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;">
                                                                        <span style="font-size: 15px;">Status: <span
                                                                                style="color: #555555;"><strong>
                                                                                    {ticket_status}</strong></span></span></p>
                                                                </div>
                                                                <div>
                                                                    <p
                                                                        style="font-size: 15px; padding-top: 3%; line-height: 1; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;font-family: inherit;">
                                                                        <span
                                                                            style="color: black; font-size: 18px; line-height: 1rem;">You
                                                                            can go to the task by clicking the following button.</span>
                                                                    </p>
                                                                    <div style="text-align: center; width: 100%; padding-top: 3%;">
                                                                        <a href="{task_link}" class="myButton"
                                                                            style="color: #ffffff">View Task</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:20px;padding-right:30px;padding-bottom:40px;padding-left:30px;">
                                                                <div
                                                                    style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                    <p
                                                                        style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;">
                                                                        <span style="color: #2b303a; font-size: 15px;">Regards,</span>
                                                                    </p>
                                                                    <p
                                                                        style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;">
                                                                        <span
                                                                            style="color: #2b303a; font-size: 15px;">{company_name}</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--<![endif]-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background-color:transparent;">
                                        <div class="block-grid"
                                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                <div class="col num12"
                                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                    <div style="width:100% !important;">
                                                        <!--[if (!mso)&(!IE)]><!-->
                                                        <div
                                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                            <!--<![endif]-->
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider"
                                                                role="presentation"
                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner"
                                                                            style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 60px; padding-right: 0px; padding-bottom: 12px; padding-left: 0px;"
                                                                            valign="top">
                                                                            <table align="center" border="0" cellpadding="0"
                                                                                cellspacing="0" class="divider_content"
                                                                                role="presentation"
                                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;"
                                                                                valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                                            valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                        </div>
                                                        <!--<![endif]-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background-color:transparent;">
                                        <div class="block-grid"
                                            style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f8f8f9;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f8f8f9;">
                                                <div class="col num12"
                                                    style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                    <div style="width:100% !important;">
                                                        <!--[if (!mso)&(!IE)]><!-->
                                                        <div
                                                            style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                            <!--<![endif]-->
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider"
                                                                role="presentation"
                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner"
                                                                            style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;"
                                                                            valign="top">
                                                                            <table align="center" border="0" cellpadding="0"
                                                                                cellspacing="0" class="divider_content"
                                                                                role="presentation"
                                                                                style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;"
                                                                                valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;"
                                                                                            valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
                
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'template_id' => 15,
                'subject' => 'Payment information for Purchase #{purchase_reference_no}',
                'body' => '<p>Hi {supplier_name},</p><p>We just want to confirm a few details about payment information:</p><p><b>Supplier Information</b></p><p>{billing_street}</p><p>{billing_city}</p><p>{billing_state}</p><p>{billing_zip_code}</p><p>{billing_country}<br></p><p><b>Payment Summary<br></b></p><p><b></b><i>Payment No : {payment_id}</i></p><p><i>Payment Date : {payment_date}&nbsp;</i></p><p><i>Payment Method : {payment_method} <br></i></p><p><i><b>Total Amount : {total_amount}</b></i></p><p><i>Purchase Order No : {purchase_reference_no}</i><br><i></i></p><p><br></p><p>Regards,</p><p>{company_name}<br></p><br><br><br><br><br><br>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'template_id' => 5,
                'subject' => 'Subject',
                'body' => '<p>Body ar&nbsp;Quotation</p>',
                'language_short_name' => 'ar',
                'template_type' => 'email',
                'language_id' => 2,
            ),
            10 => 
            array (
                'id' => 11,
                'template_id' => 6,
                'subject' => 'Subject',
                'body' => '<p>Body ar purchase</p>',
                'language_short_name' => 'ar',
                'template_type' => 'email',
                'language_id' => 2,
            ),
            11 => 
            array (
                'id' => 12,
                'template_id' => 5,
                'subject' => '',
                'body' => 'Your Quotation  #{order_reference_no} has been created on {order_date} successfully.{company_name}',
                'language_short_name' => 'en',
                'template_type' => 'sms',
                'language_id' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'template_id' => 4,
                'subject' => '',
                'body' => 'Your Invoice #{invoice_reference_no} has been created on {order_date} successfully. {company_name}',
                'language_short_name' => 'en',
                'template_type' => 'sms',
                'language_id' => 1,
            ),
            13 => 
            array (
                'id' => 14,
                'template_id' => 5,
                'subject' => 'SMS Title',
                'body' => 'Text Message ar quotation',
                'language_short_name' => 'ar',
                'template_type' => 'sms',
                'language_id' => 2,
            ),
            14 => 
            array (
                'id' => 15,
                'template_id' => 4,
                'subject' => 'SMS Title',
                'body' => 'Text Message',
                'language_short_name' => 'ar',
                'template_type' => 'sms',
                'language_id' => 2,
            ),
            15 => 
            array (
                'id' => 16,
                'template_id' => 10,
                'subject' => 'Subject',
                'body' => 'Body',
                'language_short_name' => 'ar',
                'template_type' => 'email',
                'language_id' => 2,
            ),
            16 => 
            array (
                'id' => 17,
                'template_id' => 13,
                'subject' => 'You have been assigned in a project',
                'body' => '<p></p>
                <p>Hello {assignee},</p><p>
                A new project has been assigned to you.
                <br></p>
                <br>
                Project Name :{project_name}<br>Customer Name:{customer_name}<br><div>Start Date: {start_date}</div><div>Status: {status}</div><div><br></div><div><b>Project Details</b></div><div>{details}<br></div>
                <p>Regards,</p><p>{company_name}</p>
                <br><br>
                <br>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            17 => 
            array (
                'id' => 18,
                'template_id' => 13,
                'subject' => 'Subject',
                'body' => 'Body',
                'language_short_name' => 'ar',
                'template_type' => 'email',
                'language_id' => 2,
            ),
            18 => 
            array (
                'id' => 19,
                'template_id' => 12,
                'subject' => 'A new project has been created.',
                'body' => '<p></p>

                <p>Hello {customer_name},</p><p>



                A new project has been created.



                <br></p>

                <br>

                Project Name :{project_name}<br><div>Start Date: {start_date}</div><div>Status: {status}</div><div><br></div><div><b>Project Details</b></div><div>{details}<br></div>

                <p>Regards,</p><p>{company_name}</p>



                <br><br>

                <br>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            19 => 
            array (
                'id' => 20,
                'template_id' => 18,
                'subject' => 'New Password Set',
                'body' => '                  <!DOCTYPE html>
                <html>
                  <head>
                    <meta charset="utf-8">
                    <meta http-equiv="x-ua-compatible" content="ie=edge">
                    <title>Password Reset</title>
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                  <style type="text/css">
                  /**
                   * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
                   */
                  @media screen {
                    @font-face {
                      font-family: Source Sans Pro;
                      font-style: normal;
                      font-weight: 400;
                      src: local("Source Sans Pro Regular"), local("SourceSansPro-Regular"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format("woff");
                    }
                    @font-face {
                      font-family: Source Sans Pro;
                      font-style: normal;
                      font-weight: 700;
                      src: local("Source Sans Pro Bold"), local("SourceSansPro-Bold"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format("woff");
                    }
                  }
                  /**
                   * Avoid browser level font resizing.
                   * 1. Windows Mobile
                   * 2. iOS / OSX
                   */
                  body,
                  table,
                  td,
                  a {
                    -ms-text-size-adjust: 100%; /* 1 */
                    -webkit-text-size-adjust: 100%; /* 2 */
                  }
                  /**
                   * Remove extra space added to tables and cells in Outlook.
                   */
                  table,
                  td {
                    mso-table-rspace: 0pt;
                    mso-table-lspace: 0pt;
                  }
                  /**
                   * Better fluid images in Internet Explorer.
                   */
                  img {
                    -ms-interpolation-mode: bicubic;
                  }
                  /**
                   * Remove blue links for iOS devices.
                   */
                  a[x-apple-data-detectors] {
                    font-family: inherit !important;
                    font-size: inherit !important;
                    font-weight: inherit !important;
                    line-height: inherit !important;
                    color: inherit !important;
                    text-decoration: none !important;
                  }
                  /**
                   * Fix centering issues in Android 4.4.
                   */
                  div[style*="margin: 16px 0;"] {
                    margin: 0 !important;
                  }
                  body {
                    width: 100% !important;
                    height: 100% !important;
                    padding: 0 !important;
                    margin: 0 !important;
                  }
                  /**
                   * Collapse table borders to avoid space between cells.
                   */
                  table {
                    border-collapse: collapse !important;
                  }
                  a {
                    color: #1a82e2;
                  }
                  img {
                    height: auto;
                    line-height: 100%;
                    text-decoration: none;
                    border: 0;
                    outline: none;
                  }
                  </style>

                </head>
                    <body style="background-color: #e9ecef;">
                      <div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
                      </div>
                      <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                          <td align="center" bgcolor="#e9ecef">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                              <tr>
                                <td align="center" valign="top" style="padding: 36px 24px;">
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td align="center" bgcolor="#e9ecef">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                              <tr>
                                <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
                                  <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px; text-align: center; color: cornflowerblue;">Updated Your Password</h1>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td align="center" bgcolor="#e9ecef">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                              <tr>
                                <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                                  <p style="margin: 0;">Hello {user_name},</p>
                                  <p>You requested to update your password. Your new password has been set. You can check the update going through the <a href="{company_url}">portal</a>.</p>
                                  <h5 style="margin-top:10px; margin-bottom:0px; "> <u>Credentials</u>: </h5>
                                  <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                    <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">User ID: <span style="color: #555555;"><strong> {user_id}</strong></span></span></p>
                                    <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Password: <span style="color: #555555;"><strong> {user_pass}</strong></span></span></p>
                                  </div>
                                  <p style="margin-top:10px;">Was it you or someone else? If it was not you, please inform us promptly.</p>
                                </td>
                              </tr>
                              <tr>
                                <td align="left" bgcolor="#ffffff" style="padding: 0 24px 24px 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf">
                                  <p style="margin: 0;">Thanks & Regards,<br> {company_name}</p>
                                  <p>
                                    From : {company_name}<br />
                                    Email: {company_email}<br />
                                    Phone: {company_phone}<br />
                                    Address: {company_street}, {company_city}, {company_state}
                                  </p>
                                  <br/>
                                  <hr>
                                  <p style="text-align: center;">©{company_name}, all rights reserved</p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            20 => 
            array (
                'id' => 21,
                'template_id' => 17,
                'subject' => 'Reset Password',
                'body' => '<!DOCTYPE html>
                    <html>
                      <head>
                        <meta charset="utf-8">
                        <meta http-equiv="x-ua-compatible" content="ie=edge">
                        <title>Password Reset</title>
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                      <style type="text/css">
                      /**
                       * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
                       */
                      @media screen {
                        @font-face {
                          font-family: Source Sans Pro;
                          font-style: normal;
                          font-weight: 400;
                          src: local("Source Sans Pro Regular"), local("SourceSansPro-Regular"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format("woff");
                        }
                        @font-face {
                          font-family: Source Sans Pro;
                          font-style: normal;
                          font-weight: 700;
                          src: local("Source Sans Pro Bold"), local("SourceSansPro-Bold"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format("woff");
                        }
                      }
                      /**
                       * Avoid browser level font resizing.
                       * 1. Windows Mobile
                       * 2. iOS / OSX
                       */
                      body,
                      table,
                      td,
                      a {
                        -ms-text-size-adjust: 100%; /* 1 */
                        -webkit-text-size-adjust: 100%; /* 2 */
                      }
                      /**
                       * Remove extra space added to tables and cells in Outlook.
                       */
                      table,
                      td {
                        mso-table-rspace: 0pt;
                        mso-table-lspace: 0pt;
                      }
                      /**
                       * Better fluid images in Internet Explorer.
                       */
                      img {
                        -ms-interpolation-mode: bicubic;
                      }
                      /**
                       * Remove blue links for iOS devices.
                       */
                      a[x-apple-data-detectors] {
                        font-family: inherit !important;
                        font-size: inherit !important;
                        font-weight: inherit !important;
                        line-height: inherit !important;
                        color: inherit !important;
                        text-decoration: none !important;
                      }
                      /**
                       * Fix centering issues in Android 4.4.
                       */
                      div[style*="margin: 16px 0;"] {
                        margin: 0 !important;
                      }
                      body {
                        width: 100% !important;
                        height: 100% !important;
                        padding: 0 !important;
                        margin: 0 !important;
                      }
                      /**
                       * Collapse table borders to avoid space between cells.
                       */
                      table {
                        border-collapse: collapse !important;
                      }
                      a {
                        color: #1a82e2;
                      }
                      img {
                        height: auto;
                        line-height: 100%;
                        text-decoration: none;
                        border: 0;
                        outline: none;
                      }
                      </style>

                    </head>
                    <body style="background-color: #e9ecef;">
                      <div class="preheader" style="display: none; color:black; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
                        A preheader is the short summary text that follows the subject line when an email is viewed in the inbox.
                      </div>
                      <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                          <td align="center" bgcolor="#e9ecef">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                              <tr>
                                <td align="center" valign="top" style="padding: 36px 24px;">
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td align="center" bgcolor="#e9ecef">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                              <tr>
                                <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
                                  <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px; text-align: center; color: cornflowerblue;">Reset Your Password</h1>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td align="center" bgcolor="#e9ecef">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                              <tr>
                                <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                                  <p style="margin: 0; color:black;">Dear {user_name},</p>
                                  <p style=" color:black;">Someone has asked to reset the password of your KYC account. If you did not request a password reset, you can disregard this email. No changes have been made to your account.</p>
                                  <p style=" color:black;">To reset your password, go to the following the button: </p>
                                </td>
                              </tr>
                              <tr>
                                <td align="left" bgcolor="#ffffff">
                                  <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                      <td align="center" bgcolor="#ffffff" style="padding: 12px;">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td align="center" bgcolor="#1a82e2" style="border-radius: 6px;">
                                              <a href="{password_reset_url}" target="_blank" style="display: inline-block; padding: 16px 36px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;">Click here</a>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                                  <p style="margin: 0; color:black;">If that does not work, click on the following link in your browser:</p>
                                  <p style="margin: 0;"><a href="{password_reset_url}" target="_blank">{password_reset_url}</a></p>
                                </td>
                              </tr>
                              <tr>
                                <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf">
                                  <p style="margin: 0;  color:black;">Thanks & Regards,<br> {company_name}</p>
                                  <p style=" color:black;">
                                    From : {company_name}<br />
                                    Email: {company_email}<br />
                                    Phone: {company_phone}<br />
                                    Address: {company_street}, {company_city}, {company_state}
                                  </p>
                                  <br />
                                  <hr>
                                  <p style="text-align: center;font-size:12px">©{company_name}, all rights reserved</p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            21 => 
            array (
                'id' => 22,
                'template_id' => 19,
                'subject' => 'Your Invoice # {invoice_reference_no} from {company_name} has been created.',
                'body' => '<p>Hi {customer_name},</p><p>Thank you for your order. Here’s a brief overview of your invoice: Invoice #{invoice_reference_no}. The invoice total is {currency}{total_amount}, please pay before {due_date}.</p><p>If you have any questions, please feel free to reply to this email.</p><p><strong>Billing address</strong></p><p>&nbsp;{billing_street}</p><p>&nbsp;{billing_city}</p><p>&nbsp;{billing_state}</p><p>&nbsp;{billing_zip_code}</p><p>&nbsp;{billing_country}<br>&nbsp;</p><p><br>&nbsp;</p><p><strong>Quotation summary</strong><br>&nbsp;</p><p>{invoice_summery}<br>&nbsp;</p><p>Regards,</p><p>{company_name}<br>&nbsp;</p>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            22 => 
            array (
                'id' => 23,
                'template_id' => 19,
                'subject' => 'Subject',
                'body' => '<p>Body</p>',
                'language_short_name' => 'ar',
                'template_type' => 'email',
                'language_id' => 2,
            ),
            23 => 
            array (
                'id' => 24,
                'template_id' => 19,
                'subject' => '',
                'body' => 'Your POS Invoice #{invoice_reference_no} has been created on {order_date} successfully.
                {company_name}',
                'language_short_name' => 'en',
                'template_type' => 'sms',
                'language_id' => 1,
            ),
            24 => 
            array (
                'id' => 25,
                'template_id' => 19,
                'subject' => 'Subject',
                'body' => '<p>Body</p>',
                'language_short_name' => 'ar',
                'template_type' => 'sms',
                'language_id' => 2,
            ),
            25 => 
            array (
                'id' => 26,
                'template_id' => 20,
                'subject' => 'Task status for task no: #{task_id} has been updated by {changed_by}',
                'body' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
                    <head>
                        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
                        <meta content="width=device-width" name="viewport"/>
                        <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
                        <title>Task</title>
                        <style type="text/css">
                                body {
                                    margin: 0;
                                    padding: 0;
                                }
                                table,
                                td,
                                tr {
                                    vertical-align: top;
                                    border-collapse: collapse;
                                }
                                * {
                                    line-height: inherit;
                                }
                                a[x-apple-data-detectors=true] {
                                    color: inherit !important;
                                    text-decoration: none !important;
                                }
                                .myButton {
                                    background-color:#1aa19c;
                                    border-radius:5px;
                                    display:inline-block;
                                    cursor:pointer;
                                    color:#ffffff;
                                    font-family:Trebuchet MS;
                                    font-size:17px;
                                    font-weight:bold;
                                    padding:9px 28px;
                                    text-decoration:none;
                                    text-shadow:0px 1px 2px #3d768a;
                                }
                                .myButton:hover {
                                    background-color:#408c99;
                                }
                                .myButton:active {
                                    position:relative;
                                    top:1px;
                                }
                        </style>
                        <style id="media-query" type="text/css">
                                @media (max-width: 660px) {
                                    .block-grid,
                                    .col {
                                        min-width: 320px !important;
                                        max-width: 100% !important;
                                        display: block !important;
                                    }
                                    .block-grid {
                                        width: 100% !important;
                                    }
                                    .col {
                                        width: 100% !important;
                                    }
                                    .col>div {
                                        margin: 0 auto;
                                    }
                                    img.fullwidth,
                                    img.fullwidthOnMobile {
                                        max-width: 100% !important;
                                    }
                                    .no-stack .col {
                                        min-width: 0 !important;
                                        display: table-cell !important;
                                    }
                                    .no-stack.two-up .col {
                                        width: 50% !important;
                                    }
                                    .no-stack .col.num4 {
                                        width: 33% !important;
                                    }
                                    .no-stack .col.num8 {
                                        width: 66% !important;
                                    }
                                    .no-stack .col.num4 {
                                        width: 33% !important;
                                    }
                                    .no-stack .col.num3 {
                                        width: 25% !important;
                                    }
                                    .no-stack .col.num6 {
                                        width: 50% !important;
                                    }
                                    .no-stack .col.num9 {
                                        width: 75% !important;
                                    }
                                    .video-block {
                                        max-width: none !important;
                                    }
                                    .mobile_hide {
                                        min-height: 0px;
                                        max-height: 0px;
                                        max-width: 0px;
                                        display: none;
                                        overflow: hidden;
                                        font-size: 0px;
                                    }
                                    .desktop_hide {
                                        display: block !important;
                                        max-height: none !important;
                                    }
                                }
                
                        </style>
                    </head>
                    <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f8f8f9;">
                        <table bgcolor="#f8f8f9" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f8f8f9; width: 100%;" valign="top" width="100%">
                            <tbody>
                                <tr style="vertical-align: top;" valign="top">
                                    <td style="word-break: break-word; vertical-align: top;" valign="top">
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <div style="color:#555555;font-family:Pacifico, cursive;line-height:1.2;padding-top:35px;padding-right:0px;padding-bottom:20px;padding-left:30px;">
                                                                <div style="line-height: 1.2; font-size: 18px; color: #555555; font-family: Pacifico, cursive; mso-line-height-alt: 14px;">
                                                                    <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 30px;">{company_name}</span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f3fafa;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f3fafa;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 580px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:30px solid #FFFFFF; border-bottom:0px solid transparent; border-right:30px solid #FFFFFF; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 4px solid #1AA19C; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 35px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:15px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                    <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"><strong>Hello {assignee_name},</strong></span></p>
                                                                    </div>
                                                                </div>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:0px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                    <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: black; font-size: 18px; line-height: 2rem;">Task status for task #{task_id} has been updated from {task_prev_status} to {task_new_status}. Have a look at the task details.</span></p>
                                                                    </div>
                                                                </div>
                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 25px; padding-bottom: 10px; padding-left: 25px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:20px;padding-left:25px;">
                                                                    <p style="font-size: 16px; color: black;"><b><u>Task Details:</u></b></p>
                                                                    <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Task No: <span style="color: #555555;"><strong> #{task_id}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Task Name: <span style="color: #555555;"><strong> {task_name}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Changed by: <span style="color: #555555;"><strong> {changed_by}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Task Assignee: <span style="color: #555555;"><strong> {all_assignee_name}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Start Date: <span style="color: #555555;"><strong> {start_date}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Due Date: <span style="color: #555555;"><strong> {due_date}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Priority: <span style="color: #555555;"><strong> {priority}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Status: <span style="color: #555555;"><strong> {task_new_status}</strong></span></span></p>
                                                                        <p style="font-size: 15px; padding-top: 3%; line-height: 1; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;font-family: inherit;"><span style="color: black; font-size: 18px; line-height: 1rem;">You can go to the task by clicking the following button.</span></p>
                                                                        <div style="text-align: center; width: 100%; padding-top: 3%;">
                                                                            <a href="{task_link}" class="myButton" style="color: #ffffff">View Task</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:20px;padding-right:30px;padding-bottom:40px;padding-left:30px;">
                                                                <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                    <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">Regards,</span></p>
                                                                    <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{company_name}</span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--<![endif]-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background-color:transparent;">
                                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                    <div style="width:100% !important;">
                                                        <!--[if (!mso)&(!IE)]><!-->
                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                            <!--<![endif]-->
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 60px; padding-right: 0px; padding-bottom: 12px; padding-left: 0px;" valign="top">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                        </div>
                                                        <!--<![endif]-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background-color:transparent;">
                                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f8f8f9;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f8f8f9;">
                                                <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                    <div style="width:100% !important;">
                                                        <!--[if (!mso)&(!IE)]><!-->
                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                            <!--<![endif]-->
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;" valign="top">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            26 => 
            array (
                'id' => 27,
                'template_id' => 21,
                'subject' => '{commented_by} has been commented on "{task_id}-{task_name}"',
                'body' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
                    <head>
                        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
                        <meta content="width=device-width" name="viewport"/>
                        <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
                        <title>Task</title>
                        <style type="text/css">
                                body {
                                    margin: 0;
                                    padding: 0;
                                }
                                table,
                                td,
                                tr {
                                    vertical-align: top;
                                    border-collapse: collapse;
                                }
                                * {
                                    line-height: inherit;
                                }
                                a[x-apple-data-detectors=true] {
                                    color: inherit !important;
                                    text-decoration: none !important;
                                }
                                .myButton {
                                    background-color:#1aa19c;
                                    border-radius:5px;
                                    display:inline-block;
                                    cursor:pointer;
                                    color:#ffffff;
                                    font-family:Trebuchet MS;
                                    font-size:17px;
                                    font-weight:bold;
                                    padding:9px 28px;
                                    text-decoration:none;
                                    text-shadow:0px 1px 2px #3d768a;
                                }
                                .myButton:hover {
                                    background-color:#408c99;
                                }
                                .myButton:active {
                                    position:relative;
                                    top:1px;
                                }
                        </style>
                        <style id="media-query" type="text/css">
                                @media (max-width: 660px) {
                                    .block-grid,
                                    .col {
                                        min-width: 320px !important;
                                        max-width: 100% !important;
                                        display: block !important;
                                    }
                                    .block-grid {
                                        width: 100% !important;
                                    }
                                    .col {
                                        width: 100% !important;
                                    }
                                    .col>div {
                                        margin: 0 auto;
                                    }
                                    img.fullwidth,
                                    img.fullwidthOnMobile {
                                        max-width: 100% !important;
                                    }
                                    .no-stack .col {
                                        min-width: 0 !important;
                                        display: table-cell !important;
                                    }
                                    .no-stack.two-up .col {
                                        width: 50% !important;
                                    }
                                    .no-stack .col.num4 {
                                        width: 33% !important;
                                    }
                                    .no-stack .col.num8 {
                                        width: 66% !important;
                                    }
                                    .no-stack .col.num4 {
                                        width: 33% !important;
                                    }
                                    .no-stack .col.num3 {
                                        width: 25% !important;
                                    }
                                    .no-stack .col.num6 {
                                        width: 50% !important;
                                    }
                                    .no-stack .col.num9 {
                                        width: 75% !important;
                                    }
                                    .video-block {
                                        max-width: none !important;
                                    }
                                    .mobile_hide {
                                        min-height: 0px;
                                        max-height: 0px;
                                        max-width: 0px;
                                        display: none;
                                        overflow: hidden;
                                        font-size: 0px;
                                    }
                                    .desktop_hide {
                                        display: block !important;
                                        max-height: none !important;
                                    }
                                }
                
                        </style>
                    </head>
                    <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f8f8f9;">
                        <table bgcolor="#f8f8f9" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f8f8f9; width: 100%;" valign="top" width="100%">
                            <tbody>
                                <tr style="vertical-align: top;" valign="top">
                                    <td style="word-break: break-word; vertical-align: top;" valign="top">
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <div style="color:#555555;font-family:Pacifico, cursive;line-height:1.2;padding-top:35px;padding-right:0px;padding-bottom:20px;padding-left:30px;">
                                                                <div style="line-height: 1.2; font-size: 18px; color: #555555; font-family: Pacifico, cursive; mso-line-height-alt: 14px;">
                                                                    <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 30px;">{company_name}</span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f3fafa;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f3fafa;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 580px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:30px solid #FFFFFF; border-bottom:0px solid transparent; border-right:30px solid #FFFFFF; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 4px solid #1AA19C; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 35px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:15px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                    <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"><strong>Hello {assignee_name},</strong></span></p>
                                                                    </div>
                                                                </div>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:0px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                    <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: black; font-size: 18px; line-height: 2rem;">There is a new comment in task no: #{task_id}. Have a look at the details of the comment.</span></p>
                                                                    </div>
                                                                </div>
                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 25px; padding-bottom: 10px; padding-left: 25px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 1px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:20px;padding-left:25px;">
                                                                    <p style="font-size:16px; color: black;"><b><u>Comment Details:</u></b></p>
                                                                    <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Task No: <span style="color: #555555;"><strong> #{task_id}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Task Name: <span style="color: #555555;"><strong> {task_name}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Commented by: <span style="color: #555555;"><strong> {commented_by}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Start Date: <span style="color: #555555;"><strong> {start_date}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Due Date: <span style="color: #555555;"><strong> {due_date}</strong></span></span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Comment: <span style="color: #555555;"><strong> {comment}</strong></span></span></p>
                                                                        <p style="font-size: 15px; padding-top: 3%; line-height: 1; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;font-family: inherit;"><span style="color: black; font-size: 18px; line-height: 1rem;">You can go to the task by clicking the following button.</span></p>
                                                                        <div style="text-align: center; width: 100%; padding-top: 3%;">
                                                                            <a href="{task_link}" class="myButton" style="color: #ffffff">View Comment</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:20px;padding-right:30px;padding-bottom:40px;padding-left:30px;">
                                                                <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                    <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">Regards,</span></p>
                                                                    <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{company_name}</span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--<![endif]-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background-color:transparent;">
                                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                    <div style="width:100% !important;">
                                                        <!--[if (!mso)&(!IE)]><!-->
                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                            <!--<![endif]-->
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 60px; padding-right: 0px; padding-bottom: 12px; padding-left: 0px;" valign="top">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                        </div>
                                                        <!--<![endif]-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background-color:transparent;">
                                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f8f8f9;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f8f8f9;">
                                                <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                    <div style="width:100% !important;">
                                                        <!--[if (!mso)&(!IE)]><!-->
                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                            <!--<![endif]-->
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;" valign="top">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            27 => 
            array (
                'id' => 28,
                'template_id' => 22,
                'subject' => 'Dear valued customer welcome to {company_name}',
                'body' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
                    <head>
                        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
                        <meta content="width=device-width" name="viewport"/>
                        <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
                        <title>Task</title>
                        <style type="text/css">
                                body {
                                    margin: 0;
                                    padding: 0;
                                }
                                table,
                                td,
                                tr {
                                    vertical-align: top;
                                    border-collapse: collapse;
                                }
                                * {
                                    line-height: inherit;
                                }
                                a[x-apple-data-detectors=true] {
                                    color: inherit !important;
                                    text-decoration: none !important;
                                }
                                .myButton {
                                    background-color:#1aa19c;
                                    border-radius:5px;
                                    display:inline-block;
                                    cursor:pointer;
                                    color:#ffffff;
                                    font-family:Trebuchet MS;
                                    font-size:17px;
                                    font-weight:bold;
                                    padding:9px 28px;
                                    text-decoration:none;
                                    text-shadow:0px 1px 2px #3d768a;
                                }
                                .myButton:hover {
                                    background-color:#408c99;
                                }
                                .myButton:active {
                                    position:relative;
                                    top:1px;
                                }
                        </style>
                        <style id="media-query" type="text/css">
                                @media (max-width: 660px) {
                                    .block-grid,
                                    .col {
                                        min-width: 320px !important;
                                        max-width: 100% !important;
                                        display: block !important;
                                    }
                                    .block-grid {
                                        width: 100% !important;
                                    }
                                    .col {
                                        width: 100% !important;
                                    }
                                    .col>div {
                                        margin: 0 auto;
                                    }
                                    img.fullwidth,
                                    img.fullwidthOnMobile {
                                        max-width: 100% !important;
                                    }
                                    .no-stack .col {
                                        min-width: 0 !important;
                                        display: table-cell !important;
                                    }
                                    .no-stack.two-up .col {
                                        width: 50% !important;
                                    }
                                    .no-stack .col.num4 {
                                        width: 33% !important;
                                    }
                                    .no-stack .col.num8 {
                                        width: 66% !important;
                                    }
                                    .no-stack .col.num4 {
                                        width: 33% !important;
                                    }
                                    .no-stack .col.num3 {
                                        width: 25% !important;
                                    }
                                    .no-stack .col.num6 {
                                        width: 50% !important;
                                    }
                                    .no-stack .col.num9 {
                                        width: 75% !important;
                                    }
                                    .video-block {
                                        max-width: none !important;
                                    }
                                    .mobile_hide {
                                        min-height: 0px;
                                        max-height: 0px;
                                        max-width: 0px;
                                        display: none;
                                        overflow: hidden;
                                        font-size: 0px;
                                    }
                                    .desktop_hide {
                                        display: block !important;
                                        max-height: none !important;
                                    }
                                }

                        </style>
                    </head>
                    <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f8f8f9;">
                        <table bgcolor="#f8f8f9" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f8f8f9; width: 100%;" valign="top" width="100%">
                            <tbody>
                                <tr style="vertical-align: top;" valign="top">
                                    <td style="word-break: break-word; vertical-align: top;" valign="top">
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <div style="color:#555555;font-family:Pacifico, cursive;line-height:1.2;padding-top:35px;padding-right:0px;padding-bottom:20px;padding-left:30px;">
                                                                <div style="line-height: 1.2; font-size: 18px; color: #555555; font-family: Pacifico, cursive; mso-line-height-alt: 14px;">
                                                                    <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 30px;">{company_name}</span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f3fafa;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f3fafa;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 580px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:30px solid #FFFFFF; border-bottom:0px solid transparent; border-right:30px solid #FFFFFF; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 4px solid #1AA19C; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 35px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:15px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                    <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"><strong>Hello {customer_name},</strong></span></p>
                                                                    </div>
                                                                </div>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:0px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                    <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 18px;">Take our warm welcome, please login to the <a href="{company_url}">portal</a> to see the details of your account.</span></p><br/>
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;">Your login credentials are as follows -</p>
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;">Email: {email}</p>
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;">Password: {password}</p>
                                                                    </div>
                                                                </div> 
                                                            <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:20px;padding-right:30px;padding-bottom:40px;padding-left:30px;">
                                                                <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                    <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">Regards,</span></p>
                                                                    <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{company_name}</span></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--<![endif]-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background-color:transparent;">
                                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                    <div style="width:100% !important;">
                                                        <!--[if (!mso)&(!IE)]><!-->
                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                            <!--<![endif]-->
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 60px; padding-right: 0px; padding-bottom: 12px; padding-left: 0px;" valign="top">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                        </div>
                                                        <!--<![endif]-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background-color:transparent;">
                                        <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f8f8f9;">
                                            <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f8f8f9;">
                                                <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                    <div style="width:100% !important;">
                                                        <!--[if (!mso)&(!IE)]><!-->
                                                        <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                            <!--<![endif]-->
                                                            <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                <tbody>
                                                                    <tr style="vertical-align: top;" valign="top">
                                                                        <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;" valign="top">
                                                                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                <tbody>
                                                                                    <tr style="vertical-align: top;" valign="top">
                                                                                        <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            28 => 
            array (
                'id' => 29,
                'template_id' => 23,
                'subject' => 'Dear valued Supplier welcome to {company_name}',
                'body' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
                            <head>
                                <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
                                <meta content="width=device-width" name="viewport"/>
                                <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
                                <title>Task</title>
                                <style type="text/css">
                                        body {
                                            margin: 0;
                                            padding: 0;
                                        }
                                        table,
                                        td,
                                        tr {
                                            vertical-align: top;
                                            border-collapse: collapse;
                                        }
                                        * {
                                            line-height: inherit;
                                        }
                                        a[x-apple-data-detectors=true] {
                                            color: inherit !important;
                                            text-decoration: none !important;
                                        }
                                        .myButton {
                                            background-color:#1aa19c;
                                            border-radius:5px;
                                            display:inline-block;
                                            cursor:pointer;
                                            color:#ffffff;
                                            font-family:Trebuchet MS;
                                            font-size:17px;
                                            font-weight:bold;
                                            padding:9px 28px;
                                            text-decoration:none;
                                            text-shadow:0px 1px 2px #3d768a;
                                        }
                                        .myButton:hover {
                                            background-color:#408c99;
                                        }
                                        .myButton:active {
                                            position:relative;
                                            top:1px;
                                        }
                                </style>
                                <style id="media-query" type="text/css">
                                        @media (max-width: 660px) {
                                            .block-grid,
                                            .col {
                                                min-width: 320px !important;
                                                max-width: 100% !important;
                                                display: block !important;
                                            }
                                            .block-grid {
                                                width: 100% !important;
                                            }
                                            .col {
                                                width: 100% !important;
                                            }
                                            .col>div {
                                                margin: 0 auto;
                                            }
                                            img.fullwidth,
                                            img.fullwidthOnMobile {
                                                max-width: 100% !important;
                                            }
                                            .no-stack .col {
                                                min-width: 0 !important;
                                                display: table-cell !important;
                                            }
                                            .no-stack.two-up .col {
                                                width: 50% !important;
                                            }
                                            .no-stack .col.num4 {
                                                width: 33% !important;
                                            }
                                            .no-stack .col.num8 {
                                                width: 66% !important;
                                            }
                                            .no-stack .col.num4 {
                                                width: 33% !important;
                                            }
                                            .no-stack .col.num3 {
                                                width: 25% !important;
                                            }
                                            .no-stack .col.num6 {
                                                width: 50% !important;
                                            }
                                            .no-stack .col.num9 {
                                                width: 75% !important;
                                            }
                                            .video-block {
                                                max-width: none !important;
                                            }
                                            .mobile_hide {
                                                min-height: 0px;
                                                max-height: 0px;
                                                max-width: 0px;
                                                display: none;
                                                overflow: hidden;
                                                font-size: 0px;
                                            }
                                            .desktop_hide {
                                                display: block !important;
                                                max-height: none !important;
                                            }
                                        }

                                </style>
                            </head>
                            <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f8f8f9;">
                                <table bgcolor="#f8f8f9" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f8f8f9; width: 100%;" valign="top" width="100%">
                                    <tbody>
                                        <tr style="vertical-align: top;" valign="top">
                                            <td style="word-break: break-word; vertical-align: top;" valign="top">
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                            <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                                <div style="width:100% !important;">
                                                                    <div style="color:#555555;font-family:Pacifico, cursive;line-height:1.2;padding-top:35px;padding-right:0px;padding-bottom:20px;padding-left:30px;">
                                                                        <div style="line-height: 1.2; font-size: 18px; color: #555555; font-family: Pacifico, cursive; mso-line-height-alt: 14px;">
                                                                            <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 30px;">{company_name}</span></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div style="background-color:transparent;">
                                                    <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f3fafa;">
                                                        <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f3fafa;">
                                                            <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 580px;">
                                                                <div style="width:100% !important;">
                                                                    <!--[if (!mso)&(!IE)]><!-->
                                                                    <div style="border-top:0px solid transparent; border-left:30px solid #FFFFFF; border-bottom:0px solid transparent; border-right:30px solid #FFFFFF; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                        <!--<![endif]-->
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                            <tbody>
                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                    <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 4px solid #1AA19C; width: 100%;" valign="top" width="100%">
                                                                                            <tbody>
                                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                                    <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                            <tbody>
                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                    <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 35px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                            <tbody>
                                                                                                <tr style="vertical-align: top;" valign="top">
                                                                                                    <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:15px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                            <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                                <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"><strong>Hello {supplier_name},</strong></span></p>
                                                                            </div>
                                                                        </div>
                                                                        <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:0px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                            <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                                <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 18px;">Thank you for being a part of {company_name} family as a supplier.</span></p>
                                                                            </div>
                                                                        </div>                                              
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:20px;padding-right:30px;padding-bottom:40px;padding-left:30px;">
                                                                        <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                            <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">Regards,</span></p>
                                                                            <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{company_name}</span></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!--<![endif]-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="background-color:transparent;">
                                                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                        <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                            <div style="width:100% !important;">
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                    <!--<![endif]-->
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 60px; padding-right: 0px; padding-bottom: 12px; padding-left: 0px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <!--[if (!mso)&(!IE)]><!-->
                                                                </div>
                                                                <!--<![endif]-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="background-color:transparent;">
                                                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f8f8f9;">
                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f8f8f9;">
                                                        <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                            <div style="width:100% !important;">
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                    <!--<![endif]-->
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </body>
                    </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            29 => 
            array (
                'id' => 30,
                'template_id' => 24,
                'subject' => 'Welcome to {company_name} as a Team Member',
                'body' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
                        <head>
                            <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
                            <meta content="width=device-width" name="viewport"/>
                            <meta content="IE=edge" http-equiv="X-UA-Compatible"/>
                            <title>Task</title>
                            <style type="text/css">
                                    body {
                                        margin: 0;
                                        padding: 0;
                                    }
                                    table,
                                    td,
                                    tr {
                                        vertical-align: top;
                                        border-collapse: collapse;
                                    }
                                    * {
                                        line-height: inherit;
                                    }
                                    a[x-apple-data-detectors=true] {
                                        color: inherit !important;
                                        text-decoration: none !important;
                                    }
                                    .myButton {
                                        background-color:#1aa19c;
                                        border-radius:5px;
                                        display:inline-block;
                                        cursor:pointer;
                                        color:#ffffff;
                                        font-family:Trebuchet MS;
                                        font-size:17px;
                                        font-weight:bold;
                                        padding:9px 28px;
                                        text-decoration:none;
                                        text-shadow:0px 1px 2px #3d768a;
                                    }
                                    .myButton:hover {
                                        background-color:#408c99;
                                    }
                                    .myButton:active {
                                        position:relative;
                                        top:1px;
                                    }
                            </style>
                            <style id="media-query" type="text/css">
                                    @media (max-width: 660px) {
                                        .block-grid,
                                        .col {
                                            min-width: 320px !important;
                                            max-width: 100% !important;
                                            display: block !important;
                                        }
                                        .block-grid {
                                            width: 100% !important;
                                        }
                                        .col {
                                            width: 100% !important;
                                        }
                                        .col>div {
                                            margin: 0 auto;
                                        }
                                        img.fullwidth,
                                        img.fullwidthOnMobile {
                                            max-width: 100% !important;
                                        }
                                        .no-stack .col {
                                            min-width: 0 !important;
                                            display: table-cell !important;
                                        }
                                        .no-stack.two-up .col {
                                            width: 50% !important;
                                        }
                                        .no-stack .col.num4 {
                                            width: 33% !important;
                                        }
                                        .no-stack .col.num8 {
                                            width: 66% !important;
                                        }
                                        .no-stack .col.num4 {
                                            width: 33% !important;
                                        }
                                        .no-stack .col.num3 {
                                            width: 25% !important;
                                        }
                                        .no-stack .col.num6 {
                                            width: 50% !important;
                                        }
                                        .no-stack .col.num9 {
                                            width: 75% !important;
                                        }
                                        .video-block {
                                            max-width: none !important;
                                        }
                                        .mobile_hide {
                                            min-height: 0px;
                                            max-height: 0px;
                                            max-width: 0px;
                                            display: none;
                                            overflow: hidden;
                                            font-size: 0px;
                                        }
                                        .desktop_hide {
                                            display: block !important;
                                            max-height: none !important;
                                        }
                                    }

                            </style>
                        </head>
                        <body class="clean-body" style="margin: 0; padding: 0; -webkit-text-size-adjust: 100%; background-color: #f8f8f9;">
                            <table bgcolor="#f8f8f9" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="table-layout: fixed; vertical-align: top; min-width: 320px; Margin: 0 auto; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f8f8f9; width: 100%;" valign="top" width="100%">
                                <tbody>
                                    <tr style="vertical-align: top;" valign="top">
                                        <td style="word-break: break-word; vertical-align: top;" valign="top">
                                            <div style="background-color:transparent;">
                                                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                        <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                            <div style="width:100% !important;">
                                                                <div style="color:#555555;font-family:Pacifico, cursive;line-height:1.2;padding-top:35px;padding-right:0px;padding-bottom:20px;padding-left:30px;">
                                                                    <div style="line-height: 1.2; font-size: 18px; color: #555555; font-family: Pacifico, cursive; mso-line-height-alt: 14px;">
                                                                        <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: center; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 30px;">{company_name}</span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="background-color:transparent;">
                                                <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f3fafa;">
                                                    <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f3fafa;">
                                                        <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 580px;">
                                                            <div style="width:100% !important;">
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                                <div style="border-top:0px solid transparent; border-left:30px solid #FFFFFF; border-bottom:0px solid transparent; border-right:30px solid #FFFFFF; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                    <!--<![endif]-->
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 4px solid #1AA19C; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                        <tbody>
                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 35px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" valign="top">
                                                                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                        <tbody>
                                                                                            <tr style="vertical-align: top;" valign="top">
                                                                                                <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:15px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                            <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #2b303a; font-size: 18px;"><strong>Hello {user_name},</strong></span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.2;padding-top:0px;padding-right:0px;padding-bottom:10px;padding-left:30px;">
                                                                        <div style="line-height: 1.2; font-size: 12px; color: #555555; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14px;">
                                                                            <p style="font-size: 18px; line-height: 1.2; word-break: break-word; text-align: left; mso-line-height-alt: 22px; margin: 0;"><span style="color: #008080; font-size: 18px;">A warm welcome to {company_name} family, please login to the <a href="{company_url}">portal</a> to see the details of your account.</span></p>
                                                                        </div>
                                                                    </div>        
                                                                    <div style="margin-left:5px;color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:10px;padding-right:10px;padding-bottom:20px;padding-left:25px;">
                                                                        <h5> <u>Credentials</u>: </h5>
                                                                          <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                              <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">User ID: <span style="color: #555555;"><strong> {user_id}</strong></span></span></p>
                                                                              <p style="font-size: 15px; line-height: 1.5; font-family: inherit; word-break: break-word; mso-line-height-alt: 23px; margin: 0;"><span style="font-size: 15px;">Password: <span style="color: #555555;"><strong> {user_pass}</strong></span></span></p>
                                                                          </div>
                                                                      </div>
                                                                <div style="color:#555555;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;line-height:1.5;padding-top:20px;padding-right:30px;padding-bottom:40px;padding-left:30px;">
                                                                    <div style="line-height: 1.5; font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; color: #555555; mso-line-height-alt: 18px;">
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">Thank you,</span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{assigned_by_whom}</span></p>
                                                                        <p style="font-size: 15px; line-height: 1.5; word-break: break-word; text-align: left; font-family: inherit; mso-line-height-alt: 23px; margin: 0;"><span style="color: #2b303a; font-size: 15px;">{company_name}</span></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #fff;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#fff;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:0px; padding-bottom:0px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 60px; padding-right: 0px; padding-bottom: 12px; padding-left: 0px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <!--[if (!mso)&(!IE)]><!-->
                                                            </div>
                                                            <!--<![endif]-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background-color:transparent;">
                                            <div class="block-grid" style="Margin: 0 auto; min-width: 320px; max-width: 640px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; background-color: #f8f8f9;">
                                                <div style="border-collapse: collapse;display: table;width: 100%;background-color:#f8f8f9;">
                                                    <div class="col num12" style="min-width: 320px; max-width: 640px; display: table-cell; vertical-align: top; width: 640px;">
                                                        <div style="width:100% !important;">
                                                            <!--[if (!mso)&(!IE)]><!-->
                                                            <div style="border-top:0px solid transparent; border-left:0px solid transparent; border-bottom:0px solid transparent; border-right:0px solid transparent; padding-top:5px; padding-bottom:5px; padding-right: 0px; padding-left: 0px;">
                                                                <!--<![endif]-->
                                                                <table border="0" cellpadding="0" cellspacing="0" class="divider" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top" width="100%">
                                                                    <tbody>
                                                                        <tr style="vertical-align: top;" valign="top">
                                                                            <td class="divider_inner" style="word-break: break-word; vertical-align: top; min-width: 100%; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; padding-top: 20px; padding-right: 20px; padding-bottom: 20px; padding-left: 20px;" valign="top">
                                                                                <table align="center" border="0" cellpadding="0" cellspacing="0" class="divider_content" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-top: 0px solid #BBBBBB; width: 100%;" valign="top" width="100%">
                                                                                    <tbody>
                                                                                        <tr style="vertical-align: top;" valign="top">
                                                                                            <td style="word-break: break-word; vertical-align: top; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;" valign="top"><span></span></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
            30 => 
            array (
                'id' => 31,
                'template_id' => 25,
                'subject' => '{company_name} - Activate your account',
                'body' => '<!DOCTYPE html>
                <html>
                <head>
                  <meta charset="utf-8">
                  <meta http-equiv="x-ua-compatible" content="ie=edge">
                  <title>Activation Link</title>
                  <meta name="viewport" content="width=device-width, initial-scale=1">
                  <style type="text/css">
                    /**
                     * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
                     */
                    @media screen {
                      @font-face {
                        font-family: Source Sans Pro;
                        font-style: normal;
                        font-weight: 400;
                        src: local("Source Sans Pro Regular"), local("SourceSansPro-Regular"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format("woff");
                      }
                      @font-face {
                        font-family: Source Sans Pro;
                        font-style: normal;
                        font-weight: 700;
                        src: local("Source Sans Pro Bold"), local("SourceSansPro-Bold"), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format("woff");
                      }
                    }
                    /**
                     * Avoid browser level font resizing.
                     * 1. Windows Mobile
                     * 2. iOS / OSX
                     */
                    body,
                    table,
                    td,
                    a {
                      -ms-text-size-adjust: 100%; /* 1 */
                      -webkit-text-size-adjust: 100%; /* 2 */
                    }
                    /**
                     * Remove extra space added to tables and cells in Outlook.
                     */
                    table,
                    td {
                      mso-table-rspace: 0pt;
                      mso-table-lspace: 0pt;
                    }
                    /**
                     * Better fluid images in Internet Explorer.
                     */
                    img {
                      -ms-interpolation-mode: bicubic;
                    }
                    /**
                     * Remove blue links for iOS devices.
                     */
                    a[x-apple-data-detectors] {
                      font-family: inherit !important;
                      font-size: inherit !important;
                      font-weight: inherit !important;
                      line-height: inherit !important;
                      color: inherit !important;
                      text-decoration: none !important;
                    }
                    /**
                     * Fix centering issues in Android 4.4.
                     */
                    div[style*="margin: 16px 0;"] {
                      margin: 0 !important;
                    }
                    body {
                      width: 100% !important;
                      height: 100% !important;
                      padding: 0 !important;
                      margin: 0 !important;
                    }
                    /**
                     * Collapse table borders to avoid space between cells.
                     */
                    table {
                      border-collapse: collapse !important;
                    }
                    a {
                      color: #1a82e2;
                    }
                    img {
                      height: auto;
                      line-height: 100%;
                      text-decoration: none;
                      border: 0;
                      outline: none;
                    }
                  </style>
                </head>
                <body style="background-color: #e9ecef;">
                <div class="preheader" style="display: none; color:black; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
                  A preheader is the short summary text that follows the subject line when an email is viewed in the inbox.
                </div>
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                    <td align="center" bgcolor="#e9ecef">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                        <tr>
                          <td align="center" valign="top" style="padding: 36px 24px;">
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" bgcolor="#e9ecef">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                        <tr>
                          <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
                            <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px; text-align: center; color: cornflowerblue;">Activate Your Account</h1>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" bgcolor="#e9ecef">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                        <tr>
                          <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                            <p style="margin: 0; color:black;">Dear {customer_name},</p>
                            <p style=" color:black;">To activate your account, go to the following the button: </p>
                          </td>
                        </tr>
                        <tr>
                          <td align="left" bgcolor="#ffffff">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                              <tr>
                                <td align="center" bgcolor="#ffffff" style="padding: 12px;">
                                  <table border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                      <td align="center" bgcolor="#1a82e2" style="border-radius: 6px;">
                                        <a href="{activation_link}" target="_blank" style="display: inline-block; padding: 16px 36px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;">Click here</a>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                            <p style="margin: 0; color:black;">If that does not work, click on the following link in your browser:</p>
                            <p style="margin: 0;"><a href="{activation_link}" target="_blank">{activation_link}</a></p>
                          </td>
                        </tr>
                        <tr>
                          <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: Source Sans Pro, Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf">
                            <p style="margin: 0;  color:black;">Thanks & Regards,<br> {company_name}</p>
                            <p style=" color:black;">
                              From : {company_name}<br />
                              Email: {company_email}<br />
                              Phone: {company_phone}<br />
                              Address: {company_street}, {company_city}, {company_state}
                            </p>
                            <br />
                            <hr>
                            <p style="text-align: center;font-size:12px">©{company_name}, all rights reserved</p>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                </body>
                </html>',
                'language_short_name' => 'en',
                'template_type' => 'email',
                'language_id' => 1,
            ),
        ));
    }
}