<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\AppHelper;
use App\Helpers\Helper;
use App\Investigations;
use App\User;
use App\UserTypes;
use App\Product;
use App\Client;
use App\Tickets;
use App\Invoice;
use App\InvestigatorInvoice;
use App\DeliveryboyInvoice;
use App\InvestigatorInvestigations;
use App\DeliveryboyInvestigations;
use App\InvestigationTransition;
use App\UserCategories;
use App\PerformaInvoice;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
	//
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		return view('dashboard.index');
	}

	// dashboard chart logic for all users
	public function refreshChart(Request $request)
	{
		// get all chart data for dashboard screen
		if ($request->chart == "all") {
			// get data of chart for current year
			if ($request->year == "current") {

				$finalcostperTypeData = $delPerClient = $delPerType = $delPerInvestigator = $perClient = $perType = $perInvestigator = $investigationCount = $inquireTypeData =	$monthlyinvestigations = $openclosedinvestigations = $deliveriesCount = $clientInvestigationCount = $topInvestigators = $topDeliveryboys = $pendingInvestigations = $pendingClients = $pendingInvestigators = $pendingDeliveryBoys = $lateInvestigations = $lateInvoices = $openInvoice = $closeInvoice = $openComplaints = $closeComplaints = $totalIncome = $totalOutCome = $investigations = $paymentPaidToInvDel = $paymentNotRev = $incomePerEmp = $complaintsPerClient = [];

				$investigations = $this->getEmployeeEfficiencyData(); 
				$investigations = $investigations->whereYear('investigator_investigations.created_at', date('Y'));
				
				$inquireTypeData = $this->getTimePerCaseTypeData();

				if (isInvestigator()) {
					$investigationCount =  InvestigatorInvestigations::select(
						'investigator_id', DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status = 'Completed With Findings' THEN investigation_id ELSE NULL END)) AS total_completed"), DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status = 'Assigned' THEN investigation_id ELSE NULL END)) AS in_progress"), DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status = 'Investigation Declined' THEN investigation_id ELSE NULL END)) AS declined"), DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status = 'Report Writing' THEN investigation_id ELSE NULL END)) AS report_writing"), DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status = 'Report Submitted' THEN investigation_id ELSE NULL END)) AS report_submitted"), DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status = 'Completed Without Findings' THEN investigation_id ELSE NULL END)) AS total_not_completed")
					)->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->join('users', 'users.id', '=', 'investigators.user_id')->where('investigators.user_id', Auth::id())->get();

					// Investigator Monthly Investigation
					$monthlyinvestigations = $this->getInvestigatorMonthlyInv();
					$monthlyinvestigations = $monthlyinvestigations->join('users', 'users.id', '=', 'investigators.user_id')->where('investigators.user_id', Auth::id())->whereYear('investigator_investigations.created_at', date('Y'))->get()->toArray();

					// Investigator Time Per Case Type current year
					$inquireTypeData = $inquireTypeData->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->where('investigators.user_id', Auth::id())->get()->toArray();

					// Investigator Employee Efficiency current year
					$investigations = $investigations->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->where('investigators.user_id', Auth::id())->get()->toArray();
				}
				if (isDeliveryboy()) {

					$deliveriesCount =  DeliveryboyInvestigations::select(
						'deliveryboy_id', DB::raw("COUNT(DISTINCT(CASE WHEN deliveryboy_investigations.status = 'Assigned' THEN investigation_id ELSE NULL END))+COUNT(DISTINCT(CASE WHEN deliveryboy_investigations.status = 'Return To Center' THEN investigation_id ELSE NULL END)) AS in_progress"), DB::raw("COUNT(DISTINCT(CASE WHEN deliveryboy_investigations.status = 'Done And Delivered' THEN investigation_id ELSE NULL END)) AS total_completed"), DB::raw("COUNT(DISTINCT(CASE WHEN deliveryboy_investigations.status = 'Done And Not Delivered' THEN investigation_id ELSE NULL END)) AS total_not_completed"), DB::raw("COUNT(DISTINCT(CASE WHEN deliveryboy_investigations.status = 'Report Writing' THEN investigation_id ELSE NULL END)) AS report_writing"), DB::raw("COUNT(DISTINCT(CASE WHEN deliveryboy_investigations.status = 'Report Submitted' THEN investigation_id ELSE NULL END)) AS report_submitted")
					)->join('deliveryboys', 'deliveryboys.id', '=', 'deliveryboy_investigations.deliveryboy_id')->where('deliveryboys.user_id', Auth::id())->get();

					// Deliveryboy Monthly Investigation
					$monthlyinvestigations = $this->getDeliveryboyMonthlyInv();
					$monthlyinvestigations = $monthlyinvestigations->whereYear('deliveryboy_investigations.created_at', date('Y'))->get()->toArray();

					// Deliveryboy Time Per Case Type 
					$inquireTypeData = $this->getDeliveryboyTimePerCaseTypeData();
					$inquireTypeData = $inquireTypeData->whereYear('deliveryboy_investigations.created_at', date('Y'))->get()->toArray();

					// Deliveryboy Employee Efficiency 
					$investigations = [];
					$investigations = $this->getDeliveryboyEfficiencyChartData();
					$investigations = $investigations->whereYear('deliveryboy_investigations.created_at', date('Y'))->get()->toArray();
				}
				if (isClient()) {
					$clientId = AppHelper::getClientIdFromUserId(Auth::id());
					$clientInvestigationCount =  Investigations::select('id', DB::raw("COUNT(DISTINCT(CASE WHEN status = 'Pending Approval' THEN id ELSE NULL END)) AS waiting"), DB::raw("COUNT(DISTINCT(CASE WHEN status = 'Open' THEN id ELSE NULL END))+COUNT(DISTINCT(CASE WHEN status = 'Approved' THEN id ELSE NULL END)) AS in_progress"), DB::raw("COUNT(DISTINCT(CASE WHEN status = 'Closed' THEN id ELSE NULL END)) AS closed"))->where('user_id', Auth::id())->get();
					
					$inprogressclientCount =  InvestigatorInvestigations::select(DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status = 'Assigned' or investigator_investigations.status = 'Investigation Started' or investigator_investigations.status = 'Delivered' THEN investigator_investigations.investigation_id ELSE NULL END)) AS in_progress2"))->join('investigations', 'investigations.id', '=', 'investigator_investigations.investigation_id')->where('investigations.user_id', Auth::id())->first()->toArray();
					
					$clientInvestigationCount[0]['in_progress'] = $clientInvestigationCount[0]['in_progress'] + $inprogressclientCount['in_progress2'];

					// Client Monthly Investigation
					$monthlyinvestigations = $this->monthlyInvestigation();

					// Client Time Per Case Type current year
					$inquireTypeData = $inquireTypeData->where('investigations.user_id', Auth::id())->get()->toArray();

					// Client Employee Efficiency current year
					$investigations = $investigations->where('investigations.user_id', Auth::id())->get()->toArray();
					
					$openInvoice = PerformaInvoice::with('investigation')->where('client_id', $clientId)->whereIn('status', ['pending', 'on-hold', 'requested'])->get();
					$openInvoice->map(function($invoice){
						$invoice->route = route('invoice.show', [Crypt::encrypt($invoice->id),'pinvoice']);
						$invoice->price = trans('general.money_symbol').$invoice->amount;
						return $invoice;
					});
					$closeInvoice = PerformaInvoice::with('investigation')->where('client_id', $clientId)->where('status', 'paid')->get();
					$closeInvoice->map(function($invoice){
						$invoice->route = route('invoice.show', [Crypt::encrypt($invoice->id),'pinvoice']);
						$invoice->price = trans('general.money_symbol').$invoice->amount;
						return $invoice;
					});
				}
				if (isAdmin()) {
					$topInvestigators =  InvestigatorInvestigations::with(['investigator:id,user_id', 'investigator.user:id,name,email', 'investigator.user.specializations:name,hr_name'])->select('investigator_id', DB::raw("COUNT(DISTINCT(CASE WHEN status = 'Completed With Findings' THEN investigation_id ELSE NULL END)) AS total_completed"), DB::raw("COUNT(DISTINCT(CASE WHEN status = 'Completed Without Findings' THEN investigation_id ELSE NULL END)) AS total_not_completed"), DB::raw("COUNT(DISTINCT(CASE WHEN status NOT IN ('Completed Without Findings', 'Completed With Findings') THEN investigation_id ELSE NULL END)) AS total_open"))->groupBy('investigator_id')->orderBy('total_open', 'DESC')->limit(10)->get();

					$topDeliveryboys =  DeliveryboyInvestigations::with(['deliveryboy:id,user_id', 'deliveryboy.user:id,name,email', 'deliveryboy.user.delivery_areas:area_name,hr_area_name'])->select('deliveryboy_id', DB::raw("COUNT(DISTINCT(CASE WHEN status = 'Done And Not Delivered' THEN investigation_id ELSE NULL END)) AS total_not_completed"), DB::raw("COUNT(DISTINCT(CASE WHEN status = 'Done And Delivered' THEN investigation_id ELSE NULL END)) AS total_delivered"), DB::raw("COUNT(DISTINCT(CASE WHEN status IN ('Assigned', 'Return To Center') THEN investigation_id ELSE NULL END)) AS total_open"))->groupBy('deliveryboy_id')->orderBy('total_open', 'DESC')->limit(10)->get();

					// Monthly Investigation
					$monthlyinvestigations = $this->monthlyInvestigation();

					// Time Per Case Type current year
					$inquireTypeData = $inquireTypeData->get()->toArray();
					// Employee Efficiency current year
					$investigations = $investigations->get()->toArray();
					$openclosedinvestigations = Investigations::select(DB::raw("COUNT(DISTINCT(CASE WHEN investigations.status != 'Closed' THEN investigations.id ELSE NULL END)) as open"), DB::raw("COUNT(DISTINCT(CASE WHEN investigations.status = 'Closed' THEN investigations.id ELSE NULL END)) AS closed")
					)->first()->toArray();
					
					// Investigations Per investigator
					$perInvestigator = $this->investigationPer('investigator');
					$perInvestigator = $perInvestigator->get()->toArray();

					// Investigations Per type
					$perType = $this->investigationPer('type');
					$perType = $perType->get()->toArray();

					// Investigations Per client
					$perClient = $this->investigationPer('client');
					$perClient = $perClient->get()->toArray();

					// Deliveries Per delivery boy
					$delPerInvestigator = $this->deliveryPer('deliveryboy');
					$delPerInvestigator = $delPerInvestigator->get()->toArray();

					// Deliveries Per type
					$delPerType = $this->deliveryPer('type');
					$delPerType = $delPerType->get()->toArray();

					// Deliveries Per client
					$delPerClient = $this->deliveryPer('client');
					$delPerClient = $delPerClient->get()->toArray();

					//cost per type chart
					$tempcsttypearr = $costData =  [];
					$costTypeData = $this->getInquiryTypeDate();
					$costTypeData = $costTypeData->whereYear('investigator_investigations.created_at', date('Y'))->get()->toArray();
					foreach ($costTypeData as $key => $value) {
						$costData[$key]['y'] = $value['name'];
						$costData[$key]['a'] = $value['inv_cost'];
					}

					foreach ($costData as $key => $value) {
						$tempcsttypearr[$costData[$key]['y']] = isset($tempcsttypearr[$costData[$key]['y']]) ? ($tempcsttypearr[$costData[$key]['y']]) + $costData[$key]['a'] : $costData[$key]['a'];
					}
					foreach ($tempcsttypearr as $key => $value) {
						$finalcostperTypeData[] = ['y' => $key, 'a' => $value];
					}
					// end cost per type chart
					
					// pending investigation
					$pendingInvestigations = $this->lateInvestigations();
					$pendingInvestigations = $pendingInvestigations->where('status', 'Pending Approval')->orderBy('created_at', 'desc')->get();
					$pendingInvestigations->map(function($pendingInvestigation){
						$pendingInvestigation->route = route('investigation.show', [Crypt::encrypt($pendingInvestigation->id)]);
						return $pendingInvestigation;
					});
					// pending client
					$pendingClients = $this->pendingClients();

					// pendingInvestigators
					$pendingInvestigators = $this->pendingInvestigator();

					// pending DeliveryBoys
					$pendingDeliveryBoys = $this->pendingDeliveryboy();

					$finalInvn = $this->openIvestigations();
					$lateInvestigations = $this->lateInvestigations();
					$lateInvestigations = $lateInvestigations->whereIn('id', $finalInvn)->get();

					$lateInvestigations->map(function($lateInvestigation){
						$lateInvestigation->route = route('investigation.show', [Crypt::encrypt($lateInvestigation->id)]);
						return $lateInvestigation;
					});
					
					$lateInvoices = $this->getUnPaidPerformaInv();

					$openComplaints = Tickets::where('type', 'Complaint')->where('status', 'Open')->count();
					$closeComplaints = Tickets::where('type', 'Complaint')->where('status', 'Close')->count();

					$income = Invoice::where('status', 'paid')->sum('amount');
					$totalIncome = str_replace(',','',number_format($income, 2));
					$outComeInv = InvestigatorInvoice::where('status', 'paid')->sum('amount');
					$outComeDel = DeliveryboyInvoice::where('status', 'paid')->sum('amount');
					$outCome = ($outComeInv + $outComeDel);
					$totalOutCome = str_replace(',','',number_format($outCome, 2));

					$paymentPaidToInvDel = $this->paymentPayTo();
					$paymentPaidToInvDel = $paymentPaidToInvDel->whereYear('investigator_invoices.created_at', date('Y'))->get()->toArray();

					$paymentNotRev = $this->paymentNotRev();
					$paymentNotRev = $paymentNotRev->whereYear('created_at', date('Y'))->get()->toArray();

					$incomePerEmp = PerformaInvoice::select(DB::raw('SUM(performa_invoices.amount) total'), 'users.salary', DB::raw('SUM(performa_invoices.amount) - users.salary as diff'), 'users.name', DB::raw('COUNT(performa_invoices.id) total_performa'))->join('users', 'users.id', '=', 'performa_invoices.created_by')->join('user_types', 'user_types.id', '=', 'users.type_id')->whereIn('user_types.type_name', [env('USER_TYPE_ACCOUNTANT'), env('USER_TYPE_STATION_MANAGER')])->whereNotNull('users.salary')->whereMonth('performa_invoices.created_at', date('m'))->groupby('performa_invoices.created_by')->get()->toArray();

					$complaintsPerClient = $this->complaintPerClient();
				}
				if (isSM()) {
					$usercats = UserCategories::getUserCategories();

					$topInvestigators =  InvestigatorInvestigations::with(['investigator:id,user_id', 'investigator.user:id,name,email', 'investigator.user.specializations:name,hr_name'])
						->select(
							'investigator_investigations.investigator_id',
							DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status = 'Completed With Findings' THEN investigator_investigations.investigation_id ELSE NULL END)) AS total_completed"),
							DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status = 'Completed Without Findings' THEN investigator_investigations.investigation_id ELSE NULL END)) AS total_not_completed"),
							DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status NOT IN ('Completed Without Findings', 'Completed With Findings') THEN investigator_investigations.investigation_id ELSE NULL END)) AS total_open")
						)
						->join('investigations', 'investigations.id', '=', 'investigator_investigations.investigation_id')
						->join('products', 'products.id', '=', 'investigations.type_of_inquiry')
						->wherein('products.category_id',  $usercats)
						->groupBy('investigator_investigations.investigator_id')
						->orderBy('total_open', 'DESC')
						->limit(10)
						->get();


					$topDeliveryboys =  DeliveryboyInvestigations::with(['deliveryboy:id,user_id', 'deliveryboy.user:id,name,email', 'deliveryboy.user.delivery_areas:area_name,hr_area_name'])
						->select(
							'deliveryboy_investigations.deliveryboy_id',
							DB::raw("COUNT(DISTINCT(CASE WHEN deliveryboy_investigations.status = 'Done And Not Delivered' THEN deliveryboy_investigations.investigation_id ELSE NULL END)) AS total_not_completed"),
							DB::raw("COUNT(DISTINCT(CASE WHEN deliveryboy_investigations.status = 'Done And Delivered' THEN deliveryboy_investigations.investigation_id ELSE NULL END)) AS total_delivered"),
							DB::raw("COUNT(DISTINCT(CASE WHEN deliveryboy_investigations.status IN ('Assigned', 'Return To Center') THEN deliveryboy_investigations.investigation_id ELSE NULL END)) AS total_open")
						)
						->join('investigations', 'investigations.id', '=', 'deliveryboy_investigations.investigation_id')
						->join('products', 'products.id', '=', 'investigations.type_of_inquiry')
						->wherein('products.category_id',  $usercats)
						->groupBy('deliveryboy_investigations.deliveryboy_id')
						->orderBy('total_open', 'DESC')
						->limit(10)
						->get();
					// Monthly Investigation
					$monthlyinvestigations = Investigations::select(DB::raw("DATE_FORMAT(investigations.created_at, '%d-%m-%Y') created_at"), DB::raw('COUNT(investigations.id)  total'),  DB::raw('MONTH(investigations.created_at) month'))
						->join('products', 'products.id', '=', 'investigations.type_of_inquiry')
						->wherein('products.category_id',  $usercats)
						->whereYear('investigations.created_at', date('Y'))
						->orderBy('investigations.created_at', 'ASC')
						->groupby('month')
						->get()
						->toArray();

					// Time Per Case Type current year
					$inquireTypeData = $inquireTypeData->wherein('products.category_id',  $usercats)->get()->toArray();

					// Employee Efficiency current year
					// $investigations = [];
					$investigations = $investigations->join('products', 'products.id', '=', 'investigations.type_of_inquiry')->wherein('products.category_id',  $usercats)->get()->toArray();

					//open closed single investigation status chart
					$openclosedinvestigations = Investigations::select(
						DB::raw("COUNT(DISTINCT(CASE WHEN investigations.status = 'Pending Approval' THEN investigations.id ELSE NULL END)) AS pending_approval"),
						DB::raw("COUNT(DISTINCT(CASE WHEN investigations.status != 'Closed' THEN investigations.id ELSE NULL END)) as open"),
						DB::raw("COUNT(DISTINCT(CASE WHEN investigations.status = 'Closed' THEN investigations.id ELSE NULL END)) AS closed")
					)->join('products', 'products.id', '=', 'investigations.type_of_inquiry')
						->wherein('products.category_id',  $usercats)
						->first()
						->toArray();
					$openclosecatCount =  InvestigatorInvestigations::select(
						DB::raw("COUNT(DISTINCT(CASE WHEN investigator_investigations.status = 'Assigned' or investigator_investigations.status = 'Investigation Started' or investigator_investigations.status = 'Report Writing' or investigator_investigations.status = 'Report Submitted' THEN investigation_id ELSE NULL END)) AS open2")
					)->join('investigations', 'investigations.id', '=', 'investigator_investigations.investigation_id')
						->join('products', 'products.id', '=', 'investigations.type_of_inquiry')
						->wherein('products.category_id',  $usercats)->first()
						->toArray();
					$openclosedinvestigations['open'] = $openclosedinvestigations['open'] + $openclosecatCount['open2'];

					// Investigations Per investigator
					$perInvestigator = $this->investigationPer('investigator');
					$perInvestigator = $perInvestigator->join('investigations', 'investigations.id', '=', 'investigator_investigations.investigation_id')->join('products', 'products.id', '=', 'investigator_investigations.type_of_inquiry')->wherein('products.category_id',  $usercats)->get()->toArray();

					// Investigations Per type
					$perType = $this->investigationPer('type');
					$perType = $perType->join('investigations', 'investigations.id', '=', 'investigator_investigations.investigation_id')->wherein('products.category_id',  $usercats)->get()->toArray();

					// Investigations Per client
					$perClient = $this->investigationPer('client');
					$perClient = $perClient->join('products', 'products.id', '=', 'investigator_investigations.type_of_inquiry')->wherein('products.category_id',  $usercats)->get()->toArray();

					// Deliveries Per delivery boy
					$delPerInvestigator = $this->deliveryPer('deliveryboy');
					$delPerInvestigator = $delPerInvestigator->join('investigations', 'investigations.id', '=', 'deliveryboy_investigations.investigation_id')->join('products', 'products.id', '=', 'deliveryboy_investigations.type_of_inquiry')->wherein('products.category_id',  $usercats)->get()->toArray();

					// Deliveries Per type
					$delPerType = $this->deliveryPer('type');
					$delPerType = $delPerType->join('investigations', 'investigations.id', '=', 'deliveryboy_investigations.investigation_id')->wherein('products.category_id',  $usercats)->get()->toArray();

					// Deliveries Per client
					$delPerClient = $this->deliveryPer('client');
					$delPerClient = $delPerClient->join('products', 'products.id', '=', 'deliveryboy_investigations.type_of_inquiry')->wherein('products.category_id',  $usercats)->get()->toArray();

					//cost per type chart
					$tempcsttypearr = $costData =  [];
					$costTypeData = $this->getInquiryTypeDate();
					$costTypeData = $costTypeData->whereYear('investigator_investigations.created_at', date('Y'))->wherein('products.category_id',  $usercats)->get()->toArray();
					foreach ($costTypeData as $key => $value) {
						$costData[$key]['y'] = $value['name'];
						$costData[$key]['a'] = $value['inv_cost'];
					}

					foreach ($costData as $key => $value) {
						$tempcsttypearr[$costData[$key]['y']] = isset($tempcsttypearr[$costData[$key]['y']]) ? ($tempcsttypearr[$costData[$key]['y']]) + $costData[$key]['a'] : $costData[$key]['a'];
					}
					foreach ($tempcsttypearr as $key => $value) {
						$finalcostperTypeData[] = ['y' => $key, 'a' => $value];
					}
					// end cost per type chart
					// pending investigation
					$usercats = UserCategories::getUserCategories();
					$pendingInvestigations = $this->lateInvestigations();
					$pendingInvestigations = $pendingInvestigations->whereHas('product', function ($q) use ($usercats) {
						$q->select(['id', 'name', 'category_id'])->whereIn('category_id',  $usercats);
					})->where('status', 'Pending Approval')->orderBy('created_at', 'desc')->get();

					$pendingInvestigations->map(function($pendingInvestigation){
						$pendingInvestigation->route = route('investigation.show', [Crypt::encrypt($pendingInvestigation->id)]);
						return $pendingInvestigation;
					});

					// pending client
					$pendingClients = $this->pendingClients();

					// pendingInvestigators
					$pendingInvestigators = $this->pendingInvestigator();

					// pending DeliveryBoys
					$pendingDeliveryBoys = $this->pendingDeliveryboy();

					$usercats = UserCategories::getUserCategories();

					$finalInvn = $this->openIvestigations();
					$lateInvestigations = $this->lateInvestigations();
					
					$lateInvestigations = $lateInvestigations->whereHas('product', function ($q) use ($usercats) {
						$q->select(['id', 'name', 'category_id'])->whereIn('category_id',  $usercats);
					})->whereIn('id', $finalInvn)->get();

					$lateInvestigations->map(function($lateInvestigation){
						$lateInvestigation->route = route('investigation.show', [Crypt::encrypt($lateInvestigation->id)]);
						return $lateInvestigation;
					});

					$lateInvoices = $this->getUnPaidPerformaInv();

					$openComplaints = Tickets::where('type', 'Complaint')->where('status', 'Open')->count();
					$closeComplaints = Tickets::where('type', 'Complaint')->where('status', 'Close')->count();

					$complaintsPerClient = $this->complaintPerClient();
				}
				if (isAccountant()) {
					$paymentPaidToInvDel = $this->paymentPayTo();
					$paymentPaidToInvDel = $paymentPaidToInvDel->whereYear('investigator_invoices.created_at', date('Y'))->get()->toArray();

					$paymentNotRev = $this->paymentNotRev();
					$paymentNotRev = $paymentNotRev->whereYear('created_at', date('Y'))->get()->toArray();
				}

				$employeeEfficiency = [];
				$inquireData = [];
				$monthlyinv = [];
				foreach ($investigations as $key => $value) {
					$month = Carbon::parse($value['created_at'])->formatLocalized('%b');
					$employeeEfficiency[$key]['y'] = trans('general.month.'.$month);
					$employeeEfficiency[$key]['a'] = $value['inv_cost'] ? $value['inv_cost'] : 0;
					$employeeEfficiency[$key]['b'] = $value['charge'] ? $value['charge'] : 0;
				}
				foreach ($monthlyinvestigations as $key => $value) {
					$month = Carbon::parse($value['created_at'])->formatLocalized('%b');
					$monthlyinv[$key]['y'] = trans('general.month.'.$month);
					$monthlyinv[$key]['a'] = $value['total'] ? $value['total'] : 0;
				}
				$cnt = 0;
				
				foreach ($inquireTypeData as $key => $value) {
					$status = AppHelper::find_key_value($inquireData, 'y', $value['name']);
					if($status){
						$inquireData[$cnt]['y'] = $value['name'];
						$inquireData[$cnt]['a'] = Carbon::parse($value['investigation_start_at'])->diffInDays($value['investigation_end_at']);
						$cnt++;
					} else {
						$id = AppHelper::searchForId($value['name'], 'y', $inquireData);
						$inquireData[$id]['a'] += Carbon::parse($value['investigation_start_at'])->diffInDays($value['investigation_end_at']);
					}
				}
				foreach($paymentPaidToInvDel as $key => $value){
					$paymentPaidToInvDel[$key]['y'] = trans('general.month.'.$value['y']);
				}
				foreach($paymentNotRev as $key => $value){
					$paymentNotRev[$key]['y'] = trans('general.month.'.$value['y']);
				}
				return response()->json([
					'status' => 'success',
					'employee_efficiency' => $employeeEfficiency,
					'monthly_investigations' => $monthlyinv,
					'time_per_case' => $inquireData,
					'cost_per_type' => $finalcostperTypeData,
					'perInvestigator' => $perInvestigator,
					'perType' => $perType,
					'perClient' => $perClient,
					'delPerInvestigator' => $delPerInvestigator,
					'delPerType' => $delPerType,
					'delPerClient' => $delPerClient,
					'investigationCount' => $investigationCount,
					'deliveriesCount' => $deliveriesCount,
					'clientInvestigationCount' => $clientInvestigationCount,
					'topInvestigators' => $topInvestigators,
					'topDeliveryboys' => $topDeliveryboys,
					'opencloseInvestigation' => $openclosedinvestigations,
					'pendingInvestigations' => $pendingInvestigations,
					'pendingClients' => $pendingClients,
					'pendingInvestigators' => $pendingInvestigators,
					'pendingDeliveryBoys' => $pendingDeliveryBoys,
					'lateInvestigations' => $lateInvestigations,
					'lateInvoices' => $lateInvoices,
					'openInvoice' => $openInvoice,
					'closeInvoice' => $closeInvoice,
					'openComplaints' => $openComplaints,
					'closeComplaints' => $closeComplaints,
					'totalOutCome' => $totalOutCome,
					'totalIncome' => $totalIncome,
					'paymentPaidToInvDel' => $paymentPaidToInvDel,
					'paymentNotRev' => $paymentNotRev,
					'incomePerEmp' => $incomePerEmp,
					'complaintsPerClient' => $complaintsPerClient,
				]);
			}
		} else if ($request->chart == "employee-efficiency") {
			$investigations = [];
			$investigations = $this->getEmployeeEfficiencyData();

			if ($request->year == "current" || $request->year == "previous") {
				if ($request->year == "current") {
					$year = date('Y');
				} else {
					$year = date("Y", strtotime("-1 year"));
				}
				if (isInvestigator()) {
					// Investigator Efficiency Chart current/previous year
					$investigations = $investigations->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->whereYear('investigator_investigations.created_at', $year)->where('investigators.user_id', Auth::id())->get()->toArray();
				} else if (isDeliveryboy()) {
					// Deliveryboy Efficiency Chart current/previous year
					$investigations = [];
					$investigations = $this->getDeliveryboyEfficiencyChartData();
					$investigations = $investigations->whereYear('deliveryboy_investigations.created_at', $year)->get()->toArray();
				} else {
					$investigations = $investigations->whereYear('investigator_investigations.created_at', $year);
					if (isClient()) {
						$investigations = $investigations->where('investigations.user_id', Auth::id());
					}
					if (isSM()) {
						$usercats = UserCategories::getUserCategories();
						$investigations = $investigations->join('products', 'products.id', '=', 'investigations.type_of_inquiry')->wherein('products.category_id',  $usercats);
					}
					$investigations = $investigations->get()->toArray();
				}
			} else if ($request->year == "last_3" || $request->year == "last_5") {
				if ($request->year == "last_3") {
					$from = date("Y", strtotime("-3 year"));
				} else {
					$from = date("Y", strtotime("-5 year"));
				}
				$to = date("Y");
				if (isInvestigator()) {

					// Investigator Efficiency Chart last_3/last_5 year
					$investigations = [];
					$investigations = $this->getInvestigatorEfficiencyData();
					$investigations = $investigations->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->where('investigators.user_id', Auth::id())->having('year', '>=', $from)->having('year', '<=', $to)->get()->toArray();
				} else if (isDeliveryboy()) {
					// Deliveryboy Efficiency Chart last_3/last_5 year
					$investigations = [];
					$investigations = $this->getDeliveryboyEfficiencyData();
					$investigations = $investigations->having('year', '>=', $from)->having('year', '<=', $to)->get()->toArray();
				} else {
					$investigations = [];
					$investigations = $this->getInvestigatorEfficiencyData();
					$investigations = $investigations->having('year', '>=', $from)->having('year', '<=', $to);
					if (isClient()) {
						$investigations = $investigations->where('investigations.user_id', Auth::id());
					}
					if (isSM()) {
						$usercats = UserCategories::getUserCategories();
						$investigations = $investigations->join('products', 'products.id', '=', 'investigations.type_of_inquiry')
							->wherein('products.category_id',  $usercats);
					}

					$investigations = $investigations->get()
						->toArray();
				}
			} else if ($request->year == "life_time") {
				if (isInvestigator()) {
					// Investigator Efficiency Chart life_time
					$investigations = [];
					$investigations = $this->getInvestigatorEfficiencyData();
					$investigations = $investigations->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->where('investigators.user_id', Auth::id())->get()->toArray();
				} else if (isDeliveryboy()) {
					// Deliveryboy Efficiency Chart life_time
					$investigations = [];
					$investigations = $this->getDeliveryboyEfficiencyData();
					$investigations = $investigations->get()->toArray();
				} else {
					$investigations = [];
					$investigations = $this->getInvestigatorEfficiencyData();
					if (isClient()) {
						$investigations = $investigations->where('investigations.user_id', Auth::id());
					}
					if (isSM()) {
						$usercats = UserCategories::getUserCategories();
						$investigations = $investigations->join('products', 'products.id', '=', 'investigations.type_of_inquiry')->wherein('products.category_id',  $usercats);
					}
					$investigations = $investigations->get()->toArray();
				}
			}
			$employeeEfficiency = [];
			foreach ($investigations as $key => $value) {
				$month = Carbon::parse($value['created_at'])->formatLocalized('%b');
				$employeeEfficiency[$key]['y'] = trans('general.month.'.$month);
				$employeeEfficiency[$key]['a'] = $value['inv_cost'] ? $value['inv_cost'] : 0;
				$employeeEfficiency[$key]['b'] = $value['charge'] ? $value['charge'] : 0;
			}
			return response()->json([
				'status' => 'success',
				'employee_efficiency' => $employeeEfficiency,
			]);
		} else if ($request->chart == "monthly-investigations-chart") {
			if ($request->year == "current" || $request->year == "previous") {
				if ($request->year == "current") {
					$year = date('Y');
				} else {
					$year = date("Y", strtotime("-1 year"));
				}
				if (isInvestigator()) {

					// Investigator Monthly Investigation
					$investigations = [];
					$investigations = $this->getInvestigatorMonthlyInv();
					$investigations = $investigations->where('investigators.user_id', Auth::id())->whereYear('investigator_investigations.created_at', $year)->get()->toArray();
				} else if (isDeliveryboy()) {
					// Deliveryboy Monthly Investigation
					$investigations = [];
					$investigations = $this->getDeliveryboyMonthlyInv();
					$investigations = $investigations->whereYear('deliveryboy_investigations.created_at', $year)->get()->toArray();
				} else {
					$investigations = [];
					$investigations = Investigations::select(DB::raw("DATE_FORMAT(investigations.created_at, '%d-%m-%Y') created_at"),  DB::raw('COUNT(investigations.id)  total'), DB::raw('MONTH(investigations.created_at) month'))->whereYear('investigations.created_at', $year)->groupby('month');
					if (isClient()) {
						$investigations = $investigations->where('investigations.user_id', Auth::id());
					}
					if (isSM()) {
						$usercats = UserCategories::getUserCategories();
						$investigations = $investigations->join('products', 'products.id', '=', 'investigations.type_of_inquiry')->wherein('products.category_id',  $usercats);
					}
					$investigations = $investigations->get()->toArray();
				}
			} else if ($request->year == "last_3" || $request->year == "last_5") {
				if ($request->year == "last_3") {
					$from = date("Y", strtotime("-3 year"));
				} else {
					$from = date("Y", strtotime("-5 year"));
				}
				$to = date("Y");
				if (isInvestigator()) {
					$investigations = [];
					// Investigator Monthly Investigation
					$investigations = InvestigatorInvestigations::select(DB::raw("DATE_FORMAT(investigator_investigations.created_at, '%d-%m-%Y') created_at"), DB::raw('COUNT(investigator_investigations.id)  total'),  DB::raw('MONTH(investigator_investigations.created_at) month'), DB::raw('YEAR(investigator_investigations.created_at) year'))->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->where('investigators.user_id', Auth::id())->having('year', '>=', $from)->having('year', '<=', $to)->orderBy('investigator_investigations.created_at', 'ASC')->groupby('month')->get()->toArray();
				} else if (isDeliveryboy()) {
					$investigations = [];
					// Deliveryboy Monthly Investigation
					$investigations = DeliveryboyInvestigations::select(DB::raw("DATE_FORMAT(deliveryboy_investigations.created_at, '%d-%m-%Y') created_at"), DB::raw('COUNT(deliveryboy_investigations.id)  total'),  DB::raw('MONTH(deliveryboy_investigations.created_at) month'), DB::raw('YEAR(deliveryboy_investigations.created_at) year'))->join('deliveryboys', 'deliveryboys.id', '=', 'deliveryboy_investigations.deliveryboy_id')->where('deliveryboys.user_id', Auth::id())->having('year', '>=', $from)->having('year', '<=', $to)->orderBy('deliveryboy_investigations.created_at', 'ASC')->groupby('month')->get()->toArray();
				} else {
					$investigations = [];
					$investigations = Investigations::select(DB::raw("DATE_FORMAT(investigations.created_at, '%d-%m-%Y') created_at"),  DB::raw('COUNT(investigations.id)  total'), DB::raw('MONTH(investigations.created_at) month'), DB::raw('YEAR(investigations.created_at) year'))->having('year', '>=', $from)->having('year', '<=', $to)->groupby('month');
					if (isClient()) {
						$investigations = $investigations->where('investigations.user_id', Auth::id());
					}
					if (isSM()) {
						$usercats = UserCategories::getUserCategories();
						$investigations = $investigations->join('products', 'products.id', '=', 'investigations.type_of_inquiry')->wherein('products.category_id',  $usercats);
					}
					$investigations = $investigations->get()->toArray();
				}
			} else if ($request->year == "life_time") {
				if (isInvestigator()) {
					$investigations = [];
					// Investigator Monthly Investigation
					$investigations = $this->getInvestigatorMonthlyInv();
					$investigations = $investigations->where('investigators.user_id', Auth::id())->get()->toArray();
				} else if (isDeliveryboy()) {
					$investigations = [];
					// Deliveryboy Monthly Investigation
					$investigations = $this->getDeliveryboyMonthlyInv();
					$investigations = $investigations->get()->toArray();
				} else {
					$investigations = [];
					$investigations = Investigations::select(DB::raw("DATE_FORMAT(investigations.created_at, '%d-%m-%Y') created_at"),  DB::raw('COUNT(investigations.id)  total'), DB::raw('MONTH(investigations.created_at) month'), DB::raw('YEAR(investigations.created_at) year'))->groupby('month');
					if (isClient()) {
						$investigations = $investigations->where('investigations.user_id', Auth::id());
					}
					if (isSM()) {
						$usercats = UserCategories::getUserCategories();
						$investigations = $investigations->join('products', 'products.id', '=', 'investigations.type_of_inquiry')->wherein('products.category_id',  $usercats);
					}
					$investigations = $investigations->get()->toArray();
				}
			}
			$monthlyinvestigations = [];
			foreach ($investigations as $key => $value) {
				$month = Carbon::parse($value['created_at'])->formatLocalized('%b');
				$monthlyinvestigations[$key]['y'] = trans('general.month.'.$month);
				$monthlyinvestigations[$key]['a'] = $value['total'] ? $value['total'] : 0;
			}
			return response()->json([
				'status' => 'success',
				'monthly_investigations' => $monthlyinvestigations,
			]);
		} else if ($request->chart == "time-per-case-type") {
			$inquireTypeData = [];
			$inquireTypeData = $this->getTimePerCaseTypeData();
			if ($request->year == "current" || $request->year == "previous") {
				if ($request->year == "current") {
					$year = date('Y');
				} else {
					$year = date("Y", strtotime("-1 year"));
				}
				if (isInvestigator()) {
					// Investigator Time Per Case Type life time
					$inquireTypeData = $inquireTypeData->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->whereYear('investigator_investigations.created_at', $year)->where('investigators.user_id', Auth::id())->get()->toArray();
				} else if (isDeliveryboy()) {
					// Deliveryboy Time Per Case Type life time
					$inquireTypeData = $this->getDeliveryboyTimePerCaseTypeData();
					$inquireTypeData = $inquireTypeData->whereYear('deliveryboy_investigations.created_at', $year)->get()->toArray();
				} else {
					$inquireTypeData = $inquireTypeData->whereYear('investigator_investigations.created_at', $year);
					if (isClient()) {
						$inquireTypeData = $inquireTypeData->where('investigations.user_id', Auth::id());
					}
					if (isSM()) {
						$usercats = UserCategories::getUserCategories();
						$inquireTypeData = $inquireTypeData->wherein('products.category_id',  $usercats);
					}
					$inquireTypeData = $inquireTypeData->get()
						->toArray();
				}
			} else if ($request->year == "last_3" || $request->year == "last_5") {
				if ($request->year == "last_3") {
					$from = date("Y", strtotime("-3 year"));
				} else {
					$from = date("Y", strtotime("-5 year"));
				}
				$to = date("Y");
				if (isInvestigator()) {
					// Investigator Time Per Case Type life time
					$inquireTypeData = Product::select('products.name', DB::raw("concat(investigator_investigations.start_date, ' ', investigator_investigations.start_time) as investigation_start_at"), DB::raw("concat(investigator_investigations.completion_date,' ', investigator_investigations.completion_time) as investigation_end_at"), DB::raw('MONTH(investigator_investigations.created_at) month'), DB::raw('YEAR(investigator_investigations.created_at) year'))
						->join('investigations', 'investigations.type_of_inquiry', '=', 'products.id')
						->join('investigator_investigations', 'investigator_investigations.investigation_id', '=', 'investigations.id')
						->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')
						->whereNotNull('investigator_investigations.completion_date')
						->whereNotNull('investigator_investigations.completion_time')
						->where('investigators.user_id', Auth::id())
						->having('year', '>=', $from)
						->having('year', '<=', $to)
						->get()
						->toArray();
				} else if (isDeliveryboy()) {
					// Deliveryboy Time Per Case Type life time
					$inquireTypeData = Product::select('products.name', DB::raw("concat(deliveryboy_investigations.start_date, ' ', deliveryboy_investigations.start_time) as investigation_start_at"), DB::raw("concat(deliveryboy_investigations.completion_date,' ', deliveryboy_investigations.completion_time) as investigation_end_at"), DB::raw('MONTH(deliveryboy_investigations.created_at) month'), DB::raw('YEAR(deliveryboy_investigations.created_at) year'))
						->join('investigations', 'investigations.type_of_inquiry', '=', 'products.id')
						->join('deliveryboy_investigations', 'deliveryboy_investigations.investigation_id', '=', 'investigations.id')
						->join('deliveryboys', 'deliveryboys.id', '=', 'deliveryboy_investigations.deliveryboy_id')
						->whereNotNull('deliveryboy_investigations.completion_date')
						->whereNotNull('deliveryboy_investigations.completion_time')
						->where('deliveryboys.user_id', Auth::id())
						->having('year', '>=', $from)
						->having('year', '<=', $to)
						->get()
						->toArray();
				} else {
					$inquireTypeData = Product::select('products.name', DB::raw("concat(investigator_investigations.start_date, ' ', investigator_investigations.start_time) as investigation_start_at"), DB::raw("concat(investigator_investigations.completion_date,' ', investigator_investigations.completion_time) as investigation_end_at"), DB::raw('MONTH(investigator_investigations.created_at) month'), DB::raw('YEAR(investigator_investigations.created_at) year'))
						->join('investigations', 'investigations.type_of_inquiry', '=', 'products.id')
						->join('investigator_investigations', 'investigator_investigations.investigation_id', '=', 'investigations.id')
						->whereNotNull('investigator_investigations.completion_date')
						->whereNotNull('investigator_investigations.completion_time')
						->having('year', '>=', $from)
						->having('year', '<=', $to);
					if (isClient()) {
						$inquireTypeData = $inquireTypeData->where('investigations.user_id', Auth::id());
					}
					if (isSM()) {
						$usercats = UserCategories::getUserCategories();
						$inquireTypeData = $inquireTypeData->wherein('products.category_id',  $usercats);
					}
					$inquireTypeData = $inquireTypeData->get()
						->toArray();
				}
			} else if ($request->year == "life_time") {
				if (isInvestigator()) {
					// Investigator Time Per Case Type life time
					$inquireTypeData = $inquireTypeData->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->where('investigators.user_id', Auth::id())->get()->toArray();
				} else if (isDeliveryboy()) {
					// Deliveryboy Time Per Case Type life time
					$inquireTypeData = [];
					$inquireTypeData = $this->getDeliveryboyTimePerCaseTypeData();
					$inquireTypeData = $inquireTypeData->get()->toArray();
				} else {
					if (isClient()) {
						$inquireTypeData = $inquireTypeData->where('investigations.user_id', Auth::id());
					}
					if (isSM()) {
						$usercats = UserCategories::getUserCategories();
						$inquireTypeData = $inquireTypeData->wherein('products.category_id',  $usercats);
					}
					$inquireTypeData = $inquireTypeData->get()->toArray();
				}
			}
			$inquireData = [];
			$cnt = 0;
			foreach ($inquireTypeData as $key => $value) {
				$status = AppHelper::find_key_value($inquireData, 'y', $value['name']);
				if($status){
					$inquireData[$cnt]['y'] = $value['name'];
					$inquireData[$cnt]['a'] = Carbon::parse($value['investigation_start_at'])->diffInDays($value['investigation_end_at']);
					$cnt++;
				} else {
					$id = AppHelper::searchForId($value['name'], 'y', $inquireData);
					$inquireData[$id]['a'] += Carbon::parse($value['investigation_start_at'])->diffInDays($value['investigation_end_at']);
				}
			}
			return response()->json([
				'status' => 'success',
				'time_per_case' => $inquireData
			]);
		} else if ($request->chart == "cost-per-type") {
			if ($request->year == "current" || $request->year == "previous") {
				if ($request->year == "current") {
					$year = date('Y');
				} else {
					$year = date("Y", strtotime("-1 year"));
				}
				$inquireTypeData = $this->getInquiryTypeDate();
				$inquireTypeData = $inquireTypeData->whereYear('investigator_investigations.created_at', $year);
				if (isClient()) {
					$inquireTypeData = $inquireTypeData->where('investigations.user_id', Auth::id());
				}
				if (isSM()) {
					$usercats = UserCategories::getUserCategories();
					$inquireTypeData = $inquireTypeData->wherein('products.category_id',  $usercats);
				}
				$inquireTypeData = $inquireTypeData->get()
					->toArray();
			} else if ($request->year == "last_3" || $request->year == "last_5") {
				if ($request->year == "last_3") {
					$from = date("Y", strtotime("-3 year"));
				} else {
					$from = date("Y", strtotime("-5 year"));
				}
				$to = date("Y");

				$inquireTypeData = Product::select('products.name', 'investigations.inv_cost', DB::raw('MONTH(investigator_investigations.created_at) month'), DB::raw('YEAR(investigator_investigations.created_at) year'))
					->join('investigations', 'investigations.type_of_inquiry', '=', 'products.id')
					->join('investigator_investigations', 'investigator_investigations.investigation_id', '=', 'investigations.id')
					->whereNotNull('investigator_investigations.completion_date')
					->whereNotNull('investigator_investigations.completion_time')
					->having('year', '>=', $from)
					->having('year', '<=', $to);
				if (isClient()) {
					$inquireTypeData = $inquireTypeData->where('investigations.user_id', Auth::id());
				}
				if (isSM()) {
					$usercats = UserCategories::getUserCategories();
					$inquireTypeData = $inquireTypeData->wherein('products.category_id',  $usercats);
				}
				$inquireTypeData = $inquireTypeData->get()
					->toArray();
			} else if ($request->year == "life_time") {

				$inquireTypeData = $this->getInquiryTypeDate();
				if (isClient()) {
					$inquireTypeData = $inquireTypeData->where('investigations.user_id', Auth::id());
				}
				if (isSM()) {
					$usercats = UserCategories::getUserCategories();
					$inquireTypeData = $inquireTypeData->wherein('products.category_id',  $usercats);
				}
				$inquireTypeData = $inquireTypeData->get()
					->toArray();
			}
			$costData = $tempcsttypearr = $finalcostperTypeData = [];

			foreach ($inquireTypeData as $key => $value) {
				$costData[$key]['y'] = $value['name'];
				$costData[$key]['a'] = $value['inv_cost'];
			}
			foreach ($costData as $key => $value) {
				$tempcsttypearr[$costData[$key]['y']] = isset($tempcsttypearr[$costData[$key]['y']]) ? ($tempcsttypearr[$costData[$key]['y']]) + $costData[$key]['a'] : $costData[$key]['a'];
			}
			foreach ($tempcsttypearr as $key => $value) {
				$finalcostperTypeData[] = ['y' => $key, 'a' => $value];
			}
			return response()->json([
				'status' => 'success',
				'cost_per_type' => $finalcostperTypeData
			]);
		} else if ($request->chart == "inv-del-invoice-amount") {
			if ($request->year == "current" || $request->year == "previous") {
				if ($request->year == "current") {
					$year = date('Y');
				} else {
					$year = date("Y", strtotime("-1 year"));
				}
				$paymentPaidToInvDel = $this->paymentPayTo();
				$paymentPaidToInvDel = $paymentPaidToInvDel->whereYear('investigator_invoices.created_at', $year)->get()->toArray();
			} else if ($request->year == "last_3" || $request->year == "last_5") {
				if ($request->year == "last_3") {
					$from = date("Y", strtotime("-3 year"));
				} else {
					$from = date("Y", strtotime("-5 year"));
				}
				$to = date("Y");

				$paymentPaidToInvDel = InvestigatorInvoice::select(DB::raw('SUM(investigator_invoices.amount) a'), DB::raw('SUM(deliveryboy_invoices.amount) b'), DB::raw("DATE_FORMAT(investigator_invoices.created_at, '%b') y"), DB::raw('MONTH(investigator_invoices.created_at) month'), DB::raw('YEAR(investigator_invoices.created_at) year'))->leftJoin('deliveryboy_invoices', 'deliveryboy_invoices.investigation_id', '=', 'investigator_invoices.investigation_id')->where('investigator_invoices.status', 'paid')->having('year', '>=', $from)
				->having('year', '<=', $to)->orderBy('investigator_invoices.created_at', 'ASC')->groupby('y')->get()->toArray();
				foreach($paymentPaidToInvDel as $key => $val){
					unset($paymentPaidToInvDel[$key]['month']);
					unset($paymentPaidToInvDel[$key]['year']);
				}
			} else if ($request->year == "life_time") {
				$paymentPaidToInvDel = $this->paymentPayTo();
				$paymentPaidToInvDel = $paymentPaidToInvDel->get()->toArray();
			}
			foreach($paymentPaidToInvDel as $key => $value){
				$paymentPaidToInvDel[$key]['y'] = trans('general.month.'.$value['y']);
			}
			return response()->json([
				'status' => 'success',
				'paymentPaidToInvDel' => $paymentPaidToInvDel
			]);
		} else if ($request->chart == "invoice-amount-not-rev"){
			if ($request->year == "current" || $request->year == "previous") {
				if ($request->year == "current") {
					$year = date('Y');
				} else {
					$year = date("Y", strtotime("-1 year"));
				}
				$paymentNotRev = $this->paymentNotRev();
				$paymentNotRev = $paymentNotRev->whereYear('created_at', $year)->get()->toArray();
			} else if ($request->year == "last_3" || $request->year == "last_5") {
				if ($request->year == "last_3") {
					$from = date("Y", strtotime("-3 year"));
				} else {
					$from = date("Y", strtotime("-5 year"));
				}
				$to = date("Y");
				$paymentNotRev = PerformaInvoice::select(DB::raw('SUM(amount) a'), DB::raw("DATE_FORMAT(created_at, '%b') y"), DB::raw('MONTH(created_at) month'), DB::raw('YEAR(created_at) year'))->whereIn('status', ['pending','requested'])->having('year', '>=', $from)->having('year', '<=', $to)->orderBy('created_at', 'ASC')->groupby('y')->get()->toArray();
				foreach($paymentNotRev as $key => $val){
					unset($paymentNotRev[$key]['month']);
					unset($paymentNotRev[$key]['year']);
				}
			} else if ($request->year == "life_time") {
				$paymentNotRev = PerformaInvoice::select(DB::raw('SUM(amount) a'), DB::raw("DATE_FORMAT(created_at, '%b') y"), DB::raw('MONTH(created_at) month'), DB::raw('YEAR(created_at) year'))->whereIn('status', ['pending','requested'])->orderBy('created_at', 'ASC')->groupby('y')->get()->toArray();				
			}
			foreach($paymentNotRev as $key => $value){
				$paymentNotRev[$key]['y'] = trans('general.month.'.$value['y']);
			}
			return response()->json([
				'status' => 'success',
				'paymentNotRev' => $paymentNotRev,
			]);
		}
	}
	// data which needs to show on Time per case type
	public function getTimePerCaseTypeData()
	{
		return Product::select('products.name', DB::raw("concat(investigator_investigations.start_date, ' ', investigator_investigations.start_time) as investigation_start_at"), DB::raw("concat(investigator_investigations.completion_date,' ', investigator_investigations.completion_time) as investigation_end_at"))->join('investigations', 'investigations.type_of_inquiry', '=', 'products.id')->join('investigator_investigations', 'investigator_investigations.investigation_id', '=', 'investigations.id')->whereNotNull('investigator_investigations.completion_date')->whereNotNull('investigator_investigations.completion_time')->whereYear('investigator_investigations.created_at', date('Y'));
	}
	// data which needs to show on Employee efficiency chart
	public function getEmployeeEfficiencyData()
	{
		return Investigations::select(DB::raw("DATE_FORMAT(investigator_investigations.created_at, '%d-%m-%Y') created_at"), DB::raw('sum(investigator_investigations.inv_cost + investigator_investigations.doc_cost) as `charge`'), DB::raw('sum(investigations.inv_cost) as `inv_cost`'), DB::raw('MONTH(investigator_investigations.created_at) month'))->join('investigator_investigations', 'investigator_investigations.investigation_id', '=', 'investigations.id')->orderBy('investigator_investigations.created_at')->groupby('month');
	}
	// data needs to show for investigators monthly investigations
	public function getInvestigatorMonthlyInv()
	{
		return InvestigatorInvestigations::select(DB::raw("DATE_FORMAT(investigator_investigations.created_at, '%d-%m-%Y') created_at"), DB::raw('COUNT(investigator_investigations.id)  total'),  DB::raw('MONTH(investigator_investigations.created_at) month'))->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->orderBy('investigator_investigations.created_at', 'ASC')->groupby('month');
	}
	// data needs to show for deliveryboy monthly investigations
	public function getDeliveryboyMonthlyInv()
	{
		return DeliveryboyInvestigations::select(DB::raw("DATE_FORMAT(deliveryboy_investigations.created_at, '%d-%m-%Y') created_at"), DB::raw('COUNT(deliveryboy_investigations.id)  total'),  DB::raw('MONTH(deliveryboy_investigations.created_at) month'))->join('deliveryboys', 'deliveryboys.id', '=', 'deliveryboy_investigations.deliveryboy_id')->where('deliveryboys.user_id', Auth::id())->orderBy('deliveryboy_investigations.created_at', 'ASC')->orderBy('deliveryboy_investigations.created_at', 'ASC')->groupby('month');
	}
	// data needs to show for deliveryboy time per case type chart
	public function getDeliveryboyTimePerCaseTypeData()
	{
		return Product::select('products.name', DB::raw("concat(deliveryboy_investigations.start_date, ' ', deliveryboy_investigations.start_time) as investigation_start_at"), DB::raw("concat(deliveryboy_investigations.completion_date,' ', deliveryboy_investigations.completion_time) as investigation_end_at"))->join('investigations', 'investigations.type_of_inquiry', '=', 'products.id')->join('deliveryboy_investigations', 'deliveryboy_investigations.investigation_id', '=', 'investigations.id')->join('deliveryboys', 'deliveryboys.id', '=', 'deliveryboy_investigations.deliveryboy_id')->whereNotNull('deliveryboy_investigations.completion_date')->whereNotNull('deliveryboy_investigations.completion_time')->where('deliveryboys.user_id', Auth::id());
	}
	// data needs to show for deliveryboy efficiency chart
	public function getDeliveryboyEfficiencyChartData()
	{
		return Investigations::select(DB::raw("DATE_FORMAT(deliveryboy_investigations.created_at, '%d-%m-%Y') created_at"), DB::raw('sum(deliveryboy_investigations.charge) as `charge`'), DB::raw('sum(investigations.inv_cost) as `inv_cost`'), DB::raw('MONTH(deliveryboy_investigations.created_at) month'))->join('deliveryboy_investigations', 'deliveryboy_investigations.investigation_id', '=', 'investigations.id')->join('deliveryboys', 'deliveryboys.id', '=', 'deliveryboy_investigations.deliveryboy_id')->where('deliveryboys.user_id', Auth::id())->orderBy('deliveryboy_investigations.created_at')->groupby('month');
	}
	// systems monthly investigation
	public function monthlyInvestigation()
	{
		return Investigations::select(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') created_at"), DB::raw('COUNT(id)  total'),  DB::raw('MONTH(created_at) month'))->whereYear('created_at', date('Y'))->orderBy('created_at', 'DESC')->groupby('month')->get()->toArray();
	}
	// investigation per client/type/investigator report data
	public function investigationPer($type)
	{
		if($type == 'client'){
			return InvestigatorInvestigations::select('users.name', 'users.email', DB::raw('COUNT(investigator_investigations.investigator_id) as total'))->join('investigations', 'investigations.id', '=', 'investigator_investigations.investigation_id')->join('users', 'users.id', '=', 'investigations.user_id')->orderBy('total', 'desc')->groupBy('investigations.user_id');
		} else if($type == 'type') {
			return InvestigatorInvestigations::select('products.name', DB::raw('COUNT(investigator_investigations.investigator_id) as total'))->join('products', 'products.id', '=', 'investigator_investigations.type_of_inquiry')->orderBy('total', 'desc')->groupBy('investigator_investigations.type_of_inquiry');
		} else {
			return InvestigatorInvestigations::select('users.name', 'users.email', DB::raw('COUNT(investigator_investigations.investigator_id) as total'))->join('investigators', 'investigators.id', '=', 'investigator_investigations.investigator_id')->join('users', 'users.id', '=', 'investigators.user_id')->orderBy('total', 'desc')->groupBy('investigator_investigations.investigator_id');
		}
	}
	// investigation per client/type/deliveryboy report data
	public function deliveryPer($type)
	{
		if($type == 'client') {
			return DeliveryboyInvestigations::select('users.name', 'users.email', DB::raw('COUNT(deliveryboy_investigations.deliveryboy_id) as total'))->join('investigations', 'investigations.id', '=', 'deliveryboy_investigations.investigation_id')->join('users', 'users.id', '=', 'investigations.user_id')->orderBy('total', 'desc')->groupBy('investigations.user_id');
		} else if($type == 'type') {
			return DeliveryboyInvestigations::select('products.name', DB::raw('COUNT(deliveryboy_investigations.deliveryboy_id) as total'))->join('products', 'products.id', '=', 'deliveryboy_investigations.type_of_inquiry')->orderBy('total', 'desc')->groupBy('deliveryboy_investigations.type_of_inquiry');
		} else {
			return DeliveryboyInvestigations::select('users.name', 'users.email', DB::raw('COUNT(deliveryboy_investigations.deliveryboy_id) as total'))->join('deliveryboys', 'deliveryboys.id', '=', 'deliveryboy_investigations.deliveryboy_id')->join('users', 'users.id', '=', 'deliveryboys.user_id')->orderBy('total', 'desc')->groupBy('deliveryboy_investigations.deliveryboy_id');
		}
	}
	// return all product data which investigation is completed
	public function getInquiryTypeDate()
	{
		return Product::select('products.name', 'investigations.inv_cost')->join('investigations', 'investigations.type_of_inquiry', '=', 'products.id')->join('investigator_investigations', 'investigator_investigations.investigation_id', '=', 'investigations.id')->whereNotNull('investigator_investigations.completion_date')->whereNotNull('investigator_investigations.completion_time');
	}
	// all late investigations
	public function lateInvestigations()
	{
		return Investigations::with([
			'subjects' => function ($q) {
				$q->select(['id', 'investigation_id', 'family_name', 'first_name']);
			},
			'product' => function ($q) {
				$q->select(['id', 'name']);
			}
		]);
	}
	// return all un paid performa invoice
	public function getUnPaidPerformaInv()
	{
		$lateInvoices = [];
		$invoices = PerformaInvoice::with('investigation')->whereIn('status', ['pending','on-hold'])->get();
		foreach($invoices as $invoice){
			$client = Client::where('id', $invoice['client_id'])->first();
			if($client->paymentTerm->term_name == 'Immediately') {
				$invoiceDate = $invoice->created_at;
				if(($invoiceDate->diffInDays(Carbon::now())) > 0){
					array_push($lateInvoices, $invoice);
				}
			} else if($client->paymentTerm->term_name == 'Immediately + 15') {
				$invoiceDate = $invoice->created_at;
				if(($invoiceDate->diffInDays(Carbon::now())) > 15){
					array_push($lateInvoices, $invoice);
				}
			} else if($client->paymentTerm->term_name == 'Immediately + 30') {
				$invoiceDate = $invoice->created_at;
				if(($invoiceDate->diffInDays(Carbon::now())) > 30){
					array_push($lateInvoices, $invoice);
				}
			} else if($client->paymentTerm->term_name == 'Immediately + 60') {
				$invoiceDate = $invoice->created_at;
				if(($invoiceDate->diffInDays(Carbon::now())) > 60){
					array_push($lateInvoices, $invoice);
				}
			} else if($client->paymentTerm->term_name == 'Immediately + 90') {
				$invoiceDate = $invoice->created_at;
				if(($invoiceDate->diffInDays(Carbon::now())) > 90){
					array_push($lateInvoices, $invoice);
				}
			}
		}
		collect($lateInvoices)->map(function($lateInvoice){
			$lateInvoice->route = route('invoice.show', [Crypt::encrypt($lateInvoice->id),'pinvoice']);
			$lateInvoice->price = trans('general.money_symbol').$lateInvoice->amount;
			return $lateInvoice;
		});
		return $lateInvoices;
	}
	// return amount of payment which needs to pay to employees
	public function paymentPayTo()
	{
		return InvestigatorInvoice::select(DB::raw('SUM(investigator_invoices.amount) a'), DB::raw('SUM(deliveryboy_invoices.amount) b'), DB::raw("DATE_FORMAT(investigator_invoices.created_at, '%b') y"))->leftJoin('deliveryboy_invoices', 'deliveryboy_invoices.investigation_id', '=', 'investigator_invoices.investigation_id')->where('investigator_invoices.status', 'paid')->orderBy('investigator_invoices.created_at', 'ASC')->groupby('y');
	}
	// payment not recived from cutomer/client
	public function paymentNotRev()
	{
		return PerformaInvoice::select(DB::raw('SUM(amount) a'), DB::raw("DATE_FORMAT(created_at, '%b') y"))->whereIn('status', ['pending','requested'])->orderBy('created_at', 'ASC')->groupby('y');
	}
	// client list which status is pending
	public function pendingClients()
	{
		$pendingClients = User::whereHas(
			'user_type',
			function ($query) {
				return $query->where('type_name', env('USER_TYPE_CLIENT'));
			}
		)->with([
			'client' => function ($q) {
				$q->select(['id', 'user_id', 'client_type_id', 'printname', 'legal_entity_no', 'website']);
			},
			'client.client_type' => function ($q) {
				$q->select(['id', 'type_name', 'hr_type_name']);
			},
		])->whereIn('status', ['pending', 'disabled'])->orderBy('created_at', 'desc')->get();
		$pendingClients->map(function($pendingClient){
			$pendingClient->route = route('client.showApproveForm', ['userId' => Crypt::encrypt($pendingClient->id)]);
			return $pendingClient;
		});
		return $pendingClients;
	}
	// investigator list which status is pending
	public function pendingInvestigator()
	{
		$pendingInvestigators = User::whereHas(
			'user_type',
			function ($query) {
				return $query->where('type_name', env('USER_TYPE_INVESTIGATOR'));
			}
		)->with([
			'specializations' => function ($q) {
				$q->select(['name', 'hr_name']);
			},
		])->whereIn('status', ['pending', 'disabled'])->orderBy('created_at', 'desc')->get();
		$pendingInvestigators->map(function($pendingInvestigator){
			$html = '<ul class="speclist-ul">';
			if(App::isLocale('hr')){
				$specArr = !empty($pendingInvestigator->specializations) ? array_column($pendingInvestigator->specializations->toArray(), 'hr_name') : '';
			} else {
				$specArr = !empty($pendingInvestigator->specializations) ? array_column($pendingInvestigator->specializations->toArray(), 'name') : '';
			}
			if ($specArr) {
				foreach ($specArr as $spec) {
					$html .= '<li>' . ucwords($spec) . '</li>';
				}
			}
			$html .= '</ul>';
			$pendingInvestigator->specialization = $html;
			$pendingInvestigator->route = route('investigator.showApproveForm', ['userId' => Crypt::encrypt($pendingInvestigator->id)]);
			return $pendingInvestigator;
		});
		return $pendingInvestigators;
	}
	// deliveryboy list which status is pending
	public function pendingDeliveryboy()
	{
		$pendingDeliveryBoys = User::whereHas(
			'user_type',
			function ($query) {
				return $query->where('type_name', env('USER_TYPE_DELIVERY_BOY'));
			}
		)->with([
			'delivery_areas' => function ($q) {
				$q->select(['area_name', 'hr_area_name']);
			}
		])->whereIn('status', ['pending', 'disabled'])->orderBy('created_at', 'desc')->get();
		$pendingDeliveryBoys->map(function($pendingDeliveryBoy){
			$specHtml = '';
			if(config('app.locale') == 'hr'){
				$specArr =  !empty($pendingDeliveryBoy->delivery_areas) ? array_column($pendingDeliveryBoy->delivery_areas->toArray(), 'hr_area_name') : '';
				if ($specArr) {
					foreach ($specArr as $spec) {
						$specHtml .= '<span class="badge dt-badge badge-info">' . ucwords($spec) . '</span>&nbsp;';
					}
				}
			} else {
				$specArr =  !empty($pendingDeliveryBoy->delivery_areas) ? array_column($pendingDeliveryBoy->delivery_areas->toArray(), 'area_name') : '';

				if ($specArr) {
					foreach ($specArr as $spec) {
						$specHtml .= '<span class="badge dt-badge badge-info">' . ucwords($spec) . '</span>&nbsp;';
					}
				}
			}
			$pendingDeliveryBoy->deliveryAreas = $specHtml;
			$pendingDeliveryBoy->route = route('deliveryboy.showApproveForm', ['userId' => Crypt::encrypt($pendingDeliveryBoy->id)]) . '" class="dt-btn-action" title="' . trans('general.approve');
			return $pendingDeliveryBoy;
		});
		return $pendingDeliveryBoys;
	}
	// all open investigations list
	public function openIvestigations()
	{
		$finalInvn = [];
		$openInvns = Investigations::where('status', 'Open')->pluck('id')->toArray();			
		if(!empty($openInvns)){
			$invTrans = InvestigationTransition::whereIn('investigation_id', $openInvns)->whereRaw("created_at >= ( CURDATE() - INTERVAL 4 DAY )")->pluck('investigation_id')->toArray();
			if(!empty($invTrans)){
				$finalInvn = array_diff($openInvns, $invTrans);
			}
		}
		return $finalInvn;
	}
	// complaint report per client
	public function complaintPerClient()
	{
		return Tickets::select(DB::raw('COUNT(tickets.id) total_complaint'), 'users.name')->join('users', 'users.id', '=', 'tickets.user_id')->where('tickets.type', 'Complaint')->whereMonth('tickets.created_at', date('m'))->groupby('tickets.user_id')->get()->toArray();
	}
	// investigator's efficiency data
	public function getInvestigatorEfficiencyData()
	{
		return Investigations::select(DB::raw("DATE_FORMAT(investigator_investigations.created_at, '%d-%m-%Y') created_at"), DB::raw('sum(investigator_investigations.inv_cost + investigator_investigations.doc_cost) as `charge`'), DB::raw('sum(investigations.inv_cost) as `inv_cost`'), DB::raw('MONTH(investigator_investigations.created_at) month'), DB::raw('YEAR(investigator_investigations.created_at) year'))->join('investigator_investigations', 'investigator_investigations.investigation_id', '=', 'investigations.id')->orderBy('investigator_investigations.created_at')->groupby('month');
	}
	// deliveryboy's efficiency data
	public function getDeliveryboyEfficiencyData()
	{
		return Investigations::select(DB::raw("DATE_FORMAT(deliveryboy_investigations.created_at, '%d-%m-%Y') created_at"), DB::raw('sum(deliveryboy_investigations.charge) as `charge`'), DB::raw('sum(investigations.inv_cost) as `inv_cost`'), DB::raw('MONTH(deliveryboy_investigations.created_at) month'), DB::raw('YEAR(deliveryboy_investigations.created_at) year'))
		->join('deliveryboy_investigations', 'deliveryboy_investigations.investigation_id', '=', 'investigations.id')->join('deliveryboys', 'deliveryboys.id', '=', 'deliveryboy_investigations.deliveryboy_id')->where('deliveryboys.user_id', Auth::id())->orderBy('deliveryboy_investigations.created_at')->groupby('month');
	}
}
