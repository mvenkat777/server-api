<?php

namespace Platform\Shipments\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Shipments\Repositories\Contracts\ShipmentRepository;

class AllShipmentListCommandHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
    private $shipmentRepository;

    /**
     * @param ShipmentRepository
     */
    public function __construct(ShipmentRepository $shipmentRepository)
    {
        $this->shipmentRepository = $shipmentRepository;
       
    }

    /**
     * @param  AllShipmentListCommand
     * @return mixed
     */
    public function handle($command)
    {
        return $this->shipmentRepository->getAllShipments($command);
    }

}

