<?php

namespace Platform\Pom\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Pom\Repositories\Contracts\PomRepository;
use Platform\Pom\Repositories\Contracts\PomSheetRepository;
use Platform\Pom\Repositories\Contracts\ProductCategoryRepository;
use Platform\Pom\Repositories\Contracts\ProductListRepository;
use Platform\Pom\Repositories\Contracts\SizeTypeRepository;

class CheckPomCommandHandler implements CommandHandler
{
    /**
     * $customerRepository 
     * @var object
     */
    private $customerRepository;

    /**
     * $partnerRepository 
     * @var object
     */
    private $partnerRepository;

    /**
     * @param CustomerRepository
     */
    public function __construct(
    	PomRepository $pomRepo,
    	ProductCategoryRepository $categoryRepo,
    	SizeTypeRepository $sizeTypeRepo,
    	ProductListRepository $productRepo,
		PomSheetRepository $sheetRepo)
    {
        $this->pomRepo = $pomRepo;
        $this->categoryRepo = $categoryRepo;
        $this->sizeTypeRepo = $sizeTypeRepo;
        $this->productRepo = $productRepo;
        $this->sheetRepo = $sheetRepo;
    }

    /**
     * @param  DeleteCustomerPartnerCommand
     * @return mixed
     */
    public function handle($command)
    {
        $category = $this->categoryRepo->getCategoryByName($command->category);
        $product = $this->productRepo->getProductByName($command->product);
        $sizeType = $this->sizeTypeRepo->getSizeTypeByName($command->sizeType);
        if (!isset($category->code) || !isset($product->product_type_code) || !isset($sizeType->id)) {
            return ['isPomExists' => false];
        }
        $pom = $this->pomRepo->getByRelatedCodes($category->code, $product->product_type_code, $sizeType->id);
        if ($pom) {
            return ['isPomExists' => true];
        }
        return ['isPomExists' => false];
        
    }
}