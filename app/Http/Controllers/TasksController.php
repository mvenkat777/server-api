<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Commanding\DefaultCommandBus;
use Platform\App\Exceptions\SeException;
use Platform\Observers\Commands\GetTaskActivityDetailsCommand;
use Platform\Tasks\Commands\AddAttachmentCommand;
use Platform\Tasks\Commands\AddCommentCommand;
use Platform\Tasks\Commands\AddTagCommand;
use Platform\Tasks\Commands\AddTaskFollowerCommand;
use Platform\Tasks\Commands\ArchiveTaskCommand;
use Platform\Tasks\Commands\AssignTaskCommand;
use Platform\Tasks\Commands\ChangeMultipleTasksStatusCommand;
use Platform\Tasks\Commands\ChangeTaskPriorityCommand;
use Platform\Tasks\Commands\ChangeTaskStatusCommand;
use Platform\Tasks\Commands\CloseTaskCommand;
use Platform\Tasks\Commands\CompleteTaskCommand;
use Platform\Tasks\Commands\CreateTaskCommand;
use Platform\Tasks\Commands\DeleteAttachmentCommand;
use Platform\Tasks\Commands\DeleteCommentCommand;
use Platform\Tasks\Commands\DeleteTaskCommand;
use Platform\Tasks\Commands\DeleteTaskFollowerCommand;
use Platform\Tasks\Commands\FilterTaskCommand;
use Platform\Tasks\Commands\GetAllTaskCommand;
use Platform\Tasks\Commands\GetArchivedTaskByTypeCommand;
use Platform\Tasks\Commands\GetArchivedTaskCommand;
use Platform\Tasks\Commands\GetAssignedTaskCommand;
use Platform\Tasks\Commands\GetCategoriesTagsCommand;
use Platform\Tasks\Commands\GetCommentsCommand;
use Platform\Tasks\Commands\GetSubmittedTaskCommand;
use Platform\Tasks\Commands\GetTaskByIdCommand;
use Platform\Tasks\Commands\GetTaskByTypeCommand;
use Platform\Tasks\Commands\GetTaskCommand;
use Platform\Tasks\Commands\ReassignMultipleTasksCommand;
use Platform\Tasks\Commands\ReassignTaskCommand;
use Platform\Tasks\Commands\RemoveTagCommand;
use Platform\Tasks\Commands\RollbackTaskCommand;
use Platform\Tasks\Commands\SeeTaskCommand;
use Platform\Tasks\Commands\SendMailForTaskOwnerCommand;
use Platform\Tasks\Commands\StartTaskCommand;
use Platform\Tasks\Commands\SubmitTaskCommand;
use Platform\Tasks\Commands\UpdateTaskCommand;
use Platform\Tasks\Commands\UploadTasksCommand;
use Platform\Tasks\Helpers\TaskHelper;
use Platform\Tasks\Repositories\Contracts\TaskCommentRepository;
use Platform\Tasks\Repositories\Contracts\TaskStatusRepository;
use Platform\Tasks\Transformers\CommentTransformer;
use Platform\Tasks\Transformers\MetaTaskTransformer;
use Platform\Tasks\Transformers\SchemaTransformer;
use Platform\Tasks\Transformers\TaskStatusTransformer;
use Platform\Tasks\Transformers\TaskTransformer;
use Platform\Tasks\Validators\TaskValidator;

class TasksController extends ApiController
{
    /**
     * For calling commands
     * @var Platform\App\Commanding\DefaultCommandBus
     */
    protected $commandBus;

    /**
     * @var Platform\Tasks\Validators\TaskValidator
     */
    protected $taskValidator;

    /**
     * @var Platform\Tasks\Repositories\Contracts\TaskStatusRepository
     */
    protected $taskStatusRepository;

    /**
     * @var Platform\Tasks\Repositories\Contracts\TaskCommentRepository
     */
    protected $taskCommentRepository;

    /**
     * @var Platform\Tasks\Helpers\TaskHelper 
     */
    protected $taskHelper;

