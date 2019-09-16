<?php

namespace Platform\Customer\Commands;

class UpdateCustomerCommand {

    /**
     * @var string
     */
    public $customerId;

   /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $name;
    
    /**
     * @var string
     */
    public $business_entity;
    
    /**
     * @var string
     */
    public $import_export_license;
    
    /**
     * @var string
     */
    public $tax_id;
    
    /**
     * @var string
     */
    public $vat_sales_tax_reg;
    
    /**
     * @var string
     */
    public $company_reg;

    /**
     * @var array
     */
    public $address;

    /**
     * @var array
     */
    public $contact;

    /**
     * @var array
     */
    public $brand;

    function __construct($data, $customerId)
    {   
        $this->customerId = $customerId;
        $this->code = $data['code'];
        $this->name =  $data['name'];
        $this->business_entity = !isset($data['businessEntity'])? NULL : $data['businessEntity'];
        $this->import_export_license = !isset($data['importExportLicense'])? NULL : $data['importExportLicense'];
        $this->tax_id  = !isset($data['taxId'])? NULL : $data['taxId'];
        $this->vat_sales_tax_reg = !isset($data['vatSalesTaxReg'])? NULL : $data['vatSalesTaxReg'];
        $this->company_reg   = !isset($data['companyReg'])? NULL : $data['companyReg'];
        $this->addresses = !isset($data['addresses'])? [] : $data['addresses'];
        $this->contacts = !isset($data['contacts'])? [] : $data['contacts'];
        $this->brands = !isset($data['brands'])? [] : $data['brands'];
        $this->partners = !isset($data['partners'])? [] : $data['partners'];
        $this->types = !isset($data['types'])? [] : $data['types'];
        $this->services = !isset($data['services'])? [] : $data['services'];
        $this->requirements = !isset($data['requirements'])? [] : $data['requirements'];
        $this->paymentTerms = !isset($data['paymentTerms'])? [] : $data['paymentTerms'];
    }

} 