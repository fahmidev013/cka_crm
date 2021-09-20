<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/makejson', function () {
    $p = require app()->langPath() . "\\en\\message.php";
    Storage::disk('local')->put('message.json', json_encode($p));
    return "Converted to json to storage folder";
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
});

Route::get('/makearray', function () {
    // Object of translated object
    // Send request to node server where
    // It will be translated and return the json object;
    $obj = '';
    $message  = json_decode(json_encode(json_decode($obj)), true);
    $m        = array2string($message);
    $formated = "<?php \r\n return [$m];";
    Storage::disk('local')->put('ar_message.txt', $formated);
    return "Array Conversion Done!";
});

// Cron job

// Admin part
Route::get('/', 'LoginController@showLoginForm');
Route::get('/login', 'LoginController@showLoginForm')->name('login');
Route::get('/admin', 'LoginController@showLoginForm');

Route::post('/authenticate', 'LoginController@authenticate')->name('login.post');
Route::get('/logout', 'LoginController@logout');

// Password reset
Route::get('password/resets/{type}/{token}', 'LoginController@showResetForm');
Route::post('password/resets', 'LoginController@setPassword');
Route::post('password/email/{type}', 'LoginController@sendResetLinkEmail')->name('login.sendResetLink');
Route::get('password/reset/{type}', 'LoginController@reset')->name('login.reset');

// Customer sign up
Route::get('/customer/register', 'CustomerRegistrationController@create')->name('customerRegistration.create');
Route::post('/customer/store', 'CustomerRegistrationController@store')->name('customerRegistration.store');
Route::get('/customer/activation/{token}', 'CustomerRegistrationController@customerActivation');

// Customer login
Route::get('customer', 'CustomerAuth\LoginController@showLoginForm');
Route::post('customer/authenticate', 'CustomerAuth\LoginController@login');
Route::get('customer/logout', 'CustomerAuth\LoginController@logout');

Route::group(['middleware' => ['customer', 'locale']], function() {
	Route::get('customer-files/ticket/downloads/{id}', 'TicketController@downloadAttachment');
	Route::get('customer/dashboard', 'CustomerPanelController@index');
	Route::get('customer/profile', 'CustomerPanelController@profile');
	Route::post('customer/profile', 'CustomerPanelController@updateProfile');
	Route::post('customer/update-customer-password', 'CustomerPanelController@updateCustomerPassword');
	Route::get('customer-panel/order', 'CustomerPanelController@salesOrder');
	Route::get('customer-panel/view-order-details/{id}', 'CustomerPanelController@viewOrderDetails')->middleware('quotation.detail');

	Route::get('customer-panel/order/print-pdf/{id}', 'SaleOrderController@orderPrintPdf')->middleware('quotation.detail');

	Route::get('customer-panel/view-detail-invoice/{invoice_id}', 'CustomerPanelController@viewInvoiceDetails')->middleware('invoice.detail');

	Route::get('customer-panel/invoice/print-pdf/{invoice_id}', 'InvoiceController@invoicePrintPdf')->middleware('invoice.detail');

	Route::get('customer-panel/invoice', 'CustomerPanelController@invoice');
	Route::get('customer-panel/invoice-pdf/{order_id}/{invoice_id}', 'CustomerPanelController@invoicePrintPdf');
	Route::get('customer-panel/invoice-print/{order_id}/{invoice_id}', 'CustomerPanelController@invoicePrintPdf');
	Route::get('customer-panel/payment', 'CustomerPanelController@payment');
	Route::get('customer-panel/view-receipt/{id}', 'CustomerPanelController@viewReceipt')->middleware('payment.detail');

	Route::get('customer-panel/payment/create-receipt/{id}', 'PaymentController@createReceiptPdf')->middleware('payment.detail');

	Route::get('customer-panel/project', 'CustomerPanelController@projectList');
	Route::get('customer-project/detail/{project_id}', 'CustomerPanelController@projectDetails');
	Route::match(array('GET', 'POST'),'customer-project/edittask/{project_id}/{user_id}', 'CustomerPanelController@editTaskByCustomer');
	Route::get('customer-project/task-details/{project_id}/{task_id}', 'CustomerPanelController@projectTaskDetails');
	Route::post('customer-task/store-comment', 'TaskController@customerStoreComment');
	Route::post('customer-task/delete-comment', 'TaskController@customerDeleteComment');
	Route::post('customer-task/update-comment', 'TaskController@customerUpdateComment');
	Route::post('customer-task/file-store', 'TaskController@customerFileStore');
	Route::post('customer-task/file-delete', 'TaskController@customerFileDelete');

	// Customer support / ticket
	Route::get('customer-panel/support/add', 'TicketController@addCustomerTicket');
	Route::post('customer-ticket/store', 'TicketController@storeCustomerTicket');
	Route::get('customer-panel/support/list', 'TicketController@customerTicketList');
    Route::get('customer-panel/support/reply/{id}', 'TicketController@customerReply');
    Route::post('customer-ticket/replyStore', 'TicketController@customerReplyStore');
    Route::post('customer-ticket/reply_delete', 'TicketController@replyDelete');
    Route::get('customer-ticket-filtering-csv', 'TicketController@customerTicketFilteringCsv');
    Route::get('customer-ticket-filtering-pdf', 'TicketController@customerTicketFilteringPdf');
    Route::post('customer-panel/file/upload', 'FilesController@uploadEventAttachments');
    Route::post('customer-panel/file/remove', 'FilesController@deleteEventAttachment');
    Route::get('customer-panel-quotation-filtering-pdf', 'CustomerController@quotationFilterPdf');
    Route::get('customer-panel-quotation-filtering-csv', 'CustomerController@quotationFilterCsv');
    Route::get('customer-panel/sales-report-csv', 'CustomerController@invoiceCsv');
	Route::get('customer-panel/sales-report-pdf', 'CustomerController@invoicePdf');
	Route::get('customer-panel/payment/customer-pdf', 'PaymentController@customerPaymentPdf');
	Route::get('customer-panel/payment/customer-csv', 'PaymentController@customerPaymentCsv');
	Route::get('customer-panel/projects-csv', 'CustomerController@projectCsv');
	Route::get('customer-panel/projects-pdf', 'CustomerController@projectPdf');
	Route::post('customer-panel/ticket/change-status', 'TicketController@changeStatus');

    Route::get('customer-panel/knowledge-base', 'KnowledgeBaseController@customerKnowledgeList');
    Route::get('customer-panel/knowledge-base/groups/{name}', 'KnowledgeBaseController@customerKnowledgeGroup');
    Route::get('customer-panel/knowledge-base/article/{slug}', 'KnowledgeBaseController@customerKnowledgeArticle');
    Route::get('customer-panel/knowledge-base/search', 'KnowledgeBaseController@customerKnowledgeSearch');
});

