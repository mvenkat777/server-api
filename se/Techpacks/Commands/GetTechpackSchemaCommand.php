<?php

namespace Platform\Techpacks\Commands;

class GetTechpackSchemaCommand
{
    public $version;

    public function __construct($version)
    {
        $this->version = $version;
    }
}