    /**
     * @param Platform\App\Commanding\DefaultCommandBus            $commandBus           
     * @param Platform\Tasks\Validators\TaskValidator                $taskValidator        
     * @param Platform\Tasks\Repositories\Eloquent\EloquentTaskStatusRepository $taskStatusRepository 
     */
    public function __construct(
        DefaultCommandBus $commandBus, 
        TaskValidator $taskValidator, 
        TaskStatusRepository $taskStatusRepository,
        TaskCommentRepository $taskCommentRepository,
        TaskHelper $taskHelper
    ){
        $this->commandBus = $commandBus;
        $this->taskValidator = $taskValidator;
        $this->taskCommentRepository = $taskCommentRepository;
        $this->taskStatusRepository = $taskStatusRepository;
        $this->taskHelper = $taskHelper;

        parent::__construct(new Manager());
    }

    /**
     * Send Mail To Task Owner with or with attachement and comments
     * 
     * @param  Request $request
     * @return mixed
     */
     public function sendMailForTaskOwner($taskId, Request $request)
     {
        $data = $request->all();
        return $this->respondWithItem(
            $this->commandBus->execute(new SendMailForTaskOwnerCommand($taskId, $data)), 
            new TaskTransformer, 'task'
        );
    }

    /**
     * get task Activity Log
     * 
     * @param  Request $request
     * @return mixed
     */
    public function getTaskActivity($taskId){
        $tasks = $this->commandBus->execute(new GetTaskActivityDetailsCommand($taskId));
        return $this->respondWithArray(['data' => $tasks]);
    }

    /**
     * Display a listing of the tasks.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->get('archived') === 'true') {
            $tasks = $this->commandBus->execute(
                new GetArchivedTaskByTypeCommand($request->all())
            );
        } else {
            $tasks = $this->commandBus->execute(
                new GetTaskByTypeCommand($request->all())
            );
        }
        return $this->respondWithPaginatedCollection($tasks, 
            new MetaTaskTransformer, 
            'task'
        );
        //return $this->respondWithArray(['data' => $this->taskHelper->groupTasks($tasks)]);
    }

    /**
     * Display a listing of the archived tasks.
     * @return \Illuminate\Http\Response
     */
    public function getAllArchivedTasksByType(Request $request)
    {
        $tasks = $this->commandBus->execute(
            new GetArchivedTaskByTypeCommand($request->all())
        );
        return $this->respondWithPaginatedCollection($tasks, 
            new MetaTaskTransformer, 
            'task'
        );
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->taskValidator->setInputsRules()->validate($request->all());
        return $this->respondWithNewItem(
            $this->commandBus->execute(new CreateTaskCommand($request->all())), 
            new TaskTransformer, 
            'task'
        );
    }

    /**
     * Display the specified task.
     *
     * @param  string  $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->respondWithItem(
            $this->commandBus->execute(new GetTaskByIdCommand($id)), 
            new TaskTransformer, 
            'task'
        );
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        return $this->respondWithItem(
            $this->commandBus->execute(new UpdateTaskCommand($request->all(),$id)),
            new TaskTransformer, 
            'task'
        );
    }

    /**
     * Rollback the specified archived task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return mixed
     */
    public function rollback(Request $request, $id)
    {
        $result = $this->commandBus->execute(new RollbackTaskCommand($request->all(),$id));

        if($result)
            return $this->respondOk('Rollback is successful');
        else
            return $this->setStatusCode(500)
                ->respondWithError('Task could not be rollback', 50000);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $msg = 'Task is deleted successfully';
        $result = null;
        if($request->get('type') == 'archive') {
            $result = $this->commandBus->execute(new ArchiveTaskCommand($id));
            $msg = 'Task is archived successfully';
        } else {
            $result = $this->commandBus->execute(new DeleteTaskCommand($id));
        }

        if($result)
            return $this->respondOk($msg);
        else
            return $this->setStatusCode(500)
                ->respondWithError('Task could not be deleted', 50000);
    }

    /**
     * Filter tasks depending upon request
     * 
     * @param  Request $request
     * @return mixed  
     */
    public function filterTasks(Request $request){
        $tasks = $this->commandBus->execute(new FilterTaskCommand($request->all()));

        if($tasks)
            return $this->respondWithPaginatedCollection($tasks, new TaskTransformer, 'task');
        else
            return $this->setStatusCode(404)->respondWithError('No Task Found', 40004);
    }

