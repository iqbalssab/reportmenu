<?php

namespace App\Controllers;

class Edp extends BaseController
{
    public function index()
    {
        return view('edp/index',);
    }

    public function barangtertinggal() {
        $data = [
          'title' => 'Cek Barang Tertinggal',
        ];
        
        redirect()->to('/edp/barangtertinggal')->withInput();
        return view('edp/barangtertinggal',$data);      
    }

    public function tampildatabrghlg() {
        $dbProd = db_connect('production');
        $kassa = $this->request->getVar('kassa');
        $tanggal = $this->request->getVar('tgl');
        $awal = $this->request->getVar('awal');
        $akhir = $this->request->getVar('akhir');
        $barang = [];

        if($tanggal == date('Y-m-d')) {
            $barang = $dbProd->query(
                "select 
                trjd_transactiondate as TANGGAL,
                to_char(trjd_transactiondate,'HH24:MM:SS') as JAM,
                trjd_transactionno as NO_STRUK,
                trjd_create_by as ID_KASIR,
                trjd_cashierstation as KASSA,
                trjd_cus_kodemember as KODE_MEMBER,
                trjd_prd_deskripsipendek as NAMA_BARANG,
                trjd_quantity as QTY,
                cus_namamember as NAMA_MEMBER,
                cus_alamatmember1 || ' ' || cus_alamatmember2 as ALAMAT,
                cus_hpmember as NO_HP                      
                from tbtr_jualdetail_interface
                left join tbmaster_customer on trjd_cus_kodemember=cus_kodemember
                where to_char(trjd_transactiondate,'HH24:MM') between '$awal' and '$akhir'
                and trjd_cashierstation='$kassa'
                order by JAM,TANGGAL,NO_STRUK"
            );

            $barang = $barang->getResultArray();
        } else {
            $barang = $dbProd->query(
                "select 
                trjd_transactiondate as TANGGAL,
                to_char(trjd_transactiondate,'HH24:MM:SS') as JAM,
                trjd_transactionno as NO_STRUK,
                trjd_create_by as ID_KASIR,
                trjd_cashierstation as KASSA,
                trjd_cus_kodemember as KODE_MEMBER,
                trjd_prd_deskripsipendek as NAMA_BARANG,
                trjd_quantity as QTY,
                cus_namamember as NAMA_MEMBER,
                cus_alamatmember1 || ' ' || cus_alamatmember2 as ALAMAT,
                cus_hpmember as NO_HP                      
                from tbtr_jualdetail
                left join tbmaster_customer on trjd_cus_kodemember=cus_kodemember
                where to_char(trjd_transactiondate,'HH24:MM') between '$awal' and '$akhir' 
                and trunc(trjd_transactiondate)= to_date('$tanggal','YYYY-MM-DD')
                and trjd_cashierstation='$kassa'
                order by JAM,TANGGAL,NO_STRUK"
            );

            $barang = $barang->getResultArray();
        };

        $data = [
          'title' => 'Data Barang Tertinggal',
          'kassa' => $kassa,
          'tanggal' => $tanggal,
          'awal' => $awal,
          'akhir' => $akhir,
          'barang' => $barang,
        ];
        
        return view('edp/tampildatabrghlg',$data);
    }

    public function cetaksso() {
        $data = [
          'title' => 'Cetak SSO',
        ];
  
        // redirect()->to('/edp/cetaksso')->withInput();
        return view('edp/cetaksso',$data);
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
            
          return view('edp/tampildatasso',$data);
        };
      }
  
      public function serahterimahdh() {
        $data = [
          'title' => 'Cetak Serah Terima Hadiah',
        ];
  
        redirect()->to('/edp/serahterimahdh')->withInput();
        return view('edp/serahterimahdh',$data);
      }

      public function monitoringchecker() {
      
        $data = [
          'title' => 'Monitoring Checker'
        ];
  
        return view('edp/monitoringchecker',$data);
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
          $filename = "Monitoring Checker ".date('d M Y',strtotime($tanggal)).".xls";
          header("Content-Disposition: attachment; filename=\"$filename\"");
          header("Content-Type: application/vnd.ms-excel");
            
          return view('edp/tampildatachecker',$data);
        };
    }

    public function historyappfp() {
      $dbProd = db_connect('production');
      $aksi = $this->request->getVar('tombol');
      $approval = [];

      if(isset($_GET['awal'])) {if ($_GET['awal'] !=""){$tanggalMulai = $_GET['awal']; }}
      if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tanggalSelesai = $_GET['akhir']; }}

      if($aksi == "btnksr") {
        $approval = $dbProd->query(
          "select
          rap_time as req_time,
          rap_username as userid,
          rap_station as kassa,
          rap_keterangan as keterangan,
          case
            when rap_approveid = 'X' then 'CANCEL'
            else rap_modify_by end as approval
          from tbtr_req_approval 
          where trunc(rap_create_dt) between to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd')
          and rap_program = 'POS IGR'
          order by req_time"
        );
        $approval = $approval->getResultArray();
      } else if( $aksi == "btnhh") {
        $approval = $dbProd->query(
          "select
          rap_time as req_time,
          rap_username as userid,
          rap_station as kassa,
          rap_keterangan as keterangan,
          case
            when rap_approveid = 'X' then 'CANCEL'
            else rap_modify_by end as approval
          from tbtr_req_approval 
          where trunc(rap_create_dt) between to_date('$tanggalMulai','yyyy-mm-dd') and to_date('$tanggalSelesai','yyyy-mm-dd')
          and rap_program = 'Handheld'
          order by req_time"
        );
        $approval = $approval->getResultArray();
      };

      $data=[
        'title' => 'History Approval Fingerprint',
        'approval' => $approval,
        'aksi' => $aksi,
      ];

      redirect()->to('historyappfp')->withInput();
      return view('edp/historyappfp',$data);
    }
}