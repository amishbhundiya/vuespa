<?php

namespace App\Http\Controllers\Products;

// use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\CustomFunction\CustomFunction;
use Validator;
use App\Http\Controllers\Products\ProductImport;
use Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $output = array();
        $productData = Product::latest()->get();
        if(count($productData)==0) {
            return response()->json(['data' => $output, 'code' => 0]);
        }

        foreach($productData as $key => $value) {
            $imageurl = '';
            if($value['image']!='') {
                $imageurl = config('constant.siteurl').'/'.config('constant.upload_folder').$value['image'];
            }

            $output[] = array(
                'sr_no'=>$key+1,
                'image'=>$imageurl,
                'name'=>$value['name'],
                'price'=>$value['price'],
                'upc'=>$value['upc'],
                'status'=>$value['status'],
                'created_at'=>date('d-m-Y H:i a', strtotime($value['created_at'])),
                'pid'=>$value['id'],
            );
        }

        return response()->json(['data' => $output, 'code' => 1]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
            'name' => 'required',
            'price' => 'required',
            'upc' => 'required',
            'status' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:1024', // 1 MB
        ],
            [
            'name.required' => 'Please enter Product Name',
            'price.required' => 'Please enter Product Price',
            'upc.required' => 'Please enter Product UPC',
            'status.required' => 'Please select Product Status',
            'image.mimes' => 'Please upload jpg,png images only',
            'image.max' => 'Max. Upload image size is 1mb',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['data' => $validator->errors()->first(), 'code' => 0]);
        }

        $imagename = '';
        if ($request->hasfile('image')) {
            $image_file = $request->file('image');
            $fileUpload = CustomFunction::fileUpload($image_file, null);
            $imagename = $fileUpload['name'];
        }

        $create = array(
            'name' => CustomFunction::filter_input($request->name),
            'price' => CustomFunction::filter_input($request->price),
            'upc' => CustomFunction::filter_input($request->upc),
            'status' => $request->status,
            'image' => $imagename,
        );

        if (Product::create($create)) {
            return response()->json(['data' => 'Product has been added', 'code' => 1]);
        }

        return response()->json(['data' => 'Product details not added, please try again', 'code' => 0]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, $id)
    {
        $productData = Product::find($id);
        if($productData==null) {
            return response()->json(['data' => 'No Details found', 'code' => 0]);
        }
        return response()->json(['data' => $productData, 'code' => 1]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
            // 'id' => 'required',
            'name' => 'required',
            'price' => 'required',
            'upc' => 'required',
            'status' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png|max:1024', // 1 MB
        ],
            [
            // 'id.required' => 'Invalid details found, please try again',
            'name.required' => 'Please enter Product Name',
            'price.required' => 'Please enter Product Price',
            'upc.required' => 'Please enter Product UPC',
            'status.required' => 'Please select Product Status'
            ]
        );

        if ($validator->fails()) {
            return response()->json(['data' => $validator->errors()->first(), 'code' => 0]);
        }


        $productData = Product::find($id);
        if($productData!=null) {

            $update = array(
                'name' => CustomFunction::filter_input($request->name),
                'price' => CustomFunction::filter_input($request->price),
                'upc' => CustomFunction::filter_input($request->upc),
                'status' => $request->status,
            );

            $imagename = '';
            if ($request->hasfile('image')) {
                $oldimage = $productData->image;

                $image_file = $request->file('image');
                $fileUpload = CustomFunction::fileUpload($image_file, $oldimage);
                $imagename = $fileUpload['name'];

                $update['image'] = $imagename;
            }

            if ($productData->update($update)) {
                return response()->json(['data' => 'Product has been updated', 'code' => 1]);
            }
        }

        return response()->json(['data' => 'Product details not updated, please try again', 'code' => 0]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, $id)
    {
        $productData = Product::find($id);
        if($productData!=null) {
            // remove product image
            $image = $productData->image;

            // remove old file
            if ($image != '') {
                CustomFunction::fileRemove($image);
            }

            $productData->delete();

            return response()->json(['data' => 'Product deleted succesfully', 'code' => 1]);
        }

        return response()->json(['data' => 'Product not deleted, please try again', 'code' => 0]);
    }

    /**
     * Import product data from csv/excel file
     */
    public function import(Request $request)
    {
        if ($request->hasFile('file')) {

            $mime_arr = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.oasis.opendocument.spreadsheet", "application/vnd.ms-excel", "text/csv", "text/plain", "application/zip");
            $mime = $request->file('file')->getMimeType();

            if (!in_array($mime, $mime_arr)) {
                return response()->json(['data' => 'Please select only .xlsx, .csv file', 'code' => 0]);
            }

            $import = new ProductImport($request);
            Excel::import($import, $request->file('file'));

            $return_arr = $import->getRowCount();

            $total = $return_arr[0];

            return response()->json(['data' => 'Product Imported. Total : '.$total.'', 'code' => 1]);
        }

        return response()->json(['data' => 'Product not import, please try again', 'code' => 0]);
    }
}
