<?php

namespace App\Http\Controllers;

use App\Models\Parc;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ParcController extends Controller implements HasMiddleware
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
        return Parc::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'          => 'required|unique:App\Models\Parc|max:255',
            'description'   => 'required',
            'typeparc_id'   => 'required|exists:App\Models\Typeparc,id'
        ]);
        $parc = Parc::create($fields);
        return ['typeparc' => $parc];
    }

    /**
     * Display the specified resource.
     */
    public function show(Parc $parc)
    {
        return ['parc' => $parc];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parc $parc)
    {
        $fields = $request->validate([
            'name'          => 'required|max:255|unique:App\Models\Parc,' . $parc->id,
            'description'   => 'required',
            'typeparc_id'   => 'required|exists:App\Models\Typeparc,id'
        ]);
        $parc->update($fields);
        return $parc;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parc $parc)
    {
        $parc->delete();
        return ['message' => 'Parc was deleted'];
    }
}
