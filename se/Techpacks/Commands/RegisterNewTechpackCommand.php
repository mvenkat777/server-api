<?php

namespace Platform\Techpacks\Commands;

class RegisterNewTechpackCommand
{
    public $version;
    public $name;
    public $style_code;
    public $category;
    public $season;
    public $stage;
    public $visibility;
    public $image;
    public $revision;
    public $meta;
    public $collection;
    public $bill_of_materials;
    public $poms;
    public $color_sets;
    public $spec_sheets;
    public $sketches;
    public $data;
    public $product;
    public $product_type;
    public $size_type;

    /**
     * @param $version
     * @param $meta
     * @param $bill_of_measurements
     * @param $color_sets
     * @param $spec_sheets
     * @param $sketches
     */
    public function __construct($data)
    {
        $meta = $data['meta'];
        $meta['stage'] = 'draft';
        $this->version = $data['version'];
        $this->name = $meta['name'];
		$this->customer_id = isset($meta['customer']['customerId']) ? $meta['customer']['customerId'] : null;
        $this->category = $meta['category'];
        $this->season = $meta['season'];
        $this->stage = $meta['stage'];
        $this->visibility = $meta['visibility'];
        $this->image = $meta['image'];
        $this->revision = $meta['revision'];
        $this->collection = isset($meta['collection']) ? $meta['collection'] : null;
        $this->state = isset($meta['state']) ? $meta['state'] : null;
        $this->is_published = isset($meta['isPublished']) ? $meta['isPublished'] : null;
        $this->is_builder_techpack = isset($meta['isBuilderTechpack']) ? $meta['isBuilderTechpack'] : null;
        $this->meta = $meta;
        $this->bill_of_materials = $data['billOfMaterials'];
        $this->poms = $data['poms'];
        $this->color_sets = $data['colorSets'];
        $this->spec_sheets = $data['specSheets'];
        $this->sketches = $data['sketches'];
        $this->data = $data;
        $this->product = isset($meta['product']) ? $meta['product'] : null;
        $this->product_type = isset($meta['productType']) ? $meta['productType'] : null;
        $this->size_type = isset($meta['sizeType']) ? $meta['sizeType'] : null;
    }
}
