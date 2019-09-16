<?php

namespace Platform\Customer\Transformers;

use App\Customer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Address\Transformers\AddressTransformer;
use Platform\Contacts\Transformers\ContactTransformer;
use Platform\Customer\Transformers\BrandTransformer;
use Platform\Customer\Transformers\CustomerPaymentTermTransformer;
use Platform\Customer\Transformers\CustomerRequirementTransformer;
use Platform\Customer\Transformers\CustomerServiceTransformer;
use Platform\Customer\Transformers\CustomerTypeTransformer;
use Platform\Customer\Transformers\PartnerTransformer;

class CustomerTransformer extends TransformerAbstract
{
	public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(Customer $customer)
    {
    	$brands = $this->collection($customer->brands, new BrandTransformer);
        $brands = $this->manager->createData($brands)->toArray();

        $addresses = $this->collection($customer->addresses, new AddressTransformer);
        $addresses = $this->manager->createData($addresses)->toArray();

        // $contacts = $this->collection($customer->contacts, new ContactTransformer);
        // $contacts = $this->manager->createData($contacts)->toArray();

        $partners =  $this->collection($customer->partners, new PartnerTransformer);
        $partners = $this->manager->createData($partners)->toArray();

        $types = $this->collection($customer->types, new CustomerTypeTransformer);
        $types = $this->manager->createData($types)->toArray();
        $type = [];
        foreach ($types['data'] as $value) {
            array_push($type, $value['id']);
        }

        $services = $this->collection($customer->services, new CustomerServiceTransformer);
        $services = $this->manager->createData($services)->toArray();
        $service = [];
        foreach ($services['data'] as $value) {
            array_push($service, $value['id']);
        }

        $requirements = $this->collection($customer->requirements, new CustomerRequirementTransformer);
        $requirements = $this->manager->createData($requirements)->toArray();
        $requirement = [];
        foreach ($requirements['data'] as $value) {
            array_push($requirement, $value['id']);
        }

        $paymentTerms = $this->collection($customer->paymentTerms, new CustomerPaymentTermTransformer);
        $paymentTerms = $this->manager->createData($paymentTerms)->toArray();
        $paymentTerm = [];
        foreach ($paymentTerms['data'] as $value) {
            array_push($paymentTerm, $value['id']);
        }

        return [
            'id' => (string)$customer->id,
            'code' => (string)$customer->code,
            'name' => (string)$customer->name,
            'businessEntity' => (string)$customer->business_entity,
            'importExportLicense' => (string)$customer->import_export_license,
            'taxId' => (string)$customer->tax_id,
            'vatSalesTaxReg' => (string)$customer->vat_sales_tax_reg,
            'companyReg' => (string)$customer->company_reg,
            'archivedAt' => is_null($customer->archived_at)? NULL :$customer->archived_at->toDateTimeString(),
            'createdAt' => $customer->created_at->toDateTimeString(),
            'updatedAt' => $customer->updated_at->toDateTimeString(),
            'brands' => $brands['data'],
            'types' => $type,
            'typesObject' => $types,
            'services' => $service,
            'servicesObject' => $services['data'],
            'requirements' => $requirement,
            'requirementsObject' => $requirements['data'],
            'paymentTerms' => $paymentTerm,
            'paymentTermsObject' => $paymentTerms['data'],
            'partners' => $partners['data'],
            'addresses' => $addresses['data'],
            //'contacts' => $contacts['data']
        ];
    }
}
