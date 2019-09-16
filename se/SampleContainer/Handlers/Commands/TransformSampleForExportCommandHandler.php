<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Helpers\Helpers;

class TransformSampleForExportCommandHandler implements CommandHandler 
{

	public function handle($command)
	{
        $name = $command->sample->SampleContainer->techpack->name;
        $styleCode = $command->sample->SampleContainer->style_code;
        if (isset($command->sample->SampleContainer->techpack->customer->name)) {
            $customerName = $command->sample->SampleContainer->techpack->customer->name ;
        } else {
            $customerName = $command->sample->SampleContainer->techpack->meta->customer->name ;
        }
        $author = $command->sample->author->display_name;

        $sample = $command->sample->toArray();
        $sample['name'] = $name;
        $sample['styleCode'] = $styleCode;
        $sample['customerName'] = $customerName;
        $sample['author'] = $author;
        if (isset($sample['pom']->pom)) {
            $pomSheet = [];
            foreach ($sample['pom']->pom as $pom) {
                $pom->deviance = $this->setDeviance($pom);
                $pom->isHighlighted = $this->isHighlighted($pom);
                array_push($pomSheet, $pom);
            }
            $sample['pom']->pom = $pomSheet;
        }
        return (object)$sample;
	}

    public function setDeviance($pom) {
        if (isset($pom->deviance)) {
            return (string) Helpers::decimalToFraction($pom->deviance);
        }
        return '-';
    }

    private function isHighlighted($pom) {
        $deviance = $this->fractionToDecimal($pom->deviance);

        if(!isset($pom->tol)) {
            $pom->tol = 0;
        }
        
        $tol = $this->fractionToDecimal($pom->tol);
        return ($deviance > $tol);
    }

    private function fractionToDecimal($fraction) {
        $fraction = explode(" ", $fraction);
        $decimal = 0;
        if (count($fraction) == 2) {
            $decimal += (int)$fraction[0];
            $fraction = explode("/", $fraction[1]);
            if (count($fraction) == 2) {
                if ($decimal < 0) {
                    $decimal -= (int)$fraction[0] / (int)$fraction[1];
                } else {
                    $decimal += (int)$fraction[0] / (int)$fraction[1];
                }
            }
        } else {
            $fraction = explode("/", $fraction[0]);
            if (count($fraction) == 2 && (int)$fraction[1] != 0) {
                $decimal += (int)$fraction[0] / (int)$fraction[1];
            } else {
                $decimal = (int)$fraction[0];
            }
        }
        return ($decimal < 0) ? -$decimal: $decimal;
    }

}
