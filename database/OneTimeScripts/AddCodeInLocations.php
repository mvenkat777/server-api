<?php

use Illuminate\Database\Seeder;
use Platform\Holidays\Models\Location;
use Rhumsaa\Uuid\Uuid;

class AddCodeInLocations extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \DB::beginTransaction();
        $locations = Location::get();
        $code = [
            'China' => 'CN-SHO',
            'India Bangalore' => 'IN-BLR',
            'India Hyderabad' => 'IN-HYD',
            'India Tirupur' => 'IN-TUP',
            'Vietnam' => 'VN-HCM',
        ];

        foreach ($locations as $location) {
            $dbCode = array_key_exists($location->city, $code) ? $code[$location->city] : NULL;
            $location->code = $dbCode;
            $location->save();
        }

        $uuid1 = Uuid::uuid4()->toString();
        $uuid2 = Uuid::uuid4()->toString();
        $uuid3 = Uuid::uuid4()->toString();
        $uuid4 = Uuid::uuid4()->toString();
        $uuid5 = Uuid::uuid4()->toString();

        \DB::insert(" INSERT INTO locations (id, code, city, state, country, postal_code, address, created_at, updated_at) VALUES 
                    ('$uuid1','US-NYC', 'New york', 'New York', 'USA', 10018, '241 W 37th Street #908 New York City, NY, USA', now() , now() ), 
                    ('$uuid2', 'US-SFO', 'San Francisco', 'California', 'USA', 94103, '395 S Van Ness Ave San Francisco, CA, USA', now() , now() ), 
                    ('$uuid3', 'US-LAX', 'Los Angeles', 'California', 'USA', 90014, '731 S Spring St #503 Los Angeles, CA, USA', now() , now() ), 
                    ('$uuid4', 'CN-HKG', 'Hong Kong', 'Hong Kong', 'China', 999077, NULL, now() , now() ), 
                    ('$uuid5', 'IN-BOM', 'Mumbai', 'Maharastra', 'INDIA', 400036, NULL, now() , now() )
                    
                    ");

        \DB::commit();
    }
}
