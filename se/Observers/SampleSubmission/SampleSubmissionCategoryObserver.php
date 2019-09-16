<?php
namespace Platform\Observers\SampleSubmission;

use Platform\App\Activity\ActivityObserver;

class SampleSubmissionCategoryObserver extends ActivityObserver
{
    /**
     * @param array $model
     * @return mixed
     */
    public function created($model){
            $verb = 'category created';
            $object = [
                'objectType' => 'category created',
                'sampleSubmissionCategory' => (new \Platform\SampleSubmission\Transformers\SampleSubmissionCategoryTransformer)->transform($model)
            ];
            $this->setActivityVerb($verb)->setObject($object)->create($model, 'sampleSubmissionCategory');
            return "Successfully Added To Mongo DB.";
    }

    /**
     * @param array $model
     * @return mixed
     */
    public function updated($model){
        $transformedData = ((new \Platform\SampleSubmission\Transformers\SampleSubmissionCategoryTransformer)->transform($model));
        $collection = [ 'updated_at', 'created_at' ];
        foreach ($model->getDirty() as $key => $value) {
            if (!in_array($key, $collection)) {
                if(array_key_exists($key, $this->checkArrayExists())){
                    if(array_key_exists($this->checkArrayExists()[$key],$transformedData)){
                        $object = [
                            'objectType' => $this->checkArrayExists()[$key],
                            'sampleSubmissionCategory' => (new \Platform\SampleSubmission\Transformers\SampleSubmissionCategoryTransformer)->transform($model)
                        ];
                        $this->setActivityVerb('updated')->setObject($object)->update($model, 'sampleSubmissionCategory');
                    }
                }
            }
        }
        return "Successfully Added To Mongo DB.";
    }

    /**
     * @param array $model
     * @return mixed
     */
    // public function deleted($model){
    //     $verb = 'sampleSubmissionCategory';
        
    //     foreach ($model->getDirty() as $key => $value) {
    //         $object = [
    //                 'objectType' => 'created',
    //                 'sampleSubmissionCategory' => (new \Platform\SampleSubmission\Transformers\SampleSubmissionCategoryTransformer)->transform($model)
    //             ];
    //             $this->setActivityVerb('deleted')->setObjectKey($verb)->setObject($object)->delete($model, 'sampleSubmissionCategory');
    //         }
    //     return "Successfully Added To Mongo DB.";
    // }

    public function checkArrayExists(){

        $transformed = [
            'id' => 'id',
            'name' => 'name',
            'content' => 'content'
        ];

        return $transformed;
    }
}
