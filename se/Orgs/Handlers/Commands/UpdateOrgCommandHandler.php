<?php

namespace Platform\Orgs\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Orgs\Repositories\Contracts\OrgRepository;

class UpdateOrgCommandHandler implements CommandHandler
{
	protected $orgRepo;

	function __construct(OrgRepository $orgRepo)
	{
		$this->orgRepo = $orgRepo;
	}

	public function handle($command)
	{
		if($command->logo != NULL)
		{
			$logo = $command->logo;
	        $filename  = str_shuffle('qwertyuioplkjhgfdsazxcvbnmASDFGHJKLPOIUYTREWQZXCVBNM1234567890').
	        				time() . '.' . $logo->getClientOriginalExtension();

	        $path = public_path('Orglogo/' . $filename);

	    
	        Image::make($logo->getRealPath())->resize(200, 200)->save($path);
	        $command->logo = $path;
	    }

		$this->orgRepo->UpdateOrg((array)$command);

		return 'Updated Successfully';
	}
}