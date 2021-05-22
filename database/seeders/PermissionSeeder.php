<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            'bills' => 'Contas',
            'entities' => 'Instituições',
            'roles' => 'Grupos de Usuarios',
        ];

        $roles = [
            'index' => 'Visualizar',
            'create' => 'Criar',
            'update' => 'Alterar',
            'destroy' => 'Remover',
        ];

        $data = [];

        foreach ($pages as $k => $v) {
            foreach ($roles as $i => $j) {

                array_push($data, [
                    'section' => $v,
                    'name' => $j,
                    'permission' => $k . '.' . $i
                ]);
            }
        }

        array_push($data, [
            'section' => "Dashboard",
            'name' => "Visualizar",
            'permission' => 'dashboard.index'
        ]);

        Permission::insert($data);

        $this->command->info('Regras inseridas!');
    }
}
