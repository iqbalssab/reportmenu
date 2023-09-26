<?php

namespace App\Controllers;

class Problemidm extends BaseController
{
    public function index()
    {
        return view('problemidm/index',);
    }

    public function itemkosong()
    {
        $dbProd = db_connect('production');

        $tglawal = $this->request->getVar('tglawal');
        $tglakhir = $this->request->getVar('tglakhir');
        $pluinput = $this->request->getVar('plu');
        $btn = $this->request->getVar('btn');
        $plu = sprintf("%07s",$pluinput);
        $plu0 = substr($plu,0,6)."0";

        $itemkosong = [];

        if ($pluinput!="") {
            $filterplu = "AND PLU = '$plu'";
        }else{
            $filterplu = " ";
        }

        if ($btn!="") {
            $itemkosong = $dbProd->query(
                "SELECT NOTRANS,TGLTRANS,PLU, DESK,QTYORDER, LPP FROM (
                    SELECT ((SUBSTR(OBI_PRDCD,1,6))||0) AS PLU, PRD_DESKRIPSIPANJANG DESK, OBI_QTYORDER QTYORDER, OBI_NOTRANS NOTRANS, OBI_TGLTRANS TGLTRANS
                    FROM TBTR_OBI_D 
                    LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = OBI_PRDCD
                    WHERE TRUNC(OBI_TGLTRANS) BETWEEN to_date('$tglawal', 'YYYY-MM-DD') AND to_date('$tglakhir', 'YYYY-MM-DD'))
                    LEFT JOIN (SELECT ST_PRDCD, ST_SALDOAKHIR LPP FROM TBMASTER_STOCK WHERE ST_LOKASI = '01')
                    ON ST_PRDCD = PLU
                    WHERE LPP = '0'
                    $filterplu"
            );

            $itemkosong = $itemkosong->getResultArray();
        }

        $data = [
            'title' => 'Item Kosong PBIDM',
            'itemkosong' => $itemkosong,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'plu' => $plu
        ];
        d($data);

