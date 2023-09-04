<?php

namespace App\Controllers;

class Ms extends BaseController
{
    public function index()
    {
        return view('ms/cekdatamember');
    }

    public function cekmember()
    {
        $status = $this->request->getVar('statuscari');
        $cari = strtoupper($this->request->getVar('cari'));
        $aksi = $this->request->getVar('tombol');
        $dbProd = db_connect('production');
        $member = [];
        if($aksi == "btncekmbr") {
            if($status == "nama") {
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
                    where cus_namamember like '%$cari%'       
                    order by kode, nama_member asc"
                );

                $member = $member->getResultArray();
                $data = [
                  'title' => 'Cek Data Member',
                  'member' => $member,
                  'aksi' => $aksi,
                ];
                redirect()->to('/ms/cekdatamember')->withInput();
                return view('/ms/cekdatamember',$data);
            } else if($status == "kode") {
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
                    where cus_kodemember like '%$cari%'       
                    order by kode, nama_member asc"
                );
                
                $member = $member->getResultArray();
                $data = [
                  'title' => 'Cek Data Member',
                  'member' => $member,
                  'aksi' => $aksi,
                ];
                redirect()->to('cekdatamember')->withInput();
                return view('/ms/cekdatamember',$data);
            } else if($status == "ktp") {
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
                    where cus_noktp like '%$cari%'       
                    order by kode, nama_member asc"
                );
                
                $member = $member->getResultArray();
                $data = [
                  'title' => 'Cek Data Member',
                  'member' => $member,
                  'aksi' => $aksi,
                ];
                redirect()->to('/ms/cekdatamember')->withInput();
                return view('/ms/cekdatamember',$data);
            } elseif($status == "hp") {
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
                where cus_hpmember like '%$cari%'       
                order by kode, nama_member asc"
              );
              
              $member = $member->getResultArray();
              $data = [
                'title' => 'Cek Data Member',
                'member' => $member,
                'aksi' => $aksi,
              ];
              redirect()->to('/ms/cekdatamember')->withInput();
              return view('/ms/cekdatamember',$data);
            } elseif($status == "ksg") {
              $data = [
                'title' => 'Cek Data Member',
                'member' => $member,
                'aksi' => $aksi,
              ];
              redirect()->to('/ms/cekdatamember')->with('Error', 'Silahkan pilih jenis pencarian dulu')->withInput();
              return view('/ms/cekdatamember',$data);
            }
        }
        $data = [
            'title' => 'Cek Data Member',
            'member' => $member,
            'aksi' => $aksi,
        ];
        // redirect()->to('/ms/cekdatamember')->withInput();
        return view('/ms/cekdatamember',$data);
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
      return view('ms/trxmbr',$data);

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

      return view('ms/tampildatatransaksi',$data);
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

      
      redirect()->to('ms/pengeluaranhadiah')->withInput();
      return view('ms/pengeluaranhadiah',$data);
    }

    public function monitoringchecker() {
      
      $data = [
        'title' => 'Monitoring Checker'
      ];

      return view('ms/monitoringchecker',$data);
    }

    public function tampildatachecker() {
      $dbProd = db_connect("production");
      $aksi = $this->request->getVar('tombol');
      $tanggal = $this->request->getVar('tgl');
      $checker = $filename = [];

      $checker = $dbProd->query(
        "select * from tbtr_checker_header where trunc(transactiondate)= to_date('$tanggal','YYYY-MM-DD')"
      );

      $checker = $checker->getResultArray();

      $data = [
        'title' => 'Data '.$tanggal,
        'tanggal' => $tanggal,
        'checker' => $checker,
      ];

      if($aksi == "btnmc") {
        $filename = "Monitoring Checker $tanggal.xls";
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
          
        return view('ms/tampildatachecker',$data);
      };
    }

    public function cetaksso() {
      $data = [
        'title' => 'Cetak SSO',
      ];

      // redirect()->to('/ms/cetaksso')->withInput();
      return view('ms/cetaksso',$data);
    }

    public function tampildatasso() {
      $dbProd = db_connect("production");
      $aksi = $this->request->getVar('tombol');
      $nostruk = $this->request->getVar('notrx');
      $sso = $filename = [];

      $sso = $dbProd->query(
        "select * from tbtr_kasir_sso
        where kode_trans = '$nostruk'
        order by seqno asc"
      );

      $sso = $sso->getResultArray();

      $data = [
        'title' => 'Data '.$nostruk,
        'nostruk' => $nostruk,
        'sso' => $sso,
      ];

      if($aksi == "btnsso") {
        $filename = "$nostruk.xls";
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
          
        return view('ms/tampildatasso',$data);
      };
    }

    public function membertidur(){
      $dbSim = db_connect();
      $jenis = $this->request->getVar('jenismember');

      $filtermember = "";
      if ($jenis=="mm") {
        $filtermember = "and nvl(cus_flagmemberkhusus,'T')='Y'";
      }elseif ($jenis=="mb") {
        $filtermember = "and nvl(cus_flagmemberkhusus,'T')!='Y'";
      }elseif($jenis=="all"){
        $filtermember = " ";
      }

      $tidur = $dbSim->query(
        "SELECT distinct cabang, 
        kodemember, 
        namamember, 
        alamat1, 
        alamat2, 
        alamat3, 
        alamat4, 
        outlet, 
        suboutlet, 
        kartumember, 
        hpmember, 
        KUNJUNGAN_TERAKHIR
        ktp from (
          select
            cus_kodeigr as cabang,
            cus_kodemember as kodemember, 
            cus_namamember as namamember, 
            cus_alamatmember1 as alamat1, 
            cus_alamatmember2 as alamat2, 
            cus_alamatmember3 as alamat3, 
            cus_alamatmember4 as alamat4, 
            cus_kodeoutlet as outlet, 
            cus_kodesuboutlet as suboutlet, 
            cus_nokartumember as kartumember,
            cus_hpmember as hpmember,
            cus_tglregistrasi as tglreg,
            cus_noktp as ktp,
            KUNJUNGAN_TERAKHIR
          from tbmaster_customer
          join (select jh_cus_kodemember, max(jh_transactiondate) as KUNJUNGAN_TERAKHIR   
                from tbtr_jualheader 
                where trunc(jh_transactiondate)>=trunc(sysdate)-365 
                and trunc(jh_transactiondate)<=trunc(sysdate)-90
                group by jh_cus_kodemember
                      ) on cus_kodemember = jh_cus_kodemember
          where cus_recordid is null
          and cus_kodeigr ='25'
          $filtermember
          order by jh_transactiondate desc)"
      );

      $tidur = $tidur->getResultArray();
      d($tidur);
      $data = [
        'title' => 'Cari Member Tidur',
        'tidur' => $tidur,
        'jenis' => $jenis
      ];

      return view('ms/membertidur', $data);
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

      return view('ms/efaktur', $data);
    }

    
}
