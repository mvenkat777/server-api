<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ApiController;
use App\Http\Requests;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Platform\App\Exceptions\SeException;
use Platform\Reports\UserReportBuilder;
use Platform\Users\Transformers\UserTagTransformer;
use Platform\Users\Transformers\UserTransformer;

class ReportController extends ApiController
{
    protected $mapping = [
        'user' => 'Platform\Reports\Users\UserReportBuilder',
        'task' => 'Platform\Reports\Tasks\TaskReportBuilder',
        'customer' => 'Platform\Reports\Customers\CustomerReportBuilder',
        'vendor' => 'Platform\Reports\Vendors\VendorReportBuilder',
        'order' => 'Platform\Reports\Orders\OrderReportBuilder',
        'shipment' => 'Platform\Reports\Shipments\ShipmentReportBuilder',
        'tna' => 'Platform\Reports\TNA\TNAReportBuilder',
        'payment' => 'Platform\Reports\Payments\PaymentReportBuilder',
        'techpack' => 'Platform\Reports\Techpacks\TechpackReportBuilder',
        'sample' => 'Platform\Reports\SampleSubmissions\SampleSubmissionReportBuilder',
    ];

    public function index($entity, Request $request)
    {
        $entity = strtolower($entity);
        if (isset($this->mapping["$entity"])) {
            $className = $this->mapping["$entity"];
            $ReportObj = new $className();
            $ReportObj->setEntity($entity);

            $result = $ReportObj->filter($request->all());
            return $this->respondWithArray([
                'data' => $result
            ]);
        } else {
            throw new SeException('Report Builder class not found for given entity', 403, 721100);
        }
    }
}
