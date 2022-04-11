<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules()
    {
        return [
            'nome' => 'required|unique:marcas,nome,'. $this->id.'|min:3', //para fazer o update ele vai pesquisar todos os ids menos esse que foi especificado
            'imagem' => 'required|file|mimes:png,jpeg'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'imagem' => 'O arquivo deve ser uma imagem do tipo PNG ou JPEG',
            'nome.unique' => 'O nome da marca já existe',
            'nome.min' => 'O nome deve ter no minimo 3 caracteres'
        ];
    }

    public function modelos()
    {
        //uma marca possui varios modelos
        return $this->hasMany('App\Models\Modelo');
    }
}
