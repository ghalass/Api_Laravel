<?php

namespace App\Http\Controllers;

use App\Models\Saisielubrifiant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class SaisielubrifiantController extends Controller implements HasMiddleware
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
        return Saisielubrifiant::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'du'    =>
            [
                'required',
                'date',
                Rule::unique('App\Models\Saisielubrifiant')
                    ->where('du', $request->du)
                    ->where('au', $request->au)
                    ->where('engin_id', $request->engin_id)
                    ->where('lubrifiant_id', $request->lubrifiant_id)
            ],

            'au'   => 'required|date|after_or_equal:du',
            'engin_id'   => 'required|exists:App\Models\Engin,id',
            'lubrifiant_id'    => 'required|exists:App\Models\Lubrifiant,id',
            'qte'        => 'required|numeric|min:0',
        ]);
        $saisielubrifiant = Saisielubrifiant::create($fields);
        return ['saisielubrifiant' => $saisielubrifiant];
    }

    /**
     * Display the specified resource.
     */
    public function show(Saisielubrifiant $saisielubrifiant)
    {
        return ['saisielubrifiant' => $saisielubrifiant];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Saisielubrifiant $saisielubrifiant)
    {
        $fields = $request->validate([
            'du'    =>
            [
                'required',
                'date',
                Rule::unique('App\Models\Saisielubrifiant')
                    ->ignore($saisielubrifiant->id)
                    ->where('du', $request->du)
                    ->where('au', $request->au)
                    ->where('engin_id', $request->engin_id)
                    ->where('lubrifiant_id', $request->lubrifiant_id)
            ],

            'au'   => 'required|date|after_or_equal:du',
            'engin_id'   => 'required|exists:App\Models\Engin,id',
            'lubrifiant_id'    => 'required|exists:App\Models\Lubrifiant,id',
            'qte'        => 'required|numeric|min:0',
        ]);
        $saisielubrifiant->update($fields);
        return $saisielubrifiant;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Saisielubrifiant $saisielubrifiant)
    {
        $saisielubrifiant->delete();
        return ['message' => 'Saisie Lubrifiant was deleted'];
    }
}
