<?php

namespace Platform\Techpacks\Repositories\Contracts;

interface ColorwaysRepository
{
    /**
     * Returns model
     * @return App\Colorway
     */
    public function model();

        /**
     * Add new colorway
     * @param array $data
     */
    public function addNewColorway($data);

    /**
     * Get a coloway based on BOM Line Item ID
     * @param  string $id
     * @return mixed
     */
    public function getByBOMLineItemId($id);

    /**
     * Get all the colorways that belong to a techpack
     * @param  string $id
     * @return mixed
     */
    public function getByTechpackId($id);
}
