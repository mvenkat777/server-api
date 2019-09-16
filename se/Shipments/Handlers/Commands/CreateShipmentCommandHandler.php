<?php

namespace Platform\Shipments\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Events\EventDispatcher;
use Platform\App\Events\EventGenerator;
use Platform\Shipments\Repositories\Contracts\ShipmentRepository;
use Platform\App\RuleCommanding\DefaultRuleBus;


class CreateShipmentCommandHandler implements CommandHandler
{
 
    /**
     * @var Platform\Shipments\Repositories\Contracts\ShipmentRepository
     */
    private $shipmentRepository;

    /**
     * @var DefaultRuleBus
     */
    protected $defaultRuleBus;

    /**
     * @param ShipmentRepository
     */
    public function __construct(ShipmentRepository $shipmentRepository, DefaultRuleBus $defaultRuleBus)
    {
        
        $this->shipmentRepository = $shipmentRepository;
       $this->defaultRuleBus = $defaultRuleBus;
    }

    /**
     * @param  CreateShipmentCommand
     * @return mixed
     */
    public function handle($command)
    {   
        $data = $this->shipmentRepository->makeShipment($command);
        $data->sender_email = \Auth::user()->email;
        $data->shipment_link = $_SERVER['HTTP_ORIGIN'].'/#/shipments/details/'.$data->id;
        $this->defaultRuleBus->setReceiver(\Auth::user()->email)->execute('createNewShipment', $data);
        return $data;
    }
}

