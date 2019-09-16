<?php

namespace Platform\Vendor\Transformers;

use App\Vendor;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Address\Transformers\AddressTransformer;
use Platform\Contacts\Transformers\ContactTransformer;
use Platform\Vendor\Transformers\BankTransformer;
use Platform\Vendor\Transformers\PartnerTransformer;
use Platform\Vendor\Transformers\VendorCapabilityTransformer;
use Platform\Vendor\Transformers\VendorPaymentTermTransformer;
use Platform\Vendor\Transformers\VendorServiceTransformer;
use Platform\Vendor\Transformers\VendorTypeTransformer;

class VendorTransformer extends TransformerAbstract
{
	public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(Vendor $vendor)
    {
    	$banks = $this->collection($vendor->banks, new BankTransformer);
        $banks = $this->manager->createData($banks)->toArray();

        $addresses = $this->collection($vendor->addresses, new AddressTransformer);
        $addresses = $this->manager->createData($addresses)->toArray();

        $contacts = $this->collection($vendor->contacts, new ContactTransformer);
        $contacts = $this->manager->createData($contacts)->toArray();

        $partners = $this->collection($vendor->partners, new PartnerTransformer);
        $partners = $this->manager->createData($partners)->toArray();

        $types = $this->collection($vendor->types, new VendorTypeTransformer);
        $types = $this->manager->createData($types)->toArray();
        $type = [];
        foreach ($types['data'] as $value) {
            array_push($type, $value['id']);
        }

        $services = $this->collection($vendor->services, new VendorServiceTransformer);
        $services = $this->manager->createData($services)->toArray();
        $service = [];
        foreach ($services['data'] as $value) {
            array_push($service, $value['id']);
        }

        $capabilities = $this->collection($vendor->capabilities, new VendorCapabilityTransformer);
        $capabilities = $this->manager->createData($capabilities)->toArray();
        
        $paymentTerms = $this->collection($vendor->paymentTerms, new VendorPaymentTermTransformer);
        $paymentTerms = $this->manager->createData($paymentTerms)->toArray();
        $paymentTerm = [];
        foreach ($paymentTerms['data'] as $value) {
            array_push($paymentTerm, $value['id']);
        }
        return [
            'id' => (string)$vendor->id,
            'code' => (string)$vendor->code,
            'name' => (string)$vendor->name,
            'businessEntity' => (string)$vendor->business_entity,
            'countryCode' => $vendor->country_code,
            'country' => is_null($vendor->country)? NULL : $vendor->country['country'],
            'importExportLicense' => (string)$vendor->import_export_license,
            'taxId' => (string)$vendor->tax_id,
            'vatSalesTaxReg' => (string)$vendor->vat_sales_tax_reg,
            'companyReg' => (string)$vendor->company_reg,
            'annualShippedTurnover' => $vendor->annual_shipped_turnover,
            'annualShippedQuantity' => $vendor->annual_shipped_quantity,
            'archivedAt' => is_null($vendor->archived_at)? NULL :$vendor->archived_at->toDateTimeString(),
            'createdAt' => $vendor->created_at->toDateTimeString(),
            'updatedAt' => $vendor->updated_at->toDateTimeString(),
            'banks' => $banks['data'],
            'types' => $type,
            'typesObject' => $types['data'],
            'services' => $service,
            'servicesObject' => $services['data'],
            'capabilities' =>$capabilities['data'],
            'paymentTerms' => $paymentTerm,
            'paymentTermsObject' => $paymentTerms['data'],
            'partners' => $partners['data'],
            'addresses' => $addresses['data'],
            'contacts' => $contacts['data']
        ];
    }
}
