<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('permissions')->truncate();
        $permissions = [
            [
                'id'         => '1',
                'name'      => 'client_create',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '2',
                'name'      => 'client_edit',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '3',
                'name'      => 'client_show',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '4',
                'name'      => 'client_delete',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '5',
                'name'      => 'client_access',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '6',
                'name'      => 'investigator_create',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '7',
                'name'      => 'investigator_edit',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '8',
                'name'      => 'investigator_show',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '9',
                'name'      => 'investigator_delete',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '10',
                'name'      => 'investigator_access',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '11',
                'name'      => 'deliveryboy_create',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '12',
                'name'      => 'deliveryboy_edit',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '13',
                'name'      => 'deliveryboy_show',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '14',
                'name'      => 'deliveryboy_delete',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '15',
                'name'      => 'deliveryboy_access',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '16',
                'name'      => 'contact_access',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '17',
                'name'      => 'contact_create',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '18',
                'name'      => 'contact_edit',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '19',
                'name'      => 'contact_show',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '20',
                'name'      => 'contact_delete',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '21',
                'name'      => 'staff_access',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '22',
                'name'      => 'staff_create',
                'guard_name'      => 'web',
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'id'         => '23',
                'name'      => 'staff_edit',
                'guard_name'      => 'web',
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'id'         => '24',
                'name'      => 'staff_show',
                'guard_name'      => 'web',
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'id'         => '25',
                'name'      => 'staff_delete',
                'guard_name'      => 'web',
                'created_at' =>  now(),
                'updated_at' =>  now(),
            ],
            [
                'id'         => '26',
                'name'      => 'product_access',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '27',
                'name'      => 'product_create',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '28',
                'name'      => 'product_edit',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '29',
                'name'      => 'product_show',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '30',
                'name'      => 'product_delete',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '31',
                'name'      => 'investigation_access',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '32',
                'name'      => 'investigation_create',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '33',
                'name'      => 'investigation_edit',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '34',
                'name'      => 'investigation_show',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '35',
                'name'      => 'investigation_delete',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '36',
                'name'      => 'permission_access',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '37',
                'name'      => 'permission_create',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '38',
                'name'      => 'permission_show',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '39',
                'name'      => 'permission_delete',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '40',
                'name'      => 'usertype_access',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '41',
                'name'      => 'usertype_show',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '42',
                'name'      => 'usertype_edit',
                'guard_name'      => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '43',
                'name'      => 'assign_investigation_to_investigator',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '44',
                'name'      => 'investigation_approve',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '45',
                'name'      => 'subject_create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '46',
                'name'      => 'subject_edit',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '47',
                'name'      => 'subject_delete',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '48',
                'name'      => 'subject_show',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '49',
                'name'      => 'investigation_assign',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '50',
                'name'      => 'dashboard_access',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '51',
                'name'      => 'documenttypes_create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '52',
                'name'      => 'documenttypes_edit',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '53',
                'name'      => 'documenttypes_show',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '54',
                'name'      => 'documenttypes_delete',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '55',
                'name'      => 'tickets_create',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '56',
                'name'      => 'tickets_edit',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '57',
                'name'      => 'tickets_show',
                'guard_name'  => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '58',
                'name'      => 'tickets_delete',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '59',
                'name'      => 'invoice_list',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '60',
                'name'      => 'invoice_list_investigator',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => '61',
                'name'      => 'invoice_list_deliveryboy',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        /*foreach ($permissions as $value) {
            Permission::create($value);
        }*/
        Permission::insert($permissions);
    }
}
