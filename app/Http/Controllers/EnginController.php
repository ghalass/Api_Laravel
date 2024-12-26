<?php

namespace App\Http\Controllers;

use App\Models\Engin;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EnginController extends Controller implements HasMiddleware
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
        return Engin::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'          => 'required|unique:App\Models\Engin|max:255',
            'description'   => 'required',
            'parc_id'       => 'required|exists:App\Models\Parc,id',
            'site_id'       => 'required|exists:App\Models\Site,id',
        ]);
        $engin = Engin::create($fields);
        return ['engin' => $engin];
    }

    /**
     * Display the specified resource.
     */
    public function show(Engin $engin)
    {
        return ['engin' => $engin];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Engin $engin)
    {
        $fields = $request->validate([
            'name'          => 'required|max:255|unique:App\Models\Engin,' . $engin->id,
            'description'   => 'required',
            'parc_id'   => 'required|exists:App\Models\Parc,id',
            'site_id'       => 'required|exists:App\Models\Site,id',
        ]);
        $engin->update($fields);
        return $engin;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Engin $engin)
    {
        $engin->delete();
        return ['message' => 'Engin was deleted'];
    }
}
