<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Helpers\Helpers;
use Platform\Customer\Commands\AddCustomerAddressCommand;
use Platform\Customer\Commands\AddCustomerBrandCommand;
use Platform\Customer\Commands\AddCustomerContactCommand;
use Platform\Customer\Commands\AddCustomerPartnerCommand;
use Platform\Customer\Commands\AllCustomerListCommand;
use Platform\Customer\Commands\CreateCustomerCommand;
use Platform\Customer\Commands\DeleteCustomerAddressCommand;
use Platform\Customer\Commands\DeleteCustomerBrandCommand;
use Platform\Customer\Commands\DeleteCustomerCommand;
use Platform\Customer\Commands\DeleteCustomerContactCommand;
use Platform\Customer\Commands\DeleteCustomerPartnerCommand;
use Platform\Customer\Commands\ShowCustomerByIdCommand;
use Platform\Customer\Commands\UpdateCustomerCommand;
use Platform\Customer\Helpers\CustomerHelpers;
use Platform\Customer\Repositories\Contracts\CustomerPaymentTermRepository;
use Platform\Customer\Repositories\Contracts\CustomerRepository;
use Platform\Customer\Repositories\Contracts\CustomerRequirementRepository;
use Platform\Customer\Repositories\Contracts\CustomerServiceRepository;
use Platform\Customer\Repositories\Contracts\CustomerTypeRepository;
use Platform\Customer\Transformers\CustomerTransformer;
use Platform\Customer\Transformers\MetaCustomerTransformer;
use Platform\Customer\Validators\CreateCustomer;
use Platform\NamingEngine\Commands\GenerateCustomerCodeCommand;
use Carbon\Carbon;
use Platform\Customer\Commands\ActivateCollabCommand;
use Platform\Customer\Transformers\CollabTransformer;
use Platform\Customer\Commands\GetCollabCommand;
use Platform\Customer\Commands\GetCollabUsersCommand;
use Platform\Customer\Transformers\CollabUserTransformer;
use Platform\Customer\Commands\AddUsersToCustomerCommand;
use Platform\Users\Transformers\MetaUserTransformer;
use Platform\Customer\Commands\UpdateCollabCommand;

class CustomerController extends ApiController
{
    /**
     * For Calling Commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * For Calling CustomerRepository
     * @var $customer
     */
    protected $customer;

    /**
     * For Calling CustomerRepository
     * @var $customer
     */
    protected $customerService;

    /**
     * For Calling CustomerRepository
     * @var $customer
     */
    protected $customerRequirement;

    /**
     * For Calling CustomerRepository
     * @var $customer
     */
    protected $customerType;

    /**
     * For Calling CustomerRepository
     * @var $customer
     */
    protected $customerPaymentTerm;

    /**
     * For CustomerValidator
     * @var $validator
     */
    protected $validator;

    /**
     * @param CustomerRepository $customer
     */
    function __construct(
        DefaultCommandBus $commandBus,
        CustomerRepository $customer,
        CustomerTypeRepository $customerType,
        CustomerPaymentTermRepository $customerPaymentTerm,
        CustomerServiceRepository $customerService,
        CustomerRequirementRepository $customerRequirement,
        CreateCustomer $validator
    ) {
        $this->commandBus = $commandBus;
        $this->customer = $customer;
        $this->customerType = $customerType;
        $this->customerRequirement = $customerRequirement;
        $this->customerPaymentTerm = $customerPaymentTerm;
        $this->customerService = $customerService;
        $this->validator = $validator;

        parent::__construct(new Manager());
    }

