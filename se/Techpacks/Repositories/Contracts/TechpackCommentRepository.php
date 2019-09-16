<?php

namespace Platform\Techpacks\Repositories\Contracts;

use Platform\Techpacks\Commands\AddTechpackCommentCommand;

interface TechpackCommentRepository
{
    /**
     * @return mixed
     */
    public function model();

    public function addComment(AddTechpackCommentCommand $command);
}
