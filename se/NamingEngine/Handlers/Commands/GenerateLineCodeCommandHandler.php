<?php

namespace Platform\NamingEngine\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use App\Line;

class GenerateLineCodeCommandHandler implements CommandHandler 
{
    public function handle($command)
    {
        $lineCode = $command->customerCode;
        $lineCode = $this->addPrefix($lineCode);
        $lineCode = $this->addSuffixAndMakeItUnique($lineCode);

        return $lineCode;
    }

    /**
     * Adds the LI prefix 
     *
     * @param string $lineCode
     * @return string
     */
    private function addPrefix($lineCode)
    {
        $prefix = 'LI-';
        $lineCode = $prefix . $lineCode;
        return $lineCode;
    }

    /**
     * Add 3 letter alphanumeric suffix to line code  and make it unique
     *
     * @param string $lineCode
     * @return string
     */
    private function addSuffixAndMakeItUnique($lineCode)
    {
        do {
            $suffix = sha1($lineCode . time());
            $suffix = substr($suffix, -3);
            $newLineCode = strtoupper($lineCode . '-' . $suffix);
        } while (Line::where('code', $newLineCode)->first() != null);
        return $newLineCode;
    }
}
