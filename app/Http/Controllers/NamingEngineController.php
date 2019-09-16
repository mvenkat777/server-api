<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\NamingEngine\Commands\GenerateCustomerCodeCommand;
use Platform\NamingEngine\Commands\GenerateVendorCodeCommand;
use Platform\NamingEngine\Commands\GenerateLineCodeCommand;
use Platform\NamingEngine\Commands\GenerateStyleCodeCommand;

class NamingEngineController extends ApiController
{
    /**
     * @var Platform\App\Commanding\DefaultCommandBus
     * @access private
     */
    private $commandBus;

    /**
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(DefaultCommandBus $commandBus) {
        $this->commandBus = $commandBus;
    }    

    /**
     * Generate customer code
     *
     * @param Request $request
     * @return mixed
     */
    public function customer(Request $request) {
        if ($request->get('name') != null) {
            $customerCode = $this->commandBus->execute(new GenerateCustomerCodeCommand($request->get('name')));
            return $this->setStatusCode(200)->respondWithArray([
                'data' => [
                    'code' => $customerCode
                ]
            ]);
        }

        throw new SeException('Customer name is required for customer code generation', 422);
    }    
    
    /**
     * Generate vendor code
     *
     * @param Request $request
     * @return mixed
     */
    public function vendor(Request $request) {
        if ($request->get('name') != null) {
            $vendorCode = $this->commandBus->execute(new GenerateVendorCodeCommand($request->get('name')));
            return $this->setStatusCode(200)->respondWithArray([
                'data' => [
                    'code' => $vendorCode
                ]
            ]);
        }

        throw new SeException('Vendor name is required for vendor code generation', 422);
    }    

    /**
     * Generate line code
     *
     * @param Request $request
     * @return mixed
     */
    public function line(Request $request) {
        if ($request->get('customerCode') != null) {
            $lineCode = $this->commandBus->execute(new GenerateLineCodeCommand($request->get('customerCode')));
            return $this->setStatusCode(200)->respondWithArray([
                'data' => [
                    'code' => $lineCode
                ]
            ]);
        }

        throw new SeException('Customer code is required for line code generation', 422);
    }    

    /**
     * Generate style code
     *
     * @param Request $request
     * @return mixed
     */
    public function style(Request $request) {
        if ($request->get('customerCode') == null) {
            throw new SeException('Customer code is required for style code generation', 422);
        }
        if ($request->get('productCategory') == null) {
            throw new SeException('Product category is required for style code generation', 422);
        }
        if ($request->get('product') == null) {
            throw new SeException('Product is required for style code generation', 422);
        }

        $styleCode = $this->commandBus->execute(new GenerateStyleCodeCommand(
            $request->get('customerCode'),
            $request->get('productCategory'),
            $request->get('product')
        ));
        return $this->setStatusCode(200)->respondWithArray([
            'data' => [
                'code' => $styleCode
            ]
        ]);

    }    
}
