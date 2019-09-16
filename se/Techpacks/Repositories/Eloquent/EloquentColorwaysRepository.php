<?php

namespace Platform\Techpacks\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Techpacks\Repositories\Contracts\ColorwaysRepository;
use App\Colorways;

class EloquentColorwaysRepository extends Repository implements ColorwaysRepository
{
    /**
     * Returns model
     * @return App\Colorway
     */
    public function model()
    {
        return 'App\Colorway';
    }

    /**
     * Add new colorway
     * @param array $data
     */
    public function addNewColorway($data)
    {
        $exists = $this->getByBOMLineItemIdAndTechpackId($data['bomLineItemId'], $data['techpackId']);
        $data = [
            'colorway' => $data['colorway'],
            'bom_line_item_id' => $data['bomLineItemId'],
            'techpack_id' => $data['techpackId'],
            'approval' => isset($data['approval']) ? $data['approval'] : null,
        ];
        if ($exists) {
            $exists->colorway = $data['colorway'];
            $exists->approval = $data['approval'];
            $exists->update();
            return $exists;
        }

        $data['id'] = $this->generateUUID();
        return $this->create($data);
    }

    /**
     * Delete a colorway
     * @param array $data
     */
    public function deleteColorway($colorwayId)
    {
        $colorway = $this->find($colorwayId);

        if ($colorway) {
            return $colorway->delete();
        }

        throw new SeException('Colorway with this id not found.', 404, 6020104);
    }

    /**
     * Get a coloway based on BOM Line Item ID
     * @param  string $id
     * @return mixed
     */
    public function getByBOMLineItemId($id)
    {
        return $this->model->where('bom_line_item_id', $id)
                           ->first();
    }

    /**
     * Get a coloway based on BOM Line Item ID and TechpackID
     * @param  string $bomLineItemId
     * @param  string $techpackId
     * @return mixed
     */
    public function getByBOMLineItemIdAndTechpackId($bomLineItemId, $techpackId)
    {
		return $this->model->where('bom_line_item_id', $bomLineItemId)
						   ->where('techpack_id', $techpackId)
                           ->first();
    }


    /**
     * Get all the colorways that belong to a techpack
     * @param  string $id
     * @return mixed
     */
    public function getByTechpackId($id)
    {
        return $this->model->where('techpack_id', $id)
                           ->get();
    }
}
