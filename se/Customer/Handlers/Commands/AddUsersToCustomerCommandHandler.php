<?php

namespace Platform\Customer\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Customer\Repositories\Contracts\CustomerRepository;
use Platform\Users\Repositories\Contracts\UserRepository;

use Rhumsaa\Uuid\Uuid;

class AddUsersToCustomerCommandHandler implements CommandHandler 
{
    /**
     * @var CustomerRepository
     */
    public $customer;

    /**
     * @var UserRepository
     */
    public $user;

    /**
     * @param CustomerRepository $customer
     */
    public function __construct(
        CustomerRepository $customer,
        UserRepository $user
    ) {
        $this->customer = $customer;
        $this->user = $user;
	}

    /**
     * Handle adding users to customer
     *
     * @param AddUsersToCustomerCommand $command
     */
	public function handle($command)
	{
        $users = $command->users;
        $customer = $this->customer->find($command->customerId);
        if (!$customer) {
            throw new SeException("Customer not found.", 404);
        }

        if (is_array($users))
        {
            foreach ($users as $user)
            {
                $user = $this->createAndLinkUser($user, $customer);
            }
        } else {
            $this->createAndLinkUser($users, $customer);
        } 
        
        return $customer->users()->get();
	}
    
    /**
     * Create and add link user to customer
     *
     * @param mixed $user
     * @param mixed $customerId
     */
    public function createAndLinkUser($user, $customer)
    {
        $existingUser = $this->user->getByEmail($user['email']);
        if (!$existingUser) {
            $data = [
                'id' => Uuid::uuid4()->toString(), 
                'display_name' => $user['displayName'],
                'email' => $user['email'],
            ];
            $existingUser = \App\User::create($data);
        }

        $this->customer->linkUser($customer, $existingUser->id);
        return $existingUser;
    }
}
