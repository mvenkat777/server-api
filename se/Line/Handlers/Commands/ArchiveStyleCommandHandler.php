<?php

namespace Platform\Line\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Line\Repositories\Contracts\StyleRepository;
use Platform\Line\Repositories\Contracts\LineRepository;

class ArchiveStyleCommandHandler implements CommandHandler 
{
    private $style;
    private $line;

    public function __construct(StyleRepository $style, LineRepository $line)
    {
        $this->style = $style;
        $this->line = $line;
    }

    public function handle($command)
    {
        $styleId = $command->styleId;
        $lineId = $command->lineId;

        $style = $this->style->getByLineIdAndStyleId($lineId, $styleId);
        if ($style) {
            return $style->delete();
        }

        throw new SeException("Style not found.", 404);
    }
}
