<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Provider;

class ProvidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('providers')->delete();

        $provider = new Provider();
        $provider->id = 1;
        $provider->provider = 'Legacy';
        $provider->save();

        $provider = new Provider();
        $provider->id = 2;
        $provider->provider = 'Facebook';
        $provider->save();

        $provider = new Provider();
        $provider->id = 3;
        $provider->provider = 'Google';
        $provider->save();
    }
}
