<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\User;
use App\UserTypes;
use App\Mail\UserCreated;
use App\Mail\EmailUpdate;
use App\UserCategories;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Yajra\DataTables\Facades\DataTables;

class InternalUserController extends Controller
{
    public function index()
    {
        return view('internal-user.index');
    }

    public function getUserList()
    {
        $users = User::whereHas('user_type', function ($query) {
            $query->select('id', 'type_name', 'hr_type_name', 'type')->where('type', 'Internal');
        })->with('user_type')->orderBy('created_at', 'desc');
        //return $contacts;
        $badgeColor = [
            'approved' => 'badge-success',
            'pending' => 'badge-warning',
            'rejected' => 'badge-danger',
            'disabled' => 'badge-dark',
        ];
        return DataTables::of($users)
            ->addColumn('user_type', function ($user) {
                $userArr = $user->toArray();
                if(config('app.locale') == 'hr'){
                    return $userArr['user_type']['hr_type_name'];
                } else {
                    return $userArr['user_type']['type_name'];
                }
            })->addColumn('status', function ($user) use ($badgeColor) {
                if ($user->status != '') {
                    return '<span class="badge dt-badge ' . $badgeColor[$user->status] . '">' . trans('form.timeline_status.'.ucwords($user->status)) . '</span>';
                }
                return "";
            })->addColumn('action', function ($user) {
                $btns = '';
                $editUrl = route('staff.edit', ['user_id' => \Crypt::encrypt($user->id)]);
                $userId = \Crypt::encrypt($user->id);
                
                $edit = trans('general.edit');
                $delete = trans('general.delete');
                if ($user->status == 'pending') {
                    $approve = trans('general.approve');
                    
                    $btns .= "
	                    <span class='noVis d-inline-flex'>";
                    if (check_perm('staff_edit')) {
                        $btns .= " <a href='javascript:void(0);' data-status='approved' class='dt-btn-action status-btn' data-id='{$userId}' title='{$approve}'>
		                        <i class='mdi mdi-account-check mdi-18px'></i>
		                    </a>";
                        $btns .= "<a href='{$editUrl}' class='dt-btn-action' title='{$edit}'>
		                        <i class='mdi mdi-account-edit mdi-18px'></i>
                            </a>";
                    }
                    if (check_perm('staff_delete')) {
                        $btns .= "<a class='dt-btn-action text-danger delete-record' data-id='{$userId}' title='{$delete}'>
		                        <i class='mdi mdi-account-remove mdi-18px'></i>
                            </a>";
                    }
                    $btns .= "</span>
	                ";
                } else if ($user->status == 'approved') {
                    $btns .= "
	                    <span class='noVis' style='display: inline-flex'>";
                    if (check_perm('staff_edit')) {
                        $disable = trans('general.disable');
                        $btns .= "<a href='javascript:void(0);' data-status='disabled' class='dt-btn-action status-btn' data-id='{$userId}' title='{$disable}'>
		                        <i class='mdi mdi-account-lock mdi-18px'></i>
		                    </a>";
                        $btns .= " <a href='{$editUrl}' class='dt-btn-action' title='{$edit}'>
		                        <i class='mdi mdi-account-edit mdi-18px'></i>
                            </a>";
                    }
                    if (check_perm('staff_delete')) {
                        $btns .= "<a class='dt-btn-action text-danger delete-record' data-id='{$userId}' title='{$delete}'>
		                        <i class='mdi mdi-account-remove mdi-18px'></i>
                            </a>";
                    }
                    $btns .= "</span>
	                ";
                } else if ($user->status == 'disabled') {
                    $btns .= "
	                    <span class='noVis' style='display: inline-flex'>";
                    if (check_perm('staff_edit')) {
                        $enable = trans('general.enable');
                        $btns .= "<a href='javascript:void(0);' data-status='approved' class='dt-btn-action status-btn' data-id='{$userId}' title='{$enable}'>
		                        <i class='mdi mdi-account-check mdi-18px'></i>
		                    </a>";
                        $btns .= "<a href='{$editUrl}' class='dt-btn-action' title='{$edit}'>
		                        <i class='mdi mdi-account-edit mdi-18px'></i>
                            </a>";
                    }
                    if (check_perm('staff_delete')) {
                        $btns .= "<a class='dt-btn-action text-danger delete-record' data-id='{$userId}' title='{$delete}'>
		                        <i class='mdi mdi-account-remove mdi-18px'></i>
                            </a>";
                    }
                    $btns .= "</span>
	                ";
                }

                return $btns;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $userTypes = UserTypes::where('type', 'Internal')->get();
        $categories = Category::select('name', 'id', 'hr_name')->orderBy('id', 'ASC')->get();

        return view('internal-user.create', compact('userTypes', 'categories'));
    }

    public function userValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed'],
        ]);
    }

    public function userUpdateValidator(array $data, $userId)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => 'unique:users,email,' . $userId,
        ]);
    }

    public function generateVerifyToken()
    {
        // Generate new token
        return Str::random(10) . sha1(time());
    }

    public function store(Request $request)
    {
        //dd($request);
        $this->userValidator($request->all())->validate();

        try {
            DB::beginTransaction();
            $userData = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'verify_token' => $this->generateVerifyToken(),
                'type_id' => $request->user_type_id,
                'salary' => (isset($request->salary) && !is_null($request->salary))?$request->salary:NULL,
            ]);

            $data = User::with('user_type')->where('id', $userData->id)->first();

            $user['name'] = $data->name;
            $user['email'] = $data->email;
            $user['password'] = $request->password;
            $user['type'] = $data->user_type->type_name;
            $arr_cat = $arr_cat2 = [];
            if ($request->filled('category')) {
                foreach ($request->category as $value) {
                    $arr_cat2['user_id'] = $userData->id;
                    $arr_cat2['category_id'] = $value;
                    $arr_cat2['created_at'] = now();
                    $arr_cat2['updated_at'] = now();
                    $arr_cat[] = $arr_cat2;
                }
                UserCategories::insert($arr_cat);
            }
            DB::commit();
            $mailVerify = Mail::to($user['email'])->queue(new UserCreated($user));

            return redirect()->route('staff.index')->with('success', trans('form.internal_user.new_user_added'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('staff.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }

    public function edit($id)
    {
        $user = User::find(\Crypt::decrypt($id));
        $userTypes = UserTypes::where('type', 'Internal')->get();
        $categories = Category::select('name', 'id', 'hr_name')->orderBy('id', 'ASC')->get();
        return view('internal-user.edit', compact('userTypes', 'user', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->userUpdateValidator($request->all(), $id)->validate();
        try {
            DB::beginTransaction();
            $userId = $request->user_id;
            $user = User::find($id);

            $userData['name'] = $request->name;
            $userData['email'] = $request->email;
            $userData['type_id'] = $request->user_type_id;

            if (!empty($request->password) || $request->password != null) {
                $userData['password'] = Hash::make($request->password);
            }
            if(isset($request->salary) && !is_null($request->salary)){
                $userData['salary'] = $request->salary;
            }
            $user->update($userData);
            DB::commit();
            $data = User::with('user_type')->where('id', $id)->first();
            $newUserData['name'] = $data->name;
            $newUserData['email'] = $data->email;
            $newUserData['password'] = $request->password ? $request->password : '';
            $newUserData['type'] = $data->user_type->type_name;

            $arr_cat = $arr_cat2 = [];
            if ($request->filled('category')) {
                UserCategories::where('user_id', $userId)->delete();
                foreach ($request->category as $value) {
                    $arr_cat2['user_id'] =   $userId;
                    $arr_cat2['category_id'] = $value;
                    $arr_cat2['created_at'] = now();
                    $arr_cat2['updated_at'] = now();
                    $arr_cat[] = $arr_cat2;
                }
                UserCategories::insert($arr_cat);
            }

            $mailUpdate = Mail::to($newUserData['email'])->queue(new EmailUpdate($newUserData));
            return redirect()->route('staff.index')->with('success', trans('form.internal_user.updated'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('staff.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = User::where('id', \Crypt::decrypt($id))->first();
            $user->delete();
            return response()->json([
                'status' => 'success',
                'message' => trans('form.internal_user.delete_user'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('form.error'),
                'exception' => $th->getMessage(),
            ]);
        }
    }

    public function deleteMultiple(Request $request)
    {
        if (!empty($request->ids)) {
            User::whereIn('id', $request->ids)->delete();
        }
        return response()->json(['success' => true], 200);
    }

    public function updateStatus(Request $request)
    {
        if ($request->user_id) {
            try {
                $user = User::find(\Crypt::decrypt($request->user_id));
                $user->status = $request->status;
                if ($request->status == 'approved') {
                    $user->email_verified_at = date('Y-m-d H:i:s');
                }
                $user->save();
                $response = array(
                    'success' => 'true',
                    'message' => $user->name . ' ' .trans("general.has_been"). ' ' . trans('form.timeline_status.'.ucwords($user->status)) . ' '.trans('general.successfully'),
                );
                return response()->json($response);
            } catch (Exception $e) {
                Log::error($th->getMessage());
                return response()->json([
                    'success' => 'false',
                    'message' => trans('form.error'),
                    'exception' => $e->getMessage(),
                ], 400);
            }
        }
    }
}
