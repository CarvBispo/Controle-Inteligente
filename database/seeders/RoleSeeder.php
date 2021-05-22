<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table("roles")->delete();

        //Grupo de admin
        $admin = new Role();
        $admin->name = "Administrador";
        $admin->save();

        $adminPermissions = array_column(Permission::select("id")->get()->toArray(), "id");
        $admin->permissions()->attach($adminPermissions);


        $this->command->info('roles inserted.');
    }
}
