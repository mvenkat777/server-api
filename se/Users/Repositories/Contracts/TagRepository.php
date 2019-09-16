<?php

namespace Platform\Users\Repositories\Contracts;

interface TagRepository
{
    public function model();

    public function createTag($data);

}