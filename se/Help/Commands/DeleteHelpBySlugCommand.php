<?php

namespace Platform\Help\Commands;

class DeleteHelpBySlugCommand {

	/**
	 * @var string
	 */
    public $slug;

    function __construct($slug)
    {
        $this->slug = $slug;
    }
}