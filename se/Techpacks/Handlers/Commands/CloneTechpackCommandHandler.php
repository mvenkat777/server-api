<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;
use Platform\NamingEngine\Commands\GenerateStyleCodeCommand;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use Platform\Techpacks\Repositories\Contracts\TechpackUserRepository;

class CloneTechpackCommandHandler implements CommandHandler
{
    /**
     * The techpack repository
     * @var TechpackRepository
     */
    protected $techpack;

    /**
     * The techpack user repository
     * @var TechpackUserRepository
     */
	protected $techpackUser;

    /**
     * The techpac user repository
     * @var DefaultCommandBus
     */
    protected $commandBus;

    /**
     * @param TechpackRepository     $techpack
     * @param TechpackUserRepository $techpackUser
     */
    public function __construct(
        TechpackRepository $techpack,
        TechpackUserRepository $techpackUser,
        DefaultCommandBus $commandBus
    ) {
        $this->techpack = $techpack;
        $this->techpackUser = $techpackUser;
		$this->commandBus = $commandBus;
    }

    /**
     * Handles CloneTechpackCommand
     * @param CloneTechpackCommand   $command
     * @return mixed
     */
    public function handle($command)
    {
        $techpack = $this->techpack->find($command->techpackId);
        if (!$techpack) {
            throw new SeException(
                "The selected techpack seems to be invalid.",
                404
            );
        }

        $this->userCanClone($command->techpackId, $command->userId);

        \DB::beginTransaction();
        try {
        	$techpack = $this->techpack->cloneTechpack($command);

            if ($techpack) {
                $techpack = $this->addNewStyleCode($techpack);
        		$techpackUser = $this->techpackUser->addOwner(
                    $techpack->id,
                    $command->userId
                );
                \DB::commit();
            	return $techpack;
        	}
        } catch (Exception $e) {
            \DB::rollback();
            throw new SeException(
                "Sorry we were not able to clone the techpack.",
                500
            );
        }

    	return false;
    }

    /**
     * Checks if the user can clone the techpack or not
     * @return none
     */
    private function userCanClone($techpackId, $userId) {
        if (
            !$this->techpack->isOwner($techpackId, $userId) &&
            !$this->techpack->isPublic($techpackId)
        ) {
            throw new SeException(
                "You don't have enough permissions to clone the techpack.",
                401
            );
        }
        return true;
    }

    /**
     * Generate e new styleCode for the techpack
     * @param object $techpack
     */
    private function addNewStyleCode($techpack) {
        $styleCode = $this->commandBus->execute(
            new GenerateStyleCodeCommand(
                $techpack->meta->customer->code,
                $techpack->category,
                $techpack->product
            )
        );
        $techpack->style_code = $styleCode;
        $meta = (array)$techpack->meta;
        $meta['styleCode'] = $styleCode;
        $techpack->meta = (object) $meta;
        $techpack->save();

        return $techpack;
    }
}
