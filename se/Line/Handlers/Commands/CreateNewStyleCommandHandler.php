<?php

namespace Platform\Line\Handlers\Commands;

use App\StyleDevelopment;
use App\StyleProduction;
use App\StyleShipped;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Line\Commands\AttachTechpackCommand;
use Platform\Line\Repositories\Contracts\LineRepository;
use Platform\Line\Repositories\Contracts\StyleRepository;
use Platform\App\RuleCommanding\DefaultRuleBus;
use app\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;

class CreateNewStyleCommandHandler implements CommandHandler
{
     use DispatchesJobs;
    /**
     * @var StyleRepository
     */
    private $style;

    /**
     * @var LineRepository
     */
    private $line;

    /**
     * @var DefaultCommandBus
     */
    private $commandBus;

    /**
     * @param StyleRepository $style
     * @param LineRepository $line
     * @param DefaultCommandBus $commandBus
     * @return void
     */
	public function __construct(StyleRepository $style,
                                LineRepository $line,
                                DefaultCommandBus $commandBus,
                                DefaultRuleBus $defaultRuleBus)
	{
        $this->style = $style;
        $this->line = $line;
        $this->commandBus = $commandBus;
        $this->defaultRuleBus = $defaultRuleBus;
	}

    /**
     * @param CreateNewStyleCommand $command
     * @return mixed
     */
	public function handle($command)
	{
        \DB::beginTransaction();
        $data = $command->data;

        $data['lineId'] = $command->lineId;
        $line = $this->line->find($data['lineId']);
        if ($line) {
             $data['customerCode'] = $line->customer->code;
        } else {
             throw new SeException("Invalid line id. Line not found.", 404);
        }
        $style = $this->style->createNewStyle($data);
        $this->addStyleDevelopment($style, $line);
        $this->addStyleProduction($style, $line);
        $this->addStyleShipped($style, $line);

        /**
         * To add New Style Review at the time of creating style
         */
        $this->addStyleReview($style, $line);
        \DB::commit();
        // $this->defaultRuleBus->execute($style->replicate(), \Auth::user(), 'CreateNewStyle');
        // $job = (new DefaultRuleBusJob($style, \Auth::user(), 'CreateNewStyle'));
        //  $this->dispatch($job);
         $line->updated_at = $style->updated_at;
         $line->save();
        return $style;
	}

    /**
     * @param array $style
     * @param array $line
     */
    public function addStyleReview($style, $line)
    {
        $styleReview = \App\StyleReview::get();
        foreach ($styleReview as $key => $value) {
            $user = NULL;
           // $user = $this->getOwner($value->owner, $line);
            $review[$value->id] = [
                'is_approved' => false,
                'owner' => $user,
                'is_enabled' => true
            ];
        }
        $this->style->addReviewApprovelChecklist($style->id, $review);
    }

    /**
     * @param array $style
     * @param array $line
     */
    public function addStyleDevelopment($style, $line)
    {
        $styleDevlopments = \App\StyleDevelopment::get();
        foreach ($styleDevlopments as $key => $value) {
            $user = $this->getOwner($value->owner, $line);
            $development[$value->id] = [
                'is_approved' => false,
                'owner' => $user,
                'is_enabled' => ($key === 0)
            ];
        }
        $this->style->addDevelopmentApprovelChecklist($style->id, $development);
    }

    /**
     * @param array $style
     * @param array $line
     */
    public function addStyleProduction($style, $line)
    {
        $styleProduction = \App\StyleProduction::get();
        foreach ($styleProduction as $key => $value) {
            $user = $this->getOwner($value->owner, $line);
            $production[$value->id] = [
                'is_approved' => false,
                'owner' => $user,
                'is_enabled' => ($key === 0)
            ];
        }
        $this->style->addProductionApprovelChecklist($style->id, $production);
    }

    /**
     * @param array $style
     * @param array $line
     */
    public function addStyleShipped($style, $line)
    {
        $styleShipped = \App\StyleShipped::get();
        foreach ($styleShipped as $key => $value) {
            $user = $this->getOwner($value->owner, $line);
            $shipped[$value->id] = [
                'is_approved' => false,
                'owner' => $user,
                'is_enabled' => (!($key === 3))
            ];
        }
        $this->style->addShippedApprovelChecklist($style->id, $shipped);
    }

    /**
     * @param  string $owner
     * @return   string
     */
    public function getOwner($owner, $line)
    {
        if($owner == 'PD Lead') {
            $user = \App\User::select('id', 'email', 'display_name')
                ->where('id', '=', $line->product_development_lead_id)
                ->first();
        }
        if($owner == 'Sales Rep') {
            $user = \App\User::select('id', 'email', 'display_name')
                ->where('id', '=', $line->sales_representative_id)
                ->first();
        }
        if($owner == 'Sourcing & Production Lead') {
            $user = \App\User::select('id', 'email', 'display_name')
                ->where('id', '=', $line->production_lead_id)
                ->first();
        }
        return $user;
    }
}
