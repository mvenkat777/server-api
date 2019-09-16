<?php

use Illuminate\Database\Seeder;
use Platform\Address\Commands\CreateAddressCommand;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class VendorAddressesDummySeeder extends Seeder
{
    function __construct(VendorRepository $vendorRepository, DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
        $this->vendorRepository = $vendorRepository;
    }
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $vendors = \App\Vendor::get();
        $data1 =[
            'isPrimary' => true,
            'label' => 'registered'
        ];
        $data2 =[
            'isPrimary' => false,
            'label' => 'mailing'
        ];
        $data3 =[
            'isPrimary' => false,
            'label' => 'factory'
        ];
        $count =0;
        foreach ($vendors as $vendor) {
            $venAdd = $vendor->addresses;
            if (count($venAdd) == 0) {
                $address1 = $this->commandBus->execute(new CreateAddressCommand($data1));
                $this->vendorRepository->addAddress($vendor->id, $address1->id);
                $address2 = $this->commandBus->execute(new CreateAddressCommand($data2));
                $this->vendorRepository->addAddress($vendor->id, $address2->id);
                $address3 = $this->commandBus->execute(new CreateAddressCommand($data3));
                $this->vendorRepository->addAddress($vendor->id, $address3->id);
                $count ++;
            }
        }
        echo $count. "rows updated";
    }
}
