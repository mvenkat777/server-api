<?php

namespace Platform\Line\Transformers;

use League\Fractal\Manager;
use League\Fractal\TransformerAbstract;
use App\User;
use App\Customer;
use Platform\Customer\Transformers\MetaCustomerTransformer;
use Platform\Users\Transformers\MetaUserTransformer;
use League\Fractal\Resource\Collection;
use Platform\Line\Transformers\StyleTransformer;
use App\Techpack;
use Platform\Techpacks\Transformers\MetaTechpackTransformer;
use App\Style;

class LineTransformer extends TransformerAbstract
{

	public function __construct()
	{
		$this->manager = new Manager();
	}

	public function transform($line)
	{
		if ($line->customer) {
            $customer = (new MetaCustomerTransformer())->transform($line->customer);
		} else {
            $customer = null;
		}

		if ($line->salesRepresentative) {
            $salesRepresentative = (new MetaUserTransformer())->transform($line->salesRepresentative);
		} else {
            $salesRepresentative = null;
		}

		if ($line->productionLead) {
            $productionLead = (new MetaUserTransformer())->transform($line->productionLead);
		} else {
            $productionLead = null;
		}

		if ($line->productDevelopmentLead) {
            $productDevelopmentLead = (new MetaUserTransformer())->transform($line->productDevelopmentLead);
		} else {
            $productDevelopmentLead = null;
		}

		if ($line->merchandiser) {
            $merchandiser = (new MetaUserTransformer())->transform($line->merchandiser);
		} else {
            $merchandiser = null;
		}

        $vlpApproval = $line->VLPAttachmentApprovals()
                            ->select('approval', 'created_at as approvedAt', 'approver_id')
                            ->orderBy('created_at', 'desc')
                            ->first();

		if ($line->styles) {
            $styles = new Collection($line->styles, new StyleTransformer());
            $styles = $this->manager->createData($styles)->toArray()['data'];
		} else {
            $styles = [];
		}

        $usedTechpacks = Style::lists('techpack_id')->toArray();
		$techpacks = Techpack::where('customer_id', $line->customer_id)
                               ->where('deleted_at', null)
				               ->get(['id', 'name', 'style_code as styleCode']);
        foreach ($techpacks as $techpack) {
            if (in_array($techpack->id, $usedTechpacks)) {
                $techpack->used = true;
            } else {
                $techpack->used = false;
            }
        }

		return [
            'id' => $line->id,
            'customer' => $customer,
            'techpacks' => $techpacks,
            'code' => $line->code,
            'name' => $line->name,
            'salesRepresentative' => $salesRepresentative,
            'productionLead' => $productionLead,
            'productDevelopmentLead' => $productDevelopmentLead,
            'merchandiser' => $merchandiser,
            'soTargetDate' => $line->so_target_date->toDateTimeString(),
            'deliveryTargetDate' => $line->delivery_target_date->toDateTimeString(),
            'createdAt' => $line->created_at->toDateTimeString(),
            'updatedAt' => $line->updated_at->toDateTimeString(),
            'styles' => $styles,
            'targetCustomer' => $line->targetCustomer,
            'fitReference' => $line->fitReference,
            'category' => $line->category,
            'stylesCount' => intval($line->styles_count),
            'vlpAttachments' => $line->vlp_attachments,
            'vlpApproval' => $vlpApproval,
            'archivedAt' => is_null($line->archived_at)? NULL : $line->archived_at->toDateTimeString(),
            'completedAt' => is_null($line->completed_at)? NULL : $line->completed_at->toDateTimeString(),
		];
	}
    

}
