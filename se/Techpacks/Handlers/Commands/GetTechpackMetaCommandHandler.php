<?php

namespace Platform\Techpacks\Handlers\Commands;

use Illuminate\Support\Facades\Auth;
use Platform\App\Commanding\CommandHandler;
use Platform\SampleContainer\Repositories\Contracts\SampleContainerRepository;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class GetTechpackMetaCommandHandler implements CommandHandler
{
    /**
     * The techpack repository
     * @var object
     */
    private $techpack;

    /**
     * The sample container repository
     * @var object
     */
    private $sampleContainer;

    /**
     * Constructing..
     * @param TechpackRepository        $techpack
     * @param SampleContainerRepository $sampleContainer
     */
    public function __construct(
        TechpackRepository $techpack,
        SampleContainerRepository $sampleContainer
    ) {
        $this->techpack = $techpack;
        $this->sampleContainer = $sampleContainer;
    }

    /**
     * Handle the command
     * @param  GetTechpackMetaCommand $command
     * @return mixed
     */
	public function handle($command)
	{
        switch ($command->app) {
            case 'sample':
                $usedTechpackIds = $this->sampleContainer->lists('techpack_id')->toArray();
                return \App\Techpack::where(function ($query) {
                    $query->where('user_id', Auth::user()->id)
                          ->orWhere('visibility', true);
                })->whereNotIn('id', $usedTechpackIds)
                  ->get();
                break;

            default:
                return $this->techpack->getAllAccessibleTechpacks();
        }
	}

}