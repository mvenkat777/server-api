<?php

namespace Platform\Vendor\Transformers;

use App\BankDetail;
use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use Platform\Address\Transformers\AddressTransformer;

class BankTransformer extends TransformerAbstract
{
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(BankDetail $bank)
    {
        $addresses = $this->collection($bank->addresses, new AddressTransformer);
        $addresses = $this->manager->createData($addresses)->toArray();

        return [
        	'bankId'=> $bank->id,
            'nameOnAccount' => $bank->name_on_account,
            'bankName' => $bank->bank_name,
            'accountNumber' => $bank->account_number,
            'accountType' => $bank->account_type,
            'branchAddress' => $bank->branch_address,
            'swiftCode' => $bank->bank_code,
            'note' => $bank->note,
            'address' => isset($addresses['data'][0])?$addresses['data'][0]:[]

        ];
    }
}
