<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'display_name' => 'Tester',
        'email' => 'tester@se.com',
        'password' => bcrypt('test'),
        'providers' => '1,',
    ];
});

$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'user_id' => '0E2FF096-8A60-4037-BD44-E71590F260A2',
        'title' => $faker->name,
        'description' => $faker->text,
        'due_date' => $faker->date,
        'priority' => 'highest',
    ];
});

$factory->define(\App\Customer::class, function (Faker\Generator $faker) {
     return [
        'id' => $faker->uuid,
        'name' => $faker->Company,
        'code' => 'CU'.$faker->randomNumber(4),
        'business_entity' => $faker->text,
        'import_export_license' => $faker->uuid,
        'tax_id' => $faker->uuid,
        'vat_sales_tax_reg' => $faker->uuid,
        'company_reg' => $faker->uuid
     ];
});

$factory->define(\App\Vendor::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->Company,
        'code' => 'VE'.$faker->randomNumber(4),
        'business_entity' => $faker->text,
        'import_export_license' => $faker->uuid,
        'tax_id' => $faker->uuid,
        'vat_sales_tax_reg' => $faker->uuid,
        'company_reg' => $faker->uuid,
        'annual_shipped_turnover' => $faker->randomDigit(3),
        'annual_shipped_quantity' => $faker->randomDigit(2)
    ];
});

$factory->define(\App\Address::class, function (Faker\Generator $faker) {
    return [
        'label' => '',
        'line1' => $faker->streetName,
        'line2' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'zip' => (integer)$faker->postcode,
        'country' => $faker->country,
        'air_cargo_port' => $faker->secondaryAddress,
        'sea_cargo_port' => $faker->secondaryAddress,
        'phone' => $faker->phoneNumber,
        'is_primary' => false
    ];
});

$factory->define(\App\Contact::class, function (Faker\Generator $faker) {
    return [
        'label' => '',
        'mobile_number1' => $faker->phoneNumber,
        'mobile_number2' => $faker->phoneNumber,
        'mobile_number3' => $faker->phoneNumber,
        'email1' => $faker->email,
        'email1' => $faker->email,
        'skype_id' => $faker->firstName,
        'designation' => $faker->Name,
        'is_primary' => true
    ];
});

$factory->define(\App\Brand::class, function (Faker\Generator $faker) {
    return [
        'brand' => $faker->Company
    ];
});

$factory->define(\App\CustomerPartner::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->name,
        'role' => $faker->Name
    ];
});

$factory->define(\App\VendorPartner::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->name,
        'role' => $faker->Name
    ];
});

$factory->define(\App\BankDetail::class, function (Faker\Generator $faker) {
    return [
        'name_on_account' => $faker->name,
        'bank_name' => $faker->Name,
        'account_number' => $faker->randomDigit(5).$faker->randomDigit(6),
        'account_type' => 'current',
        'branch_address' => $faker->address,
        'bank_code' => $faker->swiftBicNumber
    ];
});

$factory->define(App\Techpack::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->uuid,
        'version' => 2,
        'name' => 'techpack'.rand(0, 100),
        'style_code' => $faker->uuid,
        'category' => '',
        'season' => $faker->text,
        'stage' => '',
        'product' => '',
        'collection' => $faker->text,
        'state' => 'costing',
        'product_type' => '',
        'size_type' => '',
        'visibility' => true,
        'revision' => 1,
        'is_builder_techpack' => true,
        'is_published' => true,
        'user_id' => '0C2FB096-8A60-4037-BD44-E71590F260A0',
        'meta' => [],
    ];
});
