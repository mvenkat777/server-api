<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Techpacks\Commands\GenerateTechpackVendorExportCommand;
use Platform\Techpacks\Commands\MultipleTechpackExportCommand;
use Platform\Techpacks\Jobs\TechpackMultipleExportJob;


class TechpackExportsController extends ApiController
{
    protected $commandBus;

    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Generate techpack vendor export
     * @param  string $techpackId
     * @return mixed
     */
    public function vendorExport(Request $request, $techpackId)
    {
        $export = $this->commandBus->execute(
            new GenerateTechpackVendorExportCommand(
                $request->all(),
                $techpackId
            )
        );

        if ($export) {
            $data['data'] = [
                'downloadLink' => $export,
            ];
            return $this->respondWithArray($data);
        }
        return $this->respondWithError("Techpack export failed. Please try again.");
    }

    /**
     * Generate a selective
     * @param  string $techpackId
     * @return mixed
     */
    public function selectiveExport($techpackId, Request $request)
    {
        $export = $this->commandBus->execute(
            new GenerateTechpackVendorExportCommand(
                $request->all(),
                $techpackId,
                $request->selectedFields
            ));

        if ($export) {
            $data['data'] = [
                'downloadLink' => $export,
            ];
            return $this->respondWithArray($data);
        }
        return $this->respondWithError("Techpack export failed. Please try again.");
    }

    /**
     * Generate a selective
     * @param  string $techpackId
     * @return mixed
     */
    public function multipleExport(Request $request)
    {
                
        $job = (new TechpackMultipleExportJob($request->all(), \Auth::user()->email));
        $this->dispatch($job);
        
        return $this->respondOk("We will send you a mail once the exports are ready");
    }
}
