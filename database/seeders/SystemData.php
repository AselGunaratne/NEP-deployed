<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        

        DB::table('status')->insert(
        array('id' => '1','type' => 'Appli3cation made successfully','created_at' => NULL,'updated_at' => NULL,'status' => '7','deleted_at' => NULL),
        array('id' => '2','type' => 'Forwarded to the organization','created_at' => NULL,'updated_at' => NULL,'status' => '7','deleted_at' => NULL),
        array('id' => '3','type' => 'Assigned for approval','created_at' => NULL,'updated_at' => NULL,'status' => '7','deleted_at' => NULL),
        array('id' => '4','type' => 'Reviewing for approval','created_at' => NULL,'updated_at' => NULL,'status' => '7','deleted_at' => NULL),
        array('id' => '5','type' => 'Approved','created_at' => NULL,'updated_at' => NULL,'status' => '7','deleted_at' => NULL),
        array('id' => '6','type' => 'Rejected','created_at' => NULL,'updated_at' => NULL,'status' => '7','deleted_at' => NULL),
        array('id' => '7','type' => 'System Data','created_at' => NULL,'updated_at' => NULL,'status' => '7','deleted_at' => NULL),
        array('id' => '8','type' => 'Cancelled by Requester','created_at' => NULL,'updated_at' => NULL,'status' => '7','deleted_at' => NULL)
        );

        
    }
}
