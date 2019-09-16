<?php

namespace Platform\NamingEngine\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Form\Models\FormPurchaseOrder;
use Carbon;
use Rhumsaa\Uuid\Uuid;

class GenerateOrderCodeCommandHandler implements CommandHandler 
{
    public function handle($command)
    {   
        // dd($command);
            
        $code = $this->createBasicCodeFromName($command->seIssuingOffice, $command->userCode, $command->formType);
        
        //dd($code);
        return $code;
    }

    /**
     * Make basic code from seIssuingOffice
     *
     * @param string $seIssuingOffice
     * @param string $userCode where it is a customer or vendor
     * @return string
     */
    private function createBasicCodeFromName($seIssuingOffice, $userCode, $formType)
    {
        // $seIssuingOffice = preg_replace('/-/', '', $seIssuingOffice);
        $seIssuingOffice = explode('-', $seIssuingOffice);
        if(isset($seIssuingOffice[1])){
            $seIssuingOffice = $seIssuingOffice[1];
        } else {
            $seIssuingOffice = $seIssuingOffice[0];
        }
        $userCode = preg_replace('/V-|\-/', '', $userCode);

        $slug = strtoupper(substr(Uuid::uuid4()->toString(), 0, 5));

        $code = $this->combine($seIssuingOffice, $userCode, $slug, $formType);

        return $code;
    }

    

    /**
     * 
     *
     * @param string $seIssuingOffice
     * @param string $vendorCode
     * @param string $slug
     * @return string 
     * 
     */
    private function combine($seIssuingOffice, $userCode, $slug, $formType)
    {
        $date = Carbon\Carbon::today()->format('mdy');
        if($seIssuingOffice == 'NA')
        {
            $code = $formType.'-'.$userCode.'-'.$slug.'-'.$date;
        }
        else
        {
           $code = $formType.'-'.$userCode.'-'.$seIssuingOffice.'-'.$slug.'-'.$date; 
        }
        
        return strtoupper($code);
    }

}
