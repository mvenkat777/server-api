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
use Platform\Vendor\Commands\AddOrUpdateVendorAddressCommand;
use Carbon\Carbon;


class VendorDbSeeder extends Seeder
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
       $now =  Carbon::now();
      \DB::statement("UPDATE vendors SET archived_at ='".$now ."' ");
      //dd()
        $types = VendorType::lists('name', 'id')->toArray();
        // dd(array_search('FABRIC', $types));
        // \DB::beginTransaction();
        $vendorCollection = \Excel::load(base_path().'/resources/assets/vendordbincn.csv')->get()->toArray();
           // dd($vendorCollection);

        foreach ($vendorCollection as $key => $vendor) {
            // dd($vendor['business_type']);
            if (array_search(strtoupper($vendor['business_type']), $types)) {
                $vendorType = [
                        array_search(strtoupper($vendor['business_type']), $types),
                        array_search('FABRIC', $types)
                    ];
            }else {
                $vendorType = [
                        array_search('FABRIC', $types)
                    ];
            }
               
            
                $vendorData = [
                    
                    'countryCode'=>$vendor['country'],
                    'name' => $vendor['supplier_name'],

                    'code' => $vendor['supplier'],
                    'capabilities' => $this->getCapabilities(),
                    'types' => $vendorType,
                    'contact' =>[
                        [
                        "label"=>$vendor['contact_name'],
                        "designation"=>$vendor['title'],
                        "email1"=>$vendor['e_mail_address'],
                        "email2"=>"",
                        "mobileNumber1"=>"",
                        "mobileNumber2"=>"",
                        "mobileNumber3"=>"",
                        "skypeId"=>""
                        ]
                    ],
                    'addresses' => [
                        [
                            "label"=>"registered",
                            "line1"=>$vendor['manufacturer_address'],
                            "line2"=>"",
                            "city"=>$vendor['city'],
                            "state"=>$vendor['province'],
                            "zip"=>NULL,
                            "country"=>"",
                            "phone"=>$vendor['telephone_number'],
                            "isPrimary"=>true
                            
                        ],[
                            "label"=>"mailing",
                            "line1"=>"",
                            "line2"=>"",
                            "city"=>"",
                            "state"=>"",
                            "zip"=>NULL,
                            "country"=>"",
                            "phone"=>"",
                            "isPrimary"=>false
                        ],
                        [
                            "label"=>"factory",
                            "line1"=>"",
                            "line2"=>"",
                            "city"=>"",
                            "state"=>"",
                            "zip"=>NULL,
                            "country"=>"",
                            "phone"=>"",
                            "isPrimary"=>false
                        ]
                    ]
                ];
            // dd($vendorData);
            $newVendor = $this->commandBus->execute(new CreateVendorCommand($vendorData));
        }
        dd('Successfully Seeded');

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
