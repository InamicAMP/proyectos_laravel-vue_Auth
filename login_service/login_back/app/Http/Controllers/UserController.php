<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        if($user->rol == 'ADMIN'){ // es 'ADMIN'
            $users = User::orderby('name', 'asc')->get();
        }else{ //Si no es administrador 
            $users = User::where('id',$user)->id->orderby('name','asc')->get();
        }
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|integer',
            'rol' => 'required|string|max:100|min:2',
            'password' => 'required|string|max:255|min:8',
            'email' => 'required|email|unique:users,email',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener al menos :max caracteres.',
            'email.required' => 'El correo ya está registrado.',
            'email.unique' => 'El correo ya está registrado.',
            'email.email' => 'El correo no tiene el formato correcto.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña es de mínimo :min caracteres.',
            'rol.required' => 'El campo rol es obligatorio',
            'rol.max' => 'El rol no puede tener más de :max caracteres.',
        ]);
        if($validator-> fails()){
            return response()->json (['error' => 'No encontrado'], Response::HTTP_BAD_REQUEST);
        }
        $validatedData = [
            'name' => strip_tags($request->input('name')),
            'email' => strip_tags($request->input('email')),
            'rol' => htmlspecialchars($request->input('rol')),
            'email_verified_at' => empty(htmlspecialchars($request->input('emal_verified_at')))?null:htmlspecialchars($request->input('emal_verified_at')),
        ];
        $new = User::create($validatedData);
        if(!$new){
            return response()->json(['error' => 'No se logró crear'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json($new, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = User::find($id);
        if(!$model){
            return response()->json(['error' => 'No encontrado'], Response::HTTP_BAD_REQUEST);
        }
        return response()->json($model);
    }
    public function update(Request $request,string $id)
    {
        $model = User::find($id);
        if(!$model){
            return response()->json(['error' => 'No encontrado'], Response::HTTP_BAD_REQUEST);
        }
        $validateData = [
            'name' => strip_tags($request->input('name')),
            'email' => strip_tags($request->input('email')),
            'rol' => strip_tags($request->input('rol')),
            //Ejemplo para datos que pueden ir vacíos
            //'empresa_id' => empy(htmlspecialchars($request->input('empresa_id')))?null:htmlspecialchars($request->input('empresa_id'))>,
            'email_verified_at' => empty(htmlspecialchars($request->input('email_verified_at')))?null:htmlspecialchars($request->input('email_verified_at')),
        ];
        $model->update($validateData);
        return response()->json($model);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        if($user->rol=='ADMIN'){ //El rol es de administrador
            $model = User::find($id);
            if(!$model){ //No encontro ningún 'id'
                return response()->json(['error' => 'No encontrado'], Response::HTTP_BAD_REQUEST);
            }else{ //Si es diferente el Id de la sesión al que va a eliminar
                if($user->id!=$model->id){
                    $model-> delete();
                }else { // si el'id' es él mismo
                    return response()->json(['error' => 'No te puedes eliminar a ti mismo'], Response::HTTP_BAD_REQUEST);
                }
            }
        }else{
            return response()->json(['error', 'No tiene permisos para esta operación'], Response::HTTP_UNAUTHORIZED);
        }
    }
}
