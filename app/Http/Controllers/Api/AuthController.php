<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        //validación de los datos
        $rules = [
            'role_id'=>'required',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'errors' => $validator->errors()->all(),
            ], Response::HTTP_BAD_REQUEST);
        }

        //alta del usuario
        $user = new User();
        $user->role_id = $request->role_id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        if ($request->status != null) {
            $user->status = 1;
        }

        $user->save();

        return response()->json([
            'Data' => $user,
            'status' => Response::HTTP_CREATED,
        ], Response::HTTP_OK);
    }

    public function updateProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        //validación de los datos
        $rules = [
            'name' => 'required',
            'email' => ['required','email', Rule::unique('users')->ignore($request->id)],
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'errors' => $validator->errors()->all(),
            ], Response::HTTP_BAD_REQUEST);
        }

        //Find user
        $user = User::where('documentNumber', $request->documentNumber)->first();
        if (isset($user)) {
            $user->name = $request->name;
            $user->lastName = $request->lastName;
            $user->nationality = $request->nationality;
            $user->email = $request->email;
            $user->bk = $request->password;
            $user->password = Hash::make($request->password);

            $user->save();

            return response()->json([
                'Data' => $user,
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } else {
            return response()->json([
                'Data' => 'Usuario no encontrado',
                'status' => Response::HTTP_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND);
        }

    }

    public
    function login(Request $request): \Illuminate\Foundation\Application|\Illuminate\Http\Response|JsonResponse|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->status == 1) {
                unset($user->role_id);
                //$user->rol()->name;
                $token = $user->createToken('token')->plainTextToken;
                $cookie = cookie('cookie_token', $token, 360);
                return response()->json([
                    "data" => $user,
                    "token" => $token,
                ], Response::HTTP_OK)->withoutCookie($cookie);
            } else {
                auth()->user()->tokens()->delete();
                return response(["message" => "Usuario no autorizado"], Response::HTTP_UNAUTHORIZED);
            }

        } else {
            return response(["message" => "Credenciales inválidas"], Response::HTTP_UNAUTHORIZED);
        }
    }

    public
    function userProfile(): \Illuminate\Http\JsonResponse
    {
        if (auth()->user()->status == 1) {
            return response()->json([
                "status" => Response::HTTP_OK,
                "data" => auth()->user(),
            ], Response::HTTP_OK);
        } else {
            auth()->user()->tokens()->delete();
            return response()->json([
                "message" => "Usuario no autorizado",
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public
    function changeUserStatus(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find($request->id);

        $user->status = $request->status;
        $user->save();

        if ($request->status == 1) {
            return response()->json([
                "message" => "Usuario autorizado",
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                "message" => "Usuario no autorizado",
            ], Response::HTTP_OK);

        }
    }

    public
    function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "Sesión cerrada",
        ], Response::HTTP_OK);

    }
}
