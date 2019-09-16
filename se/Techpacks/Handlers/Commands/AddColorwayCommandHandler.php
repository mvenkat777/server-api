<?php
namespace Platform\Techpacks\Handlers\Commands;

use Platform\App\Commanding\CommandHandler;
use Platform\Techpacks\Repositories\Contracts\ColorwaysRepository;

class AddColorwayCommandHandler implements CommandHandler
{
	public function __construct(ColorwaysRepository $colorway)
    {
		$this->colorway = $colorway;
	}

	public function handle($command) {
		$boms = $command->data->bill_of_materials;
		foreach ($boms as $bom) {
			foreach ($bom['rows'] as $bomLineItem) {
				if (isset($bomLineItem['colorway']) && isset($bomLineItem['colorway']['colorway'])) {
					$colorway = $bomLineItem['colorway'];
					$colorway['techpackId'] = $command->techpackId;
					$colorway['bomLineItemId'] = $bomLineItem['id'];
					$color = $colorway['colorway'];
                                        $this->colorway->addNewColorway($colorway);
				}
			}
		}
	}

}
