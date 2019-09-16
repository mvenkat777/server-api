<?php

namespace Platform\Techpacks\Validators;

use Platform\App\Validation\DataValidator;

class CutTicketCommentValidator extends DataValidator
{
    public function setCreationRules() {
        $this->rules = [
            'techpackId' => 'required|exists:techpacks,id',
            'comment' => 'required',
            'commentedBy' => 'required|exists:users,id',
        ];

        return $this;
    }	

}
