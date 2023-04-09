<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ProductCollection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:30', 'unique:products,name'],
            'image' => ['required', 'image'],
            'description' => ['required']
        ]);
        $image = $request->file('image')->store('public/products');
        $product = Product::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'image' => $image,
            'description' => $request->input('description'),
        ]);
        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($value)
    {
        return new ProductCollection(Product::where('slug', $value)->orWhere('id', $value)->get());
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

        $this->validate($request, [
            'name' => ['sometimes', 'max:30', Rule::unique('products')->ignore($product->name(), 'name')],
            'image' => ['sometimes', 'image'],
            'description' => ['required']
        ]);

        $image = $request->file('image')->store('public/products');
        $product->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'image' => $image,
            'description' => $request->input('description'),
        ]);
        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }

    public function assign(Request $request)
    {
        $this->validate($request, [
            'user_id' => ['required', 'numeric'],
            'product_id' => ['required', 'numeric']
        ]);
        $exist = DB::table('users_products')
            ->where('user_id', $request->user_id)
            ->Where('product_id', $request->product_id)
            ->get();
        if (sizeof($exist) != 0) {
            return response()->json('field is already exists');
        } else {
            DB::table('users_products')->insert([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id
            ]);
            return response()->json(null, 204);
        }
    }
}
