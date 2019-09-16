<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechpacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('techpacks', function (Blueprint $table) {
            $table->string('id', 100);
            $table->integer('version');
            $table->string('name', 200);
            $table->string('style_code', 200)->default('')->nullable();
            $table->enum('category', [
                'women', 'men', 'girls', 'boys', 'babies', 'accessories', 'others', null
            ])->default('others')->nullable();
            $table->string('season', 255)->nullable();
            $table->enum('stage', ['costing', 'proto', 'pp_Sample', 'production', null])->default('design')->nullable();
            $table->enum('product', [
                'sports_bras', 'camisoles', 'shirts', 'jumpsuits', 'skirts', 'long_sleeve_shirts', 'shorts',
                'underwear', 'tanks', 'sweaters', 'outerwear', 'short_sleeve_shirts', 'dresses', 'rompers',
                'bodysuits','overalls', 'coveralls', 'sleepwear', 'hats', 'headbands', 'scarves', 'gloves', 'socks',
                'shoes', 'belts', 'blankets', 'bags', 'umbrellas', 'capris', 'leggings', 'yoga_pants', 'polo_shirts',
                'sweatshirts', 'gym_pants', 'gym_shorts', 'sweatpants', 'shirt_neckbands', 'tank_binding',
                'camisole_straps', 'sweatshirts_neckbands', 'cuffs', 'gym_whorts_waistbands', 'sweatpants_cuffs',
                'dresses_skirts', 'dress_shirts', 'pants', 'swimwear', 'one-pieces', 'top', 'bottom', 'accessories',
                'na', 't-shirts', 'others', null
            ])->default('na')->nullable();
            $table->string('collection', 200)->nullable();
            $table->string('state', 200)->nullable();
            $table->enum('product_type', ['SLEEVELESS', 'SHORT SLEEVE', 'LONG SLEEVE', 'JACKET', 'PANTY', 'SHORT', 'PANT', 'SKIRT', 'SKORT', 'ROMPER', 'JUMPSUIT', 'OVERALLS', 'HAT', 'GLOVES', 'BELT', 'SCARF', 'SOCKS', 'SHOES', 'BAG', 'ONESIES', 'COVERALLS', 'BIB', 'OTHERS', 'NA', null])->default('NA')->nullable();
            $table->enum('size_type', [
                'alpha', 'numeric', 'alphanumeric', 'Alpha', 'Numeric', 'Alphanumeric', null
            ])->default('alpha')->nullable();
            $table->boolean('visibility')->default(false);
            $table->text('image')->nullable();
            $table->integer('revision')->default(0)->nullable();
            $table->json('meta')->nullable();
            $table->json('bill_of_materials')->nullable();
            $table->json('spec_sheets')->nullable();
            $table->json('color_sets')->nullable();
            $table->json('sketches')->nullable();
            $table->text('parent_id')->nullable();
            $table->string('user_id', 100);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->json('data')->nullable();
            $table->boolean('is_locked')->default(false)->nullable();
            $table->boolean('is_owner_approved')->default(true)->nullable();
            $table->boolean('is_se_approved')->default(false)->nullable();
            $table->boolean('is_se_owned')->default(false)->nullable();
            $table->boolean('is_builder_techpack')->default(false)->nullable();
            $table->boolean('is_published')->default(false)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('techpacks');
    }
}
