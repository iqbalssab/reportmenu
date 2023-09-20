<?php

namespace App\Controllers;

class Mplano extends BaseController
{ 
    public function index()
    {
        $dbProd = db_connect('production');
        $blmturun = $dbProd->query(
            "SELECT spb_jenis,spb_recordid,
            count(case when substr(spb_lokasitujuan,0,1) not in ('D','G') then spb_prdcd end) as TOKOBLMTURUN,
            count(case when substr(spb_lokasitujuan,0,1) in ('D','G') then spb_prdcd end) as GUDANGBLMTURUN
            from tbtemp_antrianspb
            where spb_recordid is null 
            and spb_jenis='OTOMATIS'
            group by spb_jenis, spb_recordid"
        );
        $blmturun = $blmturun->getResultArray();

        $blmreal = $dbProd->query(
            "SELECT spb_jenis,spb_recordid,
            count(case when substr(spb_lokasitujuan,0,1) not in ('D','G') then spb_prdcd end) as TOKOBLMREAL,
            count(case when substr(spb_lokasitujuan,0,1) in ('D','G') then spb_prdcd end) as GUDANGBLMREAL
            from tbtemp_antrianspb
            where spb_recordid='3' 
            and spb_jenis='OTOMATIS'
            group by spb_jenis, spb_recordid"
        );

        $blmreal = $blmreal->getResultArray();

        $spbmanual = $dbProd->query(
            "SELECT spb_jenis,
            count(case when spb_recordid is null then spb_prdcd end) as MANUALBLMTURUN,
            count(case when spb_recordid='3' then spb_prdcd end) as MANUALBLMREAL
            from tbtemp_antrianspb
            where spb_jenis='MANUAL'
            group by spb_jenis"
        );
        $spbmanual = $spbmanual->getResultArray();
        $data = [
            'title'=>'Plano Mobile IGR',
            'blmreal' => $blmreal,
            'blmturun' => $blmturun,
            'spbmanual' => $spbmanual,
        ];

        return view('mplano/index',$data);
    }

    public function spbo()
    {
        $getspb = $this->request->getVar('spb');
        $dbProd = db_connect('production');
        $filterspb = $filtertujuanspb = $filterlokasi = $lksasal = $lkstujuan  = "";
        //tujuanspb
        if(isset($_GET['spb'])){
            if($_GET['spb'] == "TOKO"){
                $filtertujuanspb = "TOKO";
                $filterspb = "and substr(spb_lokasitujuan,0,1) not in ('D','G') ";
            }elseif($_GET['spb'] == "GUDANG"){
                $filtertujuanspb = "GUDANG";
                $filterspb = "and substr(spb_lokasitujuan,0,1) in ('D','G') ";
            }elseif($_GET['spb'] == "STOKO"){
                $filtertujuanspb = "STOKO";
                $filterspb = "and substr(spb_lokasiasal,0,1) not in ('D','G') and spb_lokasiasal like '%.S.%' ";
            }elseif($_GET['spb'] == "SGUDANG"){
                $filtertujuanspb = "SGUDANG";
                $filterspb = "and substr(spb_lokasiasal,0,1) in ('D','G') and spb_lokasiasal like '%.S.%' ";
            }elseif($_GET['spb'] == "DISPLAY"){
                $filtertujuanspb = "DISPLAY";
                $filterspb = "and spb_lokasiasal not like '%.S.%' ";
            }elseif($_GET['spb'] == "STOS"){
                $filtertujuanspb = "S TO S";
                $filterspb = "and spb_lokasiasal like '%.S.%' and spb_lokasitujuan like '%.S.%' ";
            }else{
                $filtertujuanspb = "ALL";
                $filterspb = "";
            }
        }

        //lokasirak
        if(isset($_GET['lksasal'])) {
            $lksasal = $_GET['lksasal'];
            $filterlokasi = "and spb_lokasiasal like '$lksasal%'";
        }elseif(isset($_GET['lkstujuan'])) {
            $lkstujuan = $_GET['lkstujuan'];
            $filterlokasi = "and spb_lokasitujuan like '$lkstujuan%' ";
        }else{
            $filterlokasi = "";
        }

        $spb = $tujuanspb = $lokasiasal = $lokasitujuan = [];

        $spb = $dbProd->query(
            "SELECT 
            spb_lokasiasal,spb_lokasitujuan,spb_jenis,spb_prdcd,spb_deskripsi,
            prd_unit,prd_frac,spb_qty,spb_minus,(spb_minus - mod(spb_minus,prd_frac))/prd_frac as MINUSCTN,mod(spb_minus,prd_frac) as MINUSPCS,
            case 
            when spb_recordid is null then 'Blm Diturunkan'
            when spb_recordid='3' then 'Blm Realisasi'
            end as STATUS,
            to_char(spb_create_dt,'DD/MM HH24:MI')as WAKTUSPB,
            to_char(spb_modify_dt,'DD/MM HH24:MI')as WAKTUTURUN
            from tbtemp_antrianspb
            left join tbmaster_prodmast on prd_prdcd=spb_prdcd
            where spb_recordid is null
            $filterspb
            $filterlokasi
            order by spb_create_dt"
        );

        $tujuanspb = $dbProd->query(
            "SELECT case when substr(spb_lokasitujuan,0,1) not in ('D','G') then 'TOKO' else 'GUDANG' end as tujuanspb,
            count(spb_prdcd) as jmlspb
            from tbtemp_antrianspb
            where spb_recordid is null
            group by case when substr(spb_lokasitujuan,0,1) not in ('D','G') then 'TOKO' else 'GUDANG' end
            order by tujuanspb"
        );

        $lokasiasal = $dbProd->query(
            "SELECT  substr(spb_lokasiasal,0,3) as lokasiasal,count(spb_prdcd) as jmlspb
            from tbtemp_antrianspb
            where spb_recordid is null
            $filterspb
            group by substr(spb_lokasiasal,0,3)
            order by lokasiasal"
        );

        $lokasitujuan = $dbProd->query(
            "SELECT substr(spb_lokasitujuan,0,3) as lokasitujuan,count(spb_prdcd) as jmlspb
            from tbtemp_antrianspb
            where spb_recordid is null
            $filterspb
            group by substr(spb_lokasitujuan,0,3)
            order by lokasitujuan"
        );

        $lokasitujuan = $lokasitujuan->getResultArray();
        $lokasiasal = $lokasiasal->getResultArray();
        $tujuanspb = $tujuanspb->getResultArray();
        $spb = $spb->getResultArray();

        $data = [
            'title' => 'SPB Belum Diturunkan',
            'spb' => $spb,
            'tujuanspb' => $tujuanspb,
            'lokasiasal' => $lokasiasal,
            'lokasitujuan' => $lokasitujuan,
            'filtertujuanspb' => $filtertujuanspb,
            'lskasal' => $lksasal,
            'lkstujuan' => $lkstujuan,
        ];
        return view('mplano/spbo', $data);
    }

    public function spbo3()
    {
        $dbProd = db_connect('production');

        $filterspb = $filtertujuanspb = $filterlokasi = $lksasal = $lkstujuan  = "";
        $spb = $tujuanspb = $lokasiasal = $lokasitujuan = [];
        //tujuanspb
        if(isset($_GET['spb'])){
            if($_GET['spb'] == "TOKO"){
                $filtertujuanspb = "TOKO";
                $filterspb = "and substr(spb_lokasitujuan,0,1) not in ('D','G') ";
            }elseif($_GET['spb'] == "GUDANG"){
                $filtertujuanspb = "GUDANG";
                $filterspb = "and substr(spb_lokasitujuan,0,1) in ('D','G') ";
            }elseif($_GET['spb'] == "STOKO"){
                $filtertujuanspb = "STOKO";
                $filterspb = "and substr(spb_lokasitujuan,0,1) not in ('D','G') and spb_lokasitujuan like '%.S.%' ";
            }elseif($_GET['spb'] == "SGUDANG"){
                $filtertujuanspb = "SGUDANG";
                $filterspb = "and substr(spb_lokasitujuan,0,1) in ('D','G') and spb_lokasitujuan like '%.S.%' ";
            }elseif($_GET['spb'] == "DISPLAY"){
                $filtertujuanspb = "DISPLAY";
                $filterspb = "and spb_lokasitujuan not like '%.S.%' ";
            }else{
                $filtertujuanspb = "ALL";
                $filterspb = "";
            }
        }

        //lokasirak
        if(isset($_GET['lksasal'])) {
            $lksasal = $_GET['lksasal'];
            $filterlokasi = "and spb_lokasiasal like '$lksasal%'";
        }elseif(isset($_GET['lkstujuan'])) {
            $lkstujuan = $_GET['lkstujuan'];
            $filterlokasi = "and spb_lokasitujuan like '$lkstujuan%' ";
        }else{
            $filterlokasi = "";
        }

        // Query
        $spb = $dbProd->query(
            "SELECT 
            spb_lokasiasal,spb_lokasitujuan,spb_jenis,spb_prdcd,spb_deskripsi,
            prd_unit,prd_frac,spb_qty,spb_minus,(spb_minus - mod(spb_minus,prd_frac))/prd_frac as MINUSCTN,mod(spb_minus,prd_frac) as MINUSPCS,
            case 
            when spb_recordid is null then 'Blm Diturunkan'
            when spb_recordid='3' then 'Blm Realisasi'
            end as STATUS,
            to_char(spb_create_dt,'DD/MM HH24:MI')as WAKTUSPB,
            to_char(spb_modify_dt,'DD/MM HH24:MI')as WAKTUTURUN
            from tbtemp_antrianspb
            left join tbmaster_prodmast on prd_prdcd=spb_prdcd
            where spb_recordid='3'
            $filterspb
            $filterlokasi
            order by spb_create_dt"
        );

        $tujuanspb = $dbProd->query(
            "SELECT case when substr(spb_lokasitujuan,0,1) not in ('D','G') then 'TOKO' else 'GUDANG' end as tujuanspb,
            count(spb_prdcd) as jmlspb
            from tbtemp_antrianspb
            where spb_recordid='3'
            group by case when substr(spb_lokasitujuan,0,1) not in ('D','G')  then 'TOKO' else 'GUDANG' end
            order by tujuanspb"
        );

        $lokasiasal = $dbProd->query(
            "SELECT  substr(spb_lokasiasal,0,3) as lokasiasal,count(spb_prdcd) as jmlspb
            from tbtemp_antrianspb
            where spb_recordid='3'
            $filterspb
            group by substr(spb_lokasiasal,0,3)
            order by lokasiasal"
        );

        $lokasitujuan = $dbProd->query(
            "SELECT substr(spb_lokasitujuan,0,3) as lokasitujuan,count(spb_prdcd) as jmlspb
            from tbtemp_antrianspb
            where spb_recordid='3'
            $filterspb
            group by substr(spb_lokasitujuan,0,3)
            order by lokasitujuan"
        );

        $spb = $spb->getResultArray();
        $tujuanspb = $tujuanspb->getResultArray();
        $lokasiasal = $lokasiasal->getResultArray();
        $lokasitujuan = $lokasitujuan->getResultArray();

        $data=[
            'title' => 'SPB Belum Direalisasi',
            'spb' => $spb,
            'tujuanspb' => $tujuanspb,
            'lokasiasal' => $lokasiasal,
            'lokasitujuan' => $lokasitujuan,
            'filtertujuanspb' => $filtertujuanspb,
            'lskasal' => $lksasal,
            'lkstujuan' => $lkstujuan
        ];

        return view('mplano/spbo3',$data);
    }

    public function spbm()
    {
        $dbProd = db_connect('production');
        $filterspb = $filtertujuanspb = $filterlokasi = $lksasal = $lkstujuan  = "";
        $spb = $tujuanspb = $lokasiasal = $lokasitujuan = [];

        if(isset($_GET['lksasal'])) {
            $lksasal = $_GET['lksasal'];
            $filterlokasi = "and spb_lokasiasal like '$lksasal%'";
        }elseif(isset($_GET['lkstujuan'])) {
            $lkstujuan = $_GET['lkstujuan'];
            if($lkstujuan == "TOKO") {
                $filterlokasi = "and substr(spb_lokasitujuan,0,1) in ('R','O')";
            }elseif($lkstujuan == "GUDANG"){
                $filterlokasi = "and substr(spb_lokasitujuan,0,1) in ('D')";
            }else{
                $filterlokasi = "";
            }
        }elseif(isset($_GET['lkstipe'])){
            $lkstujuan = $_GET['lkstipe'];
            if($lkstujuan == "s2s") {
                $filterlokasi = "and spb_lokasitujuan like '%.S.%' ";
            }elseif($lkstujuan == "s2d"){
                $filterlokasi = "and spb_lokasitujuan not like '%.S.%' ";
            }else{
                $filterlokasi = "";
            }
        }else{
            $filterlokasi = "";
        }

        $spb = $dbProd->query(
            "SELECT 
            spb_lokasiasal,spb_lokasitujuan,spb_jenis,spb_prdcd,spb_deskripsi,
            prd_unit,prd_frac,spb_qty,spb_minus,(spb_minus - mod(spb_minus,prd_frac))/prd_frac as MINUSCTN,mod(spb_minus,prd_frac) as MINUSPCS,
            case 
            when spb_recordid is null then 'Blm Diturunkan'
            when spb_recordid='3' then 'Blm Realisasi'
            end as STATUS,
            to_char(spb_create_dt,'DD/MM HH24:MI')as WAKTUSPB,
            to_char(spb_modify_dt,'DD/MM HH24:MI')as WAKTUTURUN
            from tbtemp_antrianspb
            left join tbmaster_prodmast on prd_prdcd=spb_prdcd
            where spb_jenis='MANUAL'
            and (spb_recordid is null or spb_recordid='3')
            $filterlokasi
            order by spb_create_dt"
        );

        $lokasiasal = $dbProd->query(
            "SELECT  substr(spb_lokasiasal,0,3) as lokasiasal,count(spb_prdcd) as jmlspb
            from tbtemp_antrianspb
            where spb_jenis='MANUAL'
            and (spb_recordid is null or spb_recordid='3')
            group by substr(spb_lokasiasal,0,3)
            order by lokasiasal"
        );

        $lokasitujuan = $dbProd->query(
            "SELECT substr(spb_lokasitujuan,0,3) as lokasitujuan,count(spb_prdcd) as jmlspb
            from tbtemp_antrianspb
            where spb_jenis='MANUAL'
            and (spb_recordid is null or spb_recordid='3')
            group by substr(spb_lokasitujuan,0,3)
            order by lokasitujuan"
        );

        $spb = $spb->getResultArray();
        $lokasiasal = $lokasiasal->getResultArray();
        $lokasitujuan = $lokasitujuan->getResultArray();

        $data=[
            'title' => 'SPB Manual',
            'spb' => $spb,
            'lokasiasal' => $lokasiasal,
            'lokasitujuan' => $lokasitujuan,
            'lskasal' => $lksasal,
            'lkstujuan' => $lkstujuan
        ];

        return view('mplano/spbm',$data);
    }

    public function slp()
    {
        $dbProd = db_connect('production');

        $lksasal = $lkstujuan = "";
        $slp = $lokasitujuan = [];
        if(isset($_GET['lksasal'])) {
            $lksasal = $_GET['lksasal'];
            $filterlokasi = "and spb_lokasiasal like '$lksasal%'";
        }elseif(isset($_GET['lkstujuan'])) {
            $lkstujuan = $_GET['lkstujuan'];
            $filterlokasi = "and slp_koderak like '$lkstujuan%'";
        }else{
            $filterlokasi = "";
        }

        $slp = $dbProd->query(
            "SELECT
            slp_id,
            slp_koderak||'.'||slp_kodesubrak||'.'||slp_tiperak||'.'||slp_shelvingrak||'.'||slp_nourut as LOKASI,
            slp_tipe as TIPE,
            slp_prdcd as PLU,
            prd_deskripsipanjang as DESKRIPSI,
            slp_unit as UNIT,
            slp_frac as FRAC,
            case when slp_frac=1 then 0 else floor(slp_qtypcs/slp_frac) end as QTYCRT,
            case when slp_frac=1 then slp_qtypcs else slp_qtypcs - (floor(slp_qtypcs/slp_frac)*slp_frac) end as QTYPCS,
            slp_expdate as EXPDATE,
            to_char(slp_create_dt,'DD/MM/YY') as CREATEDT
            from tbtr_slp
            left join tbmaster_prodmast on prd_prdcd=slp_prdcd
            where slp_flag is null $filterlokasi
            order by slp_id"
        );

        $lokasitujuan = $dbProd->query(
            "SELECT slp_koderak,count(slp_prdcd) as jmlslp from tbtr_slp
            where slp_flag is null group by slp_koderak order by slp_koderak"
        );

        $slp = $slp->getResultArray();
        $lokasitujuan = $lokasitujuan->getResultArray();

        $data=[
            'title' => 'SLP Belum Realisasi',
            'slp' => $slp,
            'lokasitujuan' => $lokasitujuan,
            'lksasal' => $lksasal,
            'lkstujuan' => $lkstujuan,
        ];

        return view('mplano/slp', $data);
    }
}
