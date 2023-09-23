<?php

namespace App\Controllers;

class Idm extends BaseController
{
    public function index()
    {
        return view('idm/index',);
    }

    public function idmcluster() {
        $dbProd = db_connect('production');
        $clusteridm = [];

        $clusteridm = $dbProd->query(
            "SELECT t.tko_kodeomi AS tko_kode_omi,
            t.tko_namaomi      AS tko_nama_omi,
            p.tko_pb_pertama   AS tko_pb_pertama,
            p.tko_pb_terakhir  AS tko_pb_terakhir,
            r.rpb_intransit    AS tko_intransit,
            t.tko_kodecustomer AS tko_kode_customer,
            --cus.cus_namamember AS tko_nama_member,
            --cus.cus_flagpkp    AS tko_pkp,
            cus.cus_alamatmember5 AS tko_alamat,
            cus.cus_alamatmember8 AS tko_kelurahan,
            cus.cus_alamatmember6 AS tko_kota,
            cus.cus_alamatmember7 AS tko_kode_pos,
            TRIM(cus.cus_tlpmember) || ' ' || TRIM(cus.cus_hpmember) as tko_nomor_hp,
            cls.cls_kode as tko_cls_kode,
            cls.cls_group AS tko_cls_group,
            cls.cls_gr1 AS tko_cls_gr1,
            cls.cls_gr2 AS tko_cls_gr2,
            cls.cls_gr3 AS tko_cls_gr3,
            cls.cls_jarakkirim AS tko_cls_jarakkirim,
            cls.cls_jarakasli AS tko_cls_jarakasli,
            cls.cls_mobil AS tko_cls_mobil
    
            FROM tbmaster_tokoigr t,
                    (SELECT hpb_kodeomi AS tko_kode_omi, MIN(TRUNC(hpb_tgltransaksi)) AS tko_pb_pertama, MAX(TRUNC(hpb_tgltransaksi)) AS tko_pb_terakhir
                    FROM tbhistory_pbomi
                    GROUP BY hpb_kodeomi) p,
                    (SELECT rpb_kodeomi, SUM(rpb_ttlnilai + rpb_ttlppn) AS rpb_intransit 
                    FROM tbtr_realpb
                    WHERE rpb_flag= '2'
                    GROUP BY rpb_kodeomi) r,
                    tbmaster_customer cus,
                    cluster_idm cls
            WHERE t.tko_kodeomi = p.tko_kode_omi (+)
                AND t.tko_kodeomi = r.rpb_kodeomi (+)
                AND t.tko_kodecustomer = cus.cus_kodemember (+)
                    AND t.tko_kodeomi = cls.cls_toko (+)
            ORDER BY tko_cls_group, tko_cls_jarakasli "
        );
        $clusteridm = $clusteridm->getResultArray();

        $data = [
            'title' => 'Daftar Cluster IDM',
            'clusteridm' => $clusteridm,
        ];

        redirect()->to('/idmcluster')->withInput();
        return view('/idm/idmcluster',$data);
    }

    public function rakdpd() {
        $dbProd = db_connect('production');
        $kdrak = $rakdpd = [];
        $kode = $this->request->getVar('kodeRak');
        $aksi = $this->request->getVar('tombol');

        $kdrak = $dbProd->query(
            "SELECT DISTINCT lks_koderak
            FROM tbmaster_lokasi
            WHERE lks_koderak LIKE 'D%'
                AND lks_koderak NOT LIKE '%C%'
                AND lks_tiperak IN ('B','N')
            ORDER BY lks_koderak"
        );
        $kdrak = $kdrak->getResultArray();

        if($aksi == "btnview") {
            $rakdpd = $dbProd->query(
                "SELECT * 
                FROM view_rak_dpd 
                WHERE lks_koderak = '$kode'
                ORDER BY lks_koderak, lks_shelvingrak DESC, lks_kodesubrak, lks_nourut"
            );
            $rakdpd = $rakdpd->getResultArray();
        };

        $data = [
            'title' => 'View Rak DPD',
            'kdrak' => $kdrak,
            'rakdpd' => $rakdpd,
        ];

        redirect()->to('rakdpd')->withInput();
        return view('/idm/rakdpd',$data);
    }

    public function fakturpajak() {
        $dbProd = db_connect('production');
        $pajak = [];

        $pajak = $dbProd->query(
            "SELECT *
            FROM view_idm_faktur_pajak
            ORDER BY fkt_tgl DESC,
              fkt_station DESC,
              fkt_kasir DESC,
              fkt_notransaksi "
        );
        $pajak = $pajak->getResultArray();

        $data = [
            'title' => 'Faktur Pajak IDM',
            'pajak' => $pajak,
        ];

        return view('/idm/fakturpajak',$data);
    }

