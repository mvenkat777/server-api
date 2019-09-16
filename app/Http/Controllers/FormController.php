<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Form\Commands\StoreFormCommand;
use Platform\Form\Commands\GetFormCommand;
use Platform\Form\Commands\UpdateFormCommand;
use Platform\Form\Commands\UpdateStatusCommand;
use Platform\Form\Commands\SubmitFormCommand;
use Platform\Form\Commands\DeleteFormCommand;
use Platform\Form\Models\FormStatus;
use Platform\Form\Models\FormHistory;
use Platform\Form\Models\Forms;
use Platform\Form\Models\FormUser;
use Platform\Form\Transformers\FormUserTransformer;
use Platform\Form\Transformers\FormTransformer;
use Platform\Form\Transformers\FormMetaTransformer;
use Platform\Form\Transformers\FormHistoryTransformer;

/**
* FormController
*/
class FormController extends ApiController
{
    /**
     * For calling commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    public function __construct(DefaultCommandBus $commandBus)
    {
        $this->commandBus = $commandBus;

        parent::__construct(new Manager());
    }

    public function getFormMeta()
    {
        $collection = Forms::all();
        return $this->respondWithCollection($collection, new FormMetaTransformer ,'FormMetaTransformer');
    }

     public function getFormsByStatus($type)
    {
       
       
       $listTypes = FormStatus::lists('id','status')->toArray();
       $listTypes['pending'] = [1,3];
      
       if(in_array($type,array_keys($listTypes))){
            //dd('IF');
            // dd(FormUser::where('form_status_id','=',$listTypes[$type])->toSql(),$listTypes[$type])->toSql();
            if(!is_array($listTypes[$type])) $listTypes[$type] = [$listTypes[$type]];
            $result = FormUser::whereIn('form_status_id',$listTypes[$type])->orderBy('updated_at','desc')->get();//model
            return $this->respondWithCollection($result, new FormUserTransformer ,'FormUserTransformer');
       }
       else{
            throw new SeException("Invalid status parameter passed", 422, '9002422');
       }
       

    }

    public function index()
    {
        $collection = FormStatus::all();
        return $this->respondWithArray(['data' => $collection]);
    }


    public function store(Request $request)
    {
        
        if(!isset($request->all()['type']))
            throw new SeException("Specify the form type", 422, '9001422');
        try{
            $result = $this->commandBus->execute(new StoreFormCommand($request->all()));
        }catch(\Exception $e){
            throw new SeException("Failed to save form.", 500, '9001500');
        }
        return $this->respondWithArray(['data' => $result]);
    }

    public function getForm(Request $request)
    {
        if(!isset($request->all()['type']))
            throw new SeException("Invalid parameter passed", 422, '9002422');
        
        $result = $this->commandBus->execute(new GetFormCommand($request->all()));
        // dd($result);
        if($request->all()['type'] == 'all')
            return $this->respondWithCollection($result, new FormUserTransformer ,'FormUserTransformer');
        else
            return $this->respondWithCollection($result, new FormTransformer ,'FormTransformer');    
    }

    public function getHistory(Request $request)
    {
        if(!isset($request->all()['type']))
            throw new SeException("Invalid parameter passed", 422, '9002422');
        
        $result = FormHistory::all();
        
        foreach ($result as $key => $value) {
            $result[$key]['type'] = $request->all()['type'];
            $result[$key]['id'] = $request->all()['id'];
        }
        $transformer = new FormHistoryTransformer();
        $collection = $transformer->transform($result);
        if(count($collection))
            return $this->respondWithArray(['data' => $collection]);
        else
            return $this->respondWithArray(['data' => []]);    
    }

    public function update(Request $request)
    {
        if(!isset($request->all()['id']))
            throw new SeException("Specify the form properly", 422, '9003422');
        // dd($request->all()['submit']);
        if(isset($request->all()['submit'])){
            $result = $this->commandBus->execute(new SubmitFormCommand($request->all()));
            return $this->getForm($request);
        }
        if(isset($request->all()['approve'])){
            $result = $this->commandBus->execute(new UpdateStatusCommand($request->all()));
            return $this->getForm($request);
        }
        $result = $this->commandBus->execute(new UpdateFormCommand($request->all()));
        if($result){
            return $this->getForm($request);
        }
        return $this->respondWithError('Failed to update');
    }

    public function destroy(Request $request)
    {
        // dd($request->all());
        $isFormDeleted = $this->commandBus->execute(new DeleteFormCommand($request));   

        if ($isFormDeleted) {
                    return $this->respondOk(
                        'The form has been deleted.',
                        "20001"
                    );
                }
        else
        {
           return $this->respondWithError('Failed to delete!'); 
        }
    }
}