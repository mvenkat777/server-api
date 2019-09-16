<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Helpers\Helpers;
use Platform\NamingEngine\Commands\GenerateVendorCodeCommand;
use Platform\Vendor\Commands\AddBankCommand;
use Platform\Vendor\Commands\AddOrUpdateVendorAddressCommand;
use Platform\Vendor\Commands\AddVendorPartnerCommand;
use Platform\Vendor\Commands\AllVendorListCommand;
use Platform\Vendor\Commands\CreateVendorCommand;
use Platform\Vendor\Commands\DeleteBankCommand;
use Platform\Vendor\Commands\DeleteVendorAddressCommand;
use Platform\Vendor\Commands\DeleteVendorCommand;
use Platform\Vendor\Commands\DeleteVendorContactCommand;
use Platform\Vendor\Commands\DeleteVendorPartnerCommand;
use Platform\Vendor\Commands\ShowVendorByIdCommand;
use Platform\Vendor\Commands\UpdateVendorCommand;
use Platform\Vendor\Helpers\VendorHelpers;
use Platform\Vendor\Repositories\Contracts\VendorCapabilityRepository;
use Platform\Vendor\Repositories\Contracts\VendorPaymentTermRepository;
use Platform\Vendor\Repositories\Contracts\VendorRepository;
use Platform\Vendor\Repositories\Contracts\VendorServiceRepository;
use Platform\Vendor\Repositories\Contracts\VendorTypeRepository;
use Platform\Vendor\Transformers\MetaVendorTransformer;
use Platform\Vendor\Transformers\VendorTransformer;
use Platform\Vendor\Validators\BankValidator;
use Platform\Vendor\Validators\CreateVendor;

class VendorController extends ApiController
{

