<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Line\Commands\ApprovalChecklistCommand;
use Platform\Line\Commands\ArchiveStyleCommand;
use Platform\Line\Commands\AttachSampleCommand;
use Platform\Line\Commands\CreateNewStyleCommand;
use Platform\Line\Commands\GetApprovalListByIdCommand;
use Platform\Line\Commands\UnapprovalChecklistCommand;
use Platform\Line\Commands\UpdateStyleCommand;
use Platform\Line\Repositories\Contracts\StyleRepository;
use Platform\Line\Transformers\StyleTransformer;
use Platform\Line\Validators\StyleValidators;
use Platform\App\RuleCommanding\DefaultRuleBus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Platform\App\RuleCommanding\ExternalNotification\DefaultRuleBusJob;

class StyleController extends ApiController
{   
     use DispatchesJobs;
    /**
     * @param DefaultCommandBus $commandBus
     * @return void
     */
    public function __construct(DefaultCommandBus $commandBus, 
        StyleValidators $validator,
        StyleRepository $styleRepo, 
        DefaultRuleBus $defaultRuleBus
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
        $this->styleRepo = $styleRepo;
        $this->defaultRuleBus = $defaultRuleBus;

        parent::__construct(new Manager());		
    }	

    public function index(Request $request, $lineId)
    {
        $data = $request->all();
        $styles = $this->styleRepo->styleLists($data, $lineId);
        return $this->respondWithCollection($styles, new StyleTransformer, 'style');
    }
    /**
     * Creates a new style
     *
     * @param string $lineId
     * @param Request $request
     * @return mixed
     */
    public function store($lineId, Request $request) {
        $this->validator->setCreationRules()->validate($request->all());
        $style = $this->commandBus->execute(new CreateNewStyleCommand($lineId, $request->all()));

        if ($style) {
                return $this->respondWithNewItem($style, new StyleTransformer, 'style');
        } else {
                return $this->respondWithError("Failed to create a new style. Try again.");
        }
    }	

    /**
     * Creates a new style
     *
     * @param string $lineId
     * @param Request $request
     * @return mixed
     */
    public function update($lineId, $styleId, Request $request) {
        $this->validator->setUpdationRules()->validate($request->all());
        $style = $this->commandBus->execute(new UpdateStyleCommand($lineId, $styleId, $request->all()));

        if ($style) {
                return $this->respondWithItem($style, new StyleTransformer, 'style');
        } else {
                return $this->respondWithError("Failed to update style. Try again.");
        }
    }	

    /**
     * complete a style
     * @param  string $lineId  
     * @param  string $styleId 
     * @return string          
     */
    public function completeStyle($lineId, $styleId)
    {
        $complete = $this->styleRepo->completeStyle($lineId, $styleId);
        if ($complete) {
            return $this->respondOk("Successfully completed style");
        }
        return $this->respondWithError("Failed to complete");
    }

    /**
     * complete a style
     * @param  string $lineId  
     * @param  string $styleId 
     * @return string          
     */
    public function undoStyle($lineId, $styleId)
    {
        $undo = $this->styleRepo->undoStyle($lineId, $styleId);
        if ($undo) {
            return $this->respondOk("Successfully undo style");
        }
        return $this->respondWithError("Failed to undo");
    }
        
    /**
     * Archives a style
     *
     * @param string $lineId
     * @param Request $request
     * @return mixed
     */
    public function destroy(Request $request, $lineId, $styleId) 
    {
        $data = $request->all();
        if (isset($data['type']) && $data['type'] == 'delete') {
            $archived = $this->commandBus->execute(new ArchiveStyleCommand($lineId, $styleId));
            if ($archived) {
                    return $this->respondOk("Successfully deleted the style");
            } else {
                    return $this->respondWithError("Failed to archive the style. Please try again.");
            }
        } else {
            // $this->defaultRuleBus->execute($this->styleRepo->getByLineIdAndStyleId($lineId, $styleId), \Auth::user(), 'ArchiveStyle');
            // dd($this->styleRepo->getByLineIdAndStyleId($lineId, $styleId));
             // $job = (new DefaultRuleBusJob($this->styleRepo->getByLineIdAndStyleId($lineId, $styleId), \Auth::user(), 'ArchiveStyle'));
             // $this->dispatch($job);
            $archived = $this->styleRepo->archiveStyle($lineId, $styleId);
            if ($archived) {
                    return $this->respondOk("Successfully archived the style");
            } else {
                    return $this->respondWithError("Failed to archive the style. Please try again.");
            }
        }
    }	

    /**
     * Rollback style
     * @param  string $lineId  
     * @param  string $styleId 
     * @return string          
     */
    public function rollbackStyle($lineId, $styleId)
    {
        $rollback = $this->styleRepo->rollbackStyle($lineId, $styleId);
        if ($rollback) {
                return $this->respondOk("Successfully rollbacked the style");
        } else {
                return $this->respondWithError("Failed to rollback the style. Please try again.");
        }
    }

    /**
     * Adds a sample to a style
     *
     * @param string $lineId
     * @param string $styleId
     * @param Request $request
     * @return mixed
     */
    public function addSample($lineId, $styleId, Request $request) {
        $style = $this->commandBus->execute(new AttachSampleCommand($lineId, $styleId, $request->all()));

        if ($style) {
                return $this->respondWithItem($style, new StyleTransformer, 'style');
        } else {
                return $this->respondWithError("Failed to update style. Try again.");
        }
    }  

    /**
     * @param  string $styleId    [description]
     * @param  string $type       [description]
     * @param  integer $approvedId [description]
     * @return style
     */
    public function approvedChecklist($styleId, $approvalName, $approvalNameId)
    {
        $style = $this->commandBus->execute(
            new ApprovalChecklistCommand($styleId, $approvalName, $approvalNameId)
        );
        if ($style) {
                return $this->respondWithItem($style, new StyleTransformer, 'style');
        } else {
                return $this->respondWithError("Failed to approve $approvalName. Try again.");
        }
    }  

    /**
     * @param  string $styleId    
     * @param  string $type       
     * @param  integer $approvedId 
     * @return mixed
     */
    public function unapprovedChecklist($styleId, $approvalName, $approvalNameId)
    {
        $style = $this->commandBus->execute(
            new UnapprovalChecklistCommand($styleId, $approvalName, $approvalNameId)
        );
        if ($style) {
                return $this->respondWithItem($style, new StyleTransformer, 'style');
        } else {
                return $this->respondWithError("Failed to unapprove $approvalName. Try again.");
        }
    } 

    /**
     * Get Approval List For A Style
     * @param  string $id 
     * @return Response
     */
    public function getApprovalLists($id)
    {
        $approvals = $this->commandBus->execute(new GetApprovalListByIdCommand($id));
        return $this->respondWithArray(['data' => $approvals]);
    }
}
