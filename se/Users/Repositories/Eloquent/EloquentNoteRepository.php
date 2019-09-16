<?php

namespace Platform\Users\Repositories\Eloquent;

use App\UserNote;
use Platform\App\Repositories\Eloquent\Repository;
use Platform\Users\Repositories\Contracts\NoteRepository;

class EloquentNoteRepository extends Repository implements NoteRepository
{
    public function model()
    {
        return 'App\UserNote';
    }

    public function createNote($command, $writtenBy)
    {
        $note = [
            'written_by' => $writtenBy,
            'user_id' => $command->userId,
            'note' => $command->note,
            'status' => $command->status,
        ];

        return $this->model->create($note);
    }

    public function updateNote($command, $writtenBy)
    {
        $note = $this->model->where('id', '=', $command->noteId)->first();
        if ($note) {
            return $note->update(['written_by' => $writtenBy,
                                'user_id' => $command->userId,
                                'note' => $command->note,
                                'status' => $command->status, ]);
        }
        return 0;
    }

    public function getNote($data, $command)
    {
        return $this->model->where('written_by', '=', $data->user_id)
                            ->where('user_id', '=', $command->userId)
                            ->orWhere(function ($query) use ($command) {
                                $query->where('status', '=', 'public')
                                      ->where('user_id', '=', $command->userId)
                                      ->where('deleted_at', null);
                            })
                            ->get();
    }

    public function deleteNote($data, $writtenBy)
    {
        $note = $this->model->where('id', '=', $data->noteId)
                    ->where('written_by', '=', $writtenBy)->first();
        if ($note) {
            return $note->delete();
        }
        return 0;
    }
}
