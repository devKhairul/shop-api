<?php


namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'slug' => 'required',
            'price' => 'required'
        ]);

        return Product::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if ( empty($product) ) 
        {
            return response([
                'status' => 404,
                'message' => 'not found'
            ], 404);
        }

        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if ( !empty($product) )
        {
            $request->validate([
                'product_name' => 'required',
                'slug' => 'required',
                'price' => 'required'
            ]);
    
            $product->update($request->all());
    
            return $product;
        } 
        else 
        {
            return response([
                'status' => 404,
                'message' => 'not found'
            ], 404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if ( !empty($product) ) 
        {
            if ( $product->destroy($id) )
            {
                return response([
                    'status' => 200,
                    'message' => 'successfully deleted'
                ], 200);
            }
        }
        else 
        {
            return response([
                'status' => 404,
                'message' => 'not found'
            ], 404);
        }
    }


    /**
     * Search for a product.
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        $product = Product::where('product_name', 'LIKE', '%'.$name.'%')->get();       
        
        if ( count($product) < 1 )
        {
            return response([
                'status' => 404,
                'message' => 'not found'
            ], 404);
        }
        else 
        {
            return $product;
        }


    }



}
