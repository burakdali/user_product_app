<?php

namespace App\Http\Controllers\WEB;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class WebProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.products');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function deleteProduct($id)
    {
        if (request()->ajax()) {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json(['result' => 'Deleted succesfully']);
        }
    }

    public function getProducts(Request $req)
    {
        if ($req->ajax()) {
            $data = Product::select('id', 'name', 'description', 'created_at')->get();
            return Datatables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
                $actionBtn =
                    '<button type="button" class="edit btn btn-success btn-sm text-dark" id="' . $data->id . '"data-bs-toggle="modal" data-bs-target= "#editProduct">Edit</button> 
                    <button type="button" id="' . $data->id . '"  class="delete btn btn-danger btn-sm text-dark">Delete</button>';
                return $actionBtn;
            })->rawColumns(['action'])->make(true);
        }
    }
    public function editProduct($id)
    {
        if (request()->ajax()) {
            $data = Product::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }
    public function updateProduct(Request $req)
    {
        $product = Product::find($req->id);
        $image = $product->image;
        if ($req->hasFile('image')) {
            Storage::delete($product->image);
            $image = $req->file('image')->store('public/categories');
        }
        $product->update([
            'name' => $req->name,
            'description' => $req->description,
            'slug' => Str::slug($req->input('name')),
            'image' => $image,
        ]);
        return view('admin.products')->with('success', 'Product edited succesfully');
    }
}
