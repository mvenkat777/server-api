<?php

namespace Platform\Line\Handlers\Commands;

use Carbon\Carbon;
use Platform\App\Activity\Recorders\LineActivityRecorder;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Platform\Line\Commands\AttachTechpackCommand;
use Platform\Line\Repositories\Contracts\StyleRepository;

class UpdateStyleCommandHandler implements CommandHandler
{
	private $style;
	private $commandBus;
    private $defaultRuleBus;

	public function __construct(
        StyleRepository $style,
        DefaultCommandBus $commandBus,
        DefaultRuleBus $defaultRuleBus
    ) {
        $this->style = $style;
        $this->defaultRuleBus = $defaultRuleBus;
        $this->commandBus = $commandBus;
	}

	public function handle($command)
	{
        $lineId = $command->lineId;
        $styleId = $command->styleId;
        $data = $command->data;

        $style = $this->style->getByLineIdAndStyleId($lineId, $styleId);
        if (!$style) {
             throw new SeException("Style with given id not found.", 404);
        }

        if (isset($data['techpackId']) &&
            $data['techpackId'] != '' &&
            $data['techpackId'] != $style->techpack_id
        ) {
            $data = $this->commandBus->execute(new AttachTechpackCommand($data));
        }

        $style = $this->style->updateStyle($lineId, $styleId, $data);
        $line = $style->line;
        $line->updated_at = $style->updated_at;
        $line->save();

        if (isset($data['changeLog'])) {
            $this->recordChangeLog($data['changeLog']);
        }
        return $style;
    }

    public function recordChangeLog($changeLogs)
    {
        $lineActivityRecorder = new LineActivityRecorder();
        $lineActivityRecorder->record($changeLogs);
    }
    /**
     * @param  string $id
     * @return string
     */
    public function getEmailByID($id)
    {
        $user = \App\User::find($id);
        return $user;
    }
}
