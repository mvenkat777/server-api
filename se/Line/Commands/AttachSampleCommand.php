<?php

namespace Platform\Line\Commands;

class AttachSampleCommand 
{
        /**
         * @var array
         */
        public $lineId;

        /**
         * @var array
         */
        public $styleId;

        /**
         * @var array
         */
        public $data;

	public function __construct($lineId, $styleId, $data) {
            $this->lineId = $lineId;
            $this->styleId = $styleId;
            $this->data = $data;
	}

}
