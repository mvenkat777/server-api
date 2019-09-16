<?php

namespace Platform\Payment\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Payment\Commands\MakeNewPaymentCommand;
use Platform\Payment\Repositories\Contracts\PaymentRepository;
use Platform\Users\Repositories\Contracts\UserRepository;
use Platform\Payment\PaymentLinkWasCreated;
use Platform\App\RuleCommanding\DefaultRuleBus;

class MakeNewPaymentCommandHandler implements CommandHandler
{
    use EventGenerator;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;

    /**
     * @var AddressRepository
     */
    private $addressRepository;

     /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param EventDispatcher
     * @param AddressRepository
     */
    public function __construct(EventDispatcher $dispatcher, PaymentRepository $paymentRepository ,
                                UserRepository $userRepository, DefaultRuleBus $defaultRuleBus)
    {
        $this->dispatcher = $dispatcher;
        $this->paymentRepository = $paymentRepository;
        $this->userRepository = $userRepository;
        $this->defaultRuleBus = $defaultRuleBus;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $userObj = $this->userRepository->getUserByEmail($command);
        $data =$this->paymentRepository->makePayment($command ,$userObj);
        if(is_null($command->uploadLinkObject)){
                if(!is_null($data->user_object)){
                    $data->user_object = $data->user_object->toArray();
                }
        }
        else{
                if(!is_null($data->user_object)){
                    $data->user_object = json_decode($data->user_object);
                }
        }

        $this->defaultRuleBus->setReceiver($command->email)->execute('makeNewPayment', $data);
        return $data->product_link;
    }
}

