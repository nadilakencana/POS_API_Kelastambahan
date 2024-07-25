<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order_Items;
use App\Models\Orders;
use App\Models\Product_Logs;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class SalesController extends Controller
{
    public function code_order($length=5){
        $str ='';
        $charecters=array_merge(range('A','Z'),range('a','z'));
        $max = count($charecters)-1;
        for($i = 0; $i < $length; $i++){
            $rand = mt_rand(0, $max);
            $str .=$charecters[$rand];
        }
        return $str;
    }

    // customer
    public function order(Request $request){
        $validator = Validator::make($request->all(), [
            'sub_amount' => 'required|numeric',
            'amount' => 'required|numeric',
            'status' => 'required|string|max:255',
            'cart' => 'required|array',
            'cart.*.id_product' => 'required',
            'cart.*.quantity' => 'required|numeric',
            'cart.*.price' => 'required',
            'cart.*.products_logs' => 'required|array',
            'cart.*.products_logs.*.id_product' => 'required|exists:products,id',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            DB::beginTransaction();

            $order = Orders::create([
                'sub_amount' => $request->sub_amount,
                'amount' => $request->amount,
                'status' => 'Open Order',
                'order_code' => $this->code_order(),
            ]);
            if (Auth::user()->is_Admin) {
                $order['created_by'] = Auth::user()->id;
            }

            foreach($request->cart as $item){
               $order_items =  Order_Items::create([
                    'id_order' => $order->id,
                    'id_product' => $item['id_product'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'amount' => $item['quantity'] * $item['price'],
                ]);
                if (Auth::user()->is_Admin) {
                    $order_items['created_by'] = Auth::user()->id;
                }
                $order_items->save();

                foreach($item['products_logs'] as $pro_logs){
                    $product_logs = Product_Logs::create([
                        'id_order' => $order->id,
                        'id_product' => $pro_logs['id_product'],
                        'id_order_item' =>  $order_items->id,
                    ]);

                    $product_logs->save();
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
                'detail_order' => $order_items,
                'product_logs' => $product_logs

                ], 201);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to create order','detail' => $e->getMessage()], 500);
        }
    }

    public function detail_order($code_order){

        try{
            $order = Orders::where('order_code', $code_order)->first();
            $order_items = Order_Items::where('id_order', $order->id)->get();

            return response()->json(['order' => $order, 'detail_order' => $order_items], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch order','detail' => $e->getMessage()], 500);
        }
    }

    // admin
    // public function Post_order(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'sub_amount' => 'required|numeric',
    //         'amount' => 'required|numeric',
    //         'status' => 'required|string|max:255',
    //         'cart' => 'required|array',
    //         'cart.*.id_product' => 'required',
    //         'cart.*.quantity' => 'required|numeric',
    //         'cart.*.price' => 'required',
    //         'cart.*.products_logs' => 'required|array',
    //         'cart.*.products_logs.*.id_product' => 'required|exists:products,id',

    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     try{
    //         DB::beginTransaction();

    //         $order = Orders::create([
    //             'sub_amount' => $request->sub_amount,
    //             'amount' => $request->amount,
    //             'status' => 'Open Order',
    //             'order_code' => $this->code_order(),
    //             'created_by' => Auth::user()->id,
    //         ]);

    //         foreach($request->cart as $item){
    //            $order_items =  Order_Items::create([
    //                 'id_order' => $order->id,
    //                 'id_product' => $item['id_product'],
    //                 'quantity' => $item['quantity'],
    //                 'price' => $item['price'],
    //                 'amount' => $item['quantity'] * $item['price'],
    //                 'created_by' => Auth::user()->id,
    //             ]);

    //             $order_items->save();

    //             foreach($item['products_logs'] as $pro_logs){
    //                 $product_logs = Product_Logs::create([
    //                     'id_order' => $order->id,
    //                     'id_product' => $pro_logs['id_product'],
    //                     'id_order_item' =>  $order_items->id,
    //                 ]);

    //                 $product_logs->save();
    //             }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'message' => 'Order created successfully',
    //             'order' => $order,
    //             'detail_order' => $order_items,
    //             'product_logs' => $product_logs

    //             ], 201);
    //     }catch(\Exception $e){
    //         return response()->json(['message' => 'Failed to create order','detail' => $e->getMessage()], 500);
    //     }
    // }

    public function payment_order(Request $request, $code_order){
        try{
            $order = Orders::where('order_code', $code_order)->first();
            $order_items = Order_Items::where('id_order', $order->id)->get();

            $order->update([
                'status' => 'Paid',
                'id_pyment_methode' => $request->id_payment_method,
            ]);

            return response()->json([
                'message' => 'Payment Order successfully',
                'order' => $order,
                'detail_order' => $order_items
                ], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch Payment Order','detail' => $e->getMessage()], 500);
        }

    }
}
