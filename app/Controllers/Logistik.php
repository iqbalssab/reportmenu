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
        if(isset($_POST['plu'])) {
            $plu = trim($_POST['plu']);
        }elseif(isset($_GET['plu'])){
            $plu = trim($_GET['plu']);
        }else{
            echo "<h3>Jangan lupa isi PLU-nya... :)</h3>";
            $plu = 0;
        }
        $pluex = explode(",",$plu);
        $tanggal = $this->request->getVar('tgl');
        // $divisi = $this->request->getVar('divisi');
        $aksi = $this->request->getVar('tombol');
        $soharian = $totalqtyplano = $totalqtylpp = $acost = $flagjual = $cekslp = $cekspb = $cekklik = $cektmi = $cekomi = [];
      
        foreach($pluex as $plu0) {
            $plu0 = "'".sprintf("%07s",$plu0)."'";
            $soharian = $dbProd->query(
                "SELECT 
                PRD_PRDCD AS PLU,
                PRD_DESKRIPSIPENDEK AS DESKRIPSI,
                PRD_UNIT || ' / ' || PRD_FRAC AS FRAC,
                PRD_KODETAG AS TAG,
                case 
                  when SUBSTR(LKS_KODERAK,0,1) not in ('D','G') then 'TOKO'
                  when SUBSTR(LKS_KODERAK,0,1) in ('D') then 'OMI'
                  when SUBSTR(LKS_KODERAK,0,1) in ('G') then 'GUDANG'
                end as AREA,
                case 
                    when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')='Y' then 'IGR+OMI'
                    when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')!='Y' then 'IGR ONLY'
                    when nvl(prd_flagigr,'N')!='Y' and nvl(prd_flagomi,'N')='Y' then 'OMI ONLY'
                    else 'TIDAK BISA JUAL'
                end as FLAGJUAL,
                LKS_KODERAK || '.' || LKS_KODESUBRAK || '.' || LKS_TIPERAK || '.' || LKS_SHELVINGRAK || '.' || LKS_NOURUT AS LOKASI,
                LKS_QTY AS STOK_PLANO,
                ST_SALDOAKHIR as STOK_LPP,
                ST_AVGCOST as ACOST,
                lks_modify_by as MODBY
                FROM TBMASTER_PRODMAST 
                LEFT JOIN TBMASTER_LOKASI ON PRD_PRDCD=LKS_PRDCD
                LEFT JOIN TBMASTER_STOCK ON ST_PRDCD=PRD_PRDCD
                WHERE ST_LOKASI='01' 
                AND PRD_PRDCD IN ($plu0) 
                ORDER BY DESKRIPSI,AREA, LKS_KODERAK, LKS_KODESUBRAK,LKS_TIPERAK, LKS_SHELVINGRAK, LKS_NOURUT ASC"
            );

            $soharian = $soharian->getResultArray();   
            
            $cekslp = $dbProd->query(
                "SELECT SLP_PRDCD,
                SLP_DESKRIPSI,
                SLP_KODERAK||'.'||SLP_KODESUBRAK||'.'||SLP_TIPERAK||'.'||SLP_SHELVINGRAK||'.'||SLP_NOURUT AS SLP_LOKASI, 
                SLP_QTYPCS
                FROM TBTR_SLP
                WHERE TRUNC(SLP_CREATE_DT)>=TRUNC(SYSDATE-3)
                AND SLP_FLAG IS NULL AND SLP_PRDCD=$plu0"
            );

            $cekslp = $cekslp->getResultArray();

            $cekspb = $dbProd->query(
                "SELECT SPB_PRDCD,
                SPB_DESKRIPSI,
                SPB_LOKASITUJUAN,
                SPB_MINUS
                FROM TBTEMP_ANTRIANSPB
                WHERE SPB_RECORDID='3'
                AND SPB_PRDCD=$plu0"
            );

            $cekspb = $cekspb->getResultArray();

            $cekklik = $dbProd->query(
                "select tbtr_obi_h.obi_tgltrans as TANGGAL,tbtr_obi_h.obi_attribute2 as ATRIBUT2,
                tbtr_obi_h.obi_notrans as NOTRANS,
                obi_prdcd as PLU,obi_qtyorder as QTYORDER,obi_qtyrealisasi as QTYREALISASI,tbtr_obi_d.obi_pick_dt as TGLPICKING,
                case 
                  when tbtr_obi_h.obi_recid='1' then 'Picking'
                  when tbtr_obi_h.obi_recid='2' then 'Siap Packing'
                  when tbtr_obi_h.obi_recid='3' then 'Siap Draft Struk'
                  when tbtr_obi_h.obi_recid='4' then 'Konfirmasi Pembayaran'
                  when tbtr_obi_h.obi_recid='5' then 'Siap Struk'
                  when tbtr_obi_h.obi_recid='7' then 'Set Ongkir'
                end as STATUS
                from tbtr_obi_d
                left join tbtr_obi_h 
                  on tbtr_obi_h.obi_notrans=tbtr_obi_d.obi_notrans 
                  and tbtr_obi_h.obi_tgltrans=tbtr_obi_d.obi_tgltrans
                where tbtr_obi_h.obi_recid in ('1','2','3')
                and trunc(tbtr_obi_h.obi_tgltrans) >= trunc(sysdate - 31)
                AND OBI_NOPB NOT LIKE '%TMI%'
                and obi_qtyrealisasi>0
                and substr(obi_prdcd,0,6)||'0' = $plu0"
            );

            $cekklik = $cekklik->getResultArray();

            $cektmi = $dbProd->query(
                "select tbtr_obi_h.obi_tgltrans as TANGGAL,tbtr_obi_h.obi_attribute2 as ATRIBUT2,
                tbtr_obi_h.obi_notrans as NOTRANS,
                obi_prdcd as PLU,obi_qtyorder as QTYORDER,obi_qtyrealisasi as QTYREALISASI,tbtr_obi_d.obi_pick_dt as TGLPICKING,
                case 
                  when tbtr_obi_h.obi_recid='1' then 'Picking'
                  when tbtr_obi_h.obi_recid='2' then 'Siap Packing'
                  when tbtr_obi_h.obi_recid='3' then 'Siap Draft Struk'
                  when tbtr_obi_h.obi_recid='4' then 'Konfirmasi Pembayaran'
                  when tbtr_obi_h.obi_recid='5' then 'Siap Struk'
                  when tbtr_obi_h.obi_recid='7' then 'Set Ongkir'
                end as STATUS
                from tbtr_obi_d
                left join tbtr_obi_h 
                  on tbtr_obi_h.obi_notrans=tbtr_obi_d.obi_notrans 
                  and tbtr_obi_h.obi_tgltrans=tbtr_obi_d.obi_tgltrans
                where tbtr_obi_h.obi_recid in ('1','2','3','4','5','7')
                and trunc(tbtr_obi_h.obi_tgltrans) >= trunc(sysdate - 31)
                AND OBI_NOPB LIKE '%TMI%'
                and obi_qtyrealisasi>0
                and substr(obi_prdcd,0,6)||'0' = $plu0"
            );

            $cektmi = $cektmi->getResultArray();

            $cekomi = $dbProd->query(
                "select pbo_pluigr,pbo_kodeomi,pbo_nokoli,pbo_qtyorder,pbo_qtyrealisasi,pbo_create_dt,
                case 
                  when pbo_recordid='3' then 'Sudah Picking'
                  when pbo_recordid='4' then 'Sudah Scanning'
                end as STATUS
                from tbmaster_pbomi 
                left join tbtr_realpb on pbo_pluigr=rpb_plu2 and pbo_kodeomi=rpb_kodeomi and pbo_nopb=rpb_nodokumen and pbo_nokoli=rpb_nokoli
                where pbo_recordid in ('4') and rpb_nokoli is null and trunc(pbo_create_dt)>=trunc(sysdate -7)
                and substr(pbo_pluigr,0,6)||'0' = $plu0"
            );

            $cekomi = $cekomi->getResultArray();
        }
     
        $data = [
            'title' => 'Data SO Per PLU',
            'soharian' => $soharian,
            'plu' => $plu,
            // 'divisi' => $divisi,
            'totalqtyplano' => $totalqtyplano,
            'totalqtylpp' => $totalqtylpp,
            'acost' => $acost,
            'flagjual' => $flagjual,
            'cekslp' => $cekslp,
            'cekspb' => $cekspb,
            'cekklik' => $cekklik,
            'cektmi' => $cektmi,
            'cekomi' => $cekomi,
            'plu0' => $plu0,
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
                    ||lks_tiperak  `  
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

    // Livecks Belum selesai
    public function livecks()
    {
        $dbProd = db_connect('production');
        $plu = sprintf("%07s", $this->request->getVar('plu')) ;
        $btn = $this->request->getVar('btn');
        $koderak = strtoupper($this->request->getVar('koderak'));
        $kodesubrak = strtoupper($this->request->getVar('kodesubrak'));

        $plu1 = substr($plu,0,6)."%";
		$plu0 = substr($plu,0,6)."0";

        // Tampil data plano ALL
        if (empty($koderak) || empty($kodesubrak)) {
            $err = "Masukkan Kode Rak dan Subrak...";
        }else{
            // Filter Kategori
            if ($btn=="all") {
                $filterkategori = "";
            }elseif($btn=="display"){
                $filterkategori = "AND LKS_TIPERAK<>'S' ";
            }elseif($btn=="storage"){
                $filterkategori= "AND LKS_TIPERAK='S'";
            }

            $dataplano = $dbProd->query(
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
                WHERE LKS_KODERAK='$koderak' 
                AND LKS_KODESUBRAK='$kodesubrak' 
                $filterkategori
                ORDER BY KATEGORI,LKS_KODERAK,LKS_KODESUBRAK,LKS_TIPERAK,LKS_SHELVINGRAK,LKS_NOURUT"
            );

            $dataplano = $dataplano->getResultArray();
        }





        $data = [
            'title' => 'Live CKS'
        ];
        return view('logistik/livecks', $data);
    }

    public function kesegaran()
    {
        $dbProd = db_connect('production');
        $isiplu = $this->request->getVar('plu');
        $desk1 = strtoupper($this->request->getVar('desk1'));
        $desk2 = strtoupper($this->request->getVar('desk2'));

        $kesegaran = $cariproduk = [];

        if (isset($isiplu)) {
            if (is_numeric($isiplu)) {
                switch (strlen($isiplu)) {
                    case 1:
                        redirect()->to('/store/cekpromo')->with('Error', 'Data yang anda masukkan tidak valid!');
                    case 2:
                        $pluplusnol = '00000'. $isiplu;
                        break;
                    case 3:
                        $pluplusnol = '0000' . $isiplu;
                        break;
                    case 4:
                        $pluplusnol = '000' . $isiplu;
                        break;
                    case 5:
                        $pluplusnol = '00' . $isiplu;
                        break;
                    case 6:
                        $pluplusnol = '0' . $isiplu;
                        break;
                    case 7 :
                        $pluplusnol = $isiplu;
                        break;
                    case $isiplu > 7: 
                        redirect()->to('/store/cekpromo')->with('Error', 'PLU maksimal 7 Digit!!')->withInput();
                        break;
                    default:
                        redirect()->to('/store/cekpromo')->with('Error', 'Data yang anda masukkan tidak valid!!')->withInput();
                        break;
                }
            }

                $pluCari = substr($pluplusnol, 0, 6);
                $plu0 = substr($pluplusnol, 0, 6).'0';
                $plu1 =  substr($pluplusnol,0,6)."%";

                $kesegaran = $dbProd->query(
                    "select * from (
                        select 
                        row_number() over (partition by slp_prdcd order by slp_id desc) as URUTSLP,
                        prd_prdcd,prd_deskripsipanjang,btr_flag_kesegaran,
                        btr_umur_brg,btr_sat_umur_brg,ubr_max_umur_brg_dci,ubr_max_umur_brg_dci_s,
                        slp_id,slp_create_dt as SLP_TERAKHIR,
                        slp_expdate as EXPDATE_TERAKHIR,
                        case 
                          when ubr_max_umur_brg_dci_s='B' then (ubr_max_umur_brg_dci*30)+sysdate 
                          when ubr_max_umur_brg_dci_s='H' then (ubr_max_umur_brg_dci)+sysdate 
                        end as MINIMAL_DITERIMA,
                        case 
                          when ubr_max_umur_brg_s='B' then (ubr_max_umur_brg*30)+sysdate 
                          when ubr_max_umur_brg_s='H' then (ubr_max_umur_brg)+sysdate 
                        end as MAKSIMAL_DITERIMA
                        from tbmaster_prodmast 
                        left join IGRBGR.tbmaster_batasretur on btr_prdcd=prd_prdcd
                        left join IGRBGR.tbtr_umurbarang on ubr_prdcd=prd_prdcd
                        left join IGRBGR.tbtr_slp on slp_prdcd=prd_prdcd
                        where prd_prdcd like '$plu0'
                        )where urutslp='1' "
                );

                $kesegaran = $kesegaran->getResultArray();
        }   

        if (!empty($desk1) || !empty($desk2)) {
            $cariproduk = $dbProd->query(
                "SELECT prd_prdcd,
                prd_deskripsipanjang,
                prd_unit,
                prd_kodetag,
                prd_hrgjual,
                st_saldoakhir 
                from tbmaster_prodmast 
                left join tbmaster_stock on st_prdcd=prd_prdcd
                where st_lokasi='01' and prd_prdcd like '%0' and prd_deskripsipanjang like '%$desk1%' and prd_deskripsipanjang like '%$desk2%'
                order by prd_prdcd"
            );
            $cariproduk = $cariproduk->getResultArray();
        }

        $data =[
            'title' => 'Cek Kesegaran Produk',
            'kesegaran' => $kesegaran,
            'cariproduk' => $cariproduk,
            'desk1' => $desk1,
            'desk2' => $desk2,
        ];
        return view('logistik/kesegaran', $data);
    }

    // Stok per departemen
    public function stokdep(){

        $dbProd = db_connect('production');
        $tgl = $this->request->getVar('tgl');
        $departement = $this->request->getVar('departement');

        $dep = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
			from tbmaster_departement 
			left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
			order by dep_kodedivisi,dep_kodedepartement"
        );

        $dep = $dep->getResultArray();

        $data = [
            'title' => 'Stok per Departemen',
            'dep' => $dep,
        ];
        return view('logistik/stokdep', $data);
    }

    public function tampilstokdep(){
        $dbProd = db_connect('production');
        $tgl = $this->request->getVar('tgl');
        $departement = $this->request->getVar('departement');
        $tglsekarang = date('d-m-Y');
        $jmlhari = date('d');
        $dsi = 0;
        $supplier = strtoupper($this->request->getVar('supplier'));

        
        // Pilih Departement
        $kodedivisi      = substr($departement,0,1);
        $kodedepartement = substr($departement,1,2);
        
        if ($departement=="all") {
            $filterdep = " and prd_kodedivisi='0' ";
            $judul_filterdep = "Pilih Divisi / Departement !";
        }elseif ($departement="") {
            $filterdep       = " and prd_kodedivisi='$kodedivisi' " ; 
	        $judul_filterdep = "DIV $kodedivisi";
        }else{
            $filterdep       = " and prd_kodedivisi='$kodedivisi' and prd_kodedepartement ='$kodedepartement' " ; 
	        $judul_filterdep = "DIV $kodedivisi DEP $kodedepartement";
        }

        // Filter Data Supplier
        if($supplier=="") {
            $filtersup = " ";
            $judullap = "<h2>Monitoring Stok $tglsekarang <br/> $judul_filterdep</h2>";
        }else{
            $filtersup = " and sup_kodesupplier='$supplier' ";
            $judullap = "<h2>Monitoring Stok $tglsekarang <br/>Supplier : $supplier</h2>";
        }

        //set bulan untuk avgsales
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
            case "11": $bln1="08";$bln2="09";$bln3="10";break;
            case "12": $bln1="09";$bln2="10";$bln3="11";break;
            default : $bln1="10";$bln2="11";$bln3="12";
        }

        $stokdep = $dbProd->query(
            "SELECT sup_kodesupplier as KDSUPPLIER,
            sup_namasupplier as NAMASUPPLIER,
            prd_kodedivisi as DIV,
            prd_kodedepartement as DEPT,
            prd_kodekategoribarang as KATB,
            prd_prdcd as PLU,
            prd_deskripsipanjang as DESKRIPSI,
            prd_frac as FRAC,
            prd_kodetag as TAG,
            prd_flagbkp2 as BKP,
            nvl(st_saldoawal,0) as STOCKAWAL,
            nvl(ST_TRFIN,0) as TRFIN, 
            nvl(ST_TRFOUT,0) as TRFOUT, 
            nvl(ST_SALES,0) as SALES, 
            nvl(ST_RETUR,0) as RETUR, 
            nvl(ST_ADJ,0) as ADJ,
            nvl(ST_INTRANSIT,0) as INTRANSIT,
            nvl(ST_SALDOAKHIR,0) as STOCKAKHIR,
            QTYREALISASI as PICKING_OMI,
            (nvl(sls_qty_$bln1,0) + nvl(sls_qty_$bln2,0) + nvl(sls_qty_$bln3,0))/3 as AVGSALES,
            prd_avgcost as ACOST,
            prd_hrgjual as HRGNORMAL,
            case when prd_flagbkp2='Y' then ((prd_hrgjual - (prd_avgcost*1.1))/prd_hrgjual)*100 else ((prd_hrgjual - prd_avgcost)/prd_hrgjual)*100 end as MRG1,
            prmd_hrgjual as HRGPROMO,
          case when prd_flagbkp2='Y' then ((prmd_hrgjual - (prd_avgcost*1.1))/prd_hrgjual)*100 else ((prmd_hrgjual - prd_avgcost)/prd_hrgjual)*100 end as MRG2,
            lks_display
            
     from tbmaster_prodmast
     left join (select * from tbmaster_stock where st_lokasi='01') on prd_prdcd=st_prdcd
     left join tbtr_salesbulanan on sls_prdcd=prd_prdcd
     left join (select * from tbmaster_hargabeli where hgb_tipe='2') on hgb_prdcd=prd_prdcd
     left join (select prmd_prdcd,prmd_hrgjual from tbtr_promomd where trunc(prmd_tglakhir)>=trunc(sysdate))on prmd_prdcd=prd_prdcd
     LEFT JOIN TBMASTER_SUPPLIER ON SUP_KODESUPPLIER=HGB_KODESUPPLIER
     left join (
     select pbo_pluigr,sum(pbo_qtyorder) as QTYORDER,sum(nvl(pbo_qtyrealisasi,0))as QTYREALISASI        
     from tbmaster_pbomi         
     left join tbmaster_prodmast on pbo_pluigr=prd_prdcd        
     where trunc(pbo_create_dt)=to_date('$tgl','YYYY-MM-DD') group by pbo_pluigr) on substr(st_prdcd,0,6)=substr(pbo_pluigr,0,6)
     left join ( select lks_prdcd,lks_koderak||'.'||lks_kodesubrak||'.'||lks_tiperak||'.'||lks_shelvingrak||'.'||lks_nourut as lks_display
     from tbmaster_lokasi where substr(lks_koderak,0,1) IN ('O','R') and substr(lks_tiperak,0,1) <>'S' ) on lks_prdcd=prd_prdcd
     where (prd_kodecabang='25' or prd_kategoritoko in ('01','02','03')) and prd_prdcd like '%0' and prd_kodetag not in ('N','X') and st_lokasi='01'
     $filterdep
     $filtersup
     order by DIV,DEPT,DESKRIPSI"
        );

        $stokdep = $stokdep->getResultArray();

        $data = [
            'title' => 'STOK DEPARTEMENT',
            'stokdep' => $stokdep,
            'dsi' => $dsi,
            'jmlhari' => $jmlhari,
            'judul' => $judullap,
            'tgl' => $tgl,
            'tglskg' => $tglsekarang,
            'departement' => $departement
        ];
    if($this->request->getVar('btn')=="tampil"){
        return view('logistik/tampilstokdep', $data);
    }elseif($this->request->getVar('btn')=="xls"){
        $tanggalSekarang = $this->tglsekarang;
        $filename = "datapromo $tanggalSekarang.xls";
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        return view('logistik/excelstokdep', $data);
    }
    }

    public function cekmd()
    {
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

    public function pertemanan()
    {
        $dbProd = db_connect('production');
        $departement = $this->request->getVar('departement');
        $status = $this->request->getVar('status');
        $pluinput = $this->request->getVar('plu');
        $btn = $this->request->getVar('btn');

        // Bagi PLU ke PLU 0 dan PLU 1
        if(isset($pluinput)){
            $plu = sprintf("%07s",$pluinput);
            $plu1 = substr($plu,0,6)."1";
            $plu0 = substr($plu,0,6)."0";
        }else{
            $plu = "";
            $plu1 = "";
            $plu0 = "";
        }

        $judul = ""; 
        $pertemanan = [];

        // Pilih Status
        if($status=="igr"){
            $judul = "IGR ONLY";
            $filterstatus = "AND STATUS='IGR-ONLY'";
        }elseif($status=="omi"){
            $judul = "IGR OMI";
            $filterstatus = "AND STATUS='OMI-ONLY'";
        }elseif($status=="igromi"){
            $judul = "IGR-OMI";
            $filterstatus = "AND STATUS='IGR-OMI'";
        }else{
            $judul = "ALL";
            $filterstatus = " ";
        }

        // Pilih Departement
        if(strlen($departement) == 1){
            $filterdep = "AND DIV = '$departement'";
        }elseif(strlen($departement)==2){
            $filterdep = "AND DEP = '$departement'";
        }elseif(strlen($departement)>2){
            $filterdep = " ";
        }

        // Apakah PLU Diinput?
        if($plu !='0000000'){
            $filterplu = "AND PLU like '$plu'";
        }else{
            $filterplu = " ";
        }

        if ($btn=="tampil") {
            $pertemanan = $dbProd->query(
                "SELECT DIV,DEP,KAT,PLU,DESK,PERTEMANAN,PALET,STATUS 
                FROM(SELECT PRD_KODEDIVISI DIV,
                PRD_KODEDEPARTEMENT DEP,
                PRD_KODEKATEGORIBARANG KAT,
                PRD_PRDCD PLU,
                PRD_DESKRIPSIPANJANG DESK,
                PLA_KODERAK PERTEMANAN,
                MPT_MAXQTY PALET,
                STATUS_IGR_IDM STATUS
                FROM TBMASTER_PRODMAST
                LEFT JOIN (SELECT DISTINCT PLA_PRDCD, PLA_KODERAK FROM TBMASTER_PLANO) ON PLA_PRDCD= PRD_PRDCD
                LEFT JOIN TBMASTER_MAXPALET ON MPT_PRDCD = PRD_PRDCD
                LEFT JOIN (
                SELECT 
                PRD_PRDCD PLU,
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
                
                FROM TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0' AND PRD_DESKRIPSIPANJANG IS NOT NULL))) ON PLU = PRD_PRDCD
                WHERE PRD_PRDCD LIKE '%0'
                AND PRD_KODETAG NOT IN ('N','O','X')
                AND PRD_KODEDIVISI NOT IN ('4','6'))
                WHERE PERTEMANAN IS NOT NULL
                $filterstatus
                $filterdep
                $filterplu
                ORDER BY PLU ASC,PERTEMANAN ASC
                "
            );
            $pertemanan = $pertemanan->getResultArray();
        }elseif($btn=="reset"){
            redirect()->to('pertemanan');
        }



        $daftarDepartement = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
          );
    
          $daftarDepartement = $daftarDepartement->getResultArray();
        $data = [
            'title' => 'Monitoring Pertemanan',
            'departement' => $daftarDepartement,
            'judul' => $judul,
            'dep' => $departement,
            'status' => $status,
            'plu' => $plu,
            'btn' => $btn,
            'pertemanan' => $pertemanan,
        ];

        redirect()->to('pertemanan')->withInput();
        return view('logistik/pertemanan', $data);
    }

}