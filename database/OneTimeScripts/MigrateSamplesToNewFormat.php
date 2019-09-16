<?php

use App\Customer;
use App\SampleContainer;
use App\SampleCriteriaAttachment;
use App\SampleCriteriaComment;
use App\SampleSubmission;
use App\SampleSubmissionAttachment;
use App\SampleSubmissionCategory;
use App\SampleSubmissionComment;
use App\Techpack;
use Illuminate\Database\Seeder;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\SampleContainer\Commands\AddNewSampleCommand;
use Platform\SampleContainer\Commands\AddNewSampleContainerCommand;
use Platform\SampleContainer\Commands\AddNewSampleCriteriaCommand;
use Platform\SampleContainer\Commands\AddSampleCriteriaAttachmentCommand;
use Platform\SampleContainer\Commands\AddSampleCriteriaCommentCommand;
use Rhumsaa\Uuid\Uuid;
use app\User;

class MigrateSamplesToNewFormat extends Seeder
{
    /**
     * Ofcourse, the commandBus.
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * Constructs the seeder
     * @param DefaultCommandBus $commandBus
     */
    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }


    /**
     * Run the database seeds.
     */
    public function run()
    {
        $techpacksWithoutCustomerIds = Techpack::whereNull('customer_id')->get();
        foreach ($techpacksWithoutCustomerIds as $techpack) {

            if (isset($techpack['meta']->customer->name)) {
                $customerName = $techpack['meta']->customer->name;
                $customer = Customer::where('name', $customerName)
                                      ->orWhere('name', 'ILIKE', $customerName . '%')
                                      ->orWhere('code', 'ILIKE', '%' . $customerName)
                                      ->orWhere(
                                        'name',
                                        strtolower(str_replace(" ", "", str_replace("'", "", $customerName))))
                                      ->orWhere('code', $customerName)
                                      ->first();
                if ($customer) {
                    $techpack->customer_id = $customer->id;
                    $techpack->timestamps = false;
                    $techpack->update();
                }
            }
        }

        // Get existing sample techpack ids
        $oldSamples = SampleSubmission::lists('techpack_id')->toArray();
        $oldSamples = array_unique(
            array_filter($oldSamples, function ($sample) {
                return !is_null($sample);
            })
        );

        // Create containers for the techpacks
        $techpacks = Techpack::whereIn('id', $oldSamples)->get();
        foreach ($techpacks as $techpack) {
            $data['techpackId'] = $techpack->id;
            if ($techpack->customer_id != null) {
                $container = $this->commandBus->execute(new AddNewSampleContainerCommand($data));
            }
        }

        // Add samples to the techpacks
        $existingSamples = SampleSubmission::whereNotNull('techpack_id')->get();
        foreach ($existingSamples as $existingSample) {
            $sampleCategories = SampleSubmissionCategory::where('sample_submission_id', $existingSample->id)->get();

            $sampleContainerId = SampleContainer::where('techpack_id', $existingSample->techpack_id)->first()->id;
            $data = [
                'sampleContainerId' => $sampleContainerId,
                'title' => $existingSample->name,
                'type' => $existingSample->type,
                'image' => null,
                'authorId' => null,
                'sentDate' => $existingSample->sent_date,
                'receivedDate' => $existingSample->received_date,
                'vendorId' => null,
                'weightOrQuality' => $existingSample->weight,
                'fabricOrContent' => $existingSample->content,
            ];
            $sample = $this->commandBus->execute(new AddNewSampleCommand($data));
            $sample->author_id = $existingSample->user_id;
            $sample->update();

            foreach ($sampleCategories as $category) {
                $data = [
                    'sampleId' => $sample->id,
                    'criteria' => ($category->name == 'MEASUREMENTS') ? 'measures' : $category->name,
                    'description' => $category->content,
                ];
                $criteria = $this->commandBus->execute(new AddNewSampleCriteriaCommand($data));

                $sampleAttachments = SampleSubmissionAttachment::where('sample_submission_id', $existingSample->id)
                                                                 ->get();
                foreach ($sampleAttachments as $attachment) {
                    $sampleCriteriaAttachment = new SampleCriteriaAttachment();
                    $sampleCriteriaAttachment->id = Uuid::uuid4()->toString();
                    $sampleCriteriaAttachment->sample_criteria_id = $criteria->id;
                    $sampleCriteriaAttachment->file = $attachment->file;
                    $sampleCriteriaAttachment->uploader_id = User::where('email', $attachment->uploaded_by->email)
                                                                   ->first()
                                                                   ->id;
                    $sampleCriteriaAttachment->created_at = $attachment->created_at;
                    $sampleCriteriaAttachment->updated_at = $attachment->updated_at;
                    $sampleCriteriaAttachment->save();

                }

                $sampleComments = SampleSubmissionComment::where('sample_submission_id', $existingSample->id)->get();
                foreach ($sampleComments as $comment) {
                    $sampleCriteriaComment = new SampleCriteriaComment();
                    $sampleCriteriaComment->id = Uuid::uuid4()->toString();
                    $sampleCriteriaComment->sample_criteria_id = $criteria->id;
                    $sampleCriteriaComment->comment = $comment->comment;
                    $sampleCriteriaComment->commenter_id = User::where('email', $comment->commented_by->email)
                                                                   ->first()
                                                                   ->id;
                    $sampleCriteriaComment->created_at = $comment->created_at;
                    $sampleCriteriaComment->updated_at = $comment->updated_at;
                    $sampleCriteriaComment->save();

                }
            }
        }
    }
}