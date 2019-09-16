<?php

namespace Platform\Line\Transformers;

use App\StyleReview;
use League\Fractal\TransformerAbstract;

class StyleReviewTransformer extends TransformerAbstract
{
    public function transform(StyleReview $review)
    {
        if($review->pivot){
            $revw = [
                'id' => $review->id,
                'name' => (string)$review->name,
                'isParallel' => $review->is_parallel,
                'owner' => json_decode($review->pivot['owner']),
                'isApproved' => $review->pivot['is_approved'],
                'approvedAt' => $review->pivot['approved_at'],
                'approvedBy' => json_decode($review->pivot['approved_by']),
                'unapprovedAt' => $review->pivot['unapproved_at'],
                'unapprovedBy' => json_decode($review->pivot['unapproved_by']),
                'isEnabled' => $review->pivot['is_enabled'],
                // 'isEditable' => \Auth::user()->email == json_decode($development->pivot['owner'])->email
            ];
        }
        else{
            $revw = [
               'id' => $review->id,
               'name' => (string)$review->name
            ];
        }
        return $revw;
    }
}