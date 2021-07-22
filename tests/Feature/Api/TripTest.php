<?php
namespace Tests\Feature\Api;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Car;
use App\Trip;

class TripTest extends Base
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->createClientUser();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
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
    */
    /** @test */
    public function it_will_get_trips()
    {        
        $car = 
        [
            'make' => '111Land Rover',
            'model' => 'Range Rover Sport',
            'year' => 2017
        ];

        $objCar = Car::create($car);
        $this->assertNotEmpty($objCar->id);

        $trip = 
        [
            'date' => Carbon::now()->subDays(1)->format('Y-m-d'),  //format('m/d/Y'),
            'miles' => 11.3,
            //'total' => 45.3,
            'car_id' => $objCar->id
        ];

        $objTrip = Trip::create($trip);
        $this->assertNotEmpty($objTrip->id);
        
        $dbCars = Car::All()->toArray();
        $countCars = count($dbCars);
        $this->assertEquals(1, $countCars);


        $dbTrips = Trip::All()->toArray();
        $countTrips = count($dbTrips);
        $this->assertEquals(1, $countTrips);

        $res = $this->get('api/get-trips');                
        $res->assertStatus(200);
        $d = $res->getData();
        $this->assertTrue(is_array($d->data));
        $this->assertEquals($countTrips, count($d->data));

        //dd($d);
        $this->assertNotEmpty($d->data[0]->id );        
        //$this->assertEquals($trip['date'], $d->data[0]->date );
        $this->assertNotEmpty($trip['date'] );        
        $this->assertEquals($trip['miles'], $d->data[0]->miles );
        $this->assertNotEmpty( $d->data[0]->total );        
        $this->assertNotEmpty( $d->data[0]->car );
        $this->assertNotEmpty( $d->data[0]->car->id );
        $this->assertEquals($trip['car_id'], $d->data[0]->car->id );
        $this->assertNotEmpty( $d->data[0]->car->make );
        $this->assertNotEmpty( $d->data[0]->car->model );
        $this->assertNotEmpty( $d->data[0]->car->year );
    }

   /** @test */
   public function it_will_create_trip()
   {        
        $dbTrips = Trip::All()->toArray();
        $this->assertEmpty($dbTrips);

        $car = 
        [
           'make' => '111Land Rover',
           'model' => 'Range Rover Sport',
           'year' => 2017
        ];

        $objCar = Car::create($car);
        $this->assertNotEmpty($objCar->id);

        $trip = 
        [
            'date' =>  '2021-07-13T22:00:00.000Z', //Carbon::now()->subDays(1)->format('Y-m-d'),  //format('m/d/Y'),
            'miles' => 11.3,
            'car_id' => $objCar->id
        ];

        // $arr = explode( 'T', $trip['date'])[0];
        // dd($arr);
        // dd(Carbon::now()->subDays(1)->format('Y-m-d'));

        $tripDb = $trip;
        $tripDb['date'] = Carbon::now()->subDays(1)->format('Y-m-d');
        $objTrip = Trip::create($tripDb);
        $this->assertNotEmpty($objTrip->id);

        $res = $this->post('api/add-trip', $trip);
        $res->assertStatus(200);
        $d = $res->getData();
        $this->assertTrue($d->success);

    }

}