<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Helpers\Helpers;
use Platform\TNA\Commands\AddAttachmentCommand;
use Platform\TNA\Commands\ChangeStateCommand;
use Platform\TNA\Commands\CreateTNACommand;
use Platform\TNA\Commands\DeleteAttachmentCommand;
use Platform\TNA\Commands\DeleteTNACommand;
use Platform\TNA\Commands\FilterTNACommand;
use Platform\TNA\Commands\GetMetaDataCommand;
use Platform\TNA\Commands\GetTNAByIdCommand;
use Platform\TNA\Commands\SyncCommand;
use Platform\TNA\Commands\UpdateTNACommand;
use Platform\TNA\Helpers\TNAHelper;
use Platform\TNA\Repositories\Contracts\TNARepository;
use Platform\TNA\Repositories\Contracts\TNATemplateRepository;
use Platform\TNA\Transformers\MetaTNATransformer;
use Platform\TNA\Transformers\TNAMetaDataTransformer;
use Platform\TNA\Transformers\TNATemplateTransformer;
use Platform\TNA\Transformers\TNATransformer;
use Platform\TNA\Validators\TNAValidator;
use Platform\Observers\Commands\GetTNAActivityDetailsCommand;
use Platform\TNA\Commands\DeleteTNATemplateCommand;
use Platform\TNA\Commands\ArchiveTNACommand;
use Platform\TNA\Commands\RollbackTNACommand;

class TNAController extends ApiController
{
    /**
     * For calling commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * For Validation of TNA
     * @var Platform\TNA\Validators\TNAValidator
     */
    protected $tnaValidator;

    /**
     * @var Platform\TNA\Repositories\TNARepository
     */
    protected $tnaRepository;
    
    /**
     * @var Platform\TNA\Repositories\TNATemplateRepository
     */
    protected $tnaTemplateRepository;
    
    /**
     * @param DefalutCommandBus $commandBus
     * @param TNAValidator $tnaValidator
     * @param TNARepository $tnaRepository
     */
    public function __construct(DefaultCommandBus $commandBus,
                                TNAValidator $tnaValidator,
                                TNATemplateRepository $tnaTemplateRepository,
                                TNARepository $tnaRepository)
    {
        $this->commandBus = $commandBus;
        $this->tnaValidator = $tnaValidator;
        $this->tnaRepository = $tnaRepository;
        $this->tnaTemplateRepository = $tnaTemplateRepository;

        parent::__construct(new Manager());
    }

    /**Get Data required for TNA Creation
     *
     * @return array
     */
    public function getMetaData()
    {
        $result = $this->tnaRepository->getCategorySchema();
        return $this->respondWithArray([
            'data' => (new TNAMetaDataTransformer)->transform($result)
        ]);
    }

    /**
     * get tna Activity Log
     * 
     * @param  Request $request
     * @return mixed
     */
    public function getTNAActivity($taskId){
        $tna = $this->commandBus->execute(new GetTNAActivityDetailsCommand($taskId));
        return $this->respondWithArray(['data' => $tna]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->get('archived') === 'true') {
            $tnaList = $this->tnaRepository->getAllArchived($request->item);
        } else {
            $tnaList = $this->tnaRepository->getAll($request->item);
        }

        if($tnaList)
            return $this->respondWithPaginatedCollection($tnaList, new MetaTNATransformer, 'tnaList');
        else
            return $this->respondWithError('Something went wrong', 50000);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $this->tnaValidator->setCreateTNARule()->validate($data);
        $result = $this->commandBus->execute(new CreateTNACommand($data));

        if($result)
            return $this->respondWithNewItem($result, new TNATransformer, 'new TNA');
        else
            return $this->respondWithError('Some Problems Occured', 50000);
    }