    /**
     * @return mixed
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $command = new AllCustomerListCommand($data);
        $customers = $this->commandBus->execute($command);

        if ($customers) {
            return $this->respondWithPaginatedCollection($customers, new MetaCustomerTransformer, 'customers');
        }

        return $this->setStatusCode(404)
                    ->respondWithError('No Customers');

    }

    /**
     * Create a new user
     *
     * @param  Request $request
     * @return array
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['code'] = $this->commandBus->execute(new GenerateCustomerCodeCommand($data['name']));
        $this->validator->validate($data);

        $command = new CreateCustomerCommand($data);
        $customer=$this->commandBus->execute($command);

        if ($customer) {
            return $this->respondWithNewItem($customer, new CustomerTransformer, 'customer');
        }

        return $this->setStatusCode(500)
                    ->respondWithError('Customer creation failed.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return array
     */
    public function show(Request $request, $id)
    {
        $command = new ShowCustomerByIdCommand($id);
        $customer = $this->commandBus->execute($command);

        if ($customer) {
            return $this->respondWithItem($customer, new CustomerTransformer, 'customer');
        }

        return $this->setStatusCode(500)
                    ->respondWithError('Invalid Customer Id');
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
        $command = new UpdateCustomerCommand($formData, $id);
        $customer = $this->commandBus->execute($command);

        if ($customer) {
            return $this->respondOk('Updated successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Customer Updation failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $command = new DeleteCustomerCommand($id);
        $customer = $this->commandBus->execute($command);
        if ($customer) {
            return $this->respondOk('Deleted successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Customer deletion failed.');
        }
    }

    /**
     * @param  Request $request
     * @return customers
     */
    public function filter(Request $request)
    {
        $customer = $this->customer->filterCustomer($request->all());
        return $this->respondWithPaginatedCollection($customer, new MetaCustomerTransformer, 'customers');
    }

    /**
     * @param  Request $request
     * @return customers
     */
    public function getSchema(Request $request)
    {
        $customer['types'] = $this->customerType->getTypes();
        $customer['requirements'] = $this->customerRequirement->getRequirements();
        $customer['services'] = $this->customerService->getServices();
        $customer['paymentTerms'] = $this->customerPaymentTerm->getPaymentTerms();
        return $this->respondWithArray(['data' => $customer]);
    }

    /**
     * @param Request $request
     * @param string  $customerId
     * @return \Illuminate\Http\Response
     */
    public function addPartners(Request $request, $customerId)
    {
        $data = $request->all();
        $command = new AddCustomerPartnerCommand($data, $customerId);
        $customer = $this->commandBus->execute($command);
        return $this->respondWithItem($customer, new CustomerTransformer, 'customer');
    }

    /**
     * @param Request $request
     * @param string  $customerId
     * @return \Illuminate\Http\Response
     */
    public function addBrands(Request $request, $customerId)
    {
        $data = $request->all();
        $command = new AddCustomerBrandCommand($data, $customerId);
        $customer = $this->commandBus->execute($command);
        return $this->respondWithItem($customer, new CustomerTransformer, 'customer');
    }

    /**
     * Remove the specified resource from storage.
     * @param  string  $id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteContact($customerId, $contactId)
    {
        $command = new DeleteCustomerContactCommand($customerId, $contactId);
        $customer = $this->commandBus->execute($command);
        if ($customer) {
            return $this->respondOk('Deleted successfully');
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
    public function deleteAddress($customerId, $contactId)
    {
        $command = new DeleteCustomerAddressCommand($customerId, $contactId);
        $customer = $this->commandBus->execute($command);
        if ($customer) {
            return $this->respondOk('Deleted successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Customer Address deletion failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  string  $id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deletePartner($customerId, $contactId)
    {
        $command = new DeleteCustomerPartnerCommand($customerId, $contactId);
        $customer = $this->commandBus->execute($command);
        if ($customer) {
            return $this->respondOk('Deleted successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Prtner deletion failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  string  $id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteBrand($customerId, $brandId)
    {
        $command = new DeleteCustomerBrandCommand($customerId, $brandId);
        $customer = $this->commandBus->execute($command);
        if ($customer) {
            return $this->respondOk('Deleted successfully');
        } else {
            return $this->setStatusCode(404)
                    ->respondWithError('Brand deletion failed.');
        }
    }

    public function getCustomerLinesHavingTna($id)
    {
        $command = new ShowCustomerByIdCommand($id);
        $customer = $this->commandBus->execute($command);  
        $lines = [];
        $styles = [];
        if (!empty($customer) && !empty($customer->lines()->whereNull('archived_at')->get())) {
            foreach ($customer->lines()->whereNull('archived_at')->get() as $line) {
                $styles = [];
                if (!empty($line->styles()->whereNull('archived_at')->get())) {
                    foreach ($line->styles()->whereNull('archived_at')->get() as $style) {
                        if ($style->tna != NULL) {
                            $styles[] = [
                                'id' => $style->id,
                                'name' => $style->name,
                                'code' => $style->code,
                                'tnaId' => $style->tna_id,
                                'tnaTitle' => $style->tna->title,
                                'isTna' => true
                            ];
                        } else {
                            $styles[] = [
                                'id' => $style->id,
                                'name' => $style->name,
                                'code' => $style->code,
                                'tnaId' => $style->tna_id,
                                'tnaTitle' => NULL,
                                'isTna' => false
                            ];
                        }
                    }
                }
                $lines[] = [
                    'id' => $line->id,
                    'code' => $line->code,
                    'name' => $line->name,
                    'styles' => $styles
                ];    
            }
        } 
        return $this->respondWithArray(['data' => $lines]);
    }

    /**
     * Archive the specified resource from storage.
     * @param  string  $id
     * @param  int  $id
     * @return \Illuminate\Http\Request
     */
    public function archiveCustomer(Request $request, $customerId)
    {
        // dd($request->all());
        $requestVars = $request->all();
        if(isset($requestVars['available'])){
            $availableFlag = ($request->all()['available'] == 'true') ? true : false;
            if($availableFlag){
                $archivedDate = NULL;
                $message = 'Customer Unarchived successfully';
            }else{
                $archivedDate = Carbon::now()->toDateTimeString();
                $message = 'Customer Archived successfully';
            } 
            // dd($archivedDate);           
            $resp = $this->customer->updateArchiveCustomer($archivedDate , $customerId);
            
            //dd($resp);
            if($resp)
            return $this->respondOk($message);
        }else{
            return $this->setStatusCode(404)->respondWithError('Invalid Resource : Parameter missing');
        }
        

    }

    /**
     * Activates collab for the customer
     *
     * @param Request $request
     * @param mixed $customerId
     */
    public function activateCollab(Request $request, $customerId)
    {
        $collab = $this->commandBus->execute(new ActivateCollabCommand($request->all(), $customerId));
        if ($collab) {
            return $this->respondWithNewItem($collab, new CollabTransformer, 'collab');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Not able to activate collab. Please try again.");
    }

    /**
     * Get collab details for the customer
     *
     * @param mixed $customerId
     */
    public function getCollab($customerId)
    {
        $collab = $this->commandBus->execute(new GetCollabCommand($customerId));
        if ($collab) {
            return $this->respondWithItem($collab, new CollabTransformer, 'collab');
        }

        return $this->setStatusCode(404)
                    ->respondWithError("Collab not activated.");
    }

    /**
     * Get collab details for the customer
     *
     * @param mixed $customerId
     */
    public function updateCollab(Request $request, $customerId)
    {
        $collab = $this->commandBus->execute(new UpdateCollabCommand($customerId, $request->all()));
        if ($collab) {
            return $this->respondWithItem($collab, new CollabTransformer, 'collab');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Unable to update the collab. Please try again.");
    }

    public function getUsers($customerId)
    {
        $users = $this->commandBus->execute(new GetCollabUsersCommand($customerId));
        if ($users) {
            return $this->respondWithCollection($users, new CollabUserTransformer, 'collab');
        }

        return $this->setStatusCode(500)
                    ->respondWithError("Not able to get collab users list. Please try again.");
    }

    /**
     * @param Request $request
     * @param mixed $collabId
     */
    public function addUsers(Request $request, $customerId)
    {
        $users = $this->commandBus->execute(new AddUsersToCustomerCommand($customerId, $request->all())) ;
        if ($users) {
            return $this->respondWithCollection($users, new MetaUserTransformer, 'user');
        }
        return $this->respondWithError("Failed to add users. Please try again.");
    }
}
