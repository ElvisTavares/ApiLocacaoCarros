<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //autenticação(email senha)
        $credenciais = $request->all(['email', 'password']);
        $token = auth('api')->attempt($credenciais);

        if($token){ //usuario autenticado com sucesso
            return response()->json(['token' => $token]);
        }else{//erro de usuario ou senha
            return response()->json(['erro'=> 'usuario ou senha invalido'], 403);
        }

        //403 - Unauthorized -> nao autorizado
        //404 - forbidden -> proibido(login inavalido)
       
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['msg' =>'Logout foi realisado com sucesso']);
    }

    public function refresh()
    {
        $token = auth('api')->refresh(); //cliente encaminhe um jwt valido
        return response()->json(['token' => $token]);
    }

    public function me()
    {
        dd(auth()->user());
    }
}