    public function fakturpajakout() {
        $dbProd = db_connect('production');
        $pajak = [];

        $pajak = $dbProd->query(
            "SELECT *
            FROM view_idm_faktur_pajak_out
            ORDER BY fkt_tgl DESC,
              fkt_station DESC,
              fkt_kasir DESC,
              fkt_notransaksi "
        );
        $pajak = $pajak->getResultArray();

        $data = [
            'title' => 'Faktur Pajak IDM',
            'pajak' => $pajak,
        ];

        return view('/idm/fakturpajakout',$data);
    }

    public function outstandingretur() {
        $dbProd = db_connect('production');
        $retur = [];

        $retur = $dbProd->query(
            "SELECT
            CASE
              WHEN w.istype = '01'
              THEN 'P'
              ELSE 'F'
            END    AS istype,
            w.TGL1 AS tanggal,
            w.shop,
            t.tko_namaomi,
            w.docno,
            w.prdcd AS pluidm,
            p.prd_prdcd,
            p.prd_deskripsipanjang,
            w.qty,
            w.price,
            w.gross AS netto,
            w.ppn,
            w.gross + ppn AS gross ,
            w.wt_create_dt,
            l.LKS_KODERAK||'.'|| LKS_KODESUBRAK||'.'|| LKS_TIPERAK||'.'|| LKS_SHELVINGRAK||'.'|| LKS_NOURUT lokasi
            FROM tbtr_wt_interface w,
                (SELECT * FROM tbmaster_prodmast WHERE prd_prdcd LIKE '%0'
                ) p,
                (select * from tbmaster_lokasi where lpad(LKS_KODERAK,1)='D' and LKS_TIPERAK<>'S') l,
                tbmaster_tokoigr t
            WHERE w.prdcd = p.prd_plumcg (+)
            AND w.shop    = t.tko_kodeomi (+)
            AND p.PRD_PRDCD   = l.LKS_PRDCD (+)
            AND w.recid  <> 'P'
            ORDER BY tanggal,
                shop,
                docno"
        );
        $retur = $retur->getResultArray();

        $data = [
            'title' => 'IDM Oustanding Retur',
            'retur' => $retur,
        ];

        return view('/idm/outstandingretur',$data);
    }

    public function itemkosongklik() {
        $dbProd = db_connect('production');

        $data = [
            'title' => 'Item Kosong PB IDM',
        ];

        redirect()->to('/itemkosongklik')->withInput();
        return view('/idm/itemkosongklik',$data);
    }

