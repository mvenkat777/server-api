<?php
namespace Platform\Observers\SampleSubmission;

use Platform\App\Activity\ActivityObserver;

class SampleSubmissionCommentObserver extends ActivityObserver
{
    /**
     * @param array $model
     * @return mixed
     */
    public function created($model){
            $verb = 'created';
            $object = [
                'objectType' => 'comment added',
                'sampleSubmissionComment' => (new \Platform\SampleSubmission\Transformers\SampleSubmissionCommentTransformer)->transform($model)
            ];
            $this->setActivityVerb($verb)->setObject($object)->create($model, 'sampleSubmissionComment');
            return "Successfully Added To Mongo DB.";
    }

    /**
     * @param array $model
     * @return mixed
     */
    public function updated($model){
        $transformedData = ((new \Platform\SampleSubmission\Transformers\SampleSubmissionCommentTransformer)->transform($model));
        $collection = [ 'updated_at', 'created_at' ];
        foreach ($model->getDirty() as $key => $value) {
            if (!in_array($key, $collection)) {
                if(array_key_exists($key, $this->checkArrayExists())){
                    if(array_key_exists($this->checkArrayExists()[$key],$transformedData)){
                        $object = [
                            'objectType' => $this->checkArrayExists()[$key],
                            'sampleSubmissionComment' => (new \Platform\SampleSubmission\Transformers\SampleSubmissionCommentTransformer)->transform($model)
                        ];
                        $this->setActivityVerb('updated')->setObject($object)->update($model, 'sampleSubmissionComment');
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
    //     $verb = 'sampleSubmissionComment';
        
    //     foreach ($model->getDirty() as $key => $value) {
    //         $object = [
    //                 'objectType' => 'created',
    //                 'sampleSubmissionComment' => (new \Platform\SampleSubmission\Transformers\SampleSubmissionCommentTransformer)->transform($model)
    //             ];
    //             $this->setActivityVerb('deleted')->setObjectKey($verb)->setObject($object)->delete($model, 'sampleSubmissionComment');
    //         }
    //     return "Successfully Added To Mongo DB.";
    // }

    public function checkArrayExists(){

        $transformed = [
            'id' => 'id',
            'comment' => 'comment',
            'commented_by' => 'commentedBy',
            'created_at' => 'createdAt',
        ];

        return $transformed;
    }
}