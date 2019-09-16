<?php

namespace Platform\Materials\Transformers;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use App\MaterialLibrary;
use League\Fractal\TransformerAbstract;
use Platform\Materials\Transformers\MaterialTransformer;
use Platform\Vendor\Transformers\MetaVendorTransformer;
use Platform\Customer\Transformers\MetaCustomerTransformer;


class MaterialLibraryTransformer extends TransformerAbstract
{ 
    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function transform(MaterialLibrary $materialLibrary)
    {
        $material = $this->item($materialLibrary->material, new MaterialTransformer);
        $material = $this->manager->createData($material)->toArray();
        //dd($materialLibrary->vendor);
        $vendor = $this->item($materialLibrary->vendor , new MetaVendorTransformer);
        $vendor = $this->manager->createData($vendor)->toArray();
        //dd($materialLibrary->primaryPrintVendor);
        if($materialLibrary->primaryPrintVendor){
            $primaryPrintVendor = $this->item($materialLibrary->primaryPrintVendor , new MetaVendorTransformer);
            $primaryPrintVendor = $this->manager->createData($primaryPrintVendor)->toArray();    
        }
        
        if($materialLibrary->secondaryPrintVendor){
        $secondaryPrintVendor = $this->item($materialLibrary->secondaryPrintVendor , new MetaVendorTransformer);
        $secondaryPrintVendor = $this->manager->createData($secondaryPrintVendor)->toArray();
        }

        //dd($materialLibrary->customers);
        $customers = $this->collection($materialLibrary->customers, new MetaCustomerTransformer);
        $customers = $this->manager->createData($customers)->toArray();
        
        $data = [
            'materialLibraryId' => $materialLibrary->id,
            'material' => $material['data'],
            'vendor' => $vendor['data'],
            'fabricReference' => (string) $materialLibrary->fabric_reference,
            'fabricStyle' => (string) ($materialLibrary->fabric_style),
            'costLocal' => (float) ($materialLibrary->cost_local),
            'costUsd' => (float) ($materialLibrary->cost_usd),
            'costUom' => (string) ($materialLibrary->cost_uom),
            'stock' => (string) ($materialLibrary->stock),
            'availGreige' => (string) ($materialLibrary->avail_greige),
            'notes' => (string) ($materialLibrary->notes),
            'majorCustomer' => $customers['data'],
            //'printCost' => (string) ($materialLibrary->print_cost),
            'primaryPrintVendor' => isset($primaryPrintVendor['data'])?$primaryPrintVendor['data']:"",
            'primaryPrintVendorCostUom' => (string) ($materialLibrary->primary_print_vendor_cost_uom),
            'primaryPrintVendorCostLocal' => is_null($materialLibrary->primary_print_vendor_cost_local)?$materialLibrary->primary_print_vendor_cost_local:(float) ($materialLibrary->primary_print_vendor_cost_local),
            'primaryPrintVendorCostUsd' => is_null($materialLibrary->primary_print_vendor_cost_usd)?$materialLibrary->primary_print_vendor_cost_usd:(float) ($materialLibrary->primary_print_vendor_cost_usd),
            'secondaryPrintVendor' => isset($secondaryPrintVendor['data'])?$primaryPrintVendor['data']:"",
            'secondaryPrintVendorCostUom' => (string) ($materialLibrary->secondary_print_vendor_cost_uom),
            'secondaryPrintVendorCostLocal' => is_null($materialLibrary->secondary_print_vendor_cost_local)?$materialLibrary->secondary_print_vendor_cost_local:(float) ($materialLibrary->secondary_print_vendor_cost_local),
            'secondaryPrintVendorCostUsd' => is_null($materialLibrary->secondary_print_vendor_cost_usd)?$materialLibrary->secondary_print_vendor_cost_usd:(float) ($materialLibrary->secondary_print_vendor_cost_usd),
            'fabricLeadTime' => is_null($materialLibrary->fabric_lead_time)?$materialLibrary->fabric_lead_time:(int)$materialLibrary->fabric_lead_time,
            'minimumOrderQuantity' => is_null($materialLibrary->minimum_order_quantity)?$materialLibrary->minimum_order_quantity:(int)$materialLibrary->minimum_order_quantity,
            'minimumOrderQuantityUom' => (string) ($materialLibrary->minimum_order_quantity_uom),
            'minimumOrderQuantitySurcharge' => is_null($materialLibrary->minimum_order_quantity_surcharge)?$materialLibrary->minimum_order_quantity_surcharge:(float) ($materialLibrary->minimum_order_quantity_surcharge),
            'minimumOrderQuantitySurchargeUsd' => is_null($materialLibrary->minimum_order_quantity_surcharge_usd)?$materialLibrary->minimum_order_quantity_surcharge_usd:(float) ($materialLibrary->minimum_order_quantity_surcharge_usd),
            'minimumColorQuantity' => is_null($materialLibrary->minimum_color_quantity)?($materialLibrary->minimum_color_quantity): (int) ($materialLibrary->minimum_color_quantity),
            'minimumColorQuantityUom' => (string) ($materialLibrary->minimum_color_quantity_uom),
            'minimumColorQuantitySurcharge' => is_null($materialLibrary->minimum_color_quantity_surcharge)?$materialLibrary->minimum_color_quantity_surcharge:(float) ($materialLibrary->minimum_color_quantity_surcharge),
            'minimumColorQuantitySurchargeUsd' => is_null($materialLibrary->minimum_color_quantity_surcharge_usd)?$materialLibrary->minimum_color_quantity_surcharge_usd:(float) ($materialLibrary->minimum_color_quantity_surcharge_usd),
            'libraryAttachment' => is_null($materialLibrary->library_attachment)?$materialLibrary->library_attachment: json_decode($materialLibrary->library_attachment),            
            'createdAt' => $materialLibrary->created_at->toDateTimeString(),
            'updatedAt' => $materialLibrary->updated_at->toDateTimeString()
        ];
      // dd($respondArr = collect($respondArr));
      return $data;
  }
       
}
