<?php

namespace App\Controllers;

class Logistik extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'LOGISTIK'
        ];
        return view('logistik/index', $data);
    }

    public function soharianplu()
    {
        $data = [
            'title' => 'Form SO Harian'
        ];
        return view('logistik/soharianplu', $data);
    }
    public function tampilsoharian()
    {
        $dbProd = db_connect('production');
        $tgl = $this->request->getVar('tgl');
        $plu = $this->request->getVar('plu');
        $divisi = $this->request->getVar('divisi');

        $soToko = $plu0 = [];

        // Explode, dibagi ke dalam bentuk banyak PLU
        $pluEx = explode(",",$plu);
        foreach($pluEx as $plu0){
            $plu0 = "'".sprintf("%07s",$plu0)."'";
            $soToko = $dbProd->query(
                "SELECT 
                PRD_PRDCD AS PLU,PRD_DESKRIPSIPENDEK AS DESKRIPSI,PRD_FRAC AS FRAC,PRD_UNIT as UNIT,PRD_KODETAG AS TAG,
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
                LKS_KODERAK AS RAK,LKS_KODESUBRAK AS SUBRAK,LKS_TIPERAK AS TIPE,LKS_SHELVINGRAK AS SHELV,LKS_NOURUT AS NOURUT,
                LKS_QTY AS STOK_PLANO,ST_SALDOAKHIR as STOK_LPP,ST_AVGCOST as ACOST,lks_modify_by as MODBY
                FROM TBMASTER_PRODMAST 
                LEFT JOIN TBMASTER_LOKASI ON PRD_PRDCD=LKS_PRDCD
                LEFT JOIN TBMASTER_STOCK ON ST_PRDCD=PRD_PRDCD
                WHERE ST_LOKASI='01' 
                AND PRD_PRDCD IN ($plu0) 
                ORDER BY DESKRIPSI,AREA, LKS_KODERAK, LKS_KODESUBRAK,LKS_TIPERAK, LKS_SHELVINGRAK, LKS_NOURUT ASC"
            );

            $soToko = $soToko->getResultArray();

            // Cek apakah ada Data PLU yg belum realisasi SLP
            $cekSlp = $dbProd->query(
                "SELECT SLP_PRDCD,SLP_DESKRIPSI,SLP_KODERAK||'.'||SLP_KODESUBRAK||'.'||SLP_TIPERAK||'.'||SLP_SHELVINGRAK||'.'||SLP_NOURUT AS SLP_LOKASI, SLP_QTYPCS
                FROM TBTR_SLP WHERE TRUNC(SLP_CREATE_DT)>=TRUNC(SYSDATE-3) AND SLP_FLAG IS NULL AND SLP_PRDCD=$plu0"
            );

            $cekSlp = $cekSlp->getResultArray();

            // Cek apakah ada Data PLU yg belum realisasi SLP
            $cekSpb = $dbProd->query(
                "SELECT SPB_PRDCD,SPB_DESKRIPSI,SPB_LOKASITUJUAN, SPB_MINUS
                FROM TBTEMP_ANTRIANSPB WHERE SPB_RECORDID='3' AND SPB_PRDCD=$plu0"
            );

            $cekSpb = $cekSpb->getResultArray();

            // DATA PICKING KLIK
	// -------PICKING KLIK, ITEM SIAP DRAFT STRUK BELUM MASUK INTRANSIT RECID YANG KAMI AMBIL (1,2,3) sudah picking belum draftstruk, qty>0 -----------------

            $ceKlik = $dbProd->query(
                "SELECT tbtr_obi_h.obi_tgltrans as TANGGAL,tbtr_obi_h.obi_attribute2 as ATRIBUT2,
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

            $ceKlik = $ceKlik->getResultArray();

            // DATA PICKING TMI
	// -------PICKING TMI, ITEM TIDAK MASUK INTRANSIT RECID YANG KAMI AMBIL (1,2,3,4,5,7)-----------------

            $cekTmi = $dbProd->query(
                "SELECT tbtr_obi_h.obi_tgltrans as TANGGAL,tbtr_obi_h.obi_attribute2 as ATRIBUT2,
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

            $cekTmi = $cekTmi->getResultArray();

            // DATA PICKING OMI
	// -------PICKING OMI, ITEM MASUK INTRANSIT RECID YANG KAMI AMBIL (4) sudah scanning belum dsp -----------------

            $cekOmi = $dbProd->query(
                "SELECT pbo_pluigr,pbo_kodeomi,pbo_nokoli,pbo_qtyorder,pbo_qtyrealisasi,pbo_create_dt,
                case 
                  when pbo_recordid='3' then 'Sudah Picking'
                  when pbo_recordid='4' then 'Sudah Scanning'
                end as STATUS
                from tbmaster_pbomi 
                left join tbtr_realpb on pbo_pluigr=rpb_plu2 and pbo_kodeomi=rpb_kodeomi and pbo_nopb=rpb_nodokumen and pbo_nokoli=rpb_nokoli
                where pbo_recordid in ('4') and rpb_nokoli is null and trunc(pbo_create_dt)>=trunc(sysdate -7)
                and substr(pbo_pluigr,0,6)||'0' = $plu0"
            );

            $cekOmi = $cekOmi->getResultArray();
        }

        $data = [
            'title' => 'Form SO Harian',
            'sotoko' => $soToko,
            'cekslp' => $cekSlp,
            'cekspb' => $cekSpb,
            'cekklik' => $ceKlik,
            'cektmi' => $cekTmi,
            'cekomi' => $cekOmi,
            'plugab' => $plu0,
            'tgl' => $tgl,
            'divisi' => $divisi,
            'plubiasa' => $pluEx
        ];

        d($data);
        return view('logistik/tampilsoharian', $data);
    }
}