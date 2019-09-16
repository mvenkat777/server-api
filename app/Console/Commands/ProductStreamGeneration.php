<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Platform\App\Activity\Models\ProductStream as ProductStreamModel;
use Platform\Dashboard\ProductStream\ProductStream;
use Vinkla\Pusher\Facades\Pusher;

class ProductStreamGeneration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:productStream';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the product stream every 15 minutes';

    /**
     * @var Platform\Dashboard\ProductStream\ProductStream
     */
    protected $productStream;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ProductStream $productStream)
    {
        parent::__construct();

        $this->productStream = $productStream;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $updatedProductStream = $this->productStream->make();
    }
}
