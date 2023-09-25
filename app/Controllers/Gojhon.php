<?php

namespace App\Controllers;

class Gojhon extends BaseController
{
    public function index()
    {
        return view('gojhon/index',);
    }

    public function bpbperhari()
    {
        $data =[
            'title' => 'BPB Per Hari'
        ];

        return view('gojhon/bpbperhari', $data);
    }

    public function tampilperhari()
    {
        $dbProd = db_connect('production');
        $tglawal = $this->request->getVar('tglawal');
        $tglakhir = $this->request->getVar('tglakhir');
        $jenis = $this->request->getVar('jenis');
        $perproduk = [];

        if($jenis=="produk"){
            $perproduk = $dbProd->query(
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
                FROM 
                (SELECT m.mstd_typetrn        AS trn_type,
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
            AND m.mstd_recordid    IS NULL)  
                WHERE TO_CHAR(trn_tgldoc,'yyyymmdd') BETWEEN $tglawal AND $tglakhir 
                GROUP BY trn_type, trn_div, trn_dept, trn_katb, trn_prdcd, trn_nama_barang, trn_unit, trn_frac, trn_tag"
            );
        }

        $data = [
            'title' => 'Tampil data BPB'
        ];

        return view('gojhon/tampilperhari',$data);
    }
}