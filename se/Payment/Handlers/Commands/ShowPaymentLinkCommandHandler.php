<?php

namespace Platform\Payment\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Payment\Repositories\Contracts\PaymentRepository;

class ShowPaymentLinkCommandHandler implements CommandHandler
{
    use EventGenerator;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var UserRepository
     */
    private $paymentRepository;

    /**
     * @param EventDispatcher
     * @param AddressRepository
     */
    public function __construct(EventDispatcher $dispatcher, PaymentRepository $paymentRepository)
    {
        $this->dispatcher = $dispatcher;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {   
        return $this->paymentRepository->showLinkContent($command);
    }
}

