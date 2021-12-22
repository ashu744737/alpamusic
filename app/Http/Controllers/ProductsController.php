<?php

namespace App\Http\Controllers;

use App\Category;
use App\ClientProduct;
use App\Product;
use App\ProductDocuments;
use App\Subjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.index');
    }

    public function getProductList()
    {
        $products = Product::with(['category:id,name,hr_name'])
            ->select('id', 'name', 'price', 'spouse_cost', 'is_delivery', 'delivery_cost', 'category_id','created_at')
            ->withCount(['clients']);

        return DataTables::of($products)
            ->editColumn('is_delivery', function ($product) {
                return $product->is_delivery == 1 ? trans('general.yes') : trans('general.no');
            })
            ->editColumn('created_at', function ($product) {
                return Carbon::parse($product->created_at)->format('d/m/Y');
            })
            ->addColumn('category', function ($product) {
                if(config('app.locale') == 'hr'){
                    return !empty($product->category) ? $product->category->hr_name : '';
                } else {
                    return !empty($product->category) ? $product->category->name : '';
                }
            })
            ->addColumn('action', function ($products) {
                $editUrl = route('product.edit', ['prod_id' => Crypt::encrypt($products->id)]);
                $productid = Crypt::encrypt($products->id);
                $btns = "<span class='noVis d-inline-flex'>";
                if (check_perm('product_edit')) {
                    $btns .= "   <a href='{$editUrl}' class='dt-btn-action' title='" . trans('general.edit') . "'>
		                        <i class='mdi mdi-table-edit mdi-18px'></i>
                            </a>";
                }
                if (check_perm('product_delete')) {
                    $btns .= " <a class='dt-btn-action text-danger delete-record' data-id='{$productid}' data-client-count='{$products->clients_count}' title='" . trans('general.delete') . "'>
		                        <i class='mdi mdi-delete mdi-18px'></i>
                            </a>";
                }

                $btns .= '<a href="' . route('product.show', ['id' => $productid]) . '" class="dt-btn-action" title="' . trans('general.view') . '">
                            <i class="mdi mdi-eye mdi-18px"></i>
                        </a>';
                
                $btns .= "</span>";

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
        return view('products.create')->with('categories', Category::select('name', 'id', 'hr_name')->orderBy('id', 'ASC')->get());
    }

    /**
     * Product Validation Function.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function productValidator(array $data)
    {
        $ignore_id = isset($data['product_id']) ? $data['product_id'] : null;
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:products,name,' . $ignore_id . ',id,deleted_at,NULL'],
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
        $this->productValidator($request->all())->validate();

        try {
            DB::beginTransaction();

            $productData = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'is_delivery' => $request->is_delivery,
                'delivery_cost' => $request->delivery_cost,
                'spouse_cost' => $request->spouse_cost,
                'category_id' => $request->category_id,
            ]);

            if ($request->hasfile('product_doc')) {
                $filePath = public_path('product-documents');

                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }

                $file = $request->file('product_doc');

                $fileName = time() . '-inv-' . trim($file->getClientOriginalName());
                $originalName = $file->getClientOriginalName();

                try {

                    $file->move($filePath . '/', $fileName);

                    $fileModel = new ProductDocuments();

                    $fileModel->product_id = $productData->id;
                    $fileModel->doc_name = $originalName;
                    $fileModel->doc_type = 'Investigator Document';
                    $fileModel->file_name = $fileName;
                    $fileModel->file_path = $filePath . '/' . $fileName;
                    $fileModel->uploaded_by = Auth::id();

                    $fileModel->save();

                } catch (\Exception $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
            }

            if ($request->hasfile('product_doc_del')) {
                $filePath = public_path('product-documents');

                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }

                $file = $request->file('product_doc_del');

                $fileName = time() . '-del-' . trim($file->getClientOriginalName());
                $originalName = $file->getClientOriginalName();

                try {

                    $file->move($filePath . '/', $fileName);

                    $fileModel = new ProductDocuments();

                    $fileModel->product_id = $productData->id;
                    $fileModel->doc_name = $originalName;
                    $fileModel->doc_type = 'Deliveryboy Document';
                    $fileModel->file_name = $fileName;
                    $fileModel->file_path = $filePath . '/' . $fileName;
                    $fileModel->uploaded_by = Auth::id();

                    $fileModel->save();

                } catch (\Exception $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
            }

            DB::commit();

            return redirect()->route('product.index')->with('success', trans('form.products_form.new_product_added'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('product.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $productId = Crypt::decrypt($id);
            $product = Product::with('documents')->find($productId);

            return view('products.show', compact('product'));
        } catch (Exception $exception) {
            return back()->with('warning', trans('form.registration.something_went_wrong'));
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
        $product = Product::find(Crypt::decrypt($id));
        return view('products.edit', compact('product'))->with('categories', Category::select('name', 'id', 'hr_name')->orderBy('id', 'ASC')->get());
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
        $this->productValidator($request->all(), $id)->validate();

        try {
            DB::beginTransaction();

            $product = Product::find($id);

            $productData['name'] = $request->name;
            $productData['price'] = $request->price;
            $productData['is_delivery'] = $request->is_delivery;
            $productData['delivery_cost'] = $request->is_delivery == '1' ? $request->delivery_cost : null;
            $productData['spouse_cost'] = $request->spouse_cost;
            $productData['category_id'] = $request->category_id;

            $product->update($productData);

            if ($request->hasfile('product_doc')) {
                //Delete old document
                $productDocument = ProductDocuments::where('product_id', $product->id)->where('doc_type', 'Investigator Document')->first();

                if($productDocument){
                    $folderPath = public_path('product-documents');
                    $fileName = $productDocument->file_name;
                    $filePath = $folderPath. '/'. $fileName;

                    if(File::exists($filePath)) {
                        File::delete($filePath);
                    }

                    $productDocument->delete();
                }

                //Add new document
                $filePath = public_path('product-documents');

                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }

                $file = $request->file('product_doc');

                $fileName = time() . '-inv-' . trim($file->getClientOriginalName());
                $originalName = $file->getClientOriginalName();

                try {

                    $file->move($filePath . '/', $fileName);

                    $fileModel = new ProductDocuments();

                    $fileModel->product_id = $product->id;
                    $fileModel->doc_name = $originalName;
                    $fileModel->doc_type = 'Investigator Document';
                    $fileModel->file_name = $fileName;
                    $fileModel->file_path = $filePath . '/' . $fileName;
                    $fileModel->uploaded_by = Auth::id();

                    $fileModel->save();

                } catch (\Exception $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
            }

            if ($request->hasfile('product_doc_del')) {
                //Delete old document
                $productDocument = ProductDocuments::where('product_id', $product->id)->where('doc_type', 'Deliveryboy Document')->first();

                if($productDocument){
                    $folderPath = public_path('product-documents');
                    $fileName = $productDocument->file_name;
                    $filePath = $folderPath. '/'. $fileName;

                    if(File::exists($filePath)) {
                        File::delete($filePath);
                    }

                    $productDocument->delete();
                }

                //Add new document
                $filePath = public_path('product-documents');

                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777, true);
                }

                $file = $request->file('product_doc_del');

                $fileName = time() . '-del-' . trim($file->getClientOriginalName());
                $originalName = $file->getClientOriginalName();

                try {

                    $file->move($filePath . '/', $fileName);

                    $fileModel = new ProductDocuments();

                    $fileModel->product_id = $product->id;
                    $fileModel->doc_name = $originalName;
                    $fileModel->doc_type = 'Deliveryboy Document';
                    $fileModel->file_name = $fileName;
                    $fileModel->file_path = $filePath . '/' . $fileName;
                    $fileModel->uploaded_by = Auth::id();

                    $fileModel->save();

                } catch (\Exception $e) {
                    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
                }
            }

            DB::commit();

            return redirect()->route('product.index')->with('success', trans('form.products_form.updated'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('product.index')->with('error', trans('form.error') . ' ( ' . $th->getMessage() . ' )');
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
            $product = Product::where('id', Crypt::decrypt($id))->first();

            //TODO - check if product is being used on Investigations - Type of Inquiry
            if ($product->investigations()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('form.products_form.prevent_product_delete'),
                ]);
            }
            //TODO - remove mapped product pricing entries for clients
            ClientProduct::where('product_id', '=', $product->id)->delete();

            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => trans('form.products_form.delete_product'),
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
            Product::whereIn('id', $request->ids)->delete();
        }
        return response()->json(['success' => true], 200);
    }

    public function delete($id)
    {
        $doc = ProductDocuments::find($id);
        if($doc){
            $doc->delete();
        }

        return Redirect::route('product.show', ['id' => Crypt::encrypt($doc->product_id)]);
    }
}
