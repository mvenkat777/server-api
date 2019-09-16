<?php

use Illuminate\Database\Seeder;

class TNAItemPresetSeeder extends Seeder
{
	
	function run()
	{
          DB::table('tna_item_presets')->delete();
        /*
          $this->call('TNAItemDepartmentSeeder');
         */

          $departments = \Platform\TNA\Models\TNAItemDepartment::lists('id', 'department')->toArray();
          $sales = $departments['Sales Rep'];
          $PDLead = $departments['PD Lead'];
          $SPLead = $departments['Sourcing & Production Lead'];
          $regionalMerchandiser = $departments['Regional Merchandiser'];
          /*
          $sales = $departments['Sales person'];
          $vpProductDevelopment = $departments['VP Product Development'];
          $directorProductDevelopment = $departments['Director Product Development'];
          $productDeveloper = $departments['Product Developer'];
          $designer = $departments['Designer'];
          $patternmaker = $departments['Patternmaker'];
          $samplemaker = $departments['Samplemaker'];
          $materialsCoordinator = $departments['Materials Coordinator'];
          $sourcingProductionDirector = $departments['Sourcing and Production Director (USA)'];
          $sourcingMaterialManager = $departments['Sourcing Material Manager (USA)'];
          $qualityRole = $departments['Quality Role'];
          $regionalMerchandiser = $departments['Regional Merchandiser (ASIA)'];
          $regionalMaterialManager = $departments['Regional Material Manager (ASIA)'];
          $directorFinance = $departments['Director Finance'];
           */

          DB::insert(" INSERT INTO tna_item_presets (id, title, description, representor, task_days, is_milestone, department_id, is_parallel, planned_date) VALUES
                    (1, 'VLP CREATED', 'VLP CREATED', 'sedev@sourceeasy.com', 14, true, $PDLead, false, '2017-05-16 00:00:00'),
                    (2, 'VLP APPROVED', 'VLP APPROVED', 'sedev@sourceeasy.com', 7, true, $sales, false, '2017-05-19 00:00:00'),
                    (3, 'FIRST PROTO SENT', 'FIRST PROTO SENT', 'sedev@sourceeasy.com', 28, true, $PDLead, false, '2017-05-21 00:00:00'),
                    (4, 'SECOND PROTO SENT', 'SECOND PROTO SENT', 'sedev@sourceeasy.com', 14, true, $PDLead, false, '2017-05-23 00:00:00'),
                    (5, 'FABRICS/MATERIAL Approved', 'FABRICS/MATERIAL Approved', 'sedev@sourceeasy.com', 0, true, $PDLead, false, '2017-05-25 00:00:00'),
                    (6, 'PROTO APPROVED', 'PROTO APPROVED', 'sedev@sourceeasy.com', 7, true, $PDLead, false, '2017-05-27 00:00:00'),
                    (7, 'CUSTOMER PO & DEPOSIT RECEIVED', 'CUSTOMER PO & DEPOSIT RECEIVED', 'sedev@sourceeasy.com', 7, true, $sales, false, '2017-05-29 00:00:00'),
                    (8, 'PP SAMPLE APPROVED', 'PP SAMPLE APPROVED', 'sedev@sourceeasy.com', 28, true, $SPLead, false, '2017-05-29 00:00:00'),
                    (19, 'PRODUCTION DONE', 'PRODUCTION DONE', 'sedev@sourceeasy.com', 35, true, $regionalMerchandiser, false, '2017-05-29 00:00:00'),
                    (10, 'GARMENT DELIVERED', 'GARMENT DELIVERED', 'sedev@sourceeasy.com', 7, true, $regionalMerchandiser, false, '2017-05-31 00:00:00')
              ");

		$this->command->info('TNA Item Preset seeded!');
	}
}
