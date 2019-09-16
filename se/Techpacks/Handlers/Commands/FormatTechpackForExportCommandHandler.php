<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Helpers\Helpers;
class FormatTechpackForExportCommandHandler implements CommandHandler
{
    public function handle($command)
	{
        $techpack = $command->techpack;
        $techpack->poms = $this->formatPOM($techpack);
        $techpack->graded_specs = $this->formatGradedSpecs($techpack);
        $techpack->graded_specs_corrections = $this->formatGradedSpecsCorrections($techpack);
        return $techpack;
    }

    public function formatPOM($techpack)
    {
        $formattedPOM['header'] = ['POM Code', 'Description', 'Tol'];
        $sampleIDs = [];

        if(isset($techpack->poms->samples) && isset($techpack->poms->pom)){
            foreach ($techpack->poms->samples as $sample) {
                array_push($formattedPOM['header'], $sample->name);
                array_push($sampleIDs, $sample->id);
            }

            $formattedPOM['values'] = [];
            foreach ($techpack->poms->pom as $pom) {
                $POMRow = [];
                if (isset($pom->pomCode)) {
                    array_push($POMRow, $pom->pomCode);
                } else {
                    array_push($POMRow, '-');
                }
                if (isset($pom->description)) {
                    array_push($POMRow, $pom->description);
                } else {
                    array_push($POMRow, '-');
                }

                if (isset($pom->tol)) {
                    array_push($POMRow, $pom->tol);
                } else {
                    array_push($POMRow, '-');
                }
                if (isset($pom->values)) {
                    foreach ($sampleIDs as $id) {
                        foreach ($pom->values as $value) {
                            if ($value->sampleId == $id) {
                                array_push($POMRow, $value->value);
                            }
                        }
                    }
                } else {
                    $POMRow = array_merge($POMRow, array_fill(0, count($sampleIDs), '-'));
                }

                $formattedPOM['values'][] = $POMRow;

            }
        }

        if (isset($techpack->poms->sizeRange)) {
            $small = isset($techpack->poms->sizeRange->small) ? $techpack->poms->sizeRange->small : 'NA';
            $large = isset($techpack->poms->sizeRange->large) ? $techpack->poms->sizeRange->large : 'NA';
            $formattedPOM['sizeRange'] = $small . ' - ' . $large;
        } else {
            $formattedPOM['sizeRange'] = 'NA';
        }

        return $formattedPOM;
    }

    public function formatGradedSpecs($techpack)
    {
        if (!empty($techpack->spec_sheets)) {
            $formattedGradedSpecs['sample'] = $techpack->spec_sheets[0]->sampleName;
            $formattedGradedSpecs['header'] = [];
            $sheets = $techpack->spec_sheets[0]->sheet;
            if(isset($sheets[0])) {
                foreach ($sheets[0] as $key => $value) {
                    if ($key != 'KEY' && $key != 'id' && $key != 'QC') {
                        array_push($formattedGradedSpecs['header'], $key);
                    }
                }

            }

            $formattedGradedSpecs['values'] = [];

            foreach ($sheets as $sheet) {
                $data = [];
                foreach ($formattedGradedSpecs['header'] as $key) {
                    if (isset($sheet->{$key})) {
                        $value = $sheet->{$key}[1] == '' ? $sheet->{$key}[0] : $sheet->{$key}[1];
                    } else {
                        $value = '-';
                    }
                    if (!in_array($key, ['POM_CODE', 'POM_DESCRIPTION'])) {
                        $value = Helpers::decimalToFraction($value);
                    }
                    $data[] = $value;
                }
                $formattedGradedSpecs['values'][] = $data;
            }

        } else {
            $formattedGradedSpecs = [];
        }

        if (empty($formattedGradedSpecs)) {
            return $formattedGradedSpecs;
        }
        return (object) $formattedGradedSpecs;

    }

    public function formatGradedSpecsCorrections($techpack)
    {
        if (!empty($techpack->spec_sheets)) {
            $formattedGradedSpecs['sample'] = $techpack->spec_sheets[0]->sampleName;
            $formattedGradedSpecs['header'] = [];
            $sheets = $techpack->spec_sheets[0]->sheet;
            // dd($sheets);
            if (isset($sheets[0])) {
                foreach ($sheets[0] as $key => $value) {
                    if ($key != 'KEY' && $key != 'id' && $key != 'QC') {
                        array_push($formattedGradedSpecs['header'], $key);
                    }
                }
            }

            $formattedGradedSpecs['values'] = [];

            foreach ($sheets as $sheet) {
                $data = [];
                foreach ($formattedGradedSpecs['header'] as $key) {
                    if (isset($sheet->{$key})) {
                        $value = $sheet->{$key}[0] == '' ? 0 : $sheet->{$key}[0];
                    } else {
                        $value = '-';
                    }
                    if (!in_array($key, ['POM_CODE', 'POM_DESCRIPTION'])) {
                        $value = Helpers::decimalToFraction($value);
                    }
                    $data[] = $value;
                }
                $formattedGradedSpecs['values'][] = $data;
            }
        } else {
            $formattedGradedSpecs = [];
        }

        if (empty($formattedGradedSpecs)) {
            return $formattedGradedSpecs;
        }
        return (object) $formattedGradedSpecs;

    }
}