    /**
     * Display the specified resource.
     *
     * @param  string (UUID)  $id  
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tna = $this->tnaRepository->getById($id);
        if($tna)
            return $this->respondWithItem($tna, new TNATransformer, 'TNA');
        else
            return $this->respondWithError('Something went wrong.', 50000);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string (UUID)  $tnaId  
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $tnaId)
    {
        $this->tnaValidator->setRulesForUpdateTNA()->validate($request->all());
        $tna = $this->commandBus->execute(new UpdateTNACommand($request->all(), $tnaId));
        if($tna)
            return $this->respondWithItem($tna, new TNATransformer, 'TNA');
        else
            return $this->respondWithError('Something went wrong.', 50000);
    }

    /**
     * Rollback the archived TNA
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string (UUID)  $tnaId  
     * @return \Illuminate\Http\Response
     */
    public function rollback(Request $request, $tnaId)
    {
        $result = $this->commandBus->execute(new RollbackTNACommand($tnaId));
        if($result)
            return $this->respondOk('Rollback is successfull');
        else
            return $this->respondWithError('Something went wrong.', 50000);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string (UUID)  $id  
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $msg = 'TNA is deleted successfully';
        $result = null;
        if($request->get('type') == 'archive') {
            $result = $this->commandBus->execute(new ArchiveTNACommand($id));
            $msg = 'TNA is archived successfully';
        } else {
            $result = $this->commandBus->execute(new DeleteTNACommand($id));
        }
        if($result)
            return $this->respondOk($msg, 20000);
        else
            return $this->respondWithError('Something went wrong.', 50000);
    }

    /**
     * Change staus to TNA i.e publish/pause/resume/archive
     * 
     * @param  Request $request
     * @param  string (UUID)  $tnaId  
     * @return mixed          
     */
    public function changeState(Request $request, $tnaId, $state)
    {
        $tna = $this->commandBus->execute(new ChangeStateCommand($request->all(), $tnaId, $state));
        if($tna)
            return $this->respondWithItem($tna, new TNATransformer, 'tna');
        else
            return $this->respondWithError('Something went wrong', 50000);
    }

    /**
     * For Synchronizing TNA->itemsOrder and TNAItems
     * 
     * @param  Request $request 
     * @param  string/UUId  $tnaId   
     * @return mixed           
     */
    public function syncTNA(Request $request, $tnaId)
    {
        $result = $this->commandBus->execute(new SyncCommand($request->all(), $tnaId, $request->tna));
        return $this->respondWithItem($result, new TNATransformer, 'Synced TNA');
        //return $this->respondWithArray(['data' => $result]);
    }

    /**
     * Adding attachment to TNA
     *
     * @param Request $request 
     * @param string/UUID  $tnaId   
     * @return  mixed 
     */
    public function addAttachment(Request $request, $tnaId)
    {
        $tna = $this->commandBus->execute(new AddAttachmentCommand($request->all(), $tnaId));
        if($tna)
            return $this->respondWithItem($tna, new TNATransformer, 'tnawithattachment');
        else
            return $this->respondWithError('Unable to add attachment', 50000);
    }

    /**
     * Delete TNA attachment
     *
     * @param  Request $request 
     * @param  string/UUID  $tnaId   
     * @return string           
     */
    public function deleteAttachment(Request $request, $tnaId)
    {
        $result = $this->commandBus->execute(new DeleteAttachmentCommand($tnaId));
        if($result)
            return $this->respondOk('Attachment Deleted Successfully');
        else
            return $this->respondWithError('Unable to delete attachment', 50000);
    }

    /**
     * Filter TNA by orderId/CustomerId/techpackId etc
     *
     * @param  Request $request 
     * @return Collection           
     */
    public function filter(Request $request)
    {
        $tna = $this->tnaRepository->filterTna($request->all());
        return $this->respondWithPaginatedCollection($tna, new MetaTNATransformer, 'TAN');
    }

    /**
     * Get list of available templates
     */
    public function getTemplateList($isMilestoneType=true)
    {
        $templateList = $this->tnaTemplateRepository->getByType($isMilestoneType);
        return $this->respondWithCollection($templateList, new TNATemplateTransformer, 'template list');
    }

    public function deleteTemplate($templateId)
    {
        $result = $this->commandBus->execute(new DeleteTNATemplateCommand($templateId));

        if($result)
            return $this->respondOk('Template deleted successfully');
        else
            return $this->respondWithError('Some error occured', 500);
    }
}
