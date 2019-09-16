<?php

namespace Platform\Techpacks\Repositories\Eloquent;

use App\CutTicket;
use Platform\App\Exceptions\SeException;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Techpacks\Repositories\Contracts\CutTicketRepository;

class EloquentCutTicketRepository extends Repository implements CutTicketRepository 
{
    public function model(){
            return 'App\CutPiece';
    }

    /**
     * bulk add cut pieces
     *
     * @param array $cutPieces
     * @param string $techpackId
     * @return void
     */
    public function addCutPieces($cutPieces, $techpackId) {
        foreach ($cutPieces as $cutPiece) {
            $existingCutPiece = $this->getByNameAndTechpackId($cutPiece['name'], $techpackId);
            if ($existingCutPiece) {
                $this->updateExisting($existingCutPiece->id, $cutPiece);
            } else {
                $this->addCutPiece($cutPiece, $techpackId);
            }
        }
    }    

    /**
     * Add a single cut piece
     *
     * @param array $cutPiece
     * @param string $techpackId
     * @return App\CutPiece
     */
    public function addCutPiece($cutPiece, $techpackId)
    {
        $data = [
            'id' => $this->generateUUID(),
            'name' => $cutPiece['name'],
            'techpack_id' => $techpackId,
            'image' => (isset($cutPiece['image']) && !empty($cutPiece['image'])) ? json_encode($cutPiece['image']) : null,
            'amount' => isset($cutPiece['amount']) && !empty($cutPiece['amount']) ? $cutPiece['amount'] : null,
            'fabric' => isset($cutPiece['fabric']) && !empty($cutPiece['fabric']) ? $cutPiece['fabric'] : null,
            'non_flip' => isset($cutPiece['nonFlip']) && !empty($cutPiece['nonFlip']) ? $cutPiece['nonFlip'] : null,
            'x' => isset($cutPiece['x']) && !empty($cutPiece['x']) ? $cutPiece['x'] : null,
            'y' => isset($cutPiece['y']) && !empty($cutPiece['y']) ? $cutPiece['y'] : null,
            'xy' => isset($cutPiece['xy']) && !empty($cutPiece['xy']) ? $cutPiece['xy'] : null,
        ];
         return $this->create($data);
    }

    /**
     * Update an already found cut piece
     *
     * @param App\CutPiece $existingCutPiece
     * @param array $cutPiece
     * @return void
     */
    private function updateExisting($id, $cutPiece)
    {
        $data = [
            'name' => $cutPiece['name']  ,
            'image' => isset($cutPiece['image']) ? json_encode($cutPiece['image']) : null,
            'amount' => isset($cutPiece['amount']) ? $cutPiece['amount'] : null,
            'fabric' => isset($cutPiece['fabric']) ? $cutPiece['fabric'] : null,
            'non_flip' => isset($cutPiece['nonFlip']) ? $cutPiece['nonFlip'] : null,
            'x' => isset($cutPiece['x']) ? $cutPiece['x'] : null,
            'y' => isset($cutPiece['y']) ? $cutPiece['y'] : null,
            'xy' => isset($cutPiece['xy']) ? $cutPiece['xy'] : null,
        ];

        $this->update($data, $id);
    }

    /**
     * Get a cut piece by name and techpack id
     *
     * @param  string $name
     * @param string $techpackId
     * @return mixed
     */
    public function getByNameAndTechpackId($name, $techpackId) {
        return $this->model->where('name', $name)
                           ->where('techpack_id', $techpackId)
                           ->first();
    }    

    /**
     * Get a cut piece by techpack id
     *
     * @param string $techpackId
     * @return mixed
     */
    public function getByTechpackId($techpackId) {
        return $this->model->where('techpack_id', $techpackId)
                           ->get();
    }    

    /**
     * Delete a cut piece by difference
     *
     * @param string $techpackId
     * @return mixed
     */
    public function removeCutPieces($cutTicketIds, $techpackId) {
        return $this->model->where('techpack_id', $techpackId)
                           ->whereNotIn('id', $cutTicketIds)
                           ->delete();
    }    

}
