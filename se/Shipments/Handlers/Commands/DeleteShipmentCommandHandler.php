<?php

namespace Platform\Shipments\Handlers\Commands;

use Platform\App\Exceptions\SeException;
use Platform\App\Commanding\CommandHandler;
use Platform\Shipments\Repositories\Contracts\ShipmentRepository;

class DeleteShipmentCommandHandler implements CommandHandler
{
    /**
     * @var UserRepository
     */
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
        $get = $this->shipmentRepository->deleteShipment($command);
        if($get)
        {
            $response='Deleted Successfully';
            return $response;
        }
        else
        {
            throw new SeException('shipmentId does not exist', 404, 3450100);
        }
    }
}