    /**
     * Get all categories in database
     * 
     * @return mixed
     */
    public function getSchema(){
        return $this->respondWithArray([
            'data' => (new SchemaTransformer)->transform()
        ]);
    }

    /**
     * Get all status in database
     * 
     * @return mixed
     */
    public function getTaskStatus(){
        $statuses = $this->taskStatusRepository->getAllTaskStatus();
        
        if($statuses)
            return $this->respondWithCollection($statuses, new TaskStatusTransformer, 'taskStatus');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong', 50000);
    }

    /**
     * Upload bulk tasks at a time
     * 
     * @param  Request $request
     * @return mixed         
     */
    public function uploadTasks(Request $request)
    {
        $this->taskValidator->setCSVRules()->validate($request->all());
        $result = $this->commandBus->execute(new UploadTasksCommand($request->all()));
        
        if($result)
            return $this->respondWithArray(['data' => $result]);
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong', 50000);            
    }

    /**
     * Add comment to task
     * 
     * @param Request $request
     */
    public function addComment($taskId, Request $request)
    {
        $this->taskValidator->setCommentRules()->validate($request->all());
        $result = $this->commandBus->execute(new AddCommentCommand($taskId, $request->all()));

        if($result)
            return $this->respondWithItem($result, new TaskTransformer, 'task');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong while adding comment', 50000);
    }

    /**
     * Get comments of a given task
     * 
     * @param  string  $taskId  
     * @param  Request $request 
     * @return mixed           
     */
    public function getComments($taskId, Request $request)
    {
        return $this->respondWithPaginatedCollection(
            $this->taskCommentRepository->getCommentsByTaskId($taskId), 
            new CommentTransformer, 'comment'
        );
    }

    /**
     * Delete Comment from task
     * 
     * @param  string $taskId  
     * @param  string $comentId
     * @return mixed          
     */
    public function deleteComment($taskId, $commentId)
    {
        $result = $this->commandBus->execute(new DeleteCommentCommand($taskId, $commentId));

        if($result)
            return $this->respondWithItem($result, new TaskTransformer, 'task');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong while adding comment', 50000);
    }

    /**
     * @param  string  $taskId 
     * @param  Request $request
     * @return mixed          
     */
    public function followTask($taskId, Request $request)
    {
        $this->taskValidator->setFollowTaskRules()->validate($request->all());
        $result = $this->commandBus->execute(new AddTaskFollowerCommand($taskId, $request->all()));

        if($result)
            return $this->respondWithItem($result, new TaskTransformer, 'task');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong while adding comment', 50000);
    }

    /**
     * @param  string $taskId    
     * @param  string $followerId
     * @return mixed            
     */
    public function deleteTaskFollower($taskId, $followerId)
    {
        $result = $this->commandBus->execute(new DeleteTaskFollowerCommand($taskId, $followerId));

        if($result)
            return $this->respondWithItem($result, new TaskTransformer, 'task');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong while adding comment', 50000);
    }

    /**
     * @param  string  $taskId 
     * @param  Request $request
     * @return mixed          
     */
    public function reassignTask($taskId, Request $request)
    {
        $this->taskValidator->setReassignTaskRules()->validate($request->all());
        $result = $this->commandBus->execute(new ReassignTaskCommand($taskId, $request->all()));

        if($result)
            return $this->respondWithItem($result, new TaskTransformer, 'task');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong while adding comment', 50000);
    }

    /**
     * Reassing multiple tasks
     * @param  Request $request 
     * @return array           
     */
    public function reassignMultipleTasks(Request $request)
    {
        // $this->taskValidator->setReassignTaskRules()->validate($request->all());
        $result = $this->commandBus->execute(new ReassignMultipleTasksCommand($request->all()));

        if($result)
            return $this->respondWithItem($result, new TaskTransformer, 'task');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong while adding comment', 50000);
    }
    
