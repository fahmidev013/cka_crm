<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('projects')->delete();
        
        \DB::table('projects')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'CRM-Expert',
                'detail' => '<p><i>Project</i> Description is a<strong> formally written declaration of the </strong><i><strong>project</strong></i><strong> </strong>and its idea and context to explain the goals and objectives to be reached, the business need and problem to be addressed, potentials pitfalls and challenges, approaches and<strong> execution methods, resource estimates, people and organizations involved</strong>.</p>',
                'project_type' => 'product',
                'customer_id' => NULL,
                'user_id' => 1,
                'project_status_id' => 2,
                'charge_type' => 1,
                'begin_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'due_date' => NULL,
                'improvement' => 85,
                'improvement_from_task' => 1,
                'completed_date' => NULL,
                'cost' => '129.00',
                'per_hour_project_scale' => NULL,
                'estimated_hours' => '50.00',
                'created_at' => '2020-04-13 20:23:24',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Pay-Money',
                'detail' => '<p><i>Project</i> Description is a formally written declaration of the <i>project</i> and its idea and context to explain the goals and objectives to be reached, the business need and<strong> problem to be addressed, potentials pitfalls and challenges, approaches and execution methods, resource estimates, people and organizations involved</strong></p>',
                'project_type' => 'product',
                'customer_id' => NULL,
                'user_id' => 1,
                'project_status_id' => 3,
                'charge_type' => 1,
                'begin_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'due_date' => '2020-10-31',
                'improvement' => 98,
                'improvement_from_task' => 1,
                'completed_date' => NULL,
                'cost' => '158.00',
                'per_hour_project_scale' => NULL,
                'estimated_hours' => '350.00',
                'created_at' => '2020-04-13 20:26:53',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'vRent',
                'detail' => '<p><i>Project</i> Description is a formally written declaration of the <i>project</i> and its idea and context to explain the goals and objectives to be reached, the business need and problem to be addressed, potentials pitfalls and challenges, approaches and execution methods, resource estimates, people and organizations involved</p>',
                'project_type' => 'product',
                'customer_id' => NULL,
                'user_id' => 1,
                'project_status_id' => 5,
                'charge_type' => 1,
                'begin_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'due_date' => \Carbon::now()->subDays(2)->toDateTimeString(),
                'improvement' => 98,
                'improvement_from_task' => 1,
                'completed_date' => NULL,
                'cost' => '90.00',
                'per_hour_project_scale' => NULL,
                'estimated_hours' => '320.00',
                'created_at' => '2020-04-13 20:30:10',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Inventory with POS',
                'detail' => '<p><i>Project</i> Description is a formally written declaration of the <i>project</i> and its idea and context to explain the goals and objectives to be reached, the business need and problem to be addressed, potentials pitfalls and challenges, approaches and execution methods, resource estimates, people and organizations involved</p>',
                'project_type' => 'in_house',
                'customer_id' => NULL,
                'user_id' => 1,
                'project_status_id' => 1,
                'charge_type' => 2,
                'begin_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'due_date' => \Carbon::now()->subDays(3)->toDateTimeString(),
                'improvement' => 7,
                'improvement_from_task' => 1,
                'completed_date' => NULL,
                'cost' => NULL,
                'per_hour_project_scale' => '5.00',
                'estimated_hours' => '600.00',
                'created_at' => '2020-04-13 20:31:41',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'BillMaster',
                'detail' => '<p><i>Project</i> Description is a formally written declaration of the <i>project</i> and its idea and context to explain the goals and objectives to be reached, the business need and problem to be addressed, potentials pitfalls and challenges, approaches and execution methods, resource estimates, people and organizations involved.</p>',
                'project_type' => 'customer',
                'customer_id' => 2,
                'user_id' => 1,
                'project_status_id' => 2,
                'charge_type' => 2,
                'begin_date' => \Carbon::now()->subDays(7)->toDateTimeString(),
                'due_date' => \Carbon::now()->subDays(1)->toDateTimeString(),
                'improvement' => 90,
                'improvement_from_task' => 1,
                'completed_date' => NULL,
                'cost' => NULL,
                'per_hour_project_scale' => '30.00',
                'estimated_hours' => '600.00',
                'created_at' => '2020-04-13 20:34:20',
            ),
        ));
        
        
    }
}