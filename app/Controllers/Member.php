<?php

namespace App\Controllers;

class Member extends BaseController
{
    public function index(){
        return view('member/cekmember');
    }

    public function cekmember() {
        $dbProd = db_connect('production');
        $status = $this->request->getVar('statuscari');
        $cari = strtoupper($this->request->getVar('cari'));
        $aksi = $this->request->getVar('tombol');
        $member = [];
        $filtercari = "";

        if($status == "nama") {
            $filtercari = " cus_namamember like '%$cari%'  ";
        } else if($status == "kode") {
            $filtercari = " cus_kodemember like '%$cari%'  ";
        } else if($status == "ktp") {
            $filtercari = " cus_noktp like '%$cari%'  ";
        } else if($status == "hp") {
            $filtercari = " cus_hpmember like '%$cari%'  ";
        }
        
        if($aksi == "btncekmbr") {
          $member = $dbProd->query(
            "select 
            cus_kodemember as kode,
            cus_namamember as nama_member,
            cus_alamatmember1 as alamat,
            cus_tlpmember || ' / ' || cus_hpmember as no_hp,
            case
              when cus_kodeoutlet = '2' then 'MEMBER MERAH'
              when cus_kodeoutlet = '5' then 'MEMBER MERAH'
            else 'MEMBER BIRU' end jns_mbr,
            case 
              when cus_recordid is null then 'AKTIF'
            else 'NON-AKTIF' end status
            from tbmaster_customer
            where $filtercari       
            order by kode, nama_member asc"
          );
          $member = $member->getResultArray();
        };       

      $data = [
          'title' => 'Cek Data Member',
          'member' => $member,
      ];
        
      // redirect()->to('member/cekmember')->withInput();
      return view('member/cekmember',$data);
    }

    public function transaksimember() {
        date_default_timezone_set("Asia/Jakarta");
        $kode = strtoupper($this->request->getVar('kode'));
        $awal = $this->request->getVar('awal');
        $akhir = $this->request->getVar('akhir');
        $aksi = $this->request->getVar('tombol');
        $dbSim = db_connect('default');
        $trx = [];
        $ttl = [];
        
        if($aksi == 'btntrxmbr') {
          $trx = $dbSim->query(
            "select tgl_transaksi,jam,tipe,ksr || '.' || kassa || '.' || notrx as nostruk,nominal,
                tunai || kredit || dc || cc1 || cc2 || isaku || voucher || kr_usaha || transfer as metode_bayar from (
                select 
                jh_transactiondate as tgl_transaksi,
                to_char(jh_timestart,'hh24:mi:ss') as jam,
                jh_transactiontype as tipe,
                jh_cashierid as ksr,
                jh_cashierstation as kassa,
                jh_transactionno as notrx,
                jh_transactionamt as nominal,
                case 
                  when nvl(jh_transactioncashamt,0) > 0 then 'Tunai ' end as tunai,
                case 
                  when nvl(jh_transactioncreditamt,0) > 0 then 'Kredit ' end as kredit,
                case 
                  when nvl(jh_debitcardamt,0) > 0 then 'DebitCard ' end as dc,
                  case 
                  when nvl(jh_ccamt1,0) > 0 then 'CreditCard ' end as cc1,
                case 
                  when nvl(jh_ccamt2,0) > 0 then 'CreditCard ' end as cc2,
                case 
                  when nvl(jh_isaku_amt,0) > 0 then 'Isaku ' end as isaku,
                case 
                  when nvl(jh_voucheramt,0) > 0 then 'Vouvher ' end as voucher,
                case 
                  when nvl(jh_kmmamt,0) > 0 then 'KreditUsaha ' end as kr_usaha,
                case 
                  when nvl(jh_transferamt,0) > 0 then 'Transfer ' end as transfer
                from tbtr_jualheader
                where jh_cus_kodemember = '$kode'
                and trunc(jh_transactiondate) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
                and jh_recordid is null)
                order by tgl_transaksi asc"
                );
                $trx = $trx->getResultArray();
  
                foreach($trx as $tm) {
                  array_push($ttl, $tm['NOMINAL']);
                }        
                
                $ttl = [
                  'ttl' => array_sum($ttl)
                ];
        }
              
        $data = [
            'title' => 'History Transaksi Member',
            'trx' => $trx,
            'ttl' => $ttl,
            'kode' => $kode,
            'awal' => $awal,
            'akhir' => $akhir,
        ];
        
        redirect()->to('trxmbr')->withInput();
        return view('member/trxmbr',$data);  
    }

    public function tampildatatransaksi() {
        $dbSim = db_connect("default");
        $detail = $notrx = $member = $ttl = [];
        $kd = $_GET['mbr'];
        $nostr = $_GET['notrx'];
        $notrx = substr($nostr,7,5);
        $ksr = substr($nostr,0,3);
        $kss = substr($nostr,4,2);
        $tgl = $_GET['tgltrx'];
        $jam = $_GET['jamtrx'];
  
        $detail = $dbSim->query(
          "select 
          trjd_cus_kodemember as kode,
          trjd_create_by || '.' || trjd_transactionno as nostruk,
          trjd_prdcd as plu,
          trjd_prd_deskripsipendek as deskripsi,
          trjd_quantity as qty,
          trjd_unitprice as unit_price,
          trjd_nominalamt as nominal_rph,
          trjd_transactiontype as tipe
          from tbtr_jualdetail where trunc(trjd_transactiondate) = '$tgl' 
          and trjd_cus_kodemember = '$kd'      				
          and trjd_transactionno = '$notrx'
          and trjd_create_by = '$ksr'
          and trjd_cashierstation = '$kss'        		
          and trjd_recordid is null
          order by trjd_seqno asc"
        );
        $detail = $detail->getResultArray();
        foreach($detail as $dt) {
          array_push($ttl, $dt['NOMINAL_RPH']);
        }        
        
        $ttl = [
          'ttl' => array_sum($ttl)
        ];
  
        $member = $dbSim->query(
          "select
          cus_namamember as nmmbr
          from tbmaster_customer where cus_kodemember = '$kd'"
        );
        $member = $member->getResultArray();
  
        $data = [
          'title' => 'Detail Transaksi Member',
          'kd' => $kd,
          'nostr' => $nostr,
          'notrx' => $notrx,
          'tgl' => $tgl,
          'detail' => $detail,
          'member' => $member,
          'jam' => $jam,
          'ttl' => $ttl,
        ];
  
        return view('member/tampildatatransaksi',$data);
    }    

