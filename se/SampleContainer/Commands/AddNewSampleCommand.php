<?php

namespace Platform\SampleContainer\Commands;

use Illuminate\Support\Facades\Auth;
use Platform\App\Helpers\Helpers;

class AddNewSampleCommand
{
    /**
     * @var string
     */
    public $sampleContainerId;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $authorId;

    /**
     * @var json
     */
    public $image;

    /**
     * @var date
     */
    public $sentDate;

    /**
     * @var date
     */
    public $receivedDate;

    /**
     * @var string
     */
    public $vendorId;

    /**
     * @var string
     */
    public $weightOrQuality;

    /**
     * @var string
     */
    public $fabricOrContent;

    /**
     * @var string
     */
    public $actionForward;

	public function __construct($data) {
        $this->sampleContainerId = $data['sampleContainerId'];
        $this->title = $data['title'];
        $this->type = $data['type'];
        $this->authorId = isset(Auth::user()->id) ? Auth::user()->id : null;
        $this->image = $data['image'];
        $this->sentDate = Helpers::isSetAndIsNotEmpty($data, 'sentDate') ? $data['sentDate'] : null;
        $this->receivedDate = Helpers::isSetAndIsNotEmpty($data, 'receivedDate') ? $data['receivedDate'] : null;
        $this->vendorId = isset($data['vendorId']) ? $data['vendorId'] : null;
        $this->weightOrQuality = isset($data['weightOrQuality']) ? $data['weightOrQuality'] : null;
        $this->fabricOrContent = isset($data['fabricOrContent']) ? $data['fabricOrContent'] : null;
        $this->actionForward = isset($data['actionForward']) ? $data['actionForward'] : null;
	}
}