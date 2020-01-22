<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengajianCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pengajian');
    }

    public function checkGaji(Request $request)
    {
        $rules = [
            'gaji' => 'required',
            'name' => 'required'
        ];

        $this->validate($request, $rules);
        $tdk_hdr = str_replace('.','', $request->get('tidak_hadir'));
        $tlt_hdr = str_replace('.','', $request->get('telat_hadir'));
        $gaji = str_replace('.','', $request->get('gaji'));
        if (empty($request->get('tidak_hadir'))){
            $tdk_hdr = 0;
        }
        if (empty($request->get('telat_hadir'))){
            $tlt_hdr = 0;
        }

        $tidak_hadir = 200000 * $tdk_hdr;
        $telat_hadir = 50000 * $tlt_hdr;
        $jamsostek = 0.2 * $gaji;
        $pph = 0.1 * $gaji;
        $iuran = 0.2 * $gaji;
        $bpjs = 0.2 * $gaji;

        $total = $gaji - $jamsostek - $pph - $iuran - $bpjs - $tidak_hadir - $telat_hadir;

        if ($total < 0){
            $total = 0;
        }

        return response()->json([
            'message' => 'ok',
            'total' => $total,
            'jamsostek' => $jamsostek,
            'pph' => $pph,
            'iuran' => $iuran,
            'bpjs' => $bpjs
        ]);
    }
}
