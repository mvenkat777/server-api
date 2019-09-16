<?php

namespace Platform\Line\Handlers\Commands;

use Carbon\Carbon;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Line\Repositories\Contracts\LineRepository;
use Platform\Line\Repositories\Contracts\StyleRepository;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;

class ApprovalChecklistCommandHandler implements CommandHandler
{   use DispatchesJobs;
    private $style;
    private $line;
    public function __construct(StyleRepository $style, DefaultRuleBus $defaultRuleBus)
    {
        $this->style = $style;
        $this->defaultRuleBus = $defaultRuleBus;
    }

    public function handle($command)
    {
        \DB::beginTransaction();
        $style = \App\Style::find($command->styleId);
        if(!$style) throw new SeException("Invalid Style", 400, 4000801);
        if ($command->approvalName == 'development') {
            $this->updateDevlopment($command, $style);
        } elseif ($command->approvalName == 'production') {
            $this->updateProduction($command, $style);
        } elseif ($command->approvalName == 'shipped') {
            $this->updateShipped($command, $style);
        } elseif ($command->approvalName == 'review') {
            $this->updateReview($command, $style);
            \DB::commit();
            $style = \App\Style::find($command->styleId);
            $style->command = $command;
            // $this->defaultRuleBus->execute($style, \Auth::user(), 'productBriefReviewApproved');
            // $job = (new DefaultRuleBusJob($style, \Auth::user(), 'productBriefReviewApproved'));
            // $this->dispatch($job);
            return $style;
        } else {
            throw new SeException("Invalid ApprovalType", 400, 4000802);
        }
        $this->enableNextChecklist($command->approvalNameId, $command->approvalName, $command->styleId);
        \DB::commit();
        return \App\Style::find($command->styleId);
    }

    /**
     * @param  array $command
     * @param  string $style
     */
    public function updateDevlopment($command, $style)
    {
        $development = $style->development()
                ->where('style_development_id', $command->approvalNameId)
                ->first();
        // $this->checkOwner(json_decode($development['pivot']['owner'])->email);
        $this->style->approvedChecklist('style_development_style','style_development_id', $command->styleId, $command->approvalNameId);
        $style->setCustomMessage(
            $development->name.' is checked for style '.$style->name
        )->recordCustomActivity(
            $style,
            [
                'Style',
                [],
                true,
                null,
                [
                    'id' => $style->id,
                    'name' => $style->name,
                ]
            ],
            'approve'
        );
    }

    /**
     * @param  array $command
     * @param  string $style
     */
    public function updateProduction($command, $style)
    {
        $production = $style->production()
                ->where('style_production_id', $command->approvalNameId)
                ->first();
        // $this->checkOwner(json_decode($production['pivot']['owner'])->email);
        $this->style->approvedCheckList('style_production_style', 'style_production_id', $command->styleId, $command->approvalNameId);
        $style->setCustomMessage($production->name.' is checked for style '.$style->name)->recordCustomActivity($style, ['Checklist',[],true, null, [
                    'id' => $style->id,
                    'name' => $style->name,
                ]],'approve');
    }

    /**
     * @param  array $command
     * @param  string $style
     */
    public function updateShipped($command, $style)
    {
        $shipped = $style->shipped()
                ->where('style_shipped_id', $command->approvalNameId)
                ->first();
        // $this->checkOwner(json_decode($shipped['pivot']['owner'])->email);
        $this->style->approvedChecklist('style_shipped_style', 'style_shipped_id', $command->styleId, $command->approvalNameId);
        $style->setCustomMessage($shipped->name.' is checked for style '.$style->name)->recordCustomActivity($style, ['Checklist',[],true, null,[
                    'id' => $style->id,
                    'name' => $style->name,
                ]],'approve');
    }

    /**
     * @param  array $command
     * @param  string $style
     */
    public function updateReview($command, $style)
    {
        $review = $style->review()
                ->where('style_review_id', $command->approvalNameId)
                ->first();
        $this->style->approvedChecklist('style_review_style', 'style_review_id', $command->styleId, $command->approvalNameId);
        $style->setCustomMessage($review->name.' is checked for style '.$style->name)->recordCustomActivity($style, ['Checklist',[],true, null,[
                    'id' => $style->id,
                    'name' => $style->name,
                ]],'approve');
    }

    /**
     * @param  string $email
     */
    public function checkOwner($email)
    {
        if ($email == \Auth::user()->email) {
            return true;
        } else {
            throw new SeException("Yow are not Owner Of this Checklist", 400, 400803);
        }
    }

    /**
     * @param  integer $checkListId
     * @param  string $phase
     * @param  string $styleId
     */
    public function enableNextChecklist($checkListId, $phase, $styleId)
    {
        $className = '\App\Style'.ucfirst($phase);
        $data = $className::get()->toArray();

        $foundKey = array_search($checkListId, array_column($data, 'id'));
        if($foundKey === null) {
            throw new SeException('Not Found', 422, 4220222);
        }
        $list = \DB::table('style_'.$phase.'_style')
            ->where('style_id', $styleId)
            ->get();
        $count = 0;
        foreach ($list as  $value) {
            if ($value->is_enabled && !$value->is_approved) {
                $count++;
            }
        }
        if ($count != 0) {
            return true;
        }

        if ($checkListId == end($data)['id']) {
            if ($phase == 'development') {
                $phase = 'production';
            } elseif ($phase == 'production') {
                $phase = 'shipped';
            } else {
                return true;
            }
            return $this->enableChecklist(0, $phase, $styleId);
        }
        $this->enableChecklist($checkListId, $phase, $styleId);
    }

    /**
     * @param  integer $checkListId
     * @param  string $phase
     * @param  string $styleId
     * @return boolean
     */
    public function enableChecklist($position, $phase, $styleId)
    {
        $className = '\App\Style'.ucfirst($phase);
        $data = $className::get()->toArray();
        $pivot = \DB::table('style_'.$phase.'_style')
                ->orderBy('style_'.$phase.'_id')
                ->where('style_id',$styleId)->get();
        $pivot = json_decode(json_encode($pivot), true);

        if (!$data[$position]['is_parallel'] && !$pivot[$position]['is_approved']) {
            $this->style->enableApprovalChecklist(
                'style_'.$phase.'_style',
                'style_'.$phase.'_id', $styleId,
                $data[$position]['id']
            );
            return true;
        }
        $enable = false;
        for ($i=$position; $i < count($data); $i++) {
            if ($data[$i]['is_parallel'] && !$pivot[$i]['is_approved']) {
                $this->style->enableApprovalChecklist(
                    'style_'.$phase.'_style',
                    'style_'.$phase.'_id',  $styleId,
                    $data[$i]['id']
                );
                $enable = true;
            } elseif (!$data[$i]['is_parallel'] && !$pivot[$i]['is_approved'] && !$enable) {
                $this->style->enableApprovalChecklist(
                    'style_'.$phase.'_style',
                    'style_'.$phase.'_id',  $styleId,
                    $data[$i]['id']
                );
                return true;
            } elseif (!$data[$i]['is_parallel'] && !$pivot[$i]['is_approved'] && $enable) {
                return true;
            }
        }
    }
}
