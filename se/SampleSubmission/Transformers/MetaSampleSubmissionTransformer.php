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

class MetaSampleSubmissionTransformer extends TransformerAbstract
{
    public function transform(SampleSubmission $sample)
    {
        // $author = \App\User::find($sample->user_id)->display_name;
        $creator = \App\User::where('id',$sample->user_id)->select('id','email','display_name')->first()->toArray();
        $customer = \App\Customer::find($sample->customer_id);

        if ($customer) {
            $customer = (new MetaCustomerTransformer())->transform($customer);
        } else {
            $customer = $sample->customer_id;
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
            'author' => $creator['display_name'],
            'creator' => $creator,
            'techpack' => $techpack,
            'styleCode' => (string) $sample->style_code,
            'weight' => (string) $sample->weight,
            'sentDate' => $sample->sent_date != null ? \Carbon\Carbon::parse($sample->sent_date)->toDateString() : null,
            'receivedDate' => $sample->received_date != null ? \Carbon\Carbon::parse($sample->received_date)->toDateString() : null,
            'vendor' => $sample->vendor,
            'customer' => $customer,
            'type' => $sample->type,
        ];

        return $response;
    }
}
