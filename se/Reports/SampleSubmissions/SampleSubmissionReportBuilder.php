<?php

namespace Platform\Reports\SampleSubmissions;

use App\SampleSubmission;
use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Platform\App\Exceptions\SeException;
use Platform\Reports\AbstractReportBuilder;
use Platform\SampleSubmission\Transformers\MetaSampleSubmissionTransformer;

class SampleSubmissionReportBuilder extends AbstractReportBuilder
{
    protected $fractal;

    public function __construct()
    {
        parent::__construct();
        $this->fractal = new Manager();
    }

    public function model()
    {
        return new SampleSubmission;
    }

    public function filter($params)
    {
        $schema = $this->model()->transformSchema();
        if (isset($params['search']) && is_array($params['search']) && count($params['search']) > 0) {
            foreach ($params['search'] as $criteria => $value) {
                if ($criteria == 'Date') {
                    if (!isset($value[1])) {
                        $value[1] = '';
                    }
                    $this->searchDates($value[0], $value[1]);
                } else {
                    $this->search($value, $criteria, $schema['filterable'], $schema['operation']);
                }
            }
        }

        $response =  $this->basicSort($params, $schema);
        $total_results = $this->countResultData();
        $response = $this->basicPaginate($params, $response);

        if (empty($response)) {
            $response = $this;
        }
        $techpacks = $response->execute();
        $techpacks = $this->applyTransformer($techpacks, new MetaSampleSubmissionTransformer);

        return [
            'schema' =>$this->model()->reportSchema(),
            'totalresult' => $total_results,
            'samples'=> $techpacks
         ];
    }

    public function applyTransformer($data, $transformObj)
    {
        $transData = new Collection($data, $transformObj);
        return $this->fractal->createData($transData)->toArray()['data'];
    }
}
