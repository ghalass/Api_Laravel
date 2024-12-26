<?php

namespace App\Http\Controllers;

use App\Models\Typeparc;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;

class TypeparcController extends Controller  implements HasMiddleware
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
        return Typeparc::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'          => 'required|unique:App\Models\Typeparc|max:255',
            'description'   => 'required',
        ]);
        $typeparc = Typeparc::create($fields);
        return ['typeparc' => $typeparc];
    }

    /**
     * Display the specified resource.
     */
    public function show(Typeparc $typeparc)
    {
        return ['typeparc' => $typeparc];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Typeparc $typeparc)
    {
        $fields = $request->validate([
            'name'          => 'required|max:255|unique:App\Models\Typeparc,' . $typeparc->id,
            'description'   => 'required',
        ]);
        $typeparc->update($fields);
        return $typeparc;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Typeparc $typeparc)
    {
        $typeparc->delete();
        return ['message' => 'Typeparc was deleted'];
    }
}
