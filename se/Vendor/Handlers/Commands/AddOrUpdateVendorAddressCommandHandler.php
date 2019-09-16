<?php

namespace Platform\Vendor\Handlers\Commands;

use Platform\Address\Commands\CreateAddressCommand;
use Platform\Address\Commands\UpdateAddressCommand;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class AddOrUpdateVendorAddressCommandHandler implements CommandHandler
{
    /**
     * @var VendorRepository
     */
    private $vendorRepository;

    /**
     * @param VendorRepository
     */
    public function __construct(
        VendorRepository $vendorRepository,
        DefaultCommandBus $commandBus
    ) {
        $this->vendorRepository = $vendorRepository;
        $this->commandBus = $commandBus;
       
    }

    /**
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {
        $count = 0;
        foreach ($command->addresses as $address) {
            if(isset($address['isPrimary']) && $address['isPrimary'] == true){
                $count ++;
            }
        }
        if($count <= 1){
            foreach ($command->addresses as $value) {
                if (isset($value['id']) && $value['id'] != NULL) {
                    $address = $this->commandBus->execute(new UpdateAddressCommand($value));
                }
                $address = $this->commandBus->execute(new CreateAddressCommand($value));
                $this->vendorRepository->addAddress($command->vendorId, $address->id);

            }
            return true;
        }
        elseif($command->addresses != []){
            throw new SeException("More then one primary address is not possible", 400, 8670100);
            
        }
    }

}

