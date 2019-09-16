<?php

namespace Platform\Payment\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Payment\RequestWasSuccessful;
use Platform\Payment\RequestWasFailed;
use Platform\Payment\Repositories\Contracts\PaymentRepository;
use Platform\App\RuleCommanding\DefaultRuleBus;

class GetRequestStatusCommandHandler implements CommandHandler
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
     * @var UserRepository
     */
    private $paymentRepository;

    /**
     * @param EventDispatcher
     * @param AddressRepository
     */
    public function __construct(EventDispatcher $dispatcher, PaymentRepository $paymentRepository, 
        DefaultRuleBus $defaultRuleBus)
    {
        $this->dispatcher = $dispatcher;
        $this->paymentRepository = $paymentRepository;
        $this->defaultRuleBus = $defaultRuleBus;
    }

    /**
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {
        $data = $this->paymentRepository->getRequestedStatus($command);
        if($command->paymentStatus == '1' && $data->getData()->status_code == 200)
        {
            $this->defaultRuleBus->setReceiver($data->getData()->data->email)->execute('requestWasSuccessful', $data->getData()->data);
            return $data;
        }
        if($command->paymentStatus == '0' && $data->getData()->status_code == 200)
        {
            $data1 = $data->getData()->data;
            $data1->product_link = 'http://payments.sourceeasy.com/?paymentId='.$data1->product_link;
            $this->defaultRuleBus->setReceiver($data->getData()->data->email)
                ->execute('requestWasFailed', $data1);
            return $data;
        }
        else{
            return $data;
        }
    }

}

