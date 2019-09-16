<?php

namespace Platform\Shipments\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Shipments\Repositories\Contracts\ShipmentRepository;
use Platform\App\Exceptions\SeException;

class ShowShipmentByIdCommandHandler implements CommandHandler
{
    
    private $shipmentRepository;

    /**
     * @param EventDispatcher
     * @param AddressRepository
     */
    public function __construct(ShipmentRepository $shipmentRepository)
    {
        $this->shipmentRepository = $shipmentRepository;
    }

    /**
     * @param  CreateUserCommand
     * @return mixed
     */
    public function handle($command)
    {
        $data = $this->shipmentRepository->showShipment($command);
        if($data != NULL)
        {
            return $data;
        }
        else
        {
            throw new SeException('shipmentId does not exist', 404, 3450100);
        }
    }
}

