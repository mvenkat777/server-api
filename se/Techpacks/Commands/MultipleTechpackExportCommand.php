<?php

namespace Platform\Techpacks\Commands;

class MultipleTechpackExportCommand
{
    /**
     * Othere relevant data for export if any
     * @var array
     */
    public $data;

    /**
     * The list of techpack ids to be exported
     * @var array
     */
    public $techpackIds;

    /**
     * @param array $data
     * @param array $techpackIds
     */
	public function __construct($data, $techpackIds){
        $this->data = $data;
        $this->techpackIds = $techpackIds;
	}

}