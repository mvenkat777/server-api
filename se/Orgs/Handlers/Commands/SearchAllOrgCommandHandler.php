<?php

namespace Platform\Orgs\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Orgs\Repositories\Contracts\OrgRepository;

class SearchAllOrgCommandHandler implements CommandHandler
{
	protected $orgRepo;

	function __construct(OrgRepository $orgRepo)
	{
		$this->orgRepo = $orgRepo;
	}

	public function handle($command)
	{
		return $this->orgRepo->allOrg();
	}
}