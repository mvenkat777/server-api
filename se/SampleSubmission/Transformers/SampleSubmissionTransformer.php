<?php

namespace Platform\SampleSubmission\Transformers;

use App\SampleSubmission;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Customer\Transformers\MetaCustomerTransformer;
use Platform\SampleSubmission\Transformers\SampleSubmissionCategoryTransformer;
use Platform\Techpacks\Transformers\MetaTechpackTransformer;
use Platform\Vendor\Transformers\MetaVendorTransformer;

class SampleSubmissionTransformer extends TransformerAbstract
{
    public function transform(SampleSubmission $sample)
    {
        $fractal = new Manager();
        $categories = new Collection($sample['categories'], new SampleSubmissionCategoryTransformer());
        $categories = $fractal->createData($categories)->toArray()['data'];

        $author = \App\User::find($sample->user_id)->display_name;

        $customer = \App\Customer::find($sample->customer_id);

        if ($customer) {
            $customer = (new MetaCustomerTransformer())->transform($customer);
        } else {
            $customer = $sample->customer;
        }

        $techpack = \App\Techpack::where('id', $sample->techpack_id)
                                   ->first(['id', 'name']);

        if ($techpack) {
            $techpack = (new MetaTechpackTransformer())->transform($techpack);
        } else {
            $techpack = $sample->techpack;
        }

        $response = [
            'id' => (string) $sample->id,
            'name' => (string) $sample->name,
            'userId' => (string) $sample->user_id,
            'techpack' => $techpack,
            'author' => $author,
            'styleCode' => (string) $sample->style_code,
            'sentDate' => $sample->sent_date != null ? \Carbon\Carbon::parse($sample->sent_date)->toDateString() : null,
            'receivedDate' => $sample->received_date != null ? \Carbon\Carbon::parse($sample->received_date)->toDateString() : null,
            'type' => (string) $sample->type,
            'content' => (string) $sample->content,
            'weight' => (string) $sample->weight,
            'vendor' => $sample->vendor,
            'customer' => $customer,
            'categories' => $categories,
            'createdAt' => $sample->created_at->toDateTimeString(),
            'updatedAt' => $sample->updated_at->toDateTimeString(),
        ];

        return $response;
    }
}
