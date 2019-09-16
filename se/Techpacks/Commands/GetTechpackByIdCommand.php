<?php

namespace Platform\Techpacks\Commands;

class GetTechpackByIdCommand
{
    public $id;
    public $withTrashed;
    public $getFields;

    /**
     * @param $id
     * @param bool $withTrashed
     */
    public function __construct($id, $withTrashed = false, $getFields = false)
    {
        $this->id = $id;
        $this->withTrashed = $withTrashed;

        $this->getFields = $this->mapGettableFields($getFields);
    }

    /**
     * Map requested fields to proper names
     * @param  [type] $getFields [description]
     * @return [type]            [description]
     */
    public function mapGettableFields($getFields)
    {
        $gettableFields = [
            'meta' => 'meta',
            'billOfMaterials' => 'bill_of_materials',
            'specSheets' => 'spec_sheets',
            'colorSets' => 'color_sets',
            'sketches' => 'sketches',
        ];

        $fields = [];

        if (!$getFields) {
            return $fields;
        }

        foreach ($getFields as $field) {
            if (isset($gettableFields[$field])) {
                array_push($fields, $gettableFields[$field]);
            }
        }

        return $fields;
    }
}