    public function pengeluaranhadiah() {
        $dbProd = db_connect("production");
        $detail = $rekap = [];
        $awal = $this->request->getVar('awal');
        $akhir = $this->request->getVar('akhir');
        $aksi = $this->request->getVar('tombol');
  
        if($aksi == "btngift1") {
          $detail = $dbProd->query(
            "select 
            kd_promosi as kode_promo,
            jenis_hadiah as jenis,
            case
              when ket_hadiah is null then ' '
              else ket_hadiah
            end as keterangan,
            kd_member as kode_member,
            tgl_trans as tanggal,
            create_by || '.' || kd_station || '.' || trans_no as nostruk,
            jmlh_hadiah as jumlah
            from m_gift_h 
            where trunc(tgl_trans) between to_date('$awal','yyyy-mm-dd') and to_date('$akhir','yyyy-mm-dd')
            order by kode_promo, nostruk asc"
          );
  
          $detail = $detail->getResultArray();
        } elseif($aksi == "btngift2"){
          $rekap = $dbProd->query(
            "select
            KD_PROMOSI as kode_promo,
            JENIS_HADIAH as jenis,
            case
              when ket_hadiah is null then ' '
              else ket_hadiah
            end as keterangan,
            TIPE,
            sum(JMLH_HADIAH) as jumlah
            FROM M_GIFT_H
            WHERE  TRUNC(TGL_TRANS) between to_date('$awal','YYYY-MM-DD') and to_date('$akhir','YYYY-MM-DD') 
            GROUP BY KD_PROMOSI, JENIS_HADIAH, KET_HADIAH, TIPE  
            ORDER BY KD_PROMOSI"
          );
  
          $rekap = $rekap->getResultArray();
        };
  
        $data = [
          'title' => 'Data Pengeluaran Hadiah',
          'detail' => $detail,
          'rekap' => $rekap,
          'awal' => $awal,
          'akhir' => $akhir,
        ];
  
        
        redirect()->to('pengeluaranhadiah')->withInput();
        return view('member/pengeluaranhadiah',$data);
    }

    public function salesmember() {
      $dbProd = db_connect('production');

      $daftarOutlet = $dbProd->query(
        "SELECT out_kodeoutlet, out_namaoutlet, sub_kodesuboutlet, sub_namasuboutlet 
        from tbmaster_outlet
        left join tbmaster_suboutlet on sub_kodeoutlet=out_kodeoutlet
        order by out_kodeoutlet,sub_kodesuboutlet"
      );
      $daftarDepartement = $dbProd->query(
        "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
        from tbmaster_departement 
        left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
        order by dep_kodedivisi,dep_kodedepartement"
      );

      $daftarOutlet = $daftarOutlet->getResultArray();
      $daftarDepartement = $daftarDepartement->getResultArray();
     
      $data = [
        'title' => 'Evaluasi Sales Member',
        'outlet' => $daftarOutlet,
        'departement' => $daftarDepartement,
      ];
      return view('member/salesmember', $data);
    }

