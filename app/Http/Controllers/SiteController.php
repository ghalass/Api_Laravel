<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;


class SiteController extends Controller implements HasMiddleware
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
        return Site::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'          => 'required|unique:App\Models\Site|max:255',
            'description'   => 'required',
        ]);
        $site = Site::create($fields);
        return ['site' => $site];
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site)
    {
        return ['site' => $site];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site)
    {
        $fields = $request->validate([
            'name'          => 'required|max:255|unique:sites,name,' . $site->id,
            'description'   => 'required',
        ]);
        $site->update($fields);
        return $site;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site)
    {
        $site->delete();
        return ['message' => 'Site was deleted'];
    }
}
