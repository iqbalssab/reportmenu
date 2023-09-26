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

    public function belumaktivasi() {
        $dbProd = db_connect('production');
        $mbrmerah = $mbrbiru = [];

        $mbrmerah = $dbProd->query(
            "SELECT cus_kodemember,
            cus_namamember,
            cus_alamatmember5,
            cus_alamatmember6,
            cus_alamatmember7,
            cus_alamatmember8,
            cus_hpmember,
            cus_tlpmember
            FROM tbmaster_customer
            WHERE cus_recordid               IS NULL
            AND NVL(cus_flagmemberkhusus,'T') = 'Y'
            AND cus_kodeigr                   =
                (SELECT prs_kodeigr FROM tbmaster_perusahaan)
            AND cus_namamember NOT LIKE 'NEW%'
            AND cus_kodemember NOT IN
                (SELECT DISTINCT jh_cus_kodemember AS kun_kode_member
                FROM tbtr_jualheader
                WHERE jh_cus_kodemember IS NOT NULL) "
        );
        $mbrmerah = $mbrmerah->getResultArray();

        $mbrbiru = $dbProd->query(
            "SELECT cus_kodemember,
            cus_namamember,
            cus_alamatmember5,
            cus_alamatmember6,
            cus_alamatmember7,
            cus_alamatmember8,
            cus_hpmember,
            cus_tlpmember
            FROM tbmaster_customer
            WHERE cus_recordid               IS NULL
            AND NVL(cus_flagmemberkhusus,'T') = 'T'
            AND cus_kodeigr                   =
                (SELECT prs_kodeigr FROM tbmaster_perusahaan)
            AND cus_namamember NOT LIKE 'NEW%'
            AND cus_kodemember NOT IN
                (SELECT DISTINCT jh_cus_kodemember AS kun_kode_member
                FROM tbtr_jualheader
                WHERE jh_cus_kodemember IS NOT NULL)"
        );
        $mbrbiru = $mbrbiru->getResultArray();
        
        $data = [
            'title' => 'Data Member Belum Aktivasi',
            'mbrmerah' => $mbrmerah,
            'mbrbiru' => $mbrbiru,
        ];

        return view('/problem/belumaktivasi',$data);
    }

    public function itemtagnxmasih() {
        $dbProd = db_connect('production');
        $tagx = $tagn = [];

        $viewHargaBeli =
        " (SELECT hgb.hgb_prdcd,
                hgb.hgb_hrgbeli,
                hgb.hgb_statusbarang,
                hgb.hgb_tglmulaidisc01,
                hgb.hgb_tglakhirdisc01,
                hgb.hgb_persendisc01,
                hgb.hgb_rphdisc01,
                hgb.hgb_flagdisc01,
                hgb.hgb_tglmulaidisc02,
                hgb.hgb_tglakhirdisc02,
                hgb.hgb_persendisc02,
                hgb.hgb_rphdisc02,
                hgb.hgb_flagdisc02,
                hgb.hgb_nilaidpp,
                hgb.hgb_top,
                hgb.hgb_kodesupplier,
                sup.sup_namasupplier AS hgb_namasupplier,
                sup.sup_jangkawaktukirimbarang AS hgb_lead_time,
                sup.sup_minrph as hgb_minrph
            FROM   tbmaster_hargabeli hgb,
                tbmaster_supplier sup
            WHERE  hgb.hgb_tipe = '2'
                AND hgb.hgb_kodesupplier = sup.sup_kodesupplier (+)) ";
        
        $bln_01 = date('m', strtotime('-3 month')) ;
        $bln_02 = date('m', strtotime('-2 month')) ;
        $bln_03 = date('m', strtotime('-1 month')) ;
            
        $viewSalesPerDay =
            " (SELECT sls_prdcd                               AS spd_prdcd,
            Nvl(sls_qty_" . $bln_01  .", 0)                      AS spd_qty_1,
            Nvl(sls_qty_" . $bln_02  .", 0)                      AS spd_qty_2,
            Nvl(sls_qty_" . $bln_03  .", 0)                      AS spd_qty_3,
            Trunc(( Nvl(sls_qty_" . $bln_01  .", 0) + Nvl(sls_qty_" . $bln_02  .", 0) + Nvl(sls_qty_" . $bln_03  .", 0) ) / 90, 5) AS spd_qty,
            Nvl(sls_rph_" . $bln_01  .", 0)                      AS spd_rph_1,
            Nvl(sls_rph_" . $bln_02  .", 0)                      AS spd_rph_2,
            Nvl(sls_rph_" . $bln_03  .", 0)                      AS spd_rph_3,
            Trunc(( Nvl(sls_rph_" . $bln_01  .", 0) + Nvl(sls_rph_" . $bln_02  .", 0) + Nvl(sls_rph_" . $bln_03  .", 0) ) / 90, 5) AS spd_rph
            FROM   tbtr_salesbulanan   ) ";

        $viewPOOutstanding =
            "(SELECT tpod_prdcd ,SUM(tpod_qtypo) AS tpod_qtypo,Count(tpod_nopo) AS tpod_nopo
              FROM (SELECT tpod_prdcd, tpod_qtypo, tpod_nopo
                FROM   tbtr_po_d
                WHERE  tpod_nopo IN (SELECT tpoh_nopo
                    FROM   tbtr_po_h
                    WHERE  tpoh_recordid IS NULL AND Trunc(tpoh_tglpo + tpoh_jwpb) >= Trunc(SYSDATE)
                )
                -- revisi sesuai permintaan Bp MAO
                -- PO Out tidak memperhitungkan PB Outstansding
                -- 19-11-2015 10:45
                --UNION ALL
                --SELECT pbd_prdcd,
                --       pbd_qtypb,
                --       pbd_nopb
                --FROM   tbtr_pb_d
                --WHERE  pbd_recordid IS NULL
            ) GROUP  BY tpod_prdcd)";
        
        $viewStatusIgrIdm =
            " (SELECT 
            PRD_PRDCD,
            CASE WHEN FLAG = 'NAS-IGR+K.IGR' THEN 'IGR-ONLY'
            WHEN FLAG = 'NAS' THEN 'IGR-ONLY'
            WHEN FLAG = 'IGR+K.IGR' THEN 'IGR-ONLY'
            WHEN FLAG = 'IGR' THEN 'IGR-ONLY'
            
            WHEN FLAG = 'NAS-OMI' THEN 'OMI-ONLY'
            WHEN FLAG = 'OMI' THEN 'OMI-ONLY'
            ELSE 'IGR-OMI' END AS STATUS_IGR_IDM 
            
            
            FROM (
            SELECT PRD_PRDCD ,
            
            CASE
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYYYY' THEN 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYYYN' THEN 'NAS-IGR+IDM+OMI+MR.BRD+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYYNN' THEN 'NAS-IGR+IDM+OMI+MR.BRD'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNYY' THEN 'NAS-IGR+IDM+OMI+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNYN' THEN 'NAS-IGR+IDM+OMI+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNNY' THEN 'NAS-IGR+IDM+OMI+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYYNNN' THEN 'NAS-IGR+IDM+OMI'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNYYY' THEN 'NAS-IGR+IDM+MR.BRD+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNYY' THEN 'NAS-IGR+IDM+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNYN' THEN 'NAS-IGR+IDM+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNNY' THEN 'NAS-IGR+IDM+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYYNNNN' THEN 'NAS-IGR+IDM'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNYY' THEN 'NAS-IGR+OMI+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNYN' THEN 'NAS-IGR+OMI+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNNY' THEN 'NAS-IGR+OMI+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNYNNN' THEN 'NAS-IGR+OMI'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNYYN' THEN 'NAS-IGR+MR.BRD+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNYNN' THEN 'NAS-IGR+MR.BRD'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNYY' THEN 'NAS-IGR+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNYN' THEN 'NAS-IGR+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNNY' THEN 'NAS-IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YYNNNNN' THEN 'NAS-IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNYY' THEN 'NAS-IDM+OMI+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNYN' THEN 'NAS-IDM+OMI+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNNY' THEN 'NAS-IDM+OMI+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYYNNN' THEN 'NAS-IDM+OMI'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNYY' THEN 'NAS-IDM+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNYN' THEN 'NAS-IDM+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNNY' THEN 'NAS-IDM+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNYNNNN' THEN 'NAS-IDM'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNYYNN' THEN 'NAS-OMI+MR.BRD'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNYNYN' THEN 'NAS-OMI+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNYNNN' THEN 'NAS-OMI'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNNYNN' THEN 'NAS-MR.BRD'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNNNYN' THEN 'NAS-K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='YNNNNNN' THEN 'NAS'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNYY' THEN 'IGR+IDM+OMI+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNYN' THEN 'IGR+IDM+OMI+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNNY' THEN 'IGR+IDM+OMI+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYYNNN' THEN 'IGR+IDM+OMI'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNYY' THEN 'IGR+IDM+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNYN' THEN 'IGR+IDM+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNNY' THEN 'IGR+IDM+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYYNNNN' THEN 'IGR+IDM'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNYNYY' THEN 'IGR+OMI+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNYNYN' THEN 'IGR+OMI+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNYNNN' THEN 'IGR+OMI'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNYYN' THEN 'IGR+MR.BRD+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNYY' THEN 'IGR+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNYN' THEN 'IGR+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNNY' THEN 'IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NYNNNNN' THEN 'IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNYY' THEN 'IDM+OMI+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNYN' THEN 'IDM+OMI+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNNY' THEN 'IDM+OMI+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYYNNN' THEN 'IDM+OMI'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNYY' THEN 'IDM+K.IGR+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNYN' THEN 'IDM+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNNY' THEN 'IDM+DEPO'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNYNNNN' THEN 'IDM'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNYNYN' THEN 'OMI+K.IGR'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNYNNN' THEN 'OMI'
            
            WHEN NAS||IGR||IDM||OMI||BRD||K_IGR||DEPO ='NNNNNNN' THEN 'BELUM ADA FLAG'
            
            ELSE 'BELUM ADA FLAG'
            
            END AS FLAG
            
            FROM
            
            (SELECT prd_prdcd,prd_plumcg,
            
            nvl(PRD_FLAGNAS,'N') AS NAS,
            
            nvl(PRD_FLAGIGR,'N') AS IGR,
            
            nvl(PRD_FLAGIDM,'N') AS IDM,
            
            nvl(PRD_FLAGOMI,'N') AS OMI,
            
            nvl(PRD_FLAGBRD,'N') AS BRD,
            
            nvl(PRD_FLAGOBI,'N') AS K_IGR,
            
            case when prd_plumcg in (select PLUIDM from DEPO_LIST_IDM ) THEN 'Y' ELSE 'N' END AS DEPO
            
            FROM TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0' AND PRD_DESKRIPSIPANJANG IS NOT NULL))) ";

        $viewLppSaatIni =
            " (SELECT prd.prd_kodedivisi    AS st_div,
                div.div_namadivisi         AS st_div_nama,
                prd.prd_kodedepartement    AS st_dept,
                dep.dep_namadepartement    AS st_dept_nama,
                prd.prd_kodekategoribarang AS st_katb,
                kat.kat_namakategori       AS st_katb_nama,
                prd.prd_prdcd              AS st_prdcd,
                prd.prd_deskripsipanjang   AS st_deskripsi,
                prd.prd_unit               AS st_unit,
                prd.prd_frac               AS st_frac,
                NVL(prd.prd_kodetag,' ')   AS st_kodetag,
                CASE
                  WHEN NVL(prd.prd_kodetag,' ') IN ('A','R','N','H','O','T','X')
                  THEN 'Discontinue'
                  ELSE 'Active'
                END st_status_tag,
                stk.st_lokasi                         AS st_lokasi,
                (NVL(stk.st_saldoakhir,0) - MOD(NVL(stk.st_saldoakhir,0),prd.prd_frac)) /prd.prd_frac AS st_saldo_ctn,
                --FLOOR(NVL(stk.st_saldoakhir,0)/prd.prd_frac) AS st_saldo_ctn,
                MOD(NVL(stk.st_saldoakhir,0),prd.prd_frac)   AS st_saldo_pcs,
                stk.st_saldoakhir                     AS st_saldo_in_pcs,
                stk.ST_AVGCOST                       AS st_avgcost,
                CASE
                  WHEN prd.prd_unit='KG' AND prd.prd_frac =1000
                  THEN stk.st_saldoakhir * stk.st_avgcost/1000
                  ELSE stk.st_saldoakhir * stk.st_avgcost
                END st_saldo_rph,
                
                stk.st_lastcost                       AS st_lastcost,
                CASE
                  WHEN prd.prd_unit='KG' AND prd.prd_frac =1000
                  THEN stk.st_saldoakhir * stk.st_lastcost/1000
                  ELSE stk.st_saldoakhir * stk.st_lastcost
                END st_saldo_rph_lastcost,
                
                pkm.pkm_pkmt                          AS st_pkm,
                spd.spd_qty                           AS st_spd,
                CASE
                  WHEN NVL(spd.spd_qty,0) > 0
                  THEN ROUND(stk.st_saldoakhir / spd.spd_qty)
                  ELSE 999999
                END st_dsi,
                poo.tpod_qtypo          AS st_po_qty,
                sii.status_igr_idm      AS st_igr_idm,
                spd.spd_qty_1 as st_sales_bln_1,
                spd.spd_qty_2 as st_sales_bln_2,
                spd.spd_qty_3 as st_sales_bln_3,
                stk.st_sales  as st_sales_bln_ini,
                NVL(hgb.hgb_kodesupplier,'Z9999')    AS st_supp_kode,
                NVL(hgb.hgb_namasupplier,'Z9999 Tidak diketahui')    AS st_supp_nama,
                prd.prd_perlakuanbarang AS st_perlakuan_barang,
                hgb.hgb_hrgbeli * prd.prd_frac  as st_harga_beli,
                hgb.hgb_nilaidpp * prd.prd_frac as st_harga_beli_netto,
                hgb.hgb_nilaidpp                as st_harga_beli_omi,
                hgb.hgb_tglmulaidisc01 as st_disc_1_mulai,
                hgb.hgb_tglakhirdisc01 as st_disc_1_selesai,
                hgb.hgb_persendisc01 as st_disc_1_persen,
                hgb.hgb_rphdisc01 as st_disc_1_rph,
                hgb.hgb_flagdisc01 as st_disc_1_flag,
                
                hgb.hgb_tglmulaidisc02 as st_disc_2_mulai,
                hgb.hgb_tglakhirdisc02 as st_disc_2_selesai,
                hgb.hgb_persendisc02 as st_disc_2_persen,
                hgb.hgb_rphdisc02 as st_disc_2_rph,
                hgb.hgb_flagdisc02 as st_disc_2_flag
                
                
                
              FROM tbmaster_prodmast prd,
                tbmaster_stock stk,
                tbmaster_kkpkm pkm,
                tbmaster_divisi div,
                tbmaster_departement dep,
                " . $viewHargaBeli ." hgb,
                (SELECT kat_kodedepartement || kat_kodekategori AS kat_kodekategori, kat_namakategori FROM tbmaster_kategori) kat,
                " . $viewSalesPerDay  . " spd,
                " . $viewPOOutstanding  ." poo,
                " . $viewStatusIgrIdm  . " sii
              WHERE prd.prd_prdcd         = stk.st_prdcd (+)
              AND prd.prd_prdcd           = pkm.pkm_prdcd (+)
              AND prd.prd_prdcd           = hgb.hgb_prdcd (+)
              AND prd.prd_kodedivisi      = div.div_kodedivisi (+)
              AND prd.prd_kodedepartement = dep.dep_kodedepartement (+)
              AND prd.prd_kodedepartement || prd.prd_kodekategoribarang = kat.kat_kodekategori (+)
              AND prd.prd_prdcd           = spd.spd_prdcd (+)
              AND prd.prd_prdcd           = poo.tpod_prdcd (+)
              AND prd.prd_prdcd           = sii.prd_prdcd (+)
              AND prd.prd_prdcd LIKE '%0') ";
        
        $tagn = $dbProd->query(
            "SELECT * FROM " . $viewLppSaatIni . "
            WHERE st_lokasi         = '01'
            AND NVL(st_saldo_in_pcs,0)  <> 0
            AND NVL(st_kodetag,' ') = 'N'
            ORDER BY st_div,st_dept,st_katb"
        );
        $tagn = $tagn->getResultArray();
        
        $tagx = $dbProd->query(
            "SELECT * FROM " . $viewLppSaatIni . "
            WHERE st_lokasi         = '01'
            AND NVL(st_saldo_in_pcs,0)  <> 0
            AND NVL(st_kodetag,' ') = 'X'
            ORDER BY st_div,st_dept,st_katb"
        );
        $tagx = $tagx->getResultArray();
        
        $data = [
            'title' => 'Stock Item Tag N & X Masih Ada',
            'tagn' =>$tagn,
            'tagx' => $tagx,
        ];

        return view('/problem/itemtagnxmasih',$data);
    }

    public function membertidur()
        {
            $dbProd = db_connect('production');

            $btn = $this->request->getVar('btn');
            $tglakhir = $this->request->getVar('tglakhir');
            $periode1 = $this->request->getVar('periode1');
            $periode2 = $this->request->getVar('periode2');
            $periode3 = $this->request->getVar('periode3');

            $membertidur = [];

            if ($btn=="tampil") {
                $membertidur = $dbProd->query(
                    "SELECT kodemember, namamember, tgl_akhir, alamat2, alamat4, avg_kunj, avg_sales, avg_margin, sisa_saldo, hpmember, ktp 
                    FROM (
                    select
                      cus_kodeigr as cabang,
                      kodemember, 
                      cus_namamember as namamember,
                      tgl_last_belanja as tgl_akhir,
                      cus_alamatmember2 as alamat2, 
                      cus_alamatmember4 as alamat4,
                      ((kunj_bln_1 + kunj_bln_2 + kunj_bln_3)/3) as avg_kunj,
                      ((sales_bln_1 + sales_bln_2 + sales_bln_3)/3) as avg_sales,
                      ((margin_bln_1 + margin_bln_2 + margin_bln_3)/3) as avg_margin,
                      cus_hpmember as hpmember,
                      cus_noktp as ktp,
                      (perolehanpoin - penukaranpoin) as sisa_saldo
                    from tbmaster_customer
                    right join (select jh_cus_kodemember as kodemember, max(trunc(jh_transactiondate)) as tgl_last_belanja 
                          from tbtr_jualheader
                          where trunc(jh_transactiondate) >= trunc(sysdate)- 365
                          group by jh_cus_kodemember
                          order by tgl_last_belanja) on cus_kodemember = kodemember
                    LEFT join (  
                    select   
                      membersales,  
                        
                      sum(case when periodesales='$periode1' then kunjungansales end ) as KUNJ_BLN_1,  
                      sum(case when periodesales='$periode1' then sales_all end ) as SALES_BLN_1,    
                      sum(case when periodesales='$periode1' then marginsales end ) as MARGIN_BLN_1,  
                        
                      sum(case when periodesales='$periode2' then kunjungansales end ) as KUNJ_BLN_2,  
                      sum(case when periodesales='$periode2' then sales_all end ) as SALES_BLN_2,   
                      sum(case when periodesales='$periode2' then marginsales end ) as MARGIN_BLN_2,  
                        
                      sum(case when periodesales='$periode3' then kunjungansales end ) as KUNJ_BLN_3,  
                      sum(case when periodesales='$periode3' then sales_all end ) as SALES_BLN_3,   
                      sum(case when periodesales='$periode3' then marginsales end ) as MARGIN_BLN_3  
                        
                    from (  
                      select   
                      trjd_cus_kodemember as membersales,  
                      to_char(trjd_transactiondate,'yyyy-mm') as periodesales,  
                      trjd_cus_kodemember||'.'||to_char(trjd_transactiondate,'yyyymm') as lookupsales,  
                      count(distinct trunc(trjd_transactiondate)) as kunjungansales,  
                      sum(case   
                        when (trjd_transactiontype='S') then trjd_nominalamt*1   
                        when (trjd_transactiontype='R') then trjd_nominalamt*(-1)   
                        end )as sales_all,  
                      sum(case   
                        when (trjd_transactiontype='S') and trjd_division like '14%' then trjd_nominalamt*1  
                        when (trjd_transactiontype='R') and trjd_division like '14%' then trjd_nominalamt*(-1)   
                        end )as sales_rokok,  
                      sum(case          
                        /*non omi */          
                        when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))          
                        when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))          
                        when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))*(-1)          
                        when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))*(-1)          
                        /*pengecualian untuk unit KG*/          
                        when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (prd_unit ='KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity/1000))          
                        when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))          
                        end) as marginsales  
                      from tbtr_jualdetail  
                      left join tbmaster_prodmast on prd_prdcd=trjd_prdcd  
                      where trunc(trjd_transactiondate)>='01-APR-23'  
                      group by trjd_cus_kodemember, to_char(trjd_transactiondate,'yyyy-mm'), trjd_cus_kodemember||'.'||to_char(trjd_transactiondate,'yyyymm')  
                      ) group by membersales  
                    ) on membersales=cus_kodemember 
                    LEFT JOIN  
                    (  
                    SELECT POR_KODEMEMBER KD_PEROLEHAN,  
                           POR_KODEMYPOIN NOHP_PEROLEHAN,  
                           SUM(POR_PEROLEHANPOINT) PEROLEHANPOIN  
                    FROM TBTR_PEROLEHANMYPOIN  
                    WHERE TRUNC(POR_CREATE_DT) >= '01-JAN-23'  
                    group by POR_KODEMEMBER, POR_KODEMYPOIN  
                    ) ON CUS_KODEMEMBER = KD_PEROLEHAN AND CUS_HPMEMBER = NOHP_PEROLEHAN  
                    LEFT JOIN  
                    (  
                    SELECT POT_KODEMEMBER KD_PENUKARAN,  
                           POT_KODEMYPOIN NOHP_PENUKARAN,  
                           SUM(POT_PENUKARANPOINT) PENUKARANPOIN  
                    FROM TBTR_PENUKARANMYPOIN  
                    WHERE TRUNC(POT_CREATE_DT) >= '01-JAN-23'  
                    group by POT_KODEMEMBER, POT_KODEMYPOIN  
                    ) ON CUS_KODEMEMBER = KD_PENUKARAN AND CUS_HPMEMBER = NOHP_PENUKARAN
                    where cus_recordid is null
                    and cus_kodeigr ='25'
                    and cus_flagmemberkhusus = 'Y'
                    and (cus_tglregistrasi is not null  or cus_tglmulai is not null)
                    order by tgl_last_belanja)"
                );
    
                $membertidur = $membertidur->getResultArray();
            }            

            $data = [
                'title' => 'Member Tidur',
                'membertidur' => $membertidur,
                'tglakhir' => $tglakhir,
                'periode1' => $periode1,
                'periode2' => $periode2,
                'periode3' => $periode3,
            ];

            d($data);

            return view('problem/membertidur', $data);
        }
}