<?php

namespace Platform\Help\Commands;

class UpdateHelpBySlugCommand {

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $title;
    
    /**
     * @var string
     */
    public $app;
    
    public function __construct($data, $slug)
    {
        $this->slug = $slug;
        $this->title = isset($data['title'])?  $data['title'] : NULL;
        $this->description = isset($data['description'])?  $data['description'] : NULL;
        $this->feedback = !isset($data['feedback'])? NULL : $data['feedback'];
        $this->quality = !isset($data['quality'])? 0 : $data['quality'];
    
    }

} 