<?php 

namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Transformers\TechpackCommentTransformer;
use Platform\Techpacks\Repositories\Contracts\CutTicketNoteRepository;

class AddCutTicketNoteCommandHandler implements CommandHandler
{
	/**
	 * @var CutTicketNoteRepository
	 */
	protected $cutTicketNote;

	public function __construct(CutTicketNoteRepository $cutTicketNote)
	{
		$this->cutTicketNote = $cutTicketNote;
	}

	/**
	 * @param  AddTechpackCommentCommand $command
	 * @return mixed
	 */
	public function handle($command)
	{
		$note = $this->cutTicketNote->getNoteByTechpackId($command->techpackId);
		if ($note) {
			return	$this->cutTicketNote->updateNote($command);
		}
		return $this->cutTicketNote->addNote($command);
	}


}
