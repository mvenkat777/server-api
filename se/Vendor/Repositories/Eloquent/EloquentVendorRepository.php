<?php

namespace Platform\Vendor\Repositories\Eloquent;

use App\Vendor;
use Carbon\Carbon;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;

class EloquentVendorRepository extends Repository implements VendorRepository
{
     /**
     * Return the models
     * @return string
     */
    public function model()
    {
        return 'App\Vendor';
    }

    /**
     * Create a new Vendor
     * @param  array $data
     * @return App\Vendor
     */
    public function createVendor($data)
    {
        $data = [
            'id' => $this->generateUUID(),
            'code' => $data->code,
            'name' => $data->name,
            'business_entity' => $data->business_entity,
            'country_code' => $data->country_code,
            'import_export_license' => $data->import_export_license,
            'tax_id' => $data->tax_id,
            'vat_sales_tax_reg' => $data->vat_sales_tax_reg,
            'company_reg' => $data->company_reg,
            'annual_shipped_turnover' => $data->annual_shipped_turnover,
            'annual_shipped_quantity' => $data->annual_shipped_quantity
        ];

        return $this->create($data);
    }

    /**
     * @return mix
     */
    public function getAllVendor($command)
    {
        return $this->model->orderBy('updated_at', 'desc')->paginate($command->item);
    }

    /**
     * @param  array $data
     * @return mixed
     */
    public function filterVendor($data)
    {
        $item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
        return $this->filter($data)->paginate($item);
    }

    /**
     * Get the vendor
     * @return App\Vendor
     */
    public function showVendor($command)
    {
        return $this->model->where('id', '=', $command->vendorId)->first();
    }

    /**
     * Create a new vendor
     * @param  array $data
     * @return App\Vendor
     */
    public function updateVendor($data)
    {
        $vendor = [
            'id' => $data->vendorId,
            'code' => $data->code,
            'name' => $data->name,
            'business_entity' => $data->business_entity,
            'country_code' => $data->country_code,
            'import_export_license' => $data->import_export_license,
            'tax_id' => $data->tax_id,
            'vat_sales_tax_reg' => $data->vat_sales_tax_reg,
            'company_reg' => $data->company_reg,
            'annual_shipped_turnover' => $data->annual_shipped_turnover,
            'annual_shipped_quantity' => $data->annual_shipped_quantity
        ];

        $resp = $this->model->where('id', '=', $data->vendorId)->first();
        if ($resp) {
            return $resp->update($vendor);
        }
    }

    /**
     * Get all the customers
     * @return boolean
     */
    public function deleteVendor($command)
    {
        $this->model->find($command->vendorId)->contacts()->detach();
        $this->model->find($command->vendorId)->addresses()->detach();
        $this->model->find($command->vendorId)->banks()->detach();
        return $this->model->where('id', '=', $command->vendorId)->delete();
    }

    /**
     * @param string $vendor_id [description]
     * @param int $address_id  [description]
     */
    public function addAddress($vendor_id, $address_id)
    {
        $this->model->find($vendor_id)->addresses()->attach([$address_id]);
    }

    /**
     * @param string $vendor_id [description]
     * @param int $contact_id  [description]
     */
    public function addContact($vendor_id, $contact_id)
    {
        $this->model->find($vendor_id)->contacts()->sync([$contact_id], false);
    }

    /**
     * @param string $vendor_id [description]
     * @param int $bank_id  [description]
     */
    public function addBank($vendor_id, $bank_id)
    {
        $this->model->find($vendor_id)->banks()->sync([$bank_id], false);
    }

    /**
     * @param string $vendor_id [description]
     * @param array $type_id  [description]
     */
    public function addTypes($vendor_id, $type_id, $activity = 'updated')
    {
        $vendor = $this->model->find($vendor_id);
        $vendor->types()->sync($type_id);
        if ($activity === 'updated') {
            $vendor->recordCustomActivity($vendor, ['types', $type_id, false], $activity);
        }
    }

    /**
     * @param string $vendor_id  [description]
     * @param array $service_id [description]
     */
    public function addServices($vendor_id, $service_id, $activity = 'updated')
    {
        $vendor = $this->model->find($vendor_id);
        $vendor->services()->sync($service_id);
        if($activity === 'updated') {
            $vendor->recordCustomActivity($vendor, ['services', $service_id, false], $activity);
        }
    }

    /**
     * @param string $vendor_id     [description]
     * @param array $requirment_id [description]
     */
    public function addCapabilities($vendor_id, $capability_id, $activity = 'updated')
    {
        $vendor = $this->model->find($vendor_id);
        $vendor->capabilities()->sync($capability_id);
        if($activity === 'updated') {
            $vendor->recordCustomActivity($vendor, ['capabilities', $capability_id, true], $activity);
        }
    }

    /**
     * @param string $vendor_id        [description]
     * @param array $payment_terms_id [description]
     */
    public function addPaymentTerms($vendor_id, $payment_terms_id, $activity = 'updated')
    {
        $vendor = $this->model->find($vendor_id);
        $vendor->paymentTerms()->sync($payment_terms_id);
        if($activity === 'updated') {
            $vendor->recordCustomActivity($vendor, ['paymentTerms', $payment_terms_id, false], $activity);
        }
    }

    /**
     * Get the vendor
     * @return App\Vendor
     */
    public function showVendorById($vendorId)
    {
        return $this->model->where('id', '=', $vendorId)->first();
    }

    /**
     * Update Archived data
     * @param  array $data
     * @return App\Vendor
     */
    public function updateArchiveVendor($archivedDate , $vendorId)
    {
        $vendor = [
            'archived_at' => $archivedDate,
        ];
        //dd($vendor);
        return $resp = $this->model->where('id', '=', $vendorId)->update($vendor);
        // dd($resp);
        // if ($resp) {
        //     return $resp->update($vendor);
        // }
    }
}
