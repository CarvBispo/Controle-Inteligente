<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderedColumns = ['entities.id', 'entities.name',  'entities.created_at'];

        $limit = $request->display_qty ?? 10;
        $sort = $request->sort ?? "desc";
        $column = checkOrderBy($orderedColumns, $request->column, 'entities.id');

        $list = Entity::myEntities($request)->with('owner')->orderBy($column, $sort)->paginate($limit);
        return Inertia::render('Entity/Index', ['entities' => $list]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->form(new Entity());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $this->validation($request);

        if (!$validation->fails()) {

            if ($this->save(new Entity(), $request)) {

                return redirect( route('entities.index'));
            } else {
                return back()->withInput();
            }
        } else {
            return back()->withErrors($validation)->withInput();
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function edit(Entity $entity)
    {
       return $this->form($entity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entity $entity)
    {
        $validation = $this->validation($request);

        if (!$validation->fails()) {

            if ($this->save($entity, $request)) {
                return Redirect::route('entities.index');
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
     * @param  \App\Models\Entity  $entity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entity $entity)
    {
        //
    }

    /**
     * Retorna a view para edição/criação de formulário
     * @param Entity $entity
     * @return Inertia
     */
    private function form(Entity $entity)
    {
        $request = Request::capture();
        $entities = Entity::myEntities($request)->get();
        $entity->address = json_decode($entity->address);
        return Inertia("Entity/Form", ['entity' => $entity, 'entities' => $entities]);
    }


    /**
     * Make validadtion of role crud
     * @param Request $request
     * @return $validator
     */
    private function validation(Request $request)
    {

        $validator = Validator::make($request->all(),
            [
                "name" => "required",
                "email" => ["required" , "unique:entities", "max:255"],
            ],
            ["name.required" => "O campo de nome é obrigatório."],
            ["email.unique" => "O email :email já está relacionado a outra instituicao."],
            ["email.required" => "O campo de Email é obrigatório."]
        );

        $validator->sometimes('id', 'required|numeric', function ($request) {
            return $request->_method == 'PUT';
        });

        return $validator;
    }


    /**
     * store Entity in database
     * @param Entity $entity
     * @param Request $request
     */
    private function save(Entity $entity, Request $request)
    {

        try {
            $user_id = Auth::user()->id;
            $entity->name = $request->name;
            $entity->phone = $request->phone;
            $entity->email = $request->email;
            $entity->refer_id = $request->refer_id;
            $entity->owner_id = $user_id;
            $entity->address = json_encode($request->address);
            $entity->save();

            $entity->users()->attach([$user_id]);

            return true;


        } catch (\Exception $e) {
            dd($e);
            return back()->with([
                'message' => 'Nao foi possivel salvar.',
            ]);
        }
    }

}
