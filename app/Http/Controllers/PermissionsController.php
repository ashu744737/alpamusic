<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('permissions.index');
    }

    public function getPermissionList()
    {
        $permissions = Permission::select('id', 'name')->orderBy('created_at', 'desc')->get();
        return DataTables::of($permissions)
            ->addColumn('action', function ($permissions) {
                $permissionid = Crypt::encrypt($permissions->id);
                $btns = '';
                if (check_perm('permission_delete')) {
                    $btns .= "
	                    <span class='noVis' style='display: inline-flex'>
		                    <a class='dt-btn-action text-danger delete-record' data-id='{$permissionid}' title='" . trans('general.delete') . "'>
		                        <i class='mdi mdi-delete mdi-18px'></i>
		                    </a>
                        </span>
                    ";
                }


                return $btns;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Permission Validation Function.
     *
     * @param  array $data
     * @return \Illuminate\Http\Response
     */
    public function permissionValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:permissions'],
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
        $this->permissionValidator($request->all())->validate();
        try {
            DB::beginTransaction();

            $productData = Permission::create([
                'name' => $request->name,
            ]);
            DB::commit();
            return redirect()->route('permission.index')->with('success', trans('form.permissions_form.new_permission_added'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permission.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::find(Crypt::decrypt($id));
        return view('permissions.edit', compact('permission'));
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
        $this->permissionValidator($request->all(), $id)->validate();
        try {
            DB::beginTransaction();
            $product = Permission::findById($id);

            $productData['name'] = $request->name;

            $product->update($productData);
            DB::commit();
            return redirect()->route('permission.index')->with('success', trans('form.permissions_form.updated'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permission.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }

    /**
     * Delete Single Record
     *
     * @param  \App\Product  $product
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $product = Permission::where('id', Crypt::decrypt($id))->first();
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => trans('form.permissions_form.delete_permission'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => trans('form.error'),
                'exception' => $th->getMessage(),
            ]);
        }
    }
    /**
     * Delete Multiple records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMultiple(Request $request)
    {
        if (!empty($request->ids)) {
            Permission::whereIn('id', $request->ids)->delete();
        }
        return response()->json(['success' => true], 200);
    }
}
