<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Bill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'origin',
        'reference_month',
        'expiration_at',
        'payment_at',
        'amount',
        'amount_tax',
        'amount_paid',
        'measure_id',
        'created_by',
        'updated_by',
        'file'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'payment_at', 'deleted_at', 'expiration_at', 'reference_month'
    ];

    /**
     * Retorna a unidade de medida da conta
     *
     * @return hasOne
     */
    public function measure() {
        return $this->hasOne(UnitMeasure::class, 'measure_id', 'id');
    }


    /**
     *  realiza uma pesquisa
     * @param  Query $query
     * @param Request $request
     * @return Query
     */
    public function scopeSearch($query, Request $request)
    {

        if ($request->search) {

            $search = trim($request->search);
            $query = $query->select(
                'bills.*',
                'unit_measures.name as measure_name'
            );
            $query->join('unit_measures', 'unit_measures.id', 'measure_id');
            $query->where(function ($query) use ($search) {
                $query->orWhere("origin", "like", '%' . $search . '%');
            });
        }

        return $query;
    }

}
