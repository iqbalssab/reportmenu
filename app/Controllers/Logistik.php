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

    public function soic() {
        $dbProd = db_connect('production');
        $soic = [];

        $soic = $dbProd->query(
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
            WHERE  ST_LOKASI='01'   AND  PRD_KODEDIVISI NOT IN ('4','6') 
            and prd_prdcd in (select HSO_PRDCD from TBHISTORY_SOIC) 
            ORDER BY    
            NVL((CASE WHEN PRD_UNIT='KG' THEN (NVL(PQTY,0)*ST_AVGCOST)/PRD_FRAC    
            ELSE NVL(PQTY,0)*ST_AVGCOST END)-(CASE WHEN PRD_UNIT='KG' THEN (ST_SALDOAKHIR*ST_AVGCOST)/PRD_FRAC    
            ELSE ST_SALDOAKHIR*ST_AVGCOST END),0) DESC"
        );
        $soic = $soic->getResultArray();

        $data = [
            'title' => 'SO IC',
            'soic' => $soic,
        ];
        redirect()->to('soic')->withInput();
        return view('logistik/soic',$data);
    }

    public function kertaskerja() {
        $dbProd = db_connect("production");
        $kdrak = $departemen = $divisi = $kategori = [];

        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $departemen = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $departemen = $departemen->getResultArray();

        $kategori = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $kategori = $kategori->getResultArray();

        $kdrak = $dbProd->query(
            "SELECT DISTINCT( lks_koderak ) AS lks_koderak
            FROM   tbmaster_lokasi
            WHERE  lks_koderak LIKE 'R%'
                   AND lks_koderak NOT LIKE '%C'
            ORDER  BY lks_koderak"
        );
        $kdrak = $kdrak->getResultArray();

        $data = [
            'title' => 'Kertas Kerja Storage Kecil',
            'divisi' => $divisi,
            'departemen' => $departemen,
            'kategori' => $kategori,
            'kdrak' => $kdrak,
        ];

        redirect()->to('kertaskerja')->withInput();
        return view('/logistik/kertaskerja',$data);
    }

    public function tampilkk() {
        $dbProd = db_connect("production");
        $kdrak = $departemen = $divisi = $kategori = $datakk = $filename = [];
        $lap = $this->request->getVar('jenisLaporan');
        $aksi = $this->request->getVar('tombol');
        $viewKertasKerjaStatus = $filterdiv = $filterdep = $filterkat = $filtertag = $filterrak = $filteromi = $filterpkm = "";

        $kodeDivisi = $kodeDepartemen = $kodeKategoriBarang = $kodeRak = $statusTag = "All"; 
        $itemOmiDiSk = $pkmLebihKecilDariMaxPallet = "Off";

        if(isset($_GET['divisi'])) {if ($_GET['divisi'] !=""){$kodeDivisi = $_GET['divisi']; }}
        if ($kodeDivisi != "All" AND $kodeDivisi != "") {
            $filterdiv = " AND kks_div = '$kodeDivisi' ";
        }
        if(isset($_GET['dep'])) {if ($_GET['dep'] !=""){$kodeDepartemen = $_GET['dep']; }}
        if ($kodeDepartemen != "All" AND $kodeDepartemen != "") {
            $filterdep = " AND kks_dept = '$kodeDepartemen' ";
        }
        if(isset($_GET['kat'])) {if ($_GET['kat'] !=""){$kodeKategoriBarang = $_GET['kat']; }}
        if ($kodeKategoriBarang != "All" AND $kodeKategoriBarang != "") {
            $filterkat = " AND kks_dept || kks_katb = '$kodeKategoriBarang' ";
        }
        if(isset($_GET['kat'])) {if ($_GET['kat'] !=""){$statusTag = $_GET['statusTag']; }}
        if ($statusTag != "All" AND $statusTag != "") {
            $filtertag = " AND NVL(kks_status_tag,' ') = '$statusTag' ";
        }
        if(isset($_GET['rowStorageBesar'])) {if ($_GET['rowStorageBesar'] !=""){$rowStorageBesar = $_GET['rowStorageBesar']; }}
        if(isset($_GET['rowStorageKecil'])) {if ($_GET['rowStorageKecil'] !=""){$rowStorageKecil = $_GET['rowStorageKecil']; }}
        if(isset($_GET['kodeRak'])) {if ($_GET['kodeRak'] !=""){$kodeRak = $_GET['kodeRak']; }}
        if ($kodeRak != "All" AND $kodeRak != "") {
            $filterrak = " AND kks_rak like '%$kodeRak%' ";
        }
        if(isset($_GET['itemOmiDiSk'])) {if ($_GET['itemOmiDiSk'] !=""){$itemOmiDiSk = $_GET['itemOmiDiSk']; }}
        $itemOmiDiSk = strtoupper($itemOmiDiSk);
        if ($itemOmiDiSk == "ON") {
            $filteromi = " AND kks_prdcd IN  (SELECT DISTINCT lks_prdcd FROM tbmaster_lokasi WHERE lks_koderak LIKE '%C' AND lks_prdcd IS NOT NULL AND lks_prdcd IN
                (SELECT DISTINCT lks_prdcd FROM tbmaster_lokasi WHERE lks_koderak LIKE 'D%' AND lks_prdcd   IS NOT NULL AND lks_tiperak <>'S')) ";
        }
        if(isset($_GET['pkmLebihKecilDariMaxPallet'])) {if ($_GET['pkmLebihKecilDariMaxPallet'] !=""){$pkmLebihKecilDariMaxPallet = $_GET['pkmLebihKecilDariMaxPallet']; }}
        $pkmLebihKecilDariMaxPallet = strtoupper($pkmLebihKecilDariMaxPallet);
        if ($pkmLebihKecilDariMaxPallet == "ON") {
            $filterpkm = " AND kks_pkmt < kks_max_palet ";
    
            //cek apakah S, SK atau NS saat akan menampilkan table di di tabel-per-produk.php
        }

        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $departemen = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $departemen = $departemen->getResultArray();

        $kategori = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $kategori = $kategori->getResultArray();

        $kdrak = $dbProd->query(
            "SELECT DISTINCT( lks_koderak ) AS lks_koderak
            FROM   tbmaster_lokasi
            WHERE  lks_koderak LIKE 'R%'
                   AND lks_koderak NOT LIKE '%C'
            ORDER  BY lks_koderak"
        );
        $kdrak = $kdrak->getResultArray();

        if($lap == "1") {
            $datakk = $dbProd->query(
                "SELECT * FROM ( SELECT 
                f.lks_koderak                    AS kks_rak,
                f.lks_kodesubrak                 AS kks_subrak,
                f.lks_tiperak                    AS kks_tipe,
                f.lks_shelvingrak                AS kks_shelving,
                f.lks_nourut                     AS kks_no_urut,
                c.prd_kodedivisi                 AS kks_div,
                c.prd_kodedepartement            AS kks_dept,
                c.prd_kodekategoribarang         AS kks_katb,
                a.pkm_prdcd                      AS kks_prdcd,
                c.prd_deskripsipanjang           AS kks_nama_barang,
                c.prd_unit                       AS kks_unit,
                c.prd_frac                       AS kks_frac,
                Nvl(c.prd_kodetag,' ')           AS kks_kode_tag,
                CASE
                  WHEN Nvl(c.prd_kodetag,' ') IN ('A','R','N','H','O','T','X') THEN 'Discontinue'
                  ELSE 'Active'
                END kks_status_tag,
                m.mpl_kodemonitoring             AS kks_item_pareto,
                s.sls_qty_avg_igr                AS kks_sls_qty_avg_igr,
                s.sls_qty_avg_omi                AS kks_sls_qty_avg_omi,
                s.sls_qty_avg_igr + s.sls_qty_avg_omi AS kks_sls_qty_avg_igr_omi,
                a.pkm_periodeproses              AS kks_periode_pkm,
                --a.pkm_qty1                       AS kks_bulan_01,
                --a.pkm_qty2                       AS kks_bulan_02,
                --a.pkm_qty3                       AS kks_bulan_03,
                --a.pkm_qtyaverage                 AS kks_average,
                c.prd_isibeli                    AS kks_minor,
                a.pkm_mindisplay                 AS kks_min_display,
                a.pkm_leadtime                   AS kks_leadtime,
                sl.tpod_sl                       AS kks_service_level, 
                a.pkm_koefisien                  AS kks_koefisien,
                a.pkm_pkm                        AS kks_pkm,
                a.pkm_mpkm                       AS kks_mpkm,
                b.pkmp_qtyminor                  AS kks_mplus,
                a.pkm_pkmt                       AS kks_pkmt,
                a.pkm_pkmt + (c.prd_isibeli / 2) AS kks_pkm_minor,
                f.lks_maxdisplay                 AS kks_max_display,
                d.mpt_maxqty * c.prd_frac        AS kks_max_palet,
                sex.pln_exis_sts                 AS kks_exis_sts,
                CASE
                  WHEN Nvl(e.prc_pluomi, 'T') <> 'T' THEN 'Y'
                END                                                             AS kks_item_omi,
                c.prd_dimensipanjang                                            AS kks_dim_panjang,
                c.prd_dimensilebar                                              AS kks_dim_lebar,
                c.prd_dimensitinggi                                             AS kks_dim_tinggi,
                c.prd_dimensipanjang * c.prd_dimensilebar * c.prd_dimensitinggi AS kks_dim_volume,
                pl.lks_maxplano_toko                                            AS kks__maxplano_toko,
                pl.lks_minpct_toko                                              AS kks__minpct_toko,
                pl.lks_maxplano_omi                                             AS kks__maxplano_omi,
                pl.lks_minpct_omi                                               AS kks__minpct_omi,
                f.lks_jenisrak                                                  AS kks_jenis_rak,
                h.storage_r                                                     AS kks_storage_r,
                h.storage_c                                                     AS kks_storage_c,
                g.teman1                                                        AS kks_teman1,
                g.teman2                                                        AS kks_teman2,
                g.teman3                                                        AS kks_teman3,
                g.teman4                                                        AS kks_teman4,
                g.teman5                                                        AS kks_teman5,
                g.teman6                                                        AS kks_teman6,
                g.teman7                                                        AS kks_teman7,
                g.teman8                                                        AS kks_teman8,
                g.teman9                                                        AS kks_teman9,
                g.teman10                                                       AS kks_teman10
                FROM  tbmaster_kkpkm a,
                      tbmaster_pkmplus b,
                      tbmaster_prodmast c,
                      tbmaster_maxpalet d,
                      tbmaster_prodcrm e,
                -- kode monitoring pareto
                (SELECT mpl_prdcd, MIN(mpl_kodemonitoring) AS mpl_kodemonitoring FROM tbtr_monitoringplu WHERE mpl_kodemonitoring IN ('SM','SJMF','SJMNF','SPVF','SPVNF','SPV','GMS') GROUP BY mpl_prdcd) m,
                -- sales 3 bulan terakhir ambil dari tbtr_sumsales
                (SELECT sls_prdcd,
                  Round(SUM(sls_qtynomi) / Nvl(SUM(CASE WHEN Nvl(sls_qtynomi, 0) <> 0 THEN 1 END), 1)) AS sls_qty_avg_igr,
                    Round(SUM(sls_qtyomi) / Nvl(SUM(CASE WHEN Nvl(sls_qtyomi, 0) <> 0 THEN 1 END), 1))  AS sls_qty_avg_omi
                    FROM   tbtr_sumsales
                    WHERE  Trunc(sls_periode) BETWEEN Add_months(Trunc(SYSDATE, 'mm'), -3) AND Last_day( Add_months(Trunc(SYSDATE, 'mm'), -1))
                    GROUP  BY sls_prdcd) s,
                -- service level tbtr_po_d dan tbtr_mstran_d
                (SELECT tpod_prdcd, Round(SUM(Nvl(mstd_qty, 0)) / SUM(tpod_qtypo) * 100) AS tpod_sl
                  FROM   tbtr_po_d LEFT JOIN tbtr_mstran_d ON tpod_prdcd = mstd_prdcd AND tpod_nopo = mstd_nopo
                    WHERE  Trunc(tpod_tglpo) BETWEEN Add_months(Trunc(SYSDATE, 'mm'), -3) AND Last_day( Add_months(Trunc(SYSDATE, 'mm'), -1))
                    AND Nvl(tpod_recordid, '0') <> '1'
                    GROUP  BY tpod_prdcd) sl,
                -- max plano dan min pct dari tbmaster_lokasi
                (SELECT lks_prdcd,
                  sum(case when SUBSTR(lks_koderak,1,1) <> 'D' then lks_maxplano else 0 end) as lks_maxplano_toko,
                  sum(case when SUBSTR(lks_koderak,1,1) IN ('R','O') then lks_minpct else 0 end) as lks_minpct_toko,
                    sum(case when SUBSTR(lks_koderak,1,1) = 'D' then lks_maxplano else 0 end) as  lks_maxplano_omi,
                    sum(case when SUBSTR(lks_koderak,1,1) = 'D' then lks_minpct else 0 end) as lks_minpct_omi
                    FROM tbmaster_lokasi
                    WHERE SUBSTR(lks_koderak,1,1) IN ('R', 'O','D','F')
                    AND lks_koderak NOT LIKE '%C'
                    AND lks_tiperak NOT IN ('S')
                    AND lks_prdcd IS NOT NULL 
                    GROUP BY lks_prdcd) pl,
                -- status existing : sex
                (SELECT a.pln_prdcd,
                CASE
                  WHEN a.pln_jenisrak = 'N' THEN 'NS'  
                    WHEN a.pln_jenisrak = 'D' THEN  b.pla_koderak
                END AS pln_exis_sts
                FROM tbmaster_pluplano a LEFT JOIN (SELECT pla_prdcd, MAX( CASE WHEN pla_koderak LIKE '%C' THEN 'SK' ELSE 'S' END) AS pla_koderak FROM tbmaster_plano GROUP BY pla_prdcd) b ON a.pln_prdcd     = b.pla_prdcd
                ) sex,
                (SELECT * FROM tbmaster_lokasi
                  WHERE  Substr(lks_koderak,1,1) IN ('R','O')
                  AND    lks_koderak NOT LIKE '%C'
                    AND    lks_tiperak NOT IN ('S','Z')) f,
                (SELECT * FROM (SELECT pla_prdcd,
                  pla_nourut,pla_koderak
                  FROM   tbmaster_plano ) pivot ( max(pla_koderak) FOR pla_nourut IN (1  AS teman1,
                    2  AS teman2,3  AS teman3,4  AS teman4,5  AS teman5,6  AS teman6,7  AS teman7,8  AS teman8,9  AS teman9,10 AS teman10) )) g,
                (SELECT   lks_prdcd,
                  SUM(CASE
                    WHEN lks_koderak NOT LIKE '%C' THEN 1
                        ELSE 0
                  END) AS storage_r,
                    SUM(CASE
                    WHEN lks_koderak LIKE '%C' THEN 1
                        ELSE 0
                  END) AS storage_c
                    FROM     tbmaster_lokasi
                    WHERE    lks_tiperak = 'S'
                    AND      lks_prdcd IS NOT NULL
                    GROUP BY lks_prdcd) h
                WHERE  pkm_prdcd = prd_prdcd
                AND    a.pkm_prdcd = mpt_prdcd (+)
                AND    a.pkm_prdcd = prc_pluigr (+)
                AND    a.pkm_prdcd = f.lks_prdcd(+)
                AND    a.pkm_prdcd = pkmp_prdcd (+)
                AND    a.pkm_prdcd = pla_prdcd (+)
                AND    a.pkm_prdcd = h.lks_prdcd (+)
                AND    a.pkm_prdcd = m.mpl_prdcd (+)
                AND    a.pkm_prdcd = s.sls_prdcd (+)
                AND    a.pkm_prdcd = sl.tpod_prdcd (+)
                AND    a.pkm_prdcd = pl.lks_prdcd (+)
                AND    a.pkm_prdcd = sex.pln_prdcd (+))
                WHERE  kks_prdcd IS NOT NULL 
                --AND    kks_kode_tag NOT IN ('N','H','O','X','A') 
                $filterdiv
                $filterdep
                $filterkat
                $filtertag
                $filterrak
                $filteromi
                $filterpkm
                ORDER BY  kks_rak,kks_subrak,kks_tipe,kks_shelving,kks_no_urut"
            );
            $datakk = $datakk->getResultArray();
        };

        if($lap == '1') {
            $jlap = 'LAPORAN per PRODUK';
        }

        $data = [
            'title' => 'Tampil Kertas Kerja Storage Kecil',
            'divisi' => $divisi,
            'departemen' => $departemen,
            'kategori' => $kategori,
            'kdrak' => $kdrak,
            'kodeDivisi' => $kodeDivisi,
            'kodeDepartemen' => $kodeDepartemen,
            'kodeKategoriBarang' => $kodeKategoriBarang,
            'kodeRak' => $kodeRak,
            'statusTag' => $statusTag,
            'lap' => $lap,
            'datakk' => $datakk,
            'rowStorageBesar' => $rowStorageBesar,
            'rowStorageKecil' => $rowStorageKecil,
        ];

        if($aksi == 'btnxls') {
            $filename = "Kertas Kerja [". $jlap. "] ".date('d M Y').".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        
            return view('logistik/tampilkk',$data);
        };

        return view('/logistik/tampilkk',$data);
    }

    public function pooutstanding() {
        $dbProd = db_connect("production");
        $departemen = $divisi = $kategori = [];

        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $departemen = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $departemen = $departemen->getResultArray();

        $kategori = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $kategori = $kategori->getResultArray();

        $data = [
            'title' => 'Data PO Outstanding',
            'divisi' => $divisi,
            'departemen' => $departemen,
            'kategori' => $kategori,
        ];

        redirect()->to('pooutstanding')->withInput();
        return view('/logistik/pooutstanding',$data);
    }

    public function tampilpooutstanding() {
        $dbProd = db_connect("production");
        $aksi = $this->request->getVar('tombol');
        $outstanding = $departemen = $divisi = $kategori = $filename = [];

        // inisiasi
        $kodeSupplier = $namaSupplier = $kodeDivisi = $kodeDepartemen = $kodeKategoriBarang = 'All';
        $filterkd = $filternm = $filterdiv = $filterdep = $filterkat = '';

        // ambil data
        if(isset($_GET['kdsup'])) {if ($_GET['kdsup'] !=""){$kodeSupplier = $_GET['kdsup']; }}
        if ($kodeSupplier != "All" AND $kodeSupplier != "") {
            $filterkd = " AND sup_kodesupplier like '%$kodeSupplier%' ";
        }
        if(isset($_GET['nmsup'])) {if ($_GET['nmsup'] !=""){$namaSupplier = $_GET['nmsup']; }}
        if ($namaSupplier != "All" AND $namaSupplier != "") {
            $filternm = " AND sup_namasupplier like '%$namaSupplier%' ";
        }
        $namaSupplier = str_replace(" ","%",$namaSupplier);
        if(isset($_GET['divisi'])) {if ($_GET['divisi'] !=""){$kodeDivisi = $_GET['divisi']; }}
        if ($kodeDivisi != "All" AND $kodeDivisi != "") {
            $filterdiv = " AND prd_kodedivisi = '$kodeDivisi' ";
        }
        if(isset($_GET['dep'])) {if ($_GET['dep'] !=""){$kodeDepartemen = $_GET['dep']; }}
        if ($kodeDepartemen != "All" AND $kodeDepartemen != "") {
            $filterdep = " AND prd_kodedepartement = '$kodeDepartemen' ";
        }
        if(isset($_GET['kat'])) {if ($_GET['kat'] !=""){$kodeKategoriBarang = $_GET['kat']; }}
        if ($kodeKategoriBarang != "All" AND $kodeKategoriBarang != "") {
            $filterkat = " AND prd_kodedepartement || prd_kodekategoribarang = '$kodeKategoriBarang' ";
        }


        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $departemen = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $departemen = $departemen->getResultArray();

        $kategori = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $kategori = $kategori->getResultArray();

        $outstanding = $dbProd->query(
                "select prd_kodedivisi as DIV,
                prd_kodedepartement as DEP,
                prd_kodekategoribarang as KAT,
                prd_prdcd as PLU,
                prd_deskripsipanjang as DESKRIPSI,
                prd_kodetag as TAG,
                prd_unit as UNIT,
                prd_frac as FRAC,
                prd_avgcost as acost,
                prd_lastcost as lcost,
                st_saldoakhir as STOK,
                pkm_pkmt as PKMT,
                nvl(QTY_PO,0) as QTY_PO,
                nvl(RPH_PO,0) as RPH_PO,
                nvl(QTY_PB,0) as QTY_PB,
                nvl(RPH_PB,0) as RPH_PB,
                nvl(QTY_PO,0) + nvl(QTY_PB,0) as QTY_PO_PB,
                nvl(RPH_PO,0) + nvl(RPH_PB,0) as RPH_PO_PB,
                sup_kodesupplier || ' - ' || sup_namasupplier as SUPPLIER
                from tbmaster_prodmast
                left join (select * from tbmaster_stock where st_lokasi='01') on st_prdcd=prd_prdcd
                left join tbmaster_hargabeli on hgb_prdcd=prd_prdcd
                left join tbmaster_supplier on sup_kodesupplier=hgb_kodesupplier
                left join tbtr_salesbulanan on sls_Prdcd=prd_prdcd
                left join tbmaster_kkpkm on pkm_prdcd=prd_prdcd
                left join (select * from tbtr_monitoringplu where mpl_kodemonitoring in ('F1','F2','NF1','NF2')) on mpl_prdcd=prd_prdcd
                left join (select tpod_prdcd as PLUPO,
                  sum(tpod_qtypo) as QTY_PO,sum(tpod_gross) as RPH_PO
                    from tbtr_po_d 
                    left join tbtr_po_h on tpod_nopo=tpoh_nopo
                    where trunc(tpoh_tglpo)+tpoh_jwpb>=trunc(sysdate)
                    and tpod_recordid is null  group by tpod_prdcd) on PRD_PRDCD=plupo
                left join (select pbd_prdcd as PLUPB,
                  sum(pbd_qtypb) as QTY_PB,sum(pbd_gross) as RPH_PB
                    from tbtr_pb_d 
                    where pbd_nopo is null and trunc(pbd_create_dt)=trunc(sysdate)
                    group by pbd_prdcd) on PRD_PRDCD=PLUPB
                where (prd_kodecabang='25' or prd_kategoritoko='01') and prd_prdcd like '%0'
                and prd_kodetag not in ('N','X')
                and hgb_tipe='2'
                $filterkd
                $filternm
                $filterdiv
                $filterdep
                $filterkat
                order by DIV, DEP, KAT, DESKRIPSI"
            );
            $outstanding = $outstanding->getResultArray();

        $data = [
            'title' => 'Data PO Outstanding',
            'divisi' => $divisi,
            'departemen' => $departemen,
            'kategori' => $kategori,
            'kodeSupplier' => $kodeSupplier,
            'namaSupplier' => $namaSupplier,
            'kodeDivisi' => $kodeDivisi,
            'kodeDepartemen' => $kodeDepartemen,
            'kodeKategoriBarang' => $kodeKategoriBarang,
            'outstanding' => $outstanding,
        ];

        if($aksi == "btnview") {
            return view('/logistik/tampilpooutstanding',$data);
        } else if($aksi == "btnxls") {
            $filename = "PO Outstanding ".date('d M Y').".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        
            d($data);
            return view('logistik/tampilpooutstanding',$data);
        };
    }

    public function servicelevel() {
        $dbProd = db_connect("production");
        $departemen = $divisi = $kategori = [];

        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $departemen = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $departemen = $departemen->getResultArray();

        $kategori = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $kategori = $kategori->getResultArray();

        $data = [
            'title' => 'Service Level',
            'divisi' => $divisi,
            'departemen' => $departemen,
            'kategori' => $kategori,
        ];

        redirect()->to('servicelevel')->withInput();
        return view('/logistik/servicelevel',$data);
    }

    public function tampilsl() {
        $dbProd = db_connect("production");
        $departemen = $divisi = $kategori = $filename = $datasl = [];
        $lap = $this->request->getVar('jenisLaporan');
        $aksi = $this->request->getVar('tombol');
        $kodePLU = $kodeDivisi = $kodeDepartemen = $kodeKategoriBarang = $kodeSupplier = $namaSupplier = "All"; 
        $tanggalMulai = $tanggalSelesai = date("Ymd");
        $filterplu = $filterdiv = $filterdep = $filterkat = $filterkd = $filternm = $jlap = "";

        //ambil; variabel dr form
        if(isset($_GET['awal'])) {if ($_GET['awal'] !=""){$tanggalMulai = $_GET['awal']; }}
        if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tanggalSelesai = $_GET['akhir']; }}
        if(isset($_GET['plu'])) {if ($_GET['plu'] !=""){$kodePLU = $_GET['plu']; }}
        if ($kodePLU != "All" AND $kodePLU != "") {
            $kodePLU = substr('00000000' . $kodePLU, -7);
            $filterplu = " AND sl_prdcd_po = '$kodePLU' ";
        }
        if(isset($_GET['divisi'])) {if ($_GET['divisi'] !=""){$kodeDivisi = $_GET['divisi']; }}
        if ($kodeDivisi != "All" AND $kodeDivisi != "") {
            $filterdiv = " AND sl_div = '$kodeDivisi' ";
        }
        if(isset($_GET['dep'])) {if ($_GET['dep'] !=""){$kodeDepartemen = $_GET['dep']; }}
        if ($kodeDepartemen != "All" AND $kodeDepartemen != "") {
            $filterdep = " AND sl_dept = '$kodeDepartemen' ";
        }
        if(isset($_GET['kat'])) {if ($_GET['kat'] !=""){$kodeKategoriBarang = $_GET['kat']; }}
        if ($kodeKategoriBarang != "All" AND $kodeKategoriBarang != "") {
            $filterkat = " AND sl_dept || sl_katb = '$kodeKategoriBarang' ";
        }
        if(isset($_GET['kdsup'])) {if ($_GET['kdsup'] !=""){$kodeSupplier = $_GET['kdsup']; }}
        if ($kodeSupplier != "All" AND $kodeSupplier != "") {
            $filterkd = " AND sl_kode_supplier like '%$kodeSupplier%' ";
        }
	    if(isset($_GET['nmsup'])) {if ($_GET['nmsup'] !=""){$namaSupplier = $_GET['nmsup']; }}
        if ($namaSupplier != "All" AND $namaSupplier != "") {
            $filternm = " AND sl_nama_supplier like '%$namaSupplier%' ";
        }

        // inisiasi div,dep,kat 
        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $departemen = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $departemen = $departemen->getResultArray();

        $kategori = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $kategori = $kategori->getResultArray();

        //inisiasi query
        $bln_01 = date('m', strtotime('-3 month')) ;
        $bln_02 = date('m', strtotime('-2 month')) ;
        $bln_03 = date('m', strtotime('-1 month')) ;

        $viewSalesPerDay = " (SELECT sls_prdcd                   AS spd_prdcd,
            Nvl(sls_qty_" . $bln_01  .", 0)                      AS spd_qty_1,
            Nvl(sls_qty_" . $bln_02  .", 0)                      AS spd_qty_2,
            Nvl(sls_qty_" . $bln_03  .", 0)                      AS spd_qty_3,
            Trunc(( Nvl(sls_qty_" . $bln_01  .", 0) + Nvl(sls_qty_" . $bln_02  .", 0) + Nvl(sls_qty_" . $bln_03  .", 0) ) / 90, 5) AS spd_qty,
            Nvl(sls_rph_" . $bln_01  .", 0)                      AS spd_rph_1,
            Nvl(sls_rph_" . $bln_02  .", 0)                      AS spd_rph_2,
            Nvl(sls_rph_" . $bln_03  .", 0)                      AS spd_rph_3,
            Trunc(( Nvl(sls_rph_" . $bln_01  .", 0) + Nvl(sls_rph_" . $bln_02  .", 0) + Nvl(sls_rph_" . $bln_03  .", 0) ) / 90, 5) AS spd_rph
            FROM   tbtr_salesbulanan   ) ";

        $viewServiceLevel = "(SELECT po.tpod_nopo                    AS sl_nomor_po,
            poh.tpoh_tglpo                                           AS sl_tanggal_po,
            mst.mstd_nodoc                                           AS sl_nomor_bpb,
            mst.mstd_tgldoc                                          AS sl_tanggal_bpb,
            prd.prd_kodedivisi                                       AS sl_div,
            dvs.div_namadivisi                                       AS sl_nmdiv,
            prd.prd_kodedepartement                                  AS sl_dept,
            dpt.dep_namadepartement                                  AS sl_nmdept,
            prd.prd_kodekategoribarang                               AS sl_katb,
            kgr.kat_namakategori                                     AS sl_nmkatb,
            po.tpod_prdcd                                            AS sl_prdcd_po,
            mst.mstd_prdcd                                           AS sl_prdcd_bpb,
            prd.prd_deskripsipanjang                                 AS sl_nama_barang,
            prd.prd_unit                                             AS sl_unit,
            prd.prd_frac                                             AS sl_frac,
            NVL(prd.prd_kodetag,' ')                                 AS sl_tag,
            PO.TPOD_QTYPO                                            AS sl_qty_po,
            po.tpod_gross + po.tpod_ppn                              AS sl_rph_po,
            NVL(MST.MSTD_QTY,0)                                      AS sl_qty_bpb,
            NVL(mst.mstd_gross-mst.mstd_discrph + mst.mstd_ppnrph,0) AS sl_rph_bpb,
            poh.tpoh_kodesupplier                                    AS sl_kode_supplier,
            sup.sup_namasupplier                                     AS sl_nama_supplier,
            spd.spd_qty_1                                            AS sl_spd_qty_1,
            spd.spd_qty_2                                            AS sl_spd_qty_2,
            spd.spd_qty_3                                            AS sl_spd_qty_3,
            stk.st_sales                                             AS sl_sales_bulan_ini,
            stk.st_saldoakhir                                        AS sl_stock_qty,
            stk.st_lastcost                                          AS sl_lastcost,
            stk.st_avgcost                                           AS sl_avgcost
            FROM tbtr_po_d po
            LEFT JOIN tbtr_mstran_d mst
            ON po.tpod_prdcd = mst.mstd_prdcd
            AND po.tpod_nopo = mst.mstd_nopo
            LEFT JOIN tbtr_po_h poh
            ON po.tpod_nopo = poh.tpoh_nopo
            LEFT JOIN tbmaster_prodmast prd
            ON po.tpod_prdcd = prd.prd_prdcd
            LEFT JOIN tbmaster_supplier sup
            ON poh.tpoh_kodesupplier = sup.sup_kodesupplier
            LEFT JOIN " . $viewSalesPerDay . "spd
            ON po.tpod_prdcd = spd.spd_prdcd
            LEFT JOIN
            (SELECT * FROM tbmaster_stock WHERE st_lokasi = '01'
            ) stk
            ON po.tpod_prdcd         = stk.st_prdcd
            LEFT JOIN tbmaster_divisi dvs 
            on prd.prd_kodedivisi = dvs.div_kodedivisi 
            LEFT JOIN tbmaster_departement dpt 
            on prd.prd_kodedepartement = dpt.dep_kodedepartement
            LEFT JOIN tbmaster_kategori kgr 
            on prd.prd_kodedepartement = kgr.kat_kodedepartement
            and prd.prd_kodekategoribarang = kgr.kat_kodekategori
            WHERE mst.mstd_recordid IS NULL
            AND (po.tpod_recordid   IS NULL
            OR po.tpod_recordid      = '2')) ";

        if($lap == "1") {
            $jlap = "Laporan per Divisi";
            $datasl = $dbProd->query(
                "SELECT sl_div   AS sl_div,
                sl_nmdiv   AS sl_nmdiv,
                COUNT(distinct(sl_nomor_po))   AS sl_nomor_po,
                COUNT(distinct(sl_nomor_bpb))   AS sl_nomor_bpb,
                COUNT(sl_prdcd_po)    AS sl_prdcd_po,
                COUNT(sl_prdcd_bpb)   AS sl_prdcd_bpb,
                SUM(sl_qty_po)        AS sl_qty_po,
                SUM(sl_qty_bpb)       AS sl_qty_bpb,
                SUM(sl_rph_po)        AS sl_rph_po,
                SUM(sl_rph_bpb)       AS sl_rph_bpb
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_div, sl_nmdiv
                ORDER BY sl_div, sl_nmdiv"
            );
            $datasl = $datasl->getResultArray();
        } else if($lap == "2") {
            $jlap = "Laporan per Departemen";
            $datasl = $dbProd->query(
                "SELECT sl_div   AS sl_div,
                sl_nmdiv   AS sl_nmdiv,
                sl_dept   AS sl_dept,
                sl_nmdept   AS sl_nmdept,
                COUNT(distinct(sl_nomor_po))   AS sl_nomor_po,
                COUNT(distinct(sl_nomor_bpb))   AS sl_nomor_bpb,
                COUNT(sl_prdcd_po)    AS sl_prdcd_po,
                COUNT(sl_prdcd_bpb)   AS sl_prdcd_bpb,
                SUM(sl_qty_po)        AS sl_qty_po,
                SUM(sl_qty_bpb)       AS sl_qty_bpb,
                SUM(sl_rph_po)        AS sl_rph_po,
                SUM(sl_rph_bpb)       AS sl_rph_bpb
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_div, sl_nmdiv, sl_dept, sl_nmdept
                ORDER BY sl_div, sl_nmdiv, sl_dept, sl_nmdept"
            );
            $datasl = $datasl->getResultArray();
        } else if($lap == "3") {
            $jlap = "Laporan per Kategori";
            $datasl = $dbProd->query(
                "SELECT sl_div   AS sl_div,
                sl_nmdiv   AS sl_nmdiv,
                sl_dept   AS sl_dept,
                sl_nmdept   AS sl_nmdept,
                sl_katb   AS sl_katb,
                sl_nmkatb   AS sl_nmkatb,
                COUNT(distinct(sl_nomor_po))   AS sl_nomor_po,
                COUNT(distinct(sl_nomor_bpb))   AS sl_nomor_bpb,
                COUNT(sl_prdcd_po)    AS sl_prdcd_po,
                COUNT(sl_prdcd_bpb)   AS sl_prdcd_bpb,
                SUM(sl_qty_po)        AS sl_qty_po,
                SUM(sl_qty_bpb)       AS sl_qty_bpb,
                SUM(sl_rph_po)        AS sl_rph_po,
                SUM(sl_rph_bpb)       AS sl_rph_bpb
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_div, sl_nmdiv, sl_dept, sl_nmdept, sl_katb, sl_nmkatb
                ORDER BY sl_div, sl_nmdiv, sl_dept, sl_nmdept, sl_katb, sl_nmkatb"
            );
            $datasl = $datasl->getResultArray();
        } else if($lap == "4") {
            $jlap = "Laporan per Produk";
            $datasl = $dbProd->query(
                "SELECT MIN(sl_div)        AS sl_div,
                MIN(sl_dept)            AS sl_dept,
                MIN(sl_katb)            AS sl_katb,
                sl_prdcd_po             AS sl_prdcd_po,
                sl_nama_barang          AS sl_nama_barang,
                sl_unit                 AS sl_unit,
                sl_frac                 AS sl_frac,
                sl_tag                  AS sl_tag,
                COUNT(sl_nomor_po)      AS sl_nomor_po,
                COUNT(sl_nomor_bpb)     AS sl_nomor_bpb,
                SUM(sl_qty_po)          AS sl_qty_po,
                SUM(sl_rph_po)          AS sl_rph_po,
                SUM(sl_qty_bpb)         AS sl_qty_bpb,
                SUM(sl_rph_bpb)         AS sl_rph_bpb,
                MIN(sl_kode_supplier)   AS sl_kode_supplier,
                MIN(sl_nama_supplier)   AS sl_nama_supplier,
                MIN(sl_spd_qty_1)       AS sl_spd_qty_1,
                MIN(sl_spd_qty_2)       AS sl_spd_qty_2,
                MIN(sl_spd_qty_3)       AS sl_spd_qty_3,
                MIN(sl_sales_bulan_ini) AS sl_sales_bulan_ini,
                MIN(sl_stock_qty)       AS sl_stock_qty,
                MIN(sl_lastcost)        AS sl_lastcost,
                MIN(sl_avgcost)         AS sl_avgcost
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_prdcd_po , sl_nama_barang, sl_unit, sl_frac, sl_tag
                ORDER BY sl_div , sl_dept, sl_katb, sl_nama_barang"
            );
            $datasl = $datasl->getResultArray();
        } else if($lap == "4B") {
            $jlap = "Laporan per Produk Detail";
            $datasl = $dbProd->query(
                "SELECT * 
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                ORDER BY sl_div , sl_dept, sl_katb, sl_nama_barang"
            );
            $datasl = $datasl->getResultArray();
        } else if($lap == "5") {
            $jlap = "Laporan per Supplier";
            $datasl = $dbProd->query(
                "SELECT sl_kode_supplier   AS sl_kode_supplier,
                sl_nama_supplier      AS sl_nama_supplier,
                COUNT(distinct(sl_nomor_po))   AS sl_nomor_po,
                COUNT(distinct(sl_nomor_bpb))   AS sl_nomor_bpb,
                COUNT(sl_prdcd_po)    AS sl_prdcd_po,
                COUNT(sl_prdcd_bpb)   AS sl_prdcd_bpb,
                SUM(sl_qty_po)        AS sl_qty_po,
                SUM(sl_qty_bpb)       AS sl_qty_bpb,
                SUM(sl_rph_po)        AS sl_rph_po,
                SUM(sl_rph_bpb)       AS sl_rph_bpb
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_kode_supplier, sl_nama_supplier
                ORDER BY sl_kode_supplier"
            );
            $datasl = $datasl->getResultArray();
        } else if($lap == "6") {
            $jlap = "Laporan per Kode Tag";
            $datasl = $dbProd->query(
                "SELECT sl_tag   AS sl_tag,
                COUNT(distinct(sl_nomor_po))   AS sl_nomor_po,
                COUNT(distinct(sl_nomor_bpb))   AS sl_nomor_bpb,
                COUNT(sl_prdcd_po)    AS sl_prdcd_po,
                COUNT(sl_prdcd_bpb)   AS sl_prdcd_bpb,
                SUM(sl_qty_po)        AS sl_qty_po,
                SUM(sl_qty_bpb)       AS sl_qty_bpb,
                SUM(sl_rph_po)        AS sl_rph_po,
                SUM(sl_rph_bpb)       AS sl_rph_bpb
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_tag
                ORDER BY sl_tag"
            );
            $datasl = $datasl->getResultArray();
        };

        $data = [
            'title' => 'Data Service Level',
            'datasl' => $datasl,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'kategori' => $kategori,
            'lap' => $lap,
            'jlap' => $jlap,
            'kodePLU' => $kodePLU,
            'kodeDivisi' => $kodeDivisi,
            'kodeDepartemen' => $kodeDepartemen,
            'kodeKategoriBarang' => $kodeKategoriBarang,
            'kodeSupplier' => $kodeSupplier,
            'namaSupplier' => $namaSupplier,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ];

        if($aksi == 'btnxls') {
            $filename = "Service Level [". $jlap. "] ".date('d M Y').".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        
            return view('logistik/tampilsl',$data);
        };
        
        return view('/logistik/tampilsl',$data);
    }

    public function servicelevelbo() {
        $dbProd = db_connect("production");
        $departemen = $divisi = $kategori = [];

        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $departemen = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $departemen = $departemen->getResultArray();

        $kategori = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $kategori = $kategori->getResultArray();

        $data = [
            'title' => 'Service Level 3 Periode',
            'divisi' => $divisi,
            'departemen' => $departemen,
            'kategori' => $kategori,
        ];

        redirect()->to('servicelevelbo')->withInput();
        return view('/logistik/servicelevelbo',$data);
    }

    public function tampilslbo() {
        $dbProd = db_connect("production");
        $departemen = $divisi = $kategori = $filename = $dataslbo = [];
        $lap = $this->request->getVar('jenisLaporan');
        $aksi = $this->request->getVar('tombol');
        $kodePLU = $kodeDivisi = $kodeDepartemen = $kodeKategoriBarang = $kodeSupplier = $namaSupplier = "All"; 
        $tanggalMulai1 = $tanggalSelesai1 = $tanggalMulai2 = $tanggalSelesai2 = $tanggalMulai3 = $tanggalSelesai3 = date("Ymd");
        $filterplu = $filterdiv = $filterdep = $filterkat = $filterkd = $filternm = $jlap = "";

        //ambil; variabel dr form
        if(isset($_GET['awal1'])) {if ($_GET['awal1'] !=""){$tanggalMulai1 = $_GET['awal1']; }}
        if(isset($_GET['akhir1'])) {if ($_GET['akhir1'] !=""){$tanggalSelesai1 = $_GET['akhir1']; }}
        if(isset($_GET['awal2'])) {if ($_GET['awal2'] !=""){$tanggalMulai2 = $_GET['awal2']; }}
        if(isset($_GET['akhir2'])) {if ($_GET['akhir2'] !=""){$tanggalSelesai2 = $_GET['akhir2']; }}
        if(isset($_GET['awal3'])) {if ($_GET['awal3'] !=""){$tanggalMulai3 = $_GET['awal3']; }}
        if(isset($_GET['akhir3'])) {if ($_GET['akhir3'] !=""){$tanggalSelesai3 = $_GET['akhir3']; }}
        if(isset($_GET['plu'])) {if ($_GET['plu'] !=""){$kodePLU = $_GET['plu']; }}
        if ($kodePLU != "All" AND $kodePLU != "") {
            $kodePLU = substr('00000000' . $kodePLU, -7);
            $filterplu = " AND sl_prdcd_po = '$kodePLU' ";
        }
        if(isset($_GET['divisi'])) {if ($_GET['divisi'] !=""){$kodeDivisi = $_GET['divisi']; }}
        if ($kodeDivisi != "All" AND $kodeDivisi != "") {
            $filterdiv = " AND sl_div = '$kodeDivisi' ";
        }
        if(isset($_GET['dep'])) {if ($_GET['dep'] !=""){$kodeDepartemen = $_GET['dep']; }}
        if ($kodeDepartemen != "All" AND $kodeDepartemen != "") {
            $filterdep = " AND sl_dept = '$kodeDepartemen' ";
        }
        if(isset($_GET['kat'])) {if ($_GET['kat'] !=""){$kodeKategoriBarang = $_GET['kat']; }}
        if ($kodeKategoriBarang != "All" AND $kodeKategoriBarang != "") {
            $filterkat = " AND sl_dept || sl_katb = '$kodeKategoriBarang' ";
        }
        if(isset($_GET['kdsup'])) {if ($_GET['kdsup'] !=""){$kodeSupplier = $_GET['kdsup']; }}
        if ($kodeSupplier != "All" AND $kodeSupplier != "") {
            $filterkd = " AND sl_kode_supplier like '%$kodeSupplier%' ";
        }
	    if(isset($_GET['nmsup'])) {if ($_GET['nmsup'] !=""){$namaSupplier = $_GET['nmsup']; }}
        if ($namaSupplier != "All" AND $namaSupplier != "") {
            $filternm = " AND sl_nama_supplier like '%$namaSupplier%' ";
        }

        // inisiasi div,dep,kat 
        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $departemen = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $departemen = $departemen->getResultArray();

        $kategori = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $kategori = $kategori->getResultArray();

        //inisiasi query
        $bln_01 = date('m', strtotime('-3 month')) ;
        $bln_02 = date('m', strtotime('-2 month')) ;
        $bln_03 = date('m', strtotime('-1 month')) ;

        $viewSalesPerDay = " (SELECT sls_prdcd                   AS spd_prdcd,
            Nvl(sls_qty_" . $bln_01  .", 0)                      AS spd_qty_1,
            Nvl(sls_qty_" . $bln_02  .", 0)                      AS spd_qty_2,
            Nvl(sls_qty_" . $bln_03  .", 0)                      AS spd_qty_3,
            Trunc(( Nvl(sls_qty_" . $bln_01  .", 0) + Nvl(sls_qty_" . $bln_02  .", 0) + Nvl(sls_qty_" . $bln_03  .", 0) ) / 90, 5) AS spd_qty,
            Nvl(sls_rph_" . $bln_01  .", 0)                      AS spd_rph_1,
            Nvl(sls_rph_" . $bln_02  .", 0)                      AS spd_rph_2,
            Nvl(sls_rph_" . $bln_03  .", 0)                      AS spd_rph_3,
            Trunc(( Nvl(sls_rph_" . $bln_01  .", 0) + Nvl(sls_rph_" . $bln_02  .", 0) + Nvl(sls_rph_" . $bln_03  .", 0) ) / 90, 5) AS spd_rph
            FROM   tbtr_salesbulanan   ) ";

        $viewServiceLevel = "(SELECT po.tpod_nopo                    AS sl_nomor_po,
            poh.tpoh_tglpo                                           AS sl_tanggal_po,
            mst.mstd_nodoc                                           AS sl_nomor_bpb,
            mst.mstd_tgldoc                                          AS sl_tanggal_bpb,
            prd.prd_kodedivisi                                       AS sl_div,
            dvs.div_namadivisi                                       AS sl_nmdiv,
            prd.prd_kodedepartement                                  AS sl_dept,
            dpt.dep_namadepartement                                  AS sl_nmdept,
            prd.prd_kodekategoribarang                               AS sl_katb,
            kgr.kat_namakategori                                     AS sl_nmkatb,
            po.tpod_prdcd                                            AS sl_prdcd_po,
            mst.mstd_prdcd                                           AS sl_prdcd_bpb,
            prd.prd_deskripsipanjang                                 AS sl_nama_barang,
            prd.prd_unit                                             AS sl_unit,
            prd.prd_frac                                             AS sl_frac,
            NVL(prd.prd_kodetag,' ')                                 AS sl_tag,
            PO.TPOD_QTYPO                                            AS sl_qty_po,
            po.tpod_gross + po.tpod_ppn                              AS sl_rph_po,
            NVL(MST.MSTD_QTY,0)                                      AS sl_qty_bpb,
            NVL(mst.mstd_gross-mst.mstd_discrph + mst.mstd_ppnrph,0) AS sl_rph_bpb,
            poh.tpoh_kodesupplier                                    AS sl_kode_supplier,
            sup.sup_namasupplier                                     AS sl_nama_supplier,
            spd.spd_qty_1                                            AS sl_spd_qty_1,
            spd.spd_qty_2                                            AS sl_spd_qty_2,
            spd.spd_qty_3                                            AS sl_spd_qty_3,
            stk.st_sales                                             AS sl_sales_bulan_ini,
            stk.st_saldoakhir                                        AS sl_stock_qty,
            stk.st_lastcost                                          AS sl_lastcost,
            stk.st_avgcost                                           AS sl_avgcost
            FROM tbtr_po_d po
            LEFT JOIN tbtr_mstran_d mst
            ON po.tpod_prdcd = mst.mstd_prdcd
            AND po.tpod_nopo = mst.mstd_nopo
            LEFT JOIN tbtr_po_h poh
            ON po.tpod_nopo = poh.tpoh_nopo
            LEFT JOIN tbmaster_prodmast prd
            ON po.tpod_prdcd = prd.prd_prdcd
            LEFT JOIN tbmaster_supplier sup
            ON poh.tpoh_kodesupplier = sup.sup_kodesupplier
            LEFT JOIN " . $viewSalesPerDay . "spd
            ON po.tpod_prdcd = spd.spd_prdcd
            LEFT JOIN
            (SELECT * FROM tbmaster_stock WHERE st_lokasi = '01'
            ) stk
            ON po.tpod_prdcd         = stk.st_prdcd
            LEFT JOIN tbmaster_divisi dvs 
            on prd.prd_kodedivisi = dvs.div_kodedivisi 
            LEFT JOIN tbmaster_departement dpt 
            on prd.prd_kodedepartement = dpt.dep_kodedepartement
            LEFT JOIN tbmaster_kategori kgr 
            on prd.prd_kodedepartement = kgr.kat_kodedepartement
            and prd.prd_kodekategoribarang = kgr.kat_kodekategori
            WHERE mst.mstd_recordid IS NULL
            AND (po.tpod_recordid   IS NULL
            OR po.tpod_recordid      = '2')) ";

        if($lap == "1") {
            $jlap = "Laporan per Divisi";
            $dataslbo = $dbProd->query(
                "SELECT sl_div   AS sl_div,
                sl_nmdiv   AS sl_nmdiv,
                COUNT(distinct(sl_nomor_po))   AS sl_nomor_po,
				COUNT(distinct(sl_nomor_bpb))   AS sl_nomor_bpb,
				COUNT(sl_prdcd_po)    AS sl_prdcd_po,
				COUNT(sl_prdcd_bpb)   AS sl_prdcd_bpb,
				SUM(sl_qty_po)        AS sl_qty_po,
				SUM(sl_qty_bpb)       AS sl_qty_bpb,
				SUM(sl_rph_po)        AS sl_rph_po,
				SUM(sl_rph_bpb)       AS sl_rph_bpb
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_div, sl_nmdiv
                ORDER BY sl_div, sl_nmdiv"
            );
            $dataslbo = $dataslbo->getResultArray();
        } else if($lap == "2") {
            $jlap = "Laporan per Departemen";
            $dataslbo = $dbProd->query(
                "SELECT sl_div   AS sl_div,
                sl_nmdiv   AS sl_nmdiv,
                sl_dept   AS sl_dept,
                sl_nmdept   AS sl_nmdept,
                COUNT(distinct(sl_nomor_po))   AS sl_nomor_po,
                COUNT(distinct(sl_nomor_bpb))   AS sl_nomor_bpb,
                COUNT(sl_prdcd_po)    AS sl_prdcd_po,
                COUNT(sl_prdcd_bpb)   AS sl_prdcd_bpb,
                SUM(sl_qty_po)        AS sl_qty_po,
                SUM(sl_qty_bpb)       AS sl_qty_bpb,
                SUM(sl_rph_po)        AS sl_rph_po,
                SUM(sl_rph_bpb)       AS sl_rph_bpb
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_div, sl_nmdiv, sl_dept, sl_nmdept
                ORDER BY sl_div, sl_nmdiv, sl_dept, sl_nmdept"
            );
            $dataslbo = $dataslbo->getResultArray();
        } else if($lap == "3") {
            $jlap = "Laporan per Kategori";
            $dataslbo = $dbProd->query(
                "SELECT sl_div   AS sl_div,
                sl_nmdiv   AS sl_nmdiv,
                sl_dept   AS sl_dept,
                sl_nmdept   AS sl_nmdept,
                sl_katb   AS sl_katb,
                sl_nmkatb   AS sl_nmkatb,
                COUNT(distinct(sl_nomor_po))   AS sl_nomor_po,
                COUNT(distinct(sl_nomor_bpb))   AS sl_nomor_bpb,
                COUNT(sl_prdcd_po)    AS sl_prdcd_po,
                COUNT(sl_prdcd_bpb)   AS sl_prdcd_bpb,
                SUM(sl_qty_po)        AS sl_qty_po,
                SUM(sl_qty_bpb)       AS sl_qty_bpb,
                SUM(sl_rph_po)        AS sl_rph_po,
                SUM(sl_rph_bpb)       AS sl_rph_bpb
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_div, sl_nmdiv, sl_dept, sl_nmdept, sl_katb, sl_nmkatb
                ORDER BY sl_div, sl_nmdiv, sl_dept, sl_nmdept, sl_katb, sl_nmkatb"
            );
            $dataslbo = $dataslbo->getResultArray();
        } else if($lap == "4") {
            $jlap = "Laporan per Produk";
            $dataslbo = $dbProd->query(
                "SELECT MIN(sl_div)        AS sl_div,
                MIN(sl_dept)            AS sl_dept,
                MIN(sl_katb)            AS sl_katb,
                sl_prdcd_po             AS sl_prdcd_po,
                sl_nama_barang          AS sl_nama_barang,
                sl_unit                 AS sl_unit,
                sl_frac                 AS sl_frac,
                sl_tag                  AS sl_tag,          
                
                -- periode 1
                COUNT(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd') THEN sl_nomor_po END)          AS sl_nomor_po,				  
                COUNT(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd') THEN sl_nomor_bpb END)          AS sl_nomor_bpb,

                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd') THEN sl_qty_po ELSE 0 END)          AS sl_qty_po,				  
                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd') THEN sl_rph_po ELSE 0 END)          AS sl_rph_po,

                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd') THEN sl_qty_bpb ELSE 0 END)          AS sl_qty_bpb,				  
                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd') THEN sl_rph_bpb ELSE 0 END)          AS sl_rph_bpb,

                -- periode 2
                COUNT(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd') THEN sl_nomor_po END)          AS sl_nomor_po2,				  
                COUNT(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd') THEN sl_nomor_bpb END)          AS sl_nomor_bpb2,

                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd') THEN sl_qty_po ELSE 0 END)          AS sl_qty_po2,				  
                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd') THEN sl_rph_po ELSE 0 END)          AS sl_rph_po2,

                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd') THEN sl_qty_bpb ELSE 0 END)          AS sl_qty_bpb2,				  
                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd') THEN sl_rph_bpb ELSE 0 END)          AS sl_rph_bpb2,

              -- periode 3
                COUNT(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd') THEN sl_nomor_po END)          AS sl_nomor_po3,
                COUNT(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd') THEN sl_nomor_bpb END)          AS sl_nomor_bpb3,

                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd') THEN sl_qty_po ELSE 0 END)          AS sl_qty_po3,				  
                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd') THEN sl_rph_po ELSE 0 END)          AS sl_rph_po3,

                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd') THEN sl_qty_bpb ELSE 0 END)          AS sl_qty_bpb3,				  
                SUM(CASE WHEN trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd') THEN sl_rph_bpb ELSE 0 END)          AS sl_rph_bpb3,

                MIN(sl_kode_supplier)   AS sl_kode_supplier,
                MIN(sl_nama_supplier)   AS sl_nama_supplier,
                MIN(sl_spd_qty_1)       AS sl_spd_qty_1,
                MIN(sl_spd_qty_2)       AS sl_spd_qty_2,
                MIN(sl_spd_qty_3)       AS sl_spd_qty_3,
                MIN(sl_sales_bulan_ini) AS sl_sales_bulan_ini,
                MIN(sl_stock_qty)       AS sl_stock_qty,
                MIN(sl_lastcost)        AS sl_lastcost,
                MIN(sl_avgcost)         AS sl_avgcost
                FROM " . $viewServiceLevel  . "
                WHERE (trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd'))
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_prdcd_po , sl_nama_barang, sl_unit, sl_frac, sl_tag
                ORDER BY sl_div , sl_dept, sl_katb, sl_nama_barang"
            );
            $dataslbo = $dataslbo->getResultArray();
        } else if($lap == "4B") {
            $jlap = "Laporan per Produk Detail";
            $dataslbo = $dbProd->query(
                "SELECT * 
                FROM " . $viewServiceLevel  . "
                WHERE (trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd'))
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                ORDER BY sl_div , sl_dept, sl_katb, sl_nama_barang"
            );
            $dataslbo = $dataslbo->getResultArray();
        } else if($lap == "5") {
            $jlap = "Laporan per Supplier";
            $dataslbo = $dbProd->query(
                "SELECT sl_kode_supplier   AS sl_kode_supplier,
                sl_nama_supplier      AS sl_nama_supplier,
                COUNT(distinct(sl_nomor_po))   AS sl_nomor_po,
                COUNT(distinct(sl_nomor_bpb))   AS sl_nomor_bpb,
                COUNT(sl_prdcd_po)    AS sl_prdcd_po,
                COUNT(sl_prdcd_bpb)   AS sl_prdcd_bpb,
                SUM(sl_qty_po)        AS sl_qty_po,
                SUM(sl_qty_bpb)       AS sl_qty_bpb,
                SUM(sl_rph_po)        AS sl_rph_po,
                SUM(sl_rph_bpb)       AS sl_rph_bpb
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_kode_supplier, sl_nama_supplier
                ORDER BY sl_kode_supplier"
            );
            $dataslbo = $dataslbo->getResultArray();
        } else if($lap == "6") {
            $jlap = "Laporan per Kode Tag";
            $dataslbo = $dbProd->query(
                "SELECT sl_tag   AS sl_tag,
                COUNT(distinct(sl_nomor_po))   AS sl_nomor_po,
                COUNT(distinct(sl_nomor_bpb))   AS sl_nomor_bpb,
                COUNT(sl_prdcd_po)    AS sl_prdcd_po,
                COUNT(sl_prdcd_bpb)   AS sl_prdcd_bpb,
                SUM(sl_qty_po)        AS sl_qty_po,
                SUM(sl_qty_bpb)       AS sl_qty_bpb,
                SUM(sl_rph_po)        AS sl_rph_po,
                SUM(sl_rph_bpb)       AS sl_rph_bpb
                FROM " . $viewServiceLevel  . "
                WHERE trunc(sl_tanggal_po) between to_date('$tanggalMulai1','yyyy-mm-dd') and to_date('$tanggalSelesai1','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai2','yyyy-mm-dd') and to_date('$tanggalSelesai2','yyyy-mm-dd')
                or trunc(sl_tanggal_po) between to_date('$tanggalMulai3','yyyy-mm-dd') and to_date('$tanggalSelesai3','yyyy-mm-dd')
                $filterplu
                $filterdiv
                $filterdep
                $filterkat
                $filterkd
                $filternm
                GROUP BY sl_tag
                ORDER BY sl_tag"
            );
            $dataslbo = $dataslbo->getResultArray();
        };

        $data = [
            'title' => 'Data Service Level',
            'dataslbo' => $dataslbo,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'kategori' => $kategori,
            'lap' => $lap,
            'jlap' => $jlap,
            'kodePLU' => $kodePLU,
            'kodeDivisi' => $kodeDivisi,
            'kodeDepartemen' => $kodeDepartemen,
            'kodeKategoriBarang' => $kodeKategoriBarang,
            'kodeSupplier' => $kodeSupplier,
            'namaSupplier' => $namaSupplier,
            'tanggalMulai1' => $tanggalMulai1,
            'tanggalSelesai1' => $tanggalSelesai1,
            'tanggalMulai2' => $tanggalMulai2,
            'tanggalSelesai2' => $tanggalSelesai2,
            'tanggalMulai3' => $tanggalMulai3,
            'tanggalSelesai3' => $tanggalSelesai3,
        ];

        if($aksi == 'btnxls') {
            $filename = "Service Level 3 Periode [". $jlap. "].xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        
            return view('logistik/tampilslbo',$data);
        };
        
        return view('/logistik/tampilslbo',$data);
    }

    public function kkhpbm() {
        $dbProd = db_connect('production');
        $kkhpbm = [];

        $hari = date('D'); 
		$bln_01 = date('m', strtotime('-3 month')) ;
        $bln_02 = date('m', strtotime('-2 month')) ;
        $bln_03 = date('m', strtotime('-1 month')) ;

        switch($hari){
            case 'Sun':
                $h = '1';
            break;
     
            case 'Mon':			
                $h = '2';
            break;
     
            case 'Tue':
                $h = '3';
            break;
     
            case 'Wed':
                $h = '4';
            break;
     
            case 'Thu':
                $h = '5';
            break;
     
            case 'Fri':
                $h = '6';
            break;
            case 'Sat':
                $h = '7';
            break;
        }
        
        switch($hari){
            case 'Sun':
                $hari_ini = "SENIN";
            break;
     
            case 'Mon':			
                $hari_ini = "SELASA";
            break;
     
            case 'Tue':
                $hari_ini = "RABU";
            break;
     
            case 'Wed':
                $hari_ini = "KAMIS";
            break;
     
            case 'Thu':
                $hari_ini = "JUMAT";
            break;
     
            case 'Fri':
                $hari_ini = "SABTU";
            break;
            case 'Sat':
                $hari_ini = "MINGGU";
            break;
        }

        $kkhpbm = $dbProd->query(
            "SELECT * FROM (SELECT KOD_SUP,SUPPLIER,DIV,DEP,KAT,PLU,DESK,
            JUAL,BELI,BULAN1,BULAN2,BULAN3,HARIS_SALES,
            AVG_BULAN,AVG_HARI,STOCK_AWAL,STOCK_AKHIR,
            JML_PO,QTY_PO,LT,MINOR,RUPIAH,PKMT,MAXPALET_CTN,
            MAXPALET_PCS,HARI_KUNJ,HARI_KUNJ2,HARI_PB,
            CASE 
                WHEN CAST(MOD(HARI_PB,6) AS CHAR) = '0' THEN CAST(HARI_PB/6 AS CHAR) 
                ELSE CAST(MOD(HARI_PB,6) AS CHAR) END AS HASIL
            FROM (SELECT DISTINCT HGB_KODESUPPLIER KOD_SUP,
            SUP_NAMASUPPLIER SUPPLIER,
            PRD_KODEDIVISI DIV,
            PRD_KODEDEPARTEMENT DEP,
            PRD_KODEKATEGORIBARANG KAT,
            TPOD_PRDCD PLU,
            PRD_DESKRIPSIPANJANG DESK,
            PRD_KODESATUANJUAL2||'/'||PRD_ISISATUANJUAL2 JUAL,
            PRD_SATUANBELI||'/'||PRD_ISIBELI BELI,
            NVL(SLS1,0) BULAN1,
            NVL(SLS2,0) BULAN2,
            NVL(SLS3,0) BULAN3,
            (NVL(HARI1,0)+NVL(HARI2,0)+NVL(HARI3,0)) HARIS_SALES,
            ROUND((NVL(SLS1,0)+NVL(SLS2,0)+NVL(SLS3,0))/3,2) AVG_BULAN,
            CASE 
              WHEN (NVL(HARI1,0)+NVL(HARI2,0)+NVL(HARI3,0)) = 0
              THEN ROUND((NVL(SLS1,0)+NVL(SLS2,0)+NVL(SLS3,0))/1)
              ELSE ROUND((NVL(SLS1,0)+NVL(SLS2,0)+NVL(SLS3,0))/(NVL(HARI1,0)+NVL(HARI2,0)+NVL(HARI3,0)))
              END AS AVG_HARI,
            --ROUND((NVL(SLS1,0)+NVL(SLS2,0)+NVL(SLS3,0))/(NVL(HARI1,0)+NVL(HARI2,0)+NVL(HARI3,0))) AVG_HARI,
            ST_SALDOAWAL STOCK_AWAL,
            ST_SALDOAKHIR STOCK_AKHIR,
            NVL(JML_PO,0) JML_PO,
            NVL(PO_OUT,0) QTY_PO,
            PKM_LEADTIME LT,
            PKM_MINORDER MINOR,
            RUPIAH,
            PKM_PKMT PKMT,
            MPT_MAXQTY MAXPALET_CTN,
            (MPT_MAXQTY*PRD_FRAC) MAXPALET_PCS,
            HARI_KUNJ,HARI_KUNJ2,
            ABS((NVL(PKM_LEADTIME,0)+ '$h' + 2)-6) HARI_PB 
            FROM TBMASTER_STOCK 
            LEFT JOIN ( SELECT HGB_PRDCD, HGB_KODESUPPLIER,SUP_NAMASUPPLIER,SUP_MINRPH RUPIAH,
                          case 
                            WHEN SUP_HARIKUNJUNGAN = 'YYYYYYY' THEN 'MINGGU-SENIN-SELASA-RABU-KAMIS-JUMAT-SABTU'
                            when SUP_HARIKUNJUNGAN = 'YYYYYY ' then 'MINGGU-SENIN-SELASA-RABU-KAMIS-JUMAT'
                            when SUP_HARIKUNJUNGAN = ' YYYYYY' then 'SENIN-SELASA-RABU-KAMIS-JUMAT-SABTU'
                            WHEN SUP_HARIKUNJUNGAN = ' YYYYY ' THEN 'SENIN-SELASA-RABU-KAMIS-JUMAT'
                            WHEN SUP_HARIKUNJUNGAN = 'Y Y   Y' THEN 'MINGGU-SELASA-SABTU'
                            when SUP_HARIKUNJUNGAN = ' Y Y  Y' then 'SENIN-RABU-SABTU'
                            when SUP_HARIKUNJUNGAN = ' Y Y Y ' then 'SENIN-RABU-JUMAT'
                            WHEN SUP_HARIKUNJUNGAN = ' Y Y   ' THEN 'SENIN-RABU'
                            WHEN SUP_HARIKUNJUNGAN = ' Y  YY ' THEN 'SENIN-KAMIS-JUMAT'
                            WHEN SUP_HARIKUNJUNGAN = '    YY ' THEN 'KAMIS-JUMAT'
                            WHEN SUP_HARIKUNJUNGAN = ' Y  Y  ' THEN 'SENIN-KAMIS'
                            when SUP_HARIKUNJUNGAN = ' Y   Y ' then 'SENIN-JUMAT'
                            WHEN SUP_HARIKUNJUNGAN = ' Y     ' THEN 'SENIN'
                            when SUP_HARIKUNJUNGAN = '  YY YY' then 'SELASA-RABU-JUMAT-SABTU'
                            WHEN SUP_HARIKUNJUNGAN = '  YY Y ' THEN 'SELASA-RABU-JUMAT'
                            when SUP_HARIKUNJUNGAN = '  Y Y Y' then 'SELASA-KAMIS-SABTU'
                            WHEN SUP_HARIKUNJUNGAN = '  Y Y  ' THEN 'SELASA-KAMIS'
                            WHEN SUP_HARIKUNJUNGAN = '  Y  Y ' THEN 'SELASA-JUMAT'
                            when SUP_HARIKUNJUNGAN = '  Y   Y' then 'SELASA-SABTU'
                            WHEN SUP_HARIKUNJUNGAN = '  Y    ' THEN 'SELASA'
                            when SUP_HARIKUNJUNGAN = '   Y Y ' then 'RABU-JUMAT'
                            WHEN SUP_HARIKUNJUNGAN = '   YY  ' THEN 'RABU-KAMIS'
                            when SUP_HARIKUNJUNGAN = '   Y  Y' then 'RABU-SABTU'
                            when SUP_HARIKUNJUNGAN = '   Y   ' then 'RABU'
                            when SUP_HARIKUNJUNGAN = '    Y  ' then 'KAMIS'
                            when SUP_HARIKUNJUNGAN = '     Y ' then 'JUMAT'
                            WHEN SUP_HARIKUNJUNGAN = '      Y' THEN 'SABTU'
                            when SUP_HARIKUNJUNGAN = 'YY     ' then 'MINGGU-SENIN'
                            ELSE '--'
                            END AS HARI_KUNJ,
                          case  
                            when SUP_HARIKUNJUNGAN = 'YYYYYYY' then '1234567' 
                            when SUP_HARIKUNJUNGAN = 'YYYYYY ' then '1234560' 
                            when SUP_HARIKUNJUNGAN = ' YYYYYY' then '0234567' 
                            WHEN SUP_HARIKUNJUNGAN = ' YYYYY ' THEN '0234560' 
                            WHEN SUP_HARIKUNJUNGAN = 'Y Y   Y' THEN '1030007' 
                            when SUP_HARIKUNJUNGAN = ' Y Y  Y' then '0204007' 
                            when SUP_HARIKUNJUNGAN = ' Y Y Y ' then '0204060' 
                            when SUP_HARIKUNJUNGAN = ' Y Y   ' then '0204000' 
                            WHEN SUP_HARIKUNJUNGAN = ' Y  YY ' THEN '0200560' 
                            when SUP_HARIKUNJUNGAN = '    YY ' then '0000560' 
                            when SUP_HARIKUNJUNGAN = ' Y  Y  ' then '0200500' 
                            when SUP_HARIKUNJUNGAN = ' Y   Y ' then '0200060' 
                            when SUP_HARIKUNJUNGAN = ' Y     ' then '0200000' 
                            when SUP_HARIKUNJUNGAN = '  YY YY' then '0034067' 
                            when SUP_HARIKUNJUNGAN = '  YY Y ' then '0034060' 
                            when SUP_HARIKUNJUNGAN = '  Y Y Y' then '0030507' 
                            when SUP_HARIKUNJUNGAN = '  Y Y  ' then '0030500' 
                            when SUP_HARIKUNJUNGAN = '  Y  Y ' then '0030060' 
                            when SUP_HARIKUNJUNGAN = '  Y   Y' then '0030007' 
                            when SUP_HARIKUNJUNGAN = '  Y    ' then '0030000' 
                            when SUP_HARIKUNJUNGAN = '   Y Y ' then '0004060' 
                            when SUP_HARIKUNJUNGAN = '   YY  ' then '0004500' 
                            when SUP_HARIKUNJUNGAN = '   Y  Y' then '0004007' 
                            when SUP_HARIKUNJUNGAN = '   Y   ' then '0004000' 
                            when SUP_HARIKUNJUNGAN = '    Y  ' then '0000500' 
                            when SUP_HARIKUNJUNGAN = '     Y ' then '0000060' 
                            when SUP_HARIKUNJUNGAN = '      Y' then '0000007' 
                            ELSE '--' 
                            END AS HARI_KUNJ2
                          FROM TBMASTER_HARGABELI LEFT JOIN TBMASTER_SUPPLIER ON SUP_KODESUPPLIER = HGB_KODESUPPLIER
                          WHERE HGB_TIPE = '2') ON HGB_PRDCD = ST_PRDCD
            LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = ST_PRDCD
            LEFT JOIN (SELECT SLS_PRDCD, Nvl(SLS_QTY_" . $bln_01  .",0) SLS1, 
                      CASE WHEN Nvl(SLS_QTY_" . $bln_01  .",0) > 0 THEN 31
                      ELSE 0 END AS HARI1,
                      Nvl(SLS_QTY_" . $bln_02  .",0) SLS2, 
                      CASE WHEN Nvl(SLS_QTY_" . $bln_02  .",0) > 0 THEN 30
                      ELSE 0 END AS HARI2,
                      Nvl(SLS_QTY_" . $bln_03  .",0) SLS3, 
                      CASE WHEN Nvl(SLS_QTY_" . $bln_03  .",0) > 0 THEN 31
                      ELSE 0 END AS HARI3 FROM TBTR_SALESBULANAN) ON SLS_PRDCD = ST_PRDCD
            --LEFT JOIN TBTR_PB_D ON TPOD_PRDCD = PBD_PRDCD AND PBD_NOPO = TPOD_NOPO
            LEFT JOIN TBTR_PO_D ON ST_PRDCD = TPOD_PRDCD
            LEFT JOIN ( SELECT TPOD_PRDCD PLU_PO,SUM( TPOD_QTYPO) PO_OUT,
                        COUNT(DISTINCT(TPOH_NOPO)) JML_PO FROM TBTR_PO_H
                        LEFT JOIN TBTR_PO_D ON TPOD_NOPO = TPOH_NOPO
                        WHERE TPOH_RECORDID IS NULL
                        AND TRUNC(tpoh_tglpo+tpoh_jwpb) >=TRUNC(sysdate)
                        GROUP BY TPOD_PRDCD, TPOD_QTYPO) ON ST_PRDCD = PLU_PO
            LEFT JOIN TBMASTER_KKPKM ON PKM_PRDCD = ST_PRDCD
            LEFT JOIN TBMASTER_MAXPALET ON MPT_PRDCD = ST_PRDCD
            WHERE  ST_PRDCD IN (SELECT MPL_PRDCD FROM TBTR_MONITORINGPLU WHERE MPL_KODEMONITORING IN ( 'F1','F2','NF1','NF2','G','O' ) )
            ---( 'F1','F2','NF1','NF2','G','O' )
            AND TPOD_KODEDIVISI <> '4'
            AND ST_LOKASI = '01'
            --AND NVL(TPOD_RECORDID,'0') <> '1'
            --AND TPOD_PRDCD  = '0232430'
            --AND HARI_PB LIKE '%JUMAT%'
            ORDER BY HGB_KODESUPPLIER ASC,PLU ASC))
            WHERE HASIL = SUBSTR(HARI_KUNJ2,1,1) OR HASIL = SUBSTR(HARI_KUNJ2,2,1)
            OR HASIL = SUBSTR(HARI_KUNJ2,3,1) OR HASIL = SUBSTR(HARI_KUNJ2,4,1)
            OR HASIL = SUBSTR(HARI_KUNJ2,5,1) OR HASIL = SUBSTR(HARI_KUNJ2,6,1)
            OR HASIL = SUBSTR(HARI_KUNJ2,7,1)"
        );
        $kkhpbm = $kkhpbm->getResultArray();
        
        $data = [
            'title' => 'Monitoring KKHPBM',
            'kkhpbm' => $kkhpbm,
        ];

        redirect()->to('kkhpbm')->withInput();
        return view('/logistik/kkhpbm',$data);
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

    public function planominus(){
      $dbProd = db_connect('production');
      $plano = $this->request->getVar('plano');
      $jenis = $this->request->getVar('jenis');

      $planominus = [];

      if ($plano=="all") {
        $filterplano = "";
      }elseif($plano == "toko"){
        $filterplano = " WHERE JENIS ='Toko'";
      }elseif($plano == "gudang"){
        $filterplano = " WHERE JENIS ='Gudang'";
      }
      if ($jenis=="1") {
        
        $planominus = $dbProd->query(
          " SELECT * FROM (
            select * from (
            SELECT LKS_KODERAK     AS RAK,
              LKS_KODESUBRAK       AS SUB,
              LKS_TIPERAK          AS TIPE,
              LKS_SHELVINGRAK      AS SHELV,
              LKS_PRDCD            AS PLU,
              PRD_DESKRIPSIPANJANG AS DESK,
              LKS_QTY              AS QTYPLANO,
              case when (LKS_KODERAK LIKE 'D%' OR LKS_KODERAK LIKE 'G%') then 'Gudang' else 'Toko' end AS JENIS
            FROM TBMASTER_LOKASI 
            LEFT JOIN TBMASTER_PRODMAST
            ON LKS_PRDCD =PRD_PRDCD
            WHERE LKS_QTY<0
            ORDER BY LKS_KODERAK ,
              LKS_KODESUBRAK ,
              LKS_TIPERAK ,
              LKS_SHELVINGRAK
             )  
             $filterplano
             order by RAK asc) 
             ORDER BY 1, 2, 3, 4, 5 "
        );
        $planominus = $planominus->getResultArray();     
      }


      $data = [
        'title' => 'Plano Minus '.$this->tglsekarang,
        'planominus' => $planominus
      ];

      return view('logistik/planominus', $data);
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
            $filter = " AND lks_tiperak <> 'S'";
        } elseif($aksi == "btnstr") {
            $filter = " AND lks_tiperak = 'S'";
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
                "SELECT * from (
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
            $lap0 = 'per PRODUK';
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
            $lap0 = 'per PRODUK DETAIL';
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
            $lap0 = 'per SUPPLIER';
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
            $lap0 = 'per DIVISI';
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
            $lap0 = 'per DEPARTEMENT';
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
            $lap0 = 'per KATEGORI';
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
            $lap0 = 'per HARI';
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
                $lap0 = 'RETUR OMI';
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
                $lap0 = 'RESET SO IC';
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
                $lap0 = 'PO vs BTB per SUPPLIER';
            };
        };       
        

        $data = [
            'title' => 'Data Back Office',
            'databo' => $databo,
            'awal' => $awal,
            'akhir' => $akhir,
            'lap' => $lap,
            'jenis' => $jenis,
        ];

        if($aksi == 'btnxls') {
            $filename = "LAPORAN ".$lap0." ".date('d M Y',strtotime($awal))." sd ".date('d M Y',strtotime($akhir)).".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        
            return view('logistik/tampildatabo',$data);
        };
  
        // redirect()->to('/logistik/tampildatabo')->withInput();
        return view('logistik/tampildatabo',$data);
    }

    public function stockharian() {
        $dbProd = db_connect("production");
        $dep = [];

        $dep = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $dep = $dep->getResultArray();

        $data = [
            'title' => 'Stock Harian',
            'dep' => $dep,
        ];
  
        redirect()->to('/logistik/stockharian')->withInput();
        return view('logistik/stockharian',$data);
    }

    public function tampilstock() {
        $dbProd = db_connect("production");
        $divisi = $this->request->getVar('divisi');
        $departemen = $this->request->getVar('dep');
        $tag = $this->request->getVar('tag');
        $jnslap = $this->request->getVar('lap');
        $stok = $dept = $filename = [];
        $aksi = $this->request->getVar('tombol');

        if($divisi == "All") {
            $dvs = "";
        } else {
            $dvs = " and prd_kodedivisi='$divisi' ";
        };

        if($departemen == "All") {
            $dep = "";
        } else {
            $dep = " and prd_kodedepartement = '$departemen' ";
        };

        if($tag == "All") {
            $kdtag = "";
        } else if($tag == "1") {
            $kdtag = " and prd_kodetag not in ('H','O','A','X','N','T') ";
        } else if($tag == "2") {
            $kdtag = " and prd_kodetag in ('H','O','A','X','N','T') ";
        } else {
            $kdtag = "";
        };

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
        };

        if($jnslap == "0") {
            $stok = $dbProd->query(
                "select 
                prd_kodedivisi as DIV,
                prd_kodedepartement as DEP,
                prd_kodekategoribarang as KAT,
                prd_plumcg as PLU_MCG,
                prd_prdcd as PLU_IGR,
                prc_pluomi as PLU_OMI,
                prd_deskripsipanjang as DESKRIPSI,
                prd_unit as UNIT,
                prd_frac as FRAC,
                prd_kodetag as TAG_IGR,
                prc_kodetag as TAG_OMI,
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
                st_saldoakhir/prd_frac as STOCK_IN_CTN,
                st_saldoakhir as STOCK_IN_PCS,
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
                pkm_qtyaverage as PKM_AVGSALES,
                case when st_saldoakhir>0 and (sls_qty_$bln1 + sls_qty_$bln2 + sls_qty_$bln3) > 0 then st_saldoakhir / ((sls_qty_$bln1 + sls_qty_$bln2 + sls_qty_$bln3)/90) else 0 end as DSI_AVG_SLS,
                case when st_saldoakhir>0 and st_sales>0 then round((((nvl(st_saldoawal,1) + nullif(st_saldoakhir,0))/2)/nullif(st_sales,0)) * (extract(day from sysdate))) else 0 end as DSI_BLN_INI,
                LASTPO,
                FIRSTBPB,
                LASTBPB,
                QTY_PO,
                jml_po,
                mpt_maxqty as MAXPALET,
                case 
                    when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')='Y' then 'IGR+OMI'
                    when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')!='Y' then 'IGR ONLY'
                    when nvl(prd_flagigr,'N')!='Y' and nvl(prd_flagomi,'N')='Y' then 'OMI ONLY'
                    else 'TIDAK BISA JUAL'
                  end as FLAG_JUAL,
                Display_REG,MAXPLANO_REG,MINPCT_REG,
                Display_DPD,MAXPLANO_DPD,
                JENIS_ITEM,
                case 
                    when nvl(JENIS_CKS,'N') = 'S' then 'SB'
                    when nvl(JENIS_CKS,'N') = 'K' then 'SK'
                    when nvl(JENIS_CKS,'N') = 'C' then 'SC'
                end as JENIS_CKS,
                st_trfin,
                st_trfout,
                st_sales,
                st_retur,
                st_adj,
                st_intransit,
                AVGSLS_IGR,
                AVGSLS_OMI,
                AVGSLS_MM,
                prd_tgldiscontinue
                from tbmaster_prodmast
                left join (select * from tbmaster_stock where st_lokasi='01') on prd_prdcd=st_prdcd
                left join tbmaster_prodcrm on prc_pluigr=prd_prdcd
                left join tbmaster_kkpkm on pkm_prdcd=prd_prdcd
                left join tbmaster_pkmplus on pkmp_prdcd=prd_prdcd
                left join (select * from tbmaster_hargabeli where hgb_tipe='2') on hgb_prdcd=prd_prdcd
                left join tbmaster_supplier on sup_kodesupplier=hgb_kodesupplier
                left join tbtr_salesbulanan on sls_prdcd=prd_prdcd
                left join TBMASTER_MAXPALET on mpt_prdcd=prd_prdcd
                left join ( 
                select tpod_prdcd AS PLUPOAKHIR,max(tpod_tglpo)as LASTPO
                    from tbtr_po_d group by tpod_prdcd
                )on PLUPOAKHIR=prd_prdcd 
                left join ( 
                select mstd_prdcd AS PLUBPBAKHIR,min(mstd_tgldoc)as FIRSTBPB,max(mstd_tgldoc)as LASTBPB
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
                left join (
                select rsl_prdcd,
                  sum(case when rsl_group='01' then (nvl(rsl_qty_$bln1,0) + nvl(rsl_qty_$bln2,0) + nvl(rsl_qty_$bln3,0))/3 end) as AVGSLS_IGR,
                  sum(case when rsl_group='02' then (nvl(rsl_qty_$bln1,0) + nvl(rsl_qty_$bln2,0) + nvl(rsl_qty_$bln3,0))/3 end) as AVGSLS_OMI,
                  sum(case when rsl_group='03' then (nvl(rsl_qty_$bln1,0) + nvl(rsl_qty_$bln2,0) + nvl(rsl_qty_$bln3,0))/3 end) as AVGSLS_MM
                from tbtr_rekapsalesbulanan group by rsl_prdcd
                ) on rsl_prdcd=prd_prdcd
                
                where (prd_kodecabang='25' or prd_kategoritoko in ('01','02','03')) and prd_prdcd like '%0' 
                $dvs
                $dep
                $kdtag
                order by DIV,DEP,DESKRIPSI"
            );
            $stok = $stok->getResultArray();
            $jns = "per PRODUK DETAIL";
        } else if($jnslap == "1") {
            $stok = $dbProd->query(
                "select DIV,NMDIV,SUM(STOCK_IN_CTN) as CTN,SUM(STOCK_IN_PCS) as PCS,SUM(STOCK_RPH) as RPH,
                SUM(JAN) as SJAN,SUM(PEB) as SFEB,SUM(MAR) as SMAR,SUM(APR) as SAPR,SUM(MEI) as SMEI,SUM(JUN) as SJUN,SUM(JUL) as SJUL,SUM(AGS) as SAGS,
                SUM(SEP) as SSEP,SUM(OKT) as SOKT,SUM(NOV) as SNOV,SUM(DES) as SDES,SUM(BLN_INI) as SBLN,
                SUM(AVGSLS_IGR) as IGR,SUM(AVGSLS_OMI) as OMI,SUM(AVGSLS_MM) as MM
                from (
                select 
                prd_kodedivisi as DIV,
                div_namadivisi as NMDIV,
                prd_kodedepartement as DEP,
                dep_namadepartement as NMDEP,
                prd_kodekategoribarang as KAT,
                prd_plumcg as PLU_MCG,
                prd_prdcd as PLU_IGR,
                prc_pluomi as PLU_OMI,
                prd_deskripsipanjang as DESKRIPSI,
                prd_unit as UNIT,
                prd_frac as FRAC,
                prd_kodetag as TAG_IGR,
                prc_kodetag as TAG_OMI,
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
                st_saldoakhir/prd_frac as STOCK_IN_CTN,
                st_saldoakhir as STOCK_IN_PCS,
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
                pkm_qtyaverage as PKM_AVGSALES,
                case when st_saldoakhir>0 and (sls_qty_$bln1 + sls_qty_$bln2 + sls_qty_$bln3) > 0 then st_saldoakhir / ((sls_qty_$bln1 + sls_qty_$bln2 + sls_qty_$bln3)/90) else 0 end as DSI_AVG_SLS,
                case when st_saldoakhir>0 and st_sales>0 then round((((nvl(st_saldoawal,1) + nullif(st_saldoakhir,0))/2)/nullif(st_sales,0)) * (extract(day from sysdate))) else 0 end as DSI_BLN_INI,
                LASTPO,
                FIRSTBPB,
                LASTBPB,
                QTY_PO,
                jml_po,
                mpt_maxqty as MAXPALET,
                case 
                    when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')='Y' then 'IGR+OMI'
                    when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')!='Y' then 'IGR ONLY'
                    when nvl(prd_flagigr,'N')!='Y' and nvl(prd_flagomi,'N')='Y' then 'OMI ONLY'
                    else 'TIDAK BISA JUAL'
                  end as FLAG_JUAL,
                Display_REG,MAXPLANO_REG,MINPCT_REG,
                Display_DPD,MAXPLANO_DPD,
                JENIS_ITEM,
                case 
                    when nvl(JENIS_CKS,'N') = 'S' then 'SB'
                    when nvl(JENIS_CKS,'N') = 'K' then 'SK'
                    when nvl(JENIS_CKS,'N') = 'C' then 'SC'
                end as JENIS_CKS,
                st_trfin,
                st_trfout,
                st_sales,
                st_retur,
                st_adj,
                st_intransit,
                AVGSLS_IGR,
                AVGSLS_OMI,
                AVGSLS_MM,
                prd_tgldiscontinue
                from tbmaster_prodmast
                left join (select * from tbmaster_stock where st_lokasi='01') on prd_prdcd=st_prdcd
                left join tbmaster_prodcrm on prc_pluigr=prd_prdcd
                left join tbmaster_kkpkm on pkm_prdcd=prd_prdcd
                left join tbmaster_pkmplus on pkmp_prdcd=prd_prdcd
                left join (select * from tbmaster_hargabeli where hgb_tipe='2') on hgb_prdcd=prd_prdcd
                left join tbmaster_supplier on sup_kodesupplier=hgb_kodesupplier
                left join tbtr_salesbulanan on sls_prdcd=prd_prdcd
                left join TBMASTER_MAXPALET on mpt_prdcd=prd_prdcd
                left join ( 
                select tpod_prdcd AS PLUPOAKHIR,max(tpod_tglpo)as LASTPO
                    from tbtr_po_d group by tpod_prdcd
                )on PLUPOAKHIR=prd_prdcd 
                left join ( 
                select mstd_prdcd AS PLUBPBAKHIR,min(mstd_tgldoc)as FIRSTBPB,max(mstd_tgldoc)as LASTBPB
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
                left join (
                select rsl_prdcd,
                  sum(case when rsl_group='01' then (nvl(rsl_qty_$bln1,0) + nvl(rsl_qty_$bln2,0) + nvl(rsl_qty_$bln3,0))/3 end) as AVGSLS_IGR,
                  sum(case when rsl_group='02' then (nvl(rsl_qty_$bln1,0) + nvl(rsl_qty_$bln2,0) + nvl(rsl_qty_$bln3,0))/3 end) as AVGSLS_OMI,
                  sum(case when rsl_group='03' then (nvl(rsl_qty_$bln1,0) + nvl(rsl_qty_$bln2,0) + nvl(rsl_qty_$bln3,0))/3 end) as AVGSLS_MM
                from tbtr_rekapsalesbulanan group by rsl_prdcd
                ) on rsl_prdcd=prd_prdcd
                left join tbmaster_divisi on div_kodedivisi = prd_kodedivisi
                left join tbmaster_departement on dep_kodedepartement = prd_kodedepartement
                
                where (prd_kodecabang='25' or prd_kategoritoko in ('01','02','03')) and prd_prdcd like '%0' 
                $dvs
                --$dep
                $kdtag
                )
                group by DIV,NMDIV
                order by DIV,NMDIV"
            );
            $stok = $stok->getResultArray();
            $jns = "per DIVISI";
        } else if($jnslap == "2") {
            $stok = $dbProd->query(
                "select DIV,NMDIV,DEP,NMDEP,SUM(STOCK_IN_CTN) as CTN,SUM(STOCK_IN_PCS) as PCS,SUM(STOCK_RPH) as RPH,
                SUM(JAN) as SJAN,SUM(PEB) as SFEB,SUM(MAR) as SMAR,SUM(APR) as SAPR,SUM(MEI) as SMEI,SUM(JUN) as SJUN,SUM(JUL) as SJUL,SUM(AGS) as SAGS,
                SUM(SEP) as SSEP,SUM(OKT) as SOKT,SUM(NOV) as SNOV,SUM(DES) as SDES,SUM(BLN_INI) as SBLN,
                SUM(AVGSLS_IGR) as IGR,SUM(AVGSLS_OMI) as OMI,SUM(AVGSLS_MM) as MM
                from (
                select 
                prd_kodedivisi as DIV,
                div_namadivisi as NMDIV,
                prd_kodedepartement as DEP,
                dep_namadepartement as NMDEP,
                prd_kodekategoribarang as KAT,
                prd_plumcg as PLU_MCG,
                prd_prdcd as PLU_IGR,
                prc_pluomi as PLU_OMI,
                prd_deskripsipanjang as DESKRIPSI,
                prd_unit as UNIT,
                prd_frac as FRAC,
                prd_kodetag as TAG_IGR,
                prc_kodetag as TAG_OMI,
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
                st_saldoakhir/prd_frac as STOCK_IN_CTN,
                st_saldoakhir as STOCK_IN_PCS,
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
                pkm_qtyaverage as PKM_AVGSALES,
                case when st_saldoakhir>0 and (sls_qty_$bln1 + sls_qty_$bln2 + sls_qty_$bln3) > 0 then st_saldoakhir / ((sls_qty_$bln1 + sls_qty_$bln2 + sls_qty_$bln3)/90) else 0 end as DSI_AVG_SLS,
                case when st_saldoakhir>0 and st_sales>0 then round((((nvl(st_saldoawal,1) + nullif(st_saldoakhir,0))/2)/nullif(st_sales,0)) * (extract(day from sysdate))) else 0 end as DSI_BLN_INI,
                LASTPO,
                FIRSTBPB,
                LASTBPB,
                QTY_PO,
                jml_po,
                mpt_maxqty as MAXPALET,
                case 
                    when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')='Y' then 'IGR+OMI'
                    when nvl(prd_flagigr,'N')='Y' and nvl(prd_flagomi,'N')!='Y' then 'IGR ONLY'
                    when nvl(prd_flagigr,'N')!='Y' and nvl(prd_flagomi,'N')='Y' then 'OMI ONLY'
                    else 'TIDAK BISA JUAL'
                  end as FLAG_JUAL,
                Display_REG,MAXPLANO_REG,MINPCT_REG,
                Display_DPD,MAXPLANO_DPD,
                JENIS_ITEM,
                case 
                    when nvl(JENIS_CKS,'N') = 'S' then 'SB'
                    when nvl(JENIS_CKS,'N') = 'K' then 'SK'
                    when nvl(JENIS_CKS,'N') = 'C' then 'SC'
                end as JENIS_CKS,
                st_trfin,
                st_trfout,
                st_sales,
                st_retur,
                st_adj,
                st_intransit,
                AVGSLS_IGR,
                AVGSLS_OMI,
                AVGSLS_MM,
                prd_tgldiscontinue
                from tbmaster_prodmast
                left join (select * from tbmaster_stock where st_lokasi='01') on prd_prdcd=st_prdcd
                left join tbmaster_prodcrm on prc_pluigr=prd_prdcd
                left join tbmaster_kkpkm on pkm_prdcd=prd_prdcd
                left join tbmaster_pkmplus on pkmp_prdcd=prd_prdcd
                left join (select * from tbmaster_hargabeli where hgb_tipe='2') on hgb_prdcd=prd_prdcd
                left join tbmaster_supplier on sup_kodesupplier=hgb_kodesupplier
                left join tbtr_salesbulanan on sls_prdcd=prd_prdcd
                left join TBMASTER_MAXPALET on mpt_prdcd=prd_prdcd
                left join ( 
                select tpod_prdcd AS PLUPOAKHIR,max(tpod_tglpo)as LASTPO
                    from tbtr_po_d group by tpod_prdcd
                )on PLUPOAKHIR=prd_prdcd 
                left join ( 
                select mstd_prdcd AS PLUBPBAKHIR,min(mstd_tgldoc)as FIRSTBPB,max(mstd_tgldoc)as LASTBPB
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
                left join (
                select rsl_prdcd,
                  sum(case when rsl_group='01' then (nvl(rsl_qty_$bln1,0) + nvl(rsl_qty_$bln2,0) + nvl(rsl_qty_$bln3,0))/3 end) as AVGSLS_IGR,
                  sum(case when rsl_group='02' then (nvl(rsl_qty_$bln1,0) + nvl(rsl_qty_$bln2,0) + nvl(rsl_qty_$bln3,0))/3 end) as AVGSLS_OMI,
                  sum(case when rsl_group='03' then (nvl(rsl_qty_$bln1,0) + nvl(rsl_qty_$bln2,0) + nvl(rsl_qty_$bln3,0))/3 end) as AVGSLS_MM
                from tbtr_rekapsalesbulanan group by rsl_prdcd
                ) on rsl_prdcd=prd_prdcd
                left join tbmaster_divisi on div_kodedivisi = prd_kodedivisi
                left join tbmaster_departement on dep_kodedepartement = prd_kodedepartement
                
                where (prd_kodecabang='25' or prd_kategoritoko in ('01','02','03')) and prd_prdcd like '%0' 
                $dvs
                $dep
                $kdtag
                )
                group by DIV,NMDIV,DEP,NMDEP
                order by DIV,NMDIV,DEP,NMDEP"
            );
            $stok = $stok->getResultArray();
            $jns = "per DEPARTEMENT";
        };
        

        $dept = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $dept = $dept->getResultArray();

        $data = [
            'title' => 'Tampil Stock Harian',
            'departemen' => $departemen,
            'divisi' => $divisi,
            'tag' => $tag,
            'stok' => $stok,
            'dept' => $dept,
            'jnslap' => $jnslap,
            'jns' => $jns,
        ];

        if($aksi == 'btnxls') {
            $filename = "Laporan $jns".date('d M Y').".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        
            return view('logistik/tampilstock',$data);
        };

        // d($data);
        // redirect()->to('/logistik/tampilstock')->withInput();
        return view('logistik/tampilstock',$data);
    }

    public function lppsaatini() {
        $dbProd = db_connect("production");
        $departemen = $divisi = $kategori = [];

        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $departemen = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $departemen = $departemen->getResultArray();

        $kategori = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $kategori = $kategori->getResultArray();

        $data = [
            'title' => 'LPP Saat Ini',
            'divisi' => $divisi,
            'departemen' => $departemen,
            'kategori' => $kategori,
        ];
  
        redirect()->to('/logistik/lppsaatini')->withInput();
        return view('logistik/lppsaatini',$data);
    }

    public function tampillpp() {
        $dbProd = db_connect("production");
        $aksi = $this->request->getVar('tombol');
        $lppSaatIni = $dept = $dvs = $katb = $filename = [];
           
        // atur nilai default
        $lokasiStock = "01";
        $groupSales	 = $statusTag = $statusQty = "All";
  
        // barang, divisi, dep, kat
        $kodePLU = $kodeDivisi = $kodeDepartemen = $kodeKategoriBarang = $jenisLaporan = $sortBy = "All";
        $filterstok = $filtergrup = $filtertag = $filterdiv = $filterdep = $filterkat = $filterQty = "";

        // Ambil variabel dr form
        if(isset($_GET['stok'])) {if ($_GET['stok'] !=""){$lokasiStock = $_GET['stok']; }}
        if ($lokasiStock != "All" AND $lokasiStock != "") {
            $filterstok = " AND st_lokasi = '$lokasiStock' ";
        } else if ($lokasiStock == "02" OR $lokasiStock == "03") {
            $filterstok = " AND st_saldo_in_pcs <> 0 ";
        }
        if(isset($_GET['grup'])) {if ($_GET['grup'] !=""){$groupSales = $_GET['grup']; }}
        if ($groupSales != "All" AND $groupSales != "") {
            $filtergrup = " AND st_igr_idm = '$groupSales' ";
        }
        if(isset($_GET['statustag'])) {if ($_GET['statustag'] !=""){$statusTag = $_GET['statustag']; }}
        if ($statusTag != "All" AND $statusTag != "") {
            $filtertag = " AND st_status_tag = '$statusTag' ";
        }
        if(isset($_GET['divisi'])) {if ($_GET['divisi'] !=""){$kodeDivisi = $_GET['divisi']; }}
        if ($kodeDivisi != "All" AND $kodeDivisi != "") {
            $filterdiv = " AND st_div = '$kodeDivisi' ";
        }
        if(isset($_GET['dep'])) {if ($_GET['dep'] !=""){$kodeDepartemen = $_GET['dep']; }}
        if ($kodeDepartemen != "All" AND $kodeDepartemen != "") {
            $filterdep = " AND st_dept = '$kodeDepartemen' ";
        }
        if(isset($_GET['kat'])) {if ($_GET['kat'] !=""){$kodeKategoriBarang = $_GET['kat']; }}
        if ($kodeKategoriBarang != "All" AND $kodeKategoriBarang != "") {
            $filterkat = " AND st_dept || st_katb = '$kodeKategoriBarang' ";
        }
        if(isset($_GET['lap'])) {if ($_GET['lap'] !=""){$jenisLaporan = $_GET['lap']; }}

        if(isset($_GET['statusqty'])) {if ($_GET['statusqty'] !=""){$statusQty = $_GET['statusqty']; }}
        if ($statusQty != "All" AND $statusQty != "") {
            switch ($statusQty) {
                case "1":
                  $filterQty = " AND st_saldo_in_pcs < 0 ";
                  break;
                case "2":
                  $filterQty = " AND st_saldo_in_pcs = 0 ";
                  break;
                case "3":
                  $filterQty = " AND st_saldo_in_pcs > 0 ";
                  break;
                case "4":
                  $filterQty = " AND st_saldo_in_pcs < st_spd * 3 ";
                  break;
                case "5":
                  $filterQty = " AND st_saldo_in_pcs < st_pkm ";
                  break;
            }
        }

        if($jenisLaporan == '1') {
            $lap = 'LAPORAN per DIVISI';
        } else if($jenisLaporan == '2') {
            $lap = 'LAPORAN per DEPARTEMEN';
        } else if($jenisLaporan == '3') {
            $lap = 'LAPORAN per KATEGORI';
        } else if($jenisLaporan == '4') {
            $lap = 'LAPORAN per PRODUK';
        } else if($jenisLaporan == '4B') {
            $lap = 'LAPORAN PRODUK DISKON 2';
        } else if($jenisLaporan == '4C') {
            $lap = 'LAPORAN PRODUK DETAIL';
        } else if($jenisLaporan == '5') {
            $lap = 'LAPORAN per SUPPLIER';
        } else if($jenisLaporan == '6') {
            $lap = 'LAPORAN per KODE TAG';
        } else if($jenisLaporan == '7') {
            $lap = 'LAPORAN per Group Sales';
        }

        $bln_01 = date('m', strtotime('-3 month')) ;
	    $bln_02 = date('m', strtotime('-2 month')) ;
	    $bln_03 = date('m', strtotime('-1 month')) ;

        $viewHargaBeli = "(SELECT hgb.hgb_prdcd,
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
                    AND hgb.hgb_kodesupplier = sup.sup_kodesupplier (+))";
        
        $viewPOOutstanding = "(SELECT tpod_prdcd,
            SUM(tpod_qtypo)  AS tpod_qtypo,
            Count(tpod_nopo) AS tpod_nopo
            FROM   (SELECT tpod_prdcd,
                tpod_qtypo,
                tpod_nopo
                FROM   tbtr_po_d
                WHERE  tpod_nopo IN (SELECT tpoh_nopo
                    FROM   tbtr_po_h
                    WHERE  tpoh_recordid IS NULL
                    AND Trunc(tpoh_tglpo + tpoh_jwpb) >= Trunc(SYSDATE)
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
            )
            GROUP  BY tpod_prdcd) ";

        $viewSalesPerDay = " (SELECT sls_prdcd                               AS spd_prdcd,
            Nvl(sls_qty_" . $bln_01  .", 0)                      AS spd_qty_1,
            Nvl(sls_qty_" . $bln_02  .", 0)                      AS spd_qty_2,
            Nvl(sls_qty_" . $bln_03  .", 0)                      AS spd_qty_3,
            Trunc(( Nvl(sls_qty_" . $bln_01  .", 0) + Nvl(sls_qty_" . $bln_02  .", 0) + Nvl(sls_qty_" . $bln_03  .", 0) ) / 90, 5) AS spd_qty,
            Nvl(sls_rph_" . $bln_01  .", 0)                      AS spd_rph_1,
            Nvl(sls_rph_" . $bln_02  .", 0)                      AS spd_rph_2,
            Nvl(sls_rph_" . $bln_03  .", 0)                      AS spd_rph_3,
            Trunc(( Nvl(sls_rph_" . $bln_01  .", 0) + Nvl(sls_rph_" . $bln_02  .", 0) + Nvl(sls_rph_" . $bln_03  .", 0) ) / 90, 5) AS spd_rph
            FROM   tbtr_salesbulanan   ) ";

        $viewStatusIgrIdm = " (SELECT 
            PRD_PRDCD,
            CASE WHEN FLAG = 'NAS-IGR+K.IGR' THEN 'IGR-ONLY'
            WHEN FLAG = 'NAS' THEN 'IGR-ONLY'
            WHEN FLAG = 'IGR+K.IGR' THEN 'IGR-ONLY'
            WHEN FLAG = 'IGR' THEN 'IGR-ONLY'
             
            WHEN FLAG = 'NAS-OMI' THEN 'OMI-ONLY'
            WHEN FLAG = 'OMI' THEN 'OMI-ONLY'
            ELSE 'IGR-OMI' END AS STATUS_IGR_IDM 
             
             
            FROM (SELECT PRD_PRDCD ,
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
                FROM (SELECT prd_prdcd,prd_plumcg,
                    nvl(PRD_FLAGNAS,'N') AS NAS,
                    nvl(PRD_FLAGIGR,'N') AS IGR,
                    nvl(PRD_FLAGIDM,'N') AS IDM,
                    nvl(PRD_FLAGOMI,'N') AS OMI,
                    nvl(PRD_FLAGBRD,'N') AS BRD,
                    nvl(PRD_FLAGOBI,'N') AS K_IGR,
                    case when prd_plumcg in (select PLUIDM from DEPO_LIST_IDM ) THEN 'Y' ELSE 'N' END AS DEPO
                    FROM TBMASTER_PRODMAST 
                    WHERE PRD_PRDCD LIKE '%0' 
                    AND PRD_DESKRIPSIPANJANG IS NOT NULL))) ";

        $viewLppSaatIni = "(SELECT prd.prd_kodedivisi    AS st_div,
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

        if($jenisLaporan == "1") {
            $lppSaatIni = $dbProd->query(
                "SELECT st_div,
                st_div_nama,
                COUNT(st_prdcd)               AS st_item_produk,
                SUM(st_saldo_in_pcs)          AS st_saldo_in_pcs,
                SUM(st_saldo_rph)             AS st_saldo_rph,
                SUM(st_saldo_rph_lastcost)    AS st_saldo_rph_lastcost,
                COUNT(DISTINCT(st_supp_kode)) AS st_supp_jumlah
                FROM " . $viewLppSaatIni . "
                WHERE st_prdcd IS NOT NULL 
                $filterstok
                $filtergrup
                $filtertag
                $filterQty
                $filterdiv
                $filterdep
                $filterkat
                GROUP BY st_div,st_div_nama
			    ORDER BY st_div"
            );
            $lppSaatIni = $lppSaatIni->getResultArray();
        } else if($jenisLaporan == "2") {
            $lppSaatIni = $dbProd->query(
                "SELECT st_div,
                st_div_nama,
                st_dept,
                st_dept_nama,
                COUNT(st_prdcd)               AS st_item_produk,
                SUM(st_saldo_in_pcs)          AS st_saldo_in_pcs,
                SUM(st_saldo_rph)             AS st_saldo_rph,
                SUM(st_saldo_rph_lastcost)    AS st_saldo_rph_lastcost,
                COUNT(DISTINCT(st_supp_kode)) AS st_supp_jumlah
                FROM " . $viewLppSaatIni . "
                WHERE st_prdcd IS NOT NULL 
                $filterstok
                $filtergrup
                $filterQty
                $filtertag
                $filterdiv
                $filterdep
                $filterkat
                GROUP BY st_div,
				st_div_nama,
				st_dept,
				st_dept_nama
			    ORDER BY st_div,st_dept"
            );
            $lppSaatIni = $lppSaatIni->getResultArray();
        } else if($jenisLaporan == "3") {
            $lppSaatIni = $dbProd->query(
                "SELECT st_div,
                st_div_nama,
                st_dept,
                st_dept_nama,
                st_katb,
                st_katb_nama,
                COUNT(st_prdcd)               AS st_item_produk,
                SUM(st_saldo_in_pcs)          AS st_saldo_in_pcs,
                SUM(st_saldo_rph)             AS st_saldo_rph,
                SUM(st_saldo_rph_lastcost)    AS st_saldo_rph_lastcost,
                COUNT(DISTINCT(st_supp_kode)) AS st_supp_jumlah
                FROM " . $viewLppSaatIni . "
                WHERE st_prdcd IS NOT NULL 
                $filterstok
                $filtergrup
                $filterQty
                $filtertag
                $filterdiv
                $filterdep
                $filterkat
                GROUP BY st_div,
				st_div_nama,
				st_dept,
				st_dept_nama,
			  	st_katb,
			  	st_katb_nama
			    ORDER BY st_div,st_dept,st_katb"
            );
            $lppSaatIni = $lppSaatIni->getResultArray();
        } else if($jenisLaporan == "4") {
            $lppSaatIni = $dbProd->query(
                "SELECT * FROM " . $viewLppSaatIni . "
                WHERE st_prdcd IS NOT NULL
                $filterstok
                $filtergrup
                $filterQty
                $filtertag
                $filterdiv
                $filterdep
                $filterkat
                order by st_div,st_dept,st_katb"
            );
            $lppSaatIni = $lppSaatIni->getResultArray();
        } else if($jenisLaporan == "4B") {
            $lppSaatIni = $dbProd->query(
                "SELECT * FROM " . $viewLppSaatIni . "
                WHERE st_prdcd IS NOT NULL 
                AND st_disc_2_mulai IS NOT NULL
                $filterstok
                $filtergrup
                $filterQty
                $filtertag
                $filterdiv
                $filterdep
                $filterkat
                order by st_div,st_dept,st_katb"
            );
            $lppSaatIni = $lppSaatIni->getResultArray();
        } else if($jenisLaporan == "5") {
            $lppSaatIni = $dbProd->query(
                "SELECT st_supp_kode,
                st_supp_nama,
                COUNT(st_prdcd)               AS st_item_produk,
                SUM(st_saldo_in_pcs)          AS st_saldo_in_pcs,
                SUM(st_saldo_rph)             AS st_saldo_rph,
                SUM(st_saldo_rph_lastcost)    AS st_saldo_rph_lastcost
                
                FROM " . $viewLppSaatIni . "
                WHERE st_prdcd IS NOT NULL 
                $filterstok
                $filtergrup
                $filterQty
                $filtertag
                $filterdiv
                $filterdep
                $filterkat
                GROUP BY st_supp_kode,
				st_supp_nama
			    ORDER BY st_saldo_rph desc"
            );
            $lppSaatIni = $lppSaatIni->getResultArray();
        } else if($jenisLaporan == "6") {
            $lppSaatIni = $dbProd->query(
                "SELECT st_kodetag,
                COUNT(st_prdcd)               AS st_item_produk,
                SUM(st_saldo_in_pcs)          AS st_saldo_in_pcs,
                SUM(st_saldo_rph)             AS st_saldo_rph,
                COUNT(DISTINCT(st_supp_kode)) AS st_supp_jumlah
                FROM " . $viewLppSaatIni . "
                WHERE st_prdcd IS NOT NULL 
                $filterstok
                $filtergrup
                $filterQty
                $filtertag
                $filterdiv
                $filterdep
                $filterkat
                GROUP BY st_kodetag
			    ORDER BY st_kodetag"
            );
            $lppSaatIni = $lppSaatIni->getResultArray();
        } else if($jenisLaporan == "7") {
            $lppSaatIni = $dbProd->query(
                "SELECT st_igr_idm,
                COUNT(st_prdcd)               AS st_item_produk,
                SUM(st_saldo_in_pcs)          AS st_saldo_in_pcs,
                SUM(st_saldo_rph)             AS st_saldo_rph,
                COUNT(DISTINCT(st_supp_kode)) AS st_supp_jumlah
                FROM " . $viewLppSaatIni . "
                WHERE st_prdcd IS NOT NULL
                $filterstok
                $filtergrup
                $filterQty
                $filtertag
                $filterdiv
                $filterdep
                $filterkat
                GROUP BY st_igr_idm
			    ORDER BY st_igr_idm"
            );
            $lppSaatIni = $lppSaatIni->getResultArray();
        };

        $dept = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $dept = $dept->getResultArray();

        $katb = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $katb = $katb->getResultArray();

        $data = [
            'title' => 'Data LPP Saat Ini',
            'lokasiStock' => $lokasiStock,
            'groupSales' => $groupSales,
            'statusTag' => $statusTag,
            'statusQty' => $statusQty,
            'kodeDivisi' => $kodeDivisi,
            'kodeDepartemen' => $kodeDepartemen,
            'kodeKategoriBarang' => $kodeKategoriBarang,
            'jenisLaporan' => $jenisLaporan,
            'lppSaatIni' => $lppSaatIni,
            'dept' => $dept,
            'dvs' => $dvs,
            'katb' => $katb,
        ];

        if($aksi == 'btnxls') {
            $filename = $lap. " ".date('d M Y').".xls";
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-Type: application/vnd.ms-excel");
        
            return view('logistik/tampillpp',$data);
        };
  
        // redirect()->to('/logistik/tampillpp')->withInput();
        return view('logistik/tampillpp',$data);
    }

    public function tampillppblnlalu() {
        $dbProd = db_connect("production");
        $lppBaik = $lppRetur = $lppRusak = [];

        $viewLpp = "(SELECT   lpp_tgl1,
            lpp_tgl2,
            lpp_kodedivisi,
            lpp_kodedepartemen,
            lpp_kategoribrg,
            lpp_prdcd,
            prd_deskripsipanjang,
            prd_unit,
            prd_frac,
            NVL(prd_kodetag,' ')                                                                                                                                                                                                                                                                                                                                                              AS prd_kodetag,
            NVL(lpp_rphbegbal,0)                                                                                                                                                                                                                                                                                                                                                              AS lpp_rphbegbal,
            NVL(lpp_rphbeli,0)                                                                                                                                                                                                                                                                                                                                                                AS lpp_rphbeli,
            NVL(lpp_rphbonus,0)                                                                                                                                                                                                                                                                                                                                                               AS lpp_rphbonus,
            NVL(lpp_rphtrmcb,0)                                                                                                                                                                                                                                                                                                                                                               AS lpp_rphtrmcb,
            NVL(lpp_rphretursales,0)                                                                                                                                                                                                                                                                                                                                                          AS lpp_rphretursales,
            NVL(lpp_rphrafak,0)                                                                                                                                                                                                                                                                                                                                                               AS lpp_rphrafak,
            NVL(lpp_rphrepack,0)                                                                                                                                                                                                                                                                                                                                                              AS lpp_rphrepack,
            NVL(lpp_rphlainin,0)                                                                                                                                                                                                                                                                                                                                                              AS lpp_rphlainin,
            NVL(lpp_rphsales,0)                                                                                                                                                                                                                                                                                                                                                               AS lpp_rphsales,
            NVL(lpp_rphkirim,0)                                                                                                                                                                                                                                                                                                                                                               AS lpp_rphkirim,
            NVL(lpp_rphprepacking,0)                                                                                                                                                                                                                                                                                                                                                          AS lpp_rphprepacking,
            NVL(lpp_rphhilang,0)                                                                                                                                                                                                                                                                                                                                                              AS lpp_rphhilang,
            NVL(lpp_rphlainout,0)                                                                                                                                                                                                                                                                                                                                                             AS lpp_rphlainout,
            NVL(lpp_rphintransit,0)                                                                                                                                                                                                                                                                                                                                                           AS lpp_rphintransit,
            NVL(lpp_rphadj,0)                                                                                                                                                                                                                                                                                                                                                                 AS lpp_rphadj,
            NVL(lpp_rphakhir,0)                                                                                                                                                                                                                                                                                                                                                               AS lpp_rphakhir,
            NVL(lpp_rphakhir,0) - ( NVL(lpp_rphbegbal,0) + NVL(lpp_rphbeli,0) + NVL(lpp_rphbonus,0) + NVL(lpp_rphtrmcb,0) + NVL(lpp_rphretursales,0) + NVL(lpp_rphrafak,0) + NVL(lpp_rphrepack,0) + NVL(lpp_rphlainin,0) - NVL(lpp_rphsales,0) - NVL(lpp_rphkirim,0) - NVL(lpp_rphprepacking,0) - NVL(lpp_rphhilang,0) - NVL(lpp_rphlainout,0) + NVL(lpp_rphintransit,0) + NVL(lpp_rphadj,0)) AS lpp_koreksi
            FROM    tbtr_lpp,
                    tbmaster_prodmast
            WHERE   lpp_prdcd = prd_prdcd (+))";

        $viewLppSummary = "( SELECT   lpp_tgl1,
            lpp_tgl2,
            Sum(lpp_koreksi)       AS LPP_KOREKSI,
            Sum(lpp_rphbegbal)     AS LPP_RPHBEGBAL,
            Sum(lpp_rphbeli)       AS LPP_RPHBELI,
            Sum(lpp_rphbonus)      AS LPP_RPHBONUS,
            Sum(lpp_rphtrmcb)      AS LPP_RPHTRMCB,
            Sum(lpp_rphretursales) AS LPP_RPHRETURSALES,
            Sum(lpp_rphrafak)      AS LPP_RPHRAFAK,
            Sum(lpp_rphrepack)     AS LPP_RPHREPACK,
            Sum(lpp_rphlainin)     AS LPP_RPHLAININ,
            Sum(lpp_rphsales)      AS LPP_RPHSALES,
            Sum(lpp_rphkirim)      AS LPP_RPHKIRIM,
            Sum(lpp_rphprepacking) AS LPP_RPHPREPACKING,
            Sum(lpp_rphhilang)     AS LPP_RPHHILANG,
            Sum(lpp_rphlainout)    AS LPP_RPHLAINOUT,
            Sum(lpp_rphintransit)  AS LPP_RPHINTRANSIT,
            Sum(lpp_rphadj)        AS LPP_RPHADJ,
            Sum(lpp_rphakhir)      AS LPP_RPHAKHIR
            FROM   {$viewLpp}
            GROUP  BY lpp_tgl1,
                    lpp_tgl2
            ORDER  BY lpp_tgl1 DESC )";
        
        $viewLpprtSummary = "(SELECT lrt_tgl1,
            lrt_tgl2,
            SUM(NVL(lrt_rphakhir,0)) - (SUM(NVL(lrt_rphbegbal,0)) + SUM(NVL(lrt_rphbaik,0)) + SUM(NVL(lrt_rphrusak,0)) - SUM(NVL(lrt_rphsupplier,0)) - SUM(NVL(lrt_rphhilang,0)) - SUM(NVL(lrt_rphlbaik,0)) - SUM(NVL(lrt_rphlrusak,0)) + SUM(NVL(lrt_rphadj,0))) AS lrt_koreksi,
            SUM(NVL(lrt_rphbegbal,0))                                                                                                                                                                                                                             AS lrt_rphbegbal,
            SUM(NVL(lrt_rphbaik,0))                                                                                                                                                                                                                               AS lrt_rphbaik,
            SUM(NVL(lrt_rphrusak,0))                                                                                                                                                                                                                              AS lrt_rphrusak,
            SUM(NVL(lrt_rphsupplier,0))                                                                                                                                                                                                                           AS lrt_rphsupplier,
            SUM(NVL(lrt_rphhilang,0))                                                                                                                                                                                                                             AS lrt_rphhilang,
            SUM(NVL(lrt_rphlbaik,0))                                                                                                                                                                                                                              AS lrt_rphlbaik,
            SUM(NVL(lrt_rphlrusak,0))                                                                                                                                                                                                                             AS lrt_rphlrusak,
            SUM(NVL(lrt_rphadj,0))                                                                                                                                                                                                                                AS lrt_rphadj,
            SUM(NVL(lrt_rphakhir,0))                                                                                                                                                                                                                              AS lrt_rphakhir
            FROM tbtr_lpprt
            GROUP BY lrt_tgl1,
                lrt_tgl2
            ORDER BY lrt_tgl1 DESC,
                lrt_tgl2)";

        $viewLpprsSummary = "(SELECT    lrs_tgl1,
            lrs_tgl2,
            SUM(Nvl(lrs_rphakhir, 0)) 
            - ( 
                SUM(Nvl(lrs_rphbegbal, 0))
            + SUM(Nvl(lrs_rphbaik, 0))
            + SUM(Nvl(lrs_rphretur, 0)) 
                - SUM(Nvl(lrs_rphmusnah, 0)) 
                - SUM(Nvl(lrs_rphhilang, 0)) 
                - SUM(Nvl(lrs_rphlbaik, 0))
            - SUM(Nvl(lrs_rphlretur, 0)) 
                + SUM(Nvl(lrs_rphadj, 0)) 
                ) 											   AS lrs_koreksi,
            SUM(Nvl(lrs_rphbegbal, 0))                          AS lrs_rphbegbal,
            SUM(Nvl(lrs_rphbaik, 0))                            AS lrs_rphbaik,
            SUM(Nvl(lrs_rphretur, 0))                           AS lrs_rphretur,
            SUM(Nvl(lrs_rphmusnah, 0))                          AS lrs_rphmusnah,
            SUM(Nvl(lrs_rphhilang, 0))                          AS lrs_rphhilang,
            SUM(Nvl(lrs_rphlbaik, 0))                           AS lrs_rphlbaik,
            SUM(Nvl(lrs_rphlretur, 0))                          AS lrs_rphlretur,
            SUM(Nvl(lrs_rphadj, 0))                             AS lrs_rphadj,
            SUM(Nvl(lrs_rphakhir, 0))                           AS lrs_rphakhir
            FROM   tbtr_lpprs
            GROUP  BY lrs_tgl1,
                    lrs_tgl2
            ORDER  BY lrs_tgl1 DESC)";

        $lppBaik = $dbProd->query(
            "SELECT * FROM " . $viewLppSummary. ""
        );
        $lppBaik = $lppBaik->getResultArray();
        
        $lppRetur = $dbProd->query(
            "SELECT * FROM " . $viewLpprtSummary. ""
        );
        $lppRetur = $lppRetur->getResultArray();

        $lppRusak = $dbProd->query(
            "SELECT * FROM " . $viewLpprsSummary. ""
        );
        $lppRusak = $lppRusak->getResultArray();

        $data = [
            'title' => 'LPP Bulan Sebelumnya',
            'lppBaik' => $lppBaik,
            'lppRetur' => $lppRetur,
            'lppRusak' => $lppRusak,
        ];
  
        redirect()->to('/logistik/tampillppblnlalu')->withInput();
        return view('logistik/tampillppblnlalu',$data);
    }

    public function informasiproduk() {
        $dbProd = db_connect("production");
        $promoMD = $departemen = $divisi = $kategori = [];

        $promoMD = $dbProd->query(
            "SELECT prmd_tglawal, prmd_tglakhir, to_char(prmd_tglawal,'yyyymmdd') || to_char(prmd_tglakhir,'yyyymmdd') as prmd_tanggal
            FROM tbtr_promomd
            WHERE TRUNC(sysdate) BETWEEN TRUNC(prmd_tglawal) AND TRUNC(prmd_tglakhir)
            GROUP BY prmd_tglawal, prmd_tglakhir, to_char(prmd_tglawal,'yyyymmdd') || to_char(prmd_tglakhir,'yyyymmdd')
            ORDER BY prmd_tglawal, prmd_tglakhir"
        );
        $promoMD = $promoMD->getResultArray();

        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $departemen = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $departemen = $departemen->getResultArray();

        $kategori = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $kategori = $kategori->getResultArray();

        $data = [
            'title' => 'Informasi Produk',
            'promoMD' => $promoMD,
            'divisi' => $divisi,
            'departemen' => $departemen,
            'kategori' => $kategori,
        ];
  
        redirect()->to('/logistik/informasiproduk')->withInput();
        return view('logistik/informasiproduk',$data);
    }

    public function tampilinfoproduk() {
        $dbProd = db_connect("production");
        $aksi = $this->request->getVar('tombol');
        $lap = $this->request->getVar('jenisLaporan');
        $infoproduk = $divisi = $dept = $katb = [];

        // atur nilai default
        $lokasiStock = "01";
        $statusTag = $satuanJual = $kodeDivisi = $kodeDepartemen = $kodeKategoriBarang = $tanggalPromosi = $jenisMarginNegatif   = "All";
        $itemOMI = $discount2 = $promoMD = $marginNegatif = $hargaJualNol = $promoMahal = $poOutstanding = $stockKosong = $lokasiTidakAda = "Off";
        $filterTag = $filterSatuan = $filterdiv = $filterdep = $filterkat = $filteromi = $filterdisc = $filtermd = $filtermhl = $filterjual0 = $filterpo = $filterstok0 = $filterlok = $filtermargin = "";

        if(isset($_GET['statusTag'])) {if ($_GET['statusTag'] !=""){$statusTag = $_GET['statusTag']; }}
        if ($statusTag != "All" AND $statusTag != "") {
            $filterTag = " AND st_status_tag = '$statusTag' ";
        }
        if(isset($_GET['satuanJual'])) {if ($_GET['satuanJual'] !=""){$satuanJual = $_GET['satuanJual']; }}
        if ($satuanJual != "All" AND $satuanJual != "") {
            $filterSatuan = " AND st_prdcd LIKE '$satuanJual' ";
        }
        if(isset($_GET['divisi'])) {if ($_GET['divisi'] !=""){$kodeDivisi = $_GET['divisi']; }}
        if ($kodeDivisi != "All" AND $kodeDivisi != "") {
            $filterdiv = " AND st_div = '$kodeDivisi' ";
        }
        if(isset($_GET['dep'])) {if ($_GET['dep'] !=""){$kodeDepartemen = $_GET['dep']; }}
        if ($kodeDepartemen != "All" AND $kodeDepartemen != "") {
            $filterdep = " AND st_dept = '$kodeDepartemen' ";
        }
        if(isset($_GET['kat'])) {if ($_GET['kat'] !=""){$kodeKategoriBarang = $_GET['kat']; }}
        if ($kodeKategoriBarang != "All" AND $kodeKategoriBarang != "") {
            $filterkat = " AND st_dept || st_katb = '$kodeKategoriBarang' ";
        }
        if(isset($_GET['itemOMI'])) {if ($_GET['itemOMI'] !=""){$itemOMI = $_GET['itemOMI']; }}
        if (strtoupper($itemOMI) == "ON") {
            $filteromi = " AND st_pluomi != '0000000' ";
        }
        if(isset($_GET['discount2'])) {if ($_GET['discount2'] !=""){$discount2 = $_GET['discount2']; }}
        if (strtoupper($discount2)  == "ON") {
            $filterdisc = " AND NVL(st_disc_2_rph,0) > 0 ";
        }
        if(isset($_GET['promoMD'])) {if ($_GET['promoMD'] !=""){$promoMD = $_GET['promoMD']; }}
        if (strtoupper($promoMD) == "ON") {
            $filtermd = " AND NVL(st_promomd_harga,0) > 0 ";
    
            // filter : tanggal promosi
            if ($tanggalPromosi != "All" AND $tanggalPromosi != "") {
                $filtermd = " AND to_char(st_promomd_mulai,'yyyymmdd') || to_char(st_promomd_selesai,'yyyymmdd') = $tanggalPromosi ";
            }
        }
        if(isset($_GET['marginNegatif'])) {if ($_GET['marginNegatif'] !=""){$marginNegatif = $_GET['marginNegatif']; }}
        if (strtoupper($marginNegatif) == "ON") {
		
            //defenisikan query sesuai jenis laporan
          switch ($jenisMarginNegatif) {
              case "1":
                  $filtermargin = " AND (st_avgcost > st_harga_netto
                                    OR st_avgcost > st_promomd_harga_netto) ";	
                  break;
              case "2":
                  $filtermargin = " AND (st_lastcost > st_harga_netto
                                   OR st_lastcost > st_promomd_harga_netto) ";
                  break;
              case "3":
                  $filtermargin = " AND (st_harga_beli_netto > st_harga_netto
                                   OR st_harga_beli_netto > st_promomd_harga_netto) ";
                  break;
              default:
                  $filtermargin = " AND (st_avgcost > st_harga_netto 
                           OR st_lastcost > st_harga_netto
                           OR st_harga_beli_netto > st_harga_netto
                           OR st_avgcost > st_promomd_harga_netto
                           OR st_lastcost > st_promomd_harga_netto
                           OR st_harga_beli_netto > st_promomd_harga_netto) ";
          }
      }
        if(isset($_GET['hargaJualNol'])) {if ($_GET['hargaJualNol'] !=""){$hargaJualNol = $_GET['hargaJualNol']; }}
        if (strtoupper($hargaJualNol) == "ON") {
            $filterjual0 = " AND NVL(st_harga_jual,0) = 0 ";
        }
        if(isset($_GET['promoMahal'])) {if ($_GET['promoMahal'] !=""){$promoMahal = $_GET['promoMahal']; }}
        if (strtoupper($promoMahal) == "ON") {
            $filtermhl = " AND NVL(st_harga_jual,0) - NVL(st_promomd_harga,0) < 0 ";
        }
        if(isset($_GET['poOutstanding'])) {if ($_GET['poOutstanding'] !=""){$poOutstanding = $_GET['poOutstanding']; }}
        if (strtoupper($poOutstanding) == "ON") {
            $filterpo = " AND NVL(st_po_qty,0) > 0 ";
        }
        if(isset($_GET['stockKosong'])) {if ($_GET['stockKosong'] !=""){$stockKosong = $_GET['stockKosong']; }}
        if (strtoupper($stockKosong) == "ON") {
            $filterstok0 = " AND NVL(st_saldo_in_pcs,0) < 0 ";
        }
        if(isset($_GET['lokasiTidakAda'])) {if ($_GET['lokasiTidakAda'] !=""){$lokasiTidakAda = $_GET['lokasiTidakAda']; }}
        if (strtoupper($lokasiTidakAda) == "ON") {
            $filterlok = " AND st_prdcd NOT IN (SELECT lks_prdcd
                                          FROM tbmaster_lokasi
                                          WHERE lks_tiperak NOT IN ('S','Z')
                                          AND lks_koderak NOT LIKE 'D%'
                                          AND lks_prdcd IS NOT NULL) 
    
                        AND st_tag NOT IN ('A','R','N','O','T','H','X') ";
        }
        if(isset($_GET['tanggalPromosi'])) {if ($_GET['tanggalPromosi'] !=""){$tanggalPromosi = $_GET['tanggalPromosi']; }}
        if(isset($_GET['jenisMarginNegatif'])) {if ($_GET['jenisMarginNegatif'] !=""){$jenisMarginNegatif = $_GET['jenisMarginNegatif']; }}
        if(isset($_GET['kodeTag'])) {if ($_GET['kodeTag'] !=""){$kodeTag = $_GET['kodeTag']; }}

        $viewHargaBeli = "(SELECT hgb.hgb_prdcd,
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
                AND hgb.hgb_kodesupplier = sup.sup_kodesupplier (+))";

        $bln_01 = date('m', strtotime('-3 month')) ;
        $bln_02 = date('m', strtotime('-2 month')) ;
        $bln_03 = date('m', strtotime('-1 month')) ;

        $viewSalesPerDay = "(SELECT sls_prdcd                               AS spd_prdcd,
		    Nvl(sls_qty_" . $bln_01  .", 0)                      AS spd_qty_1,
		    Nvl(sls_qty_" . $bln_02  .", 0)                      AS spd_qty_2,
		    Nvl(sls_qty_" . $bln_03  .", 0)                      AS spd_qty_3,
		    Trunc(( Nvl(sls_qty_" . $bln_01  .", 0) + Nvl(sls_qty_" . $bln_02  .", 0) + Nvl(sls_qty_" . $bln_03  .", 0) ) / 90, 5) AS spd_qty,
		    Nvl(sls_rph_" . $bln_01  .", 0)                      AS spd_rph_1,
		    Nvl(sls_rph_" . $bln_02  .", 0)                      AS spd_rph_2,
		    Nvl(sls_rph_" . $bln_03  .", 0)                      AS spd_rph_3,
		    Trunc(( Nvl(sls_rph_" . $bln_01  .", 0) + Nvl(sls_rph_" . $bln_02  .", 0) + Nvl(sls_rph_" . $bln_03  .", 0) ) / 90, 5) AS spd_rph
		    FROM   tbtr_salesbulanan   )";
        
        $viewPOOutstanding = "(SELECT tpod_prdcd,
            SUM(tpod_qtypo)  AS tpod_qtypo,
            Count(tpod_nopo) AS tpod_nopo
            FROM   (SELECT tpod_prdcd,
                            tpod_qtypo,
                            tpod_nopo
                    FROM   tbtr_po_d
                    WHERE  tpod_nopo IN (SELECT tpoh_nopo
                                        FROM   tbtr_po_h
                                        WHERE  tpoh_recordid IS NULL
                                                AND Trunc(tpoh_tglpo + tpoh_jwpb) >= Trunc(
                                                    SYSDATE)
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
                    )
            GROUP  BY tpod_prdcd)";

        $viewStatusIgrIdm = "(SELECT 
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
            
            FROM TBMASTER_PRODMAST WHERE PRD_PRDCD LIKE '%0' AND PRD_DESKRIPSIPANJANG IS NOT NULL)))";

        $viewSalesRekap = "(SELECT rsl_prdcd,
            SUM(CASE WHEN rsl_group = '01' THEN rsl_qty_09 + rsl_qty_10 + rsl_qty_11 END) / 3 rekap_biru,
            SUM(CASE WHEN rsl_group = '02' THEN rsl_qty_09 + rsl_qty_10 + rsl_qty_11 END) / 3 rekap_omi,
            SUM(CASE WHEN rsl_group = '03' THEN rsl_qty_09 + rsl_qty_10 + rsl_qty_11 END) / 3 rekap_merah
            FROM   tbtr_rekapsalesbulanan
            GROUP  BY rsl_prdcd)";

        $viewExpiredInfo = "(SELECT e.lks_prdcd    AS exp_prdcd,
            e.lks_expdate  AS exp_tanggal,
            SUM(l.lks_qty) AS exp_qty
            FROM   tbmaster_lokasi l,
                    (
                    SELECT lks_prdcd,
                            Min(lks_expdate) AS lks_expdate
                    FROM   tbmaster_lokasi
                    WHERE  lks_prdcd IS NOT NULL
                            AND Nvl(lks_qty, 0) <> 0
                    GROUP  BY lks_prdcd) e
            WHERE  l.lks_prdcd = e.lks_prdcd
                    AND l.lks_expdate = e.lks_expdate
            GROUP  BY e.lks_prdcd,
                    e.lks_expdate)";

        $viewLokasiDisplayToko = "(SELECT lks_prdcd,
            lks_koderak,
            lks_kodesubrak,
            lks_tiperak,
            lks_shelvingrak,
            lks_nourut,
            lks_maxdisplay,
            lks_qty,
            ( Nvl(lks_tirkirikanan, 0) * Nvl(lks_tirdepanbelakang, 0) *
            Nvl(lks_tiratasbawah, 0) ) lks_mindisplay,
            lks_maxplano
            FROM   tbmaster_lokasi
            WHERE  ( lks_koderak LIKE 'R%'
                    OR lks_koderak LIKE 'O%' )
                    AND lks_koderak NOT LIKE '%C'
                    AND lks_tiperak <> 'S')";

        $viewLokasiDPD = "(SELECT lks_prdcd as dpd_prdcd,
            lks_koderak as dpd_koderak,
            lks_kodesubrak as dpd_kodesubrak,
            lks_tiperak as dpd_tiperak,
            lks_shelvingrak as dpd_shelvingrak,
            lks_nourut as dpd_nourut,
            lks_maxdisplay as dpd_maxdisplay,
            lks_qty as dpd_qty,
            ( Nvl(lks_tirkirikanan, 0) * Nvl(lks_tirdepanbelakang, 0) *
            Nvl(lks_tiratasbawah, 0) ) as dpd_mindisplay,
            lks_maxplano as dpd_maxplano,
            lks_noid as dpd_noid
            FROM   tbmaster_lokasi
            WHERE  lks_koderak LIKE 'D%'
                    and lks_koderak not like '%C'
                    and lks_tiperak <> 'S'
                --and to_number(substr(lks_koderak,2,2),'99') between 1 and 99
                and lks_prdcd is not null)";

        $viewProdukInformasi = "( SELECT   prd.prd_kodedivisi         AS st_div,
            div.div_namadivisi         AS st_div_nama,
            prd.prd_kodedepartement    AS st_dept,
            dep.dep_namadepartement    AS st_dept_nama,
            prd.prd_kodekategoribarang AS st_katb,
            kat.kat_namakategori       AS st_katb_nama,
            prd.prd_plumcg             AS st_plumcg,
            prd.prd_prdcd              AS st_prdcd,
            prd.prd_deskripsipanjang   AS st_nama_barang,
            prd.prd_unit               AS st_unit,
            prd.prd_frac               AS st_frac,
            Nvl(prd.prd_kodetag,' ')   AS st_tag,
            prd.prd_tgldiscontinue     AS st_tgldiscontinue,
            prd.prd_hrgjual            AS st_harga_jual,
            prd.prd_minorder           AS st_minimum_order,
            prd.prd_flagigr            AS st_flagigr,
            CASE
                WHEN Nvl(prd.prd_kodetag,' ') IN ('A',
                                                    'R',
                                                    'N',
                                                    'H',
                                                    'O',
                                                    'T',
                                                    'X') THEN 'Discontinue'
                ELSE 'Active'
            END st_status_tag,
            CASE
                WHEN prd.prd_unit='KG'
                AND    prd.prd_frac =1000 THEN stk.st_avgcost * prd.prd_frac/1000
                ELSE stk.st_avgcost                           * prd.prd_frac
            END st_avgcost,
            CASE
                WHEN prd.prd_unit='KG'
                AND    prd.prd_frac =1000 THEN stk.st_lastcost * prd.prd_frac/1000
                ELSE stk.st_lastcost                           * prd.prd_frac
            END st_lastcost,
            CASE
                WHEN Nvl(prd.prd_flagbkp1,'T') ='Y' THEN prd.prd_hrgjual / 11 * 10
                ELSE prd.prd_hrgjual
            END                  st_harga_netto,
            NVL(stk.st_saldoakhir,0) AS st_saldo_in_pcs,
            CASE
                WHEN prd.prd_unit='KG'
                AND    prd.prd_frac =1000 THEN NVL(stk.st_saldoakhir,0) * stk.st_avgcost/1000
                ELSE NVL(stk.st_saldoakhir,0)                           * stk.st_avgcost
            END                 st_saldo_rph,
            prm.prmd_hrgjual  AS st_promomd_harga,
            prm.prmd_tglawal  AS st_promomd_mulai,
            prm.prmd_tglakhir AS st_promomd_selesai,
            CASE
                WHEN Nvl(prd.prd_flagbkp1,'T') ='Y' THEN prm.prmd_hrgjual / 11 * 10
                ELSE prm.prmd_hrgjual
            END             st_promomd_harga_netto,
            pkm.pkm_pkmt AS st_pkm,
            spd.spd_qty  AS st_spd,
            CASE
                WHEN Nvl(spd.spd_qty,0) > 0 THEN Round(Nvl(stk.st_saldoakhir,0) / spd.spd_qty)
                ELSE 999999
            END st_dsi_avgsales,
            CASE
                WHEN Nvl(stk.st_sales,0) != 0 THEN (NVL(stk.st_saldoawal,0) + NVL(stk.st_saldoakhir,0)) / 2 / stk.st_sales *
                        (
                                SELECT To_char(SYSDATE,'DD')
                                FROM   dual)
                ELSE 0
            END                                               st_dsi_bulan_ini,
            poo.tpod_qtypo                                    AS st_po_qty,
            sii.status_igr_idm                                AS st_igr_idm,
            spd.spd_qty_1                                     AS st_sales_bln_1,
            spd.spd_qty_2                                     AS st_sales_bln_2,
            spd.spd_qty_3                                     AS st_sales_bln_3,
            stk.st_sales                                      AS st_sales_bln_ini,

            spd.spd_rph_1                                     AS st_sales_rph_bln_1,
            spd.spd_rph_2                                     AS st_sales_rph_bln_2,
            spd.spd_rph_3                                     AS st_sales_rph_bln_3,

            rek.rekap_biru                                    AS st_rekap_biru,
            rek.rekap_omi                                     AS st_rekap_omi,
            rek.rekap_merah                                   AS st_rekap_merah,
            Nvl(hgb.hgb_kodesupplier,'Z9999')                 AS st_kode_supplier,
            Nvl(hgb.hgb_namasupplier,'Z9999 Tidak diketahui') AS st_nama_supplier,
            Nvl(hgb.hgb_lead_time,0)                          AS st_lead_time,
            prd.prd_perlakuanbarang                           AS st_perlakuan_barang,
            hgb.hgb_hrgbeli  * prd.prd_frac                    AS st_harga_beli,
            hgb.hgb_nilaidpp * prd.prd_frac                    AS st_harga_beli_netto,
            hgb.hgb_nilaidpp                                   AS st_harga_beli_omi,
            hgb.hgb_tglmulaidisc01                             AS st_disc_1_mulai,
            hgb.hgb_tglakhirdisc01                             AS st_disc_1_selesai,
            hgb.hgb_persendisc01                               AS st_disc_1_persen,
            hgb.hgb_rphdisc01                                  AS st_disc_1_rph,
            hgb.hgb_flagdisc01                                 AS st_disc_1_flag,
            hgb.hgb_tglmulaidisc02                             AS st_disc_2_mulai,
            hgb.hgb_tglakhirdisc02                             AS st_disc_2_selesai,
            hgb.hgb_persendisc02                               AS st_disc_2_persen,
            hgb.hgb_rphdisc02                                  AS st_disc_2_rph,
            hgb.hgb_flagdisc02                                 AS st_disc_2_flag,
            hgb.hgb_top                                        AS st_top,	
            hgb.hgb_minrph                                     AS st_minrph,		       
            Nvl(omi.prc_pluomi,'0000000')                      AS st_pluomi,
            Nvl(omi.prc_kodetag,' ')                           AS st_tag_omi,
            exp.exp_tanggal                                    AS st_exp_tanggal,
            exp.exp_qty                                        AS st_exp_qty,
            lks.lks_koderak                                    AS st_lks_koderak,
            lks.lks_kodesubrak 								  AS st_lks_kodesubrak,
            lks.lks_tiperak 									  AS st_lks_tiperak,
            lks.lks_shelvingrak 								  AS st_lks_shelvingrak, 
            lks.lks_nourut 									  AS st_lks_nourut,

            dpd.dpd_koderak                                    AS st_dpd_koderak,
            dpd.dpd_kodesubrak 								  AS st_dpd_kodesubrak,
            dpd.dpd_tiperak 									  AS st_dpd_tiperak,
            dpd.dpd_shelvingrak 								  AS st_dpd_shelvingrak, 
            dpd.dpd_nourut 									  AS st_dpd_nourut,
            dpd.dpd_noid 									  AS st_dpd_noid,

            kph.ksl_mean                                       AS st_kph_mean,
            bpb.mstd_bpb_pertama 							  AS mstd_bpb_pertama,	
            bpb.mstd_bpb_terakhir							  AS mstd_bpb_terakhir
            FROM   tbmaster_prodmast prd,
                    (
                        SELECT *
                        FROM   tbmaster_stock
                        WHERE  st_lokasi = '01') stk,
                    (
                        SELECT prmd_prdcd,
                                prmd_hrgjual,
                                prmd_tglawal,
                                prmd_tglakhir
                        FROM   tbtr_promomd
                        WHERE  Trunc(SYSDATE) BETWEEN Trunc(prmd_tglawal) AND    Trunc(prmd_tglakhir)) prm,
                    tbmaster_kkpkm pkm,
                    tbmaster_divisi div,
                    tbmaster_departement dep,
                    (select * from tbmaster_kph where pid = '102016') kph,
                    {$viewHargaBeli} hgb,
                    (
                        SELECT kat_kodedepartement
                                        || kat_kodekategori AS kat_kodekategori,
                                kat_namakategori
                        FROM   tbmaster_kategori) kat,
                    {$viewSalesPerDay} spd,
                    {$viewPOOutstanding} poo,
                    {$viewStatusIgrIdm} sii,
                    (
                                    SELECT DISTINCT prc_pluigr,
                                                    prc_pluomi,
                                                    prc_kodetag
                                    FROM            tbmaster_prodcrm
                                    WHERE           prc_group = 'O') omi,
                    {$viewSalesRekap} rek,
                    {$viewExpiredInfo} exp,
                    {$viewLokasiDisplayToko} lks,
                    {$viewLokasiDPD} dpd,
                    (SELECT mstd_prdcd,
                        MIN(mstd_create_dt) AS mstd_bpb_pertama,
                        MAX(mstd_create_dt) AS mstd_bpb_terakhir
                        FROM tbtr_mstran_d 
                        WHERE mstd_recordid IS NULL AND mstd_typetrn ='B'
                        GROUP BY mstd_prdcd) bpb

            WHERE  substr(prd.prd_prdcd,1,6)
                        || '0' = stk.st_prdcd (+)
            AND    prd.prd_prdcd = prm.prmd_prdcd (+)
            AND    prd.prd_plumcg = kph.prdcd (+)
            AND    prd.prd_prdcd = pkm.pkm_prdcd (+)
            AND    substr(prd.prd_prdcd,1,6)
                        || '0' = hgb.hgb_prdcd (+)
            AND    prd.prd_kodedivisi = div.div_kodedivisi (+)
            AND    prd.prd_kodedepartement = dep.dep_kodedepartement (+)
            AND    prd.prd_kodedepartement
                        || prd.prd_kodekategoribarang = kat.kat_kodekategori (+)
            AND    prd.prd_prdcd = spd.spd_prdcd (+)
            AND    substr(prd.prd_prdcd,1,6)
                        || '0' = poo.tpod_prdcd (+)
            AND    prd.prd_prdcd = sii.prd_prdcd (+)
            AND    substr(prd.prd_prdcd,1,6)
                        || '0' = omi.prc_pluigr (+)
            AND    prd.prd_prdcd = rek.rsl_prdcd (+)
            AND    prd.prd_prdcd = exp.exp_prdcd (+)  
            AND    prd.prd_prdcd = lks.lks_prdcd (+)  
            AND    prd.prd_prdcd = dpd.dpd_prdcd (+)  
            AND    prd.prd_prdcd = bpb.mstd_prdcd (+)  
            AND    prd.prd_frac IS NOT NULL )";

        if($lap == "1A") {
            $infoproduk = $dbProd->query(
                "SELECT *
                FROM " . $viewProdukInformasi . "
                WHERE st_prdcd IS NOT NULL
                $filterTag
                $filterSatuan
                $filterdiv
                $filterdep
                $filterkat
                $filteromi
                $filterdisc
                $filtermd
                $filtermhl
                $filterjual0
                $filterpo
                $filterstok0
                $filterlok
                $filtermargin
                ORDER BY st_div , st_dept, st_katb, st_nama_barang,st_prdcd"
            );
            $infoproduk = $infoproduk->getResultArray();
        } else if($lap == "1B") {
            $infoproduk = $dbProd->query(
                "SELECT *
                FROM " . $viewProdukInformasi . "
                WHERE st_prdcd IS NOT NULL 
                -- stock > avg sales 14 hari
                AND TRUNC(st_saldo_in_pcs /st_frac) > (TRUNC(st_sales_bln_1 /st_frac) + TRUNC(st_sales_bln_2 /st_frac) + TRUNC(st_sales_bln_3 /st_frac)) / 6 
                -- satuan jual nol saja
                AND st_prdcd LIKE '%0'
                $filterTag
                $filterSatuan
                $filterdiv
                $filterdep
                $filterkat
                $filteromi
                $filterdisc
                $filtermd
                $filtermhl
                $filterjual0
                $filterpo
                $filterstok0
                $filterlok
                $filtermargin
                ORDER BY st_div , st_dept, st_katb, st_nama_barang,st_prdcd"
            );
            $infoproduk = $infoproduk->getResultArray();
        } else if($lap == "1C") {
            $infoproduk = $dbProd->query(
                "SELECT *
                FROM " . $viewProdukInformasi . "
                WHERE st_prdcd IS NOT NULL
                $filterTag
                $filterSatuan
                $filterdiv
                $filterdep
                $filterkat
                $filteromi
                $filterdisc
                $filtermd
                $filtermhl
                $filterjual0
                $filterpo
                $filterstok0
                $filterlok
                $filtermargin
                ORDER BY st_div , st_dept, st_katb, st_nama_barang,st_prdcd"
            );
            $infoproduk = $infoproduk->getResultArray();
        };

        $divisi = $dbProd->query(
            "SELECT div_kodedivisi, div_namadivisi FROM tbmaster_divisi ORDER BY div_kodedivisi"
        );
        $divisi = $divisi->getResultArray();

        $dept = $dbProd->query(
            "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
            from tbmaster_departement 
            left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
            order by dep_kodedivisi,dep_kodedepartement"
        );
        $dept = $dept->getResultArray();

        $katb = $dbProd->query(
            "SELECT kat.kat_kodedepartement,
            dep.dep_namadepartement AS kat_namadepartement,
            kat.kat_kodekategori,
            kat.kat_namakategori
            FROM tbmaster_kategori kat,
                tbmaster_departement dep
            WHERE kat.kat_kodedepartement = dep.dep_kodedepartement (+)
            ORDER BY kat_kodedepartement,
                kat_kodekategori"
        );
        $katb = $katb->getResultArray();

        $data = [
            'title' => 'Data Informasi Produk',
            'infoproduk' => $infoproduk,
            'kodeDivisi' => $kodeDivisi,
            'kodeDepartemen' => $kodeDepartemen,
            'kodeKategoriBarang' => $kodeKategoriBarang,
            'divisi' =>$divisi,
            'dept' => $dept,
            'katb' => $katb,
            'lap' => $lap,
            'itemOMI' => $itemOMI,
            'discount2' => $discount2,
            'promoMD' => $promoMD,
            'marginNegatif' => $marginNegatif,
            'hargaJualNol' => $hargaJualNol,
            'promoMahal' => $promoMahal,
            'stockKosong' => $stockKosong,
            'poOutstanding' => $poOutstanding,
        ];
  
        // d($data);
        redirect()->to('/logistik/tampilinfoproduk')->withInput();
        return view('logistik/tampilinfoproduk',$data);
    }

    public function kesegaran(){
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

    public function pertemanan() {
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
                ORDER BY PLU ASC,PERTEMANAN ASC"
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
}






