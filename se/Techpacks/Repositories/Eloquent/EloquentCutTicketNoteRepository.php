<?php

namespace Platform\Techpacks\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Techpacks\Repositories\Contracts\CutTicketNoteRepository;
use App\CutTicketNote;

class EloquentCutTicketNoteRepository extends Repository implements CutTicketNoteRepository 
{

	public function model(){
		return 'App\TechpackCutTicketNote';
	}

	/**
	 * @param  string $techpackId 
	 * @return object             
	 */
	public function getNoteByTechpackId($techpackId)
	{
		return $this->model->where('techpack_id', $techpackId)->first();
	}

	/**
	 * @param object $command 
	 * @return TechpackCutTicketNote
	 */
	public function addNote($command)
	{
		$note = [
			'techpack_id' => $command->techpackId,
			'note' => $command->note,
			'image' => json_encode($command->image)
		];

		return $this->create($note);
	}

	/**
	 * @param  object $command 
	 * @return boolean          
	 */
	public function updateNote($command)
	{
		$note = [
			'note' => $command->note,
			'image' => json_encode($command->image)
		];

		return $this->model->where('techpack_id', $command->techpackId)->update($note);
	}

}