Route::group(['middleware' => ['auth', 'locale'] ], function() {
	// User
	Route::get('dashboard', 'DashboardController@index');
	Route::get('users', 'UserController@index')->middleware(['permission:manage_team_member']);
	Route::get('create-user', 'UserController@create')->middleware(['permission:add_team_member']);
	Route::get('user-import', 'UserController@importUser')->middleware(['permission:add_team_member']);
	Route::post('user-import-csv', 'UserController@importUserCSV')->middleware(['permission:add_team_member']);
	Route::post('save-user', 'UserController@store');
	Route::post('delete-user/{id}', 'UserController@destroy')->middleware(['permission:delete_team_member']);
    Route::post('user/change-status', 'UserController@change_status')->middleware(['permission:delete_team_member']);
	Route::get('email-valid-user', 'UserController@validEmail');
	Route::get('profile', 'UserController@profile'); //1
	Route::get('change-password', 'UserController@changePassword'); //2
	Route::post('change-password', 'UserController@updatePassword');
	Route::post('user/change-active-status', 'UserController@changeActiveStatus');

	// User details
	Route::get('user/purchase-list/{id}', 'UserController@userPurchaseOrderList');
	Route::get('user/sales-order-list/{id}', 'UserController@userSalesOrderList');
	Route::get('user/sales-invoice-list/{id}', 'UserController@userSalesInvoiceList');
	Route::get('user/user-transfer-list/{id}', 'UserController@userTransferList');
	Route::get('user/user-payment-list/{id}', 'UserController@userPaymentList');
	Route::get('user/user-purchase-payment-list/{id}', 'UserController@userPurchasePaymentList');
	Route::get('user/team-member-profile/{id}', 'UserController@teamMemberProfile')->middleware(['permission:edit_team_member']);
	Route::post('user/team-member-update/{id}', 'UserController@teamMemberUpdate');
    Route::post('change-member-password/{id}', 'UserController@updateMemberPassword');
    Route::get('user/purchase-list-pdf', 'UserController@userPurchaseOrderListPdf');
    Route::get('user/purchase-list-csv', 'UserController@userPurchaseOrderListCsv');
    Route::get('user/quotation-list-pdf', 'UserController@userQuotationListPdf');
    Route::get('user/quotation-list-csv', 'UserController@userQuotationListCsv');
    Route::get('user/invoice-list-pdf', 'UserController@userInvoiceListPdf');
    Route::get('user/invoice-list-csv', 'UserController@userInvoiceListCsv');
    Route::get('user/invoice-payment-list-pdf', 'UserController@userInvoicePaymentListPdf');
    Route::get('user/invoice-payment-list-csv', 'UserController@userInvoicePaymentListCsv');
    Route::get('user/purchase-payment-list-pdf', 'UserController@userPurchasePaymentListPdf');
    Route::get('user/purchase-payment-list-csv', 'UserController@userPurchasePaymentListCsv');
    Route::get('user/project-list/{id}', 'UserController@userProjectList');
    Route::get('user/task-list/{id}', 'UserController@userTaskList');
    Route::get('user/tasks_csv', 'UserController@userTaskCSV');
    Route::get('user/tasks_pdf', 'UserController@userTaskPdf');
    Route::get('user/project-list-csv', 'UserController@userProjectListCsv');
    Route::get('user/project-list-pdf', 'UserController@userProjectListPdf');
    Route::get('team_member_list_pdf', 'UserController@teamMemberListPdf');
    Route::get('team_member_list_csv', 'UserController@teamMemberListCsv');

	// User role
	Route::get('role/list', 'RoleController@index')->middleware(['permission:manage_role']);
	Route::get('role/create', 'RoleController@create')->middleware(['permission:add_role']);
	Route::post('role/save', 'RoleController@store');
	Route::get('role/edit/{id}', 'RoleController@edit')->middleware(['permission:edit_role']);
	Route::post('role/update', 'RoleController@update');
	Route::post('role/delete', 'RoleController@destroy')->middleware(['permission:delete_role']);
    Route::get('role-valid', 'RoleController@validRoleName');

	// Item category
	Route::get('item-category', 'CategoryController@index');
	Route::post('save-category', 'CategoryController@store');
	Route::post('edit-category', 'CategoryController@edit')->middleware(['permission:edit_item_category']);
	Route::post('update-category', 'CategoryController@update');
	Route::post('delete-category/{id}', 'CategoryController@destroy')->middleware(['permission:delete_item_category']);
	Route::get('categorydownloadExcel/{type}', 'CategoryController@downloadCsv');
	Route::get('categoryimport', 'CategoryController@import');
	Route::post('categoryimportcsv', 'CategoryController@importCsv');
    Route::get('category-valid', 'CategoryController@validCategoryName');
    Route::get('edit-category-valid', 'CategoryController@editValidCategoryName');

    // Ticket setting
    Route::get('department-setting', 'TicketSettingController@department')->middleware(['permission:manage_department']);
    Route::get('departmentdownload/{type}', 'TicketSettingController@departmentdownload')->middleware(['permission:manage_department']);
    Route::post('save-department', 'TicketSettingController@storeDepartment');
    Route::post('edit-department', 'TicketSettingController@editDepartment')->middleware(['permission:edit_department']);
    Route::post('update-department', 'TicketSettingController@updateDepartment');
    Route::post('department/delete', 'TicketSettingController@destroyDepartment')->middleware(['permission:delete_department']);
	Route::get('department-valid', 'TicketSettingController@validDepartmentName');

    // Status setting
    Route::get('status-setting', 'StatusSettingController@index');
    Route::post('store-status', 'StatusSettingController@storeStatus');
    Route::post('update-status', 'StatusSettingController@update');
    Route::post('status/delete', 'StatusSettingController@destroyStatus');

	// Item unit
	Route::get('unit', 'UnitController@index')->middleware(['permission:manage_unit']);
	Route::get('unitAbbr-valid', 'UnitController@validUnitAbbr');
	Route::post('save-unit', 'UnitController@store');
	Route::post('edit-unit', 'UnitController@edit')->middleware(['permission:edit_unit']);
	Route::post('update-unit', 'UnitController@update');
	Route::post('delete-unit/{id}', 'UnitController@destroy')->middleware(['permission:delete_unit']);

	// languages
	Route::get('languages', 'LanguageController@index')->middleware(['permission:manage_language']);
	Route::get('languages/translation/{id}', 'LanguageController@translation')->middleware(['permission:edit_language']);
	Route::post('languages/translation/save', 'LanguageController@translationStore')->middleware(['permission:edit_language']);
	Route::get('languageShortName-valid', 'LanguageController@validLanguageShortName');
	Route::post('delete-language/{id}', 'LanguageController@delete_language')->middleware(['permission:delete_language']);
	Route::post('save-language', 'LanguageController@save_language')->middleware(['permission:add_language']);
	Route::post('edit-language', 'LanguageController@edit')->middleware(['permission:edit_language']);
	Route::post('update-language', 'LanguageController@update_language')->middleware(['permission:edit_language']);

	// Location
	Route::get('location', 'LocationController@index')->middleware(['permission:manage_location']);
	Route::get('create-location', 'LocationController@create')->middleware(['permission:add_location']);
	Route::post('save-location', 'LocationController@store');
	Route::get('edit-location/{id}', 'LocationController@edit')->middleware(['permission:edit_location']);
	Route::post('update-location/{id}', 'LocationController@update');
	Route::post('delete-location/{id}', 'LocationController@destroy')->middleware(['permission:delete_location']);
	Route::get('loc_code-valid', 'LocationController@validLocCode');

	// Item
	Route::get('item', 'ItemController@index')->middleware(['permission:manage_item']);
	Route::get('create-item', 'ItemController@create')->middleware(['permission:add_item']);
	Route::post('save-item', 'ItemController@store');
	Route::get('edit-item/{tab}/{id}', 'ItemController@edit')->middleware(['permission:edit_item']);
	Route::get('add-variant/{id}', 'ItemController@addVariant')->middleware(['permission:add_item']);
	Route::post('save-variant', 'ItemController@storeVariant');
	Route::post('update-item-info', 'ItemController@updateItemInfo');
	Route::post('item/delete', 'ItemController@destroy')->middleware(['permission:delete_item']);
	Route::post('item/stock-update-ajax', 'ItemController@ajaxStockManage');
    Route::get('item/name_validation', 'ItemController@itemNameValidationRemote');
    Route::get('item/stock_id_validation', 'ItemController@stockIdValidationRemote');
	Route::get('variant-item-downloadcsv/{id}', 'ItemController@downloadVariantCsv')->middleware(['permission:manage_item']);
	Route::get('variant-item-downloadpdf/{id}', 'ItemController@downloadVariantPdf')->middleware(['permission:manage_item']);
	Route::get('itemdownloadcsv/{type}', 'ItemController@downloadCsv')->middleware(['permission:manage_item']);
	Route::get('itemdownloadpdf', 'ItemController@downloadPdf')->middleware(['permission:manage_item']);
	Route::get('itemimport', 'ItemController@import')->middleware(['permission:import_items']);
	Route::post('itemimportcsv', 'ItemController@importCsv');
	Route::get('item-notifications', 'ItemController@itemNotifications');


	// Invoice
	Route::get('invoice/list', 'InvoiceController@index')->middleware(['permission:manage_invoice|own_invoice']);
	Route::get('invoice/add', 'InvoiceController@create')->middleware(['permission:add_invoice']);
	Route::post('invoice/store', 'InvoiceController@store')->middleware(['permission:add_invoice']);
	Route::get('invoice/edit/{id}', 'InvoiceController@edit')->middleware(['permission:edit_invoice']);
	Route::post('invoice/update', 'InvoiceController@update')->middleware(['permission:edit_invoice']);
	Route::post('invoice/delete/{id}', 'InvoiceController@destroy')->middleware(['permission:delete_invoice']);
	Route::post('invoice/reference-validation', 'InvoiceController@referenceValidation');
	Route::post('invoice/get-branches', 'InvoiceController@customerBranches');
	Route::post('invoice/item-search', 'InvoiceController@searchItem');
	Route::post('invoice/quantity-validation', 'InvoiceController@quantityValidation');
	Route::post('invoice/check-item-qty', 'InvoiceController@checkItemQty');
	Route::get('invoice/preview/{id}', 'InvoiceController@pdfview');
	Route::post('invoice/quantity-validation-with-localtion', 'InvoiceController@quantityValidationWithLocaltion');
	Route::post('invoice/quantity-validation-edit-invoice', 'InvoiceController@quantityValidationEditInvoice');
	Route::get('sales-list-csv', 'InvoiceController@saleListCsv');
	Route::get('sales-list-pdf', 'InvoiceController@salesListPdf');
	Route::get('invoice/view-detail-invoice/{invoiceId}', 'InvoiceController@view');
	Route::post('invoice/email-invoice-info', 'InvoiceController@sendInvoiceInformationByEmail');
	Route::get('invoice/copy/{invoice_id}', 'InvoiceController@copy');
	Route::post('invoice/copy/{invoice_id}', 'InvoiceController@invoiceCopy');
	Route::get('invoice/print-pdf/{invoiceId}', 'InvoiceController@invoicePrintPdf');
	Route::get('invoice/delete-invoice/{id}', 'InvoiceController@destroy');

	// Sales order
	Route::get('order/list', 'SaleOrderController@index')->middleware(['permission:manage_quotation|own_quotation']);
	Route::get('order/filtering', 'SaleOrderController@index');
	Route::get('order/add', 'SaleOrderController@create')->middleware(['permission:add_quotation']);
	Route::post('order/store', 'SaleOrderController@store')->middleware(['permission:add_quotation']);
	Route::get('order/edit/{id}', 'SaleOrderController@edit')->middleware(['permission:edit_quotation']);
	Route::post('order/update', 'SaleOrderController@update');
	Route::post('order/delete/{id}', 'SaleOrderController@destroy')->middleware(['permission:delete_quotation']);
	Route::get('order/view-order/{id}', 'SaleOrderController@viewOrder');
	Route::post('order/convert-order', 'SaleOrderController@convertOrder');
	Route::post('order/search-item', 'SaleOrderController@searchItem');
	Route::post('order/quantity-validation', 'SaleOrderController@quantityValidation');
    Route::post('note/comment_check_update', 'SaleOrderController@noteText');
	Route::get('order/view-order-details/{id}', 'SaleOrderController@view');
	Route::get('order/auto-invoice-create/{id}', 'SaleOrderController@autoInvoiceCreate');
	Route::post('order/auto-invoice-create/{id}', 'SaleOrderController@autoInvoiceStore');
	Route::get('order/copy/{id}', 'SaleOrderController@copyOrder');
	Route::post('order/check-quantity-after-invoice', 'SaleOrderController@checkQuantityAfterInvoice');
	Route::get('order/print-pdf/{order_id}', 'SaleOrderController@orderPrintPdf');
	Route::post('order/email-order-info', 'SaleOrderController@sendOrderInformationByEmail');
	Route::post('sales/send-sms', 'SmsController@sendSms');
	Route::get('sales_orders_pdf', 'SaleOrderController@salesOrderPdf');
	Route::get('sales_orders_csv', 'SaleOrderController@salesOrderCsv');

	// Customer
	Route::get('customer/list', 'CustomerController@index')->middleware(['permission:manage_customer']);
	Route::get('customer_list_pdf', 'CustomerController@customerListPdf');
	Route::get('customer_list_csv', 'CustomerController@customerListCsv');
	Route::post('email-valid-customer', 'CustomerController@validateCustomerEmail');
	Route::get('create-customer', 'CustomerController@create')->middleware(['permission:add_customer']);
	Route::post('save-customer', 'CustomerController@store');
	Route::get('customer/edit/{id}', 'CustomerController@edit')->middleware(['permission:edit_customer']);
	Route::post('customer/change-status', 'CustomerController@changeStatus');
	Route::get('customer/order/{id}', 'CustomerController@salesOrder');
	Route::get('customer-ledger-filtering-csv', 'CustomerController@ledegerFilterCsv');
	Route::get('customer-ledger-filtering-pdf', 'CustomerController@ledegerFilterPdf');
	Route::get('customer-quotation-filtering-pdf', 'CustomerController@quotationFilterPdf');
	Route::get('customer-quotation-filtering-csv', 'CustomerController@quotationFilterCsv');
	Route::get('customer/invoice/{id}', 'CustomerController@invoice');
	Route::get('customer/quotation/filtering/{id}', 'CustomerController@customerQuotationFilter');
	Route::get('customer/sales-report-csv', 'CustomerController@invoiceCsv');
	Route::get('customer/sales-report-pdf', 'CustomerController@invoicePdf');
	Route::get('customer/payment/{id}', 'CustomerController@payment')->middleware(['permission:manage_payment']);
	Route::get('customer/payment/filtering/{id}', 'CustomerController@customerPaymentFilter');
	Route::get('customer/payment-report-csv', 'CustomerController@paymentCsv');
	Route::get('customer/payment-report-pdf', 'CustomerController@paymentPdf');
	Route::post('update-customer/{id}', 'CustomerController@update');
	Route::post('customer/update-password', 'CustomerController@updatePassword');
	Route::post('delete-customer/{id}', 'CustomerController@destroy')->middleware(['permission:delete_customer']);
	Route::get('customer/ledger/{id}', 'CustomerController@customerLedger');
	Route::get('customer/adminlogin/{id}', 'CustomerController@adminLogin');
	Route::post('customer/billing-address', 'CustomerController@billingAddress');
	Route::get('customerdownloadCsv/{type}', 'CustomerController@downloadCsv');
	Route::get('customerimport', 'CustomerController@import');
	Route::post('customerimportcsv', 'CustomerController@importCustomerCsv');
	Route::post('customer/delete-sales-info', 'CustomerController@deleteSalesInfo');
    Route::post('customer/email_data', 'CustomerController@cusEmailData');
	Route::get('customer/project/{id}', 'CustomerController@project');
	Route::get('customer/projects-csv', 'CustomerController@projectCsv');
	Route::get('customer/projects-pdf', 'CustomerController@projectPdf');
	Route::get('customer/task/{id}', 'CustomerController@task');
	Route::get('customer/tasks-csv', 'CustomerController@taskCsv');
	Route::get('customer/tasks-pdf', 'CustomerController@taskPdf');
	Route::get('customer/ticket/{id}', 'CustomerController@ticket');
	Route::get('customer/tickets-csv', 'CustomerController@ticketCsv');
	Route::get('customer/tickets-pdf', 'CustomerController@ticketPdf');

	// Supplier
	Route::get('supplier', 'SupplierController@index')->middleware(['permission:manage_supplier']);
	Route::get('create-supplier', 'SupplierController@create')->middleware(['permission:add_supplier']);
	Route::post('save-supplier', 'SupplierController@store');
	Route::get('edit-supplier/{id}', 'SupplierController@edit')->middleware(['permission:edit_supplier']);
	Route::post('update-supplier/{id}', 'SupplierController@update');
	Route::post('delete-supplier/{id}', 'SupplierController@destroy')->middleware(['permission:delete_supplier']);
    Route::get('supplier/orders/{id}', 'SupplierController@orderList');
    Route::post('supplier/billing-address/{id}', 'SupplierController@billingAddress');
	Route::get('supplierdownloadCsv/{type}', 'SupplierController@downloadCsv');
	Route::get('supplierimport', 'SupplierController@import');
	Route::post('supplierimportcsv', 'SupplierController@importSupplierCsv');
	Route::get('supplier/purchase-report-csv', 'SupplierController@supplierPurchaseCsv');
	Route::get('supplier/purchase-report-pdf', 'SupplierController@supplierPurchasePdf');
	Route::get('supplier/ledger-report-pdf', 'SupplierController@ledgerSupplierPdf');
	Route::get('supplier/ledger-report-csv', 'SupplierController@ledgerSupplierCsv');
	Route::get('supplier/payment/{id}', 'SupplierController@paymentList');
	Route::get('supplier/payment-pdf', 'SupplierController@supplierPaymentByIdPdf');
	Route::get('supplier/payment-csv', 'SupplierController@supplierPaymentByIdCsv');
	Route::get('supplier_list_csv', 'SupplierController@supplierListCsv');
	Route::get('supplier_list_pdf', 'SupplierController@supplierListPdf');
	Route::get('supplier/payment/ledger/{id}', 'SupplierController@supplierLedger');
	Route::post('supplier/change-status', 'SupplierController@changeStatus');
	Route::get('email-valid-supplier', 'SupplierController@validateSupplierEmail');


	// Check-in purchese order
	Route::get('purchase/list', 'PurchaseController@index')->middleware(['permission:manage_purchase|own_purchase']);
	Route::get('purchase/add-supplier', 'PurchaseController@addSupplier');
	Route::get('purchase/add', 'PurchaseController@create')->middleware(['permission:add_purchase']);
	Route::post('purchase/store', 'PurchaseController@store');
	Route::get('purchase/edit/{id}', 'PurchaseController@edit')->middleware(['permission:edit_purchase']);
	Route::post('purchase/update', 'PurchaseController@update');
	Route::post('purchase/delete/{id}', 'PurchaseController@destroy')->middleware(['permission:delete_purchase']);
	Route::get('purchase/file-list', 'PurchaseController@fileList');
	Route::post('purchase/item-search', 'PurchaseController@searchItem');
	Route::get('purchase/view-purchase-details/{id}', 'PurchaseController@view');
    Route::get('purchase/copy/{id}', 'PurchaseController@copy');
    Route::post('purchase/copy-store', 'PurchaseController@copyPurchase');
	Route::get('purchase/print-pdf/{order_id}', 'PurchaseController@purchasePrintPdf');
	Route::post('purchase/reference-validation', 'PurchaseController@referenceValidation');
	Route::post('purchase/email-puchase-details', 'PurchaseController@emailPurchaseDetails');
	Route::get('purchase-pdf', 'PurchaseController@purchasePdf');
	Route::get('purchase-csv', 'PurchaseController@purchaseCsv');

    // Purchase receive
    Route::get('purchase_receive/list', 'ReceiveController@index')->middleware(['permission:manage_purch_receive|own_purchase_receive']);
    Route::get('purchase/receive/edit/{id}', 'ReceiveController@edit');
    Route::post('purchase/receive/update', 'ReceiveController@update');
    Route::post('purchase/receive/update-date', 'ReceiveController@updateDate');
    Route::get('purchase_receive/details/{id}', 'ReceiveController@receiveDetails');
	Route::get('purchase/receive/manual/{id}', 'ReceiveController@manualReceive');
	Route::post('purchase/receive/manual_store', 'ReceiveController@manualStore');
	Route::get('purchase/receive/all/{id}', 'ReceiveController@allReceive');
	Route::get('purchase/receive/pdf/{id}', 'ReceiveController@receivePdf');
    Route::get('purchase/receive/print/{id}', 'ReceiveController@receivePrint');
    Route::post('purchase/receive/delete/{id}', 'ReceiveController@destroy')->middleware(['permission:delete_purchase_receive']);
    Route::get('purchase/receive/pdf', 'ReceiveController@pdfList');
    Route::get('purchase/receive/csv', 'ReceiveController@ReceiveCsv');

	// Item tax
	Route::get('tax', 'TaxController@index');
	Route::post('save-tax', 'TaxController@store');
	Route::post('edit-tax', 'TaxController@edit')->middleware(['permission:edit_tax']);
	Route::post('update-tax', 'TaxController@update');
	Route::post('delete-tax', 'TaxController@destroy')->middleware(['permission:delete_tax']);
	Route::get('tax-valid', 'TaxController@validTaxName');

	// Settings
	Route::get('setting-email-template', 'SettingController@mailTemp');
	Route::get('setting-preference', 'SettingController@preference');
	Route::get('setting-appearance', 'SettingController@appearance')->middleware(['permission:manage_theme_preference']);
	Route::post('save-appearance', 'SettingController@saveAppearance')->middleware(['permission:manage_theme_preference']);
	Route::get('setting-finance', 'SettingController@finance');
	Route::get('setting-company', 'SettingController@company');
	Route::post('save-preference', 'SettingController@savePreference');
	Route::get('currency', 'SettingController@currency')->middleware(['permission:manage_currency']);
	Route::post('save-currency', 'SettingController@store');
	Route::post('edit-currency', 'SettingController@edit')->middleware(['permission:add_currency']);
	Route::post('update-currency', 'SettingController@update');
	Route::post('delete-currency', 'SettingController@destroy')->middleware(['permission:delete_currency']);
	Route::get('currency-valid', 'SettingController@validCurrencyName');

	Route::get('backup/list', 'SettingController@backupList')->middleware(['permission:manage_db_backup']);
	Route::get('back-up', 'SettingController@backupDB');
	Route::get('email/setup', 'SettingController@emailSetup');
	Route::match(array('GET', 'POST'),'sms/setup', 'SettingController@smsSetup')->middleware(['permission:manage_sms_setup']);
	Route::match(array('GET', 'POST'),'short-url/setup', 'SettingController@shortUrlSetup')->middleware(['permission:manage_url_shortner']);
	Route::get('short-url/create', 'SettingController@shortUrlCreate');
	Route::match(['GET', 'POST'],'captcha/setup', 'SettingController@captchaSetup')->middleware(['permission:manage_captcha_setup']);
	Route::match(['GET', 'POST'],'currency-converter/setup', 'SettingController@currencyConverterSetup')->middleware(['permission:manage_currency_converter_setup']);
	Route::post('save-email-config', 'SettingController@emailSaveConfig');
	Route::get('leadStatus-valid', 'LeadStatusController@validLeadStatus');
	Route::get('lead-status', 'LeadStatusController@index')->middleware(['permission:manage_lead_status']);
	Route::post('save-lead-status', 'LeadStatusController@store');
	Route::post('edit-lead-status', 'LeadStatusController@edit')->middleware(['permission:edit_lead_status']);
	Route::post('update-lead-status', 'LeadStatusController@update');
	Route::post('delete-lead-status/{id}', 'LeadStatusController@destroy')->middleware(['permission:delete_lead_status']);

	Route::get('leadSource-valid', 'LeadSourceController@validLeadSource');
	Route::get('lead-source', 'LeadSourceController@index')->middleware(['permission:manage_lead_source']);
	Route::post('save-lead-source', 'LeadSourceController@store');
	Route::post('edit-lead-source', 'LeadSourceController@edit')->middleware(['permission:edit_lead_source']);
	Route::post('update-lead-source', 'LeadSourceController@update');
	Route::post('delete-lead-source/{id}', 'LeadSourceController@destroy')->middleware(['permission:delete_lead_source']);

	// group
    Route::get('groups', 'GroupController@index')->middleware(['permission:manage_group']);
    Route::get('groups/valid', 'GroupController@validGroup');
    Route::post('groups/save', 'GroupController@store');
    Route::post('groups/edit', 'GroupController@edit')->middleware(['permission:edit_group']);
    Route::post('groups/update', 'GroupController@update');
    Route::post('groups/delete/{id}', 'GroupController@destroy')->middleware(['permission:delete_group']);


	Route::get('lead/list', 'LeadController@index')->middleware(['permission:manage_lead']);
	Route::get('create-lead', 'LeadController@create')->middleware(['permission:add_lead']);
	Route::post('save-lead', 'LeadController@store');
	Route::get('lead/edit/{id}', 'LeadController@edit')->middleware(['permission:edit_lead']);
	Route::post('update-lead', 'LeadController@update');
	Route::post('lead/delete', 'LeadController@destroy')->middleware(['permission:delete_lead']);
	Route::get('lead/view/{id}', 'LeadController@view')->middleware(['permission:edit_lead']);
	Route::get('convert-to-customer/{id}', 'LeadController@createCustomer')->middleware(['permission:add_customer']);
	Route::post('save-converted-customer', 'LeadController@storeCustomer');
	Route::get('leads-list-csv', 'LeadController@leadListCsv');
	Route::get('leads-list-pdf', 'LeadController@leadListPdf');
	Route::post('checklist/change_status', 'CheckListController@statusChange');
	Route::post('checklist/delete', 'CheckListController@destroy');
	Route::post('checklist/edit', 'CheckListController@edit');
	Route::post('checklist_edit/add', 'CheckListController@add');
	Route::post('checklist_edit/change_status', 'TaskController@checkListEditChangeStatus');
	Route::post('backup/delete/{id}', 'SettingController@destroyBackup')->middleware(['permission:delete_db_backup']);

	// Payment
	Route::get('payment/terms', 'SettingController@paymentTerm')->middleware(['permission:manage_payment_term']);
	Route::post('payment/terms/add', 'SettingController@addPaymentTerms')->middleware(['permission:add_payment_term']);
	Route::post('payment/terms/edit', 'SettingController@editPaymentTerms')->middleware(['permission:edit_payment_term']);
	Route::post('payment/terms/update', 'SettingController@updatePaymentTerms');
	Route::post('payment/terms/delete/{id}', 'SettingController@deletePaymentTerm')->middleware(['permission:delete_payment_term']);
	Route::get('payment-term-valid', 'SettingController@validPaymentTerm');
	Route::get('payment/method', 'SettingController@paymentMethod')->middleware(['permission:manage_payment_method']);
	Route::post('payment/method/edit', 'SettingController@editPaymentMethod')->middleware(['permission:edit_payment_method']);
	Route::post('payment/method/update', 'SettingController@updatePaymentMethod');
	Route::post('payment/method/settings', 'SettingController@paymentMethodSettings')->middleware(['permission:edit_payment_method']);
	Route::post('payment/method/settings/update', 'SettingController@updatePaymentMethodSettings');

	Route::get('company/setting', 'SettingController@companySetting');
	Route::post('company/setting/save', 'SettingController@companySettingSave');
	Route::post('company/image_delete', 'SettingController@deleteImage');
	Route::post('payment/bank_gateway', 'SettingController@bank_gateway');
	Route::post('company/icon_delete', 'SettingController@deleteIcon');

	// Mail template
	Route::get('customer-invoice-temp/{id}', 'MailTemplateController@customerInvTemp');
	Route::post('customer-invoice-temp/{id}', 'MailTemplateController@update');

    Route::match(array('GET', 'POST'),'customer-sms-temp/{id}', 'SmsTemplateController@index')->middleware(['permission:manage_sms_template']);
	// Payment
	Route::get('payment/list', 'PaymentController@index')->middleware(['permission:manage_payment|own_payment']);
	Route::post('payment/delete', 'PaymentController@delete')->middleware(['permission:delete_payment']);
	Route::get('payment/view-receipt/{id}', 'PaymentController@viewReceipt');
	Route::get('payment/create-receipt/{id}', 'PaymentController@createReceiptPdf');
	Route::post('payment/email-payment-info', 'PaymentController@sendPaymentInformationByEmail');
	Route::get('payment/pay-all/{orderid}', 'PaymentController@payAllAmount');
	Route::get('payment/customer-pdf', 'PaymentController@customerPaymentPdf');
	Route::get('payment/customer-csv', 'PaymentController@customerPaymentCsv');
	Route::get('payment/edit/{id}', 'PaymentController@editPayment')->middleware(['permission:edit_payment']);
	Route::post('payment/update-payment', 'PaymentController@updatePayment');
	Route::post('payment/customer_payment/verification', 'PaymentController@paymentVerification');
	Route::post('payment/customer_payment/update', 'PaymentController@updatePaymentStatus');
   	Route::get('purchase_payment/list', 'PaymentController@purchPaymentFiltering')->middleware(['permission:manage_purch_payment|own_purchase_payment']);
   	Route::get('purchase_payment/edit/{id}', 'PaymentController@purchPaymentEdit')->middleware(['permission:edit_purch_payment']);
    Route::post('purchase_payment/update', 'PaymentController@purchPaymentUpdate');
    Route::post('purchase_payment/delete', 'PaymentController@purchPaymentdelete')->middleware(['permission:delete_purch_payment']);
    Route::get('purchase_payment/view_receipt/{id}', 'PaymentController@purchaseViewReceipt');
    Route::get('purchase_payment/create-receipt-pdf/{id}', 'PaymentController@purchaseReceiptPdf');
    Route::get('purchase_payment/print-receipt/{id}', 'PaymentController@purchasePaymentPrint');
    Route::post('purchase_payment/email-payment-info', 'PaymentController@emailPurchPaymentInformation');
	Route::post('payment/save', 'PaymentController@createPayment');
    Route::get('payment/supplier-pdf', 'PaymentController@supplierPaymentPdf');
    Route::get('payment/supplier-csv', 'PaymentController@supplierPaymentCsv');
    Route::post('get-currency-exchange-rate', 'PaymentController@getExchangeRate');

    // Stock transfer
	Route::get('stock_transfer/list', 'StockTransferController@index')->middleware(['permission:manage_stock_transfer|own_stock_transfer']);
	Route::get('stock_transfer/create', 'StockTransferController@create')->middleware(['permission:add_stock_transfer']);
	Route::get('stock_transfer/edit/{id}', 'StockTransferController@edit')->middleware(['permission:edit_stock_transfer']);
	Route::post('stock_transfer/update', 'StockTransferController@update');
	Route::post('stock_transfer/search', 'StockTransferController@itemSearch');
	Route::post('stock_transfer/get-destination', 'StockTransferController@destinationList');
	Route::post('stock_transfer/check-item-qty', 'StockTransferController@checkItemQty');
	Route::post('stock_transfer/check-old-qty', 'StockTransferController@checkOldItemQty');
	Route::post('stock_transfer/save', 'StockTransferController@store');
	Route::get('stock_transfer/view-details/{id}', 'StockTransferController@details')->middleware(['permission:manage_stock_transfer|own_stock_transfer']);
	Route::post('stock_transfer/delete', 'StockTransferController@destroy');
	Route::get('stock/transfer-csv', 'StockTransferController@stock_transfer_csv')->middleware(['permission:manage_stock_transfer|own_stock_transfer']);
	Route::get('stock/transfer-pdf', 'StockTransferController@stock_transfer_pdf')->middleware(['permission:manage_stock_transfer|own_stock_transfer']);

	// Stock adjustment
	Route::get('adjustment/list', 'StockAdjustmentController@index')->middleware(['permission:manage_stock_adjustment|own_stock_adjustment']);
	Route::get('adjustment/create', 'StockAdjustmentController@create')->middleware(['permission:add_stock_adjustment']);
	Route::post('adjustment/search', 'StockAdjustmentController@itemSearch');
	Route::post('stock_adjustment/check-item-qty', 'StockAdjustmentController@checkItemQty');
	Route::post('adjustment/save', 'StockAdjustmentController@store');
	Route::get('adjustment/view-details/{id}', 'StockAdjustmentController@details')->middleware(['permission:manage_stock_adjustment|own_stock_adjustment']);
	Route::post('adjustment/delete/', 'StockAdjustmentController@destroy')->middleware(['permission:delete_stock_adjustment']);
	Route::get('adjustment/edit/{id}', 'StockAdjustmentController@edit')->middleware(['permission:edit_stock_adjustment']);
	Route::post('adjustment/update', 'StockAdjustmentController@update');
	Route::get('stock/adjustment-csv', 'StockAdjustmentController@stock_adjustment_csv')->middleware(['permission:manage_stock_adjustment|own_stock_adjustment']);
	Route::get('stock/adjustment-pdf', 'StockAdjustmentController@stock_adjustment_pdf')->middleware(['permission:manage_stock_adjustment|own_stock_adjustment']);
	Route::post('stock_adjustment/check-old-qty', 'StockAdjustmentController@checkOldItemQty');

	// Report
	Route::get('report/inventory-stock-on-hand', 'ReportController@inventoryStockOnHand')->middleware(['permission:manage_stock_on_hand']);
	Route::get('report/inventory-stock-on-hand-pdf', 'ReportController@inventoryStockOnHandPdf');
	Route::get('report/inventory-stock-on-hand-csv', 'ReportController@inventoryStockOnHandCsv');
	Route::get('report/sales-report', 'ReportController@salesReport')->middleware(['permission:manage_sale_report']);
	Route::get('report/sales-report-pdf', 'ReportController@salesReportPdf');
	Route::get('report/sales-report-csv', 'ReportController@salesReportCsv');
	Route::get('report/sales-report-by-date', 'ReportController@salesReportByDate');
	Route::get('report/sale_report_filterwise', 'ReportController@salesReportByDate');
	Route::get('report/sales-report-by-date-csv/{date}', 'ReportController@salesReportByDateCsv');
	Route::post('report/sales-report/get-customer', 'ReportController@getCustomerByCurrency');

	// Purchase report
    Route::get('report/purchase-report', 'ReportController@purchaseReport')->middleware(['permission:manage_purchase_report']);
    Route::get('report/purchase-report-pdf', 'ReportController@purchaseReportPdf');
    Route::get('report/purchase-report-datewise/{time}', 'ReportController@purchaseReportDateWise');
    Route::get('report/purchase-report-csv', 'ReportController@purchaseReportCsv');
    Route::get('report/purchase-year-list', 'ReportController@purchaseYearList');
    Route::get('report/puchases-report-by-date-pdf', 'ReportController@purchReportByDatePdf');
    Route::get('report/puchases-report-by-date-csv', 'ReportController@purchReportByDateCsv');

    //Leads report
    Route::get('report/leads-report', 'ReportController@leadsReport')->middleware(['permission:manage_lead']);

    // Bank account
    Route::get('bank/list', 'BankController@index')->middleware(['permission:manage_bank_account']);
    Route::get('bank/add-account', 'BankController@addAccount')->middleware(['permission:add_bank_account']);
    Route::get('bank/account_validation', 'BankController@bankAccountValidation');
    Route::post('bank/save-account', 'BankController@storeAccount');
    Route::get('bank/edit-account/{tab}/{id}', 'BankController@editAccount')->middleware(['permission:edit_bank_account']);
    Route::post('bank/update-account', 'BankController@updateAccount');
    Route::get('bank/account-type', 'BankController@showAccountType');
    Route::get('acc-type-valid', 'BankController@validAccountTypeName');
    Route::post('save-acc-type', 'BankController@saveAccountType');
    Route::post('edit-acc-type', 'BankController@editAccountType');
    Route::post('update-acc-type', 'BankController@updateAccountType');
    Route::post('delete-account-type', 'BankController@destroyAccountType');
    Route::post('bank/delete/{id}', 'BankController@destroy')->middleware(['permission:delete_bank_account']);
    Route::get('all_bank_report_pdf', 'BankController@allBankStatementPdf');
    Route::get('all_bank_report_csv', 'BankController@allBankStatementCsv');
    Route::get('bank_statement_pdf', 'BankController@statementPdf');
    Route::get('bank_statement_csv', 'BankController@statementCsv');

    // Income expense category
    Route::get('income-expense-category/list', 'IncomeExpenseCategoryController@index')->middleware(['permission:manage_income_expense_category']);
    Route::post('income-expense-category/save', 'IncomeExpenseCategoryController@store');
    Route::post('income-expense-category/edit', 'IncomeExpenseCategoryController@edit')->middleware(['permission:edit_income_expense_category']);
    Route::post('income-expense-category/update', 'IncomeExpenseCategoryController@update');
    Route::post('income-expense-category/delete/{id}', 'IncomeExpenseCategoryController@destroy')->middleware(['permission:delete_income_expense_category']);
    Route::get('income-expense-category-valid', 'IncomeExpenseCategoryController@validCategoryName');
    Route::get('edit-income-expense-category-valid', 'IncomeExpenseCategoryController@editValidCategoryName');

    // Deposit
    Route::get('deposit/list', 'DepositController@index')->middleware(['permission:manage_deposit|own_deposit']);
    Route::get('deposit/add-deposit', 'DepositController@addDeposit')->middleware(['permission:add_deposit']);
    Route::post('deposit/save', 'DepositController@store');
    Route::get('deposit/edit-deposit/{id}', 'DepositController@editDeposit')->middleware(['permission:edit_deposit']);
    Route::post('deposit/update', 'DepositController@updateDeposit');
    Route::post('deposit/delete/{id}', 'DepositController@destroy')->middleware(['permission:delete_deposit']);
    Route::get('bank_account/deposit_pdf', 'DepositController@depositPdf');
    Route::get('bank_account/deposit_csv', 'DepositController@depositCsv');
    Route::post('get/balance', 'DepositController@getBalance');
    Route::post('deposit/file/delete', 'DepositController@deleteFile');

    // Expense
    Route::get('expense/list', 'ExpenseController@index')->middleware(['permission:manage_expense|own_expense']);
    Route::get('expense/add-expense', 'ExpenseController@addExpense')->middleware(['permission:add_expense']);
    Route::post('expense/save', 'ExpenseController@store');
    Route::get('expense/edit-expense/{id}', 'ExpenseController@editExpense')->middleware(['permission:edit_expense']);
    Route::post('expense/update', 'ExpenseController@updateExpense');
    Route::post('expense/delete/{id}', 'ExpenseController@destroy')->middleware(['permission:delete_expense']);
	Route::get('expense_list_pdf', 'ExpenseController@expenseListPdf');
	Route::get('expense_list_csv', 'ExpenseController@expenseListCsv');

	// Balance transfer
	Route::get('transfer/list', 'BalanceTransferController@index')->middleware(['permission:manage_balance_transfer|own_balance_transfer']);
	Route::get('transfer/create', 'BalanceTransferController@addTransfer')->middleware(['permission:add_balance_transfer']);
	Route::post('transfer/save', 'BalanceTransferController@store');
	Route::post('transfer/check-balance', 'BalanceTransferController@checkBalance');
	Route::post('transfer/delete/{id}', 'BalanceTransferController@destroy')->middleware(['permission:delete_balance_transfer']);
	Route::post('transfer-dependent', 'BalanceTransferController@selectDestination');
	Route::get('transfer/details/{id}', 'BalanceTransferController@details');
	Route::get('bank_account/transfer_pdf', 'BalanceTransferController@accTransferPdf');
	Route::get('bank_account/transfer_csv', 'BalanceTransferController@bankAccTransferCsv');
	Route::post('bank_account/source-destination/accounts', 'BalanceTransferController@accountCheck');
	Route::post('get-exchange-rate', 'BalanceTransferController@getExchangeRate');


	// Transaction
	Route::get('transaction/list', 'TransactionController@index')->middleware(['permission:manage_transaction|own_transaction']);
	Route::get('transaction/expense-report', 'TransactionController@expenseReport')->middleware(['permission:manage_expense_report']);
	Route::post('transaction/delete/{id}', 'TransactionController@destroy');
	Route::get('transaction/edit/{id}', 'TransactionController@edit');
	Route::get('transaction/details/{id}', 'TransactionController@details');
	Route::get('transaction/update', 'TransactionController@update');
	Route::get('transaction/income-report', 'ReportController@getIncomeStat')->middleware(['permission:manage_income_report']);
	Route::get('transaction/income-vs-expense', 'TransactionController@incomeVsExpense')->middleware(['permission:manage_income_vs_expense']);
    Route::get('transaction/pdf', 'TransactionController@transactionListPdf');
    Route::get('transaction/csv', 'TransactionController@transactionListCsv');
    Route::post('get-category', 'TransactionController@selectCategory');

	// Barcode generation
	Route::match(['get', 'post'], 'barcode/create', 'BarcodeController@index')->middleware(['permission:manage_barcode']);
	Route::post('barcode/search', 'BarcodeController@search');

	// Ticket
    Route::get('ticket/list', 'TicketController@index')->middleware(['permission:manage_ticket|own_ticket']);
    Route::get('ticket/add', 'TicketController@create')->middleware(['permission:add_ticket']);
    Route::get('ticket/check-inhouse-project', 'TicketController@checkInhouse');
    Route::post('ticket/store', 'TicketController@store');
    Route::get('ticket/edit/{id}', 'TicketController@edit')->middleware(['permission:edit_ticket']);
    Route::post('ticket/update', 'TicketController@update');
    Route::post('ticket/delete', 'TicketController@delete')->middleware(['permission:delete_ticket']);
    Route::post('ticket/replyStore', 'TicketController@adminReplyStore');
    Route::get('ticket/reply/{id}', 'TicketController@reply')->middleware(['permission:manage_ticket_reply']);
    Route::get('project/ticket/reply/{id}', 'TicketController@reply');
    Route::post('ticket_reply/delete', 'TicketController@replyDelete');
    Route::post('update/admin_reply', 'TicketController@updateReply');
    Route::post('ticket/save-note', 'TicketController@storeTicketNote');
    Route::post('ticket/change-status', 'TicketController@changeStatus');
    Route::get('ticket/priority-status', 'TicketController@changePriority');
    Route::get('ticket_pdf', 'TicketController@ticket_pdf');
    Route::get('ticket_csv', 'TicketController@ticket_csv');
    Route::get('files/ticket/downloads/{id}', 'TicketController@downloadAttachment');
    Route::post('ticket/change-project', 'TicketController@changeProject');

    // Canned
    Route::get('canned/messages', 'CannedController@messages')->middleware(['permission:manage_canned_message']);
    Route::post('canned/messages/save', 'CannedController@storeMessage')->middleware(['permission:add_canned_message']);
    Route::post('canned/search/{type}', 'CannedController@search');
    Route::post('canned/messages/edit', 'CannedController@editMessage')->middleware(['permission:edit_canned_message']);
    Route::post('canned/messages/update', 'CannedController@updateMessage')->middleware(['permission:edit_canned_message']);
    Route::post('canned/messages/delete/{id}', 'CannedController@destroyMessage')->middleware(['permission:delete_canned_message']);
    Route::get('canned/links', 'CannedController@links')->middleware(['permission:manage_canned_link']);
    Route::post('canned/links/save', 'CannedController@storeLink')->middleware(['permission:add_canned_link']);
    Route::post('canned/links/edit', 'CannedController@editLink')->middleware(['permission:edit_canned_link']);
    Route::post('canned/links/update', 'CannedController@updateLink')->middleware(['permission:edit_canned_link']);
    Route::post('canned/links/delete/{id}', 'CannedController@destroyLink')->middleware(['permission:delete_canned_link']);

    // Ticket assignee member
    Route::post('ticket/get-status', 'TicketController@getAllStatus');
    Route::post('ticket/change-assignee', 'TicketController@changeAssignee');

    // Project
    Route::get('project/list', 'ProjectController@index')->middleware(['permission:manage_project|own_project']);
    Route::get('project/add', 'ProjectController@create')->middleware(['permission:add_project']);
    Route::post('project/store', 'ProjectController@store');
    Route::get('project/details/{id}', 'ProjectController@details')->middleware(['permission:manage_project|own_project']);
    Route::get('project/edit/{id}', 'ProjectController@editProject')->middleware(['permission:edit_project']);
    Route::post('project/update', 'ProjectController@updateProject');
    Route::post('project/delete', 'ProjectController@projectDestroy')->middleware(['permission:delete_project']);
    Route::post('delete/project_member', 'ProjectController@removeProjectmember');
    Route::post('update/project_member', 'ProjectController@updateProjectmember');
    Route::get('project_pdf', 'ProjectController@project_pdf');
    Route::get('project_csv', 'ProjectController@project_csv');
    Route::get('project/timersheet/pdf', 'ProjectController@projectTimeSheetPdf');
    Route::get('project/timersheet/csv', 'ProjectController@projectTimeSheetCsv');

    // Project tasks
    Route::get('project/tasks/{id}', 'TaskController@getAllTask')->middleware(['permission:manage_project|own_project']);
    Route::get('project/task/add/{id}', 'ProjectController@addTask')->middleware(['permission:manage_project|own_project']);
    Route::post('project/task/get-milestone', 'MilestoneController@getMilestone');
    Route::post('project/task/store', 'ProjectController@taskStore');
    Route::get('project/task/edit/{id}', 'ProjectController@taskEdit');
    Route::post('project/task/update', 'ProjectController@taskUpdate');
    Route::get('project/task/view', 'TaskController@taskView');
    Route::post('project/task/change-status', 'TaskController@changeStatus');
    Route::get('project/task/get-priority', 'TaskController@getAllPriority');
    Route::post('project/task/change-priority', 'TaskController@changePriority');
    Route::post('project/task/update-description', 'TaskController@updateDescription');
    Route::get('project/task/get-status', 'TaskController@getAllStatus');
    Route::post('project/task/store-comment', 'CommentController@store');
    Route::post('project/task/update-comment', 'CommentController@update');
    Route::post('project/task/delete-comment', 'CommentController@delete');
    Route::get('project/task/get_all_assignee', 'TaskController@getAllAssignee');
    Route::get('project/task/get_all_user', 'TaskController@getAllUser');
    Route::post('project/task/get_rest_assignee', 'TaskController@getRestAssignee');
    Route::post('project/task/assign_assignee', 'TaskController@assignMember');
    Route::post('project/task/delete-assignee', 'TaskController@deleteAssignee');
    Route::post('project/task/store-tag', 'TagController@store');
    Route::post('project/task/delete-tag', 'TagController@delete');
    Route::get('project/task/get-tag', 'TagController@getAll');
    Route::get('project/tasks_pdf', 'TaskController@tasks_pdf');
    Route::get('project/tasks_csv', 'TaskController@tasks_csv');
    Route::get('project/tasks/timesheet/{id}', 'TaskController@projectTimeSheet')->middleware(['permission:manage_project|own_project']);
    Route::post('project/task/timer/delete/{id}', 'TaskController@deleteProjectTimer');
    Route::get('projectTaskPdf', 'TaskController@projectTaskPdf');
    Route::get('projectTaskCsv', 'TaskController@projectTaskCsv');

    // Task timer
    Route::post('task_timer/check', 'TaskTimerController@checkTimer');
    Route::post('start_task/timer', 'TaskTimerController@start');
    Route::post('end_task/timer', 'TaskTimerController@end');
    Route::post('get_task/timer', 'TaskTimerController@get');
    Route::post('task/timer/delete', 'TaskTimerController@delete');
    Route::post('manual_time/store', 'TaskTimerController@storeManualTime');
    Route::post('task/start-date/edit', 'TaskController@editStartDate');
    Route::post('task/due-date/edit', 'TaskController@editDueDate');

    // Timesheet
    Route::get('time-sheet/list', 'TimesheetController@index')->middleware(['permission:manage_timesheet|own_timesheet']);
    Route::get('timesheet-list-csv', 'TimesheetController@timesheetListCsv')->middleware(['permission:manage_timesheet|own_timesheet']);
	Route::get('timesheet-list-pdf', 'TimesheetController@timesheetListPdf')->middleware(['permission:manage_timesheet|own_timesheet']);
	Route::post('timesheet/delete/{id}', 'TimesheetController@deleteTimesheet');

    // Task file upload
    Route::post('task_file/store', 'TaskController@uploadTaskFile');
    Route::post('task_file/delete', 'TaskController@deleteTaskFile');

    // Project milestones
    Route::get('project/milestones/pdf', 'MilestoneController@projectMileStonePdf');
    Route::get('project/milestones/csv', 'MilestoneController@projectMileStoneCsv');
    Route::get('project/milestone/add/{id}', 'MilestoneController@add')->middleware(['permission:add_milestone', 'permission:manage_project|own_project']);
    Route::post('project/milestone/store', 'MilestoneController@store');
    Route::get('project/milestones/edit/{id}', 'MilestoneController@edit')->middleware(['permission:edit_milestone', 'permission:manage_project|own_project']);
    Route::post('project/milestones/update', 'MilestoneController@update');
    Route::get('projects/milestones/view/{project_id}/{id}', 'MilestoneController@view');
    Route::post('project/milestone/delete', 'MilestoneController@delete')->middleware(['permission:delete_milestone', 'permission:manage_project|own_project']);
    Route::get('project/milestones/{id}', 'MilestoneController@index')->middleware(['permission:manage_milestone', 'permission:manage_project|own_project']);

    // Project files
    Route::post('project/files/store', 'FilesController@store');
    Route::post('project/files/delete', 'FilesController@delete')->middleware(['permission:delete_project_file', 'permission:manage_project|own_project']);
    Route::get('project/files/{id}', 'FilesController@projectFiles')->middleware(['permission:manage_project|own_project']);
    Route::post('file/upload', 'FilesController@uploadEventAttachments');
	Route::post('file/remove', 'FilesController@deleteEventAttachment');
	Route::get('is-valid-file-size', 'FilesController@isValidFileSize');

    // Project ticket
    Route::get('project/tickets/{id}', 'TicketController@projectTicketList')->middleware(['permission:manage_project|own_project']);

    // Project notes
    Route::get('project/notes/edit/{id}', 'NoteController@edit')->middleware(['permission:edit_project_note', 'permission:manage_project|own_project']);
    Route::get('project/notes/{id}', 'NoteController@index')->middleware(['permission:add_project_note', 'permission:manage_project|own_project']);
    Route::post('project/note/store', 'NoteController@store');
    Route::post('project/note/update', 'NoteController@update');
    Route::post('project/note/delete', 'NoteController@destroy')->middleware(['permission:delete_project_note', 'permission:manage_project|own_project']);;

    // Customer notes
    Route::get('customer/notes/{id}', 'NoteController@customerNotes');
    Route::post('customer/addnote', 'NoteController@storeCustomerNotes');
    Route::get('customer/getnote', 'NoteController@getNoteData');
    Route::post('customer/updatenote', 'NoteController@updateCustomerNotes');
	Route::post('customer/note-delete', 'NoteController@deleteCustomerNote');
	Route::get('customer/notes-csv', 'NoteController@noteCsv');
	Route::get('customer/notes-pdf', 'NoteController@notePdf');

    // Project activities
    Route::get('project/activities/{id}', 'ActivityController@index')->middleware(['permission:manage_project|own_project']);

    // Project invoices
    Route::get('project/invoice/{id}', 'ProjectInvoiceController@index')->middleware(['permission:manage_project|own_project']);
    Route::get('project_invoice_csv', 'ProjectInvoiceController@project_invoice_csv');
    Route::get('project_invoice_pdf', 'ProjectInvoiceController@project_invoice_pdf');

    // Calendar
    Route::match(['GET', 'POST'], 'calendar', 'CalendarController@index')->middleware(['permission:manage_calendar']);
    Route::post('calendar/event-store', 'CalendarController@storeEvent');
    Route::post('calendar/event-delete', 'CalendarController@deleteEvent');

    // Task
    Route::get('task/list', 'TaskController@index')->middleware(['permission:manage_task|own_task']);
    Route::get('task/add', 'TaskController@addTask')->middleware(['permission:add_task']);
    Route::post('task/store', 'TaskController@taskStore');
    Route::get('task/edit/{id}', 'TaskController@taskEdit')->middleware(['permission:edit_task']);
    Route::get('task/details/{id}', 'TaskController@taskDetails');
    Route::post('task/update', 'TaskController@taskUpdate');
    Route::post('task/delete', 'TaskController@taskDelete')->middleware(['permission:delete_task']);
    Route::post('task/file-upload', 'TaskController@uploadEventAttachments');
	Route::post('task/file-remove', 'TaskController@deleteEventAttachment');
	Route::post('task/update/description', 'TaskController@updateDescription');
	Route::get('task/files', 'TaskController@allFiles');
	Route::post('task/related-search', 'TaskController@relatedSearch');
	Route::get('task/v/{id}', 'TaskController@index')->middleware(['permission:own_task|manage_task']);
    Route::post('task/checklist', 'TaskController@cleanInput');
    // Point of sale
    Route::get('pos', 'PointOfSaleController@index')->middleware(['permission:manage_pos']);
    Route::get('pos/add-customer', 'PointOfSaleController@addCustomer');
    Route::get('pos/add-note', 'PointOfSaleController@addNote');
    Route::get('pos/settings', 'PointOfSaleController@settings');
    Route::get('pos/add-shipping', 'PointOfSaleController@addShipping');
    Route::get('pos/add-discount', 'PointOfSaleController@addDiscount');
    Route::get('pos/order-onhold', 'PointOfSaleController@orderOnHold');
    Route::get('pos/order-payment', 'PointOfSaleController@orderPayment');
    Route::get('pos/getHold-items', 'PointOfSaleController@getHoldItems');
    Route::get('pos/set-location', 'PointOfSaleController@getLocation');
    Route::post('pos/set-location', 'PointOfSaleController@setLocation');
    Route::post('pos/payment', 'PointOfSaleController@payment');
    Route::post('pos/hold-order-payment', 'PointOfSaleController@holdOrderPayment');
    Route::post('pos/hold', 'PointOfSaleController@orderHold');
    Route::post('pos/getHoldOrderDetails', 'PointOfSaleController@getHoldOrderDetails');
    Route::post('pos/hold-item-search', 'PointOfSaleController@holdItemSearch');
    Route::post('pos/delete-hold-item', 'PointOfSaleController@destroyHoldItem');
    Route::post('pos-item/search', 'PointOfSaleController@posItemSearch');
    Route::post('pos-item-category/search', 'PointOfSaleController@posItemCategorySearch');
    Route::post('pos/customer-branch', 'PointOfSaleController@customerBranch');
    Route::get('pos/print-receipt/{id}', 'PointOfSaleController@printReceipt');

    // knowledge base
    Route::get('knowledge-base', 'KnowledgeBaseController@index')->middleware(['permission:manage_knowledge_base']);
    Route::get('knowledge-base/create', 'KnowledgeBaseController@create')->middleware(['permission:add_knowledge_base']);
    Route::post('knowledge-base/save', 'KnowledgeBaseController@store')->middleware(['permission:add_knowledge_base']);
    Route::get('knowledge-base/edit/{id}', 'KnowledgeBaseController@edit')->middleware(['permission:edit_knowledge_base']);
    Route::post('knowledge-base/update', 'KnowledgeBaseController@update')->middleware(['permission:edit_knowledge_base']);
    Route::post('knowledge-base/delete', 'KnowledgeBaseController@destroy')->middleware(['permission:delete_knowledge_base']);
    Route::get('knowledge-base/view/{slug}', 'KnowledgeBaseController@view');
    Route::get('knowledge-base/csv', 'KnowledgeBaseController@knowledgeListCsv');
    Route::get('knowledge-base/pdf', 'KnowledgeBaseController@knowledgeListPdf');

});

Route::group(['middleware' => ['isLoggedIn']], function() {
	Route::get('files/download/{id}', 'FilesController@downloadAttachment');
	Route::post('change-lang', 'DashboardController@switchLanguage');
});

Route::post('paypal-payment','PaymentGatewayController@payWithpaypal')->name('paywithpaypal');
Route::get('capture-order','PaymentGatewayController@captureOrder');


Route::post('stripe', 'PaymentGatewayController@stripePost')->name('stripe.post');
Route::post('bank/payment', 'PaymentGatewayController@bankPayment');
