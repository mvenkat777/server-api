<?php

namespace Platform\NamingEngine\Handlers\Commands;

use App\Customer;
use App\ProductCategory;
use App\ProductList;
use App\Techpack;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\App\Helpers\Helpers;

class GenerateStyleCodeCommandHandler implements CommandHandler
{
    public function handle($command)
    {
        $customerCode = $command->customerCode;
        $productCategory = $command->productCategory;
        $product = $command->product;

        $customer = Customer::where('code', $customerCode)->first();
        if (!$customer) {
            throw new SeException("Customer not found.", 404);
        }
        $styleCode = $customerCode . '-';

        $category = ProductCategory::where('category', Helpers::toSnakecase($productCategory))->first();
        if ($category) {
             $categoryCode = $category->code;
        } else {
            $categoryCode = '99';
        }
        $styleCode .= $categoryCode;

        $product = ProductList::where('product', Helpers::toSnakecase($product))->first();
        if ($product) {
             $productCode = $product->code;
        } else {
            $productCode = '999';
        }
        $styleCode .= $productCode;

        return $this->addSuffixAndMakeItUnique($styleCode);
    }

    /**
     * Add 3 letter alphanumeric suffix to line code  and make it unique
     *
     * @param string $lineCode
     * @return string
     */
    private function addSuffixAndMakeItUnique($styleCode)
    {
        $techpack = Techpack::where('style_code', 'ILIKE', $styleCode . '%')
                        ->orderBy('style_code', 'desc')
                        ->first();
        if ($techpack) {
            $code = $techpack->style_code;
            $suffix = intval(substr($code, -3));
            $suffix += 1;
            $suffix = str_pad($suffix, 3, '0', STR_PAD_LEFT);
            $styleCode .= $suffix;
        } else {
            $styleCode .= '001';
        }

        return $styleCode;
    }
}
