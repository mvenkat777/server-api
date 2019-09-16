<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Platform\App\Commanding\DefaultCommandBus;
use League\Fractal\Manager;
use Platform\Materials\Commands\CheckUniqueMaterialCommand;
use Platform\Materials\Commands\CreateMaterialCommand;
use Platform\Materials\Commands\GetAllMaterialCommand;
use Platform\Materials\Commands\ShowMaterialByIdCommand;
use Platform\Materials\Commands\UpdateMaterialCommand;
use Platform\Materials\Transformers\MaterialTransformer;
use Platform\Materials\Repositories\Eloquent\EloquentMaterialRepository;
use Platform\Materials\Repositories\Eloquent\EloquentMaterialLibraryRepository;
use Platform\Vendor\Repositories\Eloquent\EloquentVendorRepository;
use Platform\Materials\Transformers\MaterialLibraryTransformer;
use Platform\Materials\Validators\MaterialsValidator;
use Platform\Materials\Validators\MaterialsLibraryValidator;
use Platform\Vendor\Commands\ShowVendorByIdCommand;

class MaterialController extends ApiController
{
    
    protected $commandBus;

    protected $materialRepo;

    protected $materialLibRepo;

    protected $vendorRepo;

    protected $materialsValidator;

    protected $materialsLibraryValidator;     

