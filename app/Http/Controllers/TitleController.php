<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\AssignedInvestigator;
use App\ClientProduct;
use App\ContactTypes;
use App\Country;
use App\Helpers\AppHelper;
use App\Investigations;
use App\InvestigationTransition;
use App\InvestigatorBankDetail;
use App\InvestigatorInvestigations;
use App\InvestigatorProduct;
use App\Investigators;
use App\InvestigatorSpecilization;
use App\Mail\EmailClientApproval;
use App\Mail\EmailVerify;
use App\PaymentMode;
use App\PaymentTerm;
use App\Product;
use App\Specialization;
use App\User;
use App\UserAddress;
use App\UserEmail;
use App\UserPhone;
use App\UserTypes;
use App\TtileCategory;


use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Crypt;
use View, DB, Log;
use Auth;
use App\Category;
use App\Type;
use App\Title;

class TitleController extends Controller
{
        /**
         * Generate and return listing of the investigators
         * @return mixed
         * @throws \Exception
         */
        public function titleList()
        {
                
            $titles = Title::with([
                'titlecategories' => function ($q) {
                    $q->select(['id', 'title_id','category_id'])->with([
                        'categories' => function ($query) {
                            $query->select(['id', 'name','hr_name']);
                        }
                    ]);
                },
                
                'categories' => function ($q) {
                    $q->select(['name', 'hr_name']);
                },
            ])
            ->orderBy('id', 'desc');
    
            $result = DataTables::of($titles)

                ->addColumn('categories', function ($titles) {
    
                    $html = '<ul class="speclist-ul">';
                    if(App::isLocale('hr')){
                        $catArr = !empty($titles->categories) ? array_column($titles->categories->toArray(), 'name') : '';
                    } else {
                        $catArr = !empty($titles->categories) ? array_column($titles->categories->toArray(), 'name') : '';
                    }
    
                    if ($catArr) {
                        foreach ($catArr as $cat) {
                            $html .= '<li>' . ucwords($cat) . '</li>';
                        }
                    }
                    $html .= '</ul>';
    
                    return $html;
                })
                ->addColumn('confirmation_status', function ($titles) {
    
                    $isadminconfirmed = $titles->isadminconfirmed;
                    $html = '';
    
                    // $status = InvestigatorInvestigations::whereHas('investigation')
                    //     ->select(
                    //         'investigator_id',
                    //         'investigation_id',
                    //         \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN status = 'Completed With Findings' OR status = 'Report Accepted' OR status = 'Final Report Submitted' THEN 1 else 0 end) AS total_completed"),
                    //         DB::raw("SUM(CASE WHEN status NOT IN ('Completed With Findings', 'Report Accepted', 'Final Report Submitted', 'Completed Without Findings', 'Report Declined') THEN 1 else 0 end) AS total_open")
                    //     )
                    //     ->where('investigator_id', $titles->investigator->id)
                    //     ->first();
    
                    if ($isadminconfirmed == 1) {
                        $status = '<span class="badge dt-badge badge-success">' . ucwords('Approved') . '</span>';
                    } else {
                        $status = '<span class="badge dt-badge badge-warning">' . ucwords('Pending') . '</span>';
                    }
                    
                    // $active = $status->total_open ?? 0;
                    // $completed = $status->total_completed ?? 0;
    
                    $html .= "{$status}";
                    // $html .= "<li>".trans('form.investigation_status.Completed')." ({$completed})</li>";
    
                    $html .= '';
    
                    return $html;
                })
                
                ->editColumn('status', function ($titles) {
                    if ($titles->isadminconfirmed) {
                        if ($titles->isadminconfirmed == 1) {
                            return '<td>
                                <span class="badge dt-badge badge-success">' . ucwords('Approved') . '</span>
                            </td>';
                        } else if ($titles->status == 0) {
                            return '<td>
                                <span class="badge dt-badge badge-warning">' . ucwords('Pending') . '</span>
                            </td>';
                        }
                    }
    
                    return "";
                })
                ->addColumn('action', function ($titles) {
                    $btns = "<span class='d-inline-flex'>";
                    
    
                        if ($titles->isadminconfirmed == 0 ) {
                            // $btns .= '<a href="' . route('title.showApproveForm', ['titleId' => Crypt::encrypt($titles->id)]) . '" class="dt-btn-action" title="' . trans('general.approve') . '">
                            //     <i class="mdi mdi-account-check mdi-18px"></i>
                            // </a>';
                            $btns .= '<a href="javascript:void(0)" id="change_title_status" class="dt-btn-action" title="Approve" data-id="' . $titles->id . '"">
                            <i class="fas fa-user mdi-18px"></i>
                            </a>';
                            
                        }
                       
                        
    
                        // if ($titles->isadminconfirmed == 1) {
                        //     $btns .= '<a href="' . route('investigator.showApproveForm', ['userId' => Crypt::encrypt($titles->id)]) . '" class="dt-btn-action" title="' . trans('form.edit_payment_details') . '">
                        //         <i class="mdi mdi-content-save-edit-outline mdi-18px"></i>
                        //     </a>';
                        // }
    
                        // if ($titles->isadminconfirmed == 1) {
                        //     $btns .= '<a href="javascript:void(0)" class="dt-btn-action" title="' . trans('general.disable') . '" onclick="changeStatus(\'disabled\',' . $titles->id . ')">
                        //         <i class="mdi mdi-account-off mdi-18px"></i>
                        //     </a>';
                        // }
    
                        $btns .= '<a href="' . route('title.edit', ['titleId' => Crypt::encrypt($titles->id)]) . '" class="dt-btn-action" title="' . trans('general.edit') . '">
                            <i class="fas fa-edit mdi-18px"></i>
                        </a>';
                    
                        
                        $btns .= '<a href="javascript:void(0)" id="deletetitle" class="dt-btn-action" title="' . trans('general.delete') . '" data-id="' . $titles->id . '"">
                                <i class="fas fa-trash mdi-18px"></i>
                        </a>';
                      
                        $btns .= '<a href="' . route('title.detail', ['titleId' => Crypt::encrypt($titles->id)]) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                            <i class="fas fa-file-alt mdi-18px"></i>
                        </a>';
            
                    $btns .= '</span>';
    
                    return $btns;
                })
                ->rawColumns(['categories','confirmation_status','status','action'])
                ->make(true);
    
            return $result;
        }
    
        /**
         * Display a listing of the resource.
         *
         * @return \View
         */
        public function index()
        {
            $topInvestigators = InvestigatorInvestigations::with(['investigator:id,user_id', 'investigator.user:id,name,email'])
                ->select(
                    'investigator_id',
                    \Illuminate\Support\Facades\DB::raw("SUM(CASE WHEN status = 'Completed With Findings' OR status = 'Report Accepted' OR status = 'Final Report Submitted' THEN 1 else 0 end) AS total_completed"),
                    DB::raw("SUM(CASE WHEN status NOT IN ('Completed With Findings', 'Report Accepted', 'Final Report Submitted', 'Completed Without Findings', 'Report Declined') THEN 1 else 0 end) AS total_open")
                )
                ->groupBy('investigator_id')
                ->orderBy('total_completed', 'DESC')
                ->orderBy('total_open', 'DESC')
                ->limit(4)
                ->get();
    
            $activeInvestigators = DB::select("SELECT i.id, u.name, u.email,
            SUM(case when ii.status not in ('Completed With Findings', 'Report Accepted', 'Final Report Submitted', 'Completed Without Findings', 'Report Declined') then 1 else 0 end) AS total_open,
            SUM(case when ii.status = 'Completed With Findings' or ii.status = 'Report Accepted' or ii.status = 'Final Report Submitted' then 1 else 0 end) AS total_completed,
            count(ii.id) AS all_assigned,
            round(SUM(case when ii.status = 'Completed With Findings' or ii.status = 'Report Accepted' or ii.status = 'Final Report Submitted' then 1 else 0 end) * 100 / count(ii.id), 2) as percentage
            from investigators i
            left join investigator_investigations ii on ii.investigator_id = i.id
            left join users u on u.id = i.user_id
            group by i.id  
            having total_open = 0  and all_assigned > 0
            order by percentage desc LIMIT 4");


            
            return view('titles.index', compact('topInvestigators', 'activeInvestigators'));
        }
    
        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $userType = UserTypes::where('type_name', env('USER_TYPE_ADMIN'))->pluck('id');
            $typeid   = $userType[0];
            $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
            $specializations = Specialization::select('name', 'id', 'hr_name')->orderBy('id', 'ASC')->get();
            $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
    
            $categories = Category::orderBy('name','asc')->get();
            $types = Type::orderBy('type','asc')->get();
            return view('titles.create', compact('types','categories','typeid', 'countries', 'specializations', 'contacttypes'));
        }
    
