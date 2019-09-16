<?php

use Illuminate\Database\Seeder;

class UpdateLineUpdatedDate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lines = \App\Line::all();

        foreach ($lines as $line) {
        	$style = $line->styles()->orderBy('updated_at', 'desc')->first();
        	if ($style) {
        		$line->updated_at = $style->updated_at;
        		$line->timestamps = false;
        		$line->save();
        	}
        }
    }
}
