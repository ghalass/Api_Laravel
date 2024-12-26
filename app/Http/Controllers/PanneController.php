<?php

namespace App\Http\Controllers;

use App\Models\Panne;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PanneController extends Controller implements HasMiddleware
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
        return Panne::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'          => 'required|unique:App\Models\Panne|max:255',
            'typepanne_id'   => 'required|exists:App\Models\Typepanne,id'
        ]);
        $panne = Panne::create($fields);
        return ['panne' => $panne];
    }

    /**
     * Display the specified resource.
     */
    public function show(Panne $panne)
    {
        return ['panne' => $panne];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Panne $panne)
    {
        $fields = $request->validate([
            'name'          => 'required|max:255|unique:App\Models\Panne,' . $panne->id,
            'typepanne_id'   => 'required|exists:App\Models\Typepanne,id'
        ]);
        $panne->update($fields);
        return $panne;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Panne $panne)
    {
        $panne->delete();
        return ['message' => 'Panne was deleted'];
    }
}
