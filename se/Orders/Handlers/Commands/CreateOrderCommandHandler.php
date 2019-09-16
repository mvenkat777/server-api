<?php

namespace Platform\Orders\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Customer\Repositories\Contracts\CustomerRepository;
use Platform\Orders\Repositories\Contracts\OrderRepository;
use Platform\Orders\Validators\Orders;



class CreateOrderCommandHandler implements CommandHandler
{
 
    /**
     * @var Platform\Orders\Repositories\Contracts\OrderRepository
     */
    private $orderRepository;

    /**
     * @var Platform\Customer\Repositories\Contracts\CustomerRepository
     */
    private $customerRepository;

    /**
     * @param OrderRepository
     */
    public function __construct(OrderRepository $orderRepository,
                                CustomerRepository $customerRepository,
                                Orders $order)
    {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->order = $order;
    }

    /**
     * @param  CreateOrderCommand
     * @return mixed
     */
    public function handle($command)
    {  
       \DB::beginTransaction(); 
        $customer = $this->customerRepository->showCustomer($command);
        $order1 = $this->Validation($command, $customer);
        $order = $this->orderRepository->makeOrder($order1);
        $this->addOrderVendor($order, $command);
        $this->addOrderTechpack($order, $command);

        \DB::commit();
        return $order;
    }

    public function addOrderVendor($order, $command)
    {
        $this->orderRepository->addVendors($order->id, $command->vendorId);
    }

    public function addOrderTechpack($order, $command)
    {
        $this->orderRepository->addTechpacks($order->id, $command->techpackId);
    }

    public function Validation($command, $customer)
    {
        $label = implode([$customer['name'],$command->size,$command->quantity], '-');
        $order = [
            'code' => config('prefix')['order'] . $customer['code'].'-'.(substr(md5(time()), 0, 5)),
            'label' => trim(preg_replace("/[-]+/", "-", $label), '-'),
            'customer_id' => $command->customerId,
            'value' => $command->value,
            'quantity' => $command->quantity,
            'size' => $command->size,
            'expected_delivery_date' => $command->expectedDeliveryDate
        ];
        $this->order->validate($order); 
        return $order;
    }
}

