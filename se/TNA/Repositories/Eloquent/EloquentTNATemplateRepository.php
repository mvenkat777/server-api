<?php

namespace Platform\TNA\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\TNA\Repositories\Contracts\TNATemplateRepository;

class EloquentTNATemplateRepository extends Repository implements TNATemplateRepository 
{

    /**
     * To get the model
     */
	public function model(){
		return 'Platform\TNA\Models\TNATemplate';
	}

    /**
     * Get milestone by type i.e: tnaTemplate or milestoneTemplate
     *
     * @param Boolean $isMilestoneTemplate
     * @return collection
     */
    public function getByType($isMilestoneTemplate)
    {
        return $this->model->where('is_milestone_template', $isMilestoneTemplate)->get();
    }

    /**
     * Store the template
     *
     * @param array $data
     * @return Model
     */
    public function saveTemplate($data)
    {
        $dbData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'creator_id' => $data['creator_id'],
            'is_milestone_template' => $data['isMilestoneTemplate'],
            'data' => json_encode($data['data']),
            'count' => $data['count']
        ];

        return $this->create($dbData);
    }

    /**
     * Increment count of template
     *
     * @param integer $templateId
     * @param integer $value
     */
    public function updateTemplateCount($templateId, $value = 1)
    {
        $template = $this->model->find($templateId);

        try{
            $template->count = (int)($template->count) + $value;
            $template->save();
        } catch(\Exception $e) {
        }
    }

    /**
     * Delete template
     *
     * @param integer $templateId
     */
    public function deleteTemplate($templateId)
    {
        return $this->delete($templateId);
    }

}
