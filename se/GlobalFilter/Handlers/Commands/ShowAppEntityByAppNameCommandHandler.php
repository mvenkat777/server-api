<?php

namespace Platform\GlobalFilter\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Customer\Repositories\Contracts\CustomerRepository;
use Platform\GlobalFilter\Repositories\Contracts\AppEntityRepository;
use Platform\Line\Repositories\Contracts\LineRepository;
use Platform\Materials\Repositories\Contracts\MaterialRepository;
use Platform\SampleContainer\Repositories\Contracts\SampleContainerRepository;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class ShowAppEntityByAppNameCommandHandler implements CommandHandler
{
    
    private $appEntityRepository;

    /**
     * @param AddressRepository
     */
    public function __construct(
        TechpackRepository $techpackRepo,
        TaskRepository $taskRepo,
        UserRepository $userRepo,
        VendorRepository $vendorRepo,
        CustomerRepository $customerRepo,
        LineRepository $lineRepo,
        TNARepository $tnaRepo,
        AppEntityRepository $appRepo,
        MaterialRepository $materialRepo,
        SampleContainerRepository $sampleRepo
    ) {
        $this->tnaRepo = $tnaRepo;
        $this->techpackRepo = $techpackRepo;
        $this->taskRepo = $taskRepo;
        $this->userRepo = $userRepo;
        $this->customerRepo = $customerRepo;
        $this->vendorRepo = $vendorRepo;
        $this->materialRepo = $materialRepo;
        $this->lineRepo = $lineRepo;
        $this->sampleRepo = $sampleRepo;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        if ($command->appName === 'techpack') {
            $command->data['owner'] = 'both';
            $techpacks = $this->techpackRepo->filterTechpack($command->data);
            $result = $this->addPermission($techpacks);
            return $result;
            
        }
        if($command->appName ==='user' || $command->appName === 'messenger'){
             $users =$this->userRepo->filterUser($command->data);
             if ($command->appName === 'messenger') {
                foreach ($users as $key => $user) {
                    $user->display_name = '@'.$user->display_name;
                }
             }
             return $users;
        }
        if($command->appName ==='customer'){
             $customer =$this->customerRepo->filterCustomer($command->data);
             return $customer;
        }
        if($command->appName ==='vendor'){
             $vendor =$this->vendorRepo->filterVendor($command->data);
             return $vendor;
        }
        if($command->appName ==='calendar'){
             $tna =$this->tnaRepo->filterTna($command->data);
             return $tna;
        }
        if($command->appName ==='task'){
            $task =$this->taskRepo->taskFilter($command->data);
            return $task;
        }
        if($command->appName ==='material'){
            $material =$this->materialRepo->filterMaterial($command->data);
            return $material;
        }
        if($command->appName ==='sample'){
            $command->data['techpackName'] = $command->data['q'];
            unset($command->data['q']);
            $samples =$this->sampleRepo->filterSampleContainer($command->data);
            $result = $this->addTechpackNameIntoSample($samples);
            return $result;
        }
        if($command->appName ==='line'){
            $line =$this->lineRepo->filterLine($command->data);
            return $line;
        }
        throw new SeException("App doesn't exists", 422, 4220450);
    }

    /**
     * Add Techpack Editable permission as per user
     * @param array $techpacks 
     */
    public function addPermission($techpacks)
    {
        $role = \App\Role::where('name', 'Edit Access')->first();
        $userIds = is_null($role)? [] : $role->users->lists('id')->toArray();

        foreach ($techpacks as $techpack) {
            $techpack->isEditable = (
                $techpack->user_id === \Auth::user()->id || 
                \Auth::user()->is_god === true 
            );
            if (!empty($userIds)) {
                $techpack->isEditable = (
                    $techpack->isEditable ||
                    in_array(\Auth::user()->id, $userIds)
                ); 
            } 
        }
        return $techpacks;
    }

    /**
     * add techpack name to sample
     * @param array $samples 
     */
    public function addTechpackNameIntoSample($samples)
    {
        foreach ($samples as $sample) {
            $sample->name = $sample->techpack->name;
        }
        return $samples;
    }
}

