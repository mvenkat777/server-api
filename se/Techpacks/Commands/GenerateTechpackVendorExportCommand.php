<?php

namespace Platform\Techpacks\Commands;

class GenerateTechpackVendorExportCommand
{
    /**
     * @var string
     */
    public $techpackId;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $isEmail;

    /**
     * @var string
     */
    public $selectedFields;

    /**
     * @var string
     */
    public $multipleExport;

    /**
     * @param string $techpackId
     * @param string $email
     */
    public function __construct($request, $techpackId, $selectedFields = [], $multipleExport = false)
    {
        $this->techpackId = $techpackId;
        $this->email = isset(\Auth::user()->email)? \Auth::user()->email : $request['email'];
        $this->isEmail = isset($request['isEmail'])? $request['isEmail'] : false;
        $this->selectedFields = $selectedFields;
        $this->multipleExport = $multipleExport;
    }
}
