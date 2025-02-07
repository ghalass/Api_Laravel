<?php

namespace App\Http\Controllers;

use App\Models\Typepanne;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;

class TypepanneController extends Controller implements HasMiddleware
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
        return Typepanne::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'          => 'required|unique:App\Models\Typepanne|max:255',
        ]);
        $typepanne = Typepanne::create($fields);
        return ['typepanne' => $typepanne];
    }

    /**
     * Display the specified resource.
     */
    public function show(Typepanne $typepanne)
    {
        return ['typepanne' => $typepanne];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Typepanne $typepanne)
    {
        $fields = $request->validate([
            'name'          => 'required|max:255|unique:sites,name,' . $typepanne->id,
        ]);
        $typepanne->update($fields);
        return $typepanne;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Typepanne $typepanne)
    {
        $typepanne->delete();
        return ['message' => 'Typepanne was deleted'];
    }
}