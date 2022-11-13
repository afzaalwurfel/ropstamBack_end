<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

use Yajra\DataTables\DataTables;

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
    public function index(Request $request)
    {
         $cars = $this->user->cars()->get();
         return Datatables::of($cars)->addIndexColumn()->addColumn('action',function ($row){
            $url = url("delete/" . $row->id);
            $icon = $row->status == 0 ? 'check' : 'slash';
            $textColor = $row->status == 0 ? 'success' : 'warning';
            return $btnAction = '<center>
                <a  href="' . $url . '" data-item-id="' . $row->id . '"><i class="feather icon-eye text-info icon-action"></i></a>
                 <a  href="#"><i data-id="' . $row->id . '" data-status="' . $row->status . '" class="change-contractor-status feather icon-' . $icon . ' text-' . $textColor . ' icon-action"></i></a>
                  <a  href="#"><i data-id="' . $row->id . '" class="delete-contractor feather icon-trash text-danger icon-action"></i></a>
                </center>';
         })->rawColumns(['action'])->make(true);
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
