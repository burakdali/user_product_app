<?php

namespace App\Http\Controllers\WEB;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WebUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users');
    }
    public function userProducts()
    {
        $user = User::find(Auth::user()->id());
        $products = $user->products()->get();
        return view('user.index', compact('products'));
    }
    public function getUsers(Request $req)
    {
        if ($req->ajax()) {
            $data = User::select('id', 'first_name', 'last_name', 'email', 'phone_number')->get();
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($data) {
                $actionBtn =
                    '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-success btn-sm text-dark" data-bs-toggle="modal" data-bs-target= "#editUser">Edit</button>
                    <button type="button" id="' . $data->id . '" class="text-dark delete btn btn-danger btn-sm" >Delete</button>
                    <button type="button" id="' . $data->id . '" class="assign btn btn-primary text-dark btn-sm"  data-bs-toggle="modal" data-bs-target= "#assignProducts">Assign</button>
                    <button type="button" id="' . $data->id . '" class="text-dark products btn btn-secondary btn-sm"  data-bs-toggle="modal" data-bs-target= "#userProducts">Products</button>';
                return $actionBtn;
            })->rawColumns(['action'])->make(true);
        }
    }
    public function getUserProducts($id)
    {
        $user = User::find($id);
        $products = $user->products()->get();
        $product = array();
        foreach ($products as $i) {
            array_push($product, $i['name']);
        }

        $response['data'] = $product;
        return response()->json($response);
    }

    public function editUser($id)
    {
        if (request()->ajax()) {
            $user = User::findOrFail($id);
            return response()->json(['result' => $user]);
        }
    }
    public function updateUser(Request $req)
    {
        $user = User::find($req->id);
        $user->update([
            'first_name' => $req->first_name,
            'last_name' => $req->last_name,
            'email' => $req->email,
            'phone_number' => $req->phone_number,
            'password' => Hash::make($req->password),
        ]);
        return view('admin.users')->with('success', 'User edited succesfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            $user = User::find($id);
            $user->delete();
            return response()->json(['result' => 'Deleted succesfully']);
        }
    }
    public function getProductsToAssign()
    {
        $products = Product::select('id', 'name')->get();
        $response['data'] = $products;
        return response()->json($response);
    }
    public function assignSave(Request $req)
    {
        foreach ($req->input('products') as $index) {
            DB::table('users_products')->insert([
                'user_id' => $req->id,
                'product_id' => $index,
            ]);
        }
        return view('admin.users');
    }
}
