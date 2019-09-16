<?php

namespace Platform\Orders\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Orders\Repositories\Contracts\OrderRepository;
use Platform\App\Exceptions\SeException;

class ShowOrderByIdCommandHandler implements CommandHandler
{
    
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
     * @param  ShowOrderByIdCommand
     * @return mixed
     */
    public function handle($command)
    {
        $data = $this->orderRepository->showOrder($command->id);
        if($data != NULL)
        {
            return $data;
        }
        else
        {
            throw new SeException('orderId does not exist', 404, 8520100);
        }
    }
}

