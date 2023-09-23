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

    public function membertidakbelanja() {
        $dbProd = db_connect('production');
        
        $data = [
            'title' => 'Member Tidak Berblanja',
        ];

        return view('/problem/membertidakbelanja',$data);
    }

    public function tampilmember() {
        $dbProd = db_connect('production');
        $member = $filename = [];
        $filtersts = $filtermbr = $jlap = '';
        $aksi = $this->request->getVar('tombol');

        // inisiasi
        $jenisMember          = "Merah"; 
        $aktivasiKartu  = $jenisLaporan   	    = "All";
        
        // ambil data
        if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tanggalSelesai = $_GET['akhir']; }}
        if(isset($_GET['jenisMember'])) {if ($_GET['jenisMember'] !=""){$jenisMember = $_GET['jenisMember']; }}
        if ($jenisMember != "Merah" AND $jenisMember != "") {
            $filtermbr = " AND NVL(cus.cus_flagmemberkhusus,'N') != 'Y' and NVL(cus.cus_kodeoutlet,'6') != '4' ";
        } else {
            $filtermbr = " AND NVL(cus.cus_flagmemberkhusus,'N') = 'Y' ";
        }
        if(isset($_GET['aktivasiKartu'])) {if ($_GET['aktivasiKartu'] !=""){$aktivasiKartu = $_GET['aktivasiKartu']; }}
        if ($aktivasiKartu != "All" AND $aktivasiKartu != "") {
            switch ($aktivasiKartu) {
              case "Sudah":
                $filtersts = " AND kun_pertama IS NOT NULL ";
                break;
              case "Belum":
                $filtersts = " AND kun_pertama IS NULL ";
                break;
            }
        }

        if(isset($_GET['jenisLaporan'])) {if ($_GET['jenisLaporan'] !=""){$jenisLaporan = $_GET['jenisLaporan']; }}

        $viewTidakBelanja =
        " (SELECT cus.cus_kodemember,
            cus.cus_namamember,
            cus.cus_alamatmember5,
            cus.cus_alamatmember6,
            cus.cus_alamatmember7,
            cus.cus_alamatmember8,
            cus.cus_hpmember,
            cus.cus_tlpmember,
            cus.cus_kodeoutlet,
            cus.cus_kodesuboutlet,
            poi.poc_saldopoint,
            kun.kun_pertama,
            kun.kun_terakhir,
            kun.kun_jumlah,
            trunc(sysdate) - trunc(kun.kun_terakhir) as kun_hari
            
            FROM tbmaster_customer cus,
                tbmaster_pointcustomer poi,
                (SELECT jh_cus_kodemember                    AS kun_kode_member,
                MIN(TRUNC(jh_transactiondate))             AS kun_pertama,
                MAX(TRUNC(jh_transactiondate))             AS kun_terakhir,
                COUNT(DISTINCT(TRUNC(jh_transactiondate))) AS kun_jumlah
                FROM tbtr_jualheader
                WHERE jh_cus_kodemember IS NOT NULL
                GROUP BY jh_cus_kodemember) kun

            WHERE cus.cus_kodemember = kun.kun_kode_member(+)
            AND cus.cus_kodemember = poi.poc_kodemember(+)
            AND cus.cus_recordid               IS NULL
            $filtermbr
            AND cus.cus_kodeigr                   =
            (SELECT prs_kodeigr FROM tbmaster_perusahaan)
            AND cus.cus_namamember NOT LIKE 'NEW%'
            AND cus.cus_kodemember NOT IN (SELECT DISTINCT jh_cus_kodemember
                                        FROM tbtr_jualheader
                                        WHERE trunc(jh_transactiondate) >= to_date('$tanggalSelesai','yyyy-mm-dd')
                                        AND jh_cus_kodemember IS NOT NULL)) ";
        
        if($jenisLaporan == "1") {
            $jlap = "Laporan per Member";
            $member = $dbProd->query(
                "SELECT *
                FROM " . $viewTidakBelanja . "
                WHERE  cus_kodemember IS NOT NULL 
                $filtersts
                ORDER BY cus_kodemember"
            );
            $member = $member->getResultArray();
        } else if($jenisLaporan == "2") {
            $jlap = "Laporan per Outlet";
            $member = $dbProd->query(
                "SELECT 
                cus_kodeoutlet,
                out_namaoutlet,
                cus_kodesuboutlet,
                sub_namasuboutlet,
                COUNT(cus_kodemember) AS jumlah_member,
                SUM(poc_saldopoint)   AS jumlah_poin,
                SUM(kun_hari)         AS jumlah_hari,
                COUNT(DISTINCT(cus_alamatmember8)) jumlah_kelurahan,
                COUNT(DISTINCT(cus_alamatmember6)) jumlah_kota
                FROM " . $viewTidakBelanja . ",
                    tbmaster_outlet,
                    tbmaster_suboutlet
                WHERE  cus_kodemember IS NOT NULL 
                    AND cus_kodeoutlet = out_kodeoutlet (+)
                    AND cus_kodesuboutlet = sub_kodesuboutlet (+)
                    $filtersts 
                GROUP BY cus_kodeoutlet, out_namaoutlet, cus_kodesuboutlet,sub_namasuboutlet 
                ORDER BY cus_kodeoutlet, cus_kodesuboutlet "
            );
            $member = $member->getResultArray();
        };

        $data = [
            'title' => 'Member Tidak Berblanja',
            'member' => $member,
            'jlap' => $jlap,
        ];

        if($aksi == 'btnxls') {
            $filename = "[". $jlap. "] Member Tidak Belanja ".date('d M Y').".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        
            return view('problem/tampilmember',$data);
        };

        return view('/problem/tampilmember',$data);
    }
}