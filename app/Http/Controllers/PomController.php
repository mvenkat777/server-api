<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\Pom\Commands\CheckPomCommand;
use Platform\Pom\Commands\GetPomForTechpackCommand;
use Platform\Pom\Repositories\Contracts\PomRepository;
use Platform\Pom\Repositories\Contracts\PomSheetRepository;
use Platform\Pom\Transformers\MetaPomSheetTransformer;
use Platform\Pom\Transformers\MetaPomTransformer;
use Platform\Pom\Transformers\PomForTechpackTransformer;
use Platform\Pom\Transformers\PomSheetTransformer;
use Platform\Pom\Transformers\PomTransformer;
use Platform\Pom\Validators\PomSheetValidator;
use Platform\Pom\Validators\PomValidator;

class PomController extends ApiController
{

    private $validator;
    private $pom;
    private $sheetValidator;
    private $pomSheet;

    public function __construct(
        PomValidator $validator, 
        PomSheetValidator $sheetValidator,
        PomRepository $pom,
        PomSheetRepository $pomSheet,
        DefaultCommandBus $commandBus
    ) {
        parent::__construct(new Manager());

        $this->validator = $validator;
        $this->pom = $pom;
        $this->pomSheet = $pomSheet;
        $this->sheetValidator = $sheetValidator;
        $this->commandBus = $commandBus;
    }  

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $pom = $this->pom->getAllPom($data);
        return $this->respondWithCollection($pom, 
            new MetaPomTransformer, 
            'pom'
        );
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
        $this->validator->setCreatePomRules()->validate($data);
        $pom = $this->pom->addPom($data);

        if ($pom) {
            return $this->respondWithNewItem($pom, new MetaPomTransformer, 'Pom');
        }
        return $this->respondWithError("Failed to add the new pom . Please try again");
    }

    /**
     * update range value and range name
     * @param  Request $request 
     * @param  string  $pomId   
     * @return  mixed
     */
    public function update(Request $request, $pomId)
    {
        $data = $request->all();
        $pomList = $this->pom->updatePom($data, $pomId);
        if ($pomList) {
            return $this->respondWithItem($pomList, new PomTransformer, 'pom');
        }
       return $this->respondWithError('Failed to add column');
    }

    /**
     * Get Pom By Id
     *     
     * @param  string $id 
     * @return mixed
     */
    public function show($id)
    {
        $pom = $this->pom->getPomById($id);

        if ($pom) {
            return $this->respondWithItem($pom, new PomTransformer, 'pom');
        }
        return $this->respondWithError('Failed To show');
    }

    /**
     * Add sheet row
     * 
     * @param Request $request 
     * @param string  $pomId   
     */
    public function addSheetRow(Request $request, $pomId)
    {
        $data = $request->all();
        $data['pomId'] = $pomId;
        $this->sheetValidator->setAddPomSheetRowRule()->validate($data);
        $pomSheet = $this->pomSheet->addSheetRow($data);

        if ($pomSheet) {
            return $this->respondWithNewItem($pomSheet, new PomSheetTransformer, 'pomSheet');
        }
        return $this->respondWithError('Faild to add row in sheet');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSheetRow(Request $request, $pomId)
    {
        $data = $request->all();
        foreach ($data as $sheetRow) {
            $sheetRow['pomId'] = $pomId;
            $this->sheetValidator->setUpdatePomSheetRowRule()->validate($sheetRow);
            $pomSheet = $this->pomSheet->updateOrAddSheetRow($sheetRow, $pomId);
        }
        if ($pomSheet) {
            return $this->respondWithItem($this->pom->getPomById($pomId), 
                    new PomTransformer, 'pom'
                );
        }
        return $this->respondWithError("Failed to update or Added the Pom. Please try again"); 
    }

    /**
     * Delete POM
     * @param  \Illuminate\Http\Request  $request
     * @param  string $pomId 
     * @return \Illuminate\Http\Response        
     */
    public function destroy(Request $request, $pomId)
    {
        $data = $request->all();
        if (isset($data['type']) && $data['type'] == 'delete') {
            $delete = $this->pom->deletePom($pomId);
            if ($delete) {
                return $this->respondOk('POM deleted successfully');
            }
            return $this->respondWithError('Faild to delete');
        } else {
            $archived = $this->pom->archivePom($pomId);
            if ($archived) {
                return $this->respondOk('POM archived successfully');
            }
            return $this->respondWithError('Faild to archive');
        }
    }

    /**
     * Rollback Pom
     * @param  string $pomId 
     * @return string        
     */
    public function rollbackPom($pomId)
    {
        $rollbacked = $this->pom->rollback($pomId);
        
        if ($rollbacked) {
            return $this->respondOk('POM rollbacked successfully');
        }
        return $this->respondWithError('Faild to rollback');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroySheetRow(Request $request, $pomId, $sheetId)
    {
        $data = $request->all();
        if ($data['type'] == 'delete') {
            $delete = $this->pomSheet->deleteSheetRow($pomId, $sheetId);
        }else {
            $delete = $this->pomSheet->archiveSheetRow($pomId, $sheetId);
        }

        if ($delete) {
            return $this->respondOk('Sheet Row Deleted Succesfilly');
        }

        return $this->respondWithError('Faild To Delete');
    }

    public function rollbackSheetRow($pomId, $sheetId)
    {
        $rollbacked = $this->pomSheet->rollbackSheetRow($pomId, $sheetId);

        if ($rollbacked) {
            return $this->respondOk('POM Sheet Row rollbacked successfully');
        }
        return $this->respondWithError('Faild to rollback');
    }

    public function getForTechpack(Request $request)
    {
        $category = $request->get('category');
        $sizeType = $request->get('sizeType');
        $productType = $request->get('productType');
        $product = $request->get('product');
        $type = $request->get('type');

        $pomSheet = $this->commandBus->execute(new GetPomForTechpackCommand(
            $category, $sizeType,
            $productType, $product
        ));
        if ($pomSheet === 'faild') {
            return $this->respondOk('Pom Not Present');
        }
        if ($type === 'pom') {
            return $this->respondWithCollection($pomSheet, new PomForTechpackTransformer, 'pom');
        } 
        return $this->respondWithCollection($pomSheet, new MetaPomSheetTransformer, 'pomSheet');

    }

    /**
     * Filter POM 
     * @param  Request $request 
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $dataRequied = $request->get('data');
        $sheets = $this->pomSheet->filterPomSheet(['code' => $request->get('code')], $dataRequied);
        if ($dataRequied === 'all') {
            return $this->respondWithCollection($sheets, new PomForTechpackTransformer, 'sheet');
        }
        return $this->respondWithArray(['data' => $sheets]);
    }

    public function checkPom(Request $request)
    {
        $category = $request->get('category');
        $sizeType = $request->get('sizeType');
        $productType = $request->get('productType');
        $product = $request->get('product');
        $type = $request->get('type');

        $response = $this->commandBus->execute(new CheckPomCommand(
            $category, $sizeType,
            $productType, $product
        ));
        return $this->respondWithArray(['data' => $response]);
    }
}
