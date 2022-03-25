<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      //  $marcas = Marca::all(); // utilizando o metodo de forma estatica
      $marcas = $this->marca->all();
        return response()->json($marcas, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //$marca =  Marca::create($request->all());
       $regras = [
           'nome' => 'required|unique:marcas',
           'imagem' => 'required'
       ];
       $feedback = [
           'required' => 'O campo :attribute é obrigatório',
           'nome.unique' => 'O nome da marca já existe'
       ];

       $request->validate($regras, $feedback);

       $marca = $this->marca->create($request->all());
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['msg' => 'Nada a mostrar'], 404);
        }
        return response()->json($marca, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $marca->update($request->all());
        $marca = $this->marca->find($id);
        if($marca === null){
           
            return response()->json(['msg' => 'Impossivel realizar a atualização'], 404);
        }
        $marca->update($request->all());

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

      $marca = $this->marca->find($id);

      if($marca === null){

        return response()->json(['msg' => 'Impossivel deletar'], 404);
        
    }

      $marca->delete();

      return response(['msg' => 'A marca foi removida'], 200);

    }
}
