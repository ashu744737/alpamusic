<?php

namespace App\Http\Controllers;

use App\DocumentTypes;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{


    // Document Types(Price) Module functions
    public function documentpriceindex()
    {

        return view('settings.documentprice.index');
    }

    // general settings
    public function general()
    {
        $settings = Setting::all();
        return view('settings.general', compact('settings'));
    }

    public function updateTax(Request $request){
        if(isset($request->tax)){
            $taxSettings = Setting::where('key', 'tax')->first();

            if($taxSettings){
                $taxSettings->value = $request->tax;
                $taxSettings->save();
            }else{
                Setting::create([
                    'key' => 'tax',
                    'value' => $request->tax,
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => trans('form.general_setting.confirm_updated'),
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => trans('general.something_wrong'),
        ]);
    }

    public function getDocumentPriceList()
    {
        $docprices = DocumentTypes::select('id', 'name', 'hr_name', 'price')
            ->orderBy('created_at', 'desc');


        return DataTables::of($docprices)
            ->editColumn('name', function ($doc) {
                if(config('app.locale') == 'hr'){
                    return $doc->hr_name;
                } else {
                    return $doc->name;
                }
            })
            ->addColumn('action', function ($docprices) {
                $editUrl = route('documentprice.edit', ['docprice_id' => Crypt::encrypt($docprices->id)]);
                $docprice_id = Crypt::encrypt($docprices->id);
                $btns = "<span class='noVis d-inline-flex'>";
                if (check_perm('documenttypes_edit')) {
                    $btns .= "   <a href='{$editUrl}' class='dt-btn-action' title='" . trans('general.edit') . "'>
                            <i class='mdi mdi-table-edit mdi-18px'></i>
                        </a>";
                }
                if (check_perm('documenttypes_delete')) {

                    $btns .= " <a class='dt-btn-action text-danger delete-record' data-id='{$docprice_id}'  title='" . trans('general.delete') . "'>
                            <i class='mdi mdi-delete mdi-18px'></i>
                        </a>";
                }
                $btns .= "</span>";

                return $btns;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function createdocumentprice()
    {

        return view('settings.documentprice.create');
    }
    /**
     * Document Price Validation Function.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function docPriceValidator(array $data)
    {
        $ignore_id = isset($data['docprice_id']) ? $data['docprice_id'] : null;
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:document_types,name,' . $ignore_id . ',id,deleted_at,NULL'],
            'name' => ['required', 'string', 'max:255', 'unique:document_types,hr_name,' . $ignore_id . ',id,deleted_at,NULL'],
            'price' => array('required', 'regex:/^\d*(\.\d{2})?$/')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storedocumentprice(Request $request)
    {
        $this->docPriceValidator($request->all())->validate();

        try {
            DB::beginTransaction();

            $docData = DocumentTypes::create([
                'name' => $request->name,
                'hr_name' => $request->hr_name,
                'price' => $request->price,
                'include_vat' => isset($request->include_vat)?($request->include_vat=="on"?1:0):0
            ]);

            DB::commit();

            return redirect()->route('documentprice.index')->with('success', trans('form.documenttypes.new_doctype_added'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('documentprice.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }

    public function editdocumentprice($id)
    {
        $doctype = DocumentTypes::find(Crypt::decrypt($id));
        return view('settings.documentprice.edit', compact('doctype'));
    }

    public function updatedocumentprice(Request $request, $id)
    {
        $this->docPriceValidator($request->all(), $id)->validate();
        try {
            DB::beginTransaction();

            $doctype = DocumentTypes::find($id);

            $docData['name'] = $request->name;
            $docData['hr_name'] = $request->hr_name;
            $docData['price'] = $request->price;
            $docData['include_vat'] = isset($request->include_vat)?($request->include_vat=="on"?1:0):0;


            $doctype->update($docData);

            DB::commit();

            return redirect()->route('documentprice.index')->with('success', trans('form.documenttypes.updated'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('documentprice.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }
    /**
     * Delete Single Record
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroydocumentprice(Request $request, $docprice_id)
    {
        try {
            $product = DocumentTypes::where('id', Crypt::decrypt($docprice_id))->first();
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => trans('form.documenttypes.delete_doctypes'),
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
    public function deleteMultipleDocumentprice(Request $request)
    {
        if (!empty($request->ids)) {
            DocumentTypes::whereIn('id', $request->ids)->delete();
        }
        return response()->json(['success' => true], 200);
    }
}
