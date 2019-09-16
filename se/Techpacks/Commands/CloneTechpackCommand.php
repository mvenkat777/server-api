<?php

namespace Platform\Techpacks\Commands;

use Platform\App\Helpers\Helpers;

class CloneTechpackCommand
{
    /**
     * @var string
     *
     */
	public $techpackId;

    /**
     * @var string
     *
     */
    public $userId;


    /**
     * @var string
     */
	public $name;

    /**
     * @var string
     */
    public $customerId;

    /**
     * @var string
     */
    public $category;

    /**
     * @var string
     */
    public $product;

    /**
     * @var string
     */
    public $sizeType;

    /**
     * @var string
     */
    public $season;

    /**
     * @param string $techpackId
     * @param array $data
     */
	public function __construct($techpackId, $data)
	{
		$this->techpackId = $techpackId;
        $this->userId = Helpers::getAuthUserId();
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->customerId = isset($data['customerId']) ? $data['customerId'] : null;
        $this->category = isset($data['category']) ? $data['category'] : null;
        $this->product = isset($data['product']) ? $data['product'] : null;
        $this->sizeType = isset($data['sizeType']) ? $data['sizeType'] : null;
        $this->season = isset($data['season']) ? $data['season'] : null;
	}
}
