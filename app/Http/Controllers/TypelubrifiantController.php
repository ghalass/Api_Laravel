<?php

namespace App\Http\Controllers;

use App\Models\Typelubrifiant;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;

class TypelubrifiantController extends Controller implements HasMiddleware
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
        return Typelubrifiant::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'          => 'required|unique:App\Models\Typelubrifiant|max:255',
        ]);
        $typelubrifiant = Typelubrifiant::create($fields);
        return ['typelubrifiant' => $typelubrifiant];
    }

    /**
     * Display the specified resource.
     */
    public function show(Typelubrifiant $typelubrifiant)
    {
        return ['typelubrifiant' => $typelubrifiant];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Typelubrifiant $typelubrifiant)
    {
        $fields = $request->validate([
            'name'          => 'required|max:255|unique:sites,name,' . $typelubrifiant->id,
        ]);
        $typelubrifiant->update($fields);
        return $typelubrifiant;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Typelubrifiant $typelubrifiant)
    {
        $typelubrifiant->delete();
        return ['message' => 'Typelubrifiant was deleted'];
    }
}