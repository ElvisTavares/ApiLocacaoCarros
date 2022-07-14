<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MarcaRepository;

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
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);
        if($request->has('atributos_modelos')){
            $atributos_modelos = 'modelos:id,' . $request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        }else{
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if($request->has('filtro'))
        {
            $marcaRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $marcaRepository->selectAtributos($request->atributos);
        }

    
       // return response()->json($marcaRepository->getResultado(), 200);
        return response()->json($marcaRepository->getResultadoPaginado(3), 200);
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
  

       $request->validate($this->marca->rules(), $this->marca->feedback());
      
       // meios de retornar o que vem do requst (text)
        //dd($request->nome);
        //dd($request->get('nome));
        //dd($request->input('nome));
        //dd($request->file('imagem')); // ver se esta vindo parametros da imagem
        $imagem = $request->file('imagem');
        $image_urn = $imagem->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $image_urn
        ]);
        
        
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
        $marca = $this->marca->with('modelos')->find($id); // no with e o medodo que esta no model
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

       if($request->method() == 'PATCH'){
           $regrasDinamicas = array();

          // $teste = '';

           //percorrendo todas as regras definidas
           foreach($marca->rules() as $input => $regra){
         //       $teste .= 'Input: ' . $input. 'Regra: ' .$regra. '<br>'; //teste para imprimir as regras 
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                }
           }
          
           $request->validate($regrasDinamicas, $marca->feedback());
        //   return $teste;
       }else{
        $request->validate($marca->rules(), $marca->feedback());
       }

       //remove o arquivo caso um novo arquivo tenha sido enviado no request
       if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
       }

       $imagem = $request->file('imagem');
       $imagem_urn = $imagem->store('imagens', 'public');

     $marca->fill($request->all());
     $marca->imagem = $imagem_urn;
     
       
     $marca->save();
     /*    $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]); */

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
    
    Storage::disk('public')->delete($marca->imagem);

      $marca->delete();

      return response(['msg' => 'A marca foi removida'], 200);

    }
}
