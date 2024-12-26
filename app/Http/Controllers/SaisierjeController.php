<?php

namespace App\Http\Controllers;

use App\Models\Saisierje;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class SaisierjeController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Saisierje::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'daterje'    =>
            [
                'required|date',
                Rule::unique('App\Models\Saisierje')
                    ->where('daterje', $request->daterje)
                    ->where('engin_id', $request->engin_id)
                    ->where('panne_id', $request->panne_id)
            ],

            'engin_id'   => 'required|exists:App\Models\Engin,id',
            'site_id'    => 'required|exists:App\Models\Site,id',
            'panne_id'   => 'required|exists:App\Models\Panne,id',
            'hrm'        => 'required|numeric|min:0|max:24',
            'him'        => 'required|numeric|min:0|max:24',
            'nho'        => 'required|numeric|min:0|max:24',
            'ni'         => 'required|numeric|min:0',
        ]);
        $saisierje = Saisierje::create($fields);
        return ['saisierje' => $saisierje];
    }

    /**
     * Display the specified resource.
     */
    public function show(Saisierje $saisierje)
    {
        return ['saisierje' => $saisierje];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Saisierje $saisierje)
    {
        $fields = $request->validate([

            'daterje'    =>
            [
                'required|date',
                Rule::unique('App\Models\Saisierje')
                    ->ignore($saisierje->id)
                    ->where('daterje', $saisierje->daterje)
                    ->where('engin_id', $saisierje->engin_id)
                    ->where('panne_id', $saisierje->panne_id)
            ],

            'engin_id'   => 'required|exists:App\Models\Engin,id',
            'site_id'    => 'required|exists:App\Models\Site,id',
            'panne_id'   => 'required|exists:App\Models\Panne,id',
            'hrm'        => 'required|numeric|min:0|max:24',
            'him'        => 'required|numeric|min:0|max:24',
            'nho'        => 'required|numeric|min:0|max:24',
            'ni'         => 'required|numeric|min:0',
        ]);
        $saisierje->update($fields);
        return $saisierje;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Saisierje $saisierje)
    {
        $saisierje->delete();
        return ['message' => 'Saisie rje was deleted'];
    }
}
