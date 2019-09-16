<?php namespace Platform\Techpacks\Commands;

/**
 * Class UpdateTechpack
 * @package App\Commands\Techpack
 */
class UpdateTechpackCommand
{

    public $id;
    /**
     * @var
     */
    public $version;

    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $style_code;
    /**
     * @var
     */
    public $category;
    /**
     * @var
     */
    public $season;
    /**
     * @var
     */
    public $stage;
    /**
     * @var
     */
    public $visibility;
    /**
     * @var
     */
    public $image;
    /**
     * @var
     */
    public $revision;
    /**
     * @var
     */
    public $meta;
    public $collection;

    /**
     * @var
     */
    public $bill_of_materials;

    /**
     * @var
     */
    public $poms;

    /**
     * @var
     */
    public $color_sets;
    /**
     * @var
     */
    public $spec_sheets;
    /**
     * @var
     */
    public $sketches;

    public $product_type;

    public $size_type;

    public $cut_tickets;

    public $changeLogs;

    /**
     * @param array $data
     * @param string $id
     */
    function __construct($data, $id)
    {
        $meta = $data['meta'];
        $meta['revision'] = $meta['revision']+1;
        $this->id = $id;
        $this->version = $data['version'];
        $this->name = $meta['name'];
        $this->customer_id = isset($meta['customer']['customerId']) ? $meta['customer']['customerId'] : null;
        $this->style_code = $meta['styleCode'];
        $this->category = $meta['category'];
        $this->season = $meta['season'];
        $this->stage = $meta['stage'];
        $this->visibility = $meta['visibility'];
        $this->image = $meta['image'];
        $this->revision = $meta['revision'];
        $this->collection = $meta['collection'];
        $this->state = $meta['state'];
        $this->is_published = $meta['isPublished'];
        $this->is_builder_techpack = $meta['isBuilderTechpack'];
        $this->meta = $meta;
        $this->bill_of_materials = $data['billOfMaterials'];
        $this->poms = $data['poms'];
        $this->color_sets = $data['colorSets'];
        $this->spec_sheets = $data['specSheets'];
        $this->sketches = $data['sketches'];
        $this->data = $data;
        $this->product = $meta['product'];
        $this->product_type = $meta['productType'];
        $this->size_type = $meta['sizeType'];
        $this->cut_tickets = isset($data['cutTickets']) ? $data['cutTickets'] : [];
        $this->changeLogs = isset($data['changeLog']) ? $data['changeLog'] : [];
    }
}
