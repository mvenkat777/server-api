<?php

namespace Platform\Vendor\Repositories\Eloquent;

use App\Bank;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Vendor\Repositories\Contracts\BankRepository;

class EloquentBankRepository extends Repository implements BankRepository
{
     /**
     * Return the models
     * @return string
     */
    public function model()
    {
        return 'App\BankDetail';
    }

   /**
    * @param string $bank 
    * @param int $id    
    */
    public function addBankDetails($bank)
    {
        $data = [
            'name_on_account' => $bank['nameOnAccount'],
            'bank_name' => $bank['bankName'],
            'account_number' => $bank['accountNumber'],
            'account_type' => $bank['accountType'],
            'branch_address' => isset($bank['branchAddress']) ? $bank['branchAddress'] : NULL,
            'bank_code' => $bank['swiftCode'],
            'note' => $bank['note']
        ];
        return $this->create($data);
    }

    /**
    * @param string $brand 
    * @param int $id    
    */
    public function updateBank($bank)
    {
        $data = [
            'name_on_account' => $bank['nameOnAccount'],
            'bank_name' => $bank['bankName'],
            'account_number' => $bank['accountNumber'],
            'account_type' => $bank['accountType'],
            'branch_address' => $bank['branchAddress'],
            'bank_code' => $bank['swiftCode'],
            'note' => $bank['note']
        ];
        return $this->model->where('id', '=', $bank['bankId'])
                    ->update($data);
    }

    /**
     * @param string $vendorId 
     * @param int $address_id  
     */
    public function addAddress($bank_id, $address_id)
    {
        $this->model->find($bank_id)->addresses()->sync([$address_id]);
    }

    public function delete($bankId)
    {
        return $this->model->where('id', '=', $bankId)->delete();
    }

    public function deleteBanks($banks)
    {
        return $this->model->wherein('id', $banks)->delete();
    }
}