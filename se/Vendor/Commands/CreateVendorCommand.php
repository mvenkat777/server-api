<?php

namespace Platform\Vendor\Commands;

class CreateVendorCommand
{
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
	public $country_code;
	
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
	public $annual_shipped_turnover;

	/**
	 * @var string
	 */
	public $annual_shipped_quantity;
	
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
	public $banks;

	/**
	 * @var array
	 */
	public $types;

	/**
	 * @var array
	 */
	public $services;

	/**
	 * @var array
	 */
	public $paymentTerms;

	/**
	 * @var array
	 */
	public $capabilities;

	function __construct($data)
    {	
    	$this->code = $data['code'];
    	$this->name =  $data['name'];
        $this->business_entity = !isset($data['businessEntity'])? NULL : $data['businessEntity'];
        $this->country_code = !isset($data['countryCode'])? NULL : $data['countryCode'];
        $this->import_export_license = !isset($data['importExportLicense'])? NULL : $data['importExportLicense'];
        $this->tax_id  = !isset($data['taxId'])? NULL : $data['taxId'];
        $this->vat_sales_tax_reg = !isset($data['vatSalesTaxReg'])? NULL : $data['vatSalesTaxReg'];
        $this->company_reg   = !isset($data['companyReg'])? NULL : $data['companyReg'];
        $this->annual_shipped_turnover = !isset($data['annualShippedTurnover'])? NULL:$data['annualShippedTurnover'];
        $this->annual_shipped_quantity = !isset($data['annualShippedQuantity'])? NULL:$data['annualShippedQuantity'];
        $this->addresses = !isset($data['addresses'])? [] : $data['addresses'];
        $this->contacts = !isset($data['contact'])? [] : $data['contact'];
        $this->banks = !isset($data['banks'])? [] : $data['banks'];
        $this->partners = !isset($data['partners'])? [] : $data['partners'];
        $this->types = !isset($data['types'])? [] : $data['types'];
        $this->capabilities = !isset($data['capabilities'])? [] : $data['capabilities'];
        $this->paymentTerms = !isset($data['paymentTerms'])? [] : $data['paymentTerms'];
        $this->services = !isset($data['services'])? [] : $data['services'];
    } 
}