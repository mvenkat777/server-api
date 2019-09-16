<?php

use App\Customer;
use App\ProductCategory;
use App\ProductList;
use App\Techpack;
use Illuminate\Database\Seeder;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Helpers\Helpers;
use Platform\NamingEngine\Commands\GenerateStyleCodeCommand;

class MigrateLegacyStyleCodesToNewFormat extends Seeder
{
    /**
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = fopen(getcwd() . '/database/seeds/file.csv', 'r');
        $keys = fgetcsv($file);
        while (!feof($file)) {
            $updatedTechpacks[] = array_combine($keys, fgetcsv($file));
        }
        foreach ($updatedTechpacks as $updatedTechpack) {
            $techpack = Techpack::find($updatedTechpack['techpack_id']);
            $techpack->timestamps = false;
            $techpack->category = Helpers::toSnakecase($updatedTechpack['category']);
            $techpack->product = $updatedTechpack['product'];
            $techpack->update();
        }
        // dd('done');

        $techpacks = Techpack::all();
        foreach ($techpacks as $techpack) {
            $customerId = $techpack->customer_id;
            $customer = Customer::find($customerId);
            if ($customer) {
                $category = ProductCategory::where('category', $techpack->category)->first();
                $product = ProductList::where('product', $techpack->product)->first();
                if (!is_null($category) || !is_null($product)) {
                    $techpack->timestamps = false;
                    $styleCode = $this->commandBus->execute(
                        new GenerateStyleCodeCommand($customer->code, $techpack->category, $techpack->product)
                    );
                    $meta = new  \stdClass;
                    $meta = $techpack->meta;
                    $meta->styleCode = $styleCode;
                    $meta->customerStyleCode = $techpack->style_code;
                    $techpack->meta = $meta;
                    $techpack->style_code = $styleCode;
                    $techpack->update();

                }
            }
        }
        dd("Migration done.");
    }
}
