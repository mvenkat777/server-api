<?php

namespace Platform\Notes\Repositories\Eloquent;

use App\Note;
use App\NoteComment;
use Illuminate\Support\Facades\Hash;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Notes\Commands\AddCommentToNoteCommand;
use Platform\Notes\Repositories\Contracts\NoteCommentRepository;
use Platform\Notes\Repositories\Contracts\NoteRepository;

class EloquentNoteCommentRepository extends Repository implements NoteCommentRepository
{
    /**
     * @return Note
     */
    public function model()
    {
        return 'App\NoteComment';
    }
    
    /**
     * @param AddCommentToNoteCommand $command
     * @return mixed
     */
    public function addComment(AddCommentToNoteCommand $command)
    {   
        $comment = [
            'comment' => $command->comment,
            'note_id' => $command->noteId,
            'commented_by' => $command->commentedBy
        ];
        $data = $this->model->create($comment); 
        return $data;        
    }
    /**
     * @param  UpdateCmmentCommand $command 
     * @return success/Fail
     */
    public function updateComment($command, $owner=NULL)
    {
        $comment = [
                    'id' => $command->commentId,
                    'comment' => $command->comment,
                    'note_id' => $command->noteId,
                    'commented_by' =>$command->commentedBy
                ];
        return $this->model->where('id', '=', $command->commentId)
                        ->where('commented_by', '=', $command->commentedBy)
                        ->update($comment);
    }

    /**
     * @param  DeleteCommentCommand $command 
     * @param  $owner   NoteOwner
     * @return success/Fail 
     */
    public function deleteComment($command, $owner=NULL)
    {
        if ($owner) {
             $result = $this->model->where('id', '=', $command->commentId)
                            ->delete();
        }
        else {dd('no');
            $result = $this->model->where('commented_by', '=', \Auth::user()->id)
                                ->where('id', '=', $command->commentId)
                                ->delete();
        }

        return $result;
    }
}