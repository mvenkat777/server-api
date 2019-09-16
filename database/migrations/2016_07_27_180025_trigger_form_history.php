<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerFormHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::unprepared('CREATE OR REPLACE FUNCTION trgfunc_insert_formhistory()
                        RETURNS TRIGGER AS
                        $BODY$
                        declare
                v_his_formuser_id text;
                            v_his_formuser_form_name_id integer;
                v_his_formuser_form_status_id integer;
                v_his_formuser_created_by text;
                v_his_formuser_updated_by text;
                v_his_formuser_submitted_by text;
                v_his_formuser_submitted_at timestamp;
                v_his_formuser_approval_request_for text;
                v_his_formuser_is_approved boolean;
                v_his_formuser_approved_at timestamp;
                v_his_formuser_approved_by text;
                v_his_formuser_is_rejected boolean;
                v_his_formuser_rejected_at timestamp;
                v_his_formuser_rejected_by text;
                v_his_formuser_remark text;
                v_his_formuser_archived_at timestamp;
                v_his_formuser_created_at timestamp;
                v_his_formuser_updated_at timestamp;
                v_his_formuser_deleted_at timestamp;

                v_his_form_olddata json;
                v_his_form_newdata json;
                v_his_form_user_olddata json;
                v_his_form_user_newdata json;
                v_form_data json;
                v_form_user_data json;
                

                v_form_table_name text;
                vid text;
                vselect text;

                
                        begin

                if(TG_TABLE_NAME != \'form_user\') then
                    v_form_user_data := (select row_to_json(t)
                                        from 
                                            (select  id,
                                                     form_name_id,
                                                     form_status_id,
                                                     created_by,
                                                     updated_by,
                                                     submitted_by,
                                                     submitted_at,
                                                     approval_request_for,
                                                     is_approved,
                                                     approved_at,
                                                     approved_by,
                                                     is_rejected,
                                                     rejected_at,
                                                     rejected_by,
                                                     remark,
                                                     archived_at,
                                                     created_at,
                                                     updated_at,
                                                     deleted_at                    
                    
                                                from form_user 
                                                where id=old.form_user_id
                                            ) t
                                        );
            end if;
                if(TG_TABLE_NAME = \'form_sales_order\') then
                    v_his_form_olddata := (select row_to_json(t)
                                from 
                                ( select old.id,
                                     old.form_user_id,
                                     old.bill_to_address,
                                     old.customer_po_number,
                                     old.country_of_origin,
                                     old.country_of_origin_of_goods,
                                     old.final_destination_of_goods,
                                     old.mode_of_shipment,
                                     old.order_date,
                                     old.delivery_date,
                                     old.port_of_loading,
                                     old.cancel_date,
                                     old.payment_terms,
                                     old.data,
                                     old.archived_at,
                                     old.created_at,
                                     old.updated_at,
                                     old.deleted_at,
                                     old.title,
                                     old.ship_to_address
                                  from form_sales_order where id=old.id) t);
                                  
                    v_his_form_newdata := (select row_to_json(t)
                                from 
                                ( select new.id,
                                     new.form_user_id,
                                     new.bill_to_address,
                                     new.customer_po_number,
                                     new.country_of_origin,
                                     new.country_of_origin_of_goods,
                                     new.final_destination_of_goods,
                                     new.mode_of_shipment,
                                     new.order_date,
                                     new.delivery_date,
                                     new.port_of_loading,
                                     new.cancel_date,
                                     new.payment_terms,
                                     new.data,
                                     new.archived_at,
                                     new.created_at,
                                     new.updated_at,
                                     new.deleted_at,
                                     new.title,
                                     new.ship_to_address
                                  from form_sales_order where id=new.id) t);                             
                elsif(TG_TABLE_NAME = \'form_production_order\') then
                        v_his_form_olddata := (select row_to_json(t)
                                from 
                                ( select old.id,
                                     old.form_user_id,
                                     old.se_issuing_office,
                                     old.vendor,
                                     old.customer_id,
                                     old.ship_to,
                                     old.po_date,
                                     old.archived_at,
                                     old.created_at,
                                     old.updated_at,
                                     old.deleted_at,
                                     old.title,
                                     old.po,
                                     old.shipping_method,
                                     old.shipping_terms,
                                     old.inco_term,
                                     old.sizes,
                                     old.payment_terms,
                                     old.factory_ship_date,
                                     old.factory_cancel_date,
                                     old.additional_details_as_needed,
                                     old.date,
                                     old.authorized_by,
                                     old.data
                                  from form_production_order where id=old.id ) t);
                                  
                    v_his_form_newdata := (select row_to_json(t)
                                from 
                                ( select new.id,
                                     new.form_user_id,
                                     new.se_issuing_office,
                                     new.vendor,
                                     new.customer_id,
                                     new.ship_to,
                                     new.po_date,
                                     new.archived_at,
                                     new.created_at,
                                     new.updated_at,
                                     new.deleted_at,
                                     new.title,
                                     new.po,
                                     new.shipping_method,
                                     new.shipping_terms,
                                     new.inco_term,
                                     new.sizes,
                                     new.payment_terms,
                                     new.factory_ship_date,
                                     new.factory_cancel_date,
                                     new.additional_details_as_needed,
                                     new.date,
                                     new.authorized_by,
                                     new.data
                                  from form_production_order where id=new.id ) t);
                        elsif(TG_TABLE_NAME = \'form_shipping_notice\') then
                                v_his_form_olddata := (select row_to_json(t)
                                from 
                                ( select old.id,
                                     old.form_user_id,
                                     old.bill_to_address,
                                     old.customer_po_number,
                                     old.delivery_date,
                                     old.ship_to_address,
                                     old.origin_country_goods,
                                     old.destination_country,
                                     old.shipment_mode,
                                     old.origin_country,
                                     old.cancel_date,
                                     old.created_by,
                                     old.archived_at,
                                     old.created_at,
                                     old.updated_at,
                                     old.deleted_at,
                                     old.data,
                                     old.title,
                                     old.created_by_date
                                  from form_shipping_notice where id=old.id) t);
                                  
                    v_his_form_newdata := (select row_to_json(t)
                                from 
                                ( select new.id,
                                     new.form_user_id,
                                     new.bill_to_address,
                                     new.customer_po_number,
                                     new.delivery_date,
                                     new.ship_to_address,
                                     new.origin_country_goods,
                                     new.destination_country,
                                     new.shipment_mode,
                                     new.origin_country,
                                     new.cancel_date,
                                     new.created_by,
                                     new.archived_at,
                                     new.created_at,
                                     new.updated_at,
                                     new.deleted_at,
                                     new.data,
                                     new.title,
                                     new.created_by_date
                                  from form_shipping_notice where id=new.id) t);
                        elsif(TG_TABLE_NAME = \'form_purchase_order\') then
                            v_his_form_olddata := (select row_to_json(t)
                                    from 
                                    ( select old.id,
                                         old.form_user_id,
                                         old.se_issuing_office,
                                         old.vendor,
                                         old.customer_id,
                                         old.ship_to,
                                         old.shipping_method,
                                         old.payment_method,
                                         old.inco_term,
                                         old.payment_terms,
                                         old.factory_ship_date,
                                         old.factory_cancel_date,
                                         old.additional_details_as_needed,
                                         old.date,
                                         old.authorized_by,
                                         old.data,
                                         old.created_at,
                                         old.updated_at,
                                         old.deleted_at,
                                         old.title,
                                         old.po
                                      from form_purchase_order where id=old.id ) t);
                                      
                        v_his_form_newdata := (select row_to_json(t)
                                    from 
                                    ( select new.id,
                                        new.form_user_id,
                                         new.se_issuing_office,
                                         new.vendor,
                                         new.customer_id,
                                         new.ship_to,
                                         new.shipping_method,
                                         new.payment_method,
                                         new.inco_term,
                                         new.payment_terms,
                                         new.factory_ship_date,
                                         new.factory_cancel_date,
                                         new.additional_details_as_needed,
                                         new.date,
                                         new.authorized_by,
                                         new.data,
                                         new.created_at,
                                         new.updated_at,
                                         new.deleted_at,
                                         new.title,
                                         new.po
                                      from form_purchase_order where id=new.id) t);

                            elsif(TG_TABLE_NAME = \'form_order_shipment_reconciliation\') then
                                v_his_form_olddata := (select row_to_json(t)
                                        from 
                                        ( select old.id,
                                             old.form_user_id,
                                             old.customer,
                                             old.purchase_order,
                                             old.archived_at,
                                             old.created_at,
                                             old.updated_at,
                                             old.deleted_at,
                                             old.title
                                          from form_order_shipment_reconciliation where id=old.id) t);
                                          
                            v_his_form_newdata := (select row_to_json(t)
                                        from 
                                        ( select new.id,
                                            new.form_user_id,
                                             new.customer,
                                             new.purchase_order,
                                             new.archived_at,
                                             new.created_at,
                                             new.updated_at,
                                             new.deleted_at,
                                             new.title
                                          from form_order_shipment_reconciliation where id=new.id) t);

                        elsif(TG_TABLE_NAME = \'form_customer_outbound_notification\') then
                                v_his_form_olddata := (select row_to_json(t)
                                        from 
                                        ( select old.id,
                                             old.form_user_id,
                                             old.vendor,
                                             old.customer,
                                             old.customer_po,
                                             old.se_po,
                                             old.total_shipped,
                                             old.ex_factory_date,
                                             old.eta_customer,
                                             old.archived_at,
                                             old.data,
                                             old.created_at,
                                             old.updated_at,
                                             old.deleted_at,
                                             old.title
                                          from form_customer_outbound_notification where id=old.id) t);
                                          
                            v_his_form_newdata := (select row_to_json(t)
                                        from 
                                        ( select new.id,
                                            new.form_user_id,
                                             new.vendor,
                                             new.customer,
                                             new.customer_po,
                                             new.se_po,
                                             new.total_shipped,
                                             new.ex_factory_date,
                                             new.eta_customer,
                                             new.archived_at,
                                             new.data,
                                             new.created_at,
                                             new.updated_at,
                                             new.deleted_at,
                                             new.title
                                          from form_customer_outbound_notification where id=new.id) t);

                            elsif(TG_TABLE_NAME = \'form_commercial_invoice\') then
                                v_his_form_olddata := (select row_to_json(t)
                                from 
                                ( select old.id,
                                     old.form_user_id,
                                     old.exporter,
                                     old.consignee,
                                     old.notify_party,
                                     old.loading_port,
                                     old.destination,
                                     old.carrier,
                                     old.sailing_on,
                                     old.invoice_number,
                                     old.invoice_date,
                                     old.origin_remark,
                                     old.shipment_remark,
                                     old.freight_remark,
                                     old.total_qty,
                                     old.total_package,
                                     old.total_net_weight,
                                     old.total_gross_weight,
                                     old.total_measurement,
                                     old.data,
                                     old.archived_at,
                                     old.created_at,
                                     old.updated_at,
                                     old.deleted_at,
                                     old.title
                                  from form_commercial_invoice where id=old.id) t);
                                  
                    v_his_form_newdata := (select row_to_json(t)
                                from 
                                ( select new.id,
                                     new.form_user_id,
                                     new.exporter,
                                     new.consignee,
                                     new.notify_party,
                                     new.loading_port,
                                     new.destination,
                                     new.carrier,
                                     new.sailing_on,
                                     new.invoice_number,
                                     new.invoice_date,
                                     new.origin_remark,
                                     new.shipment_remark,
                                     new.freight_remark,
                                     new.total_qty,
                                     new.total_package,
                                     new.total_net_weight,
                                     new.total_gross_weight,
                                     new.total_measurement,
                                     new.data,
                                     new.archived_at,
                                     new.created_at,
                                     new.updated_at,
                                     new.deleted_at,
                                     new.title
                                  from form_commercial_invoice where id=new.id) t);

                        elsif(TG_TABLE_NAME = \'form_actual_packing_list\') then
                                v_his_form_olddata := (select row_to_json(t)
                                from 
                                ( select old.id,
                                     old.form_user_id,
                                     old.factory_name,
                                     old.factory_address,
                                     old.container_no,
                                     old.seal_no,
                                     old.shipping_date,
                                     old.made_in,
                                     old.packing_date,
                                     old.etd_vn,
                                     old.eta_sf,
                                     old.vessel_name,
                                     old.ship_to,
                                     old.attn,
                                     old.style,
                                     old.po,
                                     old.quantity,
                                     old.description,
                                     old.archived_at,
                                     old.data,
                                     old.created_at,
                                     old.updated_at,
                                     old.deleted_at,
                                     old.title
                                  from form_actual_packing_list where id=old.id) t);
                                  
                    v_his_form_newdata := (select row_to_json(t)
                                from 
                                ( select new.id,
                                     new.form_user_id,
                                     new.factory_name,
                                     new.factory_address,
                                     new.container_no,
                                     new.seal_no,
                                     new.shipping_date,
                                     new.made_in,
                                     new.packing_date,
                                     new.etd_vn,
                                     new.eta_sf,
                                     new.vessel_name,
                                     new.ship_to,
                                     new.attn,
                                     new.style,
                                     new.po,
                                     new.quantity,
                                     new.description,
                                     new.archived_at,
                                     new.data,
                                     new.created_at,
                                     new.updated_at,
                                     new.deleted_at,
                                     new.title
                                  from form_actual_packing_list where id=new.id) t);

                    end if;
                    if(TG_TABLE_NAME != \'form_user\') then
                            INSERT INTO form_history(
                              his_form_user_olddata,
                              his_form_olddata,
                              his_form_newdata, 
                              trigger_table,                      
                              created_at,
                              updated_at,
                              deleted_at
                             )

                            VALUES ( 
                                 v_form_user_data,
                                 v_his_form_olddata,
                                 v_his_form_newdata,
                                 TG_TABLE_NAME,
                                 now(),
                                 now(),
                                 null                                
                                   );
                            RETURN NEW;
                end if;
            if(TG_TABLE_NAME = \'form_user\') then


                v_his_form_user_olddata := (select row_to_json(t)
                                        from (
                                                select old.id,
                                                       old.form_name_id,
                                                       old.form_status_id,
                                                       old.created_by,
                                                       old.updated_by,
                                                       old.submitted_by,
                                                       old.submitted_at,
                                                       old.approval_request_for,
                                                       old.is_approved,
                                                       old.approved_at,
                                                       old.approved_by,
                                                       old.is_rejected,
                                                       old.rejected_at,
                                                       old.rejected_by,
                                                       old.remark,
                                                       old.archived_at,
                                                       old.created_at,
                                                       old.updated_at,
                                                       old.deleted_at
                                                from form_user 
                                                where id=old.id
                                             )t
                                        );
                v_his_form_user_newdata := (select row_to_json(t)
                                        from (
                                                select new.id,
                                                       new.form_name_id,
                                                       new.form_status_id,
                                                       new.created_by,
                                                       new.updated_by,
                                                       new.submitted_by,
                                                       new.submitted_at,
                                                       new.approval_request_for,
                                                       new.is_approved,
                                                       new.approved_at,
                                                       new.approved_by,
                                                       new.is_rejected,
                                                       new.rejected_at,
                                                       new.rejected_by,
                                                       new.remark,
                                                       new.archived_at,
                                                       new.created_at,
                                                       new.updated_at,
                                                       new.deleted_at
                                                from form_user 
                                                where id=old.id
                                             )t
                                        );


                select form_table_name from forms into v_form_table_name where id=old.form_name_id;
                
                select id from form_user into vid where id = old.id;

                if((v_form_table_name=\'form_production_shipment_invoice\') or (v_form_table_name=\'form_sample_invoice\') or (v_form_table_name=\'form_production_deposit_invoice\') ) then
                    v_form_data := (select row_to_json(t) 
                                    from   (select * from form_sales_order where form_user_id = vid) t);    
                elsif(v_form_table_name=\'form_actual_packing_list\') then
                    v_form_data := (select row_to_json(t) 
                                    from   (select * from form_actual_packing_list where form_user_id = vid) t);    
                elsif(v_form_table_name=\'form_commercial_invoice\') then
                    v_form_data := (select row_to_json(t) 
                                    from   (select * from form_commercial_invoice where form_user_id = vid) t);    
                elsif(v_form_table_name=\'form_customer_outbound_notification\') then
                    v_form_data := (select row_to_json(t) 
                                    from   (select * from form_customer_outbound_notification where form_user_id = vid) t); 
                elsif(v_form_table_name=\'form_order_shipment_reconciliation\') then
                    v_form_data := (select row_to_json(t) 
                                    from   (select * from form_order_shipment_reconciliation where form_user_id = vid) t);                           
                elsif(v_form_table_name=\'form_production_order\') then
                    v_form_data := (select row_to_json(t) 
                                    from   (select * from form_production_order where form_user_id = vid) t);                           
                elsif(v_form_table_name=\'form_purchase_order\') then
                    v_form_data := (select row_to_json(t) 
                                    from   (select * from form_purchase_order where form_user_id = vid) t);                           
                elsif(v_form_table_name=\'form_sales_order\') then
                    v_form_data := (select row_to_json(t) 
                                    from   (select * from form_sales_order where form_user_id = vid) t);                           
                elsif(v_form_table_name=\'form_shipping_notice\') then
                    v_form_data := (select row_to_json(t) 
                                    from   (select * from form_shipping_notice where form_user_id = vid) t);                           
                end if;
                
                
                INSERT INTO form_history(
                                  his_form_user_olddata,
                                  his_form_user_newdata,
                                  his_form_olddata,
                                  trigger_table,                       
                                  created_at,
                                  updated_at,
                                  deleted_at
                                )

                            VALUES ( 
                                 v_his_form_user_olddata,
                                 v_his_form_user_newdata,
                                 v_form_data,
                                 \'form_user\',
                                 now(),
                                 now(),
                                 null                                
                                   );
                            RETURN NEW;
                
                        end if;
                        

                        end;
                        $BODY$
                        LANGUAGE plpgsql VOLATILE
                        COST 100;');


            // DB::unprepared('CREATE TRIGGER trg_insert_formhistory_so BEFORE UPDATE ON form_sales_order
            //            FOR EACH ROW EXECUTE PROCEDURE trgfunc_insert_formhistory();');
            // DB::unprepared('CREATE TRIGGER trg_insert_formhistory_pdo BEFORE UPDATE ON form_production_order
            //            FOR EACH ROW EXECUTE PROCEDURE trgfunc_insert_formhistory();');
            // DB::unprepared('CREATE TRIGGER trg_insert_formhistory_apl BEFORE UPDATE ON form_actual_packing_list
            //            FOR EACH ROW EXECUTE PROCEDURE trgfunc_insert_formhistory();');
            // DB::unprepared('CREATE TRIGGER trg_insert_formhistory_ci BEFORE UPDATE ON form_commercial_invoice
            //            FOR EACH ROW EXECUTE PROCEDURE trgfunc_insert_formhistory();');
            // DB::unprepared('CREATE TRIGGER trg_insert_formhistory_con BEFORE UPDATE ON form_customer_outbound_notification
            //            FOR EACH ROW EXECUTE PROCEDURE trgfunc_insert_formhistory();');
            // DB::unprepared('CREATE TRIGGER trg_insert_formhistory_osr BEFORE UPDATE ON form_order_shipment_reconciliation
            //            FOR EACH ROW EXECUTE PROCEDURE trgfunc_insert_formhistory();');
            // DB::unprepared('CREATE TRIGGER trg_insert_formhistory_pco BEFORE UPDATE ON form_purchase_order
            //            FOR EACH ROW EXECUTE PROCEDURE trgfunc_insert_formhistory();');
            // DB::unprepared('CREATE TRIGGER trg_insert_formhistory_sn BEFORE UPDATE ON form_shipping_notice
            //            FOR EACH ROW EXECUTE PROCEDURE trgfunc_insert_formhistory();');
            // DB::unprepared('CREATE TRIGGER trg_insert_formhistory_user BEFORE UPDATE ON form_user
            //             FOR EACH ROW EXECUTE PROCEDURE trgfunc_insert_formhistory();');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::unprepared('DROP TRIGGER trg_insert_formhistory_so on form_sales_order;');

        DB::unprepared('DROP TRIGGER trg_insert_formhistory_user on form_user;');

        DB::unprepared('DROP FUNCTION trgfunc_insert_formhistory();');
    
        
    }
}
