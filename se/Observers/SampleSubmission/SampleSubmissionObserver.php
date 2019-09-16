<?php
namespace Platform\Observers\SampleSubmission;

use Platform\App\Activity\ActivityObserver;

class SampleSubmissionObserver extends ActivityObserver
{
    /**
     * @param array $model
     * @return mixed
     */
    public function created($model){
            $verb = 'created';
            $object = [
                'objectType' => 'created',
                'sampleSubmission' => (new \Platform\SampleSubmission\Transformers\MetaSampleSubmissionTransformer)->transform($model)
            ];
            $this->setActivityVerb($verb)->setObject($object)->create($model, 'sampleSubmission');
            return "Successfully Added To Mongo DB.";
    }

    /**
     * @param array $model
     * @return mixed
     */
    public function updated($model){
        $transformedData = ((new \Platform\SampleSubmission\Transformers\SampleSubmissionTransformer)->transform($model));
        $collection = [ 'updated_at', 'created_at' ];
        foreach ($model->getDirty() as $key => $value) {
            if (!in_array($key, $collection)) {
                if(array_key_exists($key, $this->checkArrayExists())){
                    if(array_key_exists($this->checkArrayExists()[$key],$transformedData)){
                        $object = [
                            'objectType' => $this->checkArrayExists()[$key],
                            'sampleSubmission' => (new \Platform\SampleSubmission\Transformers\SampleSubmissionTransformer)->transform($model)
                        ];
                        $this->setActivityVerb('updated')->setObject($object)->update($model, 'sampleSubmission');
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
    //     $verb = 'sampleSubmission';
        
    //     foreach ($model->getDirty() as $key => $value) {
    //         $object = [
    //                 'objectType' => 'created',
    //                 'sampleSubmission' => (new \Platform\SampleSubmission\Transformers\MetaSampleSubmissionTransformer)->transform($model)
    //             ];
    //             $this->setActivityVerb('deleted')->setObjectKey($verb)->setObject($object)->delete($model, 'sampleSubmission');
    //         }
    //     return "Successfully Added To Mongo DB.";
    // }

    public function checkArrayExists(){

        $transformed = [
            'id' => 'id',
            'name' => 'name',
            'user_id' => 'userId',
            'style_code' => 'styleCode',
            'sent_date' => 'sentDate',
            'received_date' => 'receivedDate',
            'type' => 'type',
            'content' => 'content',
            'weight' => 'weight',
            'vendor' => 'vendor',
            'customer_id' => 'customer',
            'techpack_id' => 'techpack'
        ];

        return $transformed;
    }
}