<?php

namespace Platform\Orders\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Orders\Repositories\Contracts\OrderRepository;

class AllOrderListCommandHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $orderRepository;

    /**
     * @param OrderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
       
    }

    /**
     * @param  AllOrderListCommand
     * @return mixed
     */
    public function handle($command)
    {
        return $this->orderRepository->getAllOrders($command);
    }

}

