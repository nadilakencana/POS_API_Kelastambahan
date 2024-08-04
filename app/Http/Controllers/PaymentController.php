<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payments;
use Illuminate\Support\Facades\Validator;
class PaymentController extends Controller
{
    public function payment(Request $request){
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try{
            $payment = Payments::create($request->all());
            return response()->json([
                'message' => 'Payment created successfully',
                'data' => $payment
            ], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to create payment','data' => $e->getMessage()], 500);
        }
    }

    public function getPayment(){
        try{
            $payment = Payments::all();
            return response()->json([
                'message' => 'Payment fetched successfully',
                'data' => $payment
            ], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch payment','data' => $e->getMessage()], 500);
        }
    }

    public function upadatePayment(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try{
            $payment = Payments::find($id);
            $payment->update($request->all());
            return response()->json([
                'message' => 'Payment updated successfully',
                'data' => $payment
            ], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to update payment','data' => $e->getMessage()], 500);
        }
    }

    public function DeletePayment($id){
        try{
            $payment = Payments::find($id);
            $payment->delete();
            return response()->json(['message' => 'Payment deleted successfully'], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to delete payment','data' => $e->getMessage()], 500);
        }
    }
}
