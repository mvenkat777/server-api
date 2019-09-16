<?php

namespace Platform\App\Console;

use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Console\SeConsole;
use Platform\TNA\Commands\TNAPublishActionCommand;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Commands\CreateItemsFromPresetCommand;

class TNACreatePreset extends SeConsole
{
    /**
     * @var Platform\TNA\Repositories\Contracts\TNARepository
     */
    protected $tnaRepository;

    /**
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DefaultCommandBus $commandBus,
                                TNARepository $tnaRepository)
    {
        $this->signature = 'tnaAction:preset';
        $this->description = 'Take action for creating TNA items from preset.';

        $this->commandBus = $commandBus;
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
        $tnaList = \DB::select('SELECT * from tna_create_preset');
        foreach ($tnaList as $key => $tna) {
            $data = json_decode($tna->data, true);
            $this->commandBus->execute(new CreateItemsFromPresetCommand($data));
            \DB::statement("DELETE FROM tna_create_preset where tna_id = '$tna->tna_id'");
        }
    }
}
