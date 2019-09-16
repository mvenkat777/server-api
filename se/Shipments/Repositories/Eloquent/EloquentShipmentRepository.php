<?php

namespace Platform\Shipments\Repositories\Eloquent;

use App\Shipment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Shipments\Commands\CreateShipmentCommand;
use Platform\Shipments\Repositories\Contracts\ShipmentRepository;

class EloquentShipmentRepository extends Repository implements ShipmentRepository
{
    /**
     * @return shipment
     */
    public function model()
    {
        return 'App\Shipment';
    }

    /**
     * @param CreateShipmentCommand $command
     * @return mixed
     */
    public function makeShipment(CreateShipmentCommand $command)
    {
        $shipment = [
            'id' => $this->generateUUID(),
            'shipment_type' => $command->shipmentType,
            'shipped_date' => $command->shippedDate,
            'shipped_from' => $command->shippedFrom,
            'shipped_destination'  => $command->shippedDestination,
            'item_details' => $command->itemDetails,
            'tracking_id'   => $command->trackingId ,
            'tracking_provider' => is_null($command->trackingProvider)? null : $command->trackingProvider,
            'user_id'    => $command->userId,
            'tracking_status'    =>  $command->trackingStatus,
            'techpack_id' => is_null($command->techpackID)? null : $command->techpackID,
            'product_id' => is_null($command->productId)? null : $command->productId
        ];
        $data = $this->model->create($shipment);
        return $data;
    }
    /**
     * @param ShowShipmentByIdCommand $command
     * @return all
     */

    public function showShipment($command)
    {
        $data = $this->model->where('id', '=', $command->id)->first();
        return $data;
    }

    /**
     * @param getRequestedStatus $command
     * @return mixed
     */
    public function getAllShipments($command)
    {
        return $this->model
                    ->orderBy('updated_at', 'desc')
                    ->paginate($command->paginate);
    }

    /**
     * @param UpdateShipmentCommand $command
     * @return 1
     */

    public function updateTrackingStatus($command)
    {
        return $this->update(['tracking_status'=> 'delivered'], $command->id);
    }

    /**
     * @param DeleteShipmentCommand $command
     * @return 1
     */
    public function deleteshipment($command)
    {
        return $this->model->where('id', '=', $command->id)->first()->delete();
    }

    /**
     * @param  array $data
     * @return mixed
     */
    public function filterShipment($data)
    {
        $item = isset($data['item'])? $data['item'] : config('constants.listItemLimit');
        return $this->filter($data)->paginate($item);
    }
}
