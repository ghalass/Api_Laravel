<?php

namespace App\Http\Controllers;

use App\Models\Saisierje;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SaisierjeController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show', 'getRJE'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Saisierje::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'daterje'    =>
            [
                'required|date',
                Rule::unique('App\Models\Saisierje')
                    ->where('daterje', $request->daterje)
                    ->where('engin_id', $request->engin_id)
                    ->where('panne_id', $request->panne_id)
            ],

            'engin_id'   => 'required|exists:App\Models\Engin,id',
            'site_id'    => 'required|exists:App\Models\Site,id',
            'panne_id'   => 'required|exists:App\Models\Panne,id',
            'hrm'        => 'required|numeric|min:0|max:24',
            'him'        => 'required|numeric|min:0|max:24',
            'nho'        => 'required|numeric|min:0|max:24',
            'ni'         => 'required|numeric|min:0',
        ]);
        $saisierje = Saisierje::create($fields);
        return ['saisierje' => $saisierje];
    }

    /**
     * Display the specified resource.
     */
    public function show(Saisierje $saisierje)
    {
        return ['saisierje' => $saisierje];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Saisierje $saisierje)
    {
        $fields = $request->validate([

            'daterje'    =>
            [
                'required|date',
                Rule::unique('App\Models\Saisierje')
                    ->ignore($saisierje->id)
                    ->where('daterje', $saisierje->daterje)
                    ->where('engin_id', $saisierje->engin_id)
                    ->where('panne_id', $saisierje->panne_id),

            ],

            'engin_id'   => 'required|exists:App\Models\Engin,id',
            'site_id'    => 'required|exists:App\Models\Site,id',
            'panne_id'   => 'required|exists:App\Models\Panne,id',
            'hrm'        => 'required|numeric|min:0|max:24',
            'him'        => 'required|numeric|min:0|max:24',
            'nho'        => 'required|numeric|min:0|max:24',
            'ni'         => 'required|numeric|min:0',
        ]);


        //TODO: verify that the sum of him hrm is less then 24h

        $saisierje->update($fields);
        return $saisierje;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Saisierje $saisierje)
    {
        $saisierje->delete();
        return ['message' => 'Saisie rje was deleted'];
    }

    public function getRJE()
    {

        $dayRJE = '2024-12-27';
        $datejre = Carbon::parse($dayRJE)
            ->format('Y-m-d');
        $day = Carbon::parse($dayRJE)
            ->format('d');
        $month = Carbon::parse($dayRJE)
            ->format('m');
        $year = Carbon::parse($dayRJE)
            ->format('Y');

        /****** HRM */
        $hrm_j = DB::query()
            ->select([
                'e.id AS ENGIN_ID',
                's.id AS SITE_ID',
                's.name AS SITE',
                DB::raw('SUM(shrm.hrm) AS HRM_J'),

                'shrm.datesaisie AS JOUR',
                'shrm.nho AS NHO_J',
                'e.name AS ENGIN'
            ])
            ->from('engins AS e')
            ->leftJoin('saisiehrms AS shrm', function ($join) use ($datejre) {
                $join->on('e.id', '=', 'shrm.engin_id');
                $join->on(function ($query) use ($datejre) {
                    $query->whereDate('shrm.datesaisie', '=', $datejre);
                });
            })
            ->leftJoin('sites AS s', 's.id', '=', 'shrm.site_id')
            ->groupBy('e.id');

        $hrm_m = DB::query()
            ->select([
                'e.id AS ENGIN_ID',
                's.id AS SITE_ID',
                's.name AS SITE',
                DB::raw('SUM(shrm.hrm) AS HRM_M'),

                DB::raw('SUM(shrm.nho) AS NHO_M'),
                'e.name AS ENGIN'
            ])
            ->from('engins AS e')
            ->leftJoin('saisiehrms AS shrm', function ($join) use ($month, $year) {
                $join->on('e.id', '=', 'shrm.engin_id');
                $join->on(function ($query) use ($month, $year) {
                    $query
                        ->whereMonth('shrm.datesaisie', $month)
                        ->whereYear('shrm.datesaisie', $year);
                });
            })
            ->leftJoin('sites AS s', 's.id', '=', 'shrm.site_id')
            ->groupBy('e.id');

        $hrm_a = DB::query()
            ->select([
                'e.id AS ENGIN_ID',
                's.id AS SITE_ID',
                's.name AS SITE',
                DB::raw('SUM(shrm.hrm) AS HRM_A'),

                DB::raw('SUM(shrm.nho) AS NHO_A'),
                'e.name AS ENGIN'
            ])
            ->from('engins AS e')
            ->leftJoin('saisiehrms AS shrm', function ($join) use ($year) {
                $join->on('e.id', '=', 'shrm.engin_id');
                $join->on(function ($query) use ($year) {
                    $query
                        ->whereYear('shrm.datesaisie', $year);
                });
            })
            ->leftJoin('sites AS s', 's.id', '=', 'shrm.site_id')
            ->groupBy('e.id');

        /****** HIM */
        $him_j = DB::query()
            ->select([
                'e.id AS ENGIN_ID',
                DB::raw('SUM(shim.him) AS HIM_J'),
                DB::raw('SUM(shim.ni) AS NI_J'),

                'shim.datesaisie AS JOUR',
                'e.name AS ENGIN'
            ])
            ->from('engins AS e')
            ->leftJoin('saisiehims AS shim', function ($join) use ($datejre) {
                $join->on('e.id', '=', 'shim.engin_id');
                $join->on(function ($query) use ($datejre) {
                    $query->whereDate('shim.datesaisie', '=', $datejre);
                });
            })
            ->groupBy('e.id');

        $him_m = DB::query()
            ->select([
                'e.id AS ENGIN_ID',
                DB::raw('SUM(shim.him) AS HIM_M'),
                DB::raw('SUM(shim.ni) AS NI_M'),

                'e.name AS ENGIN'
            ])
            ->from('engins AS e')
            ->leftJoin('saisiehims AS shim', function ($join) use ($month, $year) {
                $join->on('e.id', '=', 'shim.engin_id');
                $join->on(function ($query) use ($month, $year) {
                    $query
                        ->whereMonth('shim.datesaisie', $month)
                        ->whereYear('shim.datesaisie', $year);
                });
            })
            ->groupBy('e.id');

        $him_a = DB::query()
            ->select([
                'e.id AS ENGIN_ID',
                DB::raw('SUM(shim.him) AS HIM_A'),
                DB::raw('SUM(shim.ni) AS NI_A'),

                'e.name AS ENGIN'
            ])
            ->from('engins AS e')
            ->leftJoin('saisiehims AS shim', function ($join) use ($month, $year) {
                $join->on('e.id', '=', 'shim.engin_id');
                $join->on(function ($query) use ($month, $year) {
                    $query
                        ->whereYear('shim.datesaisie', $year);
                });
            })
            ->groupBy('e.id');


        /****** RJE */
        // DISP Disponibilité (%) DISP = 1-(HIM/NHO) X100
        // TDM Taux de marche (%) TDM = HRM/NHO X 100
        // MTBF Temps moyen entre 2 pannes (H) MTBF = HRM/NI
        $res_j = DB::query()
            ->select(
                'hrm_j.ENGIN_ID',
                'hrm_j.SITE_ID',

                'hrm_j.HRM_J',
                'him_j.HIM_J',
                'him_j.NI_J',

                'hrm_j.NHO_J',

                DB::raw('ROUND(100*(1-him_j.HIM_J/hrm_j.NHO_J),2) AS DISP_J'),
                DB::raw('ROUND(100*hrm_j.HRM_J/hrm_j.NHO_J,2) AS TDM_J'),
                DB::raw('ROUND(hrm_j.HRM_J/him_j.NI_J,2) AS MTBF_J'),

                'hrm_j.ENGIN',
                'hrm_j.SITE',
                'hrm_j.JOUR'
            )
            ->from($hrm_j, 'hrm_j')
            ->joinSub($him_j, 'him_j', 'hrm_j.ENGIN_ID', '=', 'him_j.ENGIN_ID');

        $res_m = DB::query()
            ->select(
                'hrm_m.ENGIN_ID',
                'hrm_m.SITE_ID',

                'hrm_m.HRM_M',
                'him_m.HIM_M',
                'him_m.NI_M',

                'hrm_m.NHO_M',

                DB::raw('ROUND(100*(1-him_m.HIM_M/hrm_m.NHO_M),2) AS DISP_M'),
                DB::raw('ROUND(100*hrm_m.HRM_M/hrm_m.NHO_M,2) AS TDM_M'),
                DB::raw('ROUND(hrm_m.HRM_M/him_m.NI_M,2) AS MTBF_M'),

                'hrm_m.ENGIN',
            )
            ->from($hrm_m, 'hrm_m')
            ->joinSub($him_m, 'him_m', 'hrm_m.ENGIN_ID', '=', 'him_m.ENGIN_ID');

        $res_a = DB::query()
            ->select(
                'hrm_a.ENGIN_ID',
                'hrm_a.SITE_ID',

                'hrm_a.HRM_A',
                'him_a.HIM_A',
                'him_a.NI_A',

                'hrm_a.NHO_A',

                DB::raw('ROUND(100*(1-him_a.HIM_A/hrm_a.NHO_A),2) AS DISP_A'),
                DB::raw('ROUND(100*hrm_a.HRM_A/hrm_a.NHO_A,2) AS TDM_A'),
                DB::raw('ROUND(hrm_a.HRM_A/him_a.NI_A,2) AS MTBF_A'),

                'hrm_a.ENGIN',
            )
            ->from($hrm_a, 'hrm_a')
            ->joinSub($him_a, 'him_a', 'hrm_a.ENGIN_ID', '=', 'him_a.ENGIN_ID');

        $res_1 =  DB::query()
            ->select(
                'res_j.ENGIN_ID',
                'res_j.SITE_ID',

                'res_j.HRM_J',
                'res_j.HIM_J',
                'res_j.NI_J',
                'res_j.NHO_J',

                'res_m.HRM_M',
                'res_m.HIM_M',
                'res_m.NI_M',
                'res_m.NHO_M',

                'res_j.ENGIN',
                'res_j.SITE',
            )
            ->from($res_j, 'res_j')
            ->joinSub($res_m, 'res_m', 'res_j.ENGIN_ID', '=', 'res_m.ENGIN_ID');

        $res_final = DB::query()
            ->select(
                'res_1.ENGIN_ID',
                'res_1.SITE_ID',

                'res_1.HRM_J',
                'res_1.HIM_J',
                'res_1.NI_J',
                'res_1.NHO_J',

                'res_1.HRM_M',
                'res_1.HIM_M',
                'res_1.NI_M',
                'res_1.NHO_M',

                'res_a.HRM_A',
                'res_a.HIM_A',
                'res_a.NI_A',
                'res_a.NHO_A',

                'res_1.ENGIN',
                'res_1.SITE',
            )
            ->from($res_1, 'res_1')
            ->joinSub($res_a, 'res_a', 'res_1.ENGIN_ID', '=', 'res_a.ENGIN_ID')
            ->get();

        return $res_final;
    }
}