<?php

namespace Platform\Line\Handlers\Commands;

use Carbon\Carbon;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\Line\Repositories\Contracts\LineRepository;
use Platform\Line\Repositories\Contracts\StyleRepository;

class UnapprovalChecklistCommandHandler implements CommandHandler
{
    private $style;
    private $line;
    
    public function __construct(StyleRepository $style)
    {
        $this->style = $style;
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
            return \App\Style::find($command->styleId);
        } else {
            throw new SeException("Invalid ApprovalType", 400, 4000802);
        }
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
        $this->style->unapprovedChecklist('style_development_style','style_development_id', $command->styleId, $command->approvalNameId);
        $style->setCustomMessage($development->name.' is unchecked for style '.$style->name)->recordCustomActivity($style, ['Style',[],true, null, [
                    'id' => $style->id,
                    'name' => $style->name,
                ]],'unapprove');
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
        $this->style->unapprovedChecklist('style_production_style', 'style_production_id', $command->styleId, $command->approvalNameId);
        $style->setCustomMessage($production->name.' is unchecked for style '.$style->name)->recordCustomActivity($style, ['Style',[],true, null, [
                    'id' => $style->id,
                    'name' => $style->name,
                ]],'unapprove');
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
        $this->style->unapprovedChecklist('style_shipped_style', 'style_shipped_id', $command->styleId, $command->approvalNameId);
        $style->setCustomMessage($shipped->name.' is unchecked for style '.$style->name)->recordCustomActivity($style, ['Style',[],true, null, [
                    'id' => $style->id,
                    'name' => $style->name,
                ]],'unapprove');
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
        // $this->checkOwner(json_decode($development['pivot']['owner'])->email);
        $this->style->unapprovedChecklist('style_review_style','style_review_id', $command->styleId, $command->approvalNameId);
        
        $style->setCustomMessage($review->name.' is unchecked for style '.$style->name)->recordCustomActivity($style, ['Style',[],true, null, [
                    'id' => $style->id,
                    'name' => $style->name,
                ]],'unapprove');

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
}
