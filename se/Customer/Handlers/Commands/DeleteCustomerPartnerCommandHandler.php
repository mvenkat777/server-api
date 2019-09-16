<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Customer\Repositories\Contracts\CustomerPartnerRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class DeleteCustomerPartnerCommandHandler implements CommandHandler
{
    /**
     * $customerRepository 
     * @var object
     */
    private $customerRepository;

    /**
     * $partnerRepository 
     * @var object
     */
    private $partnerRepository;

    /**
     * @param CustomerRepository
     */
    public function __construct(CustomerRepository $customerRepository,
                                CustomerPartnerRepository $partnerRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->partnerRepository = $partnerRepository;
    }

    /**
     * @param  DeleteCustomerPartnerCommand
     * @return mixed
     */
    public function handle($command)
    {
        $customer = $this->customerRepository->showCustomer($command);
        if ($customer) {
            return $this->partnerRepository->delete($command->partnerId);
        }
        else{
            throw new SeException('partner not related with this customer', 400, 8460103);
        }
    }
}

