<?php

namespace Platform\Shipments\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Exceptions\SeException;
use Platform\App\Events\EventGenerator;
use Platform\Shipments\Repositories\Contracts\ShipmentRepository;

class UpdateShipmentCommandHandler implements CommandHandler
{

    private $shipmentRepository;

    /**
     * @param ShipmentRepository
     */
    public function __construct(ShipmentRepository $shipmentRepository)
    {
        $this->shipmentRepository = $shipmentRepository;
    }

    /**
     * @param  getRequestedStatus
     * @return mixed
     */
    public function handle($command)
    {

        $data = $this->shipmentRepository->updateTrackingStatus($command);

        if($data)
        {
            $response='Updated Successfully';
            
            return $response;
        }
        else
        {
            throw new SeException('shipmentId does not exist', 404, 3450100);
        }
    }

}

