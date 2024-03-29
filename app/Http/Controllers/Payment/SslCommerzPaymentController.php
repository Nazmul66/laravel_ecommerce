<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Cart;
use App\Models\State;
use App\Models\Country;
use App\Models\District;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewOrderEmail;
use App\Mail\ContactMail;

class SslCommerzPaymentController extends Controller
{

    public function checkout()
    {
        $states = State::orderBy('name', 'asc')->where('status', 1)->get();
        $countries = Country::orderBy('name', 'asc')->where('status', 1)->get();
        $districts = District::orderBy('name', 'asc')->where('status', 1)->get();
        return view('frontend.pages.order.checkout', compact('states', 'countries', 'districts'));
    }


    public function index(Request $request)
    {
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        if( Auth::check() ){
          $userId = Auth::user()->id;
        }

        $post_data = array();
        $post_data['total_amount'] = Cart::totalAmount(); # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name']        = $request->name;
        $post_data['cus_email']       = $request->email;
        $post_data['cus_phone']       = $request->phone;
        $post_data['cus_add1']        = $request->address_line1;
        $post_data['cus_add2']        = $request->address_line2;
        $post_data['cus_district']    = $request->district_id;
        $post_data['cus_division']    = $request->division_id;
        $post_data['cus_country']     = $request->country_id;
        $post_data['cus_zipCode']     = $request->zipCode;

        $post_data['payment_method']  = $request->payment_method;

        // manage shipping methods and charges
        $post_data['shipping_method'] = 1;

        $post_data['cus_fax']         = "";

        # SHIPMENT INFORMATION & do not comment this code
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS & do not comment this code
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        #Before  going to initiate the payment order status need to insert or update as Pending.

        // COD ID 1
        if( $post_data['payment_method'] == 1 ){
            $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'user_id'             => $userId,
                'name'                => $post_data['cus_name'],
                'email'               => $post_data['cus_email'],
                'phone'               => $post_data['cus_phone'],
                'addressLine1'        => $post_data['cus_add1'],
                'addressLine2'        => $post_data['cus_add2'],
                'district_id'         => $post_data['cus_district'],
                'division_id'         => $post_data['cus_division'],
                'country'             => $post_data['cus_country'],
                'zip_code'            => $post_data['cus_zipCode'],
                'shipping_method'     => $post_data['shipping_method'],
                'amount'              => $post_data['total_amount'],
                'paid_amount'         => 0,
                'coupon_code'         => 'Blank',
                'status'              => 'Pending',
                'transaction_id'      => $post_data['tran_id'],
                'currency'            => $post_data['currency'],
                'created_at'          => Carbon::now()
            ]);

            // remove all carts data when purchased
            $transaction_id = $post_data['tran_id'];
            $order_Id = DB::table('orders')
            ->where('transaction_id', $transaction_id)
            ->select('id')->first();

            foreach ( Cart::totalCart() as $cart ) {

                if( !is_null( $cart->product->offer_price) ){
                    $unitPrice = $cart->product->offer_price;
                }
                else{
                    $unitPrice = $cart->product->regular_price;
                }

                // order_id change after purchase order data
                $cart->product_unit_price = $unitPrice;
                $cart->order_id = $order_Id->id;
                $cart->save();

                // reduce quantity after purchase orders 
                $product = Product::where('id', $cart->product_id)->first();
                $upQty = $product->quantity - $cart->product_quantity;
                $product->quantity = $upQty;
                $product->save();
            }

            // email template send to customer and admin account person
            $mailData = [
                'name'       =>  $post_data['cus_name'],
                'email'      =>  $post_data['cus_email'],
            ];

            $mailData2 = [
                'fname'      =>  $request->fname,
                'lname'      =>  $request->lname,
                'phone'      =>  $request->phone,
                'email'      =>  $post_data['cus_email'],
                'message'    =>  $request->message
            ];
    
            $adminEmail = 'hnazmul748@gmail.com';
            $customerEmail = $post_data['cus_email'];
    
            // one for admin to email send 
            Mail::to($adminEmail)->send( new NewOrderEmail($mailData) );

            // second for customer email send
            Mail::to($customerEmail)->send( new ContactMail($mailData2) );

            $notification = array(
                'message'    => "Your order has been placed successfully",
                'alert-type' => "success",
            );

            return redirect()->route('homepage')->with($notification);
        }

        // ssl commerce ID 2
        else if( $post_data['payment_method'] == 2 ){
            $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'user_id'             => $userId,
                'name'                => $post_data['cus_name'],
                'email'               => $post_data['cus_email'],
                'phone'               => $post_data['cus_phone'],
                'addressLine1'        => $post_data['cus_add1'],
                'addressLine2'        => $post_data['cus_add2'],
                'district_id'         => $post_data['cus_district'],
                'division_id'         => $post_data['cus_division'],
                'country'             => $post_data['cus_country'],
                'zip_code'            => $post_data['cus_zipCode'],
                'shipping_method'     => $post_data['shipping_method'],
                'amount'              => $post_data['total_amount'],
                'paid_amount'         => $post_data['total_amount'],
                'coupon_code'         => 'Blank',
                'status'              => 'Pending',
                'transaction_id'      => $post_data['tran_id'],
                'currency'            => $post_data['currency'],
                'created_at'          => Carbon::now()
            ]);

              // remove all carts data when purchased
              $transaction_id = $post_data['tran_id'];
              $order_Id = DB::table('orders')
              ->where('transaction_id', $transaction_id)
              ->select('id')->first();
  
              foreach ( Cart::totalCart() as $cart ) {
  
                  if( !is_null( $cart->product->offer_price ) ){
                      $unitPrice = $cart->product->offer_price;
                  }
                  else{
                      $unitPrice = $cart->product->regular_price;
                  }
  

                  // order_id change after purchase order data
                $cart->product_unit_price = $unitPrice;
                $cart->order_id = $order_Id->id;
                $cart->save();

                // reduce quantity after purchase orders 
                $product = Product::where('id', $cart->product_id)->first();
                $upQty = $product->quantity - $cart->product_quantity;
                $product->quantity = $upQty;
                $product->save();
              }

            $sslc = new SslCommerzNotification();
            # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
            $payment_options = $sslc->makePayment($post_data, 'hosted');

            if (!is_array($payment_options)) {
                print_r($payment_options);
                $payment_options = array();
            }
        }

    }

    public function payViaAjax(Request $request)
    {

        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = '10'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION 
        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION & do not comment this code
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS & do not comment this code
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        #Before  going to initiate the payment order status need to update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    public function success(Request $request)
    {
        // echo "Transaction is Successful";

        $tran_id        = $request->input('tran_id');
        $amount         = $request->input('total_amount');
        $currency       = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->first();

        $product_cart_details = DB::table('carts')
            ->where('order_id', $order_details->id)
            ->get();

        $product_details = DB::table('products')->get();
        $expected_date_time = Carbon::now()->addDays(3);

        // $mailData = [
        //     'email'      =>  $order_details->email,
        //     'message'    =>  $request->message
        // ];

        // $adminEmail = 'hnazmul748@gmail.com';

        // Mail::to($adminEmail)->send( new NewOrderEmail($mailData) );

        $notification = array(
            'message'    => "Your order has been placed successfully",
            'alert-type' => "success",
        );    

        return view('frontend.pages.order.order-success', compact('order_details','product_cart_details', 'product_details', 'expected_date_time'))->with($notification);

    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed']);
            echo "Transaction is Falied";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }

    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
            echo "Transaction is Cancel";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }


    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

}