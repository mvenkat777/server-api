<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Platform\Tasks\Repositories\Contracts\TaskRepository;
use Platform\Line\Repositories\Contracts\LineRepository;
use Platform\Line\Repositories\Contracts\StyleRepository;
use Platform\SampleContainer\Repositories\Contracts\SampleRepository;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Carbon\Carbon;

class DailyDigestCommand extends Command implements CommandHandler
{
    public function __construct(TaskRepository $taskRepository,
                                LineRepository $lineRepository, 
                                StyleRepository $styleRepository, 
                                SampleRepository $sampleRepository, 
                                TNARepository $calendarRepository, 
                                DefaultRuleBus $defaultRuleBus){
        $this->taskRepository = $taskRepository;
        $this->lineRepository = $lineRepository;
        $this->styleRepository = $styleRepository;
        $this->sampleRepository = $sampleRepository;
        $this->calendarRepository = $calendarRepository;
        $this->defaultRuleBus = $defaultRuleBus;

        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:digest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For sending daily digest';
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle($command = NULL)
    {
        echo PHP_EOL."We have started working on it. It will take some time. Sit back and relax".PHP_EOL;
        $origin = $this->isPermissionToExecuteCommand();
        $digest = [];
        $styleCreated = $this->styleRepository->getTodayCreatedStyleList();
        $styleArchived = $this->styleRepository->getTodayArchivedStyleList();
        $styleProduction =  \DB::table('style_production_style')->where('is_enabled', true)->where('approved_at', '>', Carbon::today())->get(); 
        $styleDevelopment =  \DB::table('style_development_style')->where('is_enabled', true)->where('approved_at', '>', Carbon::today())->get(); 
        $styleReview =  \DB::table('style_review_style')->where('is_enabled', true)->where('approved_at', '>', Carbon::today())->get(); 
        $styleShipped =  \DB::table('style_shipped_style')->where('is_enabled', true)->where('approved_at', '>', Carbon::today())->get(); 
        foreach ($this->validUser() as $key => $value) {
            $digest['tasks'] = $this->newlyAssignedTask($value);
            $digest['taskSubmitted'] = $this->newlySubmittedTask($value);
            $digest['taskCompleted'] = $this->newlyCompletedTask($value);
            $digest['lineCreated'] = $this->newlyCreatedLine($value);
            $digest['lineArchived'] = $this->newlyArchivedLine($value);
            $digest['styleCreated'] = $this->newlyCreatedStyle($styleCreated, $value);
            $digest['styleArchived'] = $this->newlyArchivedStyle($styleArchived, $value);
            $digest['sampleCreated'] = $this->newlyCreatedSample($value);
            $digest['calendarCreated'] = $this->newlyCreatedCalendar($value);
            $digest['styleApproval'] = $this->newlyApprovedStyle($styleProduction, $styleDevelopment, $styleReview, $styleShipped, $value);
            if($this->verifyWhetherToSendMailOrNot($digest)){
                $subject = "Good Morning ". $value->display_name.", here's your daily digest!";
                $notifiy = (new \Platform\App\RuleCommanding\ExternalNotification\DigestExternalNotificationHandler)->sendDailyDigest($digest, 'emails.digest.digest', $value, $subject);
            }
        }
        echo PHP_EOL."We have sent the digest to the users.".PHP_EOL;

    }

    public function verifyWhetherToSendMailOrNot($data)
    {
        if(count($data['tasks']) || count($data['taskSubmitted']) || count($data['taskCompleted']) || count($data['lineCreated']) || count($data['lineArchived'])
            || count($data['styleCreated']) || count($data['styleArchived']) || count($data['sampleCreated']) || count($data['calendarCreated']) || count($data['styleApproval'])){
            return true;
        } else {
            return false;
        }
    }

    public function newlyApprovedStyle($styleProduction, $styleDevelopment, $styleReview, $styleShipped, $user)
    {
        $data = [];
        if(count($styleProduction)){
            $prod = [];
            foreach ($styleProduction as $key => $value) {
                $actor = json_decode($value->approved_by);
                if($actor->id == $user->id){
                    $frame = [
                        'style' => (new \Platform\Line\Transformers\MetaStyleTransformer)->transform(\App\Style::find($value->style_id)),
                        'styleProduction' => (new \Platform\Line\Transformers\StyleProductionTransformer)->transform(\App\StyleProduction::find($value->style_production_id))
                    ];
                    array_push($prod, $frame);
                }
            }
            if(count($prod))
                $data['styleProduction'] = $prod;
        }
        if(count($styleDevelopment)){
            $dev = [];
            foreach ($styleDevelopment as $key => $value) {
                $actor = json_decode($value->approved_by);
                if($actor->id == $user->id){
                    $frame = [
                        'style' => (new \Platform\Line\Transformers\MetaStyleTransformer)->transform(\App\Style::find($value->style_id)),
                        'styleDevelopment' => (new \Platform\Line\Transformers\StyleDevelopmentTransformer)->transform(\App\StyleDevelopment::find($value->style_development_id))
                    ];
                    array_push($dev, $frame);
                }
            }
            if(count($dev))
                $data['styleDevelopment'] = $dev;
        }
        if(count($styleReview)){
            $rev = [];
            foreach ($styleReview as $key => $value) {
                $actor = json_decode($value->approved_by);
                if($actor->id == $user->id){
                    $frame = [
                        'style' => (new \Platform\Line\Transformers\MetaStyleTransformer)->transform(\App\Style::find($value->style_id)),
                        'styleReview' => (new \Platform\Line\Transformers\StyleReviewTransformer)->transform(\App\StyleReview::find($value->style_review_id))
                    ];
                    array_push($rev, $frame);
                }
            }
            if(count($rev))
                $data['styleReview'] = $rev;
        }
        if(count($styleShipped)){
            $ship = [];
            foreach ($styleShipped as $key => $value) {
                $actor = json_decode($value->approved_by);
                if($actor->id == $user->id){
                    $frame = [
                        'style' => (new \Platform\Line\Transformers\MetaStyleTransformer)->transform(\App\Style::find($value->style_id)),
                        'styleShipped' => (new \Platform\Line\Transformers\StyleShippedTransformer)->transform(\App\StyleShipped::find($value->style_shipped_id))
                    ];
                    array_push($ship, $frame);
                }
            }
            if(count($ship))
                $data['styleShipped'] = $ship;
        }
        return $data;
    }

    public function newlyCreatedCalendar($user)
    {
        $data = [];
        $calendar = $this->calendarRepository->getTodayCreatedTNAList($user->id);
        foreach ($calendar as $key => $value) {
            array_push($data, (new \Platform\TNA\Transformers\MetaTNATransformer)->transform(\Platform\TNA\Models\TNA::find($value['id'])));
        }
        return $data;
    }

    public function newlyCreatedSample($user)
    {
        $arrColl = [];
        $sample = $this->sampleRepository->getTodayCreatedSampleList($user->id);
        foreach ($sample as $key => $value) {
            $frame = [
                'title' => $value['title'],
                'type' => $value['type'],
                'sampleContainer' => (new \Platform\SampleContainer\Transformers\MetaSampleContainerTransformer)->transform(\App\SampleContainer::find($value['sample_container_id'])),
                'sentDate' => $value['sent_date'],
                'receivedDate' => $value['received_date']
            ];
            array_push($arrColl, $frame);
        }
        return $arrColl;
    }

    public function newlyArchivedStyle($styleArchived, $user)
    {
        $arrColl = [];
        foreach ($styleArchived as $key => $value) {
            $data = $this->lineRepository->isUserFoundInLine($value['line_id'], $user->id);
            if(!is_null($data)){
                $frame = [
                    'styleName' => $value['name'],
                    'customerStyleCode' => $value['customer_style_code'],
                    'line' => [
                        'name' => $data->name, 
                        'code' => $data->code,
                        'soTargetDate' => $data->so_target_date,
                        'deliveryTargetDate' => $data->delivery_target_date,
                        'customer' => (new \Platform\Customer\Transformers\MetaCustomerTransformer)->transform(\App\Customer::find($data->customer_id))
                    ]
                ];
                array_push($arrColl, $frame);
            }
        }
        return $arrColl;
    }

    public function newlyCreatedStyle($styleCreated, $user)
    {
        $arrColl = [];
        foreach ($styleCreated as $key => $value) {
            $data = $this->lineRepository->isUserFoundInLine($value['line_id'], $user->id);
            if(!is_null($data)){
                $frame = [
                    'styleName' => $value['name'],
                    'customerStyleCode' => $value['customer_style_code'],
                    'line' => [
                        'name' => $data->name, 
                        'code' => $data->code,
                        'soTargetDate' => $data->so_target_date,
                        'deliveryTargetDate' => $data->delivery_target_date,
                        'customer' => (new \Platform\Customer\Transformers\MetaCustomerTransformer)->transform(\App\Customer::find($data->customer_id))
                    ]
                ];
                array_push($arrColl, $frame);
            }
        }
        return $arrColl;
    }

    public function newlyArchivedLine($user)
    {
        $data = [];
        $line = $this->lineRepository->getTodayArchivedLineList($user->id);
        foreach ($line as $key => $value) {    
            $frame = [
                'name' => $value['name'], 
                'code' => $value['code'],
                'soTargetDate' => $value['so_target_date'],
                'deliveryTargetDate' => $value['delivery_target_date'],
                'customer' => (new \Platform\Customer\Transformers\MetaCustomerTransformer)->transform(\App\Customer::find($value['customer_id'])) 
            ];
            array_push($data, $frame);
        }
        return $data;
    }

    public function newlyCreatedLine($user)
    {
        $data = [];
        $line = $this->lineRepository->getTodayCreatedLineList($user->id);
        foreach ($line as $key => $value) {    
            $frame = [
                'name' => $value['name'], 
                'code' => $value['code'],
                'soTargetDate' => $value['so_target_date'],
                'deliveryTargetDate' => $value['delivery_target_date'],
                'customer' => (new \Platform\Customer\Transformers\MetaCustomerTransformer)->transform(\App\Customer::find($value['customer_id'])) 
            ];
            array_push($data, $frame);
        }
        return $data;
    }

    public function newlyCompletedTask($user)
    {
        $data = [];
        $task = $this->taskRepository->getTodayCompletedTaskList($user->id);
        foreach ($task as $key => $value) {    
            $frame = [
                'title' => $value['title'], 
                'completionDate' => $value['completion_date'], 
                'creator' => $this->getValidUserById('id', $value['creator_id'])
            ];
            array_push($data, $frame);
        }
        return $data;
    }

    public function newlySubmittedTask($user)
    {
        $data = [];
        $task = $this->taskRepository->getTodaySubmittedTaskList($user->id);
        foreach ($task as $key => $value) {    
            $frame = [
                'title' => $value['title'], 
                'submissionDate' => $value['submission_date'], 
                'creator' => $this->getValidUserById('id', $value['creator_id'])
            ];
            array_push($data, $frame);
        }
        return $data;
    }

    public function newlyAssignedTask($user)
    {
        $data = [];
        $task = $this->taskRepository->getTodayAssignedTaskList($user->id);
        foreach ($task as $key => $value) {    
            $frame = [
                'title' => $value['title'], 
                'dueDate' => $value['due_date'], 
                'creator' => $this->getValidUserById('id', $value['creator_id'])
            ];
            array_push($data, $frame);
        }
        return $data;
    }

    public function isPermissionToExecuteCommand()
    {
        if(env('APP_ENV') == 'staging') {
            return 'http://platform.sourc.in';
        } elseif(env('APP_ENV') == 'production') {
            return 'http://platform.sourceeasy.com';
        } else {
            throw new SeException("You don't have permission to run on this command", 401);
            // return 'http://platform.dev';
        }
    }

    public function validUser()
    {
        return \App\User::where('is_active', true)
                            ->where('is_banned', false)
                            ->where('se', true)
                            ->get();
    }

    public function getValidUserById($attribute, $value)
    {
        $user = \App\User::where($attribute, $value)
                        ->where('se', true)
                        ->where('is_banned', false)
                        ->where('is_active', true)
                        ->first();
        return [
            'displayName' => $user->display_name,
            'email' => $user->email,
            'id' => $user->id
        ];
    }
}
    