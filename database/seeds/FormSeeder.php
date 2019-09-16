<?php

use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $form_desc = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In interdum lacus magna, sit amet consequat neque hendrerit sed. Sed malesuada a nisi quis vestibulum. Maecenas vehicula varius odio ac consectetur. Nullam ultricies magna urna, sit amet aliquet turpis bibendum vel. Quisque vel quam eu arcu blandit blandit. Nullam in velit dui. Suspendisse cursus, mauris nec bibendum molestie, elit massa molestie metus, non ornare mauris tortor et turpis. Duis a consequat felis. Sed ultricies tincidunt elit quis maximus. In laoreet tempus ante, sit amet pharetra nisi rutrum quis. Aliquam at venenatis dolor. Morbi in malesuada urna. Morbi vel libero a justo condimentum interdum. Maecenas ac rutrum erat';
         $proforma_invoice_desc = 'A pro forma invoice is a document that states sourceeasy 
                                    commitment on part of the seller to deliver the products
                                    or services as notified to the buyer for a specific price.';
         // Model::unguard();
         \DB::statement("TRUNCATE TABLE forms RESTART IDENTITY CASCADE");
         \DB::insert(" INSERT INTO forms (form_name, form_table_name, created_at, updated_at, form_type, form_description, form_image) VALUES

         	        ('Form Sample Invoice', 'form_sample_invoice', now() , now(), 'Sample Invoice', concat('Sales Invoice : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/salesInvoice.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/salesInvoice_thumbnail.png\" }'),
                    
                    ('Form Proforma Invoice', 'form_proforma_invoice', now() , now(), 'Proforma Invoice', concat('Proforma Invoice : ', '$proforma_invoice_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/salesInvoice.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/salesInvoice_thumbnail.png\" }'),
                    
                    ('Form Sample Shipment Invoice', 'form_sample_shipment_invoice', now() , now(), 'Sample Shipment Invoice', concat('Sales Invoice : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/salesInvoice.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/salesInvoice_thumbnail.png\" }'),
                    
                    ('Form Bulk Shipment Invoice', 'form_bulk_shipment_invoice', now() , now(), 'Bulk Shipment Invoice', concat('Sales Invoice : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/salesInvoice.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/salesInvoice_thumbnail.png\" }'),

                    ('Form Sample PO', 'form_sample_po', now(), now(), 'Sample PO', concat('Form Purchase Order : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder_thumbnail.png\" }' ),

                    ('Form Sample Materials PO', 'form_sample_materials_po', now(), now(), 'Sample Materials PO', concat('Form Purchase Order : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder_thumbnail.png\" }' ),
                    
                    ('Form Bulk Materials PO', 'form_bulk_materials_po', now(), now(), 'Bulk Materials PO', concat('Form Purchase Order : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder_thumbnail.png\" }' ),

                    ('Form Sample Jobwork PO', 'form_sample_jobwork_po', now(), now(), 'Sample Jobwork PO', concat('Form Purchase Order : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder_thumbnail.png\" }' ),
                    
                    ('Form Bulk Jobwork PO', 'form_bulk_jobwork_po', now(), now(), 'Bulk Jobwork PO', concat('Form Purchase Order : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder_thumbnail.png\" }' ),

                    ('Form CMT / Freight estimate PO', 'form_freight_estimate', now(), now(), 'CMT / Freight estimate PO', concat('Form Purchase Order : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/purchaseOrder_thumbnail.png\" }' ) ,

                     ('Form Shipping Notice','form_shipping_notice', now() , now(), 'Shipping Notice', concat('Form Sales Order : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/shippingNotice.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/shippingNotice_thumbnail.png\" }' ),

                    ('Form Order Shipment Reconciliation','form_order_shipment_reconciliation', now() , now(), 'Reconciliation', concat('Form Order Shipment Reconciliation : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/shipmentReconciliation.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/shipmentReconciliation_thumbnail.png\" }' ),

                    ('Form Customer Outbound Notification','form_customer_outbound_notification', now() , now(), 'Outbound Notification', concat('Form Customer Outbound Notification : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/shippingNotice.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/shippingNotice_thumbnail.png\" }' ),

                    ('Form Production Order','form_production_order', now() , now(), 'Production Order', concat('Form Production Order : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/productionOrder.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/productionOrder_thumbnail.png\" }' ),

                    ('Form Commercial Invoice','form_commercial_invoice', now() , now(), 'Commercial Invoic', concat('Form Commercial Invoice : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/vendorCommInvoice.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/vendorCommInvoice_thumbnail.png\" }' ),

                    ('Form Actual Packing List','form_actual_packing_list', now() , now(), 'Packing List', concat('Form Actual Packing List : ', '$form_desc'), '{\"selfLink\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/apl.png\", \"thumbnail\" : \"https://sourceeasycdn.s3.amazonaws.com/www.v2/imgs/apl_thumbnail.png\" }' )
                ");

         // Model::reguard();
         echo 'Form seeder executed';

    }
}
