<?php

use Illuminate\Database\Seeder;

class AppsListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('apps_list')->delete();
        DB::insert(" INSERT INTO apps_list (app_name, icon, status, created_at, updated_at) VALUES
                    ('getting started', 'pages', 'active', now() , now() ),
                    ('dashboard', 'dashboard', 'active', now() , now() ),
                    ('line', 'dashboard', 'active', now() , now() ),
                    ('techpack', 'layers', 'active', now() , now() ),
                    ('samplecontainer', 'check_box', 'active', now() , now() ),
                    ('sample', 'check_box', 'active', now() , now() ),
                    ('calendar', 'alarm', 'active', now() , now() ),
                    ('Messaging', 'web', 'active', now() , now() ),
                    ('task', 'toc', 'active', now() , now() ),
                    ('customer', 'people_outline', 'active', now() , now() ),
                    ('vendor', 'map', 'active', now() , now() ),
                    ('material', 'gradient', 'active', now() , now() ),
                    ('order', 'shopping_cart', 'active', now() , now() ),
                    ('pom', 'chrome_reader_mode', 'active', now() , now() ),
                    ('user', 'people', 'active', now() , now() ),
                    ('admin', 'build', 'active', now() , now() ),
                    ('rule', 'vpn_lock', 'active', now() , now() )
                ");

    }
}
