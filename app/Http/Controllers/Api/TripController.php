<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Car;
use App\Trip;
use Validator;
use Illuminate\Support\Facades\Log;

class TripController extends Controller
{

    private $validationRules = [
        'date' => 'required|date', // ISO 8601 string
        'car_id' => 'required|integer',
        'miles' => 'required|numeric'
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


    /*
        example of return
        [
            'id'  => 2,
            'date' => Carbon::now()->subDays(2)->format('m/d/Y'),
            'miles' => 12.0,
            'total' => 34.1,
            'car' => [
                'id' => 4,
                'make' => 'Aston Martin',
                'model' => 'Vanquish',
                'year' => 2018
            ]
        ],
     * Show cars.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbTrips = Trip::All();

        $out = [];
        $i = 0;
        foreach($dbTrips as $trip){
            $out[$i] = [];
            $out[$i]['id'] = $trip->id;
            $out[$i]['date'] = (new \DateTime($trip->date))->format('m/d/Y');
            $out[$i]['miles'] = $trip->miles;
            $out[$i]['total'] = '34.1'; //todo - i dont know how to calculate this value
            $out[$i]['car'] = $trip->car()->first(['id', 'make', 'model', 'year'])->toArray(); //todo - it can be problem with performance - it can be too many request to db
            $i++;
        }

        return ['data' => $out];
    }

    /**
     * create car
     */
    public function create(Request $request)
    {
        $data = $request->only(
            'date', // ISO 8601 string
            'car_id',
            'miles'
        );                                          
        $validator = Validator::make($data, $this->validationRules);
        if ($validator->fails()) {
            return response()->json(['success'=> false, 'error'=> $validator->messages()], 200);
        }

        try {
            $data['date'] = explode('T', $data['date'])[0];
            $car = Trip::create($data);
        } catch (\Exception $e) {
            Log::error('trip add ex: '.$e->getMessage().' line: '.$e->getLine().'  file: '.$e->getFile()); 
            return response()->json(['success'=> false, 'error'=> 'Add trip problem, details in the log file.'], 200);
        }

        return response()->json(['success'=> true, 'data' => ['tripId' => $car->id, 'data' => $data] ]);
    }

}