<?php
namespace Platform\Observers\SampleSubmission;

use Platform\App\Activity\ActivityObserver;

class SampleSubmissionAttachmentObserver extends ActivityObserver
{
    /**
     * @param array $model
     * @return mixed
     */
    public function created($model){
            $verb = 'created';
            $object = [
                'objectType' => 'created',
                'sampleSubmissionAttachment' => (new \Platform\SampleSubmission\Transformers\SampleSubmissionAttachmentTransformer)->transform($model)
            ];
            $this->setActivityVerb($verb)->setObject($object)->create($model, 'sampleSubmissionAttachment');
            return "Successfully Added To Mongo DB.";
    }

    /**
     * @param array $model
     * @return mixed
     */
    public function updated($model){
        $transformedData = ((new \Platform\SampleSubmission\Transformers\SampleSubmissionAttachmentTransformer)->transform($model));
        $collection = [ 'updated_at', 'created_at' ];
        foreach ($model->getDirty() as $key => $value) {
            if (!in_array($key, $collection)) {
                if(array_key_exists($key, $this->checkArrayExists())){
                    if(array_key_exists($this->checkArrayExists()[$key],$transformedData)){
                        $object = [
                            'objectType' => $this->checkArrayExists()[$key],
                            'sampleSubmissionAttachment' => (new \Platform\SampleSubmission\Transformers\SampleSubmissionAttachmentTransformer)->transform($model)
                        ];
                        $this->setActivityVerb('updated')->setObject($object)->update($model, 'sampleSubmissionAttachment');
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
    //     $verb = 'sampleSubmissionAttachment';
        
    //     foreach ($model->getDirty() as $key => $value) {
    //         $object = [
    //                 'objectType' => 'created',
    //                 'sampleSubmissionAttachment' => (new \Platform\SampleSubmission\Transformers\SampleSubmissionAttachmentTransformer)->transform($model)
    //             ];
    //             $this->setActivityVerb('deleted')->setObjectKey($verb)->setObject($object)->delete($model, 'sampleSubmissionAttachment');
    //         }
    //     return "Successfully Added To Mongo DB.";
    // }

    public function checkArrayExists(){

        $transformed = [
            'id' => 'id',
            'file' => 'file',
            'uploaded_by' => 'uploadedBy',
            'created_at' => 'createdAt'
        ];

        return $transformed;
    }
}