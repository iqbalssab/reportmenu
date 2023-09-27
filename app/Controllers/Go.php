<?php

namespace App\Controllers;

class Go extends BaseController
{
    public function index()
    {
        return view('go/index',);
    }

    public function bpbgo() {
        $dbProd = db_connect('production');

        $data = [
            'title' => 'BPB per PO',
        ];

        redirect()->to('bpbgo')->withInput();
        return view('/go/bpbgo',$data);
    }

    public function bpbperplugo() {
        $dbProd = db_connect('production');

        $data = [
            'title' => 'BPB per Item',
        ];

        redirect()->to('bpbperplugo')->withInput();
        return view('/go/bpbperplugo',$data);
    }

    public function pertemanan() {
        $dbProd = db_connect('production');
        $pertemanan = [];

        $pertemanan = $dbProd->query(
            "SELECT lks_koderak,
            lks_kodesubrak,
            lks_tiperak,
            lks_shelvingrak,
            lks_nourut,
            lks_prdcd,
            prd_deskripsipanjang,
            prd_unit,
            prd_frac,
            prd_kodetag,
            lks_jenisrak,
            pla.*
            FROM   tbmaster_lokasi,
                tbmaster_prodmast,
                ( SELECT * FROM   ( SELECT pla_prdcd,
                                        pla_nourut,
                                        pla_koderak,
                                        pla_subrak
                                        FROM   tbmaster_plano ) pivot ( min ( pla_koderak ) AS rak, min ( pla_subrak ) AS subrak FOR pla_nourut IN ( 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31 ) )) pla
            WHERE  lks_prdcd = prd_prdcd (+)
            AND    lks_prdcd = pla_prdcd (+)
            AND    lks_tiperak <> 'S'
            AND    substr(lks_koderak,1,1) NOT IN   ('D' ,'G')
            ORDER BY 1, 2, 3, 4, 5   "
        );
        $pertemanan = $pertemanan->getResultArray();

        $data = [
            'title' => 'BPB per Item',
            'pertemanan' => $pertemanan,
        ];

        redirect()->to('pertemanan')->withInput();
        return view('/go/pertemanan',$data);
    }
}