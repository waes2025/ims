<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function index()
    {
        try {
            $customers = Customer::orderBy('name', 'asc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Customer list fetched successfully',
                'data' => $customers,
            ], 200);
        }catch (\Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching customers',
                'errors' => $e->getMessage(),
            ], 500);    
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email',
                'mobile' => 'required|string|max:20|unique:customers,mobile',
                'description' => 'nullable|string|max:255',
            ]);

            $customer = Customer::create($request->only('name', 'email', 'mobile', 'description'));
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'data' => $customer,
            ], 201);
        }catch (ValidationException $e){
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }catch (\Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while creating customer',
                'errors' => $e->getMessage(),
            ], 500);    
        }
    }

    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Customer details fetched successfully',
                'data' => $customer,
            ], 200);
        }catch (\Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching customer details',
                'errors' => $e->getMessage(),
            ], 500);    
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:customers,email,' . $customer->id,
                'mobile' => 'required|string|max:20|unique:customers,mobile,' . $customer->id,
                'description' => 'nullable|string|max:255',
            ]);

            $customer->update($request->only('name', 'email', 'mobile', 'description'));

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => $customer,
            ], 200);
        }catch (ValidationException $e){
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }catch (\Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating customer',
                'errors' => $e->getMessage(),
            ], 500);    
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully',
            ], 200);
        }catch (\Throwable $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while deleting customer',
                'errors' => $e->getMessage(),
            ], 500);    
        }
    }
}
