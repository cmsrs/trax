<?php
namespace Tests\Feature\Api;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use App\Car;
use App\Trip;

class CarTest extends Base
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
 
    /** @test */
    public function it_will_get_cars()
    {        
        $car = 
        [
            'make' => '111Land Rover',
            'model' => 'Range Rover Sport',
            'year' => 2017
        ];

        $objCar = Car::create($car);
        $this->assertNotEmpty($objCar->id);
        
        $dbCars = Car::All()->toArray();
        $countCars = count($dbCars);
        $this->assertEquals(1, $countCars);

        //$res = $this->withSession(["_token" => 'bar'])->get('api/get-cars');        
        $res = $this->get('api/get-cars');                
        $res->assertStatus(200);
        $d = $res->getData();
        $this->assertTrue(is_array($d->data));
        $this->assertEquals($countCars, count($d->data));

        //dd($d->data);
        $this->assertEquals($car['make'], $d->data[0]->make );
        $this->assertEquals($car['model'], $d->data[0]->model );
        $this->assertEquals($car['year'], $d->data[0]->year );
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
    /** @test */
    public function it_will_get_one_car()
    {        
        $car = 
        [
            'make' => '111Land Rover',
            'model' => 'Range Rover Sport',
            'year' => 2017
        ];

        $objCar = Car::create($car);
        $this->assertNotEmpty($objCar->id);
        
        $dbCars = Car::All()->toArray();
        $countCars = count($dbCars);
        $this->assertEquals(1, $countCars);

        $trip1 = 
        [
            'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'miles' => 11.3,
            'car_id' => $objCar->id
        ];
        $objTrip1 = Trip::create($trip1);
        $this->assertNotEmpty($objTrip1->id);

        $trip2 = 
        [
            'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'miles' => 21.3,
            'car_id' => $objCar->id
        ];
        $objTrip2 = Trip::create($trip2);
        $this->assertNotEmpty($objTrip2->id);


        $res = $this->get('api/get-car/'.$objCar->id );
        $res->assertStatus(200);
        $d = $res->getData();

        $this->assertNotEmpty($d->data->id  );
        $this->assertNotEmpty($d->data->make  );
        $this->assertNotEmpty($d->data->model  );        
        $this->assertNotEmpty($d->data->year  );

        $this->assertEquals($car['make'], $d->data->make );
        $this->assertEquals($car['model'], $d->data->model  );        
        $this->assertEquals($car['year'], $d->data->year  );

        $this->assertNotEmpty($d->data->trip_count  );
        $this->assertEquals(2, $d->data->trip_count );
        
        $this->assertNotEmpty($d->data->trip_miles );
        $this->assertEquals( $trip1['miles'] + $trip2['miles'] , $d->data->trip_miles );
    }


   /** @test */
   public function it_will_create_car()
   {        
        $dbCars = Car::All()->toArray();
        $this->assertEmpty($dbCars);

        $car = 
        [
           'make' => '111Land Rover',
           'model' => 'Range Rover Sport',
           'year' => 2017
        ];

        $res = $this->post('api/add-car', $car);                
        $res->assertStatus(200);
        $d = $res->getData();
        $this->assertTrue($d->success);

        $car['make'] = null;
        $res = $this->post('api/add-car', $car);                
        $res->assertStatus(200);
        $d = $res->getData();
        $this->assertFalse($d->success);
    }

    /** @test */
    public function it_will_delete_one_car()
    {
        $car = 
        [
            'make' => '111Land Rover',
            'model' => 'Range Rover Sport',
            'year' => 2017
        ];

        $objCar = Car::create($car);
        $this->assertNotEmpty($objCar->id);
        
        $dbCars = Car::All()->toArray();
        $countCars = count($dbCars);
        $this->assertEquals(1, $countCars);

        $res = $this->delete('api/delete-car/'.$objCar->id );
        $res->assertStatus(200);
        $dbCarsAfter = Car::All()->toArray();
        $this->assertEmpty($dbCarsAfter);
    }    

}