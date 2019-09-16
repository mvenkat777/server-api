<?php

namespace Platform\Notes\Repositories\Eloquent;

use Illuminate\Support\Facades\Hash;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Notes\Commands\CreateNoteCommand;
use Platform\Notes\Repositories\Contracts\NoteRepository;
use App\Note;

class EloquentNoteRepository extends Repository implements NoteRepository
{
    /**
     * @return Note
     */
    public function model()
    {
        return 'App\Note';
    }
    
    /**
     * @param CreateNoteCommand $command
     * @return mixed
     */
    public function makeNote(CreateNoteCommand $command)
    {   
        $note = [
            'id' => $this->generateUUID(),
            'title' => $command->title,
            'description' => $command->description,
            'created_by' => $command->createdBy
        ];
        $data = $this->model->create($note); 
        return $data;        
    }

    /**
     * @param ShowNoteByIdCommand $command
     * @return all
     */
    public function showNote($command)
    {
            $data = $this->model->where('notes.id', '=', $command->id)
                                ->where('notes.created_by', '=', $command->user)
                                ->leftJoin('note_shared', function ($join) use($command) {
                                        $join->on('notes.id', '=', 'note_shared.note_id')
                                                ->orWhere('note_shared.shared_by', '=', $command->user)
                                                ->orWhere('note_shared.shared_to', '=', $command->user);
                                        })
                                ->select('notes.*')
                                ->with(['comments'])
                                ->first();
            return $data;
    }

    /**
     * @param AllNoteListCommand $command
     * @return mixed
     */
    public function getAllNotes($command)
    {
        $data = $this->model->where('created_by', '=', \Auth::user()->id)
                    ->with(['comments'])
                    ->paginate($command->paginate);
        
        return $data;
    }


    /**
     * @param UpdateNoteCommand $command
     * @return 1
     */
    public function updateNote($command)
    { 
        $data = $this->model->where('id','=',$command->noteId)->first();
        if($data != NULL){
            $note = [
                'id' => $command->noteId,
                'title' => is_null($command->title)? $data->title:$command->title,
                'description' => is_null($command->description)? $data->description:$command->description,
                'created_by' => $command->createdBy
            ];
        }
        else{
            return 0;
        }
       
        return $this->model->where('id','=',$command->noteId)
                            ->where('created_by', '=', $command->createdBy)
                            ->update($note);
    }

    /**
     * @param DeleteNoteCommand $command
     * @return 1
     */
    public function deleteNote($command)
    {
        \DB::table('note_shared')->where('note_id', '=', $command->noteId)->delete();
        return $this->model->where('id','=',$command->noteId)
                            ->where('created_by', '=', $command->createdBy)
                            ->delete();
    }

    public function shareNote($command, $sharedTo)
    {
        $note = $this->model->find($command->noteId);
        if($note)
            return $note->share()->sync($sharedTo,false);
        else
            return 0;
    }

    public function getAllSharedNotes($command)
    {
        $data = $this->model->join('note_shared', 
                            function ($join){
                                $join->on('notes.id', '=', 'note_shared.note_id')
                                ->Where('note_shared.shared_to', '=', \Auth::user()->id);
                            })
                    ->paginate($command->paginate);
        return $data;
    }

    public function noteById($noteId)
    {
        return $this->model->find($noteId);
    }
}
