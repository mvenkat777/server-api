<?php
namespace Platform\Observers\TNA;

use Platform\App\Activity\ActivityObserver;
use Platform\Observers\Notifiers\TNAActivityNotifier;

class TNAObserver extends ActivityObserver
{
	/**
     * @param array $model
     * @return mixed
     */
	public function created($model){
			$verb = 'created';
			$object = [
				'objectType' => 'created',
				'tna' => (new \Platform\TNA\Transformers\MetaTNATransformer)->transform($model)
			];
			$this->setActivityVerb($verb)->setObject($object)->create($model, 'tna');
			return "Successfully Added To Mongo DB.";
	}

	/**
     * @param array $model
     * @return mixed
     */
	public function updated($model){
		try{
				$transformedData = ((new \Platform\TNA\Transformers\MetaTNATransformer)->transform($model));
				$collection = [ 'updated_at', 'created_at' ];
				foreach ($model->getDirty() as $key => $value) {
					if (!in_array($key, $collection)) {
						if(array_key_exists($key, $this->checkArrayExists())){
					    	if(array_key_exists($this->checkArrayExists()[$key],$transformedData)){
					    		$object = [
									'objectType' => $this->checkArrayExists()[$key],
									'tna' => (new \Platform\TNA\Transformers\MetaTNATransformer)->transform($model)
								];
								$this->setActivityVerb('updated')->setObject($object)->update($model, 'tna');
					    	}
					    }
					}
				}
		return "Successfully Added To Mongo DB.";
		} catch(\Exception $e){
			return;
		}
	}

	/**
     * @param array $model
     * @return mixed
     */
	public function deleted($model){
		/*foreach ($model->getDirty() as $key => $value) {
			$object = [
					'objectType' => 'deleted',
					'tna' => (new \Platform\TNA\Transformers\MetaTNATransformer)->transform($model)
				];
				$this->setActivityVerb('deleted')->setObject($object)->delete($model, 'tna');
			}
        return "Successfully Added To Mongo DB.";*/
	}

	public function checkArrayExists(){

		$transformed = [
            'id' => 'tnaId',
            'title' => 'title',
            'creator_id' => 'creator',
            'order_id' => 'order',
            'techpack_id' => 'techpack',
            'customer_id' => 'customerId',
            'vendor_id' => 'vendors',
            'tna_type_id' => 'tnaType',
            'target_date' => 'targetDate',
            'is_published' => 'isPublished',
            'tna_state_id' => 'state',
            'customer_name' => 'customerName',
            'customer_code' => 'customerCode',
            'order_quantity' => 'orderQuantity',
            'style_id' => 'styleId',
            'style_range' => 'styleRange',
            'style_description' => 'styleDescription',
            'representor_id' => 'representor',
            'start_date' => 'startDate',
            'attachment' => 'attachement',
            'published_date' => 'publishedDate',
            'projected_date' => 'projectedDate',
            'completed_date' =>'completedDate',
            'items_order' => 'itemsOrder',
            'tna_health_id' => 'tnaHealth'
        ];

        return $transformed;
	}
}
