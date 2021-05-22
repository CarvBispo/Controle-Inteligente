<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderedColumns = ['id', 'name',  'created_at'];

        $limit = $request->display_qty ?? 10;
        $sort = $request->sort ?? "desc";
        $column = checkOrderBy($orderedColumns, $request->column, 'id');

        $list = Role::search($request)->orderBy($column, $sort)->paginate($limit);
        return Inertia::render('Role/Index', ['roles' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->form(new Role());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if ( $role) {
            return $this->form($role);
        } else {
           return false;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validation = $this->validation($request);

        if (!$validation->fails()) {

            if ($this->save($role, $request)) {
                return Redirect::route('roles.index');
            } else {
                return back()->withInput();
            }
        } else {

            return back()->withErrors($validation)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }


     /**
     * Retorna a view para edição/criação de formulário
     * @param Role $group
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    private function form(Role $role)
    {

        //Obtem todas as regras
        $permissions = Permission::orderBy('section')->orderBy('name')->get();

        //agrupa a collection por seção
        $permissions = $permissions->groupBy('section');
        $role->load('permissions');

        return Inertia("Role/Form", ['role' => $role, 'permissions' => $permissions]);
    }


    /**
     * Make validadtion of role crud
     * @param Request $request
     * @return $validator
     */
    private function validation(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "name" => "required",
        ], [
            "name.required" => "O campo de nome é obrigatório.",
        ]);

        $validator->sometimes('id', 'required|numeric', function ($request) {
            return $request->_method == 'PUT';
        });

        return $validator;
    }


    /**
     * store Role in database
     * @param Role $role
     * @param Request $request
     */
    private function save(Role $role, Request $request)
    {

        try {
            DB::transaction(function () use ($request, $role) {
                $role->name = $request->name;
                $role->save();
                $permissions = collect($request->permissions)->map(function ($item) {
                    return $item['id'];
                });

                $role->permissions()->detach();
                $role->permissions()->attach($permissions);
            });
            return true;
        } catch (\Exception $e) {
            dd($e);
            return back()->with([
                'message' => 'Nao foi possivel salvar.',
            ]);
        }
    }

}
