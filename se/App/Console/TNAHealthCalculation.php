<?php

namespace Platform\App\Console;

use Platform\App\Console\SeConsole;
use Platform\TNA\Handlers\Console\TNAHealthCalculator;
use Platform\TNA\Repositories\Contracts\TNARepository;

class TNAHealthCalculation extends SeConsole
{
    /**
     * @var Platform\TNA\Repositories\Contracts\TNARepository
     */
    protected $tnaRepository;

    /**
     * @var Platform\TNA\HAndlers\Console\TNAHealthCalculator
     */
    protected $tnaHealth;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TNAHealthCalculator $tnaHealth,
                                TNARepository $tnaRepository)
    {
        $this->signature = 'tna:check_health';
        $this->description = 'Check health of TNA';

        $this->tnaHealth = $tnaHealth;
        $this->tnaRepository = $tnaRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tnaList = $this->tnaRepository->getByType('active');
        $tnaList->each(function($tna, $key) {
            $this->tnaHealth->calculate($tna);
        });
        $tnaList = $this->tnaRepository->getByType('draft');
        $tnaList->each(function($tna, $key) {
            $this->tnaHealth->calculate($tna);
        });
    }
}
