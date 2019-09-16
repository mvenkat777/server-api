<?php


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Platform\App\Commanding\DefaultCommandBus;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        $this->call('UserTableSeeder');
        $this->command->info('User table seeded!');

        $this->call('ProvidersSeeder');
        $this->command->info('Providers table seeded!');

        $this->call('LibraryItemTableSeeder');
        $this->command->info('Library Item table seeded!');

        $this->call('LibraryItemAttributeTableSeeder');
        $this->command->info('Library Item Attribute table seeded!');

        $this->call('TaskTagTableSeeder');
        $this->command->info('Tag table seeded!');

        $this->call('TasksSeeder');
        $this->command->info('Tasks seeded!');

        $this->call('TechpackSchemaGenerate');
        $this->command->info('Techpack Schema Genarated!');

        Model::reguard();
    }
}

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $password = bcrypt('se12345');

        DB::insert("INSERT INTO users (id, display_name, email, password, reset_pin, is_banned, is_god, is_active, confirmation_code, created_at, updated_at, se)
            VALUES ('0C2FB096-8A60-4037-BD44-E71590F260A0', 'SE Dev', 'sedev@sourceeasy.com', '$password', 0, false, true, true, NULL, now(), now(), true);");

        DB::insert("INSERT INTO user_details (user_id, created_at, updated_at) VALUES
            ('0C2FB096-8A60-4037-BD44-E71590F260A0', now(), now());");
    }
}

class LibraryItemTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('library_items')->delete();

        DB::insert("INSERT INTO library_items (id, name, slug, description, email, admin_created, attribute_count, status, meta, deleted_at, created_at, updated_at) VALUES
('0C2FB096-8A60-4037-BD44-E71590F260A9',    'LABELS',   'labels',   'LABELS',   'library.labels@sourceeasy.com',    true,  0,  'active',   '', NULL,   '2015-09-21 07:02:21',  '2015-09-21 07:02:21'),
('457DB47A-9E76-4305-8B50-ABB01AE1F9D7',    'TRIMS',    'trims',    'TRIMS',    'library.trims@sourceeasy.com', true,  0,  'active',   '', NULL,   '2015-09-21 07:02:19',  '2015-09-21 07:02:19'),
('4F8BBADD-0377-473E-99FC-038E87F7C7F9',    'FABRIC',   'fabric',   'FABRIC',   'library.fabric@sourceeasy.com',    true,  0,  'active',   '', NULL,   '2015-09-21 07:02:18',  '2015-09-21 07:02:18'),
('842AE648-92AE-456E-9AE0-8088619CA87D',    'PACKAGING',    'packaging',    'PACKAGING',    'library.packaging@sourceeasy.com', true,  0,  'active',   '', NULL,   '2015-09-21 07:02:23',  '2015-09-21 07:02:23'),
('BE1EC6CE-E0E1-4897-8F71-401DF293479F',    'ARTWORK',  'artwork',  'ARTWORK',  'library.artwork@sourceeasy.com',   true,  0,  'active',   '', NULL,   '2015-09-21 07:02:20',  '2015-09-21 07:02:20'),
('F62BFD4F-2BC1-4632-8174-29B62F807E37',    'WASH_FINISHING',   'wash_finishing',   'WASH FINISHING',   'library.processings@sourceeasy.com',   true,  0,  'active',   '', NULL,   '2015-09-21 07:02:22',  '2015-09-21 07:02:22');");
    }
}

class LibraryItemAttributeTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('library_item_attributes')->delete();
        DB::insert("INSERT INTO library_item_attributes (id, library_item_id, name, type, multi_valued, read_only, read_access_type, slug, description, admin_created, value_count, status, meta, deleted_at, created_at, updated_at) VALUES
('01D9BC87-FF28-48ED-AB11-35AD6C5C255D',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'description',  'text', false,  false,  'ALL',  'description',  'description',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:49',  '2015-09-21 07:02:49'),
('02D7BFC5-43DF-43A4-A9CE-6463CE9DE16D',    'BE1EC6CE-E0E1-4897-8F71-401DF293479F', 'classification',   'text', false,  false,  'ALL',  'classification',   'classification',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:10',  '2015-09-21 07:03:10'),
('070C4A04-25E7-4916-851F-44C73D66B81D',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'upload',   'text', false,  false,  'ALL',  'upload',   'upload',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:46',  '2015-09-21 07:02:46'),
('18C4A168-7FBD-4F09-8ABA-3609987F5AED',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'placement',    'text', false,  false,  'ALL',  'placement',    'placement',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:55',  '2015-09-21 07:02:55'),
('1AC5F29D-8AE5-4F0A-B073-B1D79DC68628',    'F62BFD4F-2BC1-4632-8174-29B62F807E37', 'upload',   'text', false,  false,  'ALL',  'upload',   'upload',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:17',  '2015-09-21 07:03:17'),
('1B61EAA4-9B50-4AD4-9E0D-0612E04791C5',    '842AE648-92AE-456E-9AE0-8088619CA87D', 'supplier', 'text', false,  false,  'ALL',  'supplier', 'supplier', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:01',  '2015-09-21 07:03:01'),
('1E511B6D-2C3B-451A-A334-41E5569C7FDF',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'costPerUnit',  'text', false,  false,  'ALL',  'cost_per_unit',    'cost_per_unit',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:58',  '2015-09-21 07:02:58'),
('269252F6-7E0C-4B3B-8B63-9FE9497DD20D',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'uom',  'text', false,  false,  'ALL',  'uom',  'uom',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:57',  '2015-09-21 07:02:57'),
('29004A89-FAB4-4738-848E-D450B40FA46F',    'F62BFD4F-2BC1-4632-8174-29B62F807E37', 'cost', 'text', false,  false,  'ALL',  'cost', 'cost', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:23',  '2015-09-21 07:03:23'),
('3271C913-C00F-4136-842A-06F14239A6CE',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'width',    'text', false,  false,  'ALL',  'width',    'width',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:52',  '2015-09-21 07:02:52'),
('38C09A5C-A7BB-46EE-95A0-0D96DD106241',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'classification',   'text', false,  false,  'ALL',  'classification',   'classification',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:47',  '2015-09-21 07:02:47'),
('457AD7D0-642A-4964-94AB-24AD5B35C892',    '0C2FB096-8A60-4037-BD44-E71590F260A9', 'placement',    'text', false,  false,  'ALL',  'placement',    'placement',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:31',  '2015-09-21 07:02:31'),
('4735296A-7879-4E9F-9FDE-AF23F0766485',    '842AE648-92AE-456E-9AE0-8088619CA87D', 'size', 'text', false,  false,  'ALL',  'size', 'size', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:04',  '2015-09-21 07:03:04'),
('4B8FDB51-0735-4E47-A7C1-49B469C2CCD0',    '842AE648-92AE-456E-9AE0-8088619CA87D', 'color',    'text', false,  false,  'ALL',  'color',    'color',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:05',  '2015-09-21 07:03:05'),
('4F30ED3D-0CBD-4A8F-B440-B61B70AE3096',    '0C2FB096-8A60-4037-BD44-E71590F260A9', 'consumptionUom',   'text', false,  false,  'ALL',  'consumption_uom',  'consumption_uom',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:32',  '2015-09-21 07:02:32'),
('524B2927-1224-4BD0-924D-BB3213B13F3E',    '0C2FB096-8A60-4037-BD44-E71590F260A9', 'classification',   'text', false,  false,  'ALL',  'classification',   'classification',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:25',  '2015-09-21 07:02:25'),
('52577BC4-8829-4215-B863-FDD7C8282DB3',    'BE1EC6CE-E0E1-4897-8F71-401DF293479F', 'description',  'text', false,  false,  'ALL',  'description',  'description',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:12',  '2015-09-21 07:03:12'),
('53FDEEB3-6403-4938-88C8-D3C8B9AAA3F7',    'BE1EC6CE-E0E1-4897-8F71-401DF293479F', 'notes',    'text', false,  false,  'ALL',  'notes',    'notes',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:15',  '2015-09-21 07:03:15'),
('54A953EE-5AE5-42D6-94D6-88DF44D8F202',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'classification',   'text', false,  false,  'ALL',  'classification',   'classification',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:35',  '2015-09-21 07:02:35'),
('54B6F59B-6691-4DBC-B28F-2CE093B337A3',    'BE1EC6CE-E0E1-4897-8F71-401DF293479F', 'cost', 'text', false,  false,  'ALL',  'cost', 'cost', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:16',  '2015-09-21 07:03:16'),
('55BA7253-A455-40DC-A5CA-B90215BACDB1',    '0C2FB096-8A60-4037-BD44-E71590F260A9', 'content',  'text', false,  false,  'ALL',  'content',  'content',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:28',  '2015-09-21 07:02:28'),
('5A90FC81-A610-4C80-86A4-B25982507A95',    '842AE648-92AE-456E-9AE0-8088619CA87D', 'description',  'text', false,  false,  'ALL',  'description',  'description',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:00',  '2015-09-21 07:03:00'),
('5DBCD1C1-E61D-4028-82A4-EC22D427FC69',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'uom',  'text', false,  false,  'ALL',  'uom',  'uom',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:44',  '2015-09-21 07:02:44'),
('6151D52A-0C6E-459C-83F1-5028C530B03E',    '842AE648-92AE-456E-9AE0-8088619CA87D', 'type', 'text', false,  false,  'ALL',  'type', 'type', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:02',  '2015-09-21 07:03:02'),
('643D63F9-40AC-4EC8-A01B-DF9A21F42294',    '842AE648-92AE-456E-9AE0-8088619CA87D', 'consumptionUom',   'text', false,  false,  'ALL',  'consumption_uom',  'consumption_uom',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:07',  '2015-09-21 07:03:07'),
('649D5FB7-9DA8-4AE0-9860-2EE3098FEC6B',    '842AE648-92AE-456E-9AE0-8088619CA87D', 'placement',    'text', false,  false,  'ALL',  'placement',    'placement',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:06',  '2015-09-21 07:03:06'),
('67C8B351-5AD3-4464-9A71-E47E23A3356B',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'supplier', 'text', false,  false,  'ALL',  'supplier', 'supplier', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:48',  '2015-09-21 07:02:48'),
('6AB49516-0B1E-4066-86BB-D3A5E90B1A5A',    'F62BFD4F-2BC1-4632-8174-29B62F807E37', 'supplier', 'text', false,  false,  'ALL',  'supplier', 'supplier', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:19',  '2015-09-21 07:03:19'),
('6ADA2D84-84CB-4FF0-A4E0-3D6719FE5730',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'description',  'text', false,  false,  'ALL',  'description',  'description',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:37',  '2015-09-21 07:02:37'),
('6B20201F-FD40-4F01-A4B7-098B986D5263',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'size', 'text', false,  false,  'ALL',  'size', 'size', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:39',  '2015-09-21 07:02:39'),
('71034FFE-90B5-474D-920B-104E3D2E045E',    '0C2FB096-8A60-4037-BD44-E71590F260A9', 'size', 'text', false,  false,  'ALL',  'size', 'size', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:29',  '2015-09-21 07:02:29'),
('78D198DA-7F79-42EA-AF09-547D5330D7E6',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'color',    'text', false,  false,  'ALL',  'color',    'color',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:40',  '2015-09-21 07:02:40'),
('79BF248C-EEFB-4CB2-9D9D-CD74BCE7A6EC',    '0C2FB096-8A60-4037-BD44-E71590F260A9', 'description',  'text', false,  false,  'ALL',  'description',  'description',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:27',  '2015-09-21 07:02:27'),
('7A4D245F-8FD8-4699-8C24-5683C3CF3AA6',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'content',  'text', false,  false,  'ALL',  'content',  'content',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:38',  '2015-09-21 07:02:38'),
('7BB6295C-D7F2-4C44-9C0C-6FF818D35B50',    'F62BFD4F-2BC1-4632-8174-29B62F807E37', 'classification',   'text', false,  false,  'ALL',  'classification',   'classification',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:18',  '2015-09-21 07:03:18'),
('7D1F28BA-416F-473D-9C99-BC529409835A',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'costPerUnit',  'text', false,  false,  'ALL',  'cost_per_unit',    'cost_per_unit',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:45',  '2015-09-21 07:02:45'),
('7F44BF21-5EB0-4B68-A2FE-8E87266B6FCE',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'consumption',  'text', false,  false,  'ALL',  'consumption',  'consumption',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:43',  '2015-09-21 07:02:43'),
('7F6F8031-27ED-44B3-9683-16DB23E82AAA',    'BE1EC6CE-E0E1-4897-8F71-401DF293479F', 'content',  'text', false,  false,  'ALL',  'content',  'content',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:13',  '2015-09-21 07:03:13'),
('7F84B65D-4FC7-4B5F-B336-FDDBBB40D429',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'consumption',  'text', false,  false,  'ALL',  'consumption',  'consumption',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:56',  '2015-09-21 07:02:56'),
('854C2A93-3A1B-4C70-9D27-55E68A6019C5',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'placement',    'text', false,  false,  'ALL',  'placement',    'placement',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:42',  '2015-09-21 07:02:42'),
('8B731BE3-AF84-45B5-9B11-FFF9761EB10F',    'F62BFD4F-2BC1-4632-8174-29B62F807E37', 'description',  'text', false,  false,  'ALL',  'description',  'description',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:20',  '2015-09-21 07:03:20'),
('944650D2-2712-45A3-8E29-5E1670E349B2',    'F62BFD4F-2BC1-4632-8174-29B62F807E37', 'placement',    'text', false,  false,  'ALL',  'placement',    'placement',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:22',  '2015-09-21 07:03:22'),
('98C568F2-9CFF-4438-9C87-AA687A19C43C',    'BE1EC6CE-E0E1-4897-8F71-401DF293479F', 'placement',    'text', false,  false,  'ALL',  'placement',    'placement',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:14',  '2015-09-21 07:03:14'),
('9C5B4AC5-9216-4917-A826-82372B8512FC',    'F62BFD4F-2BC1-4632-8174-29B62F807E37', 'specification',    'text', false,  false,  'ALL',  'specification',    'specification',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:21',  '2015-09-21 07:03:21'),
('A0242625-2C6E-414E-BA15-E254A01EF8F4',    '0C2FB096-8A60-4037-BD44-E71590F260A9', 'color',    'text', false,  false,  'ALL',  'color',    'color',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:30',  '2015-09-21 07:02:30'),
('A3F3D95E-384C-4400-9A4D-3409F68F35DE',    '0C2FB096-8A60-4037-BD44-E71590F260A9', 'upload',   'text', false,  false,  'ALL',  'upload',   'upload',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:24',  '2015-09-21 07:02:24'),
('ABA02743-E18D-48B9-BE71-F76E0DEA7A6D',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'content',  'text', false,  false,  'ALL',  'content',  'content',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:50',  '2015-09-21 07:02:50'),
('AE94120B-9FB8-4B1F-9220-D6BBC588B9B6',    '842AE648-92AE-456E-9AE0-8088619CA87D', 'cost', 'text', false,  false,  'ALL',  'cost', 'cost', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:08',  '2015-09-21 07:03:08'),
('B39CA2EA-8119-4FD3-839C-246A9395FED3',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'colorCombo',   'text', false,  false,  'ALL',  'color_combo',  'color_combo',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:41',  '2015-09-21 07:02:41'),
('B40EF522-6755-4A3C-9069-CEDCE79D17E6',    '842AE648-92AE-456E-9AE0-8088619CA87D', 'classification',   'text', false,  false,  'ALL',  'classification',   'classification',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:59',  '2015-09-21 07:02:59'),
('B7849A82-0A08-40FA-9F64-ECDD44544EDC',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'supplier', 'text', false,  false,  'ALL',  'supplier', 'supplier', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:36',  '2015-09-21 07:02:36'),
('B89F57A9-74CF-4E7E-BC84-77A19BCCE96B',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'colorCombo',   'text', false,  false,  'ALL',  'colorCombo',   'color_combo',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:54',  '2015-09-21 07:02:54'),
('C40A559F-B0C4-4D1E-82FA-EFF438AB3F3D',    'BE1EC6CE-E0E1-4897-8F71-401DF293479F', 'upload',   'text', false,  false,  'ALL',  'upload',   'upload',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:09',  '2015-09-21 07:03:09'),
('C55929E5-B3F0-4EF4-9056-7BD7ADAFF64B',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'weight',   'text', false,  false,  'ALL',  'weight',   'weight',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:51',  '2015-09-21 07:02:51'),
('C55E0B8A-5320-436B-84A0-C9BB2A8AA036',    '4F8BBADD-0377-473E-99FC-038E87F7C7F9', 'color',    'text', false,  false,  'ALL',  'color',    'color',    true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:53',  '2015-09-21 07:02:53'),
('CF99656F-9E7B-4CA1-B08D-C64C10B3F9C1',    '0C2FB096-8A60-4037-BD44-E71590F260A9', 'cost', 'text', false,  false,  'ALL',  'cost', 'cost', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:33',  '2015-09-21 07:02:33'),
('DF749A47-42B4-47DC-BFDD-2F76F95C30E8',    '0C2FB096-8A60-4037-BD44-E71590F260A9', 'supplier', 'text', false,  false,  'ALL',  'supplier', 'supplier', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:26',  '2015-09-21 07:02:26'),
('E20AA58C-32EB-4156-8996-7D7D54E36CFF',    '457DB47A-9E76-4305-8B50-ABB01AE1F9D7', 'upload',   'text', false,  false,  'ALL',  'upload',   'upload',   true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:02:34',  '2015-09-21 07:02:34'),
('F0CB525E-6C75-49E0-813A-FCCD8605BB52',    'BE1EC6CE-E0E1-4897-8F71-401DF293479F', 'supplier', 'text', false,  false,  'ALL',  'supplier', 'supplier', true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:11',  '2015-09-21 07:03:11'),
('FB4D9F71-6C1E-45EC-AAA4-DEBD2B57A73B',    '842AE648-92AE-456E-9AE0-8088619CA87D', 'content',  'text', false,  false,  'ALL',  'content',  'content',  true,  0,  'active',   NULL,   NULL,   '2015-09-21 07:03:03',  '2015-09-21 07:03:03');");
    }
}

class TechpackSchemaGenerate extends Seeder
{
    private $bus;

    public function __construct(DefaultCommandBus $bus)
    {
        $this->bus = $bus;
    }

    public function run()
    {
        $user = [
            'email' => 'sedev@sourceeasy.com',
            'password' => 'se12345',
        ];
        \Auth::attempt($user);
        $items = $this->bus->execute(new \Platform\Techpacks\Commands\GenerateTechpackSchemaCommand());
    }
}

class TaskTagTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('task_tags')->delete();

        DB::insert("INSERT INTO task_tags (id, title,created_at, updated_at) VALUES
         ('0C2FB096-8S60-4037-BD44-E81590F260A0', 'Kishan', now(), now()),
         ('0D3FC096-8A60-4037-DD44-E31590F260A1', 'Chirag', now(), now()),
         ('0E4FF096-8Q60-4037-RD44-E41590F260A2', 'Himan', now(), now()),
         ('0F5FE096-8R60-4037-GD44-E51590F260A3', 'Vishnu', now(), now()),
         ('0G6FG096-8Y60-4037-AD44-E11590F260A4', 'Ankur', now(), now()),
         ('0H7FD096-8G60-4037-RD44-E21590F260A5', 'Probir', now(), now()),
         ('0I8FC096-8L60-4037-RD44-E91590F260A5', 'Tarun', now(), now()),
         ('0J9FH096-8K60-4037-WD44-E31590F260A5', 'Venkatesh', now(), now());");
    }
}

class TaskCategoryTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('task_categories')->delete();

        DB::insert("INSERT INTO task_categories (id, title,created_at, updated_at) VALUES
         ('0C2FB096-8S60-4037-BD44-E81590F260A0', 'Sales', now(), now()),
         ('0D3FC096-8A60-4037-DD44-E31590F260A1', 'Samples', now(), now()),
         ('0E4FF096-8Q60-4037-RD44-E41590F260A2', 'Production', now(), now()),
         ('0F5FE096-8R60-4037-GD44-E51590F260A3', 'Development', now(), now()),
         ('0G6FG096-8Y60-4037-AD44-E11590F260A4', 'Customers', now(), now()),
         ('0H7FD096-8G60-4037-RD44-E21590F260A5', 'Operations', now(), now()),
         ('0I8FC096-8L60-4037-RD44-E91590F260A6', 'Marketing', now(), now()),
         ('0I8FC096-8L60-4037-RD44-E91590F260A7', 'Costing', now(), now()),
         ('0I8FC096-8L60-4037-RD44-E91590F260A8', 'Meeting', now(), now()),
         ('0I8FC096-8L60-4036-RE44-E91590F260A9', 'Report', now(), now()),
         ('0I8FC096-8L60-4039-RD44-E91590F260B5', 'Techpack', now(), now()),
         ('0I8FC096-8L60-4037-RD44-E91590F260B8', 'Technical', now(), now()),
         ('0I8FC096-8L60-4027-RD44-E91590F260F0', 'Bug', now(), now()),
         ('0I8FC096-8L60-4017-RD44-E91590F260A5', 'Coding', now(), now()),
         ('0I8FC096-8L60-4030-RD44-E91590F260A5', 'Others', now(), now());");
    }
}