    /**
     * Change status of task
     * 
     * @param  Request $request
     * @param  string  $taskId 
     * @param  string  $status 
     * @return mixed          
     */
    public function changeTask(Request $request, $taskId, $status){
        if(strtolower($status) == 'start')
            $task = $this->commandBus->execute(new StartTaskCommand($request->all(), $taskId));
        else if(strtolower($status) == 'submit')
            $task = $this->commandBus->execute(new CompleteTaskCommand($request->all(), $taskId));
        else if(strtolower($status) == 'complete')
            $task = $this->commandBus->execute(new CompleteTaskCommand($request->all(), $taskId));
        else if(strtolower($status) == 'close')
            $task = $this->commandBus->execute(new CloseTaskCommand($request->all(), $taskId));
        else
            return $this->setStatusCode(422)
                        ->respondWithError('Invalid Status');

        if($task)
            return $this->respondWithItem($task, new TaskTransformer, 'task');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong', 50000);
    }

    /**
     * Change priority of a given task
     * 
     * @param  Request $request
     * @param  string  $taskId 
     * @return mixed         
     */
    public function changePriority($taskId, Request $request){
        $task = $this->commandBus->execute(new ChangeTaskPriorityCommand($request->all(), $taskId));

        if($task)
            return $this->respondWithItem($task, new TaskTransformer, 'task');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong', 50000);
    }

    /**
     * When user first time click on task to see
     * 
     * @param  string $taskId
     * @return mixed
     */
    public function seeTask($taskId){
        $task = $this->commandBus->execute(new SeeTaskCommand($taskId));

        if($task)
            return $this->respondWithItem($task, new TaskTransformer, 'task');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong', 50000);
    }

    /**
     * Add tag to task
     * 
     * @param UUID  $taskId  
     * @param Request $request 
     * @return  mixed 
     */
    public function addTag($taskId, Request $request){
        $task = $this->commandBus->execute(new AddTagCommand($request->all(), $taskId));
        if($task)
            return $this->respondWithItem($task, new TaskTransformer, 'TaskAddTag');
        else
            return $this->setStatusCode(422)->respondWithError('Cannot add tag', 786132);
    }

    /**
     * Remove Tag from task
     * 
     * @param  Request $request 
     * @param  UUID string $taskId  
     * @param  UUID string $tagId   
     * @return mixed           
     */
    public function removeTag(Request $request, $taskId, $tagId){
        $task = $this->commandBus->execute(new RemoveTagCommand($taskId, $tagId));
        if($task)
            return $this->respondWithItem($task, new TaskTransformer, 'TaskRemoveTag');
        else
            return $this->setStatusCode(422)->respondWithError('Cannot remove tag', 786132);
    }

    /**
     * Add attachment to task
     * 
     * @param Request $request [description]
     * @param UUID  $taskId  [description]
     * @return  mixed
     */
    public function addAttachment(Request $request, $taskId){
        $this->taskValidator->setAddAttachmentRules()->validate($request->all());
        $task = $this->commandBus->execute(new AddAttachmentCommand($request->all(), $taskId));
        if($task)
            return $this->respondWithItem($task, new TaskTransformer, 'TaskAddAttachment');
        else
            return $this->setStatusCode(422)->respondWithError('Cannot add attachment', 786132);
    }

    /**
     * @param  string $taskId
     * @param  string $id    
     * @return string        
     */
    public function deleteAttachment($taskId, $id){
        $result = $this->commandBus->execute(new DeleteAttachmentCommand($taskId, $id));
        if($result)
            return $this->respondOk('Attachment Deleted successfully');
        else
            return $this->setStatusCode(500)->respondWithError('Something went wrong', 50000);
    }

    public function changeStatusForMultipleTasks(Request $request, $status)
    {
        $this->commandBus->execute(new ChangeMultipleTasksStatusCommand($request->all(), $status));
        $tasks = $this->commandBus->execute(
            new GetTaskByTypeCommand(['type' => $request->get('type')])
        );
        return $this->respondWithPaginatedCollection($tasks, 
            new MetaTaskTransformer, 
            'task'
        );
    }

}
