<?php

namespace App\Controllers;

class Logistik extends BaseController
{
    public function index()
    {
        return view('edp/index',);
    }

    public function formsoharian() {
        $data = [
            'title' => 'Form SO Harian',
        ];

        redirect()->to('/logistik/formsoharian')->withInput();
        return view('/logistik/formsoharian',$data);
    }

    public function tampildatasoharian() {
        $dbProd = db_connect('production');
        $soharian = $selisih = [];
        $plufokus = '';
        if(isset($_POST['kodePLU'])) {
            $plu = trim($_POST['kodePLU']);
        }elseif(isset($_GET['kodePLU'])){
            $plu = trim($_GET['kodePLU']);
        }

        $pluex = explode(",",$plu);
        foreach ($pluex as $plu0) {
            $plu0 = sprintf("%07s",$plu0);
            $plu123 = "'".substr($plu0,0,6)."0'".",'".substr($plu0,0,6)."1'".",'".substr($plu0,0,6)."2'".",'".substr($plu0,0,6)."3',";
            $plufokus .= $plu123;
            $panjangstr = strlen($plufokus)-1;
        }
        $kodePLU = substr($plufokus,0,$panjangstr);

        $selisih = $dbProd->query(
            "SELECT  PRD_KODEDIVISI DIV,
			PRD_KODEDEPARTEMENT DEPT,
			PRD_KODEKATEGORIBARANG KAT,
			ST_PRDCD PLU,
			PRD_DESKRIPSIPANJANG DESKRIPSI,
			PRD_KODETAG TAG,
			ST_SALDOAKHIR STOCK,
			PLANO,
			(PLANO - ST_SALDOAKHIR) SELISIH
			FROM TBMASTER_STOCK
			LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = ST_PRDCD
			LEFT JOIN(SELECT LKS_PRDCD,SUM(LKS_QTY) PLANO FROM TBMASTER_LOKASI GROUP BY LKS_PRDCD) ON ST_PRDCD = LKS_PRDCD
			WHERE ST_LOKASI = '01' AND ST_PRDCD IN ($kodePLU)ORDER BY SELISIH"
        );
        $selisih = $selisih->getResultArray();

        
            $soharian = $dbProd->query(
                "select LKS_PRDCD PLU,lks_koderak||'.'||lks_kodesubrak||'.'||lks_tiperak||'.'||lks_shelvingrak||'.'||lks_nourut LOKASI,
                case 
                    when SUBSTR(LKS_KODERAK,0,1) not in ('D','G') then 'TOKO'
                    when SUBSTR(LKS_KODERAK,0,1) in ('D') then 'OMI'
                    when SUBSTR(LKS_KODERAK,0,1) in ('G') then 'GUDANG'
                end as AREA,
                LKS_QTY,
                LKS_EXPDATE
                from tbmaster_lokasi
                where lks_prdcd in ($kodePLU) and lks_qty > 0
                order by PLU,AREA,LOKASI asc"
            );
            $soharian = $soharian->getResultArray();
        

        if($plu == '') {
        echo "<h2>Jangan lupa isi PLU-nya... :)</h3>";
        $kodePLU="";
        };
     
        $data = [
            'title' => 'Data SO Per PLU',
            'soharian' => $soharian,
            'selisih' => $selisih
        ];

        return view('logistik/tampildatasoharian',$data);
    }

    public function lppvsplanodetail() {
        
        $data = [
            'title' => 'LPP vs Plano Detail',
        ];

        redirect()->to('/logistik/lppvsplanodetail')->withInput();
        return view('/logistik/lppvsplanodetail',$data);
    }

    public function tampildatalppplanodetail() {
        $dbProd = db_connect('production');
        $dep = $this->request->getVar('dept');
        $sort = $this->request->getVar('sortby');
        $versi = $this->request->getVar('ver');
        $aksi = $this->request->getVar('tombol');
        $filterdep = $sortby = $judul = "";
        $detail = $filename = [];

        if($dep == "1") {
            $filterdep = " and prd_kodedivisi='1' ";
            $judul = "FOOD";
        } elseif($dep == "2") {
            $filterdep = " and prd_kodedivisi='2' ";
            $judul = "NON FOOD";
        } elseif($dep == "3") {
            $filterdep = " and prd_kodedivisi='3' ";
            $judul = "GENERAL MERCHANDISHING";
        } elseif($dep == "4") {
            $filterdep = " and prd_kodedivisi='4' ";
            $judul = "PERISHABLE";
        } elseif($dep == "5") {
            $filterdep = " and prd_kodedivisi='5' ";
            $judul = "COUNTER & PROMOTION";
        } elseif($dep == "6") {
            $filterdep = " and prd_kodedivisi='6' ";
            $judul = "FAST FOOD";
        } elseif($dep == "7") {
            $filterdep = " and prd_kodedivisi='7' ";
            $judul = "I-FASHION";
        } elseif($dep == "8") {
            $filterdep = " and prd_kodedivisi='8' ";
            $judul = "I-TECH";
        } elseif($dep == "9") {
            $filterdep = " and prd_kodedivisi='9' ";
            $judul = "I-TRONIK";
        }

        if($sort == "qty") {
            $sortby = " QTYSELISIH";
        } elseif($sort == "rph") {
            $sortby = " RPHSELISIH";
        }

        if($versi == "ver1") {
            $detail = $dbProd->query(
                "select prd_kodedivisi as DIV,
                prd_kodedepartement as DEP,
                prd_kodekategoribarang as KAT,
                st_prdcd as PLU,
                prd_deskripsipanjang as DESKRIPSI,
                prd_unit as UNIT,
                prd_frac as FRAC,
                prd_kodetag as TAG,
                st_avgcost as ACOST,
                st_saldoakhir as STOKLPP,
                case when prd_unit='KG' then st_saldoakhir*(st_avgcost/1000) else st_saldoakhir*st_avgcost end as RPHLPP,
                nvl(STOKPLANO,0) as STOKPLANO,
                case when prd_unit='KG' then nvl(STOKPLANO,0)*(st_avgcost/1000) else nvl(STOKPLANO,0)*st_avgcost end as RPHPLANO,
                nvl(STOKPLANO,0) - st_saldoakhir as QTYSELISIH,
                case when prd_unit='KG' then (nvl(STOKPLANO,0) - st_saldoakhir)*(st_avgcost/1000) else (nvl(STOKPLANO,0) - st_saldoakhir)*st_avgcost end as RPHSELISIH,
                nvl(STOKPLANO_DISPLAY_TOKO,0) as STOKPLANO_DISPLAY_TOKO,
                nvl(STOKPLANO_STORAGE_TOKO,0) as STOKPLANO_STORAGE_TOKO,
                nvl(STOKPLANO_DISPLAY_GUDANG,0) as STOKPLANO_DISPLAY_GUDANG,
                nvl(STOKPLANO_STORAGE_GUDANG,0) as STOKPLANO_STORAGE_GUDANG,
                case 
                    when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')='Y' then 'IGR+OMI'
                    when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')!='Y' then 'IGR ONLY'
                    when nvl(prd_flagigr,'N')!='Y' and nvl(prd_flagomi,'N')='Y' then 'OMI ONLY'
                    else 'TIDAK BISA JUAL'
                end as FLAG,
                STATUS,
                DISPLAY_TOKO,
                DISPLAY_OMI
                from
                tbmaster_stock
                left join tbmaster_prodmast on prd_prdcd=st_prdcd
                left join (
                select lks_prdcd,sum(lks_qty) as STOKPLANO,
                sum (case when substr(lks_koderak,0,1) not in ('D','G') and lks_tiperak !='S' then lks_qty end) as STOKPLANO_DISPLAY_TOKO,
                sum (case when substr(lks_koderak,0,1) not in ('D','G') and lks_tiperak  ='S' then lks_qty end) as STOKPLANO_STORAGE_TOKO,
                sum (case when substr(lks_koderak,0,1) in ('D','G') and lks_tiperak  !='S' then lks_qty end) as STOKPLANO_DISPLAY_GUDANG,
                sum (case when substr(lks_koderak,0,1) in ('D','G') and lks_tiperak   ='S' then lks_qty end) as STOKPLANO_STORAGE_GUDANG
                from tbmaster_lokasi 
                group by lks_prdcd
                ) on lks_prdcd=st_prdcd
                LEFT JOIN (
                select 
                lks_prdcd as PLUDISPLAYREG,
                lks_koderak||'.'||lks_kodesubrak||'.'||lks_tiperak||'.'||lks_shelvingrak||'.'||lks_nourut as DISPLAY_TOKO,lks_jenisrak as STATUS
                from tbmaster_lokasi
                where substr(lks_koderak,0,1) IN ('O','R') and substr(lks_tiperak,0,1) not in ('S','Z')
                ) ON PLUDISPLAYREG=PRD_PRDCD
                LEFT JOIN (
                select 
                lks_prdcd as PLUDISPLAYDPD,
                lks_koderak||'.'||lks_kodesubrak||'.'||lks_tiperak||'.'||lks_shelvingrak||'.'||lks_nourut as DISPLAY_OMI
                from tbmaster_lokasi
                where substr(lks_koderak,0,1) IN ('D') and substr(lks_tiperak,0,1) not in ('S','Z')
                ) ON PLUDISPLAYDPD=PRD_PRDCD
                where st_lokasi='01' and prd_kodetag not in ('N')
                and (prd_kodecabang='25' or prd_kategoritoko='01')
                $filterdep
                order by $sortby"
            );

            $detail = $detail->getResultArray();

            $data = [
                'title' => 'Stock LPP vs Plano',
                'detail' => $detail,
                'judul' => $judul,
                'versi' => $versi,
            ];
    
            if($aksi == "btnlpp1") {
                return view('/logistik/tampildatalppplanodetail',$data);
            } elseif($aksi == "btnlpp2") {
                $filename = "LPP vs Plano - Detail VERSI 1.xls";
                header("Content-Disposition: attachment; filename=\"$filename\"");
                header("Content-Type: application/vnd.ms-excel");
    
                return view('/logistik/tampildatalppplanodetail',$data);
            };

        } elseif($versi == "ver2") {
            $detail = $dbProd->query(
                "SELECT   
                PRD_KODEDIVISI AS DIV,  
                PRD_KODEDEPARTEMENT AS DEPT,  
                PRD_KODEKATEGORIBARANG AS KATB,  
                ST_PRDCD AS PLU,  
                PRD_DESKRIPSIPANJANG AS DESKRIPSI,  
                PRD_FRAC AS FRAC,  
                PRD_UNIT AS UNIT,  
                PRD_KODETAG AS TAG,  
                ST_AVGCOST AS ACOST,  
                ST_INTRANSIT AS LPP_INTRANSIT,
                CASE WHEN PRD_UNIT='KG' THEN (ST_INTRANSIT*ST_AVGCOST)/PRD_FRAC   
                ELSE ST_INTRANSIT*ST_AVGCOST END AS INTRANSIT_RPH,
                ST_SALDOAKHIR AS LPP_QTY,  
                CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
                ELSE ST_SALDOAKHIR*ST_AVGCOST END AS LPP_RPH,  
                NVL(PQTY,0) AS PLANO_QTY,  
                NVL(CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
                ELSE NVL(PQTY,0)*ST_AVGCOST END,0) AS PLANO_RPH,  
                NVL(PQTY,0)-ST_SALDOAKHIR AS QTYSELISIH,      
                NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
                ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
                ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) AS RPHSELISIH,
                STATUS_IGR_IDM STATUS,
                DISPLAY_TOKO,
                DISPLAY_OMI
                
                FROM TBMASTER_PRODMAST 
                
                LEFT JOIN TBMASTER_STOCK ON ST_PRDCD = PRD_PRDCD 
                LEFT JOIN  
                (SELECT LKS_PRDCD, SUM(LKS_QTY) AS PQTY FROM TBMASTER_LOKASI GROUP BY LKS_PRDCD) ON PRD_PRDCD=LKS_PRDCD  
                LEFT JOIN (
                SELECT lks_prdcd AS PLU_DISPLAYTOKO,    
                    lks_koderak    
                    ||'.'    
                    ||lks_kodesubrak    
                    ||'.'    
                    ||lks_tiperak    
                    ||'.'    
                    ||lks_shelvingrak    
                    || '.'    
                    ||lks_nourut AS DISPLAY_TOKO    
                  FROM tbmaster_lokasi    
                  WHERE SUBSTR(LKS_KODERAK,0,1) IN ('O','R','P')    
                  AND SUBSTR(LKS_TIPERAK,0,1)   <>'S'  )ON PLU_DISPLAYTOKO = PRD_PRDCD
                LEFT JOIN    
                  (SELECT lks_prdcd AS PLU_DISPLAYOMI,    
                    lks_koderak    
                    ||'.'    
                    ||lks_kodesubrak    
                    ||'.'    
                    ||lks_tiperak    
                    ||'.'    
                    ||lks_shelvingrak    
                    || '.'    
                    ||lks_nourut AS DISPLAY_OMI    
                  FROM tbmaster_lokasi    
                  WHERE SUBSTR(LKS_KODERAK,0,1) IN ('D')    
                  AND SUBSTR(LKS_TIPERAK,0,1)   <>'S' ) ON PLU_DISPLAYOMI=PRD_PRDCD 
                LEFT join(
                SELECT 
                PRD_PRDCD plu_flag,
                CASE WHEN FLAG = 'NAS-IGR+K.IGR' THEN 'IGR-ONLY'
                WHEN FLAG = 'NAS' THEN 'IGR-ONLY'
                WHEN FLAG = 'IGR+K.IGR' THEN 'IGR-ONLY'
                WHEN FLAG = 'IGR' THEN 'IGR-ONLY'
                
                WHEN FLAG = 'NAS-OMI' THEN 'OMI-ONLY'
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
                
                FROM TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0' AND PRD_DESKRIPSIPANJANG IS NOT NULL))) on plu_flag = prd_prdcd
                WHERE  ST_LOKASI='01' 
                $filterdep 
                ORDER BY $sortby"
            );

            $detail = $detail->getResultArray();

            $data = [
                'title' => 'Stock LPP vs Plano',
                'detail' => $detail,
                'judul' => $judul,
                'versi' => $versi,
            ];
    
            if($aksi == "btnlpp1") {
                return view('/logistik/tampildatalppplanodetail',$data);
            } elseif($aksi == "btnlpp2") {
                $filename = "LPP vs Plano - Detail.xls";
                header("Content-Disposition: attachment; filename=\"$filename\"");
                header("Content-Type: application/vnd.ms-excel");
    
                return view('/logistik/tampildatalppplanodetail',$data);
            };
        };
    }

    public function lppvsplanorekap() {
        $dbProd = db_connect('production');
        $rekap = $plusminus = $plus = $minus = [];

        $rekap = $dbProd->query(
            "SELECT SUM(LPP_QTY)LPP_QTY, 
            SUM(PLANO_QTY) PLANO_QTY,
            SUM(LPP_RPH) LPP_RPH,
            SUM(PLANO_RPH) PLANO_RPH,
            SUM(SLSH_QTY) SLSH_QTY,
            SUM(SLSH_RPH) SLSH_RPH
            FROM(SELECT   
            PRD_KODEDIVISI AS DIV,  
            PRD_KODEDEPARTEMENT AS DEPT,  
            PRD_KODEKATEGORIBARANG AS KATB,  
            ST_PRDCD AS PLU,  
            PRD_DESKRIPSIPANJANG AS DESKRIPSI,  
            PRD_FRAC AS FRAC,  
            PRD_UNIT AS UNIT,  
            PRD_KODETAG AS TAG,  
            ST_AVGCOST AS ACOST,  
            ST_SALDOAKHIR AS LPP_QTY,  
            CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END AS LPP_RPH,  
            NVL(PQTY,0) AS PLANO_QTY,  
            NVL(CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END,0) AS PLANO_RPH,  
            NVL(PQTY,0)-ST_SALDOAKHIR AS SLSH_QTY,      
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) AS SLSH_RPH    
            FROM TBMASTER_PRODMAST  
            LEFT JOIN  
            TBMASTER_STOCK ON ST_PRDCD = PRD_PRDCD 
            LEFT JOIN  
            (SELECT LKS_PRDCD, SUM(LKS_QTY) AS PQTY FROM TBMASTER_LOKASI GROUP BY LKS_PRDCD) ON PRD_PRDCD=LKS_PRDCD  
            WHERE  ST_LOKASI='01'   and  prd_kodedivisi not in ('4')
            ORDER BY   
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) DESC)"
        );
        $rekap = $rekap->getResultArray();

        $plusminus = $dbProd->query(
            "SELECT PLUS.PLUS SLS_PLUS ,
            MNUS.MNUS SLS_MNUS
            FROM (SELECT SUM (SLSH_RPH) PLUS FROM (SELECT         
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) AS SLSH_RPH    
            FROM TBMASTER_PRODMAST  
            LEFT JOIN  
            TBMASTER_STOCK ON ST_PRDCD = PRD_PRDCD 
            LEFT JOIN  
            (SELECT LKS_PRDCD, SUM(LKS_QTY) AS PQTY FROM TBMASTER_LOKASI GROUP BY LKS_PRDCD) ON PRD_PRDCD=LKS_PRDCD  
            WHERE  ST_LOKASI='01'   AND  PRD_KODEDIVISI NOT IN ('4')
            ORDER BY   
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) DESC) WHERE SLSH_RPH>'0') PLUS,
            (SELECT SUM (SLSH_RPH) MNUS FROM (SELECT         
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) AS SLSH_RPH    
            FROM TBMASTER_PRODMAST  
            LEFT JOIN  
            TBMASTER_STOCK ON ST_PRDCD = PRD_PRDCD 
            LEFT JOIN  
            (SELECT LKS_PRDCD, SUM(LKS_QTY) AS PQTY FROM TBMASTER_LOKASI GROUP BY LKS_PRDCD) ON PRD_PRDCD=LKS_PRDCD  
            WHERE  ST_LOKASI='01'   AND  PRD_KODEDIVISI NOT IN ('4')
            ORDER BY   
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) DESC) WHERE SLSH_RPH <'0') MNUS"
        );
        $plusminus = $plusminus->getResultArray();
        
        $plus = $dbProd->query(
            "SELECT * FROM (SELECT K.*, ROWNUM RNUM FROM
            (SELECT   
            PRD_KODEDIVISI AS DIV,  
            PRD_KODEDEPARTEMENT AS DEPT,  
            PRD_KODEKATEGORIBARANG AS KATB,  
            ST_PRDCD AS PLU,  
            PRD_DESKRIPSIPANJANG AS DESKRIPSI,  
            PRD_FRAC AS FRAC,  
            PRD_UNIT AS UNIT,  
            PRD_KODETAG AS TAG,  
            ST_AVGCOST AS ACOST,  
            ST_SALDOAKHIR AS LPP_QTY,  
            CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END AS LPP_RPH,  
            NVL(PQTY,0) AS PLANO_QTY,  
            NVL(CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END,0) AS PLANO_RPH,  
            NVL(PQTY,0)-ST_SALDOAKHIR AS SLSH_QTY,      
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) AS SLSH_RPH    
            FROM TBMASTER_PRODMAST  
            LEFT JOIN  
            TBMASTER_STOCK ON ST_PRDCD = PRD_PRDCD 
            LEFT JOIN  
            (SELECT LKS_PRDCD, SUM(LKS_QTY) AS PQTY FROM TBMASTER_LOKASI GROUP BY LKS_PRDCD) ON PRD_PRDCD=LKS_PRDCD  
            WHERE  ST_LOKASI='01'   and  prd_kodedivisi not in ('4')
            ORDER BY   
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) DESC) K
            WHERE ROWNUM <= 10)
            WHERE rnum >= 1"
        );
        $plus = $plus->getResultArray();

        $minus = $dbProd->query(
            "SELECT * FROM (SELECT K.*, ROWNUM RNUM FROM
            (SELECT   
            PRD_KODEDIVISI AS DIV,  
            PRD_KODEDEPARTEMENT AS DEPT,  
            PRD_KODEKATEGORIBARANG AS KATB,  
            ST_PRDCD AS PLU,  
            PRD_DESKRIPSIPANJANG AS DESKRIPSI,  
            PRD_FRAC AS FRAC,  
            PRD_UNIT AS UNIT,  
            PRD_KODETAG AS TAG,  
            ST_AVGCOST AS ACOST,  
            ST_SALDOAKHIR AS LPP_QTY,  
            CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END AS LPP_RPH,  
            NVL(PQTY,0) AS PLANO_QTY,  
            NVL(CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END,0) AS PLANO_RPH,  
            NVL(PQTY,0)-ST_SALDOAKHIR AS SLSH_QTY,      
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) AS SLSH_RPH    
            FROM TBMASTER_PRODMAST  
            LEFT JOIN  
            TBMASTER_STOCK ON ST_PRDCD = PRD_PRDCD 
            LEFT JOIN  
            (SELECT LKS_PRDCD, SUM(LKS_QTY) AS PQTY FROM TBMASTER_LOKASI GROUP BY LKS_PRDCD) ON PRD_PRDCD=LKS_PRDCD  
            WHERE  ST_LOKASI='01'   and  prd_kodedivisi not in ('4')
            ORDER BY   
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC   
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC   
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) ASC) K
            WHERE ROWNUM <= 10)
            WHERE rnum >= 1"
        );
        $minus = $minus->getResultArray();

        $data = [
            'title' => 'LPP vs Plano Rekap',
            'rekap' => $rekap, 
            'plusminus' => $plusminus,
            'plus' => $plus,
            'minus' => $minus,
        ];

        redirect()->to('/logistik/lppvsplanorekap')->withInput();
        return view('/logistik/lppvsplanorekap',$data);
    }

    public function livecks() {
        $dbProd = db_connect('production');
        $plu = $this->request->getVar('inputplu');
        $rak = $this->request->getVar('koderak');
        $subrak = $this->request->getVar('kodesubrak');
        $aksi = $this->request->getVar('tombol');
        $filter = "";
        $viewlpp = $display = $trflokasi = $viewkkpkm = $viewdisplay = $viewstr = $viewspb = $viewslp = $historyspb = [];
        
        // Monitoring PLano
        if($aksi == "btnall") {
            $filter = "";
        } elseif($aksi == "btndsp") {
            $filter = " and lks_tiperak <> 'S'";
        } elseif($aksi == "btnstr") {
            $filter = " and lks_tiperak = 'S'";
        }

        $display = $dbProd->query(
            "SELECT 
            CASE 
              WHEN LKS_TIPERAK != 'S' THEN 'Display'
              WHEN LKS_TIPERAK = 'S' AND LKS_SHELVINGRAK LIKE 'C%' THEN 'Storage_C'
              WHEN LKS_TIPERAK = 'S' AND LKS_SHELVINGRAK LIKE 'K%' THEN 'Storage_K'
              WHEN LKS_TIPERAK = 'S' AND LKS_SHELVINGRAK LIKE 'S%' THEN 'Storage_S'
              END AS KATEGORI,
            LKS_KODERAK||'.'||LKS_KODESUBRAK||'.'||LKS_TIPERAK||'.'||LKS_SHELVINGRAK||'.'||LKS_NOURUT AS LOKASI,
            LKS_JENISRAK as JENISRAK,
            LKS_NOID as NOID,
            LKS_PRDCD AS PLU,
            PRD_DESKRIPSIPENDEK AS DESKRIPSI,
            PRD_FRAC AS FRAC,
            PRD_UNIT AS UNIT,
            LKS_QTY AS QTYPLANO,
            LKS_MAXPLANO MAXPLANO,
            LKS_MAXDISPLAY AS MAXDIS,
            LKS_MINPCT as MINPCT,
            case 
                when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')='Y' then 'IGR+OMI'
                when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')!='Y' then 'IGR Only'
                when nvl(prd_flagigr,'N')!='Y' and nvl(prd_flagomi,'N')='Y' then 'OMI Only'
                else 'TIDAK JUAL'
            end as FLAGJUAL,
            case
                when DISPLAY_REG is not null and DISPLAY_DPD is null then 'Display IGR'
                when DISPLAY_REG is null and DISPLAY_DPD is not null then 'Display OMI'
                when DISPLAY_REG is not null and DISPLAY_DPD is not null then 'Display IGR+OMI'
            end as FLAGDISPLAY
            FROM TBMASTER_LOKASI 
            LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD=LKS_PRDCD
            left join (
            SELECT 
              LKS_PRDCD as plur,
              LKS_KODERAK||'.'||LKS_KODESUBRAK||'.'||LKS_TIPERAK||'.'||LKS_SHELVINGRAK||'.'||LKS_NOURUT AS DISPLAY_REG,
              LKS_JENISRAK as JENIS_ITEM
            FROM TBMASTER_LOKASI
            where substr(lks_koderak,0,1) IN ('R','O') and substr(lks_tiperak,0,1) <>'S' ) on plur=prd_prdcd
            left join (
            SELECT 
              LKS_PRDCD as plud,
              LKS_KODERAK||'.'||LKS_KODESUBRAK||'.'||LKS_TIPERAK||'.'||LKS_SHELVINGRAK||'.'||LKS_NOURUT AS DISPLAY_DPD
            FROM TBMASTER_LOKASI
            where substr(lks_koderak,0,1) IN ('D') and substr(lks_tiperak,0,1) <>'S' ) 
            on plud=prd_prdcd
            WHERE LKS_KODERAK='$rak' 
            AND LKS_KODESUBRAK='$subrak' 
            $filter
            ORDER BY KATEGORI,LKS_KODERAK,LKS_KODESUBRAK,LKS_TIPERAK,LKS_SHELVINGRAK,LKS_NOURUT"
        );
        $display = $display->getResultArray();

        // Antrian trfLokasi
        if($aksi == "btndsp2") {
            $filter = " AND FMTIPE<>'S'";
        } elseif($aksi == "btnstr2") {
            $filter = " AND FMTIPE='S'";
        }
        
        if(!empty($filter)) {

            $trflokasi = $dbProd->query(
                "select * from (
                    select FMKRAK||'.'||FMSRAK||'.'||FMTIPE||'.'||FMSELV||'.'||FMNOUR as LOKASI_TRANSFER, 
                      FMKPLU as PLU_TRANSFER,
                      PRD_DESKRIPSIPENDEK as DESKRIPSI_TRANSFER,
                      PRD_FRAC AS FRAC_TRANSFER,
                      PRD_UNIT AS UNIT_TRANSFER
                      from igrbgr.tbtemp_trflokasi 
                      left join tbmaster_prodmast on prd_prdcd=fmkplu
                      where flagupd is null 
                      $filter
                      order by fmkrak,fmsrak,fmtipe,fmselv,fmnour
                    ) 
                    left join (
                      select LKS_KODERAK||'.'||LKS_KODESUBRAK||'.'||LKS_TIPERAK||'.'||LKS_SHELVINGRAK||'.'||LKS_NOURUT as LOKASI_EXIST, 
                      LKS_NOID as NOID_EXIST,
                      LKS_PRDCD as PLU_EXIST,
                      PRD_DESKRIPSIPENDEK as DESKRIPSI_EXIST,
                      PRD_FRAC AS FRAC_EXIST,
                      PRD_UNIT AS UNIT_EXIST,
                      LKS_QTY AS QTYPLANO_EXIST
                      from tbmaster_lokasi
                      left join tbmaster_prodmast on prd_prdcd=lks_prdcd
                    ) on lokasi_transfer=lokasi_exist"
            );
            $trflokasi = $trflokasi->getResultArray();
        };


        //                                               Live Cks
        // View LPP
        $plu = sprintf("%07s",$this->request->getVar('inputplu'));
		$plu1 = substr($plu,0,6)."%";
		$plu0 = substr($plu,0,6)."0";
        
        $viewlpp = $dbProd->query(
            "SELECT  
            ST_PRDCD AS PLU, 
            PRD_DESKRIPSIPANJANG AS DESKRIPSI, 
            PRD_KODETAG AS TAG, 
            case 
                when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')='Y' then 'IGR+OMI'
                when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')!='Y' then 'IGR Only'
                when nvl(prd_flagigr,'N')!='Y' and nvl(prd_flagomi,'N')='Y' then 'OMI Only'
                else 'TIDAK JUAL'
            end as FLAGJUAL,
            PRD_FRAC AS FRAC, 
            PRD_UNIT AS UNIT, 
            ST_AVGCOST AS ACOST, 
            ST_SALDOAKHIR AS LPP_QTY, 
            NVL(PQTY,0) AS PLANO_QTY, 
            NVL(PQTY,0)-ST_SALDOAKHIR AS SLSH_QTY,     
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC  
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC  
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) AS SLSH_RPH 
            FROM 
            TBMASTER_PRODMAST 
            LEFT JOIN 
            TBMASTER_STOCK ON ST_PRDCD = PRD_PRDCD
            LEFT JOIN 
            (SELECT LKS_PRDCD, SUM(LKS_QTY) AS PQTY FROM TBMASTER_LOKASI GROUP BY LKS_PRDCD) ON PRD_PRDCD=LKS_PRDCD 
            WHERE 
            ST_LOKASI='01' and prd_prdcd like '$plu0'"
        );
        $viewlpp = $viewlpp->getResultArray();


        // View KKPKM
        $maxpalet = $pkmt = $fraction = $nilaimaxpalet = 0;

        $viewkkpkm = $dbProd->query(
            "select 
            prd_prdcd as PLU,
            prd_deskripsipanjang as DESKRIPSI,
            prd_frac as fraction,
            pkm_pkm as PKM,
            pkm_mpkm as MPKM,
            pkmp_qtyminor as MPLUS,
            pkm_pkmt as PKMT,
            gdl_qty as NPLUS,
            mpl_namamonitoring as KKHPBM,
            pkm_minorder as MINOR,
            pkm_mindisplay as MINDIS,
            mpt_maxqty as MAXPALET
            from tbmaster_prodmast
            left join tbmaster_kkpkm on pkm_prdcd=prd_prdcd
            left join tbmaster_pkmplus on pkmp_prdcd=prd_prdcd
            left join (select * from TBTR_GONDOLA where trunc(gdl_tglakhir)>=trunc(sysdate)) on gdl_prdcd=prd_prdcd
            left join (select * from TBTR_MONITORINGPLU where mpl_kodemonitoring in ('F1','F2','NF1','NF2','G') ) on mpl_prdcd=prd_prdcd
            left join tbmaster_maxpalet on mpt_prdcd=prd_prdcd
            where prd_prdcd like '$plu0'"
        );
        $viewkkpkm = $viewkkpkm->getResultArray();

        // View Display
        $ttlmaxdis = $ttlmaxplano = 0;
        $viewdisplay = $dbProd->query(
            "SELECT 
            LKS_PRDCD as PLU,
            PRD_DESKRIPSIPENDEK AS DESKRIPSI,
            PRD_UNIT AS UNIT,
            PRD_FRAC AS FRAC,
            case 
              when substr(lks_koderak,0,1) in ('D') and lks_tiperak not in ('S','Z') then 'Display OMI'
              when substr(lks_koderak,0,1) in ('R','O') and lks_tiperak not in ('S','Z') then 'Display Toko'
              when substr(lks_koderak,0,1) in ('F') then 'Floor'
              when substr(lks_koderak,0,1) in ('H') then 'Hadiah'
            end as LOKASI_DISPLAY,
            LKS_KODERAK||'.'||LKS_KODESUBRAK||'.'||LKS_TIPERAK||'.'||LKS_SHELVINGRAK||'.'||LKS_NOURUT as ALAMAT_DISPLAY,
            LKS_JENISRAK AS JENISRAK,
            LKS_QTY AS QTYPLANO,
            LKS_MAXPLANO AS MAXPLANO,
            LKS_MAXDISPLAY AS MAXDIS,
            LKS_MINPCT AS MINPCT
            FROM TBMASTER_LOKASI 
            LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = LKS_PRDCD
            WHERE lks_tiperak<>'S' and LKS_PRDCD like '$plu0' 
            ORDER BY ALAMAT_DISPLAY"
        );
        $viewdisplay = $viewdisplay->getResultArray();
        
        foreach($viewdisplay as $dp) {
            $ttlmaxdis += $dp['MAXDIS'];
            $ttlmaxplano += $dp['MAXPLANO'];
        }

        // RUMUS PERHITUNGAN CKS
        foreach($viewkkpkm as $kp) {
            $maxpalet = $kp['MAXPALET'];
            $pkmt = $kp['PKMT'];
            $fraction = $kp['FRACTION'];
            $nilaimaxpalet = $maxpalet * $fraction;
        }

        // View Storage
        $viewstr = $dbProd->query(
            "select 
            lks_prdcd as PLU,
            substr(lks_shelvingrak,0,1) as tipe_storage,
            substr(lks_koderak,0,1) as RAK_STORAGE,
            count(lks_prdcd) as JML_STORAGE,
            sum(lks_qty) as QTYPLANO_STORAGE
            from tbmaster_lokasi 
            where lks_tiperak='S' and LKS_PRDCD like '$plu0' 
            GROUP BY lks_prdcd, substr(lks_shelvingrak,0,1), substr(lks_koderak,0,1)
            order by RAK_STORAGE"
        );
        $viewstr = $viewstr->getResultArray();

        // View SPB
        $viewspb = $dbProd->query(
            "select 
            spb_id,
            spb_recordid,
            spb_prdcd,
            spb_create_dt,
            spb_lokasiasal,
            spb_lokasitujuan,
            spb_qty as QTY_PLANO,
            spb_minus as QTY_SPB,
            case 
              when spb_recordid is null then 'Blm Turun'
              when spb_recordid = '1'   then 'Sdh Realisasi'
              when spb_recordid = '2'   then 'Batal'
              when spb_recordid = '3'   then 'Blm Realisasi'
            end as STATUS
          from tbtemp_antrianspb
          where spb_prdcd like '$plu0'"
        );
        $viewspb = $viewspb->getResultArray();

        $historyspb = $dbProd->query(
            "select * from (select 
            spb_id,
            spb_recordid,
            spb_prdcd,
            spb_create_dt,
            spb_lokasiasal,
            spb_lokasitujuan,
            spb_qty as QTY_PLANO,
            spb_minus as QTY_SPB,
            case 
              when spb_recordid is null then 'Blm Turun'
              when spb_recordid = '1'   then 'Sdh Realisasi'
              when spb_recordid = '2'   then 'Batal'
              when spb_recordid = '3'   then 'Blm Realisasi'
            end as STATUS
          from tbtr_antrianspb
          where spb_prdcd like '$plu0' order by spb_id desc
          ) where to_char(spb_create_dt,'yyyymmdd')>=to_char(sysdate,'yyyymmdd')-7 "
        );
        $historyspb = $historyspb->getResultArray();

        // View SLP
        $viewslp = $dbProd->query(
            "select * from (
                select 
                  slp_id,slp_create_dt,
                  slp_prdcd,
                  slp_koderak||'.'||slp_kodesubrak||'.'||slp_tiperak||'.'||slp_shelvingrak||'.'||slp_nourut as lokasitujuan,
                  slp_qtypcs,
                  slp_jenis,
                  CASE WHEN SLP_FLAG='C' THEN 'Batal'
                       WHEN SLP_FLAG IS NULL THEN 'Blm Realisasi'
                       WHEN SLP_FLAG='P' THEN 'Sdh Realisasi'
                  END AS STATUS
                from tbtr_slp 
                where slp_prdcd like '$plu0'
                order by slp_id desc
                ) where to_char(slp_create_dt,'yyyymm')>=to_char(sysdate,'yyyymm')-1"
        );
        $viewslp = $viewslp->getResultArray();

        $data = [
            'title' => 'Live CKS',
            'display' => $display,
            'trflokasi' => $trflokasi,
            'viewlpp' => $viewlpp,
            'viewkkpkm' => $viewkkpkm,
            'maxpalet' => $maxpalet,
            'pkmt' => $pkmt,
            'fraction' => $fraction,
            'nilaimaxpalet' => $nilaimaxpalet,
            'viewdisplay' => $viewdisplay,
            'ttlmaxdis' => $ttlmaxdis,
            'ttlmaxplano' => $ttlmaxplano,
            'viewstr' => $viewstr,
            'viewspb' => $viewspb,
            'historyspb' => $historyspb,
            'viewslp' => $viewslp,
        ];

        redirect()->to('/logistik/livecks')->withInput();
        return view('/logistik/livecks',$data);
    }

    public function produkbaru() {
        
        $data = [
            'title' => 'Monitoring Produk Baru',
        ];

        redirect()->to('/logistik/produkbaru')->withInput();
        return view('/logistik/produkbaru',$data);
    }

    public function tampilprodukbtb() {
        $dbProd = db_connect("production");
        $aksi = $this->request->getVar('tombol');
        $awal = $this->request->getVar('awal');
        $akhir = $this->request->getVar('akhir');
        $perprd = $filename = [];

        $perprd = $dbProd->query(
            "select 
            mstd_tgldoc as BPB_PERTAMA,
            mstd_qty as QTY_BPB,
            prd_kodedivisi as DIV,
            prd_kodedepartement as DEP,
            prd_kodekategoribarang as KAT,
            prd_prdcd as PLU_IGR,
            prc_pluomi as PLU_OMI,
            prd_deskripsipanjang as DESKRIPSI,
            prd_unit as UNIT,
            prd_frac as FRAC,
            prd_kodetag as TAG_IGR,
            prc_kodetag as TAG_OMI,
            pkm_minorder as MINOR,
            pkmp_qtyminor as MPLUS,
            pkm_pkmt as PKMT,
            BARCODE_PLU0,
            BARCODE_PLU1,
            BARCODE_PLU2,
            HARGAJUAL_PLU0,
            HARGAJUAL_PLU1,
            HARGAJUAL_PLU2,
            DISPLAY_TOKO,
            DISPLAY_OMI
            
          from tbmaster_prodmast
          left join tbmaster_prodcrm on prc_pluigr=prd_prdcd
          left join tbmaster_kkpkm on pkm_prdcd=prd_prdcd
          left join tbmaster_pkmplus on pkmp_prdcd=prd_prdcd
          LEFT JOIN      
            (SELECT lks_prdcd AS PLU_DISPLAYTOKO,      
              lks_koderak      
              ||'.'      
              ||lks_kodesubrak      
              ||'.'      
              ||lks_tiperak      
              ||'.'      
              ||lks_shelvingrak      
              || '.'      
              ||lks_nourut AS DISPLAY_TOKO      
            FROM tbmaster_lokasi      
            WHERE SUBSTR(lks_koderak,0,1) IN ('O','R','P')      
            AND SUBSTR(lks_tiperak,0,1)   <>'S'      
            )      
          ON PLU_DISPLAYTOKO=PRD_PRDCD      
          LEFT JOIN      
            (SELECT lks_prdcd AS PLU_DISPLAYOMI,      
              lks_koderak      
              ||'.'      
              ||lks_kodesubrak      
              ||'.'      
              ||lks_tiperak      
              ||'.'      
              ||lks_shelvingrak      
              || '.'      
              ||lks_nourut AS DISPLAY_OMI      
            FROM tbmaster_lokasi      
            WHERE SUBSTR(lks_koderak,0,1) IN ('D')      
            AND SUBSTR(lks_tiperak,0,1)   <>'S'      
            )      
          ON PLU_DISPLAYOMI=PRD_PRDCD   
          left join (
            select * from (
            select mstd_prdcd, mstd_tgldoc,mstd_qty, row_number() over (partition by mstd_prdcd order by mstd_tgldoc asc) as tgldoc  
            from tbtr_mstran_d where mstd_typetrn in ('B','L') 
            ) where tgldoc='1' and trunc(mstd_tgldoc) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
          ) on mstd_prdcd=prd_prdcd
          left join (
            select brc_prdcd as BRC_PLU0,brc_barcode as BARCODE_PLU0
            from tbmaster_barcode where brc_prdcd like '%0'
          ) on brc_plu0 = substr(prd_prdcd,0,6)||'0'
          left join (
            select brc_prdcd as BRC_PLU1,brc_barcode as BARCODE_PLU1
            from tbmaster_barcode where brc_prdcd like '%1'
          ) on brc_plu1 = substr(prd_prdcd,0,6)||'1'
          left join (
            select brc_prdcd as BRC_PLU2,brc_barcode as BARCODE_PLU2
            from tbmaster_barcode where brc_prdcd like '%2'
          ) on brc_plu2 = substr(prd_prdcd,0,6)||'2'
          
          left join (
            select prd_prdcd as HRG_PLU0,prd_hrgjual as HARGAJUAL_PLU0
            from tbmaster_prodmast where prd_prdcd like '%0'
          ) on hrg_plu0 = substr(prd_prdcd,0,6)||'0'
          left join (
            select prd_prdcd as HRG_PLU1,prd_hrgjual as HARGAJUAL_PLU1
            from tbmaster_prodmast where prd_prdcd like '%1'
          ) on hrg_plu1 = substr(prd_prdcd,0,6)||'1'
          left join (
            select prd_prdcd as HRG_PLU2,prd_hrgjual as HARGAJUAL_PLU2
            from tbmaster_prodmast where prd_prdcd like '%2'
          ) on hrg_plu2 = substr(prd_prdcd,0,6)||'2'
          
          where mstd_tgldoc is not null "
        );
        $perprd = $perprd->getResultArray();

        $data = [
            'title' => 'Monitoring Produk Baru',
            'perprd' => $perprd,
        ];

        if($aksi == "btnbpb") {
            $filename = "PRODUK BARU ".date('d M Y').".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
                          
            return view('/logistik/tampilprodukbtb',$data);
        };
    }

    public function tampilproduk() {
        $dbProd = db_connect("production");
        $aksi = $this->request->getVar('tombol');
        $perdiv = $filename = [];
        $divisi = $this->request->getVar('divisi');
        if($divisi=="0") {
            $filterdivisi ="";
        }else{
            $filterdivisi = "and prd_kodedivisi='$divisi' ";
        }

        $tag = $_POST['tag'];
        if($tag=="0") {
            $filtertag ="";
        }else if($tag=="1"){
            $filtertag = "and prd_kodetag not in ('H','O','A','X','N','T') ";
        }else if($tag=="2"){
            $filtertag = "and prd_kodetag in ('H','O','A','X','N','T') ";
        }else{
            $filtertag = "";
        }
        
        $bln = date("m");
        switch ($bln) {
            case "1": $bln1="10";$bln2="11";$bln3="12";break;
            case "2": $bln1="11";$bln2="12";$bln3="01";break;
            case "3": $bln1="12";$bln2="01";$bln3="02";break;
            case "4": $bln1="01";$bln2="02";$bln3="03";break;
            case "5": $bln1="02";$bln2="03";$bln3="04";break;
            case "6": $bln1="03";$bln2="04";$bln3="05";break;
            case "7": $bln1="04";$bln2="05";$bln3="06";break;
            case "8": $bln1="05";$bln2="06";$bln3="07";break;
            case "9": $bln1="06";$bln2="07";$bln3="08";break;
            case "10": $bln1="07";$bln2="08";$bln3="09";break;
            case "11": $bln1="10";$bln2="11";$bln3="12";break;
            case "12": $bln1="09";$bln2="10";$bln3="11";break;
            default : $bln1="10";$bln2="11";$bln3="12";}

        $perdiv = $dbProd->query(
            "select 
            prd_kodedivisi as DIV,
            prd_kodedepartement as DEP,
            prd_kodekategoribarang as KAT,
            prd_prdcd as PLU_IGR,
            prc_pluomi as PLU_OMI,
            prd_deskripsipanjang as DESKRIPSI,
            prd_unit as UNIT,
            prd_frac as FRAC,
            pkm_minorder as MINOR,
            pkm_mindisplay as MINDIS,
            prd_flagbkp2 as BKP,
            st_avgcost*prd_frac as ACOST,
            st_lastcost*prd_frac as LCOST,
            prd_hrgjual as HRGJUAL,
            hgb_hrgbeli*prd_frac as HRGBELI,
            case when trunc(hgb_tglakhirdisc01)>=trunc(sysdate) then hgb_persendisc01 else 0 end as DISC1,
            case when trunc(hgb_tglakhirdisc02)>=trunc(sysdate) then hgb_persendisc02 else 0 end as DISC2,
            hgb_statusbarang as STATUS,
            prd_kodetag as TAG_IGR,
            prc_kodetag as TAG_OMI,
            st_saldoakhir as STOCK,
            CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC  ELSE ST_SALDOAKHIR*ST_AVGCOST END AS STOCK_RPH,
            hgb_kodesupplier as KODESUP,
            sup_namasupplier as NAMASUPPLIER,
            sls_qty_01 as JAN,
            sls_qty_02 as PEB,
            sls_qty_03 as MAR,
            sls_qty_04 as APR,
            sls_qty_05 as MEI,
            sls_qty_06 as JUN,
            sls_qty_07 as JUL,
            sls_qty_08 as AGS,
            sls_qty_09 as SEP,
            sls_qty_10 as OKT,
            sls_qty_11 as NOV,
            sls_qty_12 as DES,
            st_sales as BLN_INI,
            pkm_pkm as PKM,
            pkmp_qtyminor as MPLUS,
            pkm_pkmt as PKMT,
            pkm_leadtime as LT,
            case when st_saldoakhir>0 and st_sales>0 then round((((nvl(st_saldoawal,1) + nullif(st_saldoakhir,0))/2)/nullif(st_sales,0)) * (extract(day from sysdate))) else 0 end as DSI,
            LASTPO,
            LASTBPB,
            QTY_PO,
            jml_po,
            pln_tglaktif,
            Display_REG,
            Display_DPD,
            case 
                when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')='Y' then 'IGR+OMI'
                when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')!='Y' then 'IGR ONLY'
                when nvl(prd_flagigr,'N')!='Y' and nvl(prd_flagomi,'N')='Y' then 'OMI ONLY'
                else 'TIDAK JUAL'
              end as FLAG_JUAL
            
            from tbmaster_stock
            left join tbmaster_prodmast on prd_prdcd=st_prdcd
            left join tbmaster_prodcrm on prc_pluigr=prd_prdcd
            left join tbmaster_kkpkm on pkm_prdcd=prd_prdcd
            left join tbmaster_pkmplus on pkmp_prdcd=prd_prdcd
            left join tbmaster_hargabeli on hgb_prdcd=prd_prdcd
            left join tbmaster_supplier on sup_kodesupplier=hgb_kodesupplier
            left join tbtr_salesbulanan on sls_prdcd=prd_prdcd
            left join TBMASTER_MAXPALET on mpt_prdcd=prd_prdcd
            left join ( 
            select tpod_prdcd AS PLUPOAKHIR,max(tpod_tglpo)as LASTPO
                from tbtr_po_d group by tpod_prdcd
            )on PLUPOAKHIR=prd_prdcd 
            left join ( 
            select mstd_prdcd AS PLUBPBAKHIR,max(mstd_tgldoc)as LASTBPB
                from tbtr_mstran_d where mstd_typetrn='B' group by mstd_prdcd
            )on PLUBPBAKHIR=prd_prdcd 
            left join (
                select tpod_prdcd as PLUPO,count(tpod_nopo) as jml_po,sum(tpod_qtypo) as QTY_PO
                from tbtr_po_d 
                left join tbtr_po_h on tpod_nopo=tpoh_nopo
                where trunc(tpoh_tglpo)+tpoh_jwpb>=trunc(sysdate)
                and tpoh_recordid is null group by tpod_prdcd) on PRD_PRDCD=plupo
            left join (
            SELECT 
              LKS_PRDCD as plur,
              LKS_KODERAK||'.'||LKS_KODESUBRAK||'.'||LKS_TIPERAK||'.'||LKS_SHELVINGRAK||'.'||LKS_NOURUT AS DISPLAY_REG,
              LKS_JENISRAK as JENIS_ITEM,lks_maxplano as MAXPLANO_REG,lks_minpct as MINPCT_REG
            FROM TBMASTER_LOKASI
            where substr(lks_koderak,0,1) IN ('R','O') and substr(lks_tiperak,0,1) <>'S' ) on plur=prd_prdcd
            left join (
            SELECT 
              LKS_PRDCD as plud,
              LKS_KODERAK||'.'||LKS_KODESUBRAK||'.'||LKS_TIPERAK||'.'||LKS_SHELVINGRAK||'.'||LKS_NOURUT AS DISPLAY_DPD,
              LKS_JENISRAK as JENIS_ITEM_DPD,lks_maxplano as MAXPLANO_DPD,lks_minpct as MINPCT_DPD
            FROM TBMASTER_LOKASI
            where substr(lks_koderak,0,1) IN ('D') and substr(lks_tiperak,0,1) <>'S' ) 
            on plud=prd_prdcd
            left join (
                select distinct lks_prdcd as plu_cks,substr(lks_shelvingrak,0,1) as jenis_cks
                from tbmaster_lokasi where lks_tiperak='S'
            ) on prd_prdcd=plu_cks
            left join tbmaster_barangbaru on pln_prdcd=prd_prdcd    
            where st_lokasi='01' and pln_prdcd is not null 
            $filtertag
            $filterdivisi
            order by DIV,DEP,DESKRIPSI"
        );
        $perdiv = $perdiv->getResultArray();

        $data = [
            'title' => 'Monitoring Produk Baru',
            'perdiv' => $perdiv,
            'divisi' => $divisi,
            'tag' => $tag,
        ];

        if($aksi == "btndiv") {
            $filename = "PRODUK BARU ".date('d M Y').".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
              
            return view('/logistik/tampilproduk',$data);
        };
    }

    public function ubahstatus() {
        $data = [
          'title' => 'Monitoring Perubahan Status',
        ];
  
        redirect()->to('/logistik/ubahstatus')->withInput();
        return view('logistik/ubahstatus',$data);
    }

    public function tampilubahstatus() {
        $dbProd = db_connect("production");
        $awal = $this->request->getVar('awal');
        $akhir = $this->request->getVar('akhir');
        $status = $this->request->getVar('status');
        $aksi = $this->request->getVar('tombol');
        $filename = $ubahstatus = [];

        if($status == "0") {
            $filterstatus = "  and mstd_nodoc is null ";
            $sts = "[BELUM UBAH STATUS]";
        }else if($status == "1") {
            $filterstatus = " and mstd_nodoc is not null ";
            $sts = "[SUDAH UBAH STATUS]";
        }else if($status == "2") {
            $filterstatus = " ";
            $sts = "[ALL]";
        }

        $ubahstatus = $dbProd->query(
            "select prd_kodedivisi as DIV,prd_kodedepartement as DEP,prd_kodekategoribarang as KAT,
            prd_prdcd as PLU,prd_deskripsipanjang as DESKRIPSI,prd_frac as FRAC,prd_unit as UNIT,prd_kodetag as TAG,
            srt_tglsortir,srt_nosortir,srt_keterangan,(srt_frac*srt_qtykarton)+srt_qtypcs as srt_qty,
            case when prd_unit='KG' then srt_avgcost else srt_avgcost/prd_frac end as Acost_pcs,
            mstd_tgldoc,mstd_nodoc,mstd_qty,
            case when mstd_flagdisc1='B' then 'BAIK'
                 when mstd_flagdisc1='T' then 'RETUR'
                 when mstd_flagdisc1='R' then 'RUSAK'
            end as STATUS_DARI,
            case when mstd_flagdisc2='B' then 'BAIK'
                 when mstd_flagdisc2='T' then 'RETUR'
                 when mstd_flagdisc2='R' then 'RUSAK'
            end as STATUS_KE
            from TBTR_SORTIR_BARANG
            left join tbtr_mstran_d on mstd_prdcd=srt_prdcd and mstd_nofaktur=srt_nosortir
            left join tbtr_mstran_h on msth_nodoc=mstd_nodoc
            left join tbmaster_prodmast on prd_prdcd=srt_prdcd
            where trunc(srt_tglsortir) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
            $filterstatus 
            order by div,dep,kat, deskripsi,srt_tglsortir"
        );
        $ubahstatus = $ubahstatus->getResultArray();

        $data = [
          'title' => 'Monitoring Perubahan Status',
          'awal' => $awal,
          'akhir' => $akhir,
          'status' => $status,
          'ubahstatus' => $ubahstatus,
        ];
  
        if($aksi == "btnsts") {
            $filename = "PERUBAHAN STATUS ".$sts." ".date('d M Y').".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
            
            return view('logistik/tampilubahstatus',$data);
        };
        return view('logistik/tampilubahstatus',$data);
    }

    public function itemseasonal() {
        $data = [
            'title' => 'Monitoring Item Seasonal',
        ];
  
        redirect()->to('/logistik/itemseasonal')->withInput();
        return view('logistik/itemseasonal',$data);
    }

    public function tampilitemseasonal() {
        $dbProd = db_connect("production");
        $awal = $this->request->getVar('awal');
        $akhir = $this->request->getVar('akhir');
        $aksi = $this->request->getVar('tombol');
        $seasonal = $filename = [];

        $seasonal = $dbProd->query(
            "select 
            sup_kodesupplier as KDSUP,
            sup_namasupplier as NAMASUP,
            prd_kodedivisi as DIV,
            prd_kodedepartement as DEP,
            prd_kodekategoribarang as KAT,
            prd_prdcd as PLU,
            prd_deskripsipanjang as DESKRIPSI,
            prd_unit as UNIT,
            prd_frac as FRAC,
            case 
                when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')='Y' then 'IGR+OMI'
                when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')!='Y' then 'IGR ONLY'
                when nvl(prd_flagigr,'N')!='Y' and nvl(prd_flagomi,'N')='Y' then 'OMI ONLY'
                else 'TIDAK JUAL'
              end as FLAGJUAL,
            st_avgcost as ACOST,
            st_lastcost as LCOST,
            st_saldoakhir as STOK,
            case when prd_unit='KG' then st_saldoakhir*(st_avgcost/1000) else st_saldoakhir*st_avgcost end as RPHSTOK,
            LASTBPB,
            LASTPO,
            QTY_PO_OUTSTANDING,
            alamatdisplay,
            lks_qty,
            HARGA_PLU0,
            HARGA_PLU1,
            HARGA_PLU2,
            HARGA_PLU3,
            HARGA_PROMO
            from tbtr_monitoringplu
            left join tbmaster_prodmast on prd_prdcd=mpl_prdcd
            left join (select * from tbmaster_stock where st_lokasi='01') on prd_prdcd=st_prdcd
            left join tbmaster_hargabeli on hgb_prdcd=prd_prdcd
            left join tbmaster_supplier on sup_kodesupplier=hgb_kodesupplier
            left join (
                select prd_prdcd as plu0,prd_hrgjual as harga_plu0 from tbmaster_prodmast where prd_prdcd like '%0'
            )on prd_prdcd=plu0
            left join (
                select prd_prdcd as plu1,prd_hrgjual as harga_plu1 from tbmaster_prodmast where prd_prdcd like '%1'
            )on prd_prdcd=substr(plu1,0,6)||'0'
            left join (
                select prd_prdcd as plu2,prd_hrgjual as harga_plu2 from tbmaster_prodmast where prd_prdcd like '%2'
            )on prd_prdcd=substr(plu2,0,6)||'0'
            left join (
                select prd_prdcd as plu3,prd_hrgjual as harga_plu3 from tbmaster_prodmast where prd_prdcd like '%3'
            )on prd_prdcd=substr(plu3,0,6)||'0'
            left join (
                select prmd_prdcd as plupromo,prmd_hrgjual as harga_promo from tbtr_promomd where prmd_prdcd like '%0' and trunc(prmd_tglakhir)>=trunc(sysdate)
            )on prd_prdcd=plupromo
            left join (
                select tpod_prdcd as PLUPO,count(tpod_nopo) as jml_po,sum(tpod_qtypo) as QTY_PO_OUTSTANDING
                from tbtr_po_d 
                left join tbtr_po_h on tpod_nopo=tpoh_nopo
                where trunc(tpoh_tglpo)+tpoh_jwpb>=trunc(sysdate)
                and tpod_recordid is null group by tpod_prdcd) on PRD_PRDCD=plupo
            left join (
                select lks_prdcd,lks_koderak||'.'||lks_kodesubrak||'.'||lks_tiperak||'.'||lks_shelvingrak||'.'||lks_nourut as alamatdisplay,lks_qty
                from tbmaster_lokasi where substr(lks_koderak,0,1) IN ('O','R','P') and substr(lks_tiperak,0,1) <>'S' ) on lks_prdcd=prd_prdcd
            left join ( 
            select tpod_prdcd AS PLUPOAKHIR,max(tpod_tglpo)as LASTPO
                from tbtr_po_d group by tpod_prdcd
            )on PLUPOAKHIR=prd_prdcd 
            left join ( 
            select mstd_prdcd AS PLUBPBAKHIR,max(mstd_tgldoc)as LASTBPB
                from tbtr_mstran_d where mstd_typetrn='B' group by mstd_prdcd
            )on PLUBPBAKHIR=prd_prdcd 
            where mpl_kodemonitoring='G'
            and trunc(LASTBPB) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
            order by DIV,DEP,DESKRIPSI,LASTBPB"
        );
        $seasonal = $seasonal->getResultArray();

        $data = [
          'title' => 'Data Item Seasonal',
          'seasonal' => $seasonal,
          'awal' => $awal,
          'akhir' => $akhir,
        ];

        if($aksi == 'btnssn') {
            return view('logistik/tampilitemseasonal',$data);
        } else if($aksi == 'btnxls') {
            $filename = "ITEM SEASONAL - ".date('d M Y',strtotime($awal))." sd ".date('d M Y',strtotime($akhir)).".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        
            return view('logistik/tampilitemseasonal',$data);
        };
    }

    public function backoffice() {
        $data = [
            'title' => 'Laporan Back Office',
        ];
  
        redirect()->to('/logistik/backoffice')->withInput();
        return view('logistik/backoffice',$data);
    }

    public function tampildatabo() {
        $dbProd = db_connect("production");
        $aksi = $this->request->getVar('tombol');
        $awal = $this->request->getVar('awal');
        $akhir = $this->request->getVar('akhir');
        $lap = $this->request->getVar('jenisLaporan');
        $viewTransaksiBO = $databo = [];

        //Pilihan
        $plu = $dvs = $sup = $jenis = $sortBy = "All";
        $jenistrx = $kdplu = $kdsup = $kdsup0 = $div = $div0 = $plu0 = $kdplu0 = "";

        //Filter query
        if(isset($_GET['jnstrx'])) {if ($_GET['jnstrx'] !=""){$jenis = $_GET['jnstrx']; }}
        if ($jenis != "All" AND $jenis != "") {
            $jenistrx = " AND trn_type = '$jenis' ";
        }

        if(isset($_GET['kodePLU'])) {if ($_GET['kodePLU'] !=""){$plu = $_GET['kodePLU']; }}
        if ($plu != "All" AND $plu != "") {
            $plu = substr('00000000' . $plu, -7);
            $kdplu = " AND trn_prdcd = '$plu' ";
            $plu0 = substr('00000000' . $plu, -7);
            $kdplu0 = " and prd_prdcd='$plu0' ";
        }

        if(isset($_GET['kodesup'])) {if ($_GET['kodesup'] !=""){$sup = $_GET['kodesup']; }}
        if ($sup != "All" AND $sup != "") {
            $kdsup = " AND trn_kode_supplier like '%$sup%' ";
            $kdsup0 = " and sup_kodesupplier = '$sup' ";
        }

        if(isset($_GET['divisi'])) {if ($_GET['divisi'] !=""){$dvs = $_GET['divisi']; }}
        if ($dvs != "All" AND $dvs != "") {
            $div = " AND trn_div = $dvs ";
            $div0 = " and prd_kodedivisi='$dvs' ";
        }

        $viewTransaksiBO = 
            "(SELECT m.mstd_typetrn        AS trn_type,
            m.mstd_tgldoc              AS trn_tgldoc,
            m.mstd_nodoc               AS trn_nodoc,
            m.mstd_nopo                AS trn_nopo,
            TRUNC(m.mstd_tglpo)        AS trn_tglpo,
            m.mstd_seqno               AS trn_seqno,
            p.prd_kodedivisi           AS trn_div,
            p.prd_kodedepartement      AS trn_dept,
            p.prd_kodekategoribarang   AS trn_katb,
            m.mstd_prdcd               AS trn_prdcd,
            p.prd_deskripsipanjang     AS trn_nama_barang,
            m.mstd_unit                AS trn_unit,
            m.mstd_frac                AS trn_frac,
            NVL(p.prd_kodetag,' ')     AS trn_tag,
            m.mstd_qty                 AS trn_qty,
            NVL(m.mstd_qtybonus1, 0)   AS trn_qty_bonus1,
            NVL(m.mstd_qtybonus2, 0)   AS trn_qty_bonus2,
            m.mstd_hrgsatuan           AS trn_harga_satuan,
            m.mstd_gross               AS trn_gross,
            NVL(m.mstd_discrph, 0)     AS trn_discount,
            NVL(m.mstd_ppnrph, 0)      AS trn_ppn,
            NVL(m.mstd_flagdisc1, ' ') AS trn_flag1,
            NVL(m.mstd_flagdisc2, ' ') AS trn_flag2,
        
            NVL(m.mstd_dis4rr, 0)      AS trn_dis4rr,
            NVL(m.mstd_dis4jr, 0)      AS trn_dis4jr,
            NVL(m.mstd_dis4cr, 0)      AS trn_dis4cr,
            NVL(m.mstd_ppnbtlrph, 0)   AS trn_ppnbtlrph,
            NVL(m.mstd_ppnbmrph, 0)    AS trn_ppnbmrph,
        
            m.mstd_kodesupplier        AS trn_kode_supplier,
            s.sup_namasupplier         AS trn_nama_supplier
          FROM tbtr_mstran_d m,
            tbmaster_prodmast p,
            tbmaster_supplier s
          WHERE m.mstd_prdcd      = p.prd_prdcd (+)
          AND m.mstd_kodesupplier = s.sup_kodesupplier (+)
          AND m.mstd_recordid    IS NULL)";

        if($lap == "1") {
            $databo = $dbProd->query(
                "SELECT trn_type ,  
                trn_div ,
                trn_dept ,
                trn_katb ,
                trn_prdcd ,
                trn_nama_barang ,
                trn_unit ,
                trn_frac ,
                trn_tag ,
                sum(trn_qty) as trn_qty,
                sum(trn_qty_bonus1 + trn_qty_bonus2) as trn_qty_bonus,
                sum(trn_gross) as trn_gross,
                sum(trn_discount) as trn_discount,
                sum(trn_ppn) as trn_ppn,
                min(trn_kode_supplier) as trn_kode_supplier,
                min(trn_nama_supplier) as trn_nama_supplier
                FROM " . $viewTransaksiBO .
                " WHERE trunc(trn_tgldoc) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
                $jenistrx
                $kdplu
                $kdsup
                $div
                GROUP BY trn_type, trn_div, trn_dept, trn_katb, trn_prdcd, trn_nama_barang, trn_unit, trn_frac, trn_tag
                ORDER BY trn_type, trn_div, trn_dept, trn_katb, trn_prdcd"
            );
            $databo = $databo->getResultArray();
        } else if($lap == "1B") {
            $databo = $dbProd->query(
                "SELECT trn_type,
                trn_tgldoc,
                trn_nodoc,
                trn_nopo,
                trn_tglpo,
                trn_div,
                trn_dept,
                trn_katb,
                trn_prdcd,
                trn_nama_barang,
                trn_unit,
                trn_frac,
                trn_tag,
                trn_qty,
                NVL(trn_qty_bonus1,0) + NVL(trn_qty_bonus2,0) AS trn_qty_bonus,
                trn_harga_satuan,
                trn_gross,
                trn_discount,
                trn_ppn,
                trn_kode_supplier,
                trn_nama_supplier,
                trn_flag1,
                trn_flag2
              FROM " .$viewTransaksiBO .
                " WHERE trunc(trn_tgldoc) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
                $jenistrx
                $kdplu
                $kdsup
                $div
                ORDER BY trn_tgldoc,trn_type, trn_div, trn_dept, trn_katb, trn_prdcd"
            );
            $databo = $databo->getResultArray();
        } else if($lap == "2") {
            $databo = $dbProd->query(
                "SELECT trn_type,
                trn_kode_supplier,
                trn_nama_supplier,
                COUNT(trn_prdcd)                     AS trn_item,
                SUM(trn_qty)                         AS trn_qty,
                SUM(trn_qty_bonus1 + trn_qty_bonus2) AS trn_qtybonus,
                SUM(trn_gross)                       AS trn_gross,
                SUM(trn_discount)                    AS trn_discount,
                SUM(trn_ppn)                         AS trn_ppn
              FROM " . $viewTransaksiBO .
                " WHERE trunc(trn_tgldoc) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
                $jenistrx
                $kdplu
                $kdsup
                $div
                GROUP BY trn_type, trn_kode_supplier, trn_nama_supplier
                ORDER BY trn_type, trn_kode_supplier, trn_nama_supplier"
            );
            $databo = $databo->getResultArray();
        } else if($lap == "3") {
            $databo = $dbProd->query(
                "SELECT trn_type,
                trn_div,
                div.div_namadivisi                   AS trn_div_nama,
                Count(DISTINCT( trn_kode_supplier )) AS trn_kode_supplier,
                Count(DISTINCT( trn_prdcd ))         AS trn_item,
                SUM(trn_qty)                         AS trn_qty,
                SUM(trn_qty_bonus1 + trn_qty_bonus2) AS trn_qtybonus,
                SUM(trn_gross)                       AS trn_gross,
                SUM(trn_discount)                    AS trn_discount,
                SUM(trn_ppn)                         AS trn_ppn
                FROM {$viewTransaksiBO} t,
                    tbmaster_divisi div
                WHERE  t.trn_div = div.div_kodedivisi (+)
                AND    trunc(trn_tgldoc) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
                $jenistrx
                $kdplu
                $kdsup
                $div
                GROUP BY trn_type, trn_div, div.div_namadivisi
                ORDER BY trn_type, trn_div"
            );
            $databo = $databo->getResultArray();
        } else if($lap == "4") {
            $databo = $dbProd->query(
                "SELECT trn_type,
                trn_div,
                trn_dept,
                Count(DISTINCT( trn_kode_supplier )) AS trn_kode_supplier,
                Count(DISTINCT( trn_prdcd ))         AS trn_item,
                Sum(trn_qty)                         AS trn_qty,
                Sum(trn_qty_bonus1 + trn_qty_bonus2) AS trn_qtybonus,
                Sum(trn_gross)                       AS trn_gross,
                Sum(trn_discount)                    AS trn_discount,
                Sum(trn_ppn)                         AS trn_ppn
                FROM   {$viewTransaksiBO}
                WHERE  trunc(trn_tgldoc) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
                $jenistrx
                $kdplu
                $kdsup
                $div
                GROUP BY trn_type, trn_div,trn_dept
                ORDER BY trn_type, trn_div,trn_dept"
            );
            $databo = $databo->getResultArray();
        } else if($lap == "5") {
            $databo = $dbProd->query(
                "SELECT trn_type,
                trn_div,
                trn_dept,
                trn_katb,
                Count(DISTINCT( trn_kode_supplier )) AS trn_kode_supplier,
                Count(DISTINCT( trn_prdcd ))         AS trn_item,
                Sum(trn_qty)                         AS trn_qty,
                Sum(trn_qty_bonus1 + trn_qty_bonus2) AS trn_qtybonus,
                Sum(trn_gross)                       AS trn_gross,
                Sum(trn_discount)                    AS trn_discount,
                Sum(trn_ppn)                         AS trn_ppn
                FROM   {$viewTransaksiBO}
                WHERE  trunc(trn_tgldoc) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
                $jenistrx
                $kdplu
                $kdsup
                $div
                GROUP BY trn_type, trn_div,trn_dept,trn_katb
                ORDER BY trn_type, trn_div,trn_dept,trn_katb"
            );
            $databo = $databo->getResultArray();
        } else if($lap == "6") {
            $databo = $dbProd->query(
                "SELECT trn_type,
                trn_tgldoc,
                  COUNT(DISTINCT(trn_kode_supplier))   AS trn_kode_supplier,
                COUNT(trn_prdcd)                     AS trn_item,
                SUM(trn_qty)                         AS trn_qty,
                SUM(trn_qty_bonus1 + trn_qty_bonus2) AS trn_qtybonus,
                SUM(trn_gross)                       AS trn_gross,
                SUM(trn_discount)                    AS trn_discount,
                SUM(trn_ppn)                         AS trn_ppn
                FROM " . $viewTransaksiBO .
                " WHERE trunc(trn_tgldoc) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
                $jenistrx
                $kdplu
                $kdsup
                $div
                GROUP BY trn_tgldoc, trn_type
                ORDER BY trn_tgldoc, trn_type"
            );
            $databo = $databo->getResultArray();
        } else if($lap == "0") {
            if($jenis == "RETUROMI") {
                $databo = $dbProd->query(
                    "select 
                    tko_kodeomi as KODEOMI,
                    tko_namaomi as NAMAOMI,
                    rom_nodokumen as NODOKUMEN,
                    rom_tgldokumen as TGLDOKUMEN,
                    rom_noreferensi as NOREFERENSI,
                    rom_tglreferensi as TGLREFERENSI,
                    prd_kodedivisi as DIV,
                    prd_kodedepartement as DEP,
                    prd_kodekategoribarang as KAT,
                    prd_prdcd as PLU,
                    prd_deskripsipanjang as DESKRIPSI,
                    prd_flagbkp2 as BKP,
                    prd_unit as UNIT,
                    prd_frac as FRAC,
                    rom_qty as QTY,
                    rom_ttl as RUPIAH
                    from tbtr_returomi
                    left join tbmaster_tokoigr on tko_kodeomi=rom_kodetoko
                    left join tbmaster_prodmast on prd_prdcd=rom_prdcd                           
                    where trunc(rom_tgldokumen) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
                    $kdplu0
                    $div0 
                    order by rom_tgldokumen,tko_kodeomi,rom_nodokumen"
                );
                $databo = $databo->getResultArray();
            } else if($jenis == "SOIC") {
                $databo = $dbProd->query(
                    "select     
                    rso_tglso as TANGGAL,   
                    rso_kodeso as NOMORSO,   
                    prd_kodedivisi as DIV,    
                    prd_kodedepartement as DEP,    
                    prd_kodekategoribarang as KAT,    
                    prd_prdcd as PLU,    
                    prd_deskripsipanjang as DESKRIPSI,    
                    prd_unit as UNIT,   
                    prd_frac as FRAC,    
                    prd_kodetag as TAG,  
                    case 
                        when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')='Y' then 'IGR+OMI'
                        when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')!='Y' then 'IGR ONLY'
                        when nvl(prd_flagigr,'N')!='Y' and nvl(prd_flagomi,'N')='Y' then 'OMI ONLY'
                        else 'TIDAK BISA JUAL'
                    end as FLAGJUAL,
                    rso_qtyreset as QTY,    
                    (rso_qtyreset*rso_avgcostreset) as RUPIAH     
                    from TBTR_RESET_SOIC    
                    left join tbmaster_prodmast on prd_prdcd=rso_prdcd    
                    LEFT JOIN TBMASTER_PRODCRM ON PRC_PLUIGR=PRD_PRDCD  
                    where trunc(rso_tglso) between to_date('$awal','YYYY-MM-DD') and to_date('$akhir','YYYY-MM-DD')    
                    $kdplu0
                    $div0 
                    order by tanggal,DIV,DEP,DESKRIPSI"
                );
                $databo = $databo->getResultArray();
            } else if($jenis == "POBTBSUP") {
                $databo = $dbProd->query(
                    "select       
                    sup_kodesupplier as KODESUP,      
                    sup_namasupplier as NAMASUPPLIER,      
                    prd_kodedivisi as DIV,     
                    prd_kodedepartement as DEP,     
                    prd_kodekategoribarang as KAT,     
                    prd_prdcd as PLU,
                    prd_Deskripsipanjang as DESKRIPSI,     
                    prd_frac as FRAC,     
                    prd_unit as UNIT,     
                    prd_kodetag as TAG,
                    tpod_nopo  AS NOPO,      
                    tpod_tglpo as TGLPO, 
                    tpod_qtypo as QTYPO,       
                    tpod_gross as RPHPO,       
                    mstd_nodoc as NOBTB,
                    mstd_tgldoc as TGLBTB,
                    mstd_qty as QTYBTB,       
                    mstd_gross as RPHBTB     
                    from tbtr_po_d       
                    left join tbtr_po_h on tpoh_nopo=tpod_nopo      
                    left join tbtr_mstran_d on tpod_prdcd=mstd_prdcd and tpod_nopo=mstd_nopo       
                    left join tbmaster_prodmast on prd_prdcd=tpod_prdcd     
                    left join tbmaster_supplier on sup_kodesupplier=tpoh_kodesupplier    
                    where trunc(tpod_tglpo)  between to_date('$awal','YYYY-MM-DD') and to_date('$akhir','YYYY-MM-DD')    
                    $kdplu0
                    $div0
                    $kdsup0
                    order by prd_deskripsipanjang,tpod_tglpo"
                );
                $databo = $databo->getResultArray();
            };
        };       
        

        $data = [
            'title' => 'Data Back Office',
            'databo' => $databo,
            'awal' => $awal,
            'akhir' => $akhir,
            // 'jenistrx' => $jenistrx,
            // 'kdplu' => $kdplu,
            // 'kdsup' => $kdsup,
            // 'div' => $div,
            'lap' => $lap,
            'jenis' => $jenis,
        ];
  
        redirect()->to('/logistik/tampildatabo')->withInput();
        return view('logistik/tampildatabo',$data);
    }
}