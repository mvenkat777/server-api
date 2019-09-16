<?php

use Illuminate\Database\Seeder;
use Rhumsaa\Uuid\Uuid;
use Platform\NamingEngine\Commands\GenerateVendorCodeCommand;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Vendor\Commands\CreateVendorCommand;
use Platform\Address\Commands\CreateAddressCommand;
use Platform\Contacts\Commands\CreateContactCommand;
use Platform\Vendor\Commands\AddVendorPartnerCommand;
use Platform\Vendor\Repositories\Eloquent\EloquentVendorRepository;
use Platform\Vendor\Repositories\Eloquent\EloquentVendorTypeRepository;
use App\Vendor;
use App\VendorType;


class VendorNameSeeder extends Seeder
{ 

	protected $vendorRepository;
    protected $vendorTypeRepository;
  function __construct(
        DefaultCommandBus $commandBus,
        EloquentVendorRepository $vendorRepository,
        EloquentVendorTypeRepository $vendorTypeRepository 
    ) {
        $this->commandBus = $commandBus;
       $this->vendorRepository = $vendorRepository;
       $this->vendorTypeRepository = $vendorTypeRepository;

    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
      
        $types = VendorType::lists('id','name');
        
    	
    	$vendorCollection = \Excel::load(base_path().'/resources/assets/Vendor.csv')->get()->toArray();


    	foreach ($vendorCollection as $key => $vendor) {
    		$vendorData = [
    			
                'countryCode'=>$vendor['country'],
    			'name' => $vendor['supplier_name'],

    			'code' => $this->commandBus->execute(new GenerateVendorCodeCommand($vendor['supplier_name'])),
                'capabilities' => $this->getCapabilities()
                ];
        
    		$newVendor = $this->commandBus->execute(new CreateVendorCommand($vendorData));

          
            $vendor['business_type'] = strtoupper($vendor['business_type']);
            if($vendor['business_type']){
                  
                $this->vendorRepository->addTypes($newVendor->id, [$types[$vendor['business_type']]]);
            }
           
            
    	}
         

          foreach ($vendorCollection as $key => $vendorP) {
           $vendor = \App\Vendor::where('name', $vendorP['supplier_name'])->first();
          	$vendorPartnerData =[
                'name' => $vendorP['contact_name'],
                'role'=>$vendorP['title'],
                'contact' => [
                    [
                      'email1'=>$vendorP['e_mail_address'],
                      'mobileNumber1'=>$vendorP['telephone_number']
                    ]
                ],
                'address' => [
                    [
                        'line1'=>$vendorP['manufacturer_address'],
                        'city'=>$vendorP['city'],
                        'state'=>$vendorP['province'],
                    ]
                ]
            ];
                
                  $newVendorPartner=$this->commandBus->execute(new AddVendorPartnerCommand(['partners'=>[$vendorPartnerData]], $vendor->id) );
          	
	    	}

  dd("Vendor Successfully Added.");


        
    	

       
    }

    public function getCapabilities()
    {
        
        $capabilities = [
            [
                "id"=>1,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>2,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>3,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>4,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>5,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>6,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>7,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>8,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>9,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
            "id"=>10,
            "inhouse"=> false,
            "outsource"=> false,
            "moq"=> "",
            "capacity"=> ""
            ],
            [
                "id"=>11,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>12,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>13,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>14,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ],
            [
                "id"=>15,
                "inhouse"=> false,
                "outsource"=> false,
                "moq"=> "",
                "capacity"=> ""
            ]
        ];
        return $capabilities;
    }
}
