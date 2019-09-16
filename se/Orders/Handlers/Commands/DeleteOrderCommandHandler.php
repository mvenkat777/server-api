<?php

namespace Platform\Orders\Handlers\Commands;

use Platform\App\Exceptions\SeException;
use Platform\App\Commanding\CommandHandler;
use Platform\Orders\Repositories\Contracts\OrderRepository;

class DeleteOrderCommandHandler implements CommandHandler
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @param EventDispatcher
     * @param OrderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $order = $this->orderRepository->showOrder($command->id);
        if($order)
        {
            $get = $this->orderRepository->deleteOrder($command->id);
            $response='Deleted Successfully';
            return $response;
        }
        else
        {
            throw new SeException('orderId does not exist', 404, 8520100);
        }
    }
}