        /**
         * Generate unique verification token
         */
        public function generateVerifyToken()
        {
            // Generate new token
            return Str::random(10) . sha1(time());
        }
    
        /**
         * Get a validator for an incoming registration request.
         *
         * @param  array  $data
         * @return \Illuminate\Contracts\Validation\Validator
         */
        public function titleValidator(array $data)
        {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                'file_path' => ['required'],
                'categories' => ['required'],
            ]);
        }
    
        public function investigatorUpdateValidator(array $data, $userId)
        {
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:255'],
                // 'email' => 'unique:users,email,' . $userId,
                'categories' => ['required'],
            ]);
        }
    
        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            $this->titleValidator($request->all())->validate();
            dd($request->all());
            $user_id = $request->user_id;
           
            $titleData = [
                'name' => $request->name,
                'created_by_user_id' => $user_id,
                'isadminconfirmed' => 1
            ];

            $title_id  = DB::table('titles')->insertGetId($titleData);

            if(isset($request->file_path))
            {
                
                foreach($request->file_path as $file)
                {
                   
                    $file_name = $file;
                    $file_name = time(). $file_name->getClientOriginalName();
                    $file->move('uploads', $file_name);
                    $file_path = 'uploads/'. $file_name;
                  
                    DB::table('title_files')->insert([
                        'title_id' => $title_id,
                        'file_path' => $file_path
                    ]);

                }
            }

            $title_categories = $request->categories;

            foreach ($title_categories as $key => $value) {
                    DB::table('title_categories')->insert([
                        'title_id' => $title_id,
                        'category_id' => $value
                    ]
                );
            }

            // $owner_mail = $request->owner_mail;
           
            // $chk_owner = DB::table('contributors')->where('email',$owner_mail)->exists();
            
            // $owner_details = [];
            // if($chk_owner){
            //     $owner = DB::table('contributors')->where('email',$owner_mail)->first();

            //     DB::table('title_owners')->insert([
            //         'title_id' => $title_id,
            //         'contributor_id' => $owner->id,
            //     ]);

            // }else{
            //     $owner_details = [
            //         'first_name' => $request->first_name,
            //         'last_name' => $request->last_name,
            //         'email' => $request->owner_mail,
            //     ];

              

            //     $contributor_id = DB::table('contributors')->insertGetId($owner_details);
            //     DB::table('title_owners')->insert([
            //         'title_id' => $title_id,
            //         'contributor_id' => $contributor_id,
            //     ]);
            //     $contributor_types = $request->types;

            //     foreach ($contributor_types as $key => $value) {
            //         DB::table('contributor_types')->insert(
            //             [
            //                 'contributor_id' => $contributor_id,
            //                 'type_id' => $value
            //             ]
            //         );
            //     }
            // }

            if (isset($request->owner)) {
               
                $owners = $request->owner;
                foreach ($owners as $key => $value) {
                    $chk_owner = DB::table('contributors')->where('email',$value['owner_email1'])->exists();
                    
                    if ($chk_owner) {
                        $upowner = DB::table('contributors')->where('email',$value['owner_email1'])->first();
                        DB::table('title_owners')->insert([
                            'title_id' => $title_id,
                            'contributor_id' => $upowner->id,
                        ]);
                    } else {
                        $c_owner_details = [
                            'first_name' => $value['owner_first_name1'],
                            'last_name' => $value['owner_last_name1'],
                            'email' => $value['owner_email1']
                        ];
        
                        $c_owner_id = DB::table('contributors')->insertGetId($c_owner_details);
        
                        DB::table('title_owners')->insert([
                            'title_id' => $title_id,
                            'contributor_id' => $c_owner_id,
                        ]);
                        
                        foreach ($value['owner_type1'] as $key => $v) {
                            DB::table('contributor_types')->insert(
                                [
                                    'contributor_id' => $c_owner_id,
                                    'type_id' => $v
                                ]
                            );
                        }

                       
                    }
                }
            }
            
            if (isset($request->contributor)) {
               
                $contributors = $request->contributor;
                foreach ($contributors as $key => $value) {
                    $chk_contributor = DB::table('contributors')->where('email',$value['email1'])->exists();
                    
                    if ($chk_contributor) {
                        $upcontributor = DB::table('contributors')->where('email',$value['email1'])->first();
                        DB::table('title_contributors')->insert([
                            'title_id' => $title_id,
                            'contributor_id' => $upcontributor->id,
                        ]);
                    } else {
                        $c_contributor_details = [
                            'first_name' => $value['first_name1'],
                            'last_name' => $value['last_name1'],
                            'email' => $value['email1']
                        ];
        
                        $c_contributor_id = DB::table('contributors')->insertGetId($c_contributor_details);
        
                        DB::table('title_contributors')->insert([
                            'title_id' => $title_id,
                            'contributor_id' => $c_contributor_id,
                        ]);
                        
                        foreach ($value['type1'] as $key => $v) {
                            DB::table('contributor_types')->insert(
                                [
                                    'contributor_id' => $c_contributor_id,
                                    'type_id' => $v
                                ]
                            );
                        }
                    }
                }
            }
        
    
            // $invstData = Investigators::create([
            //     'user_id' => $userData->id,
            //     'family' => $request->family,
            //     'idnumber' => $request->idnumber,
            //     'website' => $request->website,
            //     'dob' => date("Y-m-d", strtotime($request->dob))
            // ]);
    
            // if ($request->filled('name_of_bank')) {
            //     InvestigatorBankDetail::create([
            //         'investigator_id' => $invstData->id,
            //         'name' => $request->name_of_bank,
            //         'company' => $request->company,
            //         'number' => $request->bank_number,
            //         'branch_name' => $request->branch_name,
            //         'branch_number' => $request->branch_no,
            //         'account_no' => $request->account_no,
            //     ]);
            // }
    
            // if ($request->filled('specializations')) {
            //     foreach ($request->input('specializations') as $value) {
            //         InvestigatorSpecilization::create([
            //             'user_id' => $userData->id,
            //             'specialization_id' => $value,
            //         ]);
            //     }
            // }
    
            // $arr_address = $arr_email = $arr_phones = $arr_mobile = $arr_fax = [];
    
            // if ($request->filled('address')) {
            //     foreach ($request->input('address') as $address) {
            //         $address['user_type_id'] = $request->type_id;
            //         $address['address_type'] = (($address['address_type'] == 'Other' || $address['address_type'] == 'Contact') && $address['other_text'] != '') ? $address['other_text'] : $address['address_type'];
            //         $address['user_id'] = $userData->id;
            //         unset($address['other_text']);
            //         $arr_address[] = $address;
            //     }
            //     UserAddress::insert($arr_address);
            // }
    
            // if ($request->filled('otheremail')) {
            //     foreach ($request->input('otheremail') as $value) {
            //         if(empty($value['email'])){
            //             continue;
            //         }
            //         $value['user_type_id'] = $request->type_id;
            //         $value['email_type'] = (($value['email_type'] == 'Other' || $value['email_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['email_type'];
            //         $value['user_id'] = $userData->id;
            //         $value['value'] = $value['email'];
            //         unset($value['other_text']);
            //         unset($value['email']);
            //         $arr_email[] = $value;
            //     }
            //     UserEmail::insert($arr_email);
            // }
    
            // if ($request->filled('otherphone')) {
            //     foreach ($request->input('otherphone') as $value) {
            //         if(empty($value['phone'])){
            //             continue;
            //         }
            //         $value['user_type_id'] = $request->type_id;
            //         $value['phone_type'] = (($value['phone_type'] == 'Other' || $value['phone_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['phone_type'];
            //         $value['type'] = 'phone';
            //         $value['user_id'] = $userData->id;
            //         $value['value'] = $value['phone'];
            //         unset($value['other_text']);
            //         unset($value['phone']);
            //         $arr_phones[] = $value;
            //     }
            //     UserPhone::insert($arr_phones);
            // }
    
            // if ($request->filled('othermobile')) {
            //     foreach ($request->input('othermobile') as $value) {
            //         if(empty($value['mobile'])){
            //             continue;
            //         }
            //         $value['user_type_id'] = $request->type_id;
            //         $value['phone_type'] = (($value['mobile_type'] == 'Other' || $value['mobile_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['mobile_type'];
            //         $value['type'] = 'mobile';
            //         $value['user_id'] = $userData->id;
            //         $value['value'] = $value['mobile'];
            //         unset($value['other_text']);
            //         unset($value['mobile_type']);
            //         unset($value['mobile']);
            //         $arr_mobile[] = $value;
            //     }
            //     UserPhone::insert($arr_mobile);
            // }
    
            // if ($request->filled('otherfax')) {
            //     foreach ($request->input('otherfax') as $value) {
            //         if(empty($value['fax'])){
            //             continue;
            //         }
            //         $value['user_type_id'] = $request->type_id;
            //         $value['phone_type'] = (($value['fax_type'] == 'Other' || $value['fax_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['fax_type'];
            //         $value['type'] = 'fax';
            //         $value['user_id'] = $userData->id;
            //         $value['value'] = $value['fax'];
            //         unset($value['other_text']);
            //         unset($value['fax_type']);
            //         unset($value['fax']);
            //         $arr_fax[] = $value;
            //     }
            //     UserPhone::insert($arr_fax);
            // }
    
            // $invtransdata = [
            //     'event' => 'investigator_created',
            //     'data' => json_encode(array('data' => array('id' => $userData->id, 'type' => 'investigator_registration'))),
            // ];
            // InvestigationTransition::addTransion($invtransdata, NULL);
    
            // $mailVerify = Mail::to($userData->email)->queue(new EmailVerify($userData));
    
            return redirect('/titles')->with('success', 'You have successfully created the Title');
        }
    
        /**
         * Show the Investigator Detail Data
         *
         * @param  int  $userid = Crypt::encrypt($userId)
         * @return \Illuminate\Http\Response
         */
        public function show($titleId)
        {

            try {
                $userType = UserTypes::where('type_name', env('USER_TYPE_ADMIN'))->pluck('id');
                // $userId = Crypt::decrypt($userId);
                $userId = 1;
                $typeid   = $userType[0];
                $user = User::where('id', '=', $userId)
                    ->where('type_id', '=', $typeid)->first();

                if (!$user) {
                    return back()->with('warning', trans('general.no_record_found'));
                }

                $title_id = Crypt::decrypt($titleId);
                $title = Title::where('id', '=', $title_id)->first();
                if (!$title) {
                    return back()->with('warning', trans('general.no_record_found'));
                }

                return view('titles.show', compact('user','title'));
            } catch (Exception $exception) {
                return back()->with('warning', trans('form.registration.something_went_wrong'));
            }
        }
    
        /**
         * Show the form for editing the specified resource.
         *
         * @param  \App\Investigators  $investigators
         * @return \Illuminate\Http\Response
         */
        public function edit($titleId)
        {
         
            $titleId = Crypt::decrypt($titleId);
            // $userId = Crypt::decrypt($userId);
            $title = Title::find($titleId);



            $userId =1;
            $userType = UserTypes::where('type_name', env('USER_TYPE_ADMIN'))->pluck('id');
            $typeid   = $userType[0];
            $countries = Country::select('id', 'en_name', 'code', 'hr_name')->get();
            $specializations = Specialization::select('name', 'id', 'hr_name')->orderBy('id', 'ASC')->get();
            $contacttypes = ContactTypes::select('type_name', 'id', 'hr_type_name')->where('type', env('CONTACT_TYPE_DEFAULT'))->orderBy('id', 'ASC')->get();
            $client = User::find($userId);
    
            $phones = $mobiles = $faxes = $specs = [];
    
            foreach ($client->userPhones as $phone) {
                if ($phone->type == 'phone') {
                    $phones[] = $phone;
                } else if ($phone->type == 'mobile') {
                    $mobiles[] = $phone;
                } else {
                    $faxes[] = $phone;
                }
            }
    
            $specs = array_column($client->specializations->toArray(), 'id');

            $cats = array_column($title->categories->toArray(), 'id');

            $categories = Category::orderBy('name','asc')->get();
       
            $types = Type::orderBy('type','asc')->get();

            $title_owner = $title->owner[0];

            dd($title->owner[0]);

            return view('titles.edit', compact('title_owner','cats','title','categories','types','typeid', 'countries', 'specializations', 'client', 'phones', 'mobiles', 'faxes', 'specs', 'contacttypes'));
        }
    
        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \App\Investigators  $investigators
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $userId)
        {
            $this->investigatorUpdateValidator($request->all(), $userId)->validate();
    
            $user = User::with('client')->find($userId);
    
            $userData = $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
    
            $investigator = $user->investigator;
    
            $invstData = $investigator->update([
                'user_id' => $userId,
                'family' => $request->family,
                'idnumber' => $request->idnumber,
                'website' => $request->website,
                'dob' => date("Y-m-d", strtotime($request->dob))
            ]);
    
            $investigatorBankDetail = $investigator->investigatorBankDetail;
    
            if ($request->filled('name_of_bank') && !is_null($investigatorBankDetail)) {
                $investigatorBankDetail->update([
                    'investigator_id' => $investigator->id,
                    'name' => $request->name_of_bank,
                    'company' => $request->company,
                    'number' => $request->bank_number,
                    'branch_name' => $request->branch_name,
                    'branch_number' => $request->branch_no,
                    'account_no' => $request->account_no,
                ]);
            }
    
            if ($request->filled('specializations')) {
                //            InvestigatorSpecilization::where('user_id', $userId)->delete();
    
                $user->specializations()->detach();
    
                foreach ($request->input('specializations') as $value) {
                    InvestigatorSpecilization::create([
                        'user_id' => $userId,
                        'specialization_id' => $value,
                    ]);
                }
            }
    
            $arr_address = $arr_email = $arr_phones = $arr_mobile = $arr_fax = [];
    
            if ($request->filled('address')) {
                UserAddress::where('user_id', $userId)->delete();
                foreach ($request->input('address') as $address) {
                    $address['user_type_id'] = $request->type_id;
                    $address['address_type'] = (($address['address_type'] == 'Other' || $address['address_type'] == 'Contact') && $address['other_text'] != '') ? $address['other_text'] : $address['address_type'];
                    $address['user_id'] = $userId;
                    unset($address['other_text']);
                    $arr_address[] = $address;
                }
                UserAddress::insert($arr_address);
            }
    
            if ($request->filled('otheremail')) {
                UserEmail::where('user_id', $userId)->delete();
                foreach ($request->input('otheremail') as $value) {
                    if(empty($value['email'])){
                        continue;
                    }
                    $value['user_type_id'] = $request->type_id;
                    $value['email_type'] = (($value['email_type'] == 'Other' || $value['email_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['email_type'];
                    $value['user_id'] = $userId;
                    $value['value'] = $value['email'];
                    unset($value['other_text']);
                    unset($value['email']);
                    $arr_email[] = $value;
                }
                UserEmail::insert($arr_email);
            }
    
            if ($request->filled('otherphone')) {
                UserPhone::where('user_id', $userId)->where('type', 'phone')->delete();
                foreach ($request->input('otherphone') as $value) {
                    if(empty($value['phone'])){
                        continue;
                    }
                    $value['user_type_id'] = $request->type_id;
                    $value['phone_type'] = (($value['phone_type'] == 'Other' || $value['phone_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['phone_type'];
                    $value['type'] = 'phone';
                    $value['user_id'] = $userId;
                    $value['value'] = $value['phone'];
                    unset($value['other_text']);
                    unset($value['phone']);
                    $arr_phones[] = $value;
                }
                UserPhone::insert($arr_phones);
            }
    
            if ($request->filled('othermobile')) {
                UserPhone::where('user_id', $userId)->where('type', 'mobile')->delete();
                foreach ($request->input('othermobile') as $value) {
                    if(empty($value['mobile'])){
                        continue;
                    }
                    $value['user_type_id'] = $request->type_id;
                    $value['phone_type'] = (($value['mobile_type'] == 'Other' || $value['mobile_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['mobile_type'];
                    $value['type'] = 'mobile';
                    $value['user_id'] = $userId;
                    $value['value'] = $value['mobile'];
                    unset($value['other_text']);
                    unset($value['mobile_type']);
                    unset($value['mobile']);
                    $arr_mobile[] = $value;
                }
                UserPhone::insert($arr_mobile);
            }
    
            if ($request->filled('otherfax')) {
                UserPhone::where('user_id', $userId)->where('type', 'fax')->delete();
                foreach ($request->input('otherfax') as $value) {
                    if(empty($value['fax'])){
                        continue;
                    }
                    $value['user_type_id'] = $request->type_id;
                    $value['phone_type'] = (($value['fax_type'] == 'Other' || $value['fax_type'] == 'Contact') && $value['other_text'] != '') ? $value['other_text'] : $value['fax_type'];
                    $value['type'] = 'fax';
                    $value['user_id'] = $userId;
                    $value['value'] = $value['fax'];
                    unset($value['other_text']);
                    unset($value['fax_type']);
                    unset($value['fax']);
                    $arr_fax[] = $value;
                }
                UserPhone::insert($arr_fax);
            }
    
            return redirect('/investigators')->with('success', trans('form.registration.investigator.investigator_updated'));
        }
    
        /**
         * Remove the specified resource from storage.
         *
         * @param  \App\Investigators  $investigators
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            try {
                Title::find($id)->delete();
    
                return response()->json([
                    'status' => 'success',
                    'message' => 'You have successfully deleted the record',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }
        }
    
        public function change_status($id){
            try {
                $title = Title::find($id);
    
                if($title){
                    if ($title->isadminconfirmed == 0) {
                        DB::table('titles')->where('id',$title->id)->update(['isadminconfirmed' => 1]);
                    } else {
                        DB::table('titles')->where('id',$title->id)->update(['isadminconfirmed' => 0]);
                    }
                    
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'You have successfully changed the status',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }
        }
        public function deleteMultiple(Request $request)
        {
    
            try {
                if (!empty($request->ids)) {
                    Title::whereIn('id', $request->ids)->delete();
    
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Titles has been deleted',
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => trans('general.something_wrong'),
                        'exception' => trans('general.something_wrong'),
                    ]);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }
        }
    
        public function changeStatus(Request $request)
        {
            try {
                if (!empty($request->id) && !empty($request->action)) {
                    $user = User::find($request->id);
    
                    $user->status = $request->action;
    
                    $user->save();
    
                    return response()->json([
                        'status' => 'success',
                        'message' => trans('form.registration.investigator.confirm_statuschanged'),
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => trans('general.something_wrong'),
                        'exception' => trans('general.something_wrong'),
                    ]);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }
        }
    
        public function searchInvestigations($investigatorId, $search)
        {
            $investigatorsList = null;
            $assignedInvestigations = InvestigatorInvestigations::where('investigator_id', $investigatorId)->get(['investigation_id'])->toArray();
    
            $investigations = Investigations::with(['subjects', 'client_type', 'product', 'user'])
                ->whereHas('subjects', function ($query) use ($search) {
                    $query->where('family_name', 'like', '%' . $search . '%')->orWhere('first_name', 'like', '%' . $search . '%')->orWhere('id_number', 'like', '%' . $search . '%');
                })
                ->orWhereHas('client_type', function ($query) use ($search) {
                    $query->where('type_name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('product', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('user_inquiry', 'like', '%' . $search . '%')
                ->orWhere('work_order_number', 'like', '%' . $search . '%')
                ->orWhere('ex_file_claim_no', 'like', '%' . $search . '%')
                ->orWhere('claim_number', 'like', '%' . $search . '%')
                ->get();
    
            $investigationList = View::make(
                'investigators.partials.investigation-search-list',
                [
                    'investigations' => $investigations,
                    'investigatorId' => $investigatorId,
                    'assigned' => count($assignedInvestigations) ? array_column($assignedInvestigations, 'investigation_id') : [],
                ]
            )->render();
    
            return response()->json([
                'status' => 'success',
                'data' => $investigationList,
            ]);
        }
    
        public function assignInvestigation(Request $request)
        {
            try {
                $assigned = null;
                $exist = InvestigatorInvestigations::where('investigation_id', $request->investigationId)->where('investigator_id', $request->investigatorId)->first();
    
                if (empty($exist)) {
                    $assigned = InvestigatorInvestigations::create([
                        'investigation_id' => $request->investigationId,
                        'investigator_id' => $request->investigatorId,
                        'note' => $request->note,
                        'status' => 'Assigned',
                        'status_hr' => trans('form.timeline_status.Assigned', [], 'hr'),
                        'assigned_by' => Auth::id(),
                    ]);
    
                    Investigations::where('id', $request->investigationId)->update(['status' => 'Assigned', 'status_hr' => trans('form.timeline_status.Assigned', [], 'hr')]);
                }
    
                return response()->json([
                    'status' => 'success',
                    'message' => trans('form.investigation.inv_assign_success'),
                    'data' => $assigned
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('general.something_wrong'),
                    'exception' => $th->getMessage(),
                ]);
            }
        }
    
        /**
         * View Investigations for investigator
         * @param $investigatorId
         * @param  \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        function viewInvestigations($investigatorId, Request $request)
        {
            $queryBuilder = InvestigatorInvestigations::with(['investigation.product'])->where('investigator_id', $investigatorId);
    
            if ($request->filled('status')) {
                if ($request->get('status') == 'Completed With Findings' || $request->get('status') == 'Completed Without Findings') {
                    $queryBuilder->where('status', $request->get('status'));
                } else {
                    $queryBuilder->whereNotIn('status', ['Completed With Findings', 'Completed Without Findings']);
                }
            }
            $investigations = $queryBuilder->groupBy('investigation_id')
                ->orderBy('investigation_id')
                ->latest()
                ->get();
    
            $returnHTML = view('investigators.view_investigations', compact('investigations'))->render();
    
            return response()->json(['status' => true, 'html' => $returnHTML]);
        }
    
        /**
         * Show form for approving investigator status
         *
         * @param  \Illuminate\Http\Request
         * @param $userId
         * @return mixed
         */
        public function showApproveForm(Request $request, $titleId)
        {
            $titleId = Crypt::decrypt($titleId);
            $title = Title::find($titleId);
    
            $user = User::where('id',1)->first();
            if (!$title) {
                return back()->with('warning', trans('general.no_record_found'));
            }
    
            $products = Product::all();
            
            $client_products = '';
            
            return view('titles.investigator-approval', compact('title','user', 'products', 'client_products'));
        }
    
        /**
         * Update investigator details and change status to Approved
         *
         * @param  \Illuminate\Http\Request
         * @param $userId
         * @return mixed
         */
        public function approveClient(Request $request, $userId)
        {
            $user = User::with('investigator')->find($userId);
    
            if ($request->filled('arr_product')) {
                foreach ($request->input('arr_product') as $key => $value) {
                    InvestigatorProduct::updateOrCreate([
                        'investigator_id' => $user->investigator->id,
                        'product_id' => $key
                    ], ['price' => $value]);
                }
            }
    
            $success_message = trans('form.client_approval.payment_details_updated_successfully');
    
            if ($user->status !== 'approved') {
                $user->status = 'approved';
                $user->approved_at = Carbon::now();
                $user->approved_by = \Illuminate\Support\Facades\Auth::user()->id;
                $user->save();
    
                $invtransdata = [
                    'event' => 'user_approve',
                    'data' => json_encode(array('data' => array('id' => $user->id, 'name' => $user->name, 'type' => 'investigator_approve'))),
                ];
                InvestigationTransition::addTransion($invtransdata, NULL);
    
                Mail::to($user->email)->queue(new EmailClientApproval($user));
                $success_message = trans('form.client_approval.investigator_approved');
            }
    
            return redirect('/investigators')->with('success', $success_message);
        }
}
