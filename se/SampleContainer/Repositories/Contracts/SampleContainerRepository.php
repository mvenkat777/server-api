<?php

namespace Platform\SampleContainer\Repositories\Contracts;

interface SampleContainerRepository
{
    /**
     * Define the Sample Container model
     *
     * @return string
     */
	public function model();

    /**
     * Add a new Sample Container
     *
     * @param array $data
     * @return mixed
     */
    public function addSampleContainer(array $data);
}