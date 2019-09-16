<?php

namespace Platform\Techpacks\Transformers;

use App\Techpack;
use App\User;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Line\Transformers\MetaLineTransformer;
use Platform\SampleContainer\Transformers\MetaSampleContainerTransformer;
use Platform\TNA\Transformers\MetaTNATransformer;
use Platform\Techpacks\Transformers\CutTicketNoteTransformer;
use Platform\Techpacks\Transformers\CutTicketTransformer;
use Platform\Users\Transformers\MetaUserTransformer;

/**
 * Class TechpackTransformer.
 */
class TechpackTransformer extends TransformerAbstract
{
    /**
     * @param Techpack $techpack
     *
     * @return array
     */
    public function transform(Techpack $techpack)
    {
        $fractal = new Manager();
        $sharedWith = $techpack->users()
                            ->where('techpack_user.permission', '!=', 'owner')
                            ->get();
        $sharedWith = new Collection($sharedWith, new TechpackUserTransformer());
        $sharedWith = $fractal->createData($sharedWith)->toArray();

        $owner = User::where('id', $techpack->user_id)->first();
        $owner = (new TechpackUserTransformer())->transform($owner);
        if ($techpack->style) {
            $line = new Item($techpack->style->line, new MetaLineTransformer());
            $line = $fractal->createData($line)->toArray()['data'];
        } else {
            $line = [];
        }

        $sampleContainer = null;
        if($techpack->sampleContainer) {
            $sampleContainer = (new MetaSampleContainerTransformer())->transform($techpack->sampleContainer);
        }

        $tna = $techpack->TNA()->first();
        if($tna) {
            $tna = (new MetaTNATransformer)->transform($tna);
        }

        $meta = $techpack->meta;
        $meta->owner = $owner;
        $meta->sharedWith = $sharedWith['data'];

        if ($techpack->cutTicketNote) {
            $cutTicketNote = new item($techpack->cutTicketNote, new CutTicketNoteTransformer());
            $cutTicketNote = $fractal->createData($cutTicketNote)->toArray()['data'];
        }
        
        $cutTickets = $techpack->cutTickets()->get();
        $cutTickets = new Collection($cutTickets, new CutTicketTransformer());
        $cutTickets = $fractal->createData($cutTickets)->toArray()['data'];

        $cutTicketList['list'] = $cutTickets;
        $cutTicketList['note'] = isset($cutTicketNote['note'])? $cutTicketNote['note'] : '';
        $cutTicketList['image'] = isset($cutTicketNote['image'])? $cutTicketNote['image'] : [];
        

        return [
            'id' => (string) $techpack->id,
            'version' => $techpack->version,
            'name' => (string) $techpack->meta->name,
            'styleCode' => (string) $techpack->meta->styleCode,
            'category' => (string) $techpack->meta->category,
            'season' => (string) $techpack->meta->season,
            'stage' => (string) $techpack->meta->stage,
            'visibility' => (boolean) $techpack->meta->visibility,
            'isEditable' => (boolean) $this->isEditable($techpack),
            'image' => $techpack->image,
            'revision' => (integer) $techpack->revision,
            'line' => isset($line)? $line : [],
            'sampleContainer' => $sampleContainer,
            'tna' => $tna,
            'meta' => $meta,
            'billOfMaterials' => $techpack->bill_of_materials,
            'cutTickets' => $cutTicketList,
            'poms' => $techpack->poms,
            'specSheets' => $techpack->spec_sheets,
            'colorSets' => $techpack->color_sets,
            'sketches' => $techpack->sketches,
            'userId' => (string) $techpack->user_id,
            'parentId' => (string) $techpack->parent_id,
            'createdAt' => date(DATE_ISO8601, strtotime($techpack->created_at)),
            'updatedAt' => date(DATE_ISO8601, strtotime($techpack->updated_at)),
            'archivedAt' => date(DATE_ISO8601, strtotime($techpack->archived_at)),
            'completedAt' => is_null($techpack->completed_at)? NULL : $techpack->completed_at->toDateTimeString(),
            'deletedAt' => $techpack->deleted_at ? date(DATE_ISO8601, strtotime($techpack->deleted_at)) : null,
            'lockedAt' => !is_null($techpack->locked_at) ? date(DATE_ISO8601, strtotime($techpack->locked_at)) : null,
            'lockedBy' => !is_null($techpack->locked_by) ? (new MetaUserTransformer)->transform(\App\User::find($techpack->locked_by)) : NULL,
            'unlockedBy' => !is_null($techpack->unlocked_by) ? (new MetaUserTransformer)->transform(\App\User::find($techpack->unlocked_by)) : NULL,
            'unlockedAt' => !is_null($techpack->unlocked_at) ? date(DATE_ISO8601, strtotime($techpack->unlocked_at)) : null,
        ];
    }

    /**
     * Add Techpack Editable permission as per user
     * @param array $techpacks 
     */
    public function isEditable($techpack)
    {
        $role = \App\Role::where('name', 'Edit Access')->first();
        $userIds = is_null($role)? [] : $role->users->lists('id')->toArray();

        if (empty($userIds)) {
            return (
                $techpack->user_id === \Auth::user()->id || 
                \Auth::user()->is_god === true
            ); 
        }
        return (
            $techpack->user_id === \Auth::user()->id || 
            in_array(\Auth::user()->id, $userIds)  || 
            \Auth::user()->is_god === true
        ); 
    }
}
