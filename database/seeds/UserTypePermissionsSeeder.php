<?php

use App\UserTypes;
use Illuminate\Database\Seeder;

class UserTypePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $userTypes = UserTypes::all();
        foreach ($userTypes as $usertype) {
            $usertype->givePermissionTo(['investigation_show', 'investigation_access']);
        }

        //Subject Permissions
        $subject_permissions = UserTypes::whereIn('type_name', [
            env('USER_TYPE_ADMIN'),
            env('USER_TYPE_COMPANY_ADMIN'),
            env('USER_TYPE_ACCOUNTANT'),
            env('USER_TYPE_SECRETARY')
        ])->get();

        foreach ($subject_permissions as $s_usertype) {
            $s_usertype->givePermissionTo(['subject_create', 'subject_edit', 'subject_delete', 'subject_show']);
        }

        //Investigation Assign/Approve
        $inv_permissions = UserTypes::whereIn('type_name', [
            env('USER_TYPE_ADMIN'),
            env('USER_TYPE_COMPANY_ADMIN')
        ])->get();

        foreach ($inv_permissions as $i_usertype) {
            $i_usertype->givePermissionTo(['investigation_approve', 'investigation_assign']);
        }

        //Dashboard Access
        $dashboard_permissions = UserTypes::whereIn('type_name', [
            env('USER_TYPE_INVESTIGATOR'),
            env('USER_TYPE_DELIVERY_BOY'),
            env('USER_TYPE_CLIENT')
        ])->get();

        foreach ($dashboard_permissions as $d_usertype) {
            $d_usertype->givePermissionTo(['dashboard_access']);
        }

        //Document permissions
        $document_permissions = UserTypes::whereIn('type_name', [
            env('USER_TYPE_ADMIN')
        ])->get();

        foreach ($document_permissions as $d_usertype) {
            $d_usertype->givePermissionTo(['documenttypes_create', 'documenttypes_edit', 'documenttypes_show', 'documenttypes_delete']);
        }

        //Ticket permission
        $user_type_admin = UserTypes::where('type_name', env('USER_TYPE_ADMIN'))->first();
        $user_type_admin->givePermissionTo(['tickets_create', 'tickets_edit', 'tickets_show', 'tickets_delete']);
        $user_type_client = UserTypes::where('type_name', env('USER_TYPE_CLIENT'))->first();
        $user_type_client->givePermissionTo(['tickets_create', 'tickets_edit', 'tickets_show']);

        //Invoice permission
        $invoice_permission_user = UserTypes::whereIn('type_name', [
            env('USER_TYPE_ADMIN'),
            env('USER_TYPE_CLIENT')
        ])->get();

        foreach ($invoice_permission_user as $inv) {
            $inv->givePermissionTo(['invoice_list']);
        }

        $invoice_permission_investigator = UserTypes::whereIn('type_name', [
            env('USER_TYPE_ADMIN'),
            env('USER_TYPE_INVESTIGATOR')
        ])->get();

        foreach ($invoice_permission_investigator as $inv) {
            $inv->givePermissionTo(['invoice_list_investigator']);
        }

        $invoice_permission_deliveryboy = UserTypes::whereIn('type_name', [
            env('USER_TYPE_ADMIN'),
            env('USER_TYPE_DELIVERY_BOY')
        ])->get();

        foreach ($invoice_permission_deliveryboy as $inv) {
            $inv->givePermissionTo(['invoice_list_deliveryboy']);
        }
    }
}
