<?php

namespace Platform\Techpacks\Commands;

class DeleteTechpackCommand
{
    public $id;

    /**
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
}
