<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['getRJE'])
        ];
    }

    public function getRJE()
    {
        $dayRJE = '2024-12-27';
        $datejre = Carbon::parse($dayRJE)->format('Y-m-d');
        $day = Carbon::parse($dayRJE)->format('d');
        $month = Carbon::parse($dayRJE)->format('m');
        $year = Carbon::parse($dayRJE)->format('Y');

        $rje = DB::query()
            ->select(
                'r_hrm_j.*',
                'r_hrm_m.*',
                'r_hrm_a.*',

                'r_him_j.*',
                'r_him_m.*',
                'r_him_a.*',

                DB::raw('ROUND(100*(1-r_him_j.HIM_J/r_hrm_j.NHO_J),2) AS DISP_J'),
                DB::raw('ROUND(100*r_hrm_j.HRM_J/r_hrm_j.NHO_J,2) AS TDM_J'),
                DB::raw('ROUND(r_hrm_j.HRM_J/r_him_j.NI_J,2) AS MTBF_J'),

                DB::raw('ROUND(100*(1-r_him_m.HIM_M/r_hrm_m.NHO_M),2) AS DISP_M'),
                DB::raw('ROUND(100*r_hrm_m.HRM_M/r_hrm_m.NHO_M,2) AS TDM_M'),
                DB::raw('ROUND(r_hrm_m.HRM_M/r_him_m.NI_M,2) AS MTBF_M'),

                DB::raw('ROUND(100*(1-r_him_a.HIM_A/r_hrm_a.NHO_A),2) AS DISP_A'),
                DB::raw('ROUND(100*r_hrm_a.HRM_A/r_hrm_a.NHO_A,2) AS TDM_A'),
                DB::raw('ROUND(r_hrm_a.HRM_A/r_him_a.NI_A,2) AS MTBF_A'),

            )
            // HRM
            ->fromSub(function ($query) use ($datejre) {
                $query->select([
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
            }, 'r_hrm_j')
            ->joinSub(function ($query) use ($month, $year) {
                $query->select([
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
            }, 'r_hrm_m', 'r_hrm_j.ENGIN_ID', '=', 'r_hrm_m.ENGIN_ID')
            ->joinSub(function ($query) use ($year) {
                $query->select([
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
            }, 'r_hrm_a', 'r_hrm_j.ENGIN_ID', '=', 'r_hrm_a.ENGIN_ID')

            // HIM
            ->joinSub(function ($query) use ($datejre) {
                $query->select([
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
            }, 'r_him_j', 'r_hrm_j.ENGIN_ID', '=', 'r_him_j.ENGIN_ID')
            ->joinSub(function ($query) use ($month, $year) {
                $query->select([
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
            }, 'r_him_m', 'r_hrm_m.ENGIN_ID', '=', 'r_him_m.ENGIN_ID')
            ->joinSub(function ($query) use ($month, $year) {
                $query->select([
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
            }, 'r_him_a', 'r_hrm_a.ENGIN_ID', '=', 'r_him_a.ENGIN_ID')
            ->orderBy('ENGIN', 'ASC')
            ->get();

        return $rje;
    }
}