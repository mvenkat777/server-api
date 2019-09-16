<?php

namespace Platform\Line\Handlers\Commands;

use App\SampleContainer;
use Platform\App\Commanding\CommandHandler;
use Platform\TNA\Models\TNA;
use Platform\Techpacks\Repositories\Contracts\TechpackRepository;

class AttachTechpackCommandHandler implements CommandHandler
{
    /**
     * @var Platform\Techpacks\Repositories\Contracts\TechpackRepository
     */
    private $techpack;

    /**
     * @param TechpackRepository $techpack
     */
    public function __construct(TechpackRepository $techpack)
    {
        $this->techpack = $techpack;
    }

    /**
     * Handles the AttachTechpackCommand
     *
     * @param AttachTechpackCommand $command
     * @return array $data
     */
    public function handle($command)
    {
        $data = $command->data;

        $techpack = $this->techpack->find($data['techpackId']);
        $techpack->visibility = true;
        $meta = $techpack->meta;
        $meta->visibility = true;
        $techpack->meta = $meta;
        $techpack->save();

        $data['flat'] = isset($techpack->image) ? $techpack->image : null;
        $data['name'] = $techpack->name;
        $data['styleCode'] = $techpack->style_code;
        $relatedTNA = TNA::where('techpack_id', $techpack->id)->first();
        if ($relatedTNA) {
            $data['tnaId'] = $relatedTNA->id;
        } else {
            $data['tnaId'] = null;
        }
        return $data;
    }

}
