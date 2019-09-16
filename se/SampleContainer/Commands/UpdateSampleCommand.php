<?php

namespace Platform\SampleContainer\Commands;

use Illuminate\Support\Facades\Auth;
use Platform\App\Helpers\Helpers;

class UpdateSampleCommand
{
    /**
     * @var string
     */
    public $sampleContainerId;

    /**
     * @var string
     */
    public $sampleId;

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
    public $pom;

    /**
     * @var string
     */
    public $actionForward;

    /**
     * @var string
     */
    public $changeLogs;

    public function __construct($data) {
        $this->sampleContainerId = $data['sampleContainerId'];
        $this->sampleId = $data['sampleId'];
        $this->title = $data['title'];
        $this->type = $data['type'];
        $this->authorId = Auth::user()->id;
        $this->image = $data['image'];
        $this->sentDate = Helpers::isSetAndIsNotEmpty($data, 'sentDate') ? $data['sentDate'] : null;
        $this->receivedDate = Helpers::isSetAndIsNotEmpty($data, 'receivedDate') ? $data['receivedDate'] : null;
        $this->vendorId = isset($data['vendorId']) ? $data['vendorId'] : null;
        $this->weightOrQuality = isset($data['weightOrQuality']) ? $data['weightOrQuality'] : null;
        $this->fabricOrContent = isset($data['fabricOrContent']) ? $data['fabricOrContent'] : null;
        $this->pom = isset($data['pom']) ? $data['pom'] : [];
        $this->actionForward = isset($data['actionForward']) ? $data['actionForward'] : null;
        $this->changeLogs = isset($data['changeLog']) ? $data['changeLog'] : [];
    }
}