<?php

namespace Platform\Techpacks\Commands;

class RestoreTechpackCommand
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
