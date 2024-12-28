<?php

namespace App\Http\Controllers;

use App\Models\Saisiehrm;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;


class SaisiehrmController extends Controller implements HasMiddleware
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
        return Saisiehrm::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'datesaisie'    =>
            [
                'required',
                'date',
                'before:tomorrow',
                Rule::unique('App\Models\Saisiehrm')
                    ->where('datesaisie', $request->datesaisie)
                    ->where('engin_id', $request->engin_id)
            ],

            'engin_id'   => 'required|exists:App\Models\Engin,id',
            'site_id'    => 'required|exists:App\Models\Site,id',
            'hrm'        => 'required|numeric|min:0|max:24',
        ]);
        $saisiehrm = Saisiehrm::create($fields);
        return ['saisiehrm' => $saisiehrm];
    }

    /**
     * Display the specified resource.
     */
    public function show(Saisiehrm $saisiehrm)
    {
        return ['saisiehrm' => $saisiehrm];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Saisiehrm $saisiehrm)
    {
        $fields = $request->validate([
            'datesaisie'    =>
            [
                'required',
                'date',
                'before:tomorrow',
                Rule::unique('App\Models\Saisiehrm')
                    ->ignore($saisiehrm->id)
                    ->where('datesaisie', $request->datesaisie)
                    ->where('engin_id', $request->engin_id)
            ],

            'engin_id'   => 'required|exists:App\Models\Engin,id',
            'site_id'    => 'required|exists:App\Models\Site,id',
            'hrm'        => 'required|numeric|min:0|max:24'
        ]);
        $saisiehrm->update($fields);
        return $saisiehrm;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Saisiehrm $saisiehrm)
    {
        $saisiehrm->delete();
        return ['message' => 'Saisie hrm was deleted'];
    }
}