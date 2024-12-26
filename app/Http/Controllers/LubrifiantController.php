<?php

namespace App\Http\Controllers;

use App\Models\Lubrifiant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LubrifiantController extends Controller implements HasMiddleware
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
        return Lubrifiant::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'          => 'required|unique:App\Models\Lubrifiant|max:255',
            'typelubrifiant_id'   => 'required|exists:App\Models\Typelubrifiant,id'
        ]);
        $lubrifiant = Lubrifiant::create($fields);
        return ['lubrifiant' => $lubrifiant];
    }

    /**
     * Display the specified resource.
     */
    public function show(Lubrifiant $lubrifiant)
    {
        return ['lubrifiant' => $lubrifiant];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lubrifiant $lubrifiant)
    {
        $fields = $request->validate([
            'name'          => 'required|max:255|unique:App\Models\Lubrifiant,' . $lubrifiant->id,
            'typelubrifiant_id'   => 'required|exists:App\Models\Typelubrifiant,id'
        ]);
        $lubrifiant->update($fields);
        return $lubrifiant;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lubrifiant $lubrifiant)
    {
        $lubrifiant->delete();
        return ['message' => 'Lubrifiant was deleted'];
    }
}
