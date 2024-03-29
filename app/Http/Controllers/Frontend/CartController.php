<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function manage()
    {
        if( Auth::check() ){
            $carts = Cart::where('user_id', Auth::id())->where("order_id", NULL)->get();
        }
        else{
            $carts = Cart::where('ip_address', request()->ip())->where("order_id", NULL)->get();
        }
        return view('frontend.pages.order.cart', compact('carts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // first check data
        if( Auth::check() ){
           $cart = Cart::where('user_id', Auth::id())->where('product_id', $request->product_id)->where('order_id', NULL)->first();
        }
        else{
            $cart = Cart::where('ip_address', request()->ip())->where('product_id', $request->product_id)->where('order_id', NULL)->first();
        }
        

        if( !is_null( $cart ) ){
            if( !is_null( $request->product_quantity ) ){
                $cart->product_quantity = $cart->product_quantity + $request->product_quantity;
            }
            else{
                $cart->increment('product_quantity');
            }

            $cart->save();

            $notification = array(
                'message'    => "Item added to cart",
                'alert-type' => "success",
            );

    
            return redirect()->back()->with($notification); 
        }

        else{
            $carts = new Cart();

            if( Auth::check() ){
                $carts->user_id             = Auth::id(); 
                $carts->product_id          = $request->product_id; 
                $carts->product_quantity    = $request->product_quantity; 
                $carts->save();

                $notification = array(
                    'message'    => "Item added to cart",
                    'alert-type' => "success",
                );
        
                return redirect()->back()->with($notification); 
            }
            else{
                $carts->ip_address        = request()->ip(); 
                $carts->product_id        = $request->product_id; 
                $carts->product_quantity  = $request->product_quantity; 
                $carts->save();

                $notification = array(
                    'message'    => "Item added to cart",
                    'alert-type' => "success",
                );
        
                return redirect()->back()->with($notification); 
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cartDel = Cart::find($id);
        $cartDel->delete();

        $notification = array(
            'message'    => "Cart items has been delete",
            'alert-type' => "error",
        );

        return redirect()->back()->with($notification); 
    }
}
