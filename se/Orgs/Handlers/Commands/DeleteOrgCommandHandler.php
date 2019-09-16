<?php

namespace Platform\Orgs\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Orgs\Repositories\Contracts\OrgRepository;

class DeleteOrgCommandHandler implements CommandHandler
{
	protected $orgRepo;

	function __construct(OrgRepository $orgRepo)
	{
		$this->orgRepo = $orgRepo;
	}

	public function handle($command)
	{
		$result = $this->orgRepo->deleteOrg($command->id);
		if($result == 1)
		{
			return ['Deleted Successfully'];
		}
		else
		{
			return ['No Record Found'];
		}
	}
}