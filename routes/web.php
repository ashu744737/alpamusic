<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
});

Route::group(['middleware' => ['set_global_config']], function () {

	Auth::routes(['register' => false]);
	Route::get('/user/verify/{token}', 'RegistrationController@userVerify');
	Route::get('/register/client', 'RegistrationController@showclientform')->name('clientregister');
	Route::get('/register/investigator', 'RegistrationController@showinvestigatorform')->name('investigatorregister');
	Route::get('/register/deliveryboy', 'RegistrationController@showdeliveryboyform')->name('deliveryboyregister');
	Route::post('/clientstore', 'RegistrationController@storeClient')->name('register.storeClient');
	Route::post('/investigatorstore', 'RegistrationController@storeInvestigator')->name('register.storeInvestigator');
	Route::post('/deliveryboystore', 'RegistrationController@storeDeliveryboy')->name('register.storeDeliveryboy');
	Route::get('client/notify-unpaid-invoice', 'InvoiceController@notifyUnpaidInvoice')->name('notify-unpaid-invoice');
	Route::get('investigation/extreme-delay', 'InvestigationController@extremeDelay')->name('extreme-delay');
	
	Route::get('/', function () {
		return redirect('login');
	});

	Route::get('/lang/{lang}', function ($lang) {
		App::setlocale($lang);
		session()->put('locale', $lang);
		return back();
	})->name('set_lang');

	//set permissions after login user's
	Route::group(['middleware' => ['set_user_permission', 'auth']], function () {

		Route::get('/home', function () {
			return redirect('dashboard/index');
		})->name('home');

		Route::get('/dashboard/index', 'DashboardController@index');
		Route::get('/dashboard/clients', 'DashboardController@clients')->name('clients');
		Route::get('/dashboard/chart', 'DashboardController@refreshChart')->name('chart.refresh');
		Route::post('/dashboard/chart', 'DashboardController@refreshChart')->name('chart.refresh');

		/* Investigators routes */
		Route::get('investigators', 'InvestigatorsController@index')->name('investigators')->middleware('perm:investigator_show|investigator_create|investigator_edit,true');
		Route::get('investigators/create', 'InvestigatorsController@create')->name('investigator.create')->middleware('perm:investigator_create');
		Route::post('investigators', 'InvestigatorsController@store')->name('investigator.store');
		Route::get('investigators/{userId}/edit', 'InvestigatorsController@edit')->name('investigator.edit')->middleware('perm:investigator_edit');
		Route::put('investigators/{userId}', 'InvestigatorsController@update')->name('investigator.update');
		Route::delete('/investigators/{userId}', 'InvestigatorsController@destroy')->name('investigator.delete')->middleware('perm:investigator_delete');
		Route::get('investigators/{userId}', 'InvestigatorsController@show')->name('investigator.detail')->middleware('perm:investigator_show');

		/* Titles routes */
		Route::get('titles', 'TitleController@index')->name('titles');
		Route::get('titles/create', 'TitleController@create')->name('titles.create');
		Route::post('titles', 'TitleController@store')->name('titles.store');
		Route::get('datatable/titles', 'TitleController@titleList')->name('title-list');
		Route::delete('/title/{id}', 'TitleController@destroy')->name('title.delete');
		Route::get('/title/change_status/{id}', 'TitleController@change_status')->name('title.change_status');
		Route::post('deletetitles', 'TitleController@deleteMultiple')->name('multidelete-titles');
		Route::get('title/{titleId}', 'TitleController@show')->name('title.detail');
		Route::get('titles/{titleId}/edit', 'TitleController@edit')->name('title.edit');
		Route::put('titles/{titleId}', 'TitleController@update')->name('title.update');

		// Route::get('investigators/{investigatorId}/view-investigations', 'TitleController@viewInvestigations')->name('investigator.view_investigations')->middleware('perm:investigator_show');

        // Route::post('investigators/{userId}/approve', 'InvestigatorsController@approveClient')->name('investigator.approve')->middleware('perm:investigator_edit');


		
		
		// titles routes

		Route::get('contributors', 'ContributorsController@index')->name('contributors');
		

		Route::group(['prefix' => 'investigator-invoice', 'middleware' => 'perm:invoice_list_investigator,true'], function () {
			Route::get('/', 'InvoiceController@investigatorIndex')->name('investigator.invoices.index');
			Route::get('datatable/invoice-list', 'InvoiceController@invoiceListInvestigator')->name('investigator.invoices.invoice-list');
			Route::get('/{id}', 'InvoiceController@showInvestigatorInvoice')->name('investigator.invoices.show');
			Route::post('investigator-invoice-pay', 'InvoiceController@investigatorInvoicePay')->name('investigator-invoice-pay');
			Route::post('/investigator-bulk-invoice-pay', 'InvoiceController@showInvestigatorInvoices')->name('investigator-bulk-invoice-pay');
		});

        Route::get('investigators/{userId}/approve', 'InvestigatorsController@showApproveForm')->name('investigator.showApproveForm')->middleware('perm:investigator_edit');
        Route::post('investigators/{userId}/approve', 'InvestigatorsController@approveClient')->name('investigator.approve')->middleware('perm:investigator_edit');

		Route::post('deleteInvestigators', 'InvestigatorsController@deleteMultiple')->name('multidelete-investigator')->middleware('perm:investigator_delete');
		Route::post('investigators/change-status', 'InvestigatorsController@changeStatus')->name('investigators.change-status')->middleware('perm:investigator_edit');
		Route::get('investigators/search-investigations/{investigatorId}/{search}', 'InvestigatorsController@searchInvestigations')->name('search-investigations');
		Route::post('investigators/assign-investigation', 'InvestigatorsController@assignInvestigation')->name('assign.investigation');

		Route::get('investigators/{investigatorId}/view-investigations', 'InvestigatorsController@viewInvestigations')->name('investigator.view_investigations')->middleware('perm:investigator_show');

		/* Clients routes */
		Route::group(['prefix' => 'clients'], function () {
			Route::get('/client-autocomplete', 'ClientsController@clientAutocomplete')->name('client.autocomplete');

			Route::get('/', 'ClientsController@index')->name('clients');
			Route::get('/create', 'ClientsController@create')->name('client.create')->middleware('perm:client_create');
			Route::post('/', 'ClientsController@store')->name('client.store');
			Route::get('/{userId}/edit', 'ClientsController@edit')->name('client.edit')->middleware('perm:client_edit');
			Route::put('/{userId}', 'ClientsController@update')->name('client.update');
			Route::delete('/{userId}', 'ClientsController@destroy')->name('client.delete')->middleware('perm:client_delete');
			Route::post('/change-status', 'ClientsController@changeStatus')->name('clients.change-status')->middleware('perm:client_edit');
			Route::get('/{userId}', 'ClientsController@show')->name('client.detail')->middleware('perm:client_show');

			Route::get('/{userId}/approve', 'ClientsController@showApproveForm')->name('client.showApproveForm')->middleware('perm:client_edit');
			Route::post('/{userId}/approve', 'ClientsController@approveClient')->name('client.approve')->middleware('perm:client_edit');
			Route::get('/{userId}/getProducts', 'ClientsController@getProducts')->name('client.products');
			Route::get('/{userId}/getCredit', 'ClientsController@getCredit')->name('client.credit');
			Route::get('/customer/data', 'ClientsController@customerDataAppends')->name('client.customerdata');
			Route::post('/customer/data', 'ClientsController@customerDataAppends')->name('client.customerdata');
			Route::post('/customer/data/update', 'ClientsController@customerDataUpdate')->name('client.customerdata.update');
		});
		Route::post('deleteClients', 'ClientsController@deleteMultiple')->name('multidelete-clients')->middleware('perm:client_delete');


		/* DeliveryBoy routes */
		Route::group(['middleware' => ['auth']], function () {
			Route::group(['prefix' => 'deliveryboys', 'middleware' => 'perm:deliveryboy_show|deliveryboy_create|deliveryboy_edit,true'], function () {
				Route::get('/', 'DeliveryBoysController@index')->name('deliveryboys');
				Route::get('/create', 'DeliveryBoysController@create')->name('deliveryboy.create')->middleware('perm:deliveryboy_create');
				Route::post('/', 'DeliveryBoysController@store')->name('deliveryboy.store');
				Route::get('/{userId}/edit', 'DeliveryBoysController@edit')->name('deliveryboy.edit')->middleware('perm:deliveryboy_edit');
				Route::put('/{userId}', 'DeliveryBoysController@update')->name('deliveryboy.update');
				Route::delete('/{userId}', 'DeliveryBoysController@destroy')->name('deliveryboy.delete')->middleware('perm:deliveryboy_delete');
				Route::post('/change-status', 'DeliveryBoysController@changeStatus')->name('deliveryboy.change-status')->middleware('perm:deliveryboy_edit');
				Route::get('/{userId}', 'DeliveryBoysController@show')->name('deliveryboy.detail')->middleware('perm:deliveryboy_show');

                Route::get('/{userId}/approve', 'DeliveryBoysController@showApproveForm')->name('deliveryboy.showApproveForm')->middleware('perm:deliveryboy_edit');
                Route::post('/{userId}/approve', 'DeliveryBoysController@approveClient')->name('deliveryboy.approve')->middleware('perm:deliveryboy_edit');

            });
		});

		Route::group(['middleware' => ['auth']], function () {
			Route::group(['prefix' => 'deliveryboy/invoice', 'middleware' => 'perm:invoice_list_deliveryboy,true'], function () {
				Route::get('/', 'InvoiceController@deliveryBoyIndex')->name('deliveryboy.invoices.index');
				Route::get('datatable/invoice-list', 'InvoiceController@invoiceListDeliveryboy')->name('deliveryboy.invoices.invoice-list');
				Route::get('/{id}', 'InvoiceController@showDeliveryboyInvoice')->name('deliveryboy.invoices.show');
				Route::post('deliveryboy-invoice-pay', 'InvoiceController@deliveryboyInvoicePay')->name('deliveryboy-invoice-pay');
				Route::post('/deliveryboy-bulk-invoice-pay', 'InvoiceController@showDeliveryboyInvoices')->name('deliveryboy-bulk-invoice-pay');
			});
		});

		Route::post('deleteDeliveryboys', 'DeliveryBoysController@deleteMultiple')->name('multidelete-deliveryboy')->middleware('perm:deliveryboy_delete');

		/* Datatable routes*/
		Route::get('datatable/clients', 'ClientsController@clientList')->name('client-list');
		Route::get('datatable/investigators', 'InvestigatorsController@investigatorList')->name('investigator-list');
		Route::get('datatable/deliveryboys', 'DeliveryBoysController@deliveryBoyList')->name('deliveryboy-list');
		Route::get('datatable/investigations/{status?}', 'InvestigationController@investigationList')->name('investigation-list');
		Route::get('datatable/investigator-investigations/{status?}', 'InvestigationController@investigatorInvestigationList')->name('investigator-investigation-list');
		Route::get('datatable/deliveryboy-investigations/{status?}', 'InvestigationController@deliveryboyInvestigationList')->name('deliveryboy-investigation-list');
		Route::get('datatable/client-investigations/{status?}', 'InvestigationController@clientInvestigationList')->name('client-investigation-list');
		

		
		/* Contact routes */
		Route::group(['prefix' => 'contacts', 'middleware' => 'perm:contact_show|contact_create|contact_edit,true'], function () {
			Route::get('/', 'ContactController@index')->name('contacts');
			Route::get('/get-contacts-list', 'ContactController@getContactList')->name('get-contacts-list');
			Route::get('/create', 'ContactController@create')->name('contactsCreate')->middleware('perm:contact_create');
			Route::post('/store', 'ContactController@store')->name('contacts.store');
			Route::get('/edit/{contact_id}', 'ContactController@edit')->name('contacts.edit')->middleware('perm:contact_edit');
			Route::post('/update/{contact_id}', 'ContactController@update')->name('contacts.update');
			Route::delete('/remove/{contact_id}', 'ContactController@destroy')->name('contacts.destroy')->middleware('perm:contact_delete');
			Route::post('/delete-contacts', 'ContactController@deleteMultiple')->name('contacts.multidelete')->middleware('perm:contact_delete');
		});

		/* Subjects routes */
		Route::group(['prefix' => 'subjects'], function () {
			Route::get('/', 'SubjectController@index')->name('subjects');
			Route::get('/get-subjects-list', 'SubjectController@getSubjectsList')->name('get-subject-list');
			Route::get('/{subjectId}', 'SubjectController@show')->name('subject.detail');
			Route::post('/updatedata', 'SubjectController@updateData')->name('subject.updatedata');
			Route::get('/investigations/{subjectId}', 'SubjectController@investigationIndex')->name('subject.investigations');
			Route::get('datatable/investigations/list/{subjectId}', 'SubjectController@getSubInvList')->name('get-subject-investigation-list');
		});

		/* Internal User routes */
		Route::group(['prefix' => 'staff', 'middleware' => 'perm:staff_show|staff_create|staff_edit,true'], function () {
			Route::get('/', 'InternalUserController@index')->name('staff.index');
			Route::get('/create', 'InternalUserController@create')->name('staff.create')->middleware('perm:staff_create');
			Route::get('/get-user-list', 'InternalUserController@getUserList')->name('staff.getlist');
			Route::post('/store', 'InternalUserController@store')->name('staff.store');
			Route::get('/edit/{user_id}', 'InternalUserController@edit')->name('staff.edit')->middleware('perm:staff_edit');
			Route::post('/update/{user_id}', 'InternalUserController@update')->name('staff.update');
			Route::delete('/remove/{user_id}', 'InternalUserController@destroy')->name('staff.destroy')->middleware('perm:staff_delete');
			Route::post('/delete-users', 'InternalUserController@deleteMultiple')->name('staff.multidelete')->middleware('perm:staff_delete');
			Route::post('/update-status', 'InternalUserController@updateStatus')->name('staff.updateStatus');
		});

		/* Products routes */
		Route::group(['prefix' => 'products', 'middleware' => 'perm:product_show|product_create|product_edit,true'], function () {
			Route::get('/', 'ProductsController@index')->name('product.index');
			Route::get('/create', 'ProductsController@create')->name('product.create')->middleware('perm:product_create');
			Route::get('/get-product-list', 'ProductsController@getProductList')->name('product.getlist');
			Route::post('/store', 'ProductsController@store')->name('product.store');
			Route::get('/edit/{prod_id}', 'ProductsController@edit')->name('product.edit')->middleware('perm:product_edit');
			Route::post('/update/{prod_id}', 'ProductsController@update')->name('product.update');
			Route::delete('/remove/{prod_id}', 'ProductsController@destroy')->name('product.destroy')->middleware('perm:product_delete');
            Route::get('/{id}', 'ProductsController@show')->name('product.show');
            Route::get('/delete/{id}', 'ProductsController@delete')->name('product.delete');

            Route::post('/delete-users', 'ProductsController@deleteMultiple')->name('product.multidelete')->middleware('perm:product_delete');
		});


		/* Investigations routes */
		Route::group(['prefix' => 'investigations', 'middleware' => 'perm:investigation_show|investigation_create|investigation_edit,true'], function () {
			Route::get('/bulk-assign', 'InvestigationController@bulkAssign')->name('assign-bulk-investigation')->middleware('perm:investigation_show');
			Route::get('/{id}/search-investigators', 'InvestigationController@searchInvestigators')->middleware('perm:investigation_show');
			Route::get('/{id}/search-deliveryboys', 'InvestigationController@searchDeliveryboys')->middleware('perm:investigation_show');

			Route::get('/', 'InvestigationController@index')->name('investigations');
			Route::get('/create', 'InvestigationController@create')->name('investigation.create')->middleware('perm:investigation_create');
			Route::post('/', 'InvestigationController@store')->name('investigation.store');
			Route::get('/{id}/edit', 'InvestigationController@edit')->name('investigation.edit')->middleware('perm:investigation_edit');
			Route::put('/{id}', 'InvestigationController@update')->name('investigation.update');
			Route::delete('/{id}', 'InvestigationController@destroy')->name('investigation.delete')->middleware('perm:investigation_delete');
			Route::get('View/{id}/{iiid?}', 'InvestigationController@show')->name('investigation.show')->middleware('perm:investigation_show');
			Route::get('/invoices/{id}', 'InvestigationController@showInvoice')->name('investigation.showinvoice');
			Route::get('/invoice/{id}', 'InvestigationController@showClientInvoice')->name('investigation.showclientinvoice');
			Route::post('/client/invoice/generate', 'InvestigationController@generateInvoice')->name('investigation.generateclientinvoice');
			Route::get('/client/invoice/generate', 'InvestigationController@generateInvoice')->name('investigation.generateclientinvoice');

			Route::post('/change-status', 'InvestigationController@changeStatus')->name('investigation.change-status');
			Route::post('/bulk-change-status', 'InvestigationController@bulkChangeStatus')->name('investigation.bulk-change-status')->middleware('perm:investigation_edit');
			Route::post('/upload-doc', 'InvestigationController@uploadDocument')->name('investigation.upload-document');
			Route::post('/deleteDocument', 'InvestigationController@deleteDocument')->name('investigation.delete-document');
            Route::post('/shareDocument', 'InvestigationController@toggleDocumentShare')->name('investigation.share-document');
			Route::post('/update-doctype', 'InvestigationController@updateDocumentType')->name('investigation.update-documenttype');
			Route::post('/updateispaymentdoc', 'InvestigationController@updateIsPaymentDocument')->name('investigation.updateispaymentdoc');

			Route::get('/assign/investigator/{id}', 'InvestigationController@showSearchInvestigators')->name('investigation.show-search-investigators');
			Route::get('/assign/deliveryboy/{id}', 'InvestigationController@showSearchDeliveryboys')->name('investigation.show-search-deliveryboys');

			Route::post('/assign-investigator', 'InvestigationController@investigatorAssign')->name('investigation.assign');
			Route::post('/assign-deliveryboy', 'InvestigationController@deliveryboyAssign')->name('investigation.assign-deliveryboy');
			Route::post('/assign/investigator', 'InvestigationController@assignInvestigator')->name('assign.investigator');
			Route::post('/actiondata', 'InvestigationController@actionData')->name('investigation.actiondata');
			Route::post('/change-assignment-status', 'InvestigationController@changeAssignmentStatus')->name('investigation.change-assignment-status');
			Route::get('/duplicate/{id}', 'InvestigationController@duplicate')->name('investigation.duplicate');
			Route::get('/investigatorcost/{invnid}/{invrid}', 'InvestigationController@investigatorCost')->name('investigation.investigatorcost');
			Route::get('/deliveryboycost/{invnid}/{delboyid}', 'InvestigationController@deliveryboyCost')->name('investigation.deliveryboycost');
            Route::post('/complete-investigation', 'InvestigationController@completeInvestigation')->name('investigation.complete-investigation');
            Route::post('/complete-investigation-del', 'InvestigationController@completeInvestigationDel')->name('investigation.complete-investigation-del');
			Route::post('/action-on-report', 'InvestigationController@actionOnReport')->name('investigation.action-on-report');
			Route::post('/action-on-report-decline', 'InvestigationController@actionOnReportDecline')->name('investigation.action-on-report-decline');
			Route::post('/action-on-report-del', 'InvestigationController@actionOnReportDel')->name('investigation.action-on-report-del');
			Route::post('/submit-sm-report', 'InvestigationController@submitSmReport')->name('investigation.submit-sm-report');
			Route::post('reject-investigation', 'InvestigationController@rejectInvestigation')->name('investigation.reject');
			Route::get('/urgent/change-status', 'InvestigationController@changeToUrgent')->name('investigation.changeToUrgent');

			Route::post('investigator-invoice-amount', 'InvestigationController@changeInvestigatorInvoiceAmount')->name('investigator-invoice-amount');
			Route::post('delboy-invoice-amount', 'InvestigationController@changeDelBoyInvoiceAmount')->name('delboy-invoice-amount');
		});

		Route::get('statuswise-counts', 'InvestigationController@getStatusWiseCounts')->name('investigation.statuswise-counts');
		Route::post('deleteInvestigations', 'InvestigationController@deleteMultiple')->name('multidelete-investigation');
		// Client invoice pay
		Route::get('client-invoice-pay', 'InvoiceController@clientInvoicePay')->name('client-invoice-pay');
		Route::post('client-invoice-pay', 'InvoiceController@clientInvoicePay')->name('client-invoice-pay');

		Route::post('client-invoice-pay-bulk', 'InvoiceController@clientInvoicePayBulk')->name('client-invoice-pay-bulk');


		/* My Profile routes */
		Route::group(['prefix' => 'myprofile'], function () {
			Route::get('/', 'MyProfileController@showMyProfile')->name('myprofile.index');
			Route::post('/update', 'MyProfileController@updatePersonalDetails')->name('myprofile.update');
			Route::post('/bankupdate', 'MyProfileController@updateBankDetails')->name('myprofile.bankupdate');
			Route::post('/checkemail', 'MyProfileController@checkemail')->name('myprofile.checkemail');
			Route::get('/contactsdata', 'MyProfileController@contactsDataAppends')->name('myprofile.contactsdata');
			Route::post('/contactsdata/update', 'MyProfileController@contactsDataUpdate')->name('myprofile.contactsdata.update');
		});

		Route::group(['prefix' => 'permissions', 'middleware' => 'perm:permission_show|permission_create,true'], function () {
			Route::get('/', 'PermissionsController@index')->name('permission.index');
			Route::get('/create', 'PermissionsController@create')->name('permission.create')->middleware('perm:permission_create');
			Route::get('/get-permission-list', 'PermissionsController@getPermissionList')->name('permission.getlist');
			Route::post('/store', 'PermissionsController@store')->name('permission.store');
			Route::delete('/remove/{perm_id}', 'PermissionsController@destroy')->name('permission.destroy')->middleware('perm:permission_delete');
			Route::post('/delete-users', 'PermissionsController@deleteMultiple')->name('permission.multidelete')->middleware('perm:permission_delete');
		});

		Route::group(['prefix' => 'usertypes', 'middleware' => 'perm:usertype_show|usertype_edit,true'], function () {
			Route::get('/', 'UserTypesController@index')->name('usertype.index');
			Route::get('/get-usertype-list', 'UserTypesController@getUsertypesList')->name('usertype.getlist');
			Route::get('/edit/{type_id}', 'UserTypesController@edit')->name('usertype.edit')->middleware('perm:usertype_edit');
			Route::post('/update/{type_id}', 'UserTypesController@update')->name('usertype.update');
			Route::get('get-usertype/{id}', 'UserTypesController@getUserType')->name('usertype.getUserType');
		});

		Route::get('read-notification/{id}', "UserTypesController@readnotification")->name('user.readnotification');
		Route::get('get-notifications/', function () {
			return getNotificationdata();
		})->name('user.getnotification');

		/* Setting routes */
		Route::group(['prefix' => 'settings'], function () {
			//Document Price  module
			Route::group(['prefix' => 'documentprice'], function () {
				Route::get('/', 'SettingsController@documentpriceindex')->name('documentprice.index');
				Route::get('/create', 'SettingsController@createdocumentprice')->name('documentprice.create');
				Route::get('/get-documentprice-list', 'SettingsController@getDocumentPriceList')->name('documentprice.getlist');
				Route::post('/store', 'SettingsController@storedocumentprice')->name('documentprice.store');
				Route::get('/edit/{docprice_id}', 'SettingsController@editdocumentprice')->name('documentprice.edit');
				Route::post('/update/{docprice_id}', 'SettingsController@updatedocumentprice')->name('documentprice.update');
				Route::delete('/remove/{docprice_id}', 'SettingsController@destroydocumentprice')->name('documentprice.destroy');
				Route::post('/delete-docprice', 'SettingsController@deleteMultipleDocumentprice')->name('documentprice.multidelete');
			});

			//General settings
			Route::get('/general', 'SettingsController@general')->name('settings.general');
			Route::post('/update-tax', 'SettingsController@updateTax')->name('settings.update-tax');
		});

		/* Ticket routes */
		Route::group(['prefix' => 'ticket', 'middleware' => 'perm:tickets_create|tickets_edit|tickets_show|tickets_delete,true'], function () {
			Route::get('/', 'TicketsController@index')->name('tickets.index');
			Route::get('datatable/tickets', 'TicketsController@ticketList')->name('ticket-list');
			Route::get('/create', 'TicketsController@create')->name('tickets.create')->middleware('perm:tickets_create');
			Route::post('/store', 'TicketsController@store')->name('tickets.store');
			Route::get('/messages/{ticketId}', 'TicketsController@messages')->name('ticket.messages');
			Route::post('/send-message', 'TicketsController@sendMessage')->name('ticket.send-message');
			Route::get('/refreshChat/{id}', 'TicketsController@refreshChat')->name('ticket.refreshChat');
			Route::get('/view/{ticketId}', 'TicketsController@show')->name('ticket.view')->middleware('perm:tickets_show');
			Route::post('/change-status', 'TicketsController@changeStatus')->name('tickets.changestatus')->middleware('perm:tickets_edit');
		});

		/* Invoice routes */
		Route::group(['prefix' => 'invoices', 'middleware' => 'perm:invoice_list,true'], function () {
			Route::get('/', 'InvoiceController@index')->name('invoices.index');
			Route::get('/performainvoices', 'InvoiceController@performainvoices')->name('performainvoices.index');

			Route::get('datatable/invoice', 'InvoiceController@invoiceList')->name('invoice-list');
			Route::get('datatable/invoice-list-ad/{status?}', 'InvoiceController@invoiceListAdmin')->name('invoice-list-ad');

			Route::get('/bulk', 'InvestigationController@showInvoices')->name('investigation.showinvoices');
			Route::post('/mark-final-paid', 'InvoiceController@markAsFinalPaid')->name('invoice.mark-final-paid');
			Route::post('/discount-payment', 'InvoiceController@discountPayment')->name('invoice.discount-payment');
			Route::post('/parital-payment', 'InvoiceController@paritalPayment')->name('invoice.parital-payment');
			Route::get('/invoice/{id}/{type}', 'InvoiceController@showInvoice')->name('invoice.show');

			Route::get('/pending', 'InvoiceController@pendinginvoice')->name('invoice.pendinginvoice');
		});

		Route::group(['prefix' => 'invoice', 'middleware' => 'perm:invoice_list,true'], function () {
			Route::get('/', 'InvoiceController@actualInvoice')->name('actualinvoice.index');
			//Route::get('datatable/invoice', 'InvoiceController@listInvoice');
		});
	});
});
