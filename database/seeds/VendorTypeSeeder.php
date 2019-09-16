<?php

use Illuminate\Database\Seeder;

class VendorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       \DB::table('vendor_vendor_types')->delete();
       \DB::table('vendor_types')->delete();
       \DB::insert(" INSERT INTO vendor_types (name, created_at, updated_at) VALUES
                   ('MILL' , now() , now()),
                   ('AGENT', now() , now()),
                   ('FACTORY', now() , now()),
                   ('TRADER' , now() , now()),
                   ('JOBWORK' , now() ,now()),
                   ('SUPPLIER' , now(), now()),
                   ('FABRIC MILL' ,now(), now()),
                   ('MANUFACTURER' ,now(), now()),
                   ('STOCKIST' , now() ,now()),
                   ('MARKET' , now(), now()),
                   ('GARMENTER' , now() ,now()),
                    ('GARMENTER MANUFACTURER', now() , now() ),
                    ('FABRIC', now() , now() ),
                    ('ACCESSORIES', now() , now() ),
                    ('SERVICES', now() , now() ),
                    ('PRINTER', now() , now() )");

       \DB::table('vendor_vendor_service')->delete();
       \DB::table('vendor_service')->delete();
       \DB::insert(" INSERT INTO vendor_service (name, created_at, updated_at) VALUES
                   ('FULL PACKAGES',now() ,now()),
                   ('FABRIC',now() ,now()),
                   ('TRIMS', now(), now()),
                   ('ACCESSORIES', now() ,now()),
                   ('CMT ONLY', now() ,now()),
                   ('PRINTING', now(), now()),
                   ('EMBROIDERY' ,now() ,now()),
                   ('SUBLIMATION' ,now() ,now()),
                    ('CUSTOM MILL', now() , now() ),
                    ('STOCK VENDOR', now() , now() ),
                    ('ELASTICS', now() , now() ),
                    ('LACES', now() , now() ),
                    ('THREAD', now() , now() ),
                    ('CLOSURES', now() , now() ),
                    ('MISC ACCESSORIES', now() , now() ),
                    ('PACKAGING', now() , now() ),
                    ('GRAPHIC SCREEN', now() , now() ),
                    ('ROTARY SCREEN', now() , now() ),
                    ('SUBLIMATION', now() , now() ),
                    ('DIGITAL PRINT', now() , now() ),
                    ('EMBROIDERY', now() , now() ),
                    ('QUALITY INSPECTION', now() , now() ),
                    ('LOGISTIC', now() , now() )
                ");

       \DB::table('vendor_vendor_payment_terms')->delete();
       \DB::table('vendor_payment_terms')->delete();
       \DB::insert(" INSERT INTO vendor_payment_terms (name, created_at, updated_at) VALUES
                    ('L/C' , now() , now()),
                    ('T/T' , now() , now()),
                    ('D/P' , now() , now()),
                    ('D/A' , now() , now()),
                    ('NET 7', now() , now() ),
                    ('NET 10', now() , now() ),
                    ('NET 15', now() , now() ),
                    ('NET 30', now() , now() ),
                    ('NET 60', now() , now() ),
                    ('NET 90', now() , now() ),
                    ('30 DIP/30 NET 7/40 NET 30', now() , now() ),
                    ('50% ADVANCE AND BALANCE ON DELIVERY', now() , now() ),
                    ('40% ADVANCE AND BALANCE 7 DAYS CREDIT', now() , now() ),
                    ('15 DAYS CREDIT', now() , now() ),
                    ('7 DAYS CREDIT', now() , now() ),
                    ('ON DELIVERY', now() , now() ),
                    ('10TH OF SUSIQUENT MONTH', now() , now() ),
                    ('5TH IF SUBSIQUENT MONTH', now() , now() )
                ");
        	
    }
}
