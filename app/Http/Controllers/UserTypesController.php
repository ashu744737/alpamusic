<?php

namespace App\Http\Controllers;

use App\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class UserTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('usertypes.index');
    }

    public function getUsertypesList()
    {
        $usertypes = UserTypes::select('id', 'type_name')->where('type_name', '!=', env('USER_TYPE_ADMIN'))->orderBy('id', 'asc')->get();
        return DataTables::of($usertypes)

            ->addColumn('type_name', function ($usertypes) {
                $btns = '';
                if (check_perm('usertype_edit')) {
                    $editUrl = route('usertype.edit', ['type_id' => Crypt::encrypt($usertypes->id)]);
                    $btns .= " <a href='{$editUrl}' class='' title='" . trans('general.edit') . "'>
		                        " . $usertypes->type_name . "
		                    </a>   
                    ";
                } else {
                    $btns .= $usertypes->type_name;
                }
                return $btns;
            })
            ->addColumn('permissions', function ($usertypes) {
                $specHtml = '';

                $specArr =  !empty($usertypes->permissions) ? array_column($usertypes->permissions->toArray(), 'name') : '';

                if ($specArr) {
                    foreach ($specArr as $spec) {
                        $specHtml .= '<span class="badge dt-badge badge-info">' . $spec . '</span>&nbsp;';
                    }
                }

                return  $specHtml;
            })
            ->addColumn('action', function ($usertypes) {
                $btns = '';
                if (check_perm('usertype_edit')) {
                    $editUrl = route('usertype.edit', ['type_id' => Crypt::encrypt($usertypes->id)]);
                    $btns .= "
	                    <span class='noVis' style='display: inline-flex'>
                           
                            <a href='{$editUrl}' class='dt-btn-action' title='" . trans('general.edit') . "'>
		                        <i class='mdi mdi-table-edit mdi-18px'></i>
		                    </a>
                        </span>
                    ";
                }


                return $btns;
            })

            ->rawColumns(['type_name', 'permissions', 'action'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $permissions = Permission::all()->pluck('name', 'id');
        $usertype = UserTypes::find(Crypt::decrypt($id));
        return view('usertypes.edit', compact('permissions', 'usertype'));
    }

    /**
     * UserType Permissions Validation Function.
     *
     * @param  array $data
     * @return \Illuminate\Http\Response
     */
    public function usertypeValidator(array $data)
    {
        return Validator::make($data, [
            'permissions' => ['required'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->usertypeValidator($request->all())->validate();
        try {
            DB::beginTransaction();
            $usertype = UserTypes::findById($id);
            $usertype->permissions()->sync($request->input('permissions', []));
            DB::commit();
            return redirect()->route('usertype.index')->with('success', trans('form.permissions_form.updated'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('usertype.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }

    public function readnotification($id){
        return readNotifications($id);
    }

    public function getUserType($id){
        $userType = UserTypes::find($id);
        if($userType->type_name == env('USER_TYPE_STATION_MANAGER')){
            return true;
        } else {
            if($userType->type_name == env('USER_TYPE_ACCOUNTANT')){
                return $userType->type_name;
            } else {
                return false;
            }
        }
    }
}
