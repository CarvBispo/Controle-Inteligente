<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Classs para os grupos de usuarios
 *
 * @author Lucas Souza <lucas@datapage.com.br>
 * @since 22/05/2021
 * @version 1.0.0
 */
class Role extends Model
{
    use HasFactory;

     private $menu = [
            ["label" => "Administracao", "list" => [
                ["label" => "Dashboard","name" => 'dashboard', "url" => 'dashboard',"icon" => "fas fa-tachometer-alt", "permission" => "dashboard.index"],
                ["label" => "Contas", "name" => 'bills.index', "url" => 'bills', "icon" => "fas fa-book", "permission" => "bills.index"],
                ["label" => "Instituições", "name" => 'entities.index', "url" => 'entities', "icon" => "fas fa-book", "permission" => "entities.index"],
                ["label" => "Grupos de Usuários", "name" => 'roles.index', "url" => 'roles',"icon" => "fas fa-user-shield", "permission" => "roles.index"],
            ]]
        ];


    protected $dateFormat = 'd/m/Y';
    /**
     * Retorna os usuários do grupo.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'roles_users', 'role_id');
    }


    /**
     * Retorna as regras do grupo.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'role_permission', 'role_id',  'permission_id');
    }

    /**
     * Verifica se o usuário tem acesso a regra
     * @param  $permission
     * @return boolean
     */
    public function hasPermission($permission)
    {
        //obtem as regras do grupo do usuário
        $permissions = array_column($this->permissions->toArray(), 'permission');

        // retorna se o usuário tem essa regra
        return  in_array($permission, $permissions);
    }

    /**
     * Retorna o menu do usuário
     */
    public function menu()
    {
        return $this->getMenu($this->menu);
    }


    /**
     * Obtem o menu recursivamente através das permissões do grupo do usuário
     * @param array $menu
     * @return array $userMenu
     */
    private function getMenu($menu)
    {

        $userMenu = [];

        foreach ($menu as $m) {

            if (isset($m['list']) && count($m['list']) > 0) {
                $list = $this->getMenu($m['list']);

                if (count($list)) {

                    $m['list'] = $list;
                    array_push($userMenu, $m);
                }
            } else {
                if ($this->hasPermission($m['permission'])) {
                    array_push($userMenu, $m);
                }
            }
        }

        return $userMenu;
    }

    /**
     *  Pesquisa por um grupo de usuários
     * @param  Query $query
     * @param Request $request
     * @return Query
     */
    public function scopeSearch($query, Request $request)
    {

        if ($request->search) {

            $search = trim($request->search);

            $query->where(function ($query) use ($search) {

                $query->orWhere("roles.name", "like", '%' . $search . '%');
            });
        }

        return $query;
    }
}
