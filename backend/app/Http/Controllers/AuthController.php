<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ], [
            'email.required' => 'E-pasta adrese ir obligāta',
            'email.email' => 'E-pasta adresei jābūt derīgai. Lūdzu, ievadiet pareizu e-pasta adresi.',
            'password.required' => 'Parole ir obligāta',
            'password.string' => 'Parolei jābūt teksta formātā',
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return response()->json(['message' => 'Lietotājs ar šādu e-pasta adresi nav atrasts. Lūdzu, pārbaudiet e-pasta adresi vai reģistrējieties.'], 422);
        }
        
        if (!Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Nepareiza parole'], 422);
        }

        $token = $user->createToken('api')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'Vārds ir obligāts',
            'name.string' => 'Vārdam jābūt teksta formātā',
            'name.min' => 'Vārdam jābūt vismaz 2 rakstzīmes garam. Lūdzu, ievadiet savu vārdu.',
            'name.max' => 'Vārds nedrīkst būt garāks par 255 rakstzīmēm',
            'email.required' => 'E-pasta adrese ir obligāta',
            'email.string' => 'E-pasta adresei jābūt teksta formātā',
            'email.email' => 'E-pasta adresei jābūt derīgai. Lūdzu, ievadiet pareizu e-pasta adresi.',
            'email.max' => 'E-pasta adrese nedrīkst būt garāka par 255 rakstzīmēm',
            'email.unique' => 'Šāda e-pasta adrese jau ir reģistrēta. Lūdzu, izmantojiet citu e-pasta adresi vai pieteikties.',
            'password.required' => 'Parole ir obligāta',
            'password.string' => 'Parolei jābūt teksta formātā',
            'password.min' => 'Parolei jābūt vismaz 8 rakstzīmes garai. Lūdzu, izmantojiet stiprāku paroli.',
            'password.confirmed' => 'Paroļu apstiprinājums nesakrīt',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'xp' => 0,
        ]);

        $token = $user->createToken('api')->plainTextToken;
        return response()->json(['token' => $token, 'user' => $user], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Iziet veiksmīgi']);
    }
}


