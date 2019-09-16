<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Illuminate\Support\Facades\Auth;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\SampleContainer\Repositories\Contracts\SampleRepository;

class DeleteSampleByIdCommandHandler implements CommandHandler
{
    /**
     * The sample repositiry
     * @var string
     */
    private $sample;

    /**
     * Construct the handler
     * @param SampleRepository $sample
     */
    public function __construct(SampleRepository $sample)
    {
        $this->sample = $sample;
    }

    /**
     * Handle the DeleteSampleByIdCommand
     * @param  DeleteSampleByIdCommand $command
     * @return mixed
     */
    public function handle($command)
    {
        $sample = $this->sample->getBySampleContainerIdAndSampleId(
            $command->sampleContainerId,
            $command->sampleId
        );

        if ($sample) {
            $this->isAuthor($sample, Auth::user());
            return $sample->delete();
        }
        throw new SeException("Sample not found in container.", 404);
    }

    /**
     * Check if authenticated user is the author of the sample
     * @param  Object  $sample
     * @param  Object  $user
     * @return boolean
     */
    public function isAuthor($sample, $user)
    {
        /**
         * Added Else condition as true just because, we are asked to remove the condition
         * of authorised checking
         */ 
        if ($sample->author_id == $user->id) {
            return true;
        } else {
            return true;
        }
        throw new SeException(
            "You must be the author of the sample to delete it.",
            401
        );
    }
}