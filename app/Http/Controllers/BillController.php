<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

/**
 * Controller de gerenciamento de contas
 *
 * @author Lucas Souza <lucas@datapage.com.br>
 * @since 22/05/2021
 * @version 1.0.0
 */
class BillController extends Controller
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

        $list = Bill::search($request)->orderBy($column, $sort)->paginate($limit);
        return Inertia::render('Bill/Index', ['bills' => $list]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->form(new Bill());
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

            if ($this->save(new Bill(), $request)) {

                return redirect( route('bills.index'));
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
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function edit(Bill $bill)
    {
        return $this->form($bill);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
         $validation = $this->validation($request);

        if (!$validation->fails()) {

            if ($this->save($bill, $request)) {
                return Redirect::route('bills.index');
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
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        //
    }


     /**
     * Retorna a view para edição/criação de formulário
     * @param Role $group
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    private function form(Bill $bill)
    {
        return Inertia("Bill/Form", ['bill' => $bill]);
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
     * store Bill in database
     * @param Bill $bill
     * @param Request $request
     */
    private function save(Bill $bill, Request $request)
    {

        try {
            return $bill->save();
        } catch (\Exception $e) {
            dd($e);
            return back()->with([
                'message' => 'Nao foi possivel salvar.',
            ]);
        }
    }
}
