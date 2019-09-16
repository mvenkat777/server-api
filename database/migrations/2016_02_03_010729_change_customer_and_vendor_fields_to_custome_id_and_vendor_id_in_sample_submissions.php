<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCustomerAndVendorFieldsToCustomeIdAndVendorIdInSampleSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('sample_submissions', 'customer'))
            {
                $samples = \App\SampleSubmission::all();

                foreach ($samples as $sample) {
                    $customerName = $sample->customer;
                    $customer = \App\Customer::where('name', $customerName)
                                               ->orWhere('id', $customerName)
                                               ->first();
                    if ($customer) {
                        $sample->customer = $customer->id;
                        $sample->save();
                    } else {
                        $sample->customer = null;
                        $sample->save();
                    }
                }

                $table->renameColumn('customer', 'customer_id');
                // $table->string('customer_id', 100)->change();

                $table->foreign('customer_id')
                      ->references('id')
                      ->on('customers')
                      ->onDelete('cascade');
            }
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_submissions', function (Blueprint $table) {
            $samples = \App\SampleSubmission::all();

            foreach ($samples as $sample) {
                $customerId = $sample->customer_id;
                $customer = \App\Customer::find($customerId);

                if ($customer) {
                    $sample->customer_id = $customer->name;
                    $sample->save();
                } else {
                    $sample->customer_id = 'NA';
                    $sample->save();
                }
            }

            $table->rename('customer_id', 'customer');
            $table->string('customer', 70);

        });
    }
}