    function __construct(DefaultCommandBus $commandBus,
                        EloquentMaterialRepository $materialRepo,
                        EloquentMaterialLibraryRepository $materialLibRepo,
                        EloquentVendorRepository $vendorRepo,
                        MaterialsValidator $materialsValidator,
                        MaterialsLibraryValidator $materialsLibraryValidator
                        )
    {
        // $this->middleware('auth.token');
        $this->commandBus = $commandBus;
        $this->materialRepo = $materialRepo;
        $this->materialLibRepo = $materialLibRepo;
        $this->vendorRepo = $vendorRepo;
        $this->materialsValidator = $materialsValidator;
        $this->materialsLibraryValidator = $materialsLibraryValidator;
        parent::__construct(new Manager());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $formData=$request->all();
        // dd($formData);
        $command=new GetAllMaterialCommand($formData);
        $materials =$this->commandBus->execute($command);
        //dd($materials);
        if ($materials) {
            return $this->respondWithPaginatedCollection($materials, new MaterialTransformer, 'Material');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $mainData=$request->all();//dd($mainData);
       $this->materialsValidator->validate($mainData);
       
       $materialData = $this->commandBus->execute(new CheckUniqueMaterialCommand($mainData));
       //dd($materialData);
        if($materialData){ 
           return $this->setStatusCode(422)->setMessage('Material Information already exists.')->respondWithItem($materialData, new MaterialTransformer , 'Material' ); 
            //return $this->setStatusCode(422)->respondWithError('Material Information already exists', 'SE_400422');    
        }else{
            $materials=$this->commandBus->execute(new CreateMaterialCommand($mainData));
        }

        return $this->setMessage('Material created successfully.')
                    ->respondWithNewItem($materials, new MaterialTransformer , 'Material' );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $command=new ShowMaterialByIdCommand($id);

        $response=$this->commandBus->execute($command);
        
        if($response != null){
            return $this->respondWithItem($response, new MaterialTransformer , 'Material');
        }else{
            return $this->setStatusCode(404)
                      ->respondWithError('Material not found', 'SE_40004');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$materialId)
    {
       $mainData=$request->all();
       $this->materialsValidator->validate($mainData);
       $mainData['materialId']=$materialId;
       //dd($mainData);

       $materialData =  $this->commandBus->execute(new CheckUniqueMaterialCommand($mainData));
       //dd($rows);
        if($materialData){
           return $this->setStatusCode(422)->setMessage('Material Information already exists.')->respondWithItem($materialData, new MaterialTransformer , 'Material' ); 
            //return $this->setStatusCode(422)->respondWithError('Material Information already exists', 'SE_400422');    
        }else{
            $materials=$this->commandBus->execute(new UpdateMaterialCommand($mainData , $materialId));
            if($materials){
                return $this->show($materialId);
            }     
        }
       
        //return $this->respondWithCollection($respondArr, new MaterialTransformer , 'Material');
    }

    public function filter(Request $request){
        //dd($request->all());
        $material = $this->materialRepo->filterMaterial($request->all());
        return $this->respondWithPaginatedCollection($material, new MaterialTransformer, 'Material');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeLibrary(Request $request)
    {
        $mainData=$request->all();
        $this->materialsLibraryValidator->validate($mainData);

        $materialExists = $this->commandBus->execute(new ShowMaterialByIdCommand($mainData['materialId']));
        $vendorExists = $this->commandBus->execute(new ShowVendorByIdCommand($mainData['vendorId']));

        //dd($vendorExists->toArray());
        if($materialExists != null && $vendorExists != null){
            //$mainData = $this->generateLibraryReference($mainData);
            $mainData['fabric_reference'] = $mainData['fabricReference']; 
            $libItems=$this->materialLibRepo->createMaterialLibrary($mainData);
            //dd();
            $this->materialLibRepo->addCustomers($libItems,$mainData['majorCustomer']);

        return $this->setMessage('Material library created successfully.')
                    ->respondWithNewItem($libItems, new MaterialLibraryTransformer , 'MaterialLibrary' );    
        }else{
        return $this->setStatusCode(422)
              ->respondWithError('Invalid Material or Vendor Provided', 'SE_400422');    
        }

        
       //dd($formData);
       /*$libItemsArr = [];
       foreach($formData as $mainData){

            if(array_key_exists('materialLibraryId',$mainData) && array_key_exists('fabricReference',$mainData)){
                //dd($mainData);
                $libItems=$this->materialLibRepo->updateMaterialLibrary($mainData);
            }else{
                //dd('TEST');
                $mainData = $this->generateLibraryReference($mainData);
                $libItems=$this->materialLibRepo->createMaterialLibrary($mainData);
            }
            
            array_push($libItemsArr,$libItems);

        }    
       //dd($libItemsArr);
        $libItemsArr= collect($libItemsArr);
        //dd($libItemsArr);
        return $this->respondWithCollection($libItemsArr, new MaterialLibraryTransformer , 'MaterialLibrary');*/
       // dd($libItems);
    }

    public function updateLibrary(Request $request,$libId)
    {
      $formData=$request->all();
      $this->materialsLibraryValidator->validate($formData);

      $materialExists = $this->commandBus->execute(new ShowMaterialByIdCommand($formData['materialId']));
      $vendorExists = $this->commandBus->execute(new ShowVendorByIdCommand($formData['vendorId']));

        //dd($vendorExists->toArray());
      if($materialExists != null && $vendorExists != null){
      $formData['materialLibraryId'] = $libId;
      $libItems=$this->materialLibRepo->updateMaterialLibrary($formData);
      $this->materialLibRepo->updateCustomers($libItems,$formData['majorCustomer']);
        //dd($libItems);
        return $this->respondWithItem($libItems, new MaterialLibraryTransformer ,'MaterialLibrary');
      }else{
        return $this->setStatusCode(422)
              ->respondWithError('Invalid Material or Vendor Provided', 'SE_400422');    
      }
     }

     /**
     * Get all the materials
     * @return App\Material
    */ 
    public function generateLibraryReference($data)
    {
        //dd($formData);
        //$newFormData = []
        //foreach($formData as &$data){
            //dd('TEST'.$data);
            $matData = $this->materialRepo->showMaterialById($data['materialId']);
            $venData = $this->vendorRepo->showVendorById($data['vendorId']);
            //dd($matData->material_reference_no);
            //dd($matData);
            if($matData->material_reference_no)
            $finalRef = $matData->material_reference_no.'-';

            if($venData->country_code)
            $finalRef .= $venData->country_code;
            
            if($venData->code)
            $finalRef .= $venData->code.'-';

            $resultRef = $this->materialLibRepo->getMaterialReference($finalRef);
            //dd($finalRef);//dd($resultRef);
            if($resultRef != null){
                $explSuffixData = explode($finalRef,$resultRef->fabric_reference);
                $dbId = (int) $explSuffixData[1];
                $suffix = $dbId+1;            
            }else{
                $suffix = 1;
            }
            //dd($suffix);
            // For zeros addition START
            $countMaterial = $suffix;
            $strCountMaterial = (string) $countMaterial;
            $strSequenceNo = (int) strlen($strCountMaterial);        

            $padLen = 23;
            if($venData->country_code == null)$padLen = 21;
            //dd($strSequenceNo);
            if($strSequenceNo > 1){            
                //dd($strSequenceNo);
                $strSequenceNo = $strSequenceNo-1;
                $padLen = $padLen-$strSequenceNo;
            }
            
            $finalRef = str_pad($finalRef, $padLen, 0, STR_PAD_RIGHT);
            // For zeros addition END
            
            $finalRef .= $suffix;

            //dd($finalRef);
            
            //array_push($data,$finalRef);
            $data['fabric_reference'] = $finalRef;
            //dd($data['fabric_reference']);   
        //}
        //dd($formData);
        return $data;
        //return $this->model->orderBy('updated_at', 'desc')->paginate($command->item);
    }

    public function filterLibrary(Request $request){
        //dd($request->all());
        $material = $this->materialLibRepo->filterMaterialLibrary($request->all());

        return $this->respondWithPaginatedCollection($material, new MaterialLibraryTransformer, 'MaterialLibrary');
    }

    public function getFabricReference(){
        //dd($request->all());
        $materialLibRefs = $this->materialLibRepo->getAllFabricReferences();
        //dd($materialLibRefs->toArray());
        
        return $this->respondWithArray(['data' => $materialLibRefs->toArray()]);
    }

    public function getVendorCountries(){
        
        $countries = $this->materialLibRepo->getAllUniqueVendorUsedCountries();
        //dd($countries->toArray());
        
        return $this->respondWithArray(['data' => $countries->toArray()]);
    }

    public function materialLibraryPrint(Request $request){
        //dd($request->all());
        $inputs = $request->all();
        if(isset($inputs['materialLibraryId'])){
            $material = $this->materialLibRepo->getMaterialLibraryPrint($inputs['materialLibraryId']);
            return $this->respondWithCollection($material, new MaterialLibraryTransformer, 'MaterialLibrary');    
        }else{
            return $this->setStatusCode(422)
              ->respondWithError('Invalid argument request passed', 'SE_400422'); 
        }
        

    }
}
