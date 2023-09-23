<?php

namespace App\Controllers;

class Problem extends BaseController
{
    public function index()
    {
        return view('problem/index',);
    }

    public function barcodedouble() {
        $dbProd = db_connect('production');
        $barcode = [];

        $barcode = $dbProd->query(
            "SELECT b.brc_barcode,
            b.brc_prdcd,
            p.prd_plumcg            AS brc_plumcg,
            p.prd_deskripsipanjang  AS brc_nama_barang,
            p.prd_unit              AS brc_unit,
            p.prd_frac              AS brc_frac,
            Nvl(p.prd_kodetag, ' ') AS brc_tag,
            p.prd_avgcost           AS brc_avg_cost,
            p.prd_hrgjual           AS brc_harga_jual,
            s.st_saldoakhir         AS brc_stock_qty
            FROM   tbmaster_barcode b,
                    tbmaster_prodmast p,
                    (SELECT st_prdcd,
                            st_saldoakhir
                    FROM   tbmaster_stock
                    WHERE  st_lokasi = '01') s
            WHERE  b.brc_prdcd = s.st_prdcd (+)
                    AND b.brc_prdcd = p.prd_prdcd (+)
                    AND brc_barcode IN (SELECT b.brc_barcode
                                        FROM   tbmaster_barcode b
                                        GROUP  BY brc_barcode
                                        HAVING Count(DISTINCT( Substr(b.brc_prdcd, 1, 6) )) > 1)
            ORDER  BY b.brc_barcode "
        );
        $barcode = $barcode->getResultArray();

        $data = [
            'title' => 'Barcode Double',
            'barcode' => $barcode,
        ];

        return view('/problem/barcodedouble',$data);
    }

    public function lokasidouble() {
        $dbProd = db_connect('production');
        $rekap = $detail = [];

        $rekap = $dbProd->query(
            "SELECT l.lks_prdcd,
            p.prd_deskripsipanjang  AS lks_nama_barang,
            p.prd_unit              AS lks_unit,
            p.prd_frac              AS lks_frac,
            Nvl(p.prd_kodetag, ' ') AS lks_tag,
            l.lks_jumlah_lokasi
            FROM   (SELECT l.lks_prdcd,
                            Count (l.lks_prdcd) AS lks_jumlah_lokasi
                    FROM   tbmaster_lokasi l
                    WHERE  l.lks_tiperak NOT IN ( 'S', 'Z' )
                            AND l.lks_koderak NOT LIKE 'D%'
                            AND l.lks_prdcd IS NOT NULL
                    GROUP  BY l.lks_prdcd
                    HAVING Count(l.lks_prdcd) > 1) l
                    left join tbmaster_prodmast p
                        ON l.lks_prdcd = p.prd_prdcd 
            ORDER  BY lks_prdcd"
        );
        $rekap = $rekap->getResultArray();

        $detail = $dbProd->query(
            "  SELECT l.lks_koderak,
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
                                    WHERE  l.lks_tiperak NOT IN ( 'S', 'Z' )
                                        AND l.lks_koderak NOT LIKE 'D%'
                                        AND l.lks_prdcd IS NOT NULL
                                    GROUP  BY l.lks_prdcd
                                    HAVING Count(l.lks_prdcd) > 1)
                    AND l.lks_tiperak NOT IN ( 'S', 'Z' )
                    AND l.lks_koderak NOT LIKE 'D%'
            ORDER  BY lks_prdcd,
                    lks_koderak "
        );
        $detail = $detail->getResultArray();

        $data = [
            'title' => 'Master Lokasi Double',
            'rekap' => $rekap,
            'detail' => $detail,
        ];

        return view('/problem/lokasidouble',$data);
    }

    public function lokasiqtyminus() {
        $dbProd = db_connect('production');
        $qtyminus = [];

        $qtyminus = $dbProd->query(
            "SELECT l.lks_koderak,
            l.lks_kodesubrak,
            l.lks_tiperak,
            l.lks_shelvingrak,
            l.lks_nourut,
            l.lks_prdcd,
            p.prd_deskripsipanjang  AS lks_nama_barang,
            p.prd_unit              AS lks_unit,
            p.prd_frac              AS lks_frac,
            Nvl(p.prd_kodetag, ' ') AS prd_kode_tag,
            l.lks_noid,
            l.lks_qty,
            l.lks_expdate,
            l.lks_minpct,
            l.lks_maxplano,
            l.lks_jenisrak
            FROM   tbmaster_lokasi l,
                    tbmaster_prodmast p
            WHERE  l.lks_prdcd = p.prd_prdcd
                    AND Nvl(l.lks_qty, 0) < 0
                    AND l.lks_prdcd IS NOT NULL
            ORDER  BY l.lks_koderak,
                    l.lks_kodesubrak,
                    l.lks_tiperak,
                    l.lks_shelvingrak,
                    l.lks_nourut"
        );
        $qtyminus = $qtyminus->getResultArray();

        $data = [
            'title' => 'Lokasi Rak Qty Minus',
            'qtyminus' => $qtyminus,
        ];

        return view('/problem/lokasiqtyminus',$data);
    }
}