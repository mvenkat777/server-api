<?php

namespace Platform\Techpacks\Transformers;

use App\Techpack;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

/**
 * Class TechpackTransformer.
 */
class MetaTechpackTransformer extends TransformerAbstract
{
    /**
     * @param Techpack $techpack
     *
     * @return array
     */
    public function transform(Techpack $techpack)
    {
        $response = [
            'id' => (string) $techpack->id,
            'name' => (string) $techpack->name,
        ];

        if (isset($techpack->meta->styleCode)) {
            $response['styleCode'] = (string) $techpack->meta->styleCode;
            $response['category'] = $techpack->meta->category;
            $response['product'] = $techpack->meta->product;
            $response['productType'] = $techpack->meta->productType;
            $response['collection'] = $techpack->meta->collection;
            $response['sizeType'] = $techpack->meta->sizeType;
            $response['season'] = $techpack->meta->season;
            $response['stage'] = $techpack->meta->stage;
            $response['revision'] = $techpack->meta->revision;
            $response['state'] = $techpack->meta->state;
            $response['isEditable'] = (boolean) $this->isEditable($techpack);
        }

        return $response;
    }

    /**
     * Add Techpack Editable permission as per user
     * @param array $techpacks 
     */
    public function isEditable($techpack)
    {
        if (!isset(\Auth::user()->id)) {
            return false;
        }

        $role = \App\Role::where('name', 'Edit Access')->first();
        $userIds = is_null($role)? [] : $role->users->lists('id')->toArray();

        if (empty($userIds)) {
            return (
                $techpack->user_id === \Auth::user()->id || 
                \Auth::user()->is_god === true
            ); 
        }
        return (
            $techpack->user_id === \Auth::user()->id || 
            in_array(\Auth::user()->id, $userIds)  || 
            \Auth::user()->is_god === true
        ); 
    }
}
