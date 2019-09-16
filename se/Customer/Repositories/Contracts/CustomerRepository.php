<?php

namespace Platform\Customer\Repositories\Contracts;

interface CustomerRepository
{
    /**
     * Return the models
     * @return string
     */
    public function model();

    /**
     * Create a new Customer
     * @param  array $data
     * @return App\Customer
     */
	public function createCustomer($data);

	/**
	 * Get customer code based on customerId 
	 *
	 * @param string $customerId
	 * @return mixed
	 */
	public function getCodeById($customerId);
}
