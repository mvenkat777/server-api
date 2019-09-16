<?php

namespace Platform\Line\Transformers;

use App\Line;
use App\Style;
use App\Techpack;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Platform\Customer\Transformers\MetaCustomerTransformer;
use Platform\Line\Transformers\StyleTransformer;
use Carbon\Carbon;

class SalesStreamTransformer extends TransformerAbstract
{ 
   public function __construct()
   {
       $this->manager = new Manager();
   }

   public function transform($style)
   {
        $cust = (new MetaCustomerTransformer())->transform($style->line->customer);
        $customer = [
            'id' => $cust['customerId'],
            'name' => $cust['name']
        ];


        $line = [
            'id' => $style->line->id,
            'name' => $style->line->name,
            'soTargetDate' => $style->line->so_target_date->toDateString(),
            'deliveryTargetDate' => $style->line->delivery_target_date->toDateString(),
            'updatedAt' => $style->updated_at->toDateString(),
            'createdAt' => $style->created_at->toDateString()
        ];
        
       if (!is_null($style->development) ) {
           foreach ($style->development as $key => $development) {

               if ($development->name == 'Visual Line Plan approved') {

                   $printApprDate = $development->pivot['approved_at'];

                } elseif ($development->name == 'Fabrics approved') {

                   $fabricApprDate = $development->pivot['approved_at'];

                } elseif($development->name == 'Lab dips approved') {

                   $labDipApprDate = $development->pivot['approved_at'];

                } elseif ($development->name == 'Fit sample approved') {

                   $fitApprDate = $development->pivot['approved_at'];
                }  
                elseif($development->name == 'Strike-offs approved'){
                  $strikeApprDate = $development->pivot['approved_at'];
                } 
           }
       }

       if (!is_null($style->production)) {
           foreach ($style->production as $key => $production) {
               if ($production->name == 'PP approved') {
                   $ppApprDate = $production->pivot['approved_at'];
                } 
            }
       }
       // if(!is_null($style->sampleContainer)) {
       //     foreach ($style->sampleContainer->samples as $sample) {
             
       //          if(preg_match('/sales/', strtolower($sample->title)) || preg_match('/sales/', strtolower($sample->type)))
       //          {
       //              $sampleData = [
       //                 'receivedDate' => $sample->received_date,
       //                 'type' => 'sales'
       //              ];

       //              $salesSample[] = $sampleData;

       //          } 
       //          elseif(preg_match('/pp/', strtolower($sample->title)) || preg_match('/pp/', strtolower($sample->type))) {
       //              $sampleData = [
       //                 'receivedDate' => $sample->received_date,
       //                 'type' => 'pp'
       //              ];

       //              $ppSample[] = $sampleData;
       //          }
       //      }
       //  }

        $style = [
            'id' => $style->id,
            'code' => $style->code,
            'name' => $style->name,
            'updatedAt' => $style->updated_at->toDateString(),
            'createdAt' => $style->created_at->toDateString()
        ];

        $data =  [
           'customer' => $customer,
           'line' => $line,
           'style' => $style,
           'fitApprDate' => isset($fitApprDate)? $fitApprDate : NULL,
           'fabricApprDate' =>isset($fabricApprDate)? $fabricApprDate : NULL,
           'labDipApprDate' =>isset($labDipApprDate)? $labDipApprDate : NULL,
           'printApprDate' =>isset($printApprDate)? $printApprDate : NULL,
           // 'salesSample' => isset($salesSample)? $salesSample : [] ,
           'ppApprDate' => isset($ppApprDate)? $ppApprDate : NULL,
        ];

       return $data;
    }
}
