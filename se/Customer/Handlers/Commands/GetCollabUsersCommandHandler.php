<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Repositories\Contracts\CollabRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;

class GetCollabUsersCommandHandler implements CommandHandler 
{
    private $customer;
    private $collab;

	public function __construct(CustomerRepository $customer, CollabRepository $collab)
	{
        $this->customer = $customer;
        $this->collab = $collab;
	}

    /**
     * Handles getting user list for customer
     *
     * @param mixed $command
     */
	public function handle($command)
	{
        $customer = $this->customer->find($command->customerId);
        $collab = $this->collab->getByCustomerId($command->customerId);
        if ($customer) {
            if ($collab) {
                $users = $customer->users()->with(['collabInvites' => function ($query) use ($collab) {
                    $query->where('collab_id', $collab->id);
                }])->get();;
            }
            return $users;
        }
        return false;
	}

}
