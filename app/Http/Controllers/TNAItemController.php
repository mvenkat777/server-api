<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\TNA\Commands\CreateTNAItemCommand;
use Platform\TNA\Commands\DeleteTNAItemCommand;
use Platform\TNA\Commands\UpdateTNAItemCommand;
use Platform\TNA\Commands\PublishTnaItemCommand;
use Platform\TNA\Repositories\Contracts\TNAItemRepository;
use Platform\TNA\Transformers\TNAItemTransformer;
use Platform\TNA\Transformers\TNAItemTransformer2;
use Platform\TNA\Validators\TNAItemValidator;

class TNAItemController extends ApiController
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
    protected $tnaItemValidator;

    /**
     * @var Platform\TNA\Repositories\Contracts\TNAItemRepository
     */
    protected $tnaItemRepository;

    /**
     * @param DefaultCommandBus $commandBus
     * @param TNAItemValidator $tnaItemValidator
     * @param TNAItemRepository $tnaItemRepository
     */
    public function __construct(DefaultCommandBus $commandBus,
                                TNAItemValidator $tnaItemValidator,
                                TNAItemRepository $tnaItemRepository)
    {
        $this->commandBus = $commandBus;
        $this->tnaItemValidator = $tnaItemValidator;
        $this->tnaItemRepository = $tnaItemRepository;

        parent::__construct(new Manager());
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $tnaId
     * @return \Illuminate\Http\Response
     */
    public function index($tnaId)
    {
        $tnaItemList = $this->tnaItemRepository->getByTNAId($tnaId);
        return $this->respondWithCollection($tnaItemList, new TNAItemTransformer, 'tnaItemList');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $tnaId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $tnaId)
    {
        $data = $request->except(['doSync', 'skipCheck', 'creator']);
        $this->tnaItemValidator->setCreateItemRule()->validate($data);
        $tnaItem = $this->commandBus->execute(new CreateTNAItemCommand($data, $tnaId, $request->tna));
        if($tnaItem)
            return $this->respondWithNewItem($tnaItem, new TNAItemTransformer, 'tnaItem');
        else
            return $this->respondWithError('Some Problems Occured.', 50000);
    }

    /**
     * Display the specified resource.
     *
     * @param string $tnaId
     * @param string $itemId
     * @return \Illuminate\Http\Response
     */
    public function show($tnaId, $itemId)
    {
        $tnaItem = $this->tnaItemRepository->getById($itemId);
        return $this->respondWithItem($tnaItem, new TNAItemTransformer, 'TNAItem');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $tnaId
     * @param string $itemId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $tnaId, $itemId)
    {
        $tnaItem = $this->commandBus->execute(new UpdateTNAItemCommand($request->all(), $itemId, $tnaId, $request->tnaItem, $request->tna));
        if($tnaItem)
            return $this->respondWithItem($tnaItem, new TNAItemTransformer, 'tnaItem');
        else
            return $this->respndWithError('Some Problems Occured.', 50000);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $tnaId
     * @param string $itemId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $tnaId, $itemId)
    {
        $result = $this->commandBus->execute(new DeleteTNAItemCommand($itemId, $request->tna, $request->tnaItem));

        if($result)
            return $this->respondOk('TNA Item deleted successfully', 20000);
        else
            return $this->respondWithError('Some Problems Occured.', 50000);
    }

    public function publishTask(Request $request, $tnaId, $itemId)
    {
        $itemsOrder = $this->commandBus->execute(new PublishTnaItemCommand($itemId, $request->tna, $request->tnaItem));

        if($itemsOrder)
            return $this->respondWithArray([ 'data' => $itemsOrder]);
        else
            return $this->respondWithError('Some Problems Occured.', 50000);
    }
}
