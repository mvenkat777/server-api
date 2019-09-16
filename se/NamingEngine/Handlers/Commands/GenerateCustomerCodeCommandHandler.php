<?php

namespace Platform\NamingEngine\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use App\Customer;

class GenerateCustomerCodeCommandHandler implements CommandHandler 
{
    public function handle($command)
    {
        $customerCode = $this->createBasicCodeFromName($command->customerName);
        $customerCode = $this->makeCodeLengthFour($customerCode);
        $customerCode = $this->makeTheCodeUnique($customerCode);

        return $customerCode;
    }

    /**
     * Make basic code from customer name
     *
     * @param string $customerName
     * @return string
     */
    private function createBasicCodeFromName($customerName)
    {
        $customerName = explode(' ', $customerName);

        if (sizeof($customerName) > 1) {
            $customerCode = $this->combine($customerName);
        } else {
            $customerCode = strtoupper(substr($customerName[0], 0, 4));
        }
        return $customerCode;
    }

    /**
     * Check with existing codes in DB to find conflicts and reiterate to make the code unique
     *
     * @param string $customerCode
     * @return string
     */
    private function makeTheCodeUnique($customerCode)
    {
        for ($i = 0; Customer::where('code', $customerCode)->first() != null; $i++) {
            $suffix = strval($i);
            $customerCode = substr_replace($customerCode, $suffix, -strlen($suffix), strlen($suffix));
        }
        return $customerCode;
    }

    /**
     * Make the code length four if it has lesser characters
     *
     * @param string $customerCode
     * @return string
     */
    private function makeCodeLengthFour($customerCode)
    {
        return str_pad($customerCode, 4, 0, STR_PAD_RIGHT);
    }

    /**
     * If customer name has two parts, combine them to make a single name
     *
     * @param string $customerName
     * @return string
     */
    private function combine($customerName)
    {
        return strtoupper(substr($customerName[0], 0, 2)) . strtoupper(substr($customerName[1], 0, 2));
    }

}
