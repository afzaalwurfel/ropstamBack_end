<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class CarsController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->user
            ->cars()
            ->get();
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
        //Validate data
        $data = $request->only('name', 'sku', 'price', 'quantity');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'sku' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new car
        $car = $this->user->cars()->create([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);

        //car created, return success response
        return response()->json([
            'success' => true,
            'message' => 'car created successfully',
            'data' => $car
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\car  $car
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = $this->user->cars()->find($id);
    
        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, car not found.'
            ], 400);
        }
    
        return $car;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(cars $cars)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, cars $car)
    {
        //Validate data
        $data = $request->only('name', 'sku', 'price', 'quantity');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'sku' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update car
        $car = $car->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);

        //car updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'car updated successfully',
            'data' => $car
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(cars $car)
    {
        $car->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'car deleted successfully'
        ], Response::HTTP_OK);
    }
}
