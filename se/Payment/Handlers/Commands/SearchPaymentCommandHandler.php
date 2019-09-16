<?php

namespace Platform\Payment\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Payment\Repositories\Contracts\PaymentRepository;

class SearchPaymentCommandHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $paymentRepository;

    /**
     * @param PaymentRepository
     */
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {
        return $this->paymentRepository->searchPaymentByRequestParameter($command);
    }

}

