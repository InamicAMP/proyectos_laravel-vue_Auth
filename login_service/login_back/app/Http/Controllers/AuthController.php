<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthController extends Controller
{
    /*
     *Get a JWT via given credentials.
     *
     * $return Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'password' => 'required|string',
        'email' => 'required|email'
        ], [
            'email.required' => 'El campo correo es obligatorio.',
            'email.email' => 'El correo no tiene el formato correcto.',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator ->errors()], Response::HTTP_UNAUTHORIZED);
        }
        if(!$token = auth()->attemp($credentials)){
            return response()->json(['error' => 'Datos de acceso incorrectos. Por favor, verifica tus credenciales.'], Response::HTTP_UNAUTHORIZED);
        }
        return $this->respondWithToken($token);
    }

    public function unauthorized()
    {
        return redirect(route('login'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max100|min:2',
            'password' => 'required|string|max:225|min:8',
            'email' => 'required|email|unique:users,email',
        ], ['name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener al menos :max caracteres.',
            'email.required' => 'El correo ya está registrado.',
            'email.unique' => 'El correo ya está registrado.',
            'email.email' => 'El correo no tiene el formato correcto.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña es de mínimo :min caracteres.'
        ]);
        if($validator-> fails()){
            return response()->json (['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }
        $exists = User::where('email', htmlspecialchars($request->input('email')))->first();
        if(!$exists){ //Si no existe el email crea en Objeto Usuario
            $new = User::create([
                'name' => htmlspecialchars($request->input('name')),
                'email' => htmlspecialchars($request->input('email')),
                'password' => Hash::make($request->input('name')),
                'rol' => 1,
            ]);
            if(!$new){
                return response()->json(['error' => 'No se logró crear'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }else{
                return response()->json(['error' => 'No se logró crear']);
            }
        }
    }

    /*
    *Get the authenticated User.
    *
    *@return \Illuminate\Http\JsonResponse
    */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /*
    *
    *
    *
    */
    public function logout()
    {
        auth()->logout();
        try{
            $token = JWTAuth::getToken(); //Obtiene el token actual
            if(!$token){
                return response()->json(['error' => 'Token no encontrado'], Response::HTTP_BAD_REQUEST);   
            }
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Session cerrada correctamente'], Response::HTTP_OK); 
        }catch (TokenInvalidException $e){
            return response()->json(['message' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        }catch (\Exception $e){
            return response()->json(['error' => 'No se pudo cerrar la sesión'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
    *Refresh a token.
    *
    *@return \Illuminate\Http\JsonResponse
    */
    public function refresh()
    {
        try{
            $token = JWTAuth::getToken(); //Obtiene el token actual
            if(!$token){
                return response()->json(['error' => 'Token no encontrado'], Response::HTTP_BAD_REQUEST);
            }
            $nuevo_token = auth()->refresh();
            //Invalida el oken actual
            JWTAuth::invalidate($token);
            return $this->respondWithToken($nuevo_token);
        }catch (TokenInvalidException $e){
            return response()->json(['error' => 'Token invalido'], Response::HTTP_UNAUTHORIZED);

        }catch(\Exception $e)
        {
            return response()->json(['error' => 'No se puso cerrar la sesión'],);
        }
    }

    /**
    *Get the token array structure.
    *
    *@param string $token
    *
    *@return \Illuminate\Http\JsonResponse
    */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>auth()->factory()->getTTL() * 60
        ], Response::HTTP_OK);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
