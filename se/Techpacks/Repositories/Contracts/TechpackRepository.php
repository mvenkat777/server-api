<?php

namespace Platform\Techpacks\Repositories\Contracts;

use Platform\Techpacks\Commands\DeleteTechpackCommand;
use Platform\Techpacks\Commands\ForceDeleteTechpackCommand;
use Platform\Techpacks\Commands\GetTechpackByIdCommand;
use Platform\Techpacks\Commands\ListTechpacksCommand;
use Platform\Techpacks\Commands\RegisterNewTechpackCommand;
use Platform\Techpacks\Commands\RestoreTechpackCommand;
use Platform\Techpacks\Commands\UpdateTechpackCommand;

interface TechpackRepository
{
    /**
     * @return mixed
     */
    public function model();

    /**
     * @param ListTechpacksCommand $command
     * @return mixed
     */
    public function listTechpacks(ListTechpacksCommand $command, $user);


    /**
     * @param RegisterNewTechpackCommand $command
     * @param $isAdmin
     * @return mixed
     */
    public function registerNewTechpack(RegisterNewTechpackCommand $command, $isAdmin);

    /**
     * @param GetTechpackByIdCommand $command
     * @return mixed
     */
    public function getTechpackById(GetTechpackByIdCommand $command);

    /**
     * @param UpdateTechpackCommand $command
     * @return mixed
     */
    public function updateTechpack(UpdateTechpackCommand $command);

    /**
     * @param DeleteTechpackCommand $command
     * @return mixed
     */
    public function deleteTechpack(DeleteTechpackCommand $command);

    /**
     * @param RestoreTechpackCommand $command
     * @return mixed
     */
    public function restoreTechpack(RestoreTechpackCommand $command);

    /**
     * @param ForceDeleteTechpackCommand $command
     * @return mixed
     */
    public function forceDeleteTechpack(ForceDeleteTechpackCommand $command);

    /**
     * Check if authenticated  user it the owner of techpack
     *
     * @param  string  $techpackId
     * @param  string  $userId
     * @return boolean
     */
    public function isOwner($techpackId, $userId);
}
