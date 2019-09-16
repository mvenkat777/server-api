<?php

namespace Platform\Techpacks\Commands;

class FormatTechpackForExportCommand
{
    public $techpack;

    public function __construct($techpack)
    {
        $this->techpack = $techpack;
    }
}
