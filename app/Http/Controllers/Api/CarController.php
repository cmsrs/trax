<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Car;
use Validator;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{

    private $validationRules = [
        'year' => 'required|integer',
        'make' => 'required|string',
        'model' => 'required|string'
  ];    

    /**
     * Create a new controller instance.
     *
     * @return void
     */    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show cars.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbCars = Car::All()->toArray();        
        return ['data' => $dbCars];
    }


    /*
    example of return    
    'data' => [
        'id' => 1,
        'make' => 'Land Rover',
        'model' => 'Range Rover Sport',
        'year' => 2017,
        'trip_count' => 2,
        'trip_miles' => 18.1
    ]
    */
    /**
     * get one item.
     *
     * @return \Illuminate\Http\Response
     */
    public function item(Request $request, $id)
    {
        $car = Car::find($id);

        if (empty($car)) {
            return response()->json(['success'=> false, 'error'=> 'Car not find'], 404);
        }

        $out = [];
        $out['id'] = $car->id;
        $out['make'] = $car->make;
        $out['model'] = $car->model;
        $out['year'] = $car->year;
        $trips = $car->trips()->get()->toArray();
        $out['trip_count'] = count($trips);
        
        $tripMiles = 0;
        foreach($trips as $trip){
            $tripMiles += $trip['miles'];
        }
        $out['trip_miles'] = $tripMiles;

        return ['data' => $out];
    }

    
    public function deleteitem(Request $request, $id)
    {
        $car = Car::find($id);

        if (empty($car)) {
            return response()->json(['success'=> false, 'error'=> 'Car not find'], 404);
        }
        $car->delete();

        return true;
    }



    /**
     * create car
     */
    public function create(Request $request)
    {
        $data = $request->only(
            'make',
            'model',
            'year'
        );                                          
        $validator = Validator::make($data, $this->validationRules);
        if ($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()], 200);
        }

        try {
            $car =  Car::create($data);
        } catch (\Exception $e) {
            Log::error('car add ex: '.$e->getMessage().' line: '.$e->getLine().'  file: '.$e->getFile()); 
            return response()->json(['success'=> false, 'error'=> 'Add car problem, details in the log file.'], 200);
        }

        return response()->json(['success'=> true, 'data' => ['carId' => $car->id, 'data' => $data] ]);
    }


}
