<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TagAssignsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tag_assigns')->delete();
        
        \DB::table('tag_assigns')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tag_type' => 'project',
                'tag_id' => 1,
                'reference_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'tag_type' => 'project',
                'tag_id' => 2,
                'reference_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'tag_type' => 'project',
                'tag_id' => 3,
                'reference_id' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'tag_type' => 'project',
                'tag_id' => 4,
                'reference_id' => 2,
            ),
            4 => 
            array (
                'id' => 5,
                'tag_type' => 'project',
                'tag_id' => 5,
                'reference_id' => 2,
            ),
            5 => 
            array (
                'id' => 6,
                'tag_type' => 'project',
                'tag_id' => 6,
                'reference_id' => 2,
            ),
            6 => 
            array (
                'id' => 7,
                'tag_type' => 'project',
                'tag_id' => 7,
                'reference_id' => 3,
            ),
            7 => 
            array (
                'id' => 8,
                'tag_type' => 'project',
                'tag_id' => 8,
                'reference_id' => 3,
            ),
            8 => 
            array (
                'id' => 9,
                'tag_type' => 'project',
                'tag_id' => 9,
                'reference_id' => 3,
            ),
            9 => 
            array (
                'id' => 10,
                'tag_type' => 'project',
                'tag_id' => 10,
                'reference_id' => 4,
            ),
            10 => 
            array (
                'id' => 11,
                'tag_type' => 'project',
                'tag_id' => 11,
                'reference_id' => 4,
            ),
            11 => 
            array (
                'id' => 12,
                'tag_type' => 'project',
                'tag_id' => 12,
                'reference_id' => 5,
            ),
            12 => 
            array (
                'id' => 13,
                'tag_type' => 'project',
                'tag_id' => 13,
                'reference_id' => 5,
            ),
            13 => 
            array (
                'id' => 14,
                'tag_type' => 'project',
                'tag_id' => 14,
                'reference_id' => 5,
            ),
            14 => 
            array (
                'id' => 15,
                'tag_type' => 'task',
                'tag_id' => 15,
                'reference_id' => 135,
            ),
            15 => 
            array (
                'id' => 16,
                'tag_type' => 'task',
                'tag_id' => 16,
                'reference_id' => 135,
            ),
            16 => 
            array (
                'id' => 17,
                'tag_type' => 'task',
                'tag_id' => 17,
                'reference_id' => 135,
            ),
            17 => 
            array (
                'id' => 18,
                'tag_type' => 'lead',
                'tag_id' => 4,
                'reference_id' => 1,
            ),
            18 => 
            array (
                'id' => 19,
                'tag_type' => 'lead',
                'tag_id' => 5,
                'reference_id' => 1,
            ),
            19 => 
            array (
                'id' => 20,
                'tag_type' => 'lead',
                'tag_id' => 6,
                'reference_id' => 1,
            ),
            20 => 
            array (
                'id' => 21,
                'tag_type' => 'lead',
                'tag_id' => 8,
                'reference_id' => 1,
            ),
            21 => 
            array (
                'id' => 22,
                'tag_type' => 'lead',
                'tag_id' => 16,
                'reference_id' => 1,
            )
        ));
        
        
    }
}