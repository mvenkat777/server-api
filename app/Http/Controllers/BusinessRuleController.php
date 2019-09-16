<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use App\Http\Controllers\ApiController;
use Platform\BusinessRules\Commands\StoreNewEntityRequestCommand;
use Platform\BusinessRules\Commands\StoreNewRuleRequestCommand;
use Platform\BusinessRules\Commands\GetAllRulesCommand;
use Platform\BusinessRules\Commands\UpdateCategoryCommand;
use Platform\BusinessRules\Commands\UpdateRuleCommand;
use Platform\BusinessRules\Commands\DeleteEntityNameCommand;
use Platform\BusinessRules\Commands\DeleteRuleNameCommand;
use Platform\BusinessRules\Validator\BusinessRuleValidator;
use Platform\BusinessRules\Transformer\BusinessRuleTransformer;
use Platform\BusinessRules\Transformer\BusinessKeyTransformer;
use Platform\App\Exceptions\SeException;

/**
 * Class TechpackController
 * @package App\Http\Controllers
 */
class BusinessRuleController extends ApiController{

	/**
     * @var defaultBus
     */
    protected $defaultBus;

    /**
     * @var validator
     */
    private $validator;

    /**
     * @var transformer
     */
    private $transformer;

    /**
     * @var key
     */
    private $key;

    /**
     * @param DefaultdefaultBus $defaultBus
     */
    public function __construct(DefaultCommandBus $defaultBus, 
                                BusinessRuleValidator $validator, 
                                BusinessRuleTransformer $transformer,
                                BusinessKeyTransformer $key)
    {
        $this->defaultBus = $defaultBus;
        $this->validator = $validator;
        $this->transformer = $transformer;
        $this->key = $key;

        parent::__construct(new Manager());
    }

     /**
     * @param Request $request
     * @return json response
     */
     public function storeNewEntity(Request $request){
        $validate = $this->validator->validateNewEntity($request->all());
        if($validate){
            $transformedData = $request->all();
            $transformedData['entityName'] = $this->key->transform($transformedData['entityName']);
            return \Response::json([
                    'data' => $this->transformer->transformEntity($this->defaultBus->execute(new StoreNewEntityRequestCommand($transformedData))),
                    'status_code' => 200
                ], 200);
        }
        throw new SeException('Error Processing Request', 400,'900101');
     }

     /**
     * @param Request $request
     * @return json response
     */
     public function storeNewRule(Request $request){
        $validate = $this->validator->validateNewRule($request->all());
        if($validate){
            $transformedData = $request->all();
            $transformedData['entityName'] = $this->key->transform($transformedData['entityName']);
            return \Response::json([
                    'data' => $this->transformer->transform($this->defaultBus->execute(new StoreNewRuleRequestCommand($transformedData))),
                    'status_code' => 200
                ], 200);
        }
        throw new SeException('Error Processing Request',400,'9001020');
     }

     /**
     * @param Request $request
     * @return json response
     */
     public function getAllRule(){
        try{
            return \Response::json([
                    'data' => $this->transformer->transform($this->defaultBus->execute(new GetAllRulesCommand())),
                    'status_code' => 200
                ], 200);
        } catch(Exception $e) {
            throw new SeException('Something Went Wrong',500,'9001030');
        }
     }

     /**
     * @param Request $request
     * @return json response
     */
     public function updateCategory(Request $request, $id){
        $validate = $this->validator->validateNewEntity($request->all());
        $transformedData = $request->all();
        $transformedData['entityName'] = $this->key->transform($transformedData['entityName']);
        if($validate){
            return \Response::json([
                        'data' => $this->transformer->transformEntity($this->defaultBus->execute(new UpdateCategoryCommand($transformedData, $id))),
                        'status_code' => 200
                    ], 200);
        }
        throw new SeException('Error Processing Request',400,'9001020');
     }

     /**
     * @param Request $request
     * @return json response
     */
     public function updateRule(Request $request, $id){
        $validate = $this->validator->validateNewRule($request->all());
        $transformedData = $request->all();
        $transformedData['entityName'] = $this->key->transform($transformedData['entityName']);
        if($validate){
            return \Response::json([
                        'data' => $this->transformer->transform($this->defaultBus->execute(new UpdateRuleCommand($transformedData, $id))),
                        'status_code' => 200
                    ], 200);
        }
        throw new SeException('Error Processing Request',400,'9001010');
     }

     /**
     * @param id
     * @return json response
     */
     public function deleteEntity($id){
        try{
            return \Response::json([
                        'data' => $this->defaultBus->execute(new DeleteEntityNameCommand($id)),
                        'status_code' => 200
                    ], 200);
        }
        catch(Exception $e){
            throw new SeException('Error Processing Request',400,'9001020');
        }
     }

     /**
     * @param id
     * @return json response
     */
     public function deleteRule($ruleId){
        try{
            return \Response::json([
                        'data' => $this->defaultBus->execute(new DeleteRuleNameCommand($ruleId)),
                        'status_code' => 200
                    ], 200);
        }
        catch(Exception $e){
            throw new SeException('Error Processing Request',400,'9001020');
        }
     }
}