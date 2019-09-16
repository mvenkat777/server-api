<?php

namespace Platform\Techpacks\Commands;

class ForceDeleteTechpackCommand
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
