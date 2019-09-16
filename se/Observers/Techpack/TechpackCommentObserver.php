<?php
namespace Platform\Observers\Techpack;

use Platform\App\Activity\ActivityObserver;
// use Platform\Observers\Notifiers\techpackActivityNotifier;

class TechpackCommentObserver extends ActivityObserver
{
	/**
     * @param array $model
     * @return mixed
     */
	public function created($model){
			$verb = 'commented';
			$object = [
				'objectType' => 'commented',
				'techpack' => (new \Platform\Techpacks\Transformers\TechpackCommentTransformer)->transform($model)
			];
			$this->setActivityVerb($verb)->setObject($object)->create($model, 'techpack');
			return "Successfully Added To Mongo DB.";
	}

	// /**
 //     * @param array $model
 //     * @return mixed
 //     */
	// public function updated($model){
	// 	$transformedData = ((new \Platform\Techpacks\Transformers\TechpackTransformer)->transform($model));
	// 	$collection = [ 'updated_at', 'created_at' ];
	// 	foreach ($model->getDirty() as $key => $value) {
	// 		if (!in_array($key, $collection)) {
	// 		    if(array_key_exists($key, $this->checkArrayExists())){
	// 		    	if(array_key_exists($this->checkArrayExists()[$key],$transformedData)){
	// 		    		$object = [
	// 						'objectType' => $this->checkArrayExists()[$key],
	// 						'techpack' => (new \Platform\Techpacks\Transformers\TechpackTransformer)->transform($model)
	// 					];
	// 					$this->setActivityVerb('updated')->setObject($object)->update($model, 'techpack');
	// 		    	}
	// 		    }
	// 		}
	// 	}
	// 	return "Successfully Added To Mongo DB.";
	// }

	// *
 //     * @param array $model
 //     * @return mixed
     
	// public function deleted($model){
	// 	$verb = 'techpack';
		
	// 	foreach ($model->getDirty() as $key => $value) {
	// 		$object = [
	// 				'objectType' => 'created',
	// 				'techpack' => (new \Platform\Techpacks\Transformers\TechpackTransformer)->transform($model)
	// 			];
	// 			$this->setActivityVerb('deleted')->setObjectKey($verb)->setObject($object)->delete($model, 'techpack');
	// 		}
	// 	return "Successfully Added To Mongo DB.";
	// }

	// public function techpackReject($model) {
	// 	$verb = 'rejected';
	// 	$object = [
	// 		'objectType' => 'rejected techpack',
	// 		'techpack' => (new \Platform\Techpacks\Transformers\TechpackTransformer)->transform($model)
	// 	];
	// 	$object['techpack']['note'] = $model->note;
	// 	$this->setActivityVerb($verb)->setObject($object)->create($model);
	// 	return "Successfully Added To Mongo DB.";
	// }

	public function checkArrayExists(){

		$transformed = [
            'id' => 'id',
            'version' => 'version',
            'name' => 'name',
            'style_code' => 'styleCode',
            'category' => 'category',
            'season' => 'season',
            'stage' => 'stage',
            'revision' => 'revision',
            'visibility' => 'visibility',
          	'image' => 'image',
          	'state' => 'status',
          	'is_published' => 'status',
          	'is_builder_techpack' => 'status',
          	'meta' => 'meta',
          	'bill_of_materials' => 'billOfMaterials',
          	'spec_sheets' => 'specSheets',
          	'color_sets' => 'colorSets',
          	'sketches' => 'sketches',
          	'parent_id' => 'parentId',
          	'user_id' => 'userId',
          	'poms' => 'poms'
        ];

        return $transformed;
	}
}