    /**
     * @param VendorRepository $vendor
     */
    function __construct(
        VendorRepository $vendor,
        VendorTypeRepository $vendorType,
        VendorServiceRepository $vendorService,
        VendorPaymentTermRepository $vendorPaymentTerm,
        VendorCapabilityRepository $vendorCapability,
        DefaultCommandBus $commandBus,
        CreateVendor $validator,
        BankValidator $bankValidator
    ) {
        $this->vendor = $vendor;
        $this->vendorType = $vendorType;
        $this->vendorCapability = $vendorCapability;
        $this->vendorService = $vendorService;
        $this->vendorPaymentTerm = $vendorPaymentTerm;
        $this->commandBus = $commandBus;
        $this->validator = $validator;
        $this->bankValidator = $bankValidator;

        parent::__construct(new Manager());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $command = new AllVendorListCommand($data);
        $vendors = $this->commandBus->execute($command);

        if ($vendors) {
            return $this->respondWithPaginatedCollection($vendors, new MetaVendorTransformer, 'vendors');
        }

        return $this->setStatusCode(500)
                    ->respondWithError('No vendors');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        //$data['code'] = $this->commandBus->execute(new GenerateVendorCodeCommand($data['name']));

        $this->validator->validate($data);
        if(isset($data['banks']) && !is_null($data['banks'])){
            foreach ($data['banks'] as $key => $bank) {
                $this->bankValidator->validate($bank);
            }
        }
        $command = new CreateVendorCommand($data);
        $vendor=$this->commandBus->execute($command);

        if ($vendor) {
            return $this->respondWithNewItem($vendor, new VendorTransformer, 'vendor');
        }

        return $this->setStatusCode(400)
                    ->respondWithError('Vendor creation failed.');
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return array
     */
    public function show(Request $request, $id)
    {
        $command = new ShowVendorByIdCommand($id);
        $vendor = $this->commandBus->execute($command);

        if ($vendor) {
            return $this->respondWithItem($vendor, new VendorTransformer, 'vendor');
        }

        return $this->setStatusCode(500)
                    ->respondWithError('Invalid Vendor Id');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $formData = $request->all();
        if(isset($formData['banks']) && !is_null($formData['banks'])){
            foreach ($formData['banks'] as $key => $bank) {
                $this->bankValidator->validate($bank);
            }
        }
        $command = new UpdateVendorCommand($formData, $id);
        $vendor = $this->commandBus->execute($command);

        if ($vendor) {
            return $this->respondOk('Updated successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Vendor Updation failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $command = new DeleteVendorCommand($id);
        $vendor = $this->commandBus->execute($command);
        if ($vendor) {
            return $this->respondOk('Deleted successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Customer deletion failed.');
        }
    }

    /**
     * @param  Request $request
     * @return vendors
     */
    public function filter(Request $request)
    {
        $vendor = $this->vendor->filterVendor($request->all());
        return $this->respondWithPaginatedCollection($vendor, new MetaVendorTransformer, 'vendors');
    }

    /**
     * @param  Request $request
     * @return Vendors
     */
    public function getSchema(Request $request)
    {
        $customer['types'] = $this->vendorType->getTypes();
        $customer['capabilities'] = $this->vendorCapability->getCapabilities();
        $customer['services'] = $this->vendorService->getServices();
        $customer['paymentTerms'] = $this->vendorPaymentTerm->getPaymentTerms();
        return $this->respondWithArray(['data' => $customer]);
    }

    /**
     * @param Request $request
     * @param string  $vendorId
     * @return \Illuminate\Http\Response
     */
    public function addPartners(Request $request, $vendorId)
    {
        $data = $request->all();
        $command = new AddVendorPartnerCommand($data, $vendorId);
        $vendor = $this->commandBus->execute($command);
        return $this->respondWithItem($vendor, new VendorTransformer, 'vendor');
    }

    /**
     * @param Request $request
     * @param string  $vendorId
     * @return \Illuminate\Http\Response
     */
    public function addBanks(Request $request, $vendorId)
    {
        $data = $request->all();
        if(!is_null($data)){
            foreach ($data['banks'] as $key => $bank) {
                $this->bankValidator->validate($bank);
            }
        }

        $command = new AddBankCommand($data, $vendorId);
        $vendor = $this->commandBus->execute($command);
        return $this->respondWithItem($vendor, new VendorTransformer, 'vendor');
    }

    /**
     * Vendor address add or update
     * @param Request $request 
     * @param string  $id      
     */
    public function AddOrUpdateAddress(Request $request, $id)
    {
        $data = $request->all();
        $command = new AddOrUpdateVendorAddressCommand($data, $id);
        $result = $this->commandBus->execute($command);

        if ($result) {
            return $this->respondOk('Address added or updated successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Customer deletion failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  string  $id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteContact($vendorId, $contactId)
    {
        $command = new DeleteVendorContactCommand($vendorId, $contactId);
        $customer = $this->commandBus->execute($command);
        if ($customer) {
            return $this->respondOk('Deleted successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Vendor deletion failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  string  $id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAddress($vendorId, $contactId)
    {
        $command = new DeleteVendorAddressCommand($vendorId, $contactId);
        $customer = $this->commandBus->execute($command);
        if ($customer) {
            return $this->respondOk('Deleted successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Vendor Address deletion failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  string  $id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePartner($vendorId, $contactId)
    {
        $command = new DeleteVendorPartnerCommand($vendorId, $contactId);
        $customer = $this->commandBus->execute($command);
        if ($customer) {
            return $this->respondOk('Deleted successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Partner deletion failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  string  $id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteBank($vendorId, $bankId)
    {
        $command = new DeleteBankCommand($vendorId, $bankId);
        $vendor = $this->commandBus->execute($command);
        if ($vendor) {
            return $this->respondOk('Deleted successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Bank deletion failed.');
        }
    }

    /**
     * Archive the specified resource from storage.
     * @param  string  $id
     * @param  int  $id
     * @return \Illuminate\Http\Request
     */
    public function archiveVendor(Request $request, $vendorId)
    {
        // dd($request->all());
        $requestVars = $request->all();
        if(isset($requestVars['available'])){
            $availableFlag = ($request->all()['available'] == 'true') ? true : false;
            if($availableFlag){
                $archivedDate = NULL;
                $message = 'Vendor Unarchived successfully';
            }else{
                $archivedDate = Carbon::now()->toDateTimeString();
                $message = 'Vendor Archived successfully';
            } 
            // dd($archivedDate);           
            $resp = $this->vendor->updateArchiveVendor($archivedDate , $vendorId);
            
            //dd($resp);
            if($resp)
            return $this->respondOk($message);
        }else{
            return $this->setStatusCode(404)->respondWithError('Invalid Resource : Parameter missing');
        }
        

    }
}
