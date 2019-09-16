<?php

use Illuminate\Database\Seeder;

class AddSamplesToSamplePOMs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sampleContainers = \App\SampleContainer::all();

        foreach ($sampleContainers as $sampleContainer) {
            $techpack = $sampleContainer->techpack;
            $samples = $sampleContainer->samples;

            if ($samples) {
                foreach($samples as $sample) {
                    $samplePOM = json_decode(
                        json_encode($sample->pom),
                        true
                    );
                    if(!isset($samplePOM['samples'])) {
                        $newPOM['samples'] = $techpack->poms->samples;
                        $newPOM['pom'] = $samplePOM;
                        $sample->pom = $newPOM;
                        $sample->timestamps = false;
                        $sample->save();
                    }
                }
            }
        }

    }
}
