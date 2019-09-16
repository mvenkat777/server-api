<?php

namespace Platform\Orders\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Exceptions\SeException;
use Platform\App\Events\EventGenerator;
use Platform\Orders\Repositories\Contracts\OrderRepository;

class UpdateOrderCommandHandler implements CommandHandler
{
    /**
     * @var OrderRepository
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
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {

        $data = $this->orderRepository->updateOrder($command);

        if($data)
        {   
            $this->addOrderVendor($command);
            $this->addOrderTechpack($command);
            $response='Updated Successfully';
            
            return $response;
        }
        else
        {
            throw new SeException('OrderId does not exist', 404, 8520100);
        }
    }

    public function addOrderVendor($command)
    {
        $this->orderRepository->addVendors($command->orderId, $command->vendorId);
    }

    public function addOrderTechpack($command)
    {
        $this->orderRepository->addTechpacks($command->orderId, $command->techpackId);
    }

}

