<?php

namespace Platform\Techpacks\Commands;

class ListTechpacksCommand
{
    public $onlyTrashed;
    public $withTrashed;
    public $withScope;
    public $withCollection;
    public $app;
    public $item;

    /**
     * @param $onlyTrashed
     * @param $withTrashed
     */
    public function __construct(
        $onlyTrashed = false,
        $withTrashed = false,
        $withScope = 'owned',
        $withCollection = 'all',
        $app='platform',
        $item = 100
    ) {
        $this->onlyTrashed = $onlyTrashed;
        $this->withTrashed = $withTrashed;
        $this->withScope = $withScope;
        $this->withCollection = $withCollection;
        $this->app = $app;
        $this->item = $item;
    }
}
