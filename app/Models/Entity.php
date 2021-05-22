<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Entity extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'refer_id',
        'address',
        'owner_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];



    /**
     * Retorna o relacionamento da entidade com seus usuarios
     *
     * @return belongsTo
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }

    /**
     * Retorna o responsavel pela entidade
     *
     * @return hasOne
     */
    public function owner() {
        return $this->hasOne(User::class, 'id', 'owner_id');
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
            $query = $query->join('users', 'owner_id', 'users.id');
            $query->where(function ($query) use ($search) {
                $query->orWhere("entities.name", "like", '%' . $search . '%');
            });
        }

        return $query;
    }

    /**
     * Retorna as entidades do usuario
     *
     * @return Query
     */
    public function scopeMyEntities($query, Request $request) {

        return $this->scopeSearch($query, $request)->select('entities.*')->join('entity_user', 'entity_id', 'entities.id')->where('user_id', Auth::user()->id);

    }
}
