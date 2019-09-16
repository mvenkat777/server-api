<?php

namespace Platform\Orders\Repositories\Eloquent;

use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Orders\Commands\CreateOrderCommand;
use Platform\Orders\Repositories\Contracts\OrderRepository;

class EloquentOrderRepository extends Repository implements OrderRepository
{
    /**
     * @return Order
     */
    public function model()
    {
        return 'App\Order';
    }

    /**
     * @param CreateOrderCommand $command
     * @return mixed
     */
    public function makeOrder($order)
    {
        $order['id'] = $this->generateUUID();
        return $this->create($order);
    }
    /**
     * @param ShowOrderByIdCommand $command
     * @return all
     */

    public function showOrder($orderId)
    {
            $data = $this->model->where('id', '=', $orderId)->first();
            return $data;
    }

    /**
     * @param getRequestedStatus $command
     * @return mixed
     */
    public function getAllOrders($command)
    {
        $data = $this->model->orderBy('updated_at', 'desc')->paginate($command->paginate);
        return $data;
    }

    /**
     * @param UpdateOrderCommand $command
     * @return 1
     */

    public function updateOrder($command)
    {
        $order = [
            'customer_id' => $command->customerId,
            'value' => $command->value,
            'quantity' => $command->quantity,
            'size' => $command->size,
            'expected_delivery_date' => $command->expectedDeliveryDate
        ];
        return $this->model->where('id', '=', $command->orderId)
                    ->where('customer_id', '=', $command->customerId)
                    ->update($order);
    }

    /**
     * @param DeleteOrderCommand $command
     * @return 1
     */
    public function deleteOrder($orderId)
    {
        $this->model->find($orderId)->vendors()->detach();
        return $this->model->where('id', '=', $orderId)->first()->delete();
    }

    /**
     * @param int $orderId  [description]
     * @param array $vendorId [description]
     */
    public function addVendors($orderId, $vendorId)
    {
        return $this->model->find($orderId)->vendors()->sync($vendorId);
    }

    /**
     * @param int $orderId  [description]
     * @param array $techpackId [description]
     */
    public function addTechpacks($orderId, $techpackId)
    {
        return $this->model->find($orderId)->techpacks()->sync($techpackId);
    }

    /**
     * @param  string $customerId
     * @return mixed
     */
    public function searchOrder($customerId)
    {
        return $this->model->where('customer_id', '=', $customerId)->get();
    }

    /**
     * @param  array $data
     * @return mixed
     */
    public function filterOrder($data)
    {
        $item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
        return $this->filter($data)->paginate($item);
    }
}
