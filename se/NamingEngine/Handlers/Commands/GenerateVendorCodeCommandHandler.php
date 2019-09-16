<?php

namespace Platform\NamingEngine\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use App\Vendor;

class GenerateVendorCodeCommandHandler implements CommandHandler 
{

    public function handle($command)
    {
        $vendorCode = $this->createBasicCodeFromName($command->vendorName);
        $vendorCode = $this->makeCodeLengthFour($vendorCode);
        $vendorCode = $this->addPrefix($vendorCode);
        $vendorCode = $this->makeTheCodeUnique($vendorCode);

        return $vendorCode;
    }

    /**
     * Make basic code from vendor name
     *
     * @param string $vendorName
     * @return string
     */
    private function createBasicCodeFromName($vendorName)
    {
        $vendorName = explode(' ', $vendorName);

        if (sizeof($vendorName) > 1) {
            $vendorCode = $this->combine($vendorName);
        } else {
            $vendorCode = strtoupper(substr($vendorName[0], 0, 4));
        }
        return $vendorCode;
    }

    /**
     * Adds the v prefix to the vendor code
     *
     * @param string $vendorCode
     * @return string
     */
    private function addPrefix($vendorCode)
    {
        $prefix = 'V-';
        $vendorCode = $prefix . $vendorCode;
        return $vendorCode;
    }

    /**
     * Check with existing codes in DB to find conflicts and reiterate to make the code unique
     *
     * @param string $vendorCode
     * @return string
     */
    private function makeTheCodeUnique($vendorCode)
    {
        for ($i = 0; Vendor::where('code', $vendorCode)->first() != null; $i++) {
            $suffix = strval($i);
            $vendorCode = substr_replace($vendorCode, $suffix, -strlen($suffix), strlen($suffix));
        }
        return $vendorCode;
    }

    /**
     * Make the code length four if it has lesser characters
     *
     * @param string $vendorCode
     * @return string
     */
    private function makeCodeLengthFour($vendorCode)
    {
        return str_pad($vendorCode, 4, 0, STR_PAD_RIGHT);
    }

    /**
     * If vendor name has two parts, combine them to make a single name
     *
     * @param string $vendorName
     * @return string
     */
    private function combine($vendorName)
    {
        return strtoupper(substr($vendorName[0], 0, 2)) . strtoupper(substr($vendorName[1], 0, 2));
    }

}