    public function tampilsalesmember() {
      $dbProd = db_connect('production');
      $tglSekarang = date('d-m-Y-H:i:s');
      $tglAwal = $this->request->getVar('tglawal');
      $tglAkhir = $this->request->getVar('tglakhir');
      $jenisLaporan = $this->request->getVar('jenislaporan');
      $jenisTransaksi = $this->request->getVar('jenistransaksi');
      $jenisMember = $this->request->getVar('jenismember');
      $outlet = $this->request->getVar('outlet');
      $segmentasi = $this->request->getVar('segmentasi');
      $memberTertentu = strtoupper($this->request->getVar('membertertentu'));
      $jenisProduk = $this->request->getVar('jenisproduk');
      $departement = $this->request->getVar('departement');
      $supplier = strtoupper($this->request->getVar('supplier'));
      $plu = $this->request->getVar('plu');

      $tipeoutlet = $permember = $produk = $bulan = $struk = $filename = [];
      $filtertran = $filtermember = $filterkodemember = $filteroutlet = $filterSegmen = $filterproduk = $filterdep = $filterplu = $filtersup = "";

      // Set Filter Data
      switch ($jenisTransaksi) {
        case 'all':
          $filtertran = "" ; 
          break;
        case 'reguler':
          $filtertran = " and OBI_NOPB IS NULL ";
          break;
        case 'klik':
          $filtertran = " and OBI_NOPB IS NOT NULL " ; 
          break;
        default: "";
      }

      // Pilihan Member
      switch ($jenisMember) {
        case 'all':
          $filtermember = "";
          break;
        case 'nontmi':
          $filtermember = " and nvl(cus_jenismember,'X') != 'T' ";
          break;
        case 'mm':
            $filtermember = " and nvl(cus_flagmemberkhusus,'T')='Y' ";
          break;
        case 'mmtmi':
          $filtermember = " and nvl(cus_flagmemberkhusus,'T')='Y' and cus_jenismember='T' ";
          break;
        case 'mmnontmi':
          $filtermember = " and nvl(cus_flagmemberkhusus,'T')='Y' and nvl(cus_jenismember,'X') != 'T' ";
          break;
        case 'mmhoreka':
          $filtermember = " and nvl(cus_flagmemberkhusus,'T')='Y' and cus_kodeoutlet='5' and cus_kodesuboutlet='5A' ";
          break;
        case 'mb':
          $filtermember = " and nvl(cus_flagmemberkhusus,'T')!='Y' ";
          break;
        case 'mbend':
          $filtermember = " and nvl(cus_flagmemberkhusus,'T')!='Y' and nvl(cus_kodeoutlet,6)='6' ";
          break;
        case 'mbomi':
          $filtermember = " and nvl(cus_flagmemberkhusus,'T')!='Y' and cus_kodeoutlet='4' ";
          break;
        default:
          $filtermember = " and cus_kodemember='' ";
          break;
      }

      if ($memberTertentu == "") {
        $filterkodemember = "";
      }else{
        $memberFokus = '';
        $kodeMember = $memberTertentu;
        $kdMemberEx = explode(",",$kodeMember);
        foreach($kdMemberEx as $kdx){
          $kdx = "'$kdx',";
          $memberFokus .= $kdx;
          $panjangstr = strlen($memberFokus)-1;
        }
        $memberGab = substr($memberFokus,0,$panjangstr);
        $filterkodemember = " and cus_kodemember in ( $memberGab ) ";
      }

      // Pilih Outlet
      $kodeOutlet = substr($outlet,0,1);
      $kodeSubOutlet = substr($outlet,1,2);

      if($outlet=="all"){
        $filteroutlet = "" ; 
      }elseif($kodeSubOutlet==""){
        $filteroutlet = " and nvl(cus_kodeoutlet,6)='$kodeOutlet' ";
      }else{
        $filteroutlet = " and nvl(cus_kodeoutlet,6)='$kodeOutlet' and cus_kodesuboutlet ='$kodeSubOutlet' ";
      }

      // Pilih Segmentasi Member
      if($segmentasi=="0"){
        $filterSegmen = "";
      }else{
        $filterSegmen = " and crm_idsegment ='$segmentasi' ";
      }

      // Pilihan Produk
      switch ($jenisProduk) {
        case 'all':
          # code...
          break;
        case 'itempromo':
          $filterproduk = " and concat(rpad(trjd_prdcd,6),'0') IN   (SELECT NON_PRDCD FROM TBMASTER_PLUNONPROMO) ";
          break;
        case 'itemnonpromo':
          $filterproduk = " and concat(rpad(trjd_prdcd,6),'0') NOT IN   (SELECT NON_PRDCD FROM TBMASTER_PLUNONPROMO) ";
          break;
        default:"";
      }

      // Pilih Departement
      $kodeDivisi = substr($departement,0,1);
      $kodeDepartement = substr($departement,1,2);

      if ($departement=="all") {
        $filterdep = "";
      }elseif ($kodeDepartement=="") {
        $filterdep = " and prd_kodedivisi='$kodeDivisi' ";
      }else{
        $filterdep = " and prd_kodedivisi='$kodeDivisi' and prd_kodedepartement ='$kodeDepartement' ";
      }

      // Pilih input PLU tertentu
      if ($plu=="") {
        $filterplu = "";
      }else{
        $pluFokus = "";
        $pluEx = explode(",",$plu);
        foreach($pluEx as $plu0){
          $plu0 = sprintf("%07s",$plu0);
          $plu123 = "'". substr($plu0,0,6)."0'".",'".substr($plu0,0,6)."1'".",'".substr($plu0,0,6)."2'".",'".substr($plu0,0,6)."3',";
          $pluFokus .= $plu123;
          $panjangstr = strlen($pluFokus)-1;
        }
        $pluGab = substr($pluFokus,0,$panjangstr);
        $filterplu =" and trjd_prdcd in ($pluGab) ";
      }

      // Pilih PLU dari supplier Tertentu
      if ($supplier=="") {
        $filtersup = "";
      }else{
        $filtersup = " and concat(rpad(trjd_prdcd,6),'0') in ( select hgb_prdcd from tbmaster_hargabeli where hgb_kodesupplier='$supplier' )" ;
      }

      // Query berdasarkan jenis laporan yg dipilih
      if ($jenisLaporan=="tipeoutlet") {
        $tipeoutlet = $dbProd->query(
          "SELECT cus_kodeoutlet as KDOUTLET,out_namaoutlet as NAMAOUTLET,cus_kodesuboutlet as KDSUBOUTLET,sub_namasuboutlet as NAMASUBOUTLET,
          count(distinct TO_DATE(TRJD_TRANSACTIONDATE,'DD-MM-YYYY')) as HARISALES,
          count (distinct concat(concat(concat(trjd_create_by,trjd_cashierstation),trjd_transactionno),trjd_transactiondate)||trjd_cus_kodemember) as SLIP,    
          count (distinct rpad(trjd_prdcd,6)||trjd_cus_kodemember) as PRODUK,
          count(distinct trjd_cus_kodemember) as JML_MEMBER,  
          count(distinct TO_DATE(TRJD_TRANSACTIONDATE,'DD-MM-YYYY')||TRJD_CUS_KODEMEMBER) as KUNJUNGAN,
          /*Qty Sales */ 
          sum (case 
               when trjd_transactiontype='S' and prd_unit<>'KG' then (trjd_quantity * prd_frac)*1 
               when trjd_transactiontype='R' and prd_unit<>'KG' then (trjd_quantity * prd_frac)*(-1) 
               when trjd_transactiontype='S' and prd_unit='KG' then (trjd_quantity / prd_frac)*1 
               when trjd_transactiontype='R' and prd_unit='KG' then (trjd_quantity / prd_frac)*(-1) 
               end) as QTY_SALES, 
          /*Sales Gross*/ 
              sum(case 
                  when (trjd_transactiontype='S')and(cus_kodeoutlet='4') and(trjd_flagtax2='Y') then trjd_nominalamt*(1+(prs_nilaippn/100)) 
                  when trjd_transactiontype='S' then trjd_nominalamt*1 
                  when trjd_transactiontype='R' then trjd_nominalamt*(-1) 
                  end) as RPH_GROSS, 
          /*Sales Nett*/   
              sum(case   
                  /*non omi */   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(1+(prs_nilaippn/100))   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1   
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-(1+(prs_nilaippn/100)))   
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1)   
                  /*omi */   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N' )='Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1   
                  end) as S_NETT,   
                
             /*Margin*/   
             sum(case   
                  /*non omi */   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/(1+(prs_nilaippn/100)) - (trjd_baseprice * trjd_quantity))   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))   
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/(1+(prs_nilaippn/100)) - (trjd_baseprice * trjd_quantity))*(-1)   
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))*(-1)   
                  /*pengecualian untuk unit KG*/   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/(1+(prs_nilaippn/100)) - (trjd_baseprice * trjd_quantity/1000))   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))  
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/(1+(prs_nilaippn/100)) - (trjd_baseprice * trjd_quantity/1000))*(-1)    
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))*(-1)    
                  /*omi */   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)   
                  end) as MARGIN
                  
          from tbtr_jualdetail 
          left join tbmaster_prodmast on trjd_prdcd=prd_prdcd 
          left join tbmaster_customer on trjd_cus_kodemember=cus_kodemember
          left join tbmaster_perusahaan on prs_kodeigr=trjd_kodeigr
          left join tbmaster_outlet on out_kodeoutlet=cus_kodeoutlet
          left join tbmaster_suboutlet on sub_kodesuboutlet=cus_kodesuboutlet
          LEFT JOIN TBTR_OBI_H 
            ON trjd_cus_kodemember         =obi_kdmember
            AND trjd_cashierstation        =obi_kdstation     
            AND TRUNC(trjd_transactiondate)=TRUNC(obi_tglstruk)     
            AND trjd_transactionno         =obi_nostruk     
            AND trjd_create_by             =obi_modifyby  
          where trunc(trjd_transactiondate)  between to_date('$tglAwal','YYYY-MM-DD') and to_date('$tglAkhir','YYYY-MM-DD') 
          $filtertran
          $filtermember
          $filteroutlet
          $filterSegmen
          $filterkodemember
          $filterproduk
          $filterdep
          $filtersup
          $filterplu
          group by cus_kodeoutlet,out_namaoutlet, cus_kodesuboutlet,sub_namasuboutlet 
          order by kdoutlet,kdsuboutlet"
        );
        $tipeoutlet = $tipeoutlet->getResultArray();
      } elseif($jenisLaporan=="member"){
        $permember = $dbProd->query(
          "SELECT cus_kodemember as KODEMEMBER,    
          cus_namamember as NAMAMEMBER,    
          cus_kodeigr as KODEIGR,    
          CUS_FLAGMEMBERKHUSUS as MM,   
          cus_jenismember as JENIS, 
          nvl(CUS_KODEOUTLET,6) as OUTLET,             /* member yang outletnya kosong dibikin outlet 6 */    
          CUS_KODESUBOUTLET as SUBOUTLET,    
          CRM_IDSEGMENT as IDSEGMEN,    
          SEG_NAMA as SEGMENTASI,    
          /*menambahkan kolom KUNJUNGAN,SLIP,PRODUK*/    
          count(distinct rpad(trjd_transactiondate,9)||trjd_cus_kodemember) as KUNJUNGAN,    
          count (distinct concat(concat(concat(trjd_create_by,trjd_cashierstation),trjd_transactionno),trjd_transactiondate)) as SLIP,    
          count (distinct rpad(trjd_prdcd,6)||trjd_cus_kodemember) as PRODUK,
          /*menambahkan kolom S_GROSS,S_NETT,MARGIN*/    
          /*Sales Gross*/   
              sum(case   
                  when (trjd_transactiontype='S')and(cus_kodeoutlet='4') and(trjd_flagtax2='Y') then trjd_nominalamt*1.11   
                  when trjd_transactiontype='S' then trjd_nominalamt*1   
                  when trjd_transactiontype='R' then trjd_nominalamt*(-1)   
                  end) as S_GROSS,   
          /*Sales Nett*/     
              sum(case     
                  /*non omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1.11     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1.11)     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1)     
                  /*omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N' )='Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1     
                  end) as S_NETT,     
                  
            /*Margin*/     
            sum(case     
                  /*non omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))*(-1)     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))*(-1)     
                  /*pengecualian untuk unit KG*/     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity/1000))     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity/1000))*(-1)    
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))*(-1)    
            /*omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)     
                  end) as MARGIN
                      
          FROM tbtr_jualdetail    
          LEFT JOIN tbmaster_customer on trjd_cus_kodemember=cus_kodemember    
          LEFT JOIN tbmaster_customercrm on trjd_cus_kodemember=crm_kodemember    
          LEFT JOIN tbmaster_segmentasi on crm_idsegment=seg_id    
          LEFT JOIN tbmaster_prodmast on trjd_prdcd=prd_prdcd    
          LEFT JOIN TBTR_OBI_H 
            ON trjd_cus_kodemember         =obi_kdmember
            AND trjd_cashierstation        =obi_kdstation     
            AND TRUNC(trjd_transactiondate)=TRUNC(obi_tglstruk)     
            AND trjd_transactionno         =obi_nostruk     
            AND trjd_create_by             =obi_modifyby  
          where trunc(trjd_transactiondate)  between to_date('$tglAwal','YYYY-MM-DD') and to_date('$tglAkhir','YYYY-MM-DD') 
          $filtertran
          $filtermember
          $filteroutlet
          $filterSegmen
          $filterkodemember
          $filterproduk
          $filterdep
          $filtersup
          $filterplu
          group by cus_kodemember, cus_namamember, cus_kodeigr, CUS_FLAGMEMBERKHUSUS, cus_jenismember, nvl(CUS_KODEOUTLET,6), CUS_KODESUBOUTLET, CRM_IDSEGMENT, SEG_NAMA
          order by KODEMEMBER"
        );

        $permember = $permember->getResultArray();
      }elseif($jenisLaporan=="produk"){
        $produk = $dbProd->query(
          "SELECT prd_kodedivisi as DIV, 
          prd_kodedepartement as DEP, 
          prd_kodekategoribarang as KATB, 
          concat(rpad(trjd_prdcd,6),'0') as PLU, 
          prd_deskripsipanjang as DESKRIPSI, 
          count(distinct TO_DATE(TRJD_TRANSACTIONDATE,'DD-MM-YYYY')) as HARISALES,
          count (distinct concat(concat(concat(trjd_create_by,trjd_cashierstation),trjd_transactionno),trjd_transactiondate)) as SLIP,    
          count(distinct trjd_cus_kodemember) as JML_MEMBER,   
          /*Qty Sales */ 
          sum (case 
              when trjd_transactiontype='S' and prd_unit<>'KG' then (trjd_quantity * prd_frac)*1 
              when trjd_transactiontype='R' and prd_unit<>'KG' then (trjd_quantity * prd_frac)*(-1) 
              when trjd_transactiontype='S' and prd_unit='KG' then (trjd_quantity / prd_frac)*1 
              when trjd_transactiontype='R' and prd_unit='KG' then (trjd_quantity / prd_frac)*(-1) 
              end) as QTY_SALES, 
          /*Sales Gross*/ 
              sum(case 
                  when (trjd_transactiontype='S')and(cus_kodeoutlet='4') and(trjd_flagtax2='Y') then trjd_nominalamt*(1+(prs_nilaippn/100)) 
                  when trjd_transactiontype='S' then trjd_nominalamt*1 
                  when trjd_transactiontype='R' then trjd_nominalamt*(-1) 
                  end) as RPH_GROSS, 
          /*Sales Nett*/   
              sum(case   
                  /*non omi */   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(1+(prs_nilaippn/100))   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1   
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-(1+(prs_nilaippn/100)))   
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1)   
                  /*omi */   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N' )='Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1   
                  end) as S_NETT,   
                
            /*Margin*/   
            sum(case   
                  /*non omi */   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/(1+(prs_nilaippn/100)) - (trjd_baseprice * trjd_quantity))   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))   
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/(1+(prs_nilaippn/100)) - (trjd_baseprice * trjd_quantity))*(-1)   
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))*(-1)   
                  /*pengecualian untuk unit KG*/   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/(1+(prs_nilaippn/100)) - (trjd_baseprice * trjd_quantity/1000))   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))  
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/(1+(prs_nilaippn/100)) - (trjd_baseprice * trjd_quantity/1000))*(-1)    
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))*(-1)    
                  /*omi */   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)   
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)   
                  end) as MARGIN
                
          from tbtr_jualdetail 
          left join tbmaster_prodmast on trjd_prdcd=prd_prdcd 
          left join tbmaster_customer on trjd_cus_kodemember=cus_kodemember
          left join tbmaster_perusahaan on prs_kodeigr=trjd_kodeigr
          left join tbmaster_outlet on out_kodeoutlet=cus_kodeoutlet
          left join tbmaster_suboutlet on sub_kodesuboutlet=cus_kodesuboutlet
          LEFT JOIN TBTR_OBI_H 
            ON trjd_cus_kodemember         =obi_kdmember
            AND trjd_cashierstation        =obi_kdstation     
            AND TRUNC(trjd_transactiondate)=TRUNC(obi_tglstruk)     
            AND trjd_transactionno         =obi_nostruk     
            AND trjd_create_by             =obi_modifyby  
          where trunc(trjd_transactiondate)  between to_date('$tglAwal','YYYY-MM-DD') and to_date('$tglAkhir','YYYY-MM-DD') 
          $filtertran
          $filtermember
          $filteroutlet
          $filterSegmen
          $filterkodemember
          $filterproduk
          $filterdep
          $filtersup
          $filterplu
          group by prd_kodedivisi, prd_kodedepartement, prd_kodekategoribarang, prd_deskripsipanjang, concat(rpad(trjd_prdcd,6),'0') 
          order by div,dep"
        );

        $produk = $produk->getResultArray();
      }elseif($jenisLaporan=="bulan") {
        $bulan = $dbProd->query(
          "SELECT cus_kodemember as KODEMEMBER,    
          cus_namamember as NAMAMEMBER,    
          to_char(trjd_transactiondate,'YYYY-MM') as PERIODE, 
          /*menambahkan kolom KUNJUNGAN,SLIP,PRODUK*/    
          count(distinct rpad(trjd_transactiondate,9)||trjd_cus_kodemember) as KUNJUNGAN,    
          count (distinct concat(concat(concat(trjd_create_by,trjd_cashierstation),trjd_transactionno),trjd_transactiondate)) as SLIP,    
          count (distinct rpad(trjd_prdcd,6)) as PRODUK,    
          /*menambahkan kolom S_GROSS,S_NETT,MARGIN*/    
          /*Sales Gross*/   
              sum(case   
                  when (trjd_transactiontype='S')and(cus_kodeoutlet='4') and(trjd_flagtax2='Y') then trjd_nominalamt*1.11   
                  when trjd_transactiontype='S' then trjd_nominalamt*1   
                  when trjd_transactiontype='R' then trjd_nominalamt*(-1)   
                  end) as S_GROSS,   
          /*Sales Nett*/     
              sum(case     
                  /*non omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1.11     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1.11)     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1)     
                  /*omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N' )='Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1     
                  end) as S_NETT,     
                  
            /*Margin*/     
            sum(case     
                  /*non omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))*(-1)     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))*(-1)     
                  /*pengecualian untuk unit KG*/     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity/1000))     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity/1000))*(-1)    
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))*(-1)    
            /*omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)     
                  end) as MARGIN
            
          FROM tbtr_jualdetail    
          LEFT JOIN tbmaster_customer on trjd_cus_kodemember=cus_kodemember    
          LEFT JOIN tbmaster_customercrm on trjd_cus_kodemember=crm_kodemember    
          LEFT JOIN tbmaster_segmentasi on crm_idsegment=seg_id    
          LEFT JOIN tbmaster_prodmast on trjd_prdcd=prd_prdcd    
          LEFT JOIN TBTR_OBI_H 
            ON trjd_cus_kodemember         =obi_kdmember
            AND trjd_cashierstation        =obi_kdstation     
            AND TRUNC(trjd_transactiondate)=TRUNC(obi_tglstruk)     
            AND trjd_transactionno         =obi_nostruk     
            AND trjd_create_by             =obi_modifyby  
          where trunc(trjd_transactiondate)  between to_date('$tglAwal','YYYY-MM-DD') and to_date('$tglAkhir','YYYY-MM-DD') 
          $filtertran
          $filtermember
          $filteroutlet
          $filterSegmen
          $filterkodemember
          $filterproduk
          $filterdep
          $filtersup
          $filterplu
          group by cus_kodemember, cus_namamember, to_char(trjd_transactiondate,'YYYY-MM')
          order by PERIODE"
        );

        $bulan = $bulan->getResultArray();
      }elseif($jenisLaporan=="struk"){
        $struk = $dbProd->query(
          "SELECT cus_kodemember as KODEMEMBER,    
          cus_namamember as NAMAMEMBER,    
          to_char(trjd_transactiondate,'YYYY-MM-DD') as TANGGAL, 
          trjd_create_by||'.'||trjd_cashierstation||'.'||trjd_transactionno||'.'||trjd_transactiontype as NOSTRUK,
          /*menambahkan kolom KUNJUNGAN,SLIP,PRODUK*/    
          count(distinct rpad(trjd_transactiondate,9)||trjd_cus_kodemember) as KUNJUNGAN,    
          count (distinct concat(concat(concat(trjd_create_by,trjd_cashierstation),trjd_transactionno),trjd_transactiondate)) as SLIP,    
          count (distinct rpad(trjd_prdcd,6)) as PRODUK,    
          /*menambahkan kolom S_GROSS,S_NETT,MARGIN*/    
          /*Sales Gross*/   
              sum(case   
                  when (trjd_transactiontype='S')and(cus_kodeoutlet='4') and(trjd_flagtax2='Y') then trjd_nominalamt*1.11   
                  when trjd_transactiontype='S' then trjd_nominalamt*1   
                  when trjd_transactiontype='R' then trjd_nominalamt*(-1)   
                  end) as S_GROSS,   
          /*Sales Nett*/     
              sum(case     
                  /*non omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1.11     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1.11)     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1)     
                  /*omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N' )='Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1     
                  end) as S_NETT,     
                  
            /*Margin*/     
            sum(case     
                  /*non omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))*(-1)     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))*(-1)     
                  /*pengecualian untuk unit KG*/     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity/1000))     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))     
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity/1000))*(-1)    
                  when (trjd_transactiontype='R')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))*(-1)    
            /*omi */     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N') ='Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)     
                  when (trjd_transactiontype='S')and(nvl(trjd_flagtax2,'N')<>'Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)     
                  end) as MARGIN
            
          FROM tbtr_jualdetail    
          LEFT JOIN tbmaster_customer on trjd_cus_kodemember=cus_kodemember    
          LEFT JOIN tbmaster_customercrm on trjd_cus_kodemember=crm_kodemember    
          LEFT JOIN tbmaster_segmentasi on crm_idsegment=seg_id    
          LEFT JOIN tbmaster_prodmast on trjd_prdcd=prd_prdcd    
          LEFT JOIN TBTR_OBI_H 
            ON trjd_cus_kodemember         =obi_kdmember
            AND trjd_cashierstation        =obi_kdstation     
            AND TRUNC(trjd_transactiondate)=TRUNC(obi_tglstruk)     
            AND trjd_transactionno         =obi_nostruk     
            AND trjd_create_by             =obi_modifyby  
          where trunc(trjd_transactiondate)  between to_date('$tglAwal','YYYY-MM-DD') and to_date('$tglAkhir','YYYY-MM-DD') 
          $filtertran
          $filtermember
          $filteroutlet
          $filterSegmen
          $filterkodemember
          $filterproduk
          $filterdep
          $filtersup
          $filterplu
          group by cus_kodemember, cus_namamember, to_char(trjd_transactiondate,'YYYY-MM-DD'), trjd_create_by||'.'||trjd_cashierstation||'.'||trjd_transactionno||'.'||trjd_transactiontype
          order by TANGGAL"
        );

        $struk = $struk->getResultArray();
      }

      $data = [
        'title' => 'Data '. $tglSekarang,
        'jenistransaksi' => $jenisTransaksi,
        'jenislaporan' => $jenisLaporan,
        'jenismember' => $jenisMember,
        'membertertentu' => $memberTertentu,
        'tglawal' => $tglAwal,
        'tglakhir' => $tglAkhir,
        'jenisproduk' => $jenisProduk,
        'segmentasi' => $segmentasi,
        'tipeoutlet' => $tipeoutlet,
        'member' => $permember,
        'produk' => $produk,
        'bulan' => $bulan,
        'struk' => $struk
      ];
      
      if($this->request->getVar('btn')=="tampil"){
        return view('member/tampilsalesmember', $data);
      }elseif($this->request->getVar('btn')=="xls"){
        $filename = "Sales Member".$tglSekarang.".xls";
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        return view('member/tampilexcelsalesmember', $data);
      }
    }

    public function salesperdep()
    {
      $dbProd = db_connect('production');
      $daftarDepartement = $dbProd->query(
        "SELECT dep_kodedivisi,div_namadivisi,div_singkatannamadivisi,dep_kodedepartement, dep_namadepartement 
        from tbmaster_departement 
        left join tbmaster_divisi on div_kodedivisi=dep_kodedivisi
        order by dep_kodedivisi,dep_kodedepartement"
      );

      $daftarDepartement = $daftarDepartement->getResultArray();
      $data=[
        'title' => 'Monitoring Sales perDepartement',
        'departement' => $daftarDepartement,
      ];
      return view('member/salesperdep',$data);
    }

    public function tampilsalesperdep()
    { 
      $now = date('d-m-Y H:i:s');
      $dbProd = db_connect('production');
      $tglAwal = $this->request->getVar('tglawal');
      $tglAkhir = $this->request->getVar('tglakhir');
      $departement = $this->request->getVar('departement');
      $jenisLap = $this->request->getVar('jenislap');
      $jmlHari = date('d');
      $dsi = 0;
      $kd_sup = strtoupper("");
      $jenisSales = "detail";

      switch ($jenisLap) {
        case '01':
          $jenisLap = "(sls_qtynomi + sls_qtyomi)";$jenisSales="Sales QTY IGR + OMI";
          break;
        case '02':
          $jenisLap = "(sls_qtynomi)";$jenisSales="Sales QTY IGR Only";
          break;
        case '03':
          $jenisLap = "(sls_qtyomi)";$jenisSales="Sales QTY IGR to OMI";
          break;
        case "04": $jenisLap = "(sls_netnomi + sls_netomi)";$jenisSales="Sales Nett IGR + OMI";break;
        case "05": $jenisLap = "(sls_netnomi)";$jenisSales="Sales Nett IGR Only";break;
        case "06": $jenisLap = "(sls_netomi)";$jenisSales="Sales Nett IGR to OMI";break;
        case "07": $jenisLap = "(sls_marginnomi + sls_marginomi)";$jenisSales="Margin IGR + OMI";break;
        case "08": $jenisLap = "(sls_marginnomi)";$jenisSales="Margin IGR Only";break;
        case "09": $jenisLap = "(sls_marginomi)";$jenisSales="Margin IGR to OMI";break;
        default : $jenisLap="(sls_qtynomi + sls_qtyomi)"; 
      }

      // set filter data

      if ($kd_sup=="") {
        $filterdata1 = "and prd_kodedepartement='$departement' ";
      }elseif ($kd_sup=="ALL") {
        $filterdata1 = "";
      }else{
        $filterdata1 = "and hgb_kodesupplier='$kd_sup' ";
      }

      //set bulan untuk avgsales
      $bln = date("m");
      $bln_1 = date("m",strtotime("-3 month"));
      $bln_2 = date("m",strtotime("-2 month"));
      $bln_3 = date("m",strtotime("-1 month"));
      $bln1= sprintf("%02s",$bln_1);
      $bln2= sprintf("%02s",$bln_2);
      $bln3= sprintf("%02s",$bln_3);
      
      $dataQuery = $dbProd->query(
        "SELECT 
        prd_kodedivisi as DIV,
        prd_kodedepartement as DEP,
        prd_kodekategoribarang as KAT,
        prd_prdcd as PLU,
        prd_deskripsipanjang as DESKRIPSI,
        prd_unit as UNIT,
        prd_frac as FRAC,
        prd_kodetag as TAG,
        sum(case when to_char(sls_periode,'DD')='01' then $jenisLap end )as T01,
        sum(case when to_char(sls_periode,'DD')='02' then $jenisLap end )as T02,
        sum(case when to_char(sls_periode,'DD')='03' then $jenisLap end )as T03,
        sum(case when to_char(sls_periode,'DD')='04' then $jenisLap end )as T04,
        sum(case when to_char(sls_periode,'DD')='05' then $jenisLap end )as T05,
        sum(case when to_char(sls_periode,'DD')='06' then $jenisLap end )as T06,
        sum(case when to_char(sls_periode,'DD')='07' then $jenisLap end )as T07,
        sum(case when to_char(sls_periode,'DD')='08' then $jenisLap end )as T08,
        sum(case when to_char(sls_periode,'DD')='09' then $jenisLap end )as T09,
        sum(case when to_char(sls_periode,'DD')='10' then $jenisLap end )as T10,
        sum(case when to_char(sls_periode,'DD')='11' then $jenisLap end )as T11,
        sum(case when to_char(sls_periode,'DD')='12' then $jenisLap end )as T12,
        sum(case when to_char(sls_periode,'DD')='13' then $jenisLap end )as T13,
        sum(case when to_char(sls_periode,'DD')='14' then $jenisLap end )as T14,
        sum(case when to_char(sls_periode,'DD')='15' then $jenisLap end )as T15,
        sum(case when to_char(sls_periode,'DD')='16' then $jenisLap end )as T16,
        sum(case when to_char(sls_periode,'DD')='17' then $jenisLap end )as T17,
        sum(case when to_char(sls_periode,'DD')='18' then $jenisLap end )as T18,
        sum(case when to_char(sls_periode,'DD')='19' then $jenisLap end )as T19,
        sum(case when to_char(sls_periode,'DD')='20' then $jenisLap end )as T20,
        sum(case when to_char(sls_periode,'DD')='21' then $jenisLap end )as T21,
        sum(case when to_char(sls_periode,'DD')='22' then $jenisLap end )as T22,
        sum(case when to_char(sls_periode,'DD')='23' then $jenisLap end )as T23,
        sum(case when to_char(sls_periode,'DD')='24' then $jenisLap end )as T24,
        sum(case when to_char(sls_periode,'DD')='25' then $jenisLap end )as T25,
        sum(case when to_char(sls_periode,'DD')='26' then $jenisLap end )as T26,
        sum(case when to_char(sls_periode,'DD')='27' then $jenisLap end )as T27,
        sum(case when to_char(sls_periode,'DD')='28' then $jenisLap end )as T28,
        sum(case when to_char(sls_periode,'DD')='29' then $jenisLap end )as T29,
        sum(case when to_char(sls_periode,'DD')='30' then $jenisLap end )as T30,
        sum(case when to_char(sls_periode,'DD')='31' then $jenisLap end )as T31,
        sum($jenisLap) as Total
        
        from tbtr_sumsales
        left join tbmaster_prodmast on prd_prdcd=sls_prdcd
        left join tbmaster_stock on st_prdcd=prd_prdcd
        left join tbmaster_hargabeli on hgb_prdcd=prd_prdcd
        where st_lokasi='01'
        and trunc(sls_periode) between to_date('$tglAwal','YYYY-MM-DD') and to_date('$tglAkhir','YYYY-MM-DD')
        $filterdata1
        group by prd_kodedivisi, prd_kodedepartement, prd_kodekategoribarang, prd_prdcd, prd_deskripsipanjang, prd_unit, prd_frac, prd_kodetag
        order by prd_deskripsipanjang"
      );

      $dataQuery = $dataQuery->getResultArray();
      
      $data=[
        'title' => 'Data '.$now,
        'tglawal' => $tglAwal,
        'tglakhir' => $tglAkhir,
        'departement' => $departement,
        'jenissales' => $jenisSales,
        'datas' => $dataQuery
      ];
      
      return view('member/tampilsalesperdep', $data);
    }

    public function efaktur(){
      $dbProd = db_connect('production');
      $tglawal = $this->request->getVar('tglawal');
      $tglakhir = $this->request->getVar('tglakhir');
      // opsional
      $idkasir = strtoupper($this->request->getVar('kasir'));
      $kdmember = strtoupper($this->request->getVar('kdmember'));
      $status = $this->request->getVar('status');
    
      if(!empty($idkasir)){
        $filterIdKasir = "AND fkt_kasir='$idkasir'";
      }else{
        $filterIdKasir = " ";
      }
    
      if(!empty($kdmember)){
        $filterkdmember = "AND fkt_kodemember='$kdmember'";
      }else{
        $filterkdmember =" ";
      }
    
      if($status=="bu"){
        $filterstatus = "WHERE fkt_status='BELUM UPLOAD'";
      }elseif ($status=="suba") {
        $filterstatus = "WHERE fkt_status='SUDAH UPLOAD, BELUM APPROVE'";
      }elseif ($status=="sa") {
        $filterstatus = "WHERE fkt_status='SUDAH APPROVE'";
      }else{
        $filterstatus = "";
      }
      
    
      $monitor = $dbProd->query(
        " SELECT * FROM   
        (SELECT fkt_tipe,
        fkt_tglfaktur,
        fkt_station,
        fkt_kasir,
        fkt_notransaksi,
        fkt_noseri,
        fkt_kodemember,
        cus_namamember as fkt_namamember,
        CASE when nvl(pjk_recordid,'0') = '1' then 'SUDAH UPLOAD, BELUM APPROVE'
             when nvl(pjk_recordid,'0') = '4' then 'SUDAH APPROVE' 
             else 'BELUM UPLOAD' end fkt_status
        FROM tbmaster_faktur
        LEFT JOIN tbmaster_pajak  ON fkt_nofaktur = pjk_nofaktur
        LEFT JOIN tbmaster_customer ON fkt_kodemember = cus_kodemember
        WHERE trunc(fkt_tglfaktur) between to_date('$tglawal','yyyy-mm-dd') and to_date('$tglakhir','yyyy-mm-dd')
        $filterIdKasir
        $filterkdmember
        order by fkt_tglfaktur)
        $filterstatus"
      );
    
      $monitor = $monitor->getResultArray();
    
    
      $data = [
        'title' => 'Monitoring E-Faktur',
        'monitor' => $monitor,
        'tglawal' => $tglawal,
        'tglakhir' => $tglakhir,
        'status' => $status,
      ];
    
      return view('member/efaktur', $data);
        }
}