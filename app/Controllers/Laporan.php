<?php

namespace App\Controllers;

class Laporan extends BaseController
{
    public function index()
    {
        return view('laporan/index',);
    }

    public function nonhanox()
    {
        $dbProd = db_connect('production');

        $nonhanox = $dbProd->query(
            "SELECT * FROM (select DIV, DEP,KAT,PLU, DESKRIPSI,UNIT,                                
            FRAC, TAG , STOK, AVGSALES,PKMT,LASTCOST, ACOST, PO, TGL_PO , KODESUP,NAMASUP,HRG_CTN,HRG_PCS,HRG_MBT,HRG_BOX from                                                    
            (select prd_kodedivisi DIV, prd_kodedepartement DEP, prd_kodekategoribarang KAT,prd_prdcd PLU, prd_deskripsipanjang DESKRIPSI,  prd_unit UNIT, prd_frac FRAC,prd_kodetag TAG , STOK, AVGSALES, pkm_pkmt PKMT, ST_AVGCOST * PRD_FRAC ACOST,st_lastcost * PRD_FRAC LASTCOST, po_out PO, TGL_AKHIR TGL_PO , hgb_kodesupplier KODESUP, sup_namasupplier NAMASUP
            from tbmaster_prodmast
            left join (select st_prdcd, st_saldoakhir STOK, st_avgcost, st_lastcost from tbmaster_stock
            where st_lokasi='01')on st_prdcd=prd_prdcd
            left join (SELECT TPOD_PRDCD, SUM(TPOD_QTYPO) PO_OUT, max(tpod_tglpo) TGL_AKHIR 
            FROM  tbtr_po_d, tbtr_po_h WHERE tpod_nopo = tpoh_nopo and tpoh_recordid is null
            and trunc(tpoh_tglpo)>=TO_DATE(SYSDATE)-6 AND TPOD_QTYPB IS NULL 
            group by TPOD_PRDCD) on tpod_prdcd=prd_prdcd
            left join tbmaster_hargabeli on hgb_prdcd=prd_prdcd and hgb_tipe='2'
            left join tbmaster_supplier on sup_kodesupplier=hgb_kodesupplier
            left join tbmaster_kkpkm on pkm_prdcd=prd_prdcd
            left join (select sls_prdcd, ( sls_qty_01 + sls_qty_02 + sls_qty_03) / 3 as avgsales from tbtr_salesbulanan) on sls_prdcd=prd_prdcd
            where prd_prdcd like '%0' and prd_kodetag not in ('H','A','N','O','X') and prd_kodedivisi not in ('5') )
            left join
            (select prd_prdcd plu_ctn, prd_hrgjual hrg_ctn from tbmaster_prodmast where prd_prdcd like '%0') on plu = plu_ctn
            left join                                                    
            (select prd_prdcd plu_pcs, prd_hrgjual hrg_pcs from tbmaster_prodmast where prd_prdcd like '%1') on SUBSTR(plu,1,6) = SUBSTR(plu_pcs,1,6)                                                   
            left join                                                    
            (select prd_prdcd plu_mbt, prd_hrgjual hrg_mbt,PRD_minjual minjual_mbt from tbmaster_prodmast where prd_prdcd like '%2') on SUBSTR(plu,1,6) = SUBSTR(plu_mbt,1,6)  
            left join                                                    
            (Select Prd_Prdcd Plu_Box, Prd_Hrgjual Hrg_Box From Tbmaster_Prodmast Where Prd_Prdcd Like '%3') On Substr(Plu,1,6) = Substr(Plu_Box,1,6)                                                
             ) sub1 order by 2 asc"
        );

        $nonhanox = $nonhanox->getResultArray();

        $data = [
            'title' => 'Prodmast Non-Hanox',
            'nonhanox' => $nonhanox,
        ];

        return view('laporan/nonhanox', $data);
    }
    
    public function hanox()
    {
        $dbProd = db_connect('production');

        $hanox = $dbProd->query(
            "SELECT * from (select DIV, DEP, KAT, PLU, DESKRIPSI,  UNIT, FRAC, TAG , STOK, AVGSALES, PKMT, ACOST,LASTCOST, PO, TGL_PO , KODESUP, NAMASUP,HRG_CTN,HRG_PCS,HRG_MBT,HRG_BOX 
            from (select prd_kodedivisi DIV, prd_kodedepartement DEP, prd_kodekategoribarang KAT, prd_prdcd PLU, prd_deskripsipanjang DESKRIPSI,  prd_unit UNIT, prd_frac FRAC,prd_kodetag TAG , STOK, AVGSALES, pkm_pkmt PKMT, ST_AVGCOST * PRD_FRAC ACOST,st_lastcost * PRD_FRAC LASTCOST, po_out PO, TGL_AKHIR TGL_PO , hgb_kodesupplier KODESUP, sup_namasupplier NAMASUP
            from tbmaster_prodmast left join (select st_prdcd, st_saldoakhir STOK, st_avgcost, st_lastcost from tbmaster_stock 
            where st_lokasi='01')on st_prdcd=prd_prdcd
            left join (SELECT TPOD_PRDCD, SUM(TPOD_QTYPO) PO_OUT, max(tpod_tglpo) TGL_AKHIR 
            FROM  tbtr_po_d, tbtr_po_h WHERE tpod_nopo = tpoh_nopo and tpoh_recordid is null and trunc(tpoh_tglpo)>=TO_DATE(SYSDATE)-6 AND TPOD_QTYPB IS NULL 
            group by TPOD_PRDCD) on tpod_prdcd=prd_prdcd 
            left join tbmaster_hargabeli on hgb_prdcd=prd_prdcd and hgb_tipe='2'
            left join tbmaster_supplier on sup_kodesupplier=hgb_kodesupplier
            left join tbmaster_kkpkm on pkm_prdcd=prd_prdcd
            left join (select sls_prdcd, ( sls_qty_01 + sls_qty_02 + sls_qty_03) / 3 as avgsales from tbtr_salesbulanan) on sls_prdcd=prd_prdcd 
            where prd_prdcd like '%0' and prd_kodetag in ('H','A','N','O','X') and prd_kodedivisi not in ('5') )
            left join (select prd_prdcd plu_ctn, prd_hrgjual hrg_ctn from tbmaster_prodmast where prd_prdcd like '%0') on plu = plu_ctn
            left join (select prd_prdcd plu_pcs, prd_hrgjual hrg_pcs from tbmaster_prodmast where prd_prdcd like '%1') on SUBSTR(plu,1,6) = SUBSTR(plu_pcs,1,6) 
            left join (select prd_prdcd plu_mbt, prd_hrgjual hrg_mbt,PRD_minjual minjual_mbt from tbmaster_prodmast where prd_prdcd like '%2') on SUBSTR(plu,1,6) = SUBSTR(plu_mbt,1,6)
            left join (select prd_prdcd plu_box, prd_hrgjual hrg_box from tbmaster_prodmast where prd_prdcd like '%3') on SUBSTR(plu,1,6) = SUBSTR(plu_box,1,6) ) sub1 order by 2 asc"
        );

        $hanox = $hanox->getResultArray();

        $data = [
            'title' => 'Prodmast Non-Hanox',
            'hanox' => $hanox,
        ];

        return view('laporan/hanox', $data);
    }

    public function masterlokasi()
    {
        $data = [
            'title' => 'Master Lokasi',
        ];

        return view('laporan/masterlokasi', $data);
    }

    public function tampilmaslok()
    {
        $dbProd = db_connect('production');

        $rak = $this->request->getVar('rak');
        $jenis = $this->request->getVar('jenis');
        $btn = $this->request->getVar('btn');

        $perrak = [];
        if ($rak=="Toko") {
            $filterrak = "AND LOK = 'Toko'";
        }elseif ($rak=="Gudang") {
            $filterrak="AND LOK = 'Gudang'";
        }else{
            $filterrak = " ";
        }

        if ($btn=="tampil" && !empty($rak) && !empty($jenis)) {
            $perrak = $dbProd->query(
                "SELECT DISTINCT lok,
                Lks_Koderak,
                  Lks_Kodesubrak,
                  lks_tiperak,  
                  Lks_Shelvingrak,
                  lks_nourut,
                lks_jenisrak,
                lks_prdcd,
                prd_deskripsipanjang,
                PRD_KODETAG,
                CASE
                  WHEN prd_unit IS NOT NULL
                  THEN prd_unit
                    ||'/'
                    || prd_frac
                  ELSE ''
                END frac,
                lks_qty,
                lks_expdate,
                lks_maxplano,
                lks_maxdisplay
              FROM
                (SELECT
                  CASE
                    WHEN SUBSTR(lks_koderak,1,1) IN ('D','G')
                    THEN 'Gudang'
                    ELSE 'Toko'
                  END lok,
                  Lks_Koderak,
                  Lks_Kodesubrak,
                  lks_tiperak,
                  Lks_Shelvingrak,
                  lks_nourut,
                  lks_jenisrak,
                  lks_prdcd,
                  lks_qty,
                  lks_expdate,
                  lks_maxplano,
                  lks_maxdisplay
                FROM tbmaster_lokasi
                ),
                Tbmaster_Prodmast
              WHERE prd_prdcd(+)=lks_prdcd
              $filterrak
              ORDER BY 2, 3, 4, 5 ASC
              "
            );

            $perrak = $perrak->getResultArray();
        }

        $data = [
            'title' => 'Master Lokasi',
            'perrak' => $perrak,
            'rak' => $rak,
        ];

        return view('laporan/tampilmaslok', $data);
    }

    public function historyso()
    {
        $data = [
            'title' => 'History SO'
        ];

        return view('laporan/historyso', $data);
    }

    public function tampilhistoryso()
    {
        $dbProd = db_connect('production');
        $tglawal = $this->request->getVar('tglawal');
        $tglakhir = $this->request->getVar('tglakhir');
        $pluinput = $this->request->getVar('plu');
        $btn = $this->request->getVar('btn');
        $history = [];
        $plu0 = sprintf("%07s",$pluinput);

        if ($pluinput!="") {
            $filterplu = "AND HSO_PRDCD = '$plu0'";
        }else{
            $filterplu = " ";
        }
        if ($btn=="tampil") {
            $history = $dbProd->query(
                "SELECT HSO_KODERAK||'.'||HSO_KODESUBRAK||'.'||HSO_TIPERAK
				||'.'||HSO_SHELVINGRAK||'.'||HSO_NOURUT LOKASI,
				PRD_KODEDIVISI DIV,
				PRD_KODEDEPARTEMENT DEP,
				PRD_KODEKATEGORIBARANG KAT,
				HSO_PRDCD PLU,
				PRD_DESKRIPSIPANJANG DESK,
				HSO_QTYLAMA QTY_LAMA,
				HSO_QTYBARU QTY_BARU,
				HSO_CREATE_BY CREATEBY,
				trunc(HSO_CREATE_DT) TANGGAL
				FROM TBHISTORY_SOPLANO
				LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = HSO_PRDCD
				WHERE trunc(HSO_CREATE_DT) BETWEEN to_date('$tglawal','YYYY-MM-DD') AND to_date('$tglakhir','YYYY-MM-DD')  
                $filterplu
        ORDER BY HSO_CREATE_DT DESC, HSO_KODERAK ASC"
            );
            $history = $history->getResultArray();
        }

        $data = [
            'title' => 'History SO',
            'history' => $history,
        ];
        d($data);

        return view('laporan/tampilhistoryso', $data);
    }

    public function pobanding(){
        $data = [
            'title' => 'Input PO Perbandingan'
        ];

        return view('laporan/pobanding',$data);
    }

    public function tampilbanding()
    {
        $dbProd = db_connect('production');
        $nopo1 = $this->request->getVar('nopo1');
        $nopo2 = $this->request->getVar('nopo2');
        $jenis = $this->request->getVar('jenis');
        $btn = $this->request->getVar('btn');

        $banding = [];

        if ($jenis=="banding") {
            $banding = $dbProd->query(
                "SELECT DISTINCT PRD_KODEDIVISI DIV,
				PRD_KODEDEPARTEMENT DEP,
				PRD_KODEKATEGORIBARANG KAT,
				TPOD_PRDCD PLU,
				PRD_DESKRIPSIPANJANG DESK,
				PRD_FRAC FRAC,
				NOPO1,TGLPO1,QTYPO1,QTYBPB1,TGLBPB1,
				NOPO2,TGLPO2,QTYPO2,QTYBPB2,TGLBPB2
				FROM TBTR_PO_D
				LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = TPOD_PRDCD
				LEFT JOIN TBTR_PO_H ON TPOD_NOPO = TPOH_NOPO
				LEFT JOIN (SELECT TPOD_PRDCD PLU1,
						   TPOD_NOPO NOPO1,
						   TPOD_TGLPO TGLPO1,
						   TPOD_QTYPO QTYPO1,
						   MSTD_QTY QTYBPB1,
						   MSTD_TGLDOC TGLBPB1
						   FROM  TBTR_PO_D
						   LEFT JOIN (SELECT MSTD_NOPO,MSTD_PRDCD,MSTD_QTY,MSTD_TGLDOC 
						   FROM TBTR_MSTRAN_D WHERE MSTD_NOPO = '{$nopo1}') ON TPOD_NOPO = MSTD_NOPO AND TPOD_PRDCD = MSTD_PRDCD
						   WHERE TPOD_NOPO = '{$nopo1}') ON PLU1 = TPOD_PRDCD
				LEFT JOIN (SELECT TPOD_PRDCD PLU2,
						   TPOD_NOPO NOPO2,
						   TPOD_TGLPO TGLPO2,
						   TPOD_QTYPO QTYPO2,
						   MSTD_QTY QTYBPB2,
						   MSTD_TGLDOC TGLBPB2
						   FROM  TBTR_PO_D
						   LEFT JOIN (SELECT MSTD_NOPO,MSTD_PRDCD,MSTD_QTY,MSTD_TGLDOC 
						   FROM TBTR_MSTRAN_D WHERE MSTD_NOPO = '{$nopo2}') ON TPOD_NOPO = MSTD_NOPO AND TPOD_PRDCD = MSTD_PRDCD
						   WHERE TPOD_NOPO = '{$nopo2}') ON PLU2 = TPOD_PRDCD
				WHERE NVL(TPOH_RECORDID,'0') <> '1'
                ORDER BY NOPO1 ASC"
            );
            $banding = $banding->getResultArray();
        }

        $data = [
            'title' => 'Tampil PO Banding',
            'banding' => $banding,
            'nopo1' => $nopo1,
            'nopo2' => $nopo2,
        ];


        return view('laporan/tampilbanding', $data);
    }

    public function storagenull()
    {
        $dbProd = db_connect('production');
        $storage = $this->request->getVar('storage');

        $judulstorage = "";
        $queryStorage = [];

        if ($storage=="sb") {
            $judulstorage = "Storage Besar";
            $filterstorage = "AND LKS_shelvingrak like 'S%'";
        }elseif($storage=="sk"){
            $judulstorage = "Storage Kecil";
            $filterstorage = "AND LKS_shelvingrak like 'K%'";
        }elseif($storage=="sc"){
            $judulstorage = "Storage Campur";
            $filterstorage = "AND LKS_shelvingrak like 'C%'";
        }else{
            $filterstorage =" ";
        }

        if ($storage!=' ') {
            $queryStorage = $dbProd->query(
                "SELECT lok,
                rak,
                lks_jenisrak,
                lks_prdcd,
                prd_deskripsipanjang,
                CASE
                  WHEN prd_unit IS NOT NULL
                  THEN prd_unit
                    ||'/'
                    || prd_frac
                  ELSE ''
                END frac,
                lks_qty,
                lks_expdate
              FROM
                (SELECT
                  CASE
                    WHEN SUBSTR(lks_koderak,1,1) IN ('D','G')
                    THEN 'Gudang'
                    ELSE 'Toko'
                  END lok,
                  lks_koderak
                  ||'.'
                  || lks_kodesubrak
                  ||'.'
                  || lks_tiperak
                  ||'.'
                  || lks_shelvingrak
                  ||'.'
                  || lks_nourut rak,
                  lks_jenisrak,
                  lks_prdcd,
                  lks_qty,
                  lks_expdate
                FROM tbmaster_lokasi
                WHERE LKS_PRDCD IS NULL 
                AND LKS_TIPERAK ='S' 
                AND LKS_KODERAK LIKE 'R%' 
                $filterstorage
                ),
                tbmaster_prodmast
              WHERE prd_prdcd(+)=lks_prdcd
              ORDER BY 1, 2, 3, 4, 5 "
            );
            $queryStorage = $queryStorage->getResultArray();        
        }

        $data = [
            'title' => 'Storage Null',
            'querystorage' => $queryStorage,
            'judulstorage' => $judulstorage,
        ];

        return view('laporan/storagenull', $data);
    }

    public function cekmd() {
        $dbProd = db_connect('production');
        $cekmd = $dbProd->query(
            "SELECT PRMD_PRDCD,
            PRD_DESKRIPSIPANJANG,
            PRMD_TGLAWALBARU,
            PRMD_TGLAKHIRBARU,
            PRMD_HRGJUAL  HRG_LAMA,
            PRMD_HRGJUALBARU HRG_BARU
            from tbtr_promomd
            LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = PRMD_PRDCD
            WHERE TRUNC(PRMD_TGLAWALBARU)>= TRUNC(SYSDATE)"
        );
        $cekmd = $cekmd->getResultArray();
        $data = [
            'title' => 'CEK MD',
            'cekmd' => $cekmd
        ];
    
        return view('logistik/cekmd', $data);
        }

        // Unfinished Member tidur
        public function membertidur()
        {
            $dbProd = db_connect('production');
            $membertidur = $dbProd->query(
                "SELECT
                cus_kodeigr as cabang,
                kodemember, 
                cus_namamember as namamember,
                tgl_last_belanja,
                cus_alamatmember2 as alamat2, 
                cus_alamatmember4 as alamat4, 
                cus_kodeoutlet as outlet, 
                cus_kodesuboutlet as suboutlet,
                cus_hpmember as hpmember,
                cus_noktp as ktp
              from tbmaster_customer
              left join (select jh_cus_kodemember as kodemember, max(trunc(jh_transactiondate)) as tgl_last_belanja 
                    from tbtr_jualheader
                    where trunc(jh_transactiondate) >= trunc(sysdate)- 365
                    group by jh_cus_kodemember
                    order by tgl_last_belanja) on cus_kodemember = kodemember
              where cus_recordid is null
              and cus_kodeigr ='25'
              and cus_flagmemberkhusus = 'Y'
              and (cus_tglregistrasi is not null  or cus_tglmulai is not null)
              order by tgl_last_belanja desc"
            );

            $membertidur = $membertidur->getResultArray();

            $data = [
                'title' => 'Member Tidur',
                'membertidur' => $membertidur,
            ];

            return view('laporan/membertidur', $data);
        }
}