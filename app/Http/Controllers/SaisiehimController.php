<?php

namespace App\Http\Controllers;

use App\Models\Saisiehim;
use App\Models\Saisiehrm;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class SaisiehimController extends Controller implements HasMiddleware
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
        return Saisiehim::latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'datesaisie'    =>
            [
                'required',
                'date',
                'before:tomorrow',
                Rule::unique('App\Models\Saisiehim')
                    ->where('datesaisie', $request->datesaisie)
                    ->where('engin_id', $request->engin_id)
                    ->where('panne_id', $request->panne_id)
            ],

            'engin_id'   => 'required|exists:App\Models\Engin,id',
            'panne_id'   => 'required|exists:App\Models\Panne,id',
            'him'        => 'required|numeric|min:0|max:24',
            'ni'        => 'required|integer|numeric|min:0|max:24',
        ]);

        $res = $this->checkSAISIE($request);
        if ($res === 'ok') {
            $saisiehim = Saisiehim::create($fields);
            return ['saisiehim' => $saisiehim];
        } else {
            return $res;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Saisiehim $saisiehim)
    {
        return ['saisiehim' => $saisiehim];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Saisiehim $saisiehim)
    {
        $fields = $request->validate([
            'datesaisie'    =>
            [
                'required',
                'date',
                'before:tomorrow',
                Rule::unique('App\Models\Saisiehim')
                    ->ignore($saisiehim->id)
                    ->where('datesaisie', $request->datesaisie)
                    ->where('engin_id', $request->engin_id)
            ],

            'engin_id'   => 'required|exists:App\Models\Engin,id',
            'panne_id'   => 'required|exists:App\Models\Panne,id',
            'him'        => 'required|numeric|min:0|max:24',
            'ni'        => 'required|integer|numeric|min:0|max:24',
        ]);

        $res = $this->checkSAISIE($request);
        if ($res === 'ok') {
            $saisiehim->update($fields);
            return $saisiehim;
        } else {
            return $res;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Saisiehim $saisiehim)
    {
        $saisiehim->delete();
        return ['message' => 'Saisie hrm was deleted'];
    }

    // vérifier si le contrainte nho = sum_hrm + sum_him + sum_hrd
    private function checkSAISIE($request)
    {
        $check1 = Saisiehrm::where('datesaisie', $request->datesaisie)
            ->selectRaw('SUM(hrm) as sum_hrm, nho')
            ->where('engin_id', $request->engin_id)
            ->groupBy('engin_id')
            ->get();

        $check2 = Saisiehim::where('datesaisie', $request->datesaisie)
            ->selectRaw('SUM(him) as sum_him')
            ->where('engin_id', $request->engin_id)
            ->groupBy('engin_id')
            ->get();

        $sum_hrm = $check1[0]->sum_hrm ?? 0;
        $nho = $check1[0]->nho ?? 0;
        $sum_him = $check2[0]->sum_him ?? 0;

        // nho = sum_him + hrm + hrd <= 24
        $check = $sum_him + $request->him + $sum_hrm;
        if ($nho !== 0) {
            if ($check <= $nho) {
                return 'ok';
            } else {
                return response()->json(
                    [
                        'errors' =>  [
                            'him' => ["La valeur HIM = " . $request->him . ", le total (HRM + HIM + HRD) sera " . $check . ", il ne peut pas dépasser " . $nho . ""]
                        ]
                    ],
                    422
                );
            }
        } else {
            return response()->json(
                [
                    'errors' =>  [
                        'him' => ["Vous devait saisir NHO et HRM pour cet engin à cette date avant de saisir les arrêts."]
                    ]
                ],
                422
            );
        }
    }
}