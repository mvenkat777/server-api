<?php

use Illuminate\Database\Seeder;

class FormTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('forms')
            ->where('form_name','=','Form Sales Order')
            ->update(['form_type' => 'Sales Order']);
        DB::table('forms')
            ->where('form_name','=','Form Shipping Notice')
            ->update(['form_type' => 'Shipping Notice']);

        DB::table('forms')
            ->where('form_name','=','Form Order Shipment Reconciliation')
            ->update(['form_type' => 'Reconciliation']);

         DB::table('forms')
            ->where('form_name','=','Form Customer Outbound Notification')
            ->update(['form_type' => 'Outbound Notification']);

        DB::table('forms')
            ->where('form_name','=','Form Production Order')
            ->update(['form_type' => 'Production Order']);

        DB::table('forms')
            ->where('form_name','=','Form Commercial Invoice')
            ->update(['form_type' => 'Commercial Invoice']);

         DB::table('forms')
            ->where('form_name','=','Form Actual Packing List')
            ->update(['form_type' => 'Packing List']);

        DB::table('forms')
            ->where('form_name','=','Form Purchase Order')
            ->update(['form_type' => 'Purchase Order']);
    }
}
