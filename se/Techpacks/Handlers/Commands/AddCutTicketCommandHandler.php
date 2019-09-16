<?php

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Techpacks\Repositories\Contracts\CutTicketRepository;

class AddCutTicketCommandHandler implements CommandHandler 
{
    /**
     * @param CutTicketRepository $cutTicket
     * @return void
     */
    public function __construct(CutTicketRepository $cutTicket)
    {
        $this->cutTicket = $cutTicket;
    }

    public function handle($command)
    {
        $techpackId = $command->techpackId;
        $billOfMaterials = $command->billOfMaterials;
        $cutTickets = $command->cutTickets;

        // foreach ($billOfMaterials as $billOfMaterial) {
        //     if ($billOfMaterial['label'] == 'FABRIC') {
        //         $fabricDescriptions = $this->getFabricDescription($billOfMaterial);
        //     }   
        // }
        // $cutTicketFebrics = $this->getCutTicketFebric($cutTickets);

        // if (!empty(array_diff($cutTicketFebrics, $fabricDescriptions))) {
        //     throw new SeException("Fabric is not present", 422, 4220901);
        // }
        $this->removeCutTickets($cutTickets, $techpackId);
        $this->cutTicket->addCutPieces($cutTickets, $techpackId);
    }

    /**
     * Get the fabric placements data from bill of materials
     *
     * @param array $billOfMaterial
     * @return array
     */
    private function getFabricDescription($billOfMaterial)
    {
        $fabricDescriptions = [];
        
        foreach ($billOfMaterial['rows'] as $row) {
            $description = $row['description'];
            if ($description != '') {
                array_push($fabricDescriptions, $description);
            }
        }
        return $fabricDescriptions;
    }

    /**
     * @param  array $cutTickets 
     * @return array             
     */
    public function getCutTicketFebric($cutTickets)
    {
        $cutTicketFebrics = [];

        foreach ($cutTickets as $cutTicket) {
            if ($cutTicket['fabric'] != NULL) {
                array_push($cutTicketFebrics, $cutTicket['fabric']);
            }
            if(!isset($cutTicket['name']) || empty($cutTicket['name'])) {
                throw new SeException("Place Name requied", 422, 4220903);
            }
        }
        return $cutTicketFebrics;
    }

    // private function addCutTicketsFromPlacements($fabricPlacements, $techpackId) {
    //     $this->cutTicket->addCutPieces($fabricPlacements, $techpackId); 
    // }    

    /**
     * Deletes the cut tickets based on the difference between the existing
     * and new cuttickets
     *
     * @param mixed $newCutTickets
     * @param mixed $techpackId
     * @return void
     */
    private function removeCutTickets($newCutTickets, $techpackId) {
        $newCutTickets = collect($newCutTickets);
        $cutTicketIds = $newCutTickets->pluck('id')->all();
        $this->cutTicket->removeCutPieces($cutTicketIds, $techpackId); 
    }    

}
