<?php

namespace Platform\Line\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Line\Repositories\Contracts\StyleRepository;
use Platform\App\Exceptions\SeException;

class AttachSampleCommandHandler implements CommandHandler 
{
    private $style;
    public function __construct(StyleRepository $style)
    {
        $this->style = $style;
    }

    public function handle($command)
    {
        $lineId = $command->lineId;
        $styleId = $command->styleId;
        $sampleSubmissionId = $command->data['sampleId'];
        $style = $this->style->getByLineIdAndStyleId($lineId, $styleId);

        if ($style) {
            $style->sampleSubmissions()->sync([$sampleSubmissionId]);
            return $style;
        }

        throw new SeException("Style with that Id not found", 404);
    }

}
