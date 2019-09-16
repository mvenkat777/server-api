<?php

namespace Platform\CollabBoard\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\CollabBoard\Repositories\Contracts\CollabInviteRepository;
use Platform\App\Exceptions\SeException;
use Platform\Customer\Repositories\Contracts\CustomerRepository;
use Platform\CollabBoard\Mailer\CollabMailer;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Customer\Repositories\Contracts\CollabRepository;

class InviteUserToCollabCommandHandler implements CommandHandler 
{
    /**
     * @var Platform\CollabBoard\Repositories\Contracts\CollabInviteRepository
     */
    private $collabInviteRepository;
    
    /**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @var CollabRepository
     */
    private $collabRepository;

    /**
     * @var CollabMailer
     */
    private $collabMailer;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param CollabInviteRepository $collabInviteRepository
     * @param CollabRepository $collabRepository
     * @param CustomerRepository $customerRepository
     * @param CollabMailer $collabMailer
     * @param UserRepository $collabMailer
     */
    public function __construct(
        CollabInviteRepository $collabInviteRepository, 
        CollabRepository $collabRepository, 
        CustomerRepository $customerRepository,
        CollabMailer $collabMailer,
        UserRepository $userRepository
    ) {
        $this->collabInviteRepository = $collabInviteRepository;
        $this->collabRepository = $collabRepository;
        $this->customerRepository = $customerRepository;
        $this->collabMailer = $collabMailer;
        $this->userRepository = $userRepository;
	}

    /**
     * Handle inviting users to collab
     *
     * @param mixed $command
     */
	public function handle($command)
	{
        $customerId = $command->customerId;
        $userId = $command->user['userId'];
        $permission = $command->user['permission'];

        $customer = $this->customerRepository->find($customerId);
        if (!$customer) {
            throw new SeException("Customer not found.", 404);
        }
        
        $collab = $customer->collab;
        if (!$collab) {
            throw new SeException("Collab not activated for this customer.", 404);
        }

        $userIds = $customer->users()->get();
        if (!$userIds->contains($userId)) {
            throw new SeException("User not found in customer users list.", 404);
        }

        $alreadyInvited = $this->collabInviteRepository->getByCollabIdAndUserId($collab->id, $userId);
        if ($alreadyInvited) {
            throw new SeException("User is already invited to the collab.", 409);
        }

        $invited = $this->collabInviteRepository->inviteUser($collab->id, $userId, $permission);
        if ($invited) {
            $this->sendMail($invited);
            return $invited;
        }
        return false;
	}

    /**
     * Sent a mail to user with invite link
     *
     * @param Platform\CollabBoard\Models\CollabInvite $invited
     */
    public function sendMail($invited)
    {
        $user = $this->userRepository->find($invited->user_id);
        $collab = $this->collabRepository->find($invited->collab_id);
        $link = 'http://' . $collab->url . '.sourceeasy.com/invite/' . $invited->invite_code;

        $data = [
            'user' => $user,
            'customerName' => ucwords(strtolower($collab->customer->name)),
            'link' => $link,
        ];
        $this->collabMailer->invite($user, $data);
    }
}