        return view('problemidm/itemkosong', $data);
    }

    public function dpddouble()
    {
        $dbProd = db_connect('production');

        $doublerekap = $dbProd->query(
            "SELECT l.lks_prdcd,
            p.prd_deskripsipanjang  AS lks_nama_barang,
            p.prd_unit              AS lks_unit,
            p.prd_frac              AS lks_frac,
            Nvl(p.prd_kodetag, ' ') AS lks_tag,
            l.lks_jumlah_lokasi
            FROM   (SELECT l.lks_prdcd,
                            Count (l.lks_prdcd) AS lks_jumlah_lokasi
                    FROM   tbmaster_lokasi l
                    WHERE  l.lks_tiperak <> 'S'
                            AND l.lks_koderak LIKE 'D%'
                            AND l.lks_prdcd IS NOT NULL
                    GROUP  BY l.lks_prdcd
                    HAVING Count(l.lks_prdcd) > 1) l
                    left join tbmaster_prodmast p
                        ON l.lks_prdcd = p.prd_prdcd 
            ORDER  BY lks_prdcd"
        );

        $doubledetail = $dbProd->query(
            "SELECT l.lks_koderak,
            l.lks_kodesubrak,
            l.lks_tiperak,
            l.lks_shelvingrak,
            l.lks_nourut,
            l.lks_prdcd,
            p.prd_deskripsipanjang  AS lks_nama_barang,
            p.prd_unit              AS lks_unit,
            p.prd_frac              AS lks_frac,
            Nvl(p.prd_kodetag, ' ') AS lks_tag
            FROM   tbmaster_lokasi l
                    left join tbmaster_prodmast p
                        ON l.lks_prdcd = p.prd_prdcd
            WHERE  l.lks_prdcd IN (SELECT l.lks_prdcd
                                    FROM   tbmaster_lokasi l
                                    WHERE  l.lks_tiperak <> 'S'
                                        AND l.lks_koderak LIKE 'D%'
                                        AND l.lks_prdcd IS NOT NULL
                                    GROUP  BY l.lks_prdcd
                                    HAVING Count(l.lks_prdcd) > 1)
                    AND l.lks_tiperak <> 'S'
                    AND l.lks_koderak LIKE 'D%'
            ORDER  BY lks_prdcd,
                    lks_koderak"
        );

        $doublerekap = $doublerekap->getResultArray();
        $doubledetail = $doubledetail->getResultArray();
        $data = [
            'title' => 'DPD Double',
            'doublerekap' => $doublerekap,
            'doubledetail' => $doubledetail,
        ];

        return view('problemidm/dpddouble', $data);
    }

    public function hppigridm()
    {
        $dbProd = db_connect('production');

        $itemnonaktif = $dbProd->query(
            "SELECT * FROM view_hppigridm WHERE prd_deskripsipanjang like '*%'"
        );

        $prodcrmkosong = $dbProd->query(
            "SELECT * FROM view_hppigridm WHERE prc_pluidm is null"
        );

        $hargabelinol = $dbProd->query(
            "SELECT * FROM view_hppigridm WHERE hgb_hrgbeli = 0"
        );

        $stockdanponol = $dbProd->query(
            "SELECT * FROM view_hppigridm WHERE st_saldoakhir = 0 AND tpod_qtypo = 0"
        );

        $stockdanpoada = $dbProd->query(
            "SELECT * FROM view_hppigridm WHERE st_saldoakhir = 0 AND tpod_qtypo > 0"
        );

        $itemnonaktif = $itemnonaktif->getResultArray();
        $prodcrmkosong = $prodcrmkosong->getResultArray();
        $hargabelinol = $hargabelinol->getResultArray();
        $stockdanponol = $stockdanponol->getResultArray();
        $stockdanpoada = $stockdanpoada->getResultArray();

        $data = [
            'title' => 'HPP IGR-IDM',
            'itemnonaktif' => $itemnonaktif,
            'prodcrmkosong' => $prodcrmkosong,
            'hargabelinol' => $hargabelinol,
            'stockdanponol' => $stockdanponol,
            'stockdanpoada' => $stockdanpoada
        ];

        return view('problemidm/hppigridm',$data);
    }

    public function itemidm()
    {
        $dbprod = db_connect('production');

        $statustidakjelas = $dbprod->query(
            "SELECT * FROM view_hppigridm WHERE NVL(prd_flagidm, ' ') = ' ' ORDER BY k_div,k_dept, k_katb "
        );

        $statusidmonly = $dbprod->query(
            "SELECT * FROM view_hppigridm WHERE NVL(prd_flagidm, ' ') = 'Y' ORDER BY k_div,k_dept, k_katb "
        );

        $statusigridm = $dbprod->query(
            "SELECT * FROM view_hppigridm WHERE NVL(prd_flagidm, ' ') = 'N' ORDER BY k_div,k_dept, k_katb"
        );

        $statustidakjelas = $statustidakjelas->getResultArray();
        $statusidmonly = $statusidmonly->getResultArray();
        $statusigridm = $statusigridm->getResultArray();

        $data = [
            'title' => 'Status Item IDM',
            'tidakjelas' => $statustidakjelas,
            'idmonly' => $statusidmonly,
            'igridm' => $statusigridm
        ];

        return view('problemidm/itemidm',$data);
    }

    public function slidm()
    {
        $dbProd = db_connect('production');
        $tglawal = $this->request->getVar('tglawal');
        $tglakhir = $this->request->getVar('tglawal');

        $servicelevel = $dbProd->query(
            "SELECT *
            FROM  view_idm_sl_simulasi
            WHERE  trunc(hpbi_tgltransaksi) BETWEEN  to_date('$tglawal','yyyy-mm-dd') AND to_date('$tglakhir','yyyy-mm-dd')
            ORDER BY  hpbi_tgltransaksi"
        );

        $servicelevel = $servicelevel->getResultArray();

        $data = [
            'title' => 'Service Level IDM',
            'servicelevel' => $servicelevel
        ];

        return view('problemidm/slidm', $data);
    }

    public function lokasikosong()
    {
        $dbProd = db_connect('production');
        
        $jenisrak = $dbProd->query(
            "SELECT *
            FROM view_idm_problem_dpd
            WHERE lks_jenisrak is null
            ORDER BY lks_koderak,
              lks_kodesubrak,
              lks_shelvingrak,
              lks_nourut "
        );

        $maxplano = $dbProd->query(
            "SELECT *
            FROM view_idm_problem_dpd
            WHERE NVL(lks_maxplano,0) = 0
            ORDER BY lks_koderak,
              lks_kodesubrak,
              lks_shelvingrak,
              lks_nourut "
        );

        $dpdkosong = $dbProd->query(
            "SELECT *
            FROM view_idm_problem_dpd
            WHERE NVL(lks_qty,0) = 0
            ORDER BY lks_koderak,
              lks_kodesubrak,
              lks_shelvingrak,
              lks_nourut "
        );

        $jenisrak = $jenisrak->getResultArray();
        $maxplano = $maxplano->getResultArray();
        $dpdkosong = $dpdkosong->getResultArray();

        $data = [
            'title' => 'Lokasi Kosong',
            'jenisrak' => $jenisrak,
            'maxplano' => $maxplano,
            'dpdkosong' => $dpdkosong
        ];

        return view('problemidm/lokasikosong',$data);
    }
}