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
}