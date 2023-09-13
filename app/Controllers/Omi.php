<?php

namespace App\Controllers;

class Omi extends BaseController
{
    public function index()
    {
        
    }

    public function monitoringpbomi()
    {
        $data = [
            'title' => 'Monitoring PB OMI'
        ];
        return view('omi/monitoringpbomi', $data);
    }

    public function tampilmonitoringomi()
    {   
        $dbProd = db_connect('production');
        $kodeomi = strtoupper($this->request->getVar('kodeomi'));
        $tglawal = $this->request->getVar('tglawal');
        $tglakhir = $this->request->getVar('tglakhir');
        $jenis = $this->request->getVar('jenis');
        $tombol = $this->request->getVar('btn');
        $tgljam = date('d-m-Y H:i:s');

        $rekaporder = $hitungtotal = $listorder = $tolakanpb = $pickingitem = $masteritem = $masterpb = [];

        // filter kode omi
        if($kodeomi=="") {
            $filteromi = "";
            $tlko_filteromi = "";
            $jalur_filteromi = "";
        }else{
            $filteromi = "and pbo_kodeomi='$kodeomi'";
            $tlko_filteromi = "and tlko_kodeomi='$kodeomi'";
            $jalur_filteromi = "and hjp_kodetokoomi='$kodeomi'";
        }

        // filter Jenis Laporan
        if($jenis=="rekap") {
            $judullap =  "<h3>REKAP ORDER Tanggal : $tglawal s/d $tglakhir // OMI : $kodeomi</h3>";
            $rekaporder = $dbProd->query(
                "SELECT pbo_kodeomi as KODEOMI,
                tko_namaomi as NAMAOMI,
                pbo_nopb as NOPB,
                count(distinct pbo_pluigr) as JMLITEM,
                count(distinct rpb_plu2) as JMLITEM_REAL,
                sum(pbo_qtyorder) as QTY_ORDER,
                sum(rph_order) as RPH_ORDER,
                sum(qty_picking) as QTY_PICKING,
                sum(rph_picking) as RPH_PICKING,
                sum(qty_dsp) as QTY_REALISASI,
                sum(rph_dsp) as RPH_REALISASI
          from (
                select pbo_kodeomi,tko_namaomi,pbo_nopb,pbo_pluigr,rpb_plu2,pbo_hrgsatuan,
                      pbo_qtyorder,(pbo_nilaiorder + pbo_ppnorder)*((tko_persendistributionfee/100)+1) as RPH_ORDER,
                      pbo_qtyrealisasi as qty_picking, (pbo_ttlnilai + pbo_ttlppn)*((tko_persendistributionfee/100)+1) as RPH_PICKING,
                      pbo_create_dt,
                      qty_dsp,rph_dsp,rpb_create_dt
                from tbmaster_pbomi 
                left join tbmaster_tokoigr on tko_kodeomi=pbo_kodeomi
                left join (
                    select rpb_kodeomi,rpb_nodokumen,rpb_plu2,rpb_nokoli, sum(rpb_qtyrealisasi) as qty_dsp,
                      sum(rpb_ttlnilai) + sum(rpb_ttlppn) +(sum(rpb_distributionfee)*1.1) as rph_dsp,
                      rpb_create_dt
                    from tbtr_realpb group by rpb_kodeomi, rpb_nodokumen, rpb_plu2, rpb_nokoli, rpb_create_dt
                )on pbo_pluigr=rpb_plu2 and pbo_kodeomi=rpb_kodeomi and pbo_nopb=rpb_nodokumen and pbo_nokoli=rpb_nokoli
                where substr(pbo_nopb,0,1) in ('6','S','K') and trunc(pbo_create_dt) between to_date('$tglawal', 'yyyy-mm-dd')  and to_date('$tglakhir', 'yyyy-mm-dd') $filteromi
          )
          left join tbmaster_prodmast on substr(pbo_pluigr,0,6)||'0'=prd_prdcd
          left join tbmaster_prodcrm on substr(pbo_pluigr,0,6)||'0'=prc_pluigr
          group by pbo_kodeomi, tko_namaomi, pbo_nopb
          order by pbo_kodeomi,pbo_nopb"
            );

            $hitungtotal = $dbProd->query(
                "SELECT count(distinct pbo_pluigr) as JMLITEM,
                count(distinct rpb_plu2) as JMLITEM_REAL,
                sum(pbo_qtyorder) as QTY_ORDER,
                sum(rph_order) as RPH_ORDER,
                sum(qty_picking) as QTY_PICKING,
                sum(rph_picking) as RPH_PICKING,
                sum(qty_dsp) as QTY_REALISASI,
                sum(rph_dsp) as RPH_REALISASI
          from (
                select pbo_kodeomi,tko_namaomi,pbo_nopb,pbo_pluigr,rpb_plu2,pbo_hrgsatuan,
                      pbo_qtyorder,(pbo_nilaiorder + pbo_ppnorder)*((tko_persendistributionfee/100)+1) as RPH_ORDER,
                      pbo_qtyrealisasi as qty_picking, (pbo_ttlnilai + pbo_ttlppn)*((tko_persendistributionfee/100)+1) as RPH_PICKING,
                      pbo_create_dt,
                      qty_dsp,rph_dsp,rpb_create_dt
                from tbmaster_pbomi 
                left join tbmaster_tokoigr on tko_kodeomi=pbo_kodeomi
                left join (
                    select rpb_kodeomi,rpb_nodokumen,rpb_plu2,rpb_nokoli, sum(rpb_qtyrealisasi) as qty_dsp,
                      sum(rpb_ttlnilai) + sum(rpb_ttlppn) +(sum(rpb_distributionfee)*1.1) as rph_dsp,
                      rpb_create_dt
                    from tbtr_realpb group by rpb_kodeomi, rpb_nodokumen, rpb_plu2, rpb_nokoli, rpb_create_dt
                )on pbo_pluigr=rpb_plu2 and pbo_kodeomi=rpb_kodeomi and pbo_nopb=rpb_nodokumen and pbo_nokoli=rpb_nokoli
                where pbo_nopb like '6%' and trunc(pbo_create_dt) between to_date('$tglawal', 'yyyy-mm-dd') and to_date('$tglakhir', 'yyyy-mm-dd') $filteromi
          )
          left join tbmaster_prodmast on substr(pbo_pluigr,0,6)||'0'=prd_prdcd
          left join tbmaster_prodcrm on substr(pbo_pluigr,0,6)||'0'=prc_pluigr"
            );

            $hitungtotal = $hitungtotal->getResultArray();
            $rekaporder = $rekaporder->getResultArray();
        }elseif($jenis=="list"){
            
            $judullap =  "<h3>LIST ORDER tanggal : $tglawal s/d $tglakhir // OMI : $kodeomi</h3>";
            $listorder = $dbProd->query(
                "SELECT 
                TGL, 
                PRD_KODEDIVISI AS DIV,
                PRD_KODEDEPARTEMENT AS DEPT,
                PRD_KODEKATEGORIBARANG AS KATB,
                substr(pbo_pluigr,0,6)||'0' as PLUIGR,
                prc_pluomi as PLUOMI,
                prd_deskripsipanjang as DESKRIPSI,prd_unit as UNIT,prd_frac as FRAC,
                prd_kodetag as TAGIGR,prc_kodetag as TAGOMI,
                pbo_hrgsatuan as HRGSATUAN,
                sum(pbo_qtyorder) as QTY_ORDER,
                sum(rph_order) as RPH_ORDER,
                count(distinct substr(pbo_create_dt,0,9)) as HARIPB,
                sum(qty_picking) as QTY_PICKING,
                sum(rph_picking) as RPH_PICKING,
                sum(qty_dsp) as QTY_REALISASI,
                sum(rph_dsp) as RPH_REALISASI,
                count(distinct substr(rpb_create_dt,0,9)) as HARISALES,
               (sum(pbo_qtyorder) - sum(qty_dsp)) as QTY_SELISIH,
               (sum(rph_order) - sum(rph_dsp)) as RPH_SELISIH
               
          from (
                select TRUNC(PBO_CREATE_DT) TGL,pbo_kodeomi,pbo_nopb,pbo_pluigr,pbo_hrgsatuan,
                      pbo_qtyorder,(pbo_nilaiorder + pbo_ppnorder)*((tko_persendistributionfee/100)+1) as RPH_ORDER,
                      pbo_qtyrealisasi as qty_picking, (pbo_ttlnilai + pbo_ttlppn)*((tko_persendistributionfee/100)+1) as RPH_PICKING,
                      pbo_create_dt,
                      nvl(qty_dsp,0) as qty_dsp,nvl(rph_dsp,0) as rph_dsp,rpb_create_dt
                from tbmaster_pbomi 
                left join tbmaster_tokoigr on tko_kodeomi=pbo_kodeomi
                left join (
                    select rpb_kodeomi,rpb_nodokumen,rpb_plu2,rpb_nokoli, sum(rpb_qtyrealisasi) as qty_dsp,
                      sum(rpb_ttlnilai) + sum(rpb_ttlppn) +(sum(rpb_distributionfee*1.1)) as rph_dsp,
                      rpb_create_dt
                    from tbtr_realpb group by rpb_kodeomi, rpb_nodokumen, rpb_plu2, rpb_nokoli, rpb_create_dt
                )on pbo_pluigr=rpb_plu2 and pbo_kodeomi=rpb_kodeomi and pbo_nopb=rpb_nodokumen and pbo_nokoli=rpb_nokoli
                where substr(pbo_nopb,0,1) in ('6','S','K') and trunc(pbo_create_dt) between to_date('$tglawal', 'yyyy-mm-dd') and to_date('$tglakhir', 'yyyy-mm-dd') $filteromi
          )
          left join tbmaster_prodmast on substr(pbo_pluigr,0,6)||'0'=prd_prdcd
          left join tbmaster_prodcrm on substr(pbo_pluigr,0,6)||'0'=prc_pluigr
          group by TGL,PRD_KODEDIVISI,PRD_KODEDEPARTEMENT,PRD_KODEKATEGORIBARANG, substr(pbo_pluigr,0,6)||'0', prc_pluomi, prd_deskripsipanjang,
           prd_unit, prd_frac, prd_kodetag, prc_kodetag, pbo_hrgsatuan
          order by prd_deskripsipanjang"
            );
            $listorder = $listorder->getResultArray();
        }elseif($jenis=="tolakan"){
            $judullap =  "<h3>TOLAKAN PB OMI tanggal : $tglawal s/d $tglakhir // OMI : $kodeomi</h3>";
            $tolakanpb = $dbProd->query(
                "SELECT 
                TO_CHAR (TLKO_CREATE_DT, 'DD-MM-YY') AS TANGGAL, 
                CASE WHEN TLKO_KETTOLAKAN IN ('NOID PIECES DOUBLE','PLU TIDAK TERDAFTAR DI MASTER_LOKASI')  
                THEN 'LJM' ELSE 'MD HO/CAB' END PIC, 
                PRD_KODEDIVISI AS DIV,
                PRD_KODEDEPARTEMENT AS DEPT,
                PRD_KODEKATEGORIBARANG AS KATB,
                TLKO_PLUIGR AS PLUIGR,
                TLKO_PLUOMI AS PLUOMI,
                PRD_DESKRIPSIPANJANG AS DESKRIPSI,
                PRD_FRAC AS FRAC,
                PRD_UNIT AS UNIT,
                TAG AS TAGIGR,
                PRC_KODETAG AS TAGOMI,
                COUNT(TLKO_KODEOMI) AS JMLHTOKO_PB,
                NVL(JMLHTOKO_REAL,0) AS JMLHTOKO_REAL,
                HGB_HRGBELI AS HRGOMI,
                SUM(TLKO_QTYORDER) AS QTY_ORDER,
                NVL(QTYR,0) AS QTY_REAL,
                (SUM(TLKO_QTYORDER))-(NVL(QTYR,0)) AS QTY_SLSH,
                SUM(TLKO_QTYORDER)*HGB_HRGBELI AS RPH_ORDER,
                NVL(QTYR,0)*HGB_HRGBELI AS RPH_REAL,
                (SUM(TLKO_QTYORDER)*HGB_HRGBELI)-(NVL(QTYR,0)*HGB_HRGBELI) AS RPH_SLSH,
                RAK,
                SR,
                TR,
                SH,
                NU,
                ST_SALDOAKHIR AS STOK,
                TLKO_KETTOLAKAN AS KETERANGAN 
                FROM TBTR_TOLAKANPBOMI
                LEFT JOIN 
                (SELECT PRD_KODEDIVISI, PRD_KODEDEPARTEMENT, PRD_KODEKATEGORIBARANG,
                CONCAT(SUBSTR(PRD_PRDCD,0,6),0) AS PLU, PRD_DESKRIPSIPANJANG, PRD_FRAC, PRD_UNIT, PRD_KODETAG AS TAG
                FROM TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%1')
                ON TLKO_PLUIGR=PLU
                LEFT JOIN
                TBMASTER_PRODCRM ON TLKO_PLUIGR=PRC_PLUIGR
                LEFT JOIN
                (SELECT ST_PRDCD, ST_SALDOAKHIR FROM TBMASTER_STOCK WHERE ST_LOKASI='01') ON TLKO_PLUIGR=ST_PRDCD
                LEFT JOIN
                (SELECT LKS_PRDCD, LKS_KODERAK AS RAK, LKS_KODESUBRAK AS SR, LKS_TIPERAK AS TR, LKS_SHELVINGRAK AS SH, LKS_NOURUT AS NU 
                FROM TBMASTER_LOKASI WHERE LKS_KODERAK LIKE 'D%') ON TLKO_PLUIGR=LKS_PRDCD
        
                LEFT JOIN
                (SELECT PBO_PLUOMI, PBO_HRGSATUAN, SUM(PBO_QTYORDER) AS QTYO , SUM(PBO_QTYREALISASI) AS QTYR, COUNT(PBO_KODEOMI) AS JMLHTOKO_REAL,
                (SUM(PBO_QTYREALISASI)*PBO_HRGSATUAN) AS RPHR  FROM 
                TBMASTER_PBOMI WHERE TRUNC(PBO_CREATE_DT) BETWEEN to_date('$tglawal', 'yyyy-mm-dd') AND to_date('$tglakhir', 'yyyy-mm-dd') $filteromi
                GROUP BY PBO_PLUOMI, PBO_HRGSATUAN) ON TLKO_PLUIGR=PBO_PLUOMI
        
                LEFT JOIN
                (SELECT * FROM TBMASTER_HARGABELI ) ON TLKO_PLUIGR=HGB_PRDCD
                 WHERE TRUNC(TLKO_CREATE_DT) BETWEEN to_date('$tglawal', 'yyyy-mm-dd') AND to_date('$tglakhir', 'yyyy-mm-dd') $tlko_filteromi
                 GROUP BY TO_CHAR (TLKO_CREATE_DT, 'DD-MM-YY'), CASE WHEN TLKO_KETTOLAKAN IN ('NOID PIECES DOUBLE','PLU TIDAK TERDAFTAR DI MASTER_LOKASI')  
                 THEN 'LJM' ELSE 'MD HO/CAB' END, PRD_KODEDIVISI, PRD_KODEDEPARTEMENT, PRD_KODEKATEGORIBARANG, TLKO_PLUIGR, TLKO_PLUOMI, PRD_DESKRIPSIPANJANG, PRD_FRAC, PRD_UNIT, TAG, PRC_KODETAG, NVL(JMLHTOKO_REAL,0), HGB_HRGBELI, NVL(QTYR,0), NVL(QTYR,0)*HGB_HRGBELI, RAK, SR, TR, SH, NU, ST_SALDOAKHIR, TLKO_KETTOLAKAN
                ORDER BY DESKRIPSI, PIC ASC"
            );

            $tolakanpb = $tolakanpb->getResultArray();
            
        }elseif($jenis=="picking"){
            // JALUR KERTAS
            $judullap =  "<h3>ITEM PICKING NON DPD TANGGAL $tglawal</h3>";

            $pickingitem = $dbProd->query(
                "SELECT 
                pbo_kodeomi as KODEOMI,
                pbo_nopb as NOPB,
                pbo_recordid  as RECID,
                prd_prdcd as PLUIGR,
                prd_deskripsipanjang as DESKRIPSI,
                prd_unit as UNIT,
                prd_frac as FRAC,
                hjp_koderak as JALURPICKING,
                pbo_qtyorder as QTYORDER,
                pbo_qtyrealisasi as QTYREAL
                from tbmaster_pbomi 
                left join tbmaster_prodmast on substr(pbo_pluigr,0,6)||'0'=prd_prdcd
                left join (select * from tbmaster_historyjalurpicking 
                  where trunc(hjp_tglpicking)=to_date('$tglawal', 'YYYY-MM-DD')       --> GANTI PERIODE
                  $jalur_filteromi)on pbo_pluigr=hjp_prdcd 
                  and pbo_kodeomi=hjp_kodetokoomi 
                  and pbo_nopb=hjp_nodokumenomi
                where trunc(pbo_create_dt)=to_date('$tglawal', 'YYYY-MM-DD')          --> GANTI PERIODE
                $filteromi
                and hjp_prdcd is null
                and mod(pbo_qtyorder,prd_frac)=0
                and pbo_nopb like '6%'
                order by pbo_kodeomi,deskripsi"
            );

            $pickingitem = $pickingitem->getResultArray();
           
        }elseif($jenis=="masteritem"){
            $judullap =  "<h3>MASTER ITEM OMI </h3>";

            //set bulan untuk avgsales
            $bln = date("m");
            $bln_1 = date("m",strtotime("-3 month"));
            $bln_2 = date("m",strtotime("-2 month"));
            $bln_3 = date("m",strtotime("-1 month"));
            $bln1= sprintf("%02s",$bln_1);
            $bln2= sprintf("%02s",$bln_2);
            $bln3= sprintf("%02s",$bln_3);

            $masteritem = $dbProd->query(
                "SELECT 
                PRD_KODEDIVISI AS DIV,
                PRD_KODEDEPARTEMENT AS DEPT,
                PRD_KODEKATEGORIBARANG AS KATB,
                PRC_PLUIGR AS PLU_IGR,
                PRC_PLUOMI AS PLU_OMI,
                PRD_DESKRIPSIPANJANG AS DESKRIPSI,
                PRD_FRAC AS FRAC,
                PRD_UNIT AS UNIT,
                PRD_KODETAG AS TAG_IGR,
                PRC_KODETAG AS TAG_OMI,
                LKS_KODERAK AS RAK,
                LKS_KODESUBRAK AS SR,
                LKS_TIPERAK AS TR,
                LKS_SHELVINGRAK AS SH,
                LKS_NOURUT AS NU,
                ST_SALDOAKHIR AS STOK,
                AVG_SLSIGR + AVG_SLSMM as AVG_SLSIGR,
                AVG_SLS_PB_OMI AS AVG_PBOMI,
                AVG_6X_PBOMI,
                ROUND((ST_SALDOAKHIR/AVG_6X_PBOMI),2) AS STOCK_VS_AVG_6X_PBOMI,
                PRC_MINORDER AS MINOR_OMI,
                PKM_MINORDER AS MINOR_IGR,
                SL,
                AVG_SL_3BLN,
                PKM_PKM AS PKM,
                PKMP_QTYMINOR AS MPLUS,
                PKM_PKMT AS PKMT,
                LKS_MAXPLANO AS MAXPLANO_DPD,
                LKS_QTY AS QTY_PLANODPD,
                MAXPLANO_TOKO,
                QTY_PLANOTOKO,
                CASE WHEN QTYPO_KD>0 AND QTY_PO IS NULL THEN '*' ELSE '' END AS PO_KADALUARSA,
                QTY_PO,
                BPB_QTY AS QTY_BPB,
                SL_SUPP_SAATINI,
                HGB_KODESUPPLIER KODE_SUPP,
                SUP_NAMASUPPLIER NAMA_SUPP,
                PO_VIA
                
          FROM 
          TBMASTER_PRODCRM
          
          LEFT JOIN
          TBMASTER_PRODMAST ON PRC_PLUIGR=PRD_PRDCD
          LEFT JOIN
          (SELECT * FROM TBMASTER_LOKASI WHERE SUBSTR(LKS_KODERAK,0,1) IN ('D') and SUBSTR(LKS_TIPERAK,0,1) <>'S') on PRC_PLUIGR=LKS_PRDCD
          LEFT JOIN 
          (SELECT LKS_PRDCD AS LKS_PLUTK, SUM(LKS_MAXPLANO) AS MAXPLANO_TOKO, SUM(LKS_QTY) AS QTY_PLANOTOKO FROM TBMASTER_LOKASI
          WHERE SUBSTR(LKS_KODERAK,0,1) NOT IN ('D','G') AND SUBSTR(LKS_TIPERAK,0,1) <>'S' GROUP BY LKS_PRDCD) 
          ON PRC_PLUIGR=LKS_PLUTK
          LEFT JOIN
          (SELECT * FROM TBMASTER_STOCK WHERE ST_LOKASI='01') ON PRC_PLUIGR=ST_PRDCD
          LEFT JOIN
          (SELECT RSL_PRDCD as RSL_PRDCD_01, (RSL_QTY_$bln1+RSL_QTY_$bln2+RSL_QTY_$bln3)/3 AS AVG_SLSIGR FROM TBTR_REKAPSALESBULANAN WHERE RSL_GROUP='01')
          ON PRC_PLUIGR=RSL_PRDCD_01
          LEFT JOIN
          (SELECT RSL_PRDCD as RSL_PRDCD_03, (RSL_QTY_$bln1+RSL_QTY_$bln2+RSL_QTY_$bln3)/3 AS AVG_SLSMM FROM TBTR_REKAPSALESBULANAN WHERE RSL_GROUP='03')
          ON PRC_PLUIGR=RSL_PRDCD_03
          LEFT JOIN 
          TBMASTER_KKPKM ON PRC_PLUIGR=PKM_PRDCD
          LEFT JOIN
          TBMASTER_PKMPLUS ON PRC_PLUIGR=PKMP_PRDCD
          
          -- AVG PB OMI BERDASARKAN JUMLAH HARI PB
          LEFT JOIN
          (SELECT PLU_AVG66X,QTY_PB,JML_HARI,ROUND(((QTY_PB/JML_HARI)),0) AVG_SLS_PB_OMI,ROUND((((QTY_PB/JML_HARI))*6),0) AVG_6X_PBOMI
            FROM (SELECT SUBSTR(PBO_PLUIGR,1,6)||'0' PLU_AVG66X, SUM(PBO_QTYORDER) QTY_PB,
                  COUNT(DISTINCT((TRUNC(PBO_CREATE_DT)))) AS JML_HARI  FROM TBMASTER_PBOMI
                  WHERE TRUNC(PBO_CREATE_DT) BETWEEN TRUNC(SYSDATE-90,'MON') AND LAST_DAY(SYSDATE-30) 
                  GROUP BY SUBSTR(PBO_PLUIGR,1,6)||'0')) ON PRC_PLUIGR=PLU_AVG66X
          
          ----------------SL_BULAN_KEMARIN
          LEFT JOIN
          (SELECT PRD_PRDCD AS PLUSL,ROUND(((BPB_QTY/PO_QTY)*100),2) AS SL FROM TBMASTER_PRODMAST
           LEFT JOIN (SELECT TPOD_PRDCD, SUM(TPOD_QTYPO) PO_QTY, SUM(MSTD_QTY) BPB_QTY
                      FROM (SELECT  * FROM TBTR_PO_H LEFT JOIN TBTR_PO_D ON TPOH_NOPO=TPOD_NOPO WHERE NVL(TPOH_RECORDID,'2')<>'1'
                      AND TRUNC(TPOH_TGLPO) BETWEEN TRUNC(SYSDATE-30,'MON') AND LAST_DAY(SYSDATE-30))
                      LEFT JOIN(SELECT * FROM TBTR_MSTRAN_H LEFT JOIN TBTR_MSTRAN_D ON MSTH_NODOC=MSTD_NODOC WHERE MSTH_RECORDID IS NULL)
                      ON TPOH_NOPO=MSTH_NOPO AND TPOD_PRDCD=MSTD_PRDCD GROUP BY TPOD_PRDCD
                      ) ON PRD_PRDCD=TPOD_PRDCD) ON PRC_PLUIGR=PLUSL     
          
          ----------------SL_3_BULAN_KEMARIN
          LEFT JOIN
          (SELECT PRD_PRDCD AS PLUSL3BLN,ROUND(((BPB_QTY/PO_QTY)*100),2) AS AVG_SL_3BLN FROM TBMASTER_PRODMAST 
           LEFT JOIN (SELECT TPOD_PRDCD, SUM(TPOD_QTYPO) PO_QTY, SUM(MSTD_QTY) BPB_QTY
                      FROM (SELECT * FROM TBTR_PO_H LEFT JOIN TBTR_PO_D ON TPOH_NOPO=TPOD_NOPO WHERE NVL(TPOH_RECORDID,'2')<>'1'
                      AND TRUNC(TPOH_TGLPO) BETWEEN TRUNC(SYSDATE-90,'MON') AND LAST_DAY(SYSDATE-30))
                      LEFT JOIN(SELECT * FROM TBTR_MSTRAN_H LEFT JOIN TBTR_MSTRAN_D ON MSTH_NODOC=MSTD_NODOC WHERE MSTH_RECORDID IS NULL)
                      ON TPOH_NOPO=MSTH_NOPO AND TPOD_PRDCD=MSTD_PRDCD GROUP BY TPOD_PRDCD) ON PRD_PRDCD=TPOD_PRDCD)
                      ON PRC_PLUIGR=PLUSL3BLN           
          
          ----------------SL_BULAN_BERJALAN
          LEFT JOIN
          (SELECT PRD_PRDCD AS PLUSLBLN,BPB_QTY,ROUND(((BPB_QTY/PO_QTY)*100),2) AS SL_SUPP_SAATINI FROM TBMASTER_PRODMAST
           LEFT JOIN (SELECT TPOD_PRDCD, SUM(TPOD_QTYPO) PO_QTY, SUM(MSTD_QTY) BPB_QTY
                      FROM ( SELECT  * FROM TBTR_PO_H LEFT JOIN TBTR_PO_D ON TPOH_NOPO=TPOD_NOPO WHERE NVL(TPOH_RECORDID,'2')<>'1'
                      AND TRUNC(TPOH_TGLPO) >=TRUNC(SYSDATE-1,'MON'))
                      LEFT JOIN (SELECT * FROM TBTR_MSTRAN_H LEFT JOIN TBTR_MSTRAN_D ON MSTH_NODOC=MSTD_NODOC WHERE MSTH_RECORDID IS NULL)
                      ON TPOH_NOPO=MSTH_NOPO AND TPOD_PRDCD=MSTD_PRDCD GROUP BY TPOD_PRDCD
                      ) ON PRD_PRDCD=TPOD_PRDCD) ON PRC_PLUIGR=PLUSLBLN 
          
          --PO YANG MASIH AKTIF
          LEFT JOIN
          (SELECT TPOD_PRDCD,SUM(TPOD_QTYPO) AS QTY_PO FROM TBTR_PO_H LEFT JOIN TBTR_PO_D ON TPOH_NOPO=TPOD_NOPO 
                    WHERE TPOH_RECORDID IS NULL AND TRUNC(TPOH_TGLPO+TPOH_JWPB)>=TRUNC(SYSDATE) GROUP BY TPOD_PRDCD
                    )ON PRC_PLUIGR=TPOD_PRDCD
          
          --PO KADALUARSA (HARUS DIBANDINGKAN DENGAN PO YANG MASIH AKTIF)
          LEFT JOIN 
          (SELECT * FROM (SELECT TPOH_RECORDID  RECID_KD,TPOH_TGLPO TGLPO_KD,TPOH_JWPB,TPOH_NOPO NOPO_KD,TPOD_PRDCD PLU_KD,TPOD_QTYPO QTYPO_KD,
                           DENSE_RANK() OVER (PARTITION BY TPOD_PRDCD ORDER BY TPOH_TGLPO DESC,TPOH_NOPO DESC) RANK_KD
                           FROM TBTR_PO_H LEFT JOIN TBTR_PO_D ON TPOH_NOPO=TPOD_NOPO WHERE TRUNC(TPOH_TGLPO+TPOH_JWPB)<TRUNC(SYSDATE))WHERE RANK_KD='1' AND RECID_KD IS NULL
                          )ON PRC_PLUIGR=PLU_KD
          
          LEFT JOIN 
          (SELECT HGB_PRDCD,HGB_NILAIDPP,HGB_KODESUPPLIER,SUP_NAMASUPPLIER FROM TBMASTER_HARGABELI LEFT JOIN TBMASTER_SUPPLIER
          ON HGB_KODESUPPLIER=SUP_KODESUPPLIER WHERE HGB_TIPE='2') ON PRC_PLUIGR=HGB_PRDCD
          
          LEFT JOIN
          (SELECT PLUPO, SUM(TPOD_QTYPO) AS PO_OUT, PO_VIA FROM
          (SELECT PLUPO,TPOD_QTYPO,PBH_KETERANGANPB AS PO_VIA
              FROM (
                  SELECT TPOD_PRDCD AS PLUPO,TPOH_KODESUPPLIER AS KODESUP, MAX(TPOH_TGLPO) AS TGLPO
                  FROM TBTR_PO_D LEFT JOIN TBTR_PO_H ON TPOD_NOPO=TPOH_NOPO  
                  WHERE TRUNC(TPOH_TGLPO)+TPOH_JWPB>=TO_DATE(SYSDATE) AND TPOD_RECORDID IS NULL 
                  GROUP BY TPOD_PRDCD, TPOH_KODESUPPLIER)
              LEFT JOIN TBTR_PO_D ON PLUPO=TPOD_PRDCD AND TGLPO=TPOD_TGLPO
              LEFT JOIN TBTR_PB_D ON TPOD_PRDCD=PBD_PRDCD AND TPOD_NOPO=PBD_NOPO
              LEFT JOIN TBTR_PB_H ON PBH_NOPB=PBD_NOPB
              LEFT JOIN TBTR_PO_H ON TPOH_NOPO=TPOD_NOPO)
              GROUP BY PLUPO, PO_VIA)
          ON PRC_PLUIGR=PLUPO
          
          WHERE 
          PRD_DESKRIPSIPANJANG IS NOT NULL AND PRD_KODETAG NOT IN ('N','X')
          
          ORDER BY DESKRIPSI ASC"
            );

            $masteritem = $masteritem->getResultArray();
            
        }elseif($jenis=="masterpb"){
            $judullap =  "<h3>LIST ORDER : $kodeomi // tanggal : $tglawal</h3>";
             //set bulan untuk avgsales
             $bln = date("m");
             $bln_1 = date("m",strtotime("-3 month"));
             $bln_2 = date("m",strtotime("-2 month"));
             $bln_3 = date("m",strtotime("-1 month"));
             $bln1= sprintf("%02s",$bln_1);
             $bln2= sprintf("%02s",$bln_2);
             $bln3= sprintf("%02s",$bln_3);
             $masterpb = $dbProd->query(
                 "SELECT 
                 TGL,              DIV,              DEPT,             KATB,             PLU_OMI,
                 PLU_IGR,          DESKRIPSI,        FRAC,             UNIT,
                 TAG_IGR,          TAG_OMI,  
                 
                 JML_TOKO_YANG_PB, JML_TOKO_YANG_PB_REALISASI,         QTY_ORDER,        QTY_REALISASI,    
                 QTY_SELISIH,      RPH_ORDER,        RPH_REALISASI,    RPH_SELISIH,      
                 
                 RAK,              SR,               TR,               SH,               NU,
                 
                 STOKLPP,          AVGSLS_IGR,       AVG_SLS_PB_OMI AVG_PB_OMI,
                 MINOR_OMI,        MINOR_IGR,        SL,               AVG_SL_3BLN,      
                 PKM,              MPLUS,            PKMT,             
                 MAXPLANO_DPD,     QTYPLANO_DPD,     MAXPLANO_TOKO,    QTYPLANO_TOKO,    
                 --QTY_PO_KD,
                 QTY_PO,           PO_KADALUARSA,    BPB_QTY QTY_BPB,    SL_SUPP_SAATINI , 
                 KODE_SUPP,        NAMA_SUPP,
                 AVG_6X_PBOMI,     STOCK_VS_AVG_6X_PBOMI
                 
                 FROM
                 --DATA SL OMI
                 (
                 SELECT 
                 TRUNC(PBO_CREATE_DT) TGL,SUBSTR(PBO_PLUIGR,1,6)||'0' PLU, 
                 COUNT (DISTINCT PBO_KODEMEMBER) JML_TOKO_YANG_PB,NVL(TOKO_YANG_PB_REALISASI,0) JML_TOKO_YANG_PB_REALISASI,
                 SUM(PBO_QTYORDER) QTY_ORDER,NVL(QTY_REAL,0) QTY_REALISASI,SUM(PBO_NILAIORDER) RPH_ORDER,NVL(RPH_REAL,0) RPH_REALISASI,
                 SUM(PBO_QTYORDER)-NVL(QTY_REAL,0) QTY_SELISIH,SUM(PBO_NILAIORDER)-NVL(RPH_REAL,0) RPH_SELISIH 
                 
                 FROM TBMASTER_PBOMI
                 LEFT JOIN (SELECT PBO_PLUIGR PLU_REAL,COUNT(DISTINCT PBO_KODEOMI) TOKO_YANG_PB_REALISASI,SUM (PBO_QTYREALISASI) QTY_REAL,SUM(PBO_TTLNILAI) RPH_REAL
                           FROM TBMASTER_PBOMI WHERE TRUNC(PBO_CREATE_DT) between to_date('$tglawal', 'yyyy-mm-dd') and to_date('$tglakhir', 'yyyy-mm-dd') $filteromi
                           AND PBO_CREATE_BY <>'BKL' AND PBO_RECORDID IN ('4','5') GROUP BY PBO_PLUIGR 
                           )ON PBO_PLUIGR=PLU_REAL
                 WHERE PBO_CREATE_BY <>'BKL' AND  TRUNC(PBO_CREATE_DT) between to_date('$tglawal', 'yyyy-mm-dd') and to_date('$tglakhir', 'yyyy-mm-dd') $filteromi
                 GROUP BY TRUNC(PBO_CREATE_DT), SUBSTR(PBO_PLUIGR,1,6)||'0', NVL(TOKO_YANG_PB_REALISASI,0), NVL(QTY_REAL,0), NVL(RPH_REAL,0)
                 )
                 LEFT JOIN
                 
                 (SELECT 
                 PRD_KODEDIVISI DIV,
                 PRD_KODEDEPARTEMENT DEPT,
                 PRD_KODEKATEGORIBARANG KATB,
                 PRC_PLUOMI PLU_OMI,
                 PRD_PRDCD PLU_IGR,
                 PRD_DESKRIPSIPANJANG DESKRIPSI,
                 PRD_UNIT UNIT,
                 PRD_FRAC FRAC,
                 PRD_KODETAG TAG_IGR,
                 PRC_KODETAG TAG_OMI,
                 HGB_NILAIDPP HARGABELIOMI,
                 ST_SALDOAKHIR STOKLPP,
                 PKM_PKMT PKMT,
                 PKM_PKM PKM,
                 PKMP_QTYMINOR MPLUS,
                 HGB_KODESUPPLIER KODE_SUPP,
                 SUP_NAMASUPPLIER NAMA_SUPP,
                 RAK,
                 SR,
                 TR,
                 SH,
                 NU,
                 QTYPLANO_DPD,
                 MAXPLANO_DPD,
                 MAXPLANO_TOKO,
                 QTYPLANO_TOKO,
                 NVL(QTYPO_KD,0) QTY_PO_KD,
                 QTY_PO,
                 CASE WHEN QTYPO_KD>0 AND QTY_PO IS NULL THEN '*' ELSE '' END AS PO_KADALUARSA,
                 SL_SUPP_SAATINI ,
                 SL,
                 AVG_SL_3BLN ,
                 PRC_MINORDER MINOR_OMI,
                 PKM_MINORDER MINOR_IGR,
                 BPB_QTY,
                 AVG_6X_PBOMI,
                 AVG_SLS_PB_OMI,
                 ROUND((ST_SALDOAKHIR/AVG_6X_PBOMI),2) STOCK_VS_AVG_6X_PBOMI
                 
                 
                 FROM TBMASTER_PRODMAST
                 
                 LEFT JOIN TBMASTER_PRODCRM ON PRD_PRDCD=PRC_PLUIGR
                 LEFT JOIN (SELECT ST_PRDCD,ST_SALDOAKHIR FROM TBMASTER_STOCK WHERE ST_LOKASI='01')ON PRD_PRDCD=ST_PRDCD
                 LEFT JOIN (SELECT HGB_PRDCD,HGB_NILAIDPP,HGB_KODESUPPLIER,SUP_NAMASUPPLIER FROM TBMASTER_HARGABELI LEFT JOIN TBMASTER_SUPPLIER ON HGB_KODESUPPLIER=SUP_KODESUPPLIER WHERE HGB_TIPE='2') ON PRD_PRDCD=HGB_PRDCD
                 LEFT JOIN TBMASTER_KKPKM ON PRD_PRDCD=PKM_PRDCD
                 LEFT JOIN (SELECT PKMP_PRDCD, PKMP_QTYMINOR  FROM TBMASTER_PKMPLUS ) ON PRD_PRDCD=PKMP_PRDCD
                 
                 -- MASTER LOKASI DPD, JIKA DUPLIKAT YG DIPILIH BULKY
                 LEFT JOIN (SELECT * FROM(
                 SELECT LKS_KODERAK RAK,LKS_KODESUBRAK SR,LKS_TIPERAK TR,LKS_SHELVINGRAK SH,LKS_NOURUT NU,LKS_PRDCD,
                             LKS_QTY QTYPLANO_DPD,LKS_MAXPLANO MAXPLANO_DPD, LKS_NOID,SUBSTR(LKS_NOID,-1,1),
                         DENSE_RANK() OVER (PARTITION BY LKS_PRDCD ORDER BY SUBSTR(LKS_NOID,-1,1)) RANK 
                 FROM TBMASTER_LOKASI WHERE SUBSTR(LKS_KODERAK,0,1) IN ('D') AND LKS_TIPERAK<>'S') 
                 WHERE RANK='1'
                           ) ON PRD_PRDCD=LKS_PRDCD
                 
                 --MASTER LOKASI REGULER
                 LEFT JOIN (
                             SELECT LKS_KODERAK RAKA,LKS_PRDCD PLUTOKO,LKS_MAXPLANO MAXPLANO_TOKO,LKS_QTY QTYPLANO_TOKO
                             FROM TBMASTER_LOKASI WHERE (LKS_KODERAK LIKE 'R%' OR LKS_KODERAK LIKE 'O%') AND LKS_TIPERAK<>'S'
                            ) ON PRD_PRDCD=PLUTOKO
                 
                 --PO YANG MASIH AKTIF
                 LEFT JOIN(
                           SELECT TPOD_PRDCD,SUM(TPOD_QTYPO) AS QTY_PO FROM TBTR_PO_H LEFT JOIN TBTR_PO_D ON TPOH_NOPO=TPOD_NOPO 
                           WHERE TPOH_RECORDID IS NULL AND TRUNC(TPOH_TGLPO+TPOH_JWPB)>=TRUNC(SYSDATE) GROUP BY TPOD_PRDCD
                           )ON PRD_PRDCD=TPOD_PRDCD
                 
                 --PO KADALUARSA (HARUS DIBANDINGKAN DENGAN PO YANG MASIH AKTIF)
                 LEFT JOIN (
                             SELECT * FROM (
                                           SELECT TPOH_RECORDID  RECID_KD,TPOH_TGLPO TGLPO_KD,TPOH_JWPB,TPOH_NOPO NOPO_KD,TPOD_PRDCD PLU_KD,TPOD_QTYPO QTYPO_KD,
                                           DENSE_RANK() OVER (PARTITION BY TPOD_PRDCD ORDER BY TPOH_TGLPO DESC,TPOH_NOPO DESC) RANK_KD
                                           FROM TBTR_PO_H LEFT JOIN TBTR_PO_D ON TPOH_NOPO=TPOD_NOPO WHERE TRUNC(TPOH_TGLPO+TPOH_JWPB)<TRUNC(SYSDATE)
                                           )WHERE RANK_KD='1' AND RECID_KD IS NULL
                           )ON PRD_PRDCD=PLU_KD
                           
                 ----------------SL_3_BULAN_KEMARIN
                 LEFT JOIN(SELECT PRD_PRDCD AS PLUSL3BLN,ROUND(((BPB_QTY/PO_QTY)*100),2) AS AVG_SL_3BLN FROM TBMASTER_PRODMAST 
                           LEFT JOIN (
                                       SELECT   TPOD_PRDCD, SUM(TPOD_QTYPO) PO_QTY, SUM(MSTD_QTY) BPB_QTY
                                       FROM (  SELECT  * FROM TBTR_PO_H LEFT JOIN TBTR_PO_D ON TPOH_NOPO=TPOD_NOPO WHERE NVL(TPOH_RECORDID,'2')<>'1'
                                       AND TRUNC(TPOH_TGLPO) BETWEEN TRUNC(SYSDATE-90,'MON') AND LAST_DAY(SYSDATE-30))
                                       LEFT JOIN(SELECT * FROM TBTR_MSTRAN_H LEFT JOIN TBTR_MSTRAN_D ON MSTH_NODOC=MSTD_NODOC WHERE MSTH_RECORDID IS NULL)
                                       ON TPOH_NOPO=MSTH_NOPO AND TPOD_PRDCD=MSTD_PRDCD GROUP BY TPOD_PRDCD
                                     ) ON PRD_PRDCD=TPOD_PRDCD     
                         ) ON PRD_PRDCD=PLUSL3BLN 
                 
                 ----------------SL_BULAN_KEMARIN
                 LEFT JOIN(SELECT PRD_PRDCD AS PLUSL,ROUND(((BPB_QTY/PO_QTY)*100),2) AS SL FROM TBMASTER_PRODMAST
                           LEFT JOIN (
                                       SELECT   TPOD_PRDCD, SUM(TPOD_QTYPO) PO_QTY, SUM(MSTD_QTY) BPB_QTY
                                       FROM (  SELECT  * FROM TBTR_PO_H LEFT JOIN TBTR_PO_D ON TPOH_NOPO=TPOD_NOPO WHERE NVL(TPOH_RECORDID,'2')<>'1'
                                       AND TRUNC(TPOH_TGLPO) BETWEEN TRUNC(SYSDATE-30,'MON') AND LAST_DAY(SYSDATE-30))
                                       LEFT JOIN(SELECT * FROM TBTR_MSTRAN_H LEFT JOIN TBTR_MSTRAN_D ON MSTH_NODOC=MSTD_NODOC WHERE MSTH_RECORDID IS NULL)
                                       ON TPOH_NOPO=MSTH_NOPO AND TPOD_PRDCD=MSTD_PRDCD GROUP BY TPOD_PRDCD
                                       ) ON PRD_PRDCD=TPOD_PRDCD
                           ) ON PRD_PRDCD=PLUSL 
                 
                 ----------------SL_BULAN_BERJALAN
                 LEFT JOIN(SELECT PRD_PRDCD AS PLUSLBLN,BPB_QTY,ROUND(((BPB_QTY/PO_QTY)*100),2) AS SL_SUPP_SAATINI FROM TBMASTER_PRODMAST
                       LEFT JOIN (
                                   SELECT   TPOD_PRDCD, SUM(TPOD_QTYPO) PO_QTY, SUM(MSTD_QTY) BPB_QTY
                                   FROM (  SELECT  * FROM TBTR_PO_H LEFT JOIN TBTR_PO_D ON TPOH_NOPO=TPOD_NOPO WHERE NVL(TPOH_RECORDID,'2')<>'1'
                                   AND TRUNC(TPOH_TGLPO) >=TRUNC(SYSDATE-1,'MON'))
                                   LEFT JOIN (SELECT * FROM TBTR_MSTRAN_H LEFT JOIN TBTR_MSTRAN_D ON MSTH_NODOC=MSTD_NODOC WHERE MSTH_RECORDID IS NULL)
                                   ON TPOH_NOPO=MSTH_NOPO AND TPOD_PRDCD=MSTD_PRDCD GROUP BY TPOD_PRDCD
                                   ) ON PRD_PRDCD=TPOD_PRDCD
                           ) ON PRD_PRDCD=PLUSLBLN 
                     
                 -- AVG PB OMI BERDASARKAN JUMLAH HARI PB
                    LEFT JOIN(
                               SELECT PLU_AVG66X,QTY_PB,JML_HARI,ROUND(((QTY_PB/JML_HARI)/3),0) AVG_SLS_PB_OMI,ROUND((((QTY_PB/JML_HARI)/3)*6),0) AVG_6X_PBOMI
                               FROM (
                                       SELECT SUBSTR(PBO_PLUIGR,1,6)||'0' PLU_AVG66X, SUM(PBO_QTYORDER) QTY_PB,
                                       COUNT(DISTINCT((TRUNC(PBO_CREATE_DT)))) AS JML_HARI  FROM TBMASTER_PBOMI
                                       WHERE TRUNC(PBO_CREATE_DT) BETWEEN TRUNC(SYSDATE-90,'MON') AND LAST_DAY(SYSDATE-30) 
                                       GROUP BY SUBSTR(PBO_PLUIGR,1,6)||'0'
                                     )
                               )ON PRD_PRDCD=PLU_AVG66X   
                 
                 WHERE PRD_PRDCD LIKE '%0'
                 ) ON PLU=PLU_IGR
                 
                 -- AVG SLS IGR (TIDAK TERMASUK OMI DAN HJK)
                   LEFT JOIN(
                             SELECT PSL_PRDCD PLUAVGSLS,
                             ROUND(((NVL(PSL_QTY_$bln1,0)+NVL(PSL_QTY_$bln2,0)+NVL(PSL_QTY_$bln3,0))-(NVL(RSL_QTY_$bln1,0)+NVL(RSL_QTY_$bln2,0)+NVL(RSL_QTY_$bln3,0))/3),2) AS AVGSLS_IGR 
                             FROM TBTR_PKMSALES
                             LEFT JOIN (SELECT * FROM TBTR_REKAPSALESBULANAN WHERE RSL_GROUP='02') ON PSL_PRDCD=RSL_PRDCD 
                             )ON PLU=PLUAVGSLS
                 
                 ORDER BY DIV,DEPT,KATB,PLU_IGR"
             );
 
             $masterpb = $masterpb->getResultArray();
        }elseif($jenis=="cekdspselisih"){
            $judullap =  "<h3>CEK DATA DSP vs ISIKOLI // OMI : $kodeomi // tanggal : $tglawal</h3>";
                    
        }

        $data = [
            'title' => 'Tampil Data '.$tgljam,
            'judul' => $judullap,
            'rekaporder' => $rekaporder, 
            'hitungtotal' => $hitungtotal,
            'listorder' => $listorder,
            'tolakanpb' => $tolakanpb,
            'pickingitem' => $pickingitem,
            'masteritem' => $masteritem,
            'masterpb' => $masterpb,
        ];

        return view('omi/tampilmonitoringomi', $data);
    }

    public function cekprosespbomi()
    {
        $dbProd = db_connect('production');
        $kodeomi = strtoupper($this->request->getVar('kodeomi'));
        $nopb = $this->request->getVar('nopb');
        $btn = $this->request->getVar('btn');

        $cekpicking = $dspvskoli = $dspcostnull = $jalurkarton = $blmpicking = [];

        if ($btn=="cekpicking") {
            $judulkanan = "Data Item Belum Diproses oleh Checker --> OMI : $kodeomi - NOPB : $nopb";

            $cekpicking = $dbProd->query(
                "SELECT pbo_kodeomi as OMI,pbo_nopb as NOPB,pbo_pluigr as PLU,prd_deskripsipanjang as DESKRIPSI,prd_kodetag as TAG,
                pbo_qtyorder as QTYPB,pbo_qtyrealisasi as QTYPICKING,st_saldoakhir as QTYLPP,lks_qty as QTYPLANO,
                lks_koderak||'.'||lks_kodesubrak||'.'||lks_tiperak||'.'||lks_shelvingrak||'.'||lks_nourut as DISPLAY
                from tbmaster_pbomi 
                left join tbmaster_prodmast on prd_prdcd=pbo_pluigr
                left join (select * from tbmaster_stock where st_lokasi='01')on substr(st_prdcd,0,6)=substr(prd_prdcd,0,6)
                left join (select * from tbmaster_lokasi where lks_koderak like 'D%' and substr(lks_tiperak,0,1) <>'S' and lks_koderak!='DKLIK') on substr(lks_prdcd,0,6)=substr(prd_prdcd,0,6)
                where pbo_kodeomi='$kodeomi' and pbo_nopb='$nopb' and pbo_recordid='3'
                order by display"
            );
            $cekpicking = $cekpicking->getResultArray();
        }elseif($btn=="dspvskoli"){
            $judulkanan = "Data Item DSP vs Isi Koli";
            $dspvskoli = $dbProd->query(
                "SELECT pbo_kodeomi as KODEOMI,
                pbo_nopb as NOPB,
                pbo_create_dt as TGLPROSES,
                pbo_nokoli as NOKOLI,
                pbo_userupdatechecker as CHECKER,
                count(pbo_pluigr) as ItemPB,
                count(pbo_jamupdatechecker) as ItemChecker,
                count(rpb_idsuratjalan) as ItemStruk
                from tbmaster_pbomi
                left join tbtr_realpb on pbo_nopb=rpb_nodokumen and pbo_pluigr=rpb_plu2 and pbo_nokoli=rpb_nokoli
                where pbo_nokoli is not null and pbo_kodeomi='$kodeomi' and pbo_nopb='$nopb' 
                group by pbo_kodeomi,pbo_nopb,pbo_create_dt, pbo_nokoli, pbo_userupdatechecker
                order by pbo_nokoli"
            );

            $dspvskoli = $dspvskoli->getResultArray();

        }elseif($btn=="dspcostnull"){
            $judulkanan = "Data PB OMI dengan Nilai RphCost NULL";
            $dspcostnull = $dbProd->query(
                "SELECT rpb_create_dt,
                rpb_kodeomi,
                rpb_nodokumen,
                rpb_nokoli,
                rpb_plu2,
                rpb_cost,
                rpb_nosph 
                from tbtr_realpb 
                where rpb_kodeomi='$kodeomi' and rpb_nodokumen='$nopb' and rpb_cost is null and rpb_nodokumen is not null"
            );

            $dspcostnull = $dspcostnull->getResultArray();
        }elseif($btn=="jalurkarton"){
            $judulkanan = "Item Khusus Karton DPD01 & DPD10 >> OMI : $kodeomi - NoPB : $nopb";
            $jalurkarton = $dbProd->query(
                "SELECT 
                grr_grouprak as GROUPRAK,
                lks_koderak||'.'||lks_kodesubrak||'.'||lks_tiperak||'.'||lks_shelvingrak||'.'||lks_nourut as DISPLAY,
                pbo_pluigr,prd_deskripsipanjang,prd_kodetag,prd_unit,prd_frac,
                pbo_qtyorder,pbo_qtyrealisasi
                from tbmaster_pbomi 
                left join tbmaster_prodmast on prd_prdcd=substr(pbo_pluigr,0,6)||'0'
                left join tbmaster_lokasi on substr(lks_prdcd,0,6)=substr(pbo_pluigr,0,6)
                left join tbmaster_grouprak on lks_koderak=grr_koderak and lks_kodesubrak=grr_subrak
                where pbo_kodeomi='$kodeomi' and pbo_nopb='$nopb'
                and grr_grouprak in('DPD01','DPD10')
                and lks_noid is not null
                order by DISPLAY"
            );

            $jalurkarton = $jalurkarton->getResultArray();
        }elseif($btn=="blmpicking"){
            $judulkanan = "Item Belum Picking >> OMI : $kodeomi - NoPB : $nopb";
            $blmpicking = $dbProd->query(
                "SELECT pbo_kodeomi as OMI,pbo_nopb as NOPB,pbo_pluigr as PLU,prd_deskripsipanjang as DESKRIPSI,prd_kodetag as TAG,
                pbo_qtyorder as QTYPB,pbo_qtyrealisasi as QTYPICKING,st_saldoakhir as QTYLPP,lks_qty as QTYPLANO,
                lks_koderak||'.'||lks_kodesubrak||'.'||lks_tiperak||'.'||lks_shelvingrak||'.'||lks_nourut as DISPLAY
                from tbmaster_pbomi 
                left join tbmaster_prodmast on prd_prdcd=pbo_pluigr
                left join (select * from tbmaster_stock where st_lokasi='01')on substr(st_prdcd,0,6)=substr(prd_prdcd,0,6)
                left join (select * from tbmaster_lokasi where lks_koderak like 'D%' and substr(lks_tiperak,0,1) <>'S' and lks_koderak!='DKLIK') on substr(lks_prdcd,0,6)=substr(prd_prdcd,0,6)
                where pbo_kodeomi='$kodeomi' and pbo_nopb='$nopb' and pbo_recordid='2'
                order by display"
            );

            $blmpicking = $blmpicking->getResultArray();
        }else{
            $judulkanan = "Tidak Ada Data untuk Ditampilkan";
        }


        $data = [
            'title' => 'Cek Proses PB OMI',
            'kodeomi' => $kodeomi,
            'nopb' => $nopb,
            'judulkanan' => $judulkanan,
            'cekpicking' => $cekpicking,
            'dspvskoli' => $dspvskoli,
            'dspcostnull' => $dspcostnull,
            'jalurkarton' => $jalurkarton,
            'blmpicking' => $blmpicking,
        ];

        redirect()->to('omi/cekprosespbomi')->withInput();
        return view('omi/cekprosespbomi', $data);
    }

    public function awbipp()
    {
        $dbProd = db_connect('production');
        $tgl = $this->request->getVar('tgl');

        $awb = $dbProd->query(
            "SELECT * from igrbgr.tbtr_ipp_omi
            where trunc(create_dt)=to_date('$tgl','YYYY-MM-DD')
            order by kodetoko "
        );

        $awb = $awb->getResultArray();

        $data = [
            'title' => 'Cek Proses AWB IPP',
            'tanggal' => $tgl,
            'awb' => $awb
        ];

        return view('omi/awbipp', $data);
    }

    public function progress()
    {
        $dbProd = db_connect('production');
        $tgl = $this->request->getVar('tgl');

        $progress = $dbProd->query(
            "SELECT TAHAP,count(pbo_pluigr) as JUMLAHITEM from (
                select pbo_recordid,rpb_idsuratjalan,
                case 
                when pbo_recordid='2' and rpb_idsuratjalan is null then '1'
                when pbo_recordid='3' and rpb_idsuratjalan is null then '2'
                when pbo_recordid='4' and rpb_idsuratjalan is null then '3'
                when pbo_recordid='4' and rpb_idsuratjalan is not null then '4'
                when pbo_recordid='5' and rpb_idsuratjalan is not null then '5'
                end as TAHAP,
                pbo_pluigr 
                from tbmaster_pbomi
                left join tbtr_realpb on rpb_kodeomi=pbo_kodeomi and rpb_nodokumen=pbo_nopb and rpb_plu2=pbo_pluigr
                where pbo_kodesupplier='SIGR5' and trunc(pbo_create_dt)=to_date('$tgl','YYYY-MM-DD')
                ) group by TAHAP order by 1"
        );

        $progress = $progress->getResultArray();

        $data = [
            'title' => 'Progress Realisasi PBOMI',
            'progress' => $progress
        ];

        return view('omi/progress', $data);
    }

    public function cektolakan()
    {
        $dbProd = db_connect('production');
        $tgl = $this->request->getVar('tgl');
        $pluinput = $this->request->getVar('plu');
        if(isset($pluinput)){
            $plu = sprintf("%07s",$pluinput);
            $plu1 = substr($plu,0,6)."%";
            $plu0 = substr($plu,0,6)."0";
            if($plu0 != "0000000") {
                $filterplu = "and tlko_pluigr='$plu0' ";
            }else{
                $filterplu = " ";
            }
        }else{
            $filterplu = "";
        }

        $tolakan = $dbProd->query(
            "select tlko_pluigr,tlko_desc,tlko_kodeomi,tlko_nopb,tlko_qtyorder,tlko_qtyekonomis,tlko_lpp,tlko_kettolakan 
            from tbtr_tolakanpbomi
            where trunc(tlko_create_dt)=to_date('$tgl','YYYY-MM-DD')
            $filterplu
            order by tlko_create_dt"
        );

        $tolakan = $tolakan->getResultArray();

        $data = [
            'title' => 'Tolakan PB OMI',
            'tgl' => $tgl,
            'tolakan' => $tolakan
        ];

        redirect()->to('cektolakan')->withInput();
        return view('omi/cektolakan', $data);
    }

    public function historyplu()
    {
        $dbProd = db_connect('production');
        $tglawal = $this->request->getVar('tglawal');
        $tglakhir = $this->request->getVar('tglakhir');
        $pluinput = $this->request->getVar('plu');
        $kodeomi = $this->request->getVar('kodeomi');

        if(isset($pluinput)){
            $plu = sprintf("%07s",$pluinput);
            $plu1 = substr($plu,0,6)."%";
            $plu0 = substr($plu,0,6)."0";
            if($plu0 != "0000000") {
                $filterplu = "and substr(pbo_pluigr,0,6)||'0' = '$plu0' ";
            }else{
                $filterplu = " ";
            }
        }else{
            $filterplu = "";
        }
        
        if(isset($kodeomi)){
            $tokoomi = strtoupper($kodeomi);
            if($tokoomi != "") {
                $filtertokoomi = "and pbo_kodeomi='$tokoomi' ";
            }else{
                $filtertokoomi = "";
            }
        }else{
            $filtertokoomi = "";
        }

        $history = $dbProd->query(
            "SELECT pbo_kodeomi as KODEOMI,
            tko_namaomi as NAMAOMI,
            pbo_tglpb as TGLPB,
            pbo_create_dt as TGLPROSES,
            pbo_pluigr as PLUIGR,
            prd_deskripsipendek as DESKRIPSI,
            nvl(pbo_qtyorder,0) as QTYORDER,
            nvl(rpb_qtyrealisasi,0) as QTYREALISASI,
            nvl(rpb_qtyv,0) as QTYV,
            nvl(trjd_quantity,0)  as QTYSALES
          from tbmaster_pbomi   
          left join tbmaster_prodmast on prd_prdcd=pbo_pluigr
          left join tbmaster_tokoigr on tko_kodeomi=pbo_kodeomi   
          left join tbtr_realpb on rpb_kodeomi=pbo_kodeomi and pbo_nopb=rpb_nodokumen and pbo_pluigr=rpb_plu2   
          left join tbtr_jualdetail on trjd_prdcd=pbo_pluigr and trunc(trjd_transactiondate)=trunc(pbo_tglstruk) and pbo_nostruk=trjd_transactionno||trjd_cashierstation||trjd_create_by and trjd_cus_kodemember=pbo_kodemember   
          where trunc(pbo_create_dt) between to_date('$tglawal','yyyy-mm-dd') and  to_date('$tglakhir','yyyy-mm-dd') 
          $filterplu
          $filtertokoomi "
        );

        $history = $history->getResultArray();

        $data = [
            'title' => 'History Per PLU',
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'history' => $history,
            'plu' => $pluinput,
            'omi' => $kodeomi,
        ];

        redirect()->to('historyplu')->withInput();
        return view('omi/historyplu', $data);
    }

    public function cekprosessph()
    {
        $dbProd = db_connect('production');
        $tgl = $this->request->getVar('tgl');
        $btn = $this->request->getVar('btn');

        $ceksph = $blmstruk = [];

        if($btn=="ceksph"){
            $ceksph = $dbProd->query(
                "SELECT 
                tko_kodeomi as KodeOmi,
                trpt_cus_kodemember as KodeMember,
                mcl_top as TOP,
                okl_noorder as Nomorpb,
                trpt_cashierid as Cashier,
                trpt_cashierstation as Station,
                trpt_sph_no as NoSPH,
                trpt_salesinvoicedate,
                trpt_sph_tgl,
                trpt_salesduedate,
                case when mcl_top - (trpt_salesduedate - trpt_salesinvoicedate) between 0 and 2 then '1' else '0' end as CEKJT,
                sum(trpt_salesvalue) as NilaiSales,
                sum(trpt_sph_amount) as NilaiSPH,
                sum(trpt_salesvalue-trpt_sph_amount) as selisih
              from tbtr_piutang 
              left join tbmaster_tokoigr on tko_kodecustomer=trpt_cus_kodemember
              left join tbtr_omikoli on okl_kodeomi=tko_kodeomi and okl_nosph=trpt_sph_no and trpt_docno=okl_nokoli
              left join ( select * from igrbgr.tbmaster_clomi where mcl_kodeproxy='1' ) on mcl_kodeomi=tko_kodeomi
              where trpt_recordid is null and trpt_cashierid in ('OMI') and trunc(trpt_salesinvoicedate)=to_date('$tgl','YYYY-MM-DD')
              group by tko_kodeomi, trpt_cus_kodemember,mcl_top,okl_noorder, trpt_cashierid, trpt_cashierstation, trpt_sph_no, trpt_salesinvoicedate,trpt_sph_tgl,trpt_salesduedate,
              case when mcl_top - (trpt_salesduedate - trpt_salesinvoicedate) between 0 and 2 then '1' else '0' end
              order by trpt_cashierid,tko_kodeomi,trpt_sph_no"
            );

            $ceksph = $ceksph->getResultArray();
        }elseif($btn=="blmstruk"){
            $blmstruk = $dbProd->query(
                "SELECT tko_kodeomi as KODEOMI,tko_namaomi as NAMAOMI,
                rpb_nodokumen as NOPB,
                to_char(rpb_create_dt,'DD-MON-YYYY') as TGL_DSP,
                sum(rpb_ttlnilai+rpb_ttlppn) + sum(rpb_distributionfee*1.1) as NILAI
              from tbtr_realpb 
              left join tbmaster_tokoigr on tko_kodeomi=rpb_kodeomi
              where trunc(rpb_create_dt)>=trunc(sysdate-30) and rpb_flag ='2' and rpb_nosph is null
              group by tko_kodeomi, tko_namaomi, rpb_nodokumen, to_char(rpb_create_dt,'DD-MON-YYYY')
              order by TGL_DSP,KODEOMI"
            );

            $blmstruk = $blmstruk->getResultArray();
        }
        $data = [
            'title' => 'Cek Proses SPH',
            'tgl' => $tgl,
            'ceksph' => $ceksph,
            'blmstruk' => $blmstruk,
        ];

        redirect()->to('cekprosessph')->withInput();
        return view('omi/cekprosessph', $data);
    }

    public function historybkl()
    {
        $dbProd = db_connect('production');
        $kodeomi2 = strtoupper($this->request->getVar('kodeomi2'));
        $nobukti = $this->request->getVar('nobukti');

        $supplier = $cekbkl = [];
        $supplier = $dbProd->query(
            "SELECT distinct bkl_kodesupplier,sup_namasupplier
            from tbhistory_bkl
            left join tbmaster_supplier on sup_kodesupplier=bkl_kodesupplier
            order by sup_namasupplier"
        );

        $cekbkl = $dbProd->query(
            "select 
            bkl_kodeomi as OMI,
            bkl_idfile as IDFILE,
            bkl_nobukti as NOBUKTI,
          bkl_nodoc as NODOC,
            substr(bkl_tglbukti,0,9) as TGLBUKTI,
            sum(mstd_gross+mstd_ppnrph) as NILAIFAKTUR,
            bkl_kodesupplier as SUPPLIER,
            SUP_NAMASUPPLIER as NAMASUPPLIER,
            substr(bkl_tglstruk,0,9) as TGLSTRUK,
          bkl_total as NILAISTRUKBKL,
            bkl_create_by  as PROSESBY
          from tbhistory_bkl 
          LEFT JOIN TBMASTER_SUPPLIER ON SUP_KODESUPPLIER=BKL_KODESUPPLIER
        LEFT JOIN tbtr_mstran_d on mstd_nodoc=bkl_nodoc
          where bkl_kodeomi='$kodeomi2' and bkl_nobukti='$nobukti'
        group by bkl_kodeomi, bkl_idfile, bkl_nobukti, bkl_nodoc, substr(bkl_tglbukti,0,9), bkl_kodesupplier, SUP_NAMASUPPLIER, substr(bkl_tglstruk,0,9), bkl_total, bkl_create_by
          order by bkl_kodeomi,bkl_nobukti"
        );

        $supplier = $supplier->getResultArray();
        $cekbkl = $cekbkl->getResultArray();

        $data = [
            'title' => 'History BKL OMI',
            'supplier' => $supplier,
            'cekbkl' => $cekbkl,
        ];

        return view('omi/historybkl', $data);
    }

    public function tampilbkl()
    {
        $dbProd = db_connect('production');
        $tglawal = $this->request->getVar('tglawal');
        $tglakhir = $this->request->getVar('tglakhir');
        $kodesup = $this->request->getVar('supplier');
        $kodeomi = $this->request->getVar('kodeomi');
        $btn = $this->request->getVar('btn');
        $tanggalSekarang = $this->tglsekarang;

        if($kodeomi=="") {
            $filteromi = "";
        }else{
            $filteromi = "and bkl_kodeomi='$kodeomi'";
        }
        
        if($kodesup=="all") {
            $filtersupplier = "";
        }else{
            $filtersupplier = "and bkl_kodesupplier='$kodesup'";
        }

        $rekapbkl = $dbProd->query(
            "SELECT BKL_KODEOMI,BKL_IDFILE,BKL_NOBUKTI,BKL_TGLBUKTI,MSTH_NODOC,MSTH_TGLDOC,
            MSTH_NOFAKTUR,MSTH_TGLFAKTUR,sum(mstd_gross+mstd_ppnrph) as NILAI,SUP_NAMASUPPLIER,BKL_CREATE_BY
            from tbhistory_bkl 
            left join tbtr_mstran_h on msth_nodoc=bkl_nodoc
            left join tbtr_mstran_d on msth_nodoc=mstd_nodoc
            left join tbmaster_supplier on msth_kodesupplier=sup_kodesupplier
            where trunc(bkl_tglstruk) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD') $filteromi $filtersupplier
            group by BKL_KODEOMI, BKL_IDFILE, BKL_NOBUKTI, BKL_TGLBUKTI, MSTH_NODOC, MSTH_TGLDOC, MSTH_NOFAKTUR, MSTH_TGLFAKTUR, SUP_NAMASUPPLIER, BKL_CREATE_BY
            order by BKL_KODEOMI,BKL_NOBUKTI"
        );

        $rekapbkl = $rekapbkl->getResultArray();

        $data = [
            'title' => 'Tampil Data BKL',
            'rekapbkl' => $rekapbkl,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'omi' => $kodeomi,
        ];

        if($btn=="xls"){
            $filename = "datapromo $tanggalSekarang.xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        return view('omi/xlsbkl',$data);
        }else{
            return view('omi/tampilbkl', $data);
        }

    }

    public function slomi()
    {
        $data = [
            'title' => 'Service Level OMI'
        ];

        return view('omi/slomi', $data);
    }

    public function tampilslomi()
    {
        $dbProd = db_connect('production');
        $tglawal = $this->request->getVar('tglawal');
        $tglakhir = $this->request->getVar('tglakhir');
        $omi = strtoupper($this->request->getVar('omi'));
        $pluinput = $this->request->getVar('plu');
        $jenis = $this->request->getVar('jenis');
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
        
        //set filter data
        
        if($omi=="") {
            $filtertokoomi      = "";
            $judul_filtertokoomi = "ALL";
        }else{
            $filtertokoomi       = " and tko_kodeomi='$omi' ";
            $judul_filtertokoomi = "$omi";
        }
        
        if($pluinput=="") {
            $filterplu = "";
        }else{
            $filterplu = " and substr(pbo_pluigr,0,6)||'0'='$plu0' ";
        }

        $judul = "";
        $rekaptoko = $rekappb = $listorder = $detailplu = $itemrefund = $tolakanlengkap = $tolakanplu = $tidaktersuplai = [];

        // Pilihan Jenis Laporan
        if($jenis=="rekaptoko"){
            $judul = "Rekap Order OMI | Periode : $tglawal s/d $tglakhir";
            $rekaptoko = $dbProd->query(
                "SELECT tko_kodeomi as KODEOMI,
                tko_namaomi as NAMAOMI,
                count(pbo_pluigr) as ITEMORDER,
                sum(pbo_qtyorder) as QTYORDER,sum(pbo_nilaiorder) as RPHORDER,sum(pbo_ppnorder) as PPNORDER,
                sum((pbo_nilaiorder + pbo_ppnorder)+(pbo_distributionfee*1.11)) as TTLORDER,
                count(case when pbo_qtyrealisasi>0 then pbo_Pluigr end ) as ITEMPICK,
                sum(pbo_qtyrealisasi) as QTYPICK,sum(pbo_ttlnilai) as RPHPICK,sum(pbo_ttlppn) as PPNPICK,
                sum((pbo_ttlnilai + pbo_ttlppn)+(pbo_distributionfee*1.11)) as TTLPICK,
                count(rpb_plu2) as ITEMDSP,
                sum(rpb_qtyrealisasi) as QTYDSP,sum(rpb_ttlnilai) as RPHDSP,sum(rpb_ttlppn) as PPNDSP,
                sum((rpb_ttlnilai + rpb_ttlppn)+(rpb_distributionfee*1.11)) as TTLDSP
              from tbmaster_pbomi
              left join tbtr_realpb on pbo_pluigr=rpb_plu2 and pbo_kodeomi=rpb_kodeomi and pbo_nopb=rpb_nodokumen and pbo_nokoli=rpb_nokoli
              left join tbmaster_prodcrm on prc_pluomi=pbo_pluomi
              left join tbmaster_prodmast on substr(pbo_pluigr,0,6)||'0'=prd_prdcd
              left join tbmaster_tokoigr on tko_kodeomi=pbo_kodeomi
              where trunc(pbo_create_dt) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
              and substr(pbo_nopb,0,1) in ('6','S','K') 
              $filtertokoomi
              group by tko_kodeomi, tko_namaomi
              order by kodeomi"
            );
            $rekaptoko = $rekaptoko->getResultArray();
        }elseif($jenis=="rekapnopb"){
            $judul = "Rekap Order OMI - Per Nomor PB | Periode : $tglawal s/d $tglakhir";
            $rekappb = $dbProd->query(
                "SELECT tko_kodeomi as KODEOMI,
                tko_namaomi as NAMAOMI,pbo_nopb as NOMORPB,pbo_tglpb as TGLPB,
                count(pbo_pluigr) as ITEMORDER,
                sum(pbo_qtyorder) as QTYORDER,sum(pbo_nilaiorder) as RPHORDER,sum(pbo_ppnorder) as PPNORDER,
                sum((pbo_nilaiorder + pbo_ppnorder)+(pbo_distributionfee*1.11)) as TTLORDER,
                count(case when pbo_qtyrealisasi>0 then pbo_Pluigr end ) as ITEMPICK,
                sum(pbo_qtyrealisasi) as QTYPICK,sum(pbo_ttlnilai) as RPHPICK,sum(pbo_ttlppn) as PPNPICK,
                sum((pbo_ttlnilai + pbo_ttlppn)+(pbo_distributionfee*1.11)) as TTLPICK,
                count(rpb_plu2) as ITEMDSP,
                sum(rpb_qtyrealisasi) as QTYDSP,sum(rpb_ttlnilai) as RPHDSP,sum(rpb_ttlppn) as PPNDSP,
                sum((rpb_ttlnilai + rpb_ttlppn)+(rpb_distributionfee*1.11)) as TTLDSP
              from tbmaster_pbomi
              left join tbtr_realpb on pbo_pluigr=rpb_plu2 and pbo_kodeomi=rpb_kodeomi and pbo_nopb=rpb_nodokumen and pbo_nokoli=rpb_nokoli
              left join tbmaster_prodcrm on prc_pluomi=pbo_pluomi
              left join tbmaster_prodmast on substr(pbo_pluigr,0,6)||'0'=prd_prdcd
              left join tbmaster_tokoigr on tko_kodeomi=pbo_kodeomi
              where trunc(pbo_create_dt) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
              and substr(pbo_nopb,0,1) in ('6','S','K') 
              $filtertokoomi
              group by tko_kodeomi, tko_namaomi,pbo_nopb,pbo_tglpb
              order by kodeomi,nomorpb"
            );

            $rekappb = $rekappb->getResultArray();
        }elseif($jenis=="listorder"){
            $judul = "Item List Order OMI | Periode : $tglawal s/d $tglakhir";
            $listorder = $dbProd->query(
                "SELECT prd_kodedivisi as DIV,prd_kodedepartement as DEP, prd_kodekategoribarang as KAT,
                substr(pbo_pluigr,0,6)||'0' as PLUIGR,prd_deskripsipanjang as DESKRIPSI,prd_frac as FRAC,prd_unit as UNIT,prd_kodetag as TAGIGR,
                count(distinct pbo_kodeomi) as TOKOORDER,
                count(pbo_pluigr) as ITEMORDER,sum(pbo_qtyorder) as QTYORDER,sum(pbo_nilaiorder) as RPHORDER,sum(pbo_ppnorder) as PPNORDER,
                sum((pbo_nilaiorder + pbo_ppnorder)+(pbo_distributionfee*1.11)) as TTLORDER,
                count(distinct case when nvl(pbo_qtyrealisasi,0)>0 then pbo_kodeomi else null end ) as TOKOPICK,
                sum (case when pbo_qtyrealisasi>0 then 1 else 0 end) as ITEMPICK,sum(pbo_qtyrealisasi) as QTYPICK,sum(pbo_ttlnilai) as RPHPICK,sum(pbo_ttlppn) as PPNPICK,
                sum((pbo_ttlnilai + pbo_ttlppn)+(pbo_distributionfee*1.11)) as TTLPICK,
                count(distinct rpb_kodeomi) as TOKODSP,
                count(rpb_plu2) as ITEMDSP,sum(rpb_qtyrealisasi) as QTYDSP,sum(rpb_ttlnilai) as RPHDSP,sum(rpb_ttlppn) as PPNDSP,
                sum((rpb_ttlnilai + rpb_ttlppn)+(rpb_distributionfee*1.11)) as TTLDSP
              from tbmaster_pbomi
              left join tbtr_realpb on pbo_pluigr=rpb_plu2 and pbo_kodeomi=rpb_kodeomi and pbo_nopb=rpb_nodokumen and pbo_nokoli=rpb_nokoli
              left join tbmaster_prodcrm on prc_pluomi=pbo_pluomi
              left join tbmaster_prodmast on substr(pbo_pluigr,0,6)||'0'=prd_prdcd
              left join tbmaster_tokoigr on tko_kodeomi=pbo_kodeomi
              where trunc(pbo_create_dt) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
              and substr(pbo_nopb,0,1) in ('6','S','K') 
              $filtertokoomi
              group by prd_kodedivisi, prd_kodedepartement,prd_kodekategoribarang,pbo_pluigr,prd_deskripsipanjang,prd_frac,prd_unit,prd_kodetag
              order by prd_kodedivisi, prd_kodedepartement,prd_kodekategoribarang,prd_deskripsipanjang"
            );
            $listorder = $listorder->getResultArray();
        }elseif($jenis=="detailplu"){
            $judul = "Detail Order Per Item | Periode : $tglawal s/d $tglakhir";
            $detailplu = $dbProd->query(
                "SELECT prd_kodedivisi as DIV,prd_kodedepartement as DEP, prd_kodekategoribarang as KAT,prd_frac as FRAC,prd_unit as UNIT,
                substr(pbo_pluigr,0,6)||'0' as PLUIGR,prd_deskripsipanjang as DESKRIPSI,pbo_kodeomi as KODEOMI,pbo_nopb as NOMORPB,pbo_create_dt as TGLPROSES,
                count(pbo_pluigr) as ITEMORDER,sum(pbo_qtyorder) as QTYORDER,sum(pbo_nilaiorder) as RPHORDER,sum(pbo_ppnorder) as PPNORDER,
                sum((pbo_nilaiorder + pbo_ppnorder)+(pbo_distributionfee*1.11)) as TTLORDER,
                sum (case when pbo_qtyrealisasi>0 then 1 else 0 end) as ITEMPICK,sum(pbo_qtyrealisasi) as QTYPICK,sum(pbo_ttlnilai) as RPHPICK,sum(pbo_ttlppn) as PPNPICK,
                sum((pbo_ttlnilai + pbo_ttlppn)+(pbo_distributionfee*1.11)) as TTLPICK,
                count(rpb_plu2) as ITEMDSP,sum(rpb_qtyrealisasi) as QTYDSP,sum(rpb_ttlnilai) as RPHDSP,sum(rpb_ttlppn) as PPNDSP,
                sum((rpb_ttlnilai + rpb_ttlppn)+(rpb_distributionfee*1.11)) as TTLDSP,
                sum(rpb_qtyv) AS QTYREFUND,
                SUM(rpb_qtyv*rpb_hrgsatuan) as RPHREFUND
              from tbmaster_pbomi
              left join tbtr_realpb on pbo_pluigr=rpb_plu2 and pbo_kodeomi=rpb_kodeomi and pbo_nopb=rpb_nodokumen and pbo_nokoli=rpb_nokoli
              left join tbmaster_prodcrm on prc_pluomi=pbo_pluomi
              left join tbmaster_prodmast on substr(pbo_pluigr,0,6)||'0'=prd_prdcd
              left join tbmaster_tokoigr on tko_kodeomi=pbo_kodeomi
              where trunc(pbo_create_dt) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
              and substr(pbo_nopb,0,1) in ('6','S','K') 
              $filtertokoomi
              $filterplu
              group by prd_kodedivisi, prd_kodedepartement,prd_kodekategoribarang,pbo_pluigr,prd_deskripsipanjang,prd_frac,prd_unit,pbo_kodeomi,pbo_nopb,pbo_create_dt
              order by pbo_create_dt,pbo_kodeomi,pbo_nopb"
            );

            $detailplu = $detailplu->getResultArray();
        }elseif($jenis=="itemrefund"){
            $judul = "Item Dikembalikan OMI | Periode : $tglawal s/d $tglakhir";
            $itemrefund = $dbProd->query(
                "SELECT 
                prd_kodedivisi as DIV,prd_kodedepartement as DEP,prd_kodekategoribarang as KAT,
                rpb_plu2 as PLURPB,prd_prdcd as PLUIGR,prd_deskripsipanjang as DESKRIPSI, prd_frac as FRAC,prd_unit as UNIT,prd_kodetag as TAGIGR,
                rpb_kodeomi as KODEOMI,rpb_nodokumen as NOPBOMI,
                case when rpb_nodokumen is not null then rpb_create_dt else null end as TGLDSP,
                rpb_qtyorder as QTYORDER,rpb_qtyrealisasi as QTYREALISASI,rpb_qtyv as QTYREFUND,
                rpb_keteranganv as KETERANGANV,
                case 
                    when rpb_keteranganv='10' then 'FISIK LEBIH'
                    when rpb_keteranganv='11' then 'SALAH KIRIM / FISIK TIDAK PESAN'
                    when rpb_keteranganv='12' then 'FISIK RUSAK'
                    when rpb_keteranganv='20' then 'FISIK KURANG'
                    when rpb_keteranganv='21' then 'FISIK TIDAK ADA'
                  end as KETERANGAN
                from tbtr_realpb
                left join tbmaster_tokoigr on tko_kodeomi=rpb_kodeomi
                left join tbmaster_prodmast on prd_prdcd=substr(rpb_plu2,0,6)||'0'
                where trunc(rpb_create_dt) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
                and rpb_qtyv is not null
                $filtertokoomi
                order by div,dep,kat,pluigr "
            );
            $itemrefund = $itemrefund->getResultArray();
        }elseif($jenis=="tolakanlengkap"){
            $judul = "Tolakan PB OMI Lengkap | Periode : $tglawal s/d $tglakhir";
            $tolakanlengkap = $dbProd->query(
                "SELECT 
                tlko_kodeomi,
                tlko_nopb,
                tlko_pluigr,
                tlko_pluomi,
                tlko_desc,
                tlko_tag_igr,
                tlko_kettolakan,
                tlko_create_dt,
                tlko_kodeomi,
                tlko_nopb,
                tlko_qtyorder,
                tlko_lastcost,
                tlko_qtyorder*tlko_nilai as total_nilai
              from tbtr_tolakanpbomi
              left join tbmaster_tokoigr on tko_kodeomi=tlko_kodeomi
              where trunc(tlko_create_dt) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
              $filtertokoomi "
            );

            $tolakanlengkap = $tolakanlengkap->getResultArray();
        }elseif($jenis=="tolakanplu"){
            $judul = "Tolakan PB OMI per PLU | Periode : $tglawal s/d $tglakhir";
            $tolakanplu = $dbProd->query(
                "SELECT 
                tlko_pluigr,
                tlko_pluomi,
                tlko_desc,
                count(distinct tlko_kodeomi) as jmltoko,
                count(tlko_nopb) as jmlpb,
                sum(tlko_qtyorder) as qtyorder,
                sum(tlko_qtyorder*tlko_nilai) as total_nilai
              from tbtr_tolakanpbomi
              where trunc(tlko_create_dt) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
              $filtertokoomi 
              group by tlko_pluigr,tlko_pluomi,tlko_desc"
            );

            $tolakanplu = $tolakanplu->getResultArray();
        }elseif($jenis=="tidaktersuplai"){
            $judul = "Item Tidak Tersupply | Periode : $tglawal s/d $tglakhir";
            $tidaktersuplai = $dbProd->query(
                "SELECT distinct
                prd_kodedivisi as DIV,
                prd_kodedepartement as DEP,
                prd_kodekategoribarang as KAT,
                substr(prd_prdcd,0,6)||'0' as PLUIGR,
                prd_deskripsipanjang as DESKRIPSI,
                prd_unit as UNIT,
                prd_frac as FRAC,
                prd_kodetag as TAGIGR,
                prc_pluomi as PLUOMI,
                st_avgcost as ACOST,
                AVGSALES_OMI,
                QTYORDER,
                QTYDSP,
                QTYORDER - QTYDSP as SELISIH_QTY,
                RPHORDER - RPHDSP as SELISIH_RPH,
                st_saldoakhir as STOK,
                TGLPB,QTYPB,
                case when QTYBPB>0 then 'Y' end as BPB,
                TGLPO,QTYPO,
                pkm_pkmt as PKMT,
                pkmp_qtyminor as MPLUS,
                sup_kodesupplier as KDSUPPLIER,
                sup_namasupplier as NAMASUPPLIER
              from tbmaster_prodmast
              left join tbmaster_prodcrm on prc_pluigr=prd_prdcd
              left join (select * from tbmaster_stock where st_lokasi='01') on prd_prdcd=st_prdcd
              left join tbmaster_kkpkm on pkm_prdcd = prd_prdcd
              left join tbmaster_pkmplus on pkmp_prdcd = prd_prdcd
              left join tbmaster_supplier on sup_kodesupplier = pkm_kodesupplier
              left join (
                select rsl_prdcd as PLU02, round((nvl(rsl_qty_04,0) + nvl(rsl_qty_05,0) + nvl(rsl_qty_06,0))/3) as AVGSALES_OMI
                from tbtr_rekapsalesbulanan where rsl_group='02'
              ) ON prd_prdcd=PLU02
  
              left join (
                select
                  pbo_pluigr as PLUPBOMI,
                  sum(pbo_qtyorder) as QTYORDER,
                  sum(pbo_nilaiorder) as RPHORDER,
                  sum(rpb_qtyrealisasi) as QTYDSP,
                  sum(rpb_ttlnilai) as RPHDSP
                from tbmaster_pbomi
                left join tbtr_realpb on pbo_pluigr=rpb_plu2 and pbo_kodeomi=rpb_kodeomi and pbo_nopb=rpb_nodokumen and pbo_nokoli=rpb_nokoli 
                where trunc(pbo_create_dt) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
                $filtertokoomi
                group by pbo_pluigr
              ) on substr(PLUPBOMI,0,6)=substr(prd_prdcd,0,6)
  
              left join (
                select PLUPB,TGLPB,qtypb from (
                  select pbd_prdcd as plulastpb,max(trunc(pbd_create_dt)) as tgllastpb
                  from tbtr_pb_d
                  group by pbd_prdcd
                )
                left join (
                select 
                    pbd_prdcd as PLUPB,
                    trunc(pbd_create_dt) as TGLPB,
                    sum(pbd_qtypb) as QTYPB
                from tbtr_pb_d
                group by pbd_prdcd, trunc(pbd_create_dt) 
                ) on plupb=plulastpb and tgllastpb=tglpb
              ) on PLUPB=PRD_PRDCD
  
              left join (
                select PLUPO,tglpo,qtypo,qtybpb from (
                  select tpod_prdcd as plulastpo,max(trunc(tpod_create_dt)) as tgllastpo
                  from tbtr_po_d 
                  group by tpod_prdcd
                )
                left join (
                select 
                    tpod_prdcd as PLUPO,
                    trunc(tpod_tglpo) as TGLPO,
                    sum(tpod_qtypo) as QTYPO,
                    sum(tpod_qtypb) as QTYBPB
                from tbtr_po_d 
                group by tpod_prdcd, trunc(tpod_tglpo) 
                ) on plupo=plulastpo and tgllastpo=tglpo
              ) on PLUPO=PRD_PRDCD
  
              where prc_pluigr is not null
              and qtyorder>0
              and qtyorder>qtydsp
              order by 1,2,3,5"
            );

            $tidaktersuplai = $tidaktersuplai->getResultArray();
        }else{
            $judul = "Pilihan Tidak Valid";
        }

        $data = [
            'title' => 'Data'.$this->tglsekarang,
            'rekaptoko' => $rekaptoko,
            'rekappb' => $rekappb,
            'listorder' => $listorder,
            'detailplu' => $detailplu,
            'itemrefund' => $itemrefund,
            'tolakanlengkap' => $tolakanlengkap,
            'tolakanplu' => $tolakanplu,
            'tidaktersuplai' => $tidaktersuplai,
            'judul' => $judul,
            'tglawal' => $tglawal,
            'tglakhir' => $tglakhir,
            'judulomi' => $judul_filtertokoomi,
        ];

        return view('omi/tampilslomi', $data);
    }
}