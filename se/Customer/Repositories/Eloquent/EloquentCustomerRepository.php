<?php

namespace Platform\Customer\Repositories\Eloquent;

use App\Customer;
use Carbon\Carbon;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class EloquentCustomerRepository extends Repository implements CustomerRepository
{
     /**
     * Return the models
     * @return string
     */
    public function model()
    {
        return 'App\Customer';
    }

    /**
     * Create a new Customer
     * @param  array $data
     * @return App\Customer
     */
    public function createCustomer($data)
    {
        $data = [
            'id' => $this->generateUUID(),
            'code' => $data->code,
            'name' => $data->name,
            'business_entity' => $data->business_entity,
            'import_export_license' => $data->import_export_license,
            'tax_id' => $data->tax_id,
            'vat_sales_tax_reg' => $data->vat_sales_tax_reg,
            'company_reg' => $data->company_reg
        ];

        return $this->create($data);
    }

    /**
     * Get all the customers
     * @return App\Customer
     */
    public function getAllcustomers($command)
    {
        return $this->model->orderBy('updated_at', 'desc')->paginate($command->item);
    }

    /**
     * Get the customers
     * @return App\Customer
     */
    public function showCustomer($command)
    {
        return $this->model->where('id', '=', $command->customerId)->first();
    }

    /**
     * Create a new Customer
     * @param  array $data
     * @return App\Customer
     */
    public function updateCustomer($data)
    {
        $customer = [
            'id' => $data->customerId,
            'code' => $data->code,
            'name' => $data->name,
            'business_entity' => $data->business_entity,
            'import_export_license' => $data->import_export_license,
            'tax_id' => $data->tax_id,
            'vat_sales_tax_reg' => $data->vat_sales_tax_reg,
            'company_reg' => $data->company_reg
        ];

        return $this->model->where('id', '=', $data->customerId)->update($customer);
    }

    /**
     * Get all the customers
     * @return boolean
     */
    public function deleteCustomer($command)
    {
        $this->model->find($command->customerId)->brands()->delete();
        $this->model->find($command->customerId)->contacts()->detach();
        $this->model->find($command->customerId)->addresses()->detach();
        $this->model->find($command->customerId)->orders()->delete();
        return $this->model->where('id', '=', $command->customerId)->delete();
    }

    /**
     * @param  array $data
     * @return mixed
     */
    public function filterCustomer($data)
    {
        $item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
        return $this->filter($data)->paginate($item);
    }

    /**
     * @param string $customer_id [description]
     * @param int $address_id  [description]
     */
    public function addAddress($customer_id, $address_id, $activity = 'updated')
    {
        $customer = $this->model->find($customer_id);
        $customer->addresses()->attach([$address_id]);
        if ($activity === 'updated') {
            $customer->setCustomMessage('Customer Address added/updated')->recordCustomActivity($customer, ['contact'], $activity);
        }
    }

    /**
     * @param string $customer_id [description]
     * @param int $contact_id  [description]
     */
    public function addContact($customer_id, $contact_id, $activity = 'updated')
    {
        $customer = $this->model->find($customer_id);
        $customer->contacts()->sync([$contact_id]);
        if ($activity === 'updated') {
            $customer->setCustomMessage('Customer Contact added/updated')->recordCustomActivity($customer, ['contact'], $activity);
        }
    }

     /**
     * @param string $customer_id [description]
     * @param array $type_id  [description]
     */
    public function addTypes($customer_id, $type_id, $activity = 'updated')
    {
        $customer = $this->model->find($customer_id);
        $customer->types()->sync($type_id);
        if ($activity === 'updated') {
            $customer->recordCustomActivity($customer, ['types', $type_id, false], $activity);
        }
    }

    /**
     * @param string $customer_id  [description]
     * @param array $service_id [description]
     */
    public function addServices($customer_id, $service_id, $activity = 'updated')
    {
        $customer = $this->model->find($customer_id);
        $customer->services()->sync($service_id);
        if ($activity === 'updated') {
            $customer->recordCustomActivity($customer, ['services', $service_id, false], $activity);
        }
    }

    /**
     * @param string $customer_id     [description]
     * @param array $requirment_id [description]
     */
    public function addRequirements($customer_id, $requirment_id, $activity = 'updated')
    {
        $customer = $this->model->find($customer_id);
        $customer->requirements()->sync($requirment_id);
        if ($activity === 'updated') {
            $customer->recordCustomActivity($customer, ['requirements', $requirment_id, false], $activity);
        }
    }

    /**
     * @param string $customer_id        [description]
     * @param array $payment_terms_id [description]
     */
    public function addPaymentTerms($customer_id, $payment_terms_id, $activity = 'updated')
    {
        $customer = $this->model->find($customer_id);
        $customer->paymentTerms()->sync($payment_terms_id);
        if ($activity === 'updated') {
            $customer->recordCustomActivity($customer, ['paymentTerms', $payment_terms_id, false], $activity);
        }
	}

	/**
	 * Get customer code based on customerId
	 *
	 * @param string $customerId
	 * @return mixed
	 */
	public function getCodeById($customerId) {
		$customer = $this->find($customerId);
		if ($customer) {
			return $customer->code;
		}
		return false;
	}

    /**
     * Update Archived data
     * @param  array $data
     * @return App\Vendor
     */
    public function updateArchiveCustomer($archivedDate , $customerId)
    {
        $vendor = [
            'archived_at' => $archivedDate,
        ];
        //dd($vendor);
        return $this->model->where('id', '=', $customerId)->update($vendor);
    }

    /**
     * Links a user to customer
     *
     * @param App\Customer $customer
     * @param string $userId
     */
    public function linkUser($customer, $userId)
    {
        return $customer->users()->sync([$userId], false);
    }
}