    public function tampilitemkosong() {
        $dbProd = db_connect('production');
        $klik = [];

        //Inisiasi
        $kodePLU = $kodeID = "All"; 
        $filterid = $filterplu = '';

        if(isset($_GET['awal'])) {if ($_GET['awal'] !=""){$tanggalMulai = $_GET['awal']; }}
        if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tanggalSelesai = $_GET['akhir']; }}

        if(isset($_GET['plu'])) {if ($_GET['plu'] !=""){$kodePLU = $_GET['plu']; }}
        if ($kodePLU != "All" AND $kodePLU != "") {
            $kodePLU = substr('00000000' . $kodePLU, -7);
            $filterplu = " AND substr(PLU,1,6) || '0' = '$kodePLU' ";    
        }
        
        if(isset($_GET['notrans'])) {if ($_GET['notrans'] !=""){$kodeID = $_GET['notrans']; }}
        if ($kodeID  != "All" AND $kodeID != "") {
            $kodeID = substr('000000' . $kodeID, -5);
            $filterid = " AND NOTRANS = '$kodeID'  ";    
        }

        if(isset($_GET['jenisLaporan'])) {if ($_GET['jenisLaporan'] !=""){$jenisLaporan = $_GET['jenisLaporan']; }}

        if($jenisLaporan == "1") {
            $klik = $dbProd->query(
                "SELECT NOTRANS,TGLTRANS,PLU, DESK,QTYORDER, LPP FROM (
                    SELECT ((SUBSTR(OBI_PRDCD,1,6))||0) AS PLU, PRD_DESKRIPSIPANJANG DESK, OBI_QTYORDER QTYORDER, OBI_NOTRANS NOTRANS, OBI_TGLTRANS TGLTRANS
                    FROM TBTR_OBI_D 
                    LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = OBI_PRDCD
                    WHERE trunc(OBI_TGLTRANS) between to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd'))
                LEFT JOIN (SELECT ST_PRDCD, ST_SALDOAKHIR LPP FROM TBMASTER_STOCK WHERE ST_LOKASI = '01')
                    ON ST_PRDCD = PLU
                WHERE LPP = '0'
                $filterid
                $filterplu
                order by TGLTRANS, NOTRANS"
            );
            $klik = $klik->getResultArray();
        };

        $data = [
            'title' => 'Data Item Kosong PB IDM',
            'klik' => $klik,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'kodePLU' => $kodePLU,
            'kodeID' => $kodeID,
            'jenisLaporan' => $jenisLaporan,
        ];

        redirect()->to('/tampilitemkosong')->withInput();
        return view('/idm/tampilitemkosong',$data);
    }

    public function tolakanpbidm() {
        $dbProd = db_connect('production');
        $tokoOmi = $tolakan = [];

        $tokoOmi = $dbProd->query(
            "SELECT tko_kodeomi,tko_namaomi FROM tbmaster_tokoigr WHERE tko_kodesbu = 'I' ORDER BY tko_kodeomi"
        );
        $tokoOmi = $tokoOmi->getResultArray();

        $tolakan = $dbProd->query(
            "SELECT DISTINCT tlko_kettolakan
            FROM   tbtr_tolakanpbomi
            WHERE  Trunc(tlko_create_dt) > Trunc(SYSDATE) - 60
            ORDER  BY tlko_kettolakan "
        );
        $tolakan = $tolakan->getResultArray();

        $data = [
            'title' => 'Tolakan PB IDM',
            'tokoOmi' => $tokoOmi,
            'tolakan' => $tolakan,
        ];

        redirect()->to('/tolakanpbidm')->withInput();
        return view('/idm/tolakanpbidm',$data);
    }

    public function tampilpbidm() {
        $dbProd = db_connect('production');
        $tolakan = [];

        //inisiasi
        $tanggalMulai = $tanggalSelesai = date("Ymd");
        $kodeTokoOMI          = "All"; 
        $filteromi = $filtertlk = '';

        if(isset($_GET['awal'])) {if ($_GET['awal'] !=""){$tanggalMulai = $_GET['awal']; }}
        if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tanggalSelesai = $_GET['akhir']; }}

        if(isset($_GET['tokoOmi'])) {if ($_GET['tokoOmi'] !=""){$kodeTokoOMI = $_GET['tokoOmi']; }}
        if ($kodeTokoOMI != "All" AND $kodeTokoOMI != "") {
            $filteromi = " AND tlko_kode_omi = '$kodeTokoOMI' ";
        }

        if(isset($_GET['tolakan'])) {if ($_GET['tolakan'] !=""){$kodeTolakan = $_GET['tolakan']; }}
        if ($kodeTolakan != "All" AND $kodeTolakan != "") {
            $filtertlk = " AND tlko_kettolakan = '$kodeTolakan' ";
        }

        if(isset($_GET['jenisLaporan'])) {if ($_GET['jenisLaporan'] !=""){$jenisLaporan = $_GET['jenisLaporan']; }}

        $viewTolakanPbOmi = "(SELECT trunc(t.tlko_create_dt) AS tlko_tanggal,
            t.tlko_nopb,
            o.tko_kodesbu    AS tlko_kodesbu,
            t.tlko_kodeomi   AS tlko_kode_omi,
            o.tko_namaomi    AS tlko_nama_omi,
            t.tlko_pluigr,
            t.tlko_pluomi,
            t.tlko_desc,
            t.tlko_ptag,
            t.tlko_kettolakan,
            t.tlko_qtyorder,
            NVL(t.tlko_lpp,0) as tlko_lpp,
            -- NVL(t.tlko_nilai,0) as tlko_nilai
            NVL(tlko_qtyorder,0)  * NVL(tlko_lastcost,0) as tlko_nilai

            FROM   tbtr_tolakanpbomi t,
                    tbmaster_tokoigr o
            WHERE  t.tlko_kodeomi = o.tko_kodeomi
                    AND trunc(t.tlko_create_dt) BETWEEN
                    to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd'))";

        if($jenisLaporan == '1') {
            $tolakan = $dbProd->query(
                "SELECT tlko_pluomi,
                tlko_pluigr,
                tlko_desc,
                tlko_ptag,
                tlko_kettolakan,
                MIN(tlko_lpp)                    AS tlko_lpp,
                SUM(tlko_qtyorder)               AS tlko_qtyorder,
                SUM(tlko_nilai)                  AS tlko_nilai,				       
                Count(DISTINCT( tlko_kode_omi )) AS tlko_kode_omi,
                Count(DISTINCT( tlko_kode_omi || tlko_nopb ))     AS tlko_nopb,
                Count(DISTINCT( tlko_tanggal ))  AS tlko_tanggal
                FROM " . $viewTolakanPbOmi  . "
                WHERE  tlko_pluomi IS NOT NULL 
                AND  tlko_kodesbu = 'I'
                $filteromi
                $filtertlk
                GROUP BY tlko_pluomi, tlko_pluigr, tlko_desc, tlko_ptag, tlko_kettolakan
                order by tlko_pluomi"
            );
            $tolakan = $tolakan->getResultArray();
        };

        $data = [
            'title' => 'Data Tolakan PB IDM',
            'tolakan' => $tolakan,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'kodeTokoOMI' => $kodeTokoOMI,
            'kodeTolakan' => $kodeTolakan,
            'jenisLaporan' => $jenisLaporan,
        ];

        redirect()->to('/tampilpbidm')->withInput();
        return view('/idm/tampilpbidm',$data);
    }
}