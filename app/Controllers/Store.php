<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Store extends BaseController
{
    protected $helpers = ['number'];

    public function index()
    {
        $db = db_connect();
        $koneksi = $db->query("SELECT * FROM tbmaster_user");
        dd($koneksi->getResultArray());
        $data = [
            'title' => 'Store Menu',
            'datas' => $koneksi
        ];

        return view('store/index', $data);
    }
    public function previewkasir()
    {
        $tglHariIni = date('d-m-Y');
        date_default_timezone_set("Asia/Jakarta");
        $waktuHariIni = date('H:i:s');

        $dbProd = db_connect('production');

        $koneksiMember = $dbProd->query("SELECT 
        case 
          when nvl(cus_flagmemberkhusus,'N')='Y' then '1 - MBR MERAH'
          when nvl(cus_flagmemberkhusus,'N')!='Y' and cus_kodeoutlet='6' then '2 - END USER'
          else '3 - OTHER'
        end as TIPEMBR,
      count( distinct jh_cus_kodemember) as JMLMBR
      from tbtr_jualheader_interface
      left join tbmaster_customer on jh_cus_kodemember=cus_kodemember
      group by case 
          when nvl(cus_flagmemberkhusus,'N')='Y' then '1 - MBR MERAH'
          when nvl(cus_flagmemberkhusus,'N')!='Y' and cus_kodeoutlet='6' then '2 - END USER'
          else '3 - OTHER'
        end, cus_flagmemberkhusus
      order by TIPEMBR");

        $koneksiKasir = $dbProd->query("SELECT js_cashierid AS ID_KASIR,
        username AS NAMA_KASIR,
        js_cashierstation AS KASSA, 
        js_totsalesamt AS TOTAL_TRANSAKSI, 
        js_totcashsalesamt AS TUNAI,
        js_totdebitamt AS KDEBIT,
        (js_totcc1amt+js_totcc2amt) AS KKREDIT,
        js_totcreditsalesamt AS KREDIT,
        CASE 
           WHEN js_resetamt=0 THEN 'Aktif'
           WHEN js_resetamt>0 THEN 'Closing'
         END AS Status
        FROM tbtr_jualsummary 
        LEFT JOIN tbmaster_user on js_cashierid=userid 
        WHERE trunc(js_transactiondate)=to_date('$tglHariIni','DD-MM-YYYY')
        ORDER BY status,js_cashierstation,js_cashierid");

        $koneksiKasir = $koneksiKasir->getResultArray();
        $totalTunai = $totalTotal = $totalKDebit = $totalKKredit = $totalKredit = [];

        foreach($koneksiKasir as $kasir){
          array_push($totalTotal, $kasir['TOTAL_TRANSAKSI']);
          array_push($totalTunai, $kasir['TUNAI']);
          array_push($totalKDebit, $kasir['KDEBIT']);
          array_push($totalKKredit, $kasir['KKREDIT']);
          array_push($totalKredit, $kasir['KREDIT']);
        }
        $totalTransaksi = [
         'totalTotal' => array_sum($totalTotal),
         'totalTunai' => array_sum($totalTunai),
         'totalKdebit' => array_sum($totalKDebit),
         'totalKkredit' => array_sum($totalKKredit),
         'totalKredit' => array_sum($totalKredit)
        ];

        $koneksiFaktur = $dbProd->query("SELECT alk_tahunpajak, case when alk_used='Y' then 'USED' else 'AVAILABLE' end as STATUS,  
        count(alk_taxnum) as ALOKASI
        from tbtr_alokasitax
        where alk_tahunpajak>=to_char(sysdate,'YY') group by alk_tahunpajak,case when alk_used='Y' then 'USED' else 'AVAILABLE' end
        order by alk_tahunpajak,status");

        $data = [
            'title' => 'Preview Transaksi Kasir',
            'transactions' => $koneksiKasir,
            'totalTransaksi' => $totalTransaksi,
            'members' => $koneksiMember,
            'fakturs' => $koneksiFaktur
        ];

        return view('store/previewkasir', $data);
    }

    public function cekpromo()
    {
        $isidesk1 = strtoupper($this->request->getVar('desc1'));
        $isidesk2 = strtoupper($this->request->getVar('desc2'));
        $isiplu = $this->request->getVar('plu');
        $aksi = $this->request->getVar('tombol');
        $pluplusnol = ""; // Inisialisasi $pluplusnol
        $promomd = []; 
        $promocb = $hargamb = $hargamm = $hargaplt = $promogift = $promonk = $promohjk = $cariProduk = [];  
    
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

                $pluCari = substr($pluplusnol, 0, 6);
                $plu0 = substr($pluplusnol, 0, 6).'0';
                $dbProd = db_connect('production');

                if($aksi == "btnpromomd"){
                $promomd = $dbProd->query(
                    "SELECT prd_prdcd as PLU,
                    prd_deskripsipanjang as DESKRIPSI,
                    prd_frac AS FRAC,
                    prd_unit AS UNIT,
                    prd_kodetag AS TAG_IGR,
                    prc_kodetag AS TAG_OMI,
                    prd_flag_aktivasi AS ACT,
                    prd_minjual AS MIN_JUAL,
                    prd_avgcost AS ACOST,
                    prd_hrgjual as H_NORMAL,
                    nvl(prmd_hrgjual,0)as H_PROMO,
                    prmd_tglawal AS TGL_AWAL,
                    prmd_tglakhir AS TGL_AKHIR,
                    prmd_modify_dt AS TGL_UBAH,
                case when non_prdcd is not null then 'Y' else 'N' end as plunonpromo
                from tbmaster_prodmast 
                left join (
                  select prmd_prdcd,prmd_hrgjual,prmd_tglawal,prmd_tglakhir,to_char(prmd_modify_dt,'DD/MM/YY hh24:mm') as prmd_modify_dt  from tbtr_promomd where trunc(prmd_tglakhir)>=trunc(sysdate)
                )on prd_prdcd=prmd_prdcd
                left join tbmaster_prodcrm on prc_pluigr=prd_prdcd
                left join tbmaster_plunonpromo on non_prdcd=prd_prdcd
                where prd_prdcd like '$pluCari%' order by prd_prdcd"); 
                
                $promomd = $promomd->getResultArray();                
                }elseif($aksi == "btnpromocb"){
                $promocb = $dbProd->query(
                    "SELECT cbd_prdcd as PLU,
					prd_deskripsipendek as DESKRIPSI,
					prd_kodetag as TAG,
					prd_frac as FRAC,
					cbd_kodepromosi as KDPROMO,
					cbh_namapromosi as NAMAPROMO,
					cbh_mekanisme as MEKANISME,
					case when cbh_kiosk='Y' then 'via I-Kiosk'
					when cbh_kiosk='M' then 'via MyIndogrosir'
					else '' end as KIOSK,
					cbh_kiosk,
					cbmmember,
					cbh_mintotbelanja as MINTOTBELANJA,
					cbh_minrphprodukpromo as MINSPONSOR,
					cbd_minstruk as MINSTRUK,
					cbh_maxstrkperhari as MAX_STRK_PERHARI,
                    cbh_maxrphperhari as MAX_RPH_PERHARI,
					cbh_cashback as CBH,
					cbd_cashback as CBD,
					cbh_tglawal as TGLAWAL,
					cbh_tglakhir as TGLAKHIR,
					cba_reguler as MB,
                    cba_retailer as MM,
                    cba_platinum as PLT,
					cba_alokasijumlah as ALOKASI,
					cbd_alokasistok as ALOKASIPLU,
					ALKUSED,
					cbh_recordid as RECID,
					case when trunc(cbh_tglawal)<=trunc(sysdate) then 'AKTIF' else 'BLMAKTIF' end as STATUS,
					case when cbh_tglawalreg is null then '0' else '1' end as REGTERTENTU
					from tbtr_cashback_dtl
					left join tbtr_cashback_hdr on cbd_kodepromosi=cbh_kodepromosi
					left join tbtr_cashback_alokasi on cba_kodepromosi=cbd_kodepromosi
					left join (
					  SELECT cbm_kodepromosi,count(cbm_kodemember) as cbmmember FROM TBTR_CASHBACK_MEMBER group by cbm_kodepromosi
					) on cbm_kodepromosi=cbh_kodepromosi
					left join (select kd_promosi,sum(kelipatan) as alkused from m_promosi_h group by kd_promosi) on kd_promosi=cbh_kodepromosi
					left join tbmaster_prodmast on cbd_prdcd=prd_prdcd
					where trunc(cbh_tglakhir)>=trunc(sysdate) and cbd_prdcd like '$pluCari%' 
					and (cba_reguler !='0' or cba_retailer !='0'  or cba_platinum !='0' )
					order by cbh_kiosk,mb,mm,plt,kdpromo");
                
                $hargamb = $dbProd->query(
                    "SELECT plu,
                    deskripsi,
                    frac,
                    unit,
                    qtyjual,
                    hrgjual,
                    sum(nvl(nilai_cashback,0)) as TTLCASHBACK,
                    (hrgjual-sum(nvl(nilai_cashback,0)))  as HRGNETT
                    from (
                    select
                      prd_prdcd as PLU,
                      prd_deskripsipendek as DESKRIPSI,
                      prd_frac as FRAC,
                      prd_unit as UNIT,
                      prd_minjual as MINJUAL,
                      (prd_frac*prd_minjual) as QTYJUAL,
                      prd_hrgjual as HRGNORMAL,
                      prmd_hrgjual as HRGPROMO,
                      case when prmd_hrgjual<prd_hrgjual then prmd_hrgjual*prd_minjual else prd_hrgjual*prd_minjual end as HRGJUAL,
                      plucb,
                      kodepromosi,
                      cashback,
                      minstruk,
                      case 
                        when minstruk <>0 and (prd_frac*prd_minjual) >= minstruk then floor((prd_frac*prd_minjual)/minstruk)*cashback 
                        else 0
                        end as nilai_cashback
                    from tbmaster_prodmast
                    left join (
                        select * from tbtr_promomd 
                        left join tbtr_promomd_alokasi on substr(prmd_prdcd,0,6)||'0'=alk_prdcd
                        where trunc(prmd_tglawal)<=trunc(sysdate) and  trunc(prmd_tglakhir)>=trunc(sysdate) and alk_member='REGBIRU'
                      ) on prmd_prdcd=prd_prdcd
                    left join (
                    select 
                      cbd_prdcd as PLUCB,
                      cbh_kodepromosi as kodepromosi,
                      cbd_cashback as cashback,
                      cbd_minstruk as minstruk
                    from tbtr_cashback_hdr
                    left join tbtr_cashback_dtl     on cbd_kodepromosi=cbh_kodepromosi
                    left join tbtr_cashback_alokasi on cba_kodepromosi=cbh_kodepromosi
                    left join tbtr_cashback_member  on cbm_kodepromosi=cbh_kodepromosi
                    where trunc(cbh_tglawal)<=trunc(sysdate) and  trunc(cbh_tglakhir)>=trunc(sysdate)
                    and cbh_recordid is null
                    and cbh_kiosk not in ('K')
                    and cbh_namapromosi not like '%PWP%'
                    and cbm_kodemember is null
                    and cbh_jenispromosi not in ('5','4')
                    and cba_reguler='1'
                    and substr(cbd_prdcd,0,6)||'0' in ('$plu0')
                    ) on substr(prd_prdcd,0,6)=substr(plucb,0,6)
                    where substr(prd_prdcd,0,6)||'0' in ('$plu0') and prd_unit not in ('KG')
                    order by plu
                    )group by plu, deskripsi, frac, unit, qtyjual, hrgjual 
                    order by plu");

                $hargamm = $dbProd->query(
                    "SELECT plu,
                    deskripsi,
                    frac,
                    unit,
                    qtyjual,
                    hrgjual,
                    sum(nvl(nilai_cashback,0)) as TTLCASHBACK,
                    (hrgjual-sum(nvl(nilai_cashback,0)))  as HRGNETT
                    from (
                    select
                      prd_prdcd as PLU,
                      prd_deskripsipendek as DESKRIPSI,
                      prd_frac as FRAC,
                      prd_unit as UNIT,
                      prd_minjual as MINJUAL,
                      (prd_frac*prd_minjual) as QTYJUAL,
                      prd_hrgjual as HRGNORMAL,
                      prmd_hrgjual as HRGPROMO,
                      case when prmd_hrgjual<prd_hrgjual then prmd_hrgjual*prd_minjual else prd_hrgjual*prd_minjual end as HRGJUAL,
                      plucb,
                      kodepromosi,
                      cashback,
                      minstruk,
                      case 
                        when minstruk <>0 and (prd_frac*prd_minjual) >= minstruk then floor((prd_frac*prd_minjual)/minstruk)*cashback 
                        else 0
                        end as nilai_cashback
                    from tbmaster_prodmast
                    left join (
                        select * from tbtr_promomd 
                        left join tbtr_promomd_alokasi on substr(prmd_prdcd,0,6)||'0'=alk_prdcd
                        where trunc(prmd_tglawal)<=trunc(sysdate) and  trunc(prmd_tglakhir)>=trunc(sysdate) and alk_member='RETMERAH'
                      ) on prmd_prdcd=prd_prdcd
                    left join (
                    select 
                      cbd_prdcd as PLUCB,
                      cbh_kodepromosi as kodepromosi,
                      cbd_cashback as cashback,
                      cbd_minstruk as minstruk
                    from tbtr_cashback_hdr
                    left join tbtr_cashback_dtl     on cbd_kodepromosi=cbh_kodepromosi
                    left join tbtr_cashback_alokasi on cba_kodepromosi=cbh_kodepromosi
                    left join tbtr_cashback_member  on cbm_kodepromosi=cbh_kodepromosi
                    where trunc(cbh_tglawal)<=trunc(sysdate) and  trunc(cbh_tglakhir)>=trunc(sysdate)
                    and cbh_recordid is null
                    and cbh_kiosk not in ('K')
                    and cbh_namapromosi not like '%PWP%'
                    and cbm_kodemember is null
                    and cbh_jenispromosi not in ('5','4')
                    and cba_retailer='1'
                    and substr(cbd_prdcd,0,6)||'0' in ('$plu0')
                    ) on substr(prd_prdcd,0,6)=substr(plucb,0,6)
                    where substr(prd_prdcd,0,6)||'0' in ('$plu0') and prd_unit not in ('KG')
                    order by plu
                    )group by plu, deskripsi, frac, unit, qtyjual, hrgjual 
                    order by plu");
                
                $hargaplt = $dbProd->query(
                    "SELECT plu,deskripsi,frac,unit,qtyjual,hrgjual,sum(nvl(nilai_cashback,0)) as TTLCASHBACK,(hrgjual-sum(nvl(nilai_cashback,0)))  as HRGNETT
                    from (
                    select
                      prd_prdcd as PLU,
                      prd_deskripsipendek as DESKRIPSI,
                      prd_frac as FRAC,
                      prd_unit as UNIT,
                      prd_minjual as MINJUAL,
                      (prd_frac*prd_minjual) as QTYJUAL,
                      prd_hrgjual as HRGNORMAL,
                      prmd_hrgjual as HRGPROMO,
                      case when prmd_hrgjual<prd_hrgjual then prmd_hrgjual*prd_minjual else prd_hrgjual*prd_minjual end as HRGJUAL,
                      plucb,
                      kodepromosi,
                      cashback,
                      minstruk,
                      case 
                        when minstruk <>0 and (prd_frac*prd_minjual) >= minstruk then floor((prd_frac*prd_minjual)/minstruk)*cashback 
                        else 0
                        end as nilai_cashback
                    from tbmaster_prodmast
                    left join (
                        select * from tbtr_promomd 
                        left join tbtr_promomd_alokasi on substr(prmd_prdcd,0,6)||'0'=alk_prdcd
                        where trunc(prmd_tglawal)<=trunc(sysdate) and  trunc(prmd_tglakhir)>=trunc(sysdate) and alk_member='PLATINUM'
                      ) on prmd_prdcd=prd_prdcd
                    left join (
                    select 
                      cbd_prdcd as PLUCB,
                      cbh_kodepromosi as kodepromosi,
                      cbd_cashback as cashback,
                      cbd_minstruk as minstruk
                    from tbtr_cashback_hdr
                    left join tbtr_cashback_dtl     on cbd_kodepromosi=cbh_kodepromosi
                    left join tbtr_cashback_alokasi on cba_kodepromosi=cbh_kodepromosi
                    left join tbtr_cashback_member  on cbm_kodepromosi=cbh_kodepromosi
                    where trunc(cbh_tglawal)<=trunc(sysdate) and  trunc(cbh_tglakhir)>=trunc(sysdate)
                    and cbh_recordid is null
                    and cbh_kiosk not in ('K')
                    and cbh_namapromosi not like '%PWP%'
                    and cbm_kodemember is null
                    and cbh_jenispromosi not in ('5','4')
                    and cba_platinum='1'
                    and substr(cbd_prdcd,0,6)||'0' in ('$plu0')
                    ) on substr(prd_prdcd,0,6)=substr(plucb,0,6)
                    where substr(prd_prdcd,0,6)||'0' in ('$plu0') and prd_unit not in ('KG')
                    order by plu
                    )group by plu, deskripsi, frac, unit, qtyjual, hrgjual 
                    order by plu");

                    $promocb = $promocb->getResultArray();
                    $hargamb = $hargamb->getResultArray();
                    $hargamm = $hargamm->getResultArray();
                    $hargaplt = $hargaplt->getResultArray();

                }elseif($aksi== "btnpromogift"){
                $promogift = $dbProd->query(
                    "SELECT KODE,
                    NAMA_PROMO,
                    HADIAH,
                    MIN_PCS,
                    MIN_RPH,
                    MIN_SPONSOR,
                    ALOKASI,
                    ALO_TERPAKAI,
                    SISA_ALOKASI,
                    gfa_reguler,
                    gfa_retailer,
                    gfa_platinum,
                    gfh_tglawal,
                    gfh_tglakhir 
                    from ( select
                    gfh_kodepromosi as KODE,
                    gfh_namapromosi as NAMA_PROMO,
                    gfh_kethadiah || ' - ' || prd_deskripsipendek as HADIAH,
                    gfd_pcs as MIN_PCS,
                    gfd_rph as MIN_RPH,
                    gfh_mintotsponsor as MIN_SPONSOR,
                    gfa_alokasijumlah as ALOKASI,
                    alokasiused as ALO_TERPAKAI,
                    case
                      when gfa_alokasijumlah = 0 then 999999999 end as SISA_ALOKASI,
                      gfa_reguler,
                      gfa_retailer,
                     gfa_platinum,
                    gfh_tglawal,
                     gfh_tglakhir
                    from tbtr_gift_hdr
                    left join tbtr_gift_dtl on gfd_kodepromosi = gfh_kodepromosi
                    left join tbmaster_prodmast on prd_prdcd = gfd_prdcd
                    left join (select kd_promosi,sum(jmlh_hadiah) as alokasiused from m_gift_h group by kd_promosi) on kd_promosi=gfh_kodepromosi 
                    left join tbtr_gift_alokasi on gfa_kodepromosi = gfh_kodepromosi
                    where trunc(gfh_tglakhir) >= trunc(sysdate) and gfd_prdcd = '$plu0') --> ganti plu");

                    $promogift = $promogift->getResultArray();
                }elseif($aksi == "btnnk"){
                    $promonk = $dbProd->query(
                        "SELECT 
                        NKH_KODEPROMOSI AS KDPROMO,
                        NKH_NAMAPROMOSI AS NAMAPROMO,
                        NKH_TGLAWAL as TGLAWAL,
                        NKH_TGLAKHIR AS TGLAKHIR,
                        NKH_TOTALDANA AS TOTALDANA,
                        NKD_PRDCD AS PLU,
                        NKD_MAXCASHBACK AS MAXCASHBACK,
                        NKD_MAXQTY AS MAXQTY,
                        (NKD_MAXQTY*NKD_MAXCASHBACK) as MAXRPH,
                        NVL(TOTQTYITEM,0) as QTYPAKAI,
                        NVL(TOTDISCITEM,0) as RPHPAKAI,
                        NVL(TOTDISC,0) AS TOTAL_RPHPAKAI,
                        NKH_TOTALDANA-NVL(TOTDISC,0)AS SISA_RPHTOTAL
                        FROM TBTR_NOTAKREDIT_HDR 
                        LEFT JOIN TBTR_NOTAKREDIT_DTL ON NKH_KODEPROMOSI=NKD_KODEPROMOSI
                        LEFT JOIN (SELECT KD_PROMOSI,SUM(TOTDISC) AS TOTDISC FROM TBTR_KASIR_NK_D 
                          GROUP BY KD_PROMOSI) ON KD_PROMOSI=NKH_KODEPROMOSI
                        LEFT JOIN (SELECT KD_PROMOSI as KD_PROMOSI1,PLU as PLU1,SUM(QTY) AS TOTQTYITEM,SUM(TOTDISC) AS TOTDISCITEM FROM TBTR_KASIR_NK_D 
                          GROUP BY KD_PROMOSI, PLU) ON KD_PROMOSI1=NKH_KODEPROMOSI and PLU1=NKD_PRDCD
                        WHERE nkd_prdcd like ('$pluCari%')    --> GANTI PLU
                        and TRUNC(NKH_TGLAKHIR)>=TRUNC(SYSDATE)-30
                        order by NKD_KODEPROMOSI desc"
                    );

                    $promohjk = $dbProd->query(
                        "SELECT hgk_prdcd,
                        prd_deskripsipanjang,
                        hgk_hrgjual,
                        hgk_tglawal,
                        hgk_tglakhir 
                        from tbtr_hargakhusus 
                        left join tbmaster_prodmast on prd_prdcd=hgk_prdcd
                        where trunc(hgk_tglakhir)>=trunc(sysdate)-7
                        and hgk_prdcd like ('$pluCari%')        --> GANTI PLU
                        order by hgk_tglakhir desc,hgk_tglawal desc,hgk_prdcd asc"
                    );

                    $promonk = $promonk->getResultArray();
                    $promohjk = $promohjk->getResultArray();
                }
            } else {
                return redirect()->to('/store/cekpromo')->with('Error', 'Data yang anda masukkan tidak valid!!!')->withInput();
            }
        }

        if (!empty($isidesk1) || !empty($isidesk2)) {

          $dbProd = db_connect('production');
          $cariProduk = $dbProd->query(
            " SELECT prd_prdcd,prd_deskripsipanjang,prd_unit,prd_kodetag,prd_hrgjual,st_saldoakhir 
						from tbmaster_prodmast 
						left join tbmaster_stock on st_prdcd=prd_prdcd
						left join tbmaster_barcode on brc_prdcd=prd_prdcd
						where (st_lokasi='01' and prd_prdcd like '%0' and prd_deskripsipanjang like '%$isidesk1%' and prd_deskripsipanjang like '%$isidesk2%') 
						or brc_barcode like '%$isidesk1%' 
						order by prd_prdcd");

            $cariProduk = $cariProduk->getResultArray();
        }

        $data = [
            'title' => 'Cek Promo',
            'promomd' => $promomd,
            'promocb' => $promocb,
            'hargamb' => $hargamb,
            'hargamm' => $hargamm,
            'hargaplt' => $hargaplt,
            'promogift' => $promogift,
            'promonk' => $promonk,
            'promohjk' => $promohjk,
            'cariproduk' => $cariProduk,
            'desk1' => $isidesk1,
            'desk2' => $isidesk2,
        ];
        redirect()->to('/store/cekpromo')->withInput();
        return view('store/cekpromo', $data);
    }

    public function monitoringpromo()
    {
        $data = [
          'title' => 'Monitoring Promo IGR'
        ];

        return view('store/monitoringpromo', $data);
    }

    public function tampildatapromo()
    {
      $tanggalSekarang = $this->tglsekarang;
      $dbProd = db_connect("production");
      $jenis = $this->request->getVar('jenisLaporan');
      $statusPromo = $this->request->getVar('statusPromo');
      $tgl = $this->request->getVar('tglAkhir');
      $kdPromosi = strtoupper($this->request->getVar('kdPromosi'));

      $cashBack = $cbperplu = $perolehancb = $gift = $giftperplu = $perolehangift = $instore = $instoreperplu = [];
      $judul_filterstatus = $judul_filterkodepromo = $judul_filtertglakhir = "";

      // masukan tgl awal dan tgl akhir
      switch ($jenis) {
        case 'cb':
          $kolomtglAwal = "cbh_tglawal";
          $kolomtglAkhir = "cbh_tglakhir";
          break;
        case 'cbperplu' :
            $kolomtglAwal = "cbh_tglawal";
            $kolomtglAkhir = "cbh_tglakhir";
          break;
        case 'gift':
          $kolomtglAwal = "gfh_tglawal";
          $kolomtglAkhir = "gfh_tglakhir";
          break;
        case 'giftperplu':
          $kolomtglAwal = "gfh_tglawal";
          $kolomtglAkhir = "gfh_tglakhir";
          break;
        case 'instore':
          $kolomtglAwal = "ish_tglawal";
          $kolomtglAkhir = "ish_tglakhir";
          break;
        case 'instoreperplu':
          $kolomtglAwal = "ish_tglawal";
          $kolomtglAkhir = "ish_tglakhir";
          break;
        
        default:
          $kolomtglAwal = "";
          $kolomtglAkhir = "";
          break;
      }

      // pilih status promo
      switch ($statusPromo) {
        case 'all':
          $filterstatus = " and trunc($kolomtglAwal)>trunc(sysdate-365) "; 
          $judul_filterstatus = " All Promo";
          break;
        case 'aktif':
          $filterstatus = " and trunc($kolomtglAwal)<=trunc(sysdate) and trunc($kolomtglAkhir)>=trunc(sysdate) "; 
          $judul_filterstatus = " hanya promo aktif saja";
          break;            
        case 'selesai':
          $filterstatus = " and trunc($kolomtglAkhir)>=trunc(sysdate-90) and trunc($kolomtglAkhir)<trunc(sysdate) " ; 
          $judul_filterstatus = " promo yang sudah selesai";
          break;
        case 'blmaktif':
          $filterstatus = " and trunc($kolomtglAwal)>trunc(sysdate) " ; 
          $judul_filterstatus = " belum aktif";
          break;
        default :
          $filterstatus = " and trunc($kolomtglAwal)>trunc(sysdate-365) "; 
          $judul_filterstatus = "";
          break;
        }

        // cek tanggal akhir promo
        if($tgl == "") {
          $filtertglakhir = "";
          $judul_filtertglakhir = " tidak ditentukan";
        }else{
          $filtertglakhir = " and trunc($kolomtglAkhir)=to_date('$tgl','yyyy-mm-dd') ";
          $judul_filtertglakhir = " $tgl ";
        }

        // cek input kodepromosi
        if($kdPromosi == "") {
          $filterkodepromo = "";
          $judul_filterkodepromo = " belum diinput! ";
        }else{
          $filterkodepromo = "and kd_promosi='$kdPromosi' ";
          $judul_filterkodepromo = " $kdPromosi ";
        }
      
      if ($jenis=="cb") {
        
        $judul_jenis = "CASHBACK";        
          
          $cashBack = $dbProd->query(
            "SELECT distinct 
            cbh_kodepromosi,
            cbh_namapromosi,
            cbh_recordid,
            cbh_tglawal,
            cbh_tglakhir,
            cba_alokasijumlah,
            alokasiused,
            case when cbd_alokasistok>0 then 'Y' else 'N' end as pembatasanplu
            from tbtr_cashback_hdr
            left join tbtr_cashback_alokasi on cba_kodepromosi=cbh_kodepromosi
            left join tbtr_cashback_dtl on cbd_kodepromosi=cbh_kodepromosi
            left join (select kd_promosi,sum(kelipatan) as alokasiused from m_promosi_h group by kd_promosi)on kd_promosi=cbh_kodepromosi
            where 1=1 $filterstatus $filtertglakhir $filterkodepromo
            order by cbh_kodepromosi"
          );

          $cashBack = $cashBack->getResultArray();

          
      }elseif($jenis=="cbperplu"){

        $judul_jenis = "CASHBACK per PLU";
        

        $cbperplu = $dbProd->query(
          "SELECT 
          prd_kodedivisi as DIV,
          prd_kodedepartement as DEP,
          prd_kodekategoribarang as KAT,
          prd_prdcd as PLU,
          prd_deskripsipanjang as DESKRIPSI,
          prd_unit as UNIT,
          prd_frac as FRAC,
          prd_kodetag as TAG,
          cbh_kodepromosi as KDPROMOSI,
          cbh_namapromosi as NAMAPROMOSI,
          cbh_tglawal as tglawal,
          cbh_tglakhir as tglakhir,
          CBH_MINRPHPRODUKPROMO AS MIN_SPONSOR, 
          CBD_CASHBACK AS NILAI_CB,
          CBH_CASHBACK AS NILAI_CB_GAB,
          CBD_REDEEMPOINT AS REDEEM_POINT,  
          CBD_MINSTRUK AS MIN_STRUK,   
          CBD_MAXSTRUK AS MAX_STRUK,
          cbh_maxrphperhari as MAX_RPH_PERHARI,
          CBA_ALOKASIJUMLAH AS ALOKASI_JUMLAH,
          ALOKASIUSED as ALOKASI_KELUAR,
          case when CBA_ALOKASIJUMLAH!=0 then CBA_ALOKASIJUMLAH-ALOKASIUSED else 0 end as SISA_ALOKASI,
              CASE WHEN CBA_REGULER = 1 THEN 'BIRU ' ELSE '' END BIRU, 
              CASE WHEN CBA_REGULER_BIRUPLUS = 1 THEN 'BIRU+ ' ELSE '' END BIRUPLUS, 
              CASE WHEN CBA_FREEPASS = 1 THEN 'FREE ' ELSE '' END FREEPASS, 
              CASE WHEN CBA_RETAILER = 1 THEN 'RET ' ELSE '' END RETAILER, 
              CASE WHEN CBA_SILVER = 1 THEN 'SILV ' ELSE '' END SILVER, 
              CASE WHEN CBA_GOLD1 = 1 THEN 'GD1 ' ELSE '' END GOLD1, 
              CASE WHEN CBA_GOLD2 = 1 THEN 'GD2 ' ELSE '' END GOLD2, 
              CASE WHEN CBA_GOLD3 = 1 THEN 'GD3 ' ELSE '' END GOLD3, 
              CASE WHEN CBA_PLATINUM = 1 THEN 'PLAT ' ELSE '' END PLATINUM 
          from tbmaster_prodmast 
          left join tbtr_cashback_dtl on cbd_prdcd = prd_prdcd
          left join tbtr_cashback_hdr on cbh_kodepromosi = cbd_kodepromosi
          left join tbtr_cashback_alokasi on cba_kodepromosi = cbd_kodepromosi
          left join (select kd_promosi,sum(kelipatan) as alokasiused from m_promosi_h group by kd_promosi)on kd_promosi=cbh_kodepromosi
          where cbd_prdcd is not null
          $filterstatus $filtertglakhir
          order by DIV,DEP,KAT,DESKRIPSI"
        );

        $cbperplu = $cbperplu->getResultArray();
      }elseif($jenis=="perolehancb"){
        $judul_jenis = "PEROLEHAN CASHBACK";

        $perolehancb = $dbProd->query(
          "SELECT 
          cbh_kodepromosi,
          cbh_namapromosi,
          cus_kodemember,
          cus_namamember,
          tgl_trans,
          create_by||'.'||kode_station||'.'||trans_no as NOSTRUK,
          kelipatan,
          cashback
        from M_PROMOSI_H 
        left join tbmaster_customer on cus_kodemember=kd_member
        left join tbtr_CASHBACK_hdr on cbh_kodepromosi=kd_promosi
        where $filterkodepromo
        order by tgl_trans,nostruk"
        );

        $perolehancb = $perolehancb->getResultArray();
      }elseif($jenis=="gift"){
        $judul_jenis = "Promo GIFT";

        $gift = $dbProd->query(
          "SELECT 
          gfh_kodepromosi,
          gfh_namapromosi,
          gfh_tglawal,
          gfh_tglakhir,
          gfa_alokasijumlah,
          alokasiused
          from tbtr_gift_hdr
          left join tbtr_gift_alokasi on gfa_kodepromosi=gfh_kodepromosi
          left join (select kd_promosi,sum(jmlh_hadiah) as alokasiused from m_gift_h group by kd_promosi) on kd_promosi=gfh_kodepromosi
          where 1=1 $filterstatus  $filtertglakhir or $filterkodepromo
          order by gfh_kodepromosi"
        );

        $gift = $gift->getResultArray();
      }elseif($jenis=="giftperplu"){
        $judul_jenis = "GIFT per PLU";

        $giftperplu = $dbProd->query(
          "SELECT 
          DIV,DEP,KAT,PLU,DESKRIPSI,UNIT,FRAC,TAG,KODE_PROMOSI,NAMA_PROMOSI,
          JENIS_PROMOSI,ALL_ITEM,TANGGAL_AWAL,TANGGAL_AKHIR,MINBELI,MIN_TOTAL_BELANJA,    
          MIN_TOTAL_SPONSOR,MAX_JUMLAH_HARI,MAX_FREQ_HARI,MAX_JUMLAH_EVENT,MAX_FREQ_EVENT,  
          DIV_HADIAH,PLU_HADIAH,HADIAH_RUPIAH,JUMLAH_HADIAH,ALOKASI_HADIAH,ALOKASIUSED,
          SISA_ALOKASI, BIRU, BIRUPLUS, FREEPASS, RETAILER, SILVER, GOLD1,
          GOLD2, GOLD3, PLATINUM, KODEPERJANJIAN FROM (
          select   
          prd_kodedivisi as DIV,  
          prd_kodedepartement as DEP,  
          prd_kodekategoribarang as KAT,  
          prd_prdcd as PLU,  
          prd_deskripsipanjang as DESKRIPSI,  
          prd_unit as UNIT,  
          prd_frac as FRAC,  
          prd_kodetag as TAG,  
          GFH_KODEPROMOSI AS KODE_PROMOSI,    
          GFH_NAMAPROMOSI AS NAMA_PROMOSI,    
          GFH_JENISPROMOSI AS JENIS_PROMOSI,    
          GFH_ALLITEM AS ALL_ITEM,    
          GFH_TGLAWAL AS TANGGAL_AWAL,    
          GFH_TGLAKHIR AS TANGGAL_AKHIR,    
          GFD_PCS as MINBELI,  
          GFH_MINTOTBELANJA AS MIN_TOTAL_BELANJA,    
          GFH_MINTOTSPONSOR AS MIN_TOTAL_SPONSOR,     
          GFH_MAXJMLHARI AS MAX_JUMLAH_HARI,    
          GFH_MAXFREKHARI AS MAX_FREQ_HARI,    
          GFH_MAXJMLEVENT AS MAX_JUMLAH_EVENT,    
          GFH_MAXFREKEVENT AS MAX_FREQ_EVENT,  
          divhdh as DIV_HADIAH,  
          GFH_KETHADIAH AS PLU_HADIAH,    
          GFH_RPHHADIAH AS HADIAH_RUPIAH,    
          GFH_JMLHADIAH AS JUMLAH_HADIAH,   
          GFA_ALOKASIJUMLAH AS ALOKASI_HADIAH,
          GFH_KODEPERJANJIAN AS KODEPERJANJIAN,  
          ALOKASIUSED,  
          case 
            when GFA_ALOKASIJUMLAH!=0 then GFA_ALOKASIJUMLAH-ALOKASIUSED else 999999 end as SISA_ALOKASI,  
          CASE 
            WHEN GFA_REGULER = 1 THEN 'BIRU ' ELSE '' END BIRU,   
          CASE 
            WHEN GFA_REGULER_BIRUPLUS = 1 THEN 'BIRU+ ' ELSE '' END BIRUPLUS,   
          CASE 
            WHEN GFA_FREEPASS = 1 THEN 'FREE ' ELSE '' END FREEPASS,   
          CASE 
            WHEN GFA_RETAILER = 1 THEN 'RET ' ELSE '' END RETAILER,   
          CASE 
            WHEN GFA_SILVER = 1 THEN 'SILV ' ELSE '' END SILVER,   
          CASE 
            WHEN GFA_GOLD1 = 1 THEN 'GD1 ' ELSE '' END GOLD1,   
          CASE 
            WHEN GFA_GOLD2 = 1 THEN 'GD2 ' ELSE '' END GOLD2,   
          CASE 
            WHEN GFA_GOLD3 = 1 THEN 'GD3 ' ELSE '' END GOLD3,   
          CASE 
            WHEN GFA_PLATINUM = 1 THEN 'PLAT ' ELSE '' END PLATINUM   
                    
          from tbmaster_prodmast  
          left join tbtr_gift_dtl     on gfd_prdcd=prd_prdcd  
          left join tbtr_gift_hdr     on gfh_kodepromosi = gfd_kodepromosi  
          left join tbtr_gift_alokasi on gfa_kodepromosi = gfd_kodepromosi  
          left join (select prd_prdcd as pluhdh,prd_kodedivisi as divhdh from tbmaster_prodmast) on pluhdh=gfh_kethadiah  
          left join (select kd_promosi,sum(jmlh_hadiah) as alokasiused from m_gift_h group by kd_promosi) on kd_promosi=gfh_kodepromosi  
          where gfd_prdcd is not null  $filterstatus $filtertglakhir
          order by DIV,DEP,KAT,DESKRIPSI)"
        );

        $giftperplu = $giftperplu->getResultArray();
      }elseif($jenis=="perolehangift"){
        $judul_jenis="PEROLEHAN GIFT";

        $perolehangift = $dbProd->query(
          "SELECT 
          kd_promosi,
          ket_hadiah,
          kd_member,
          cus_namamember,
          tgl_trans,
          create_by||'.'||kd_station||'.'||trans_no as NOSTRUK,
          jmlh_hadiah
          from m_gift_h 
          left join tbmaster_customer on cus_kodemember=kd_member
          where $filterkodepromo
          order by tgl_trans,nostruk"
        );

        $perolehangift = $perolehangift->getResultArray();
      }elseif($jenis=="instore"){
        $judul_jenis="PROMO INSTORE";

        $instore = $dbProd->query(
          "SELECT 
          ish_kodepromosi,
          ish_namapromosi,
          ish_keterangan,
          ish_tglawal,
          ish_tglakhir,
          ish_qtyalokasi,
          alokasiused
          from tbtr_instore_hdr
          left join (select kd_promosi,sum(jmlh_hadiah) as alokasiused from m_gift_h group by kd_promosi) on kd_promosi=ish_kodepromosi
          where 1=1 $filterstatus  $filtertglakhir
          order by ish_kodepromosi"
        );

        $instore = $instore->getResultArray();
      }elseif($jenis=="instoreperplu"){
        $judul_jenis="INSTORE PER PLU";

        $instoreperplu = $dbProd->query(
          "SELECT prd_kodedivisi as DIV,
          prd_kodedepartement as DEP,
          prd_kodekategoribarang as KAT,
          isd_prdcd as PLU,
          prd_deskripsipanjang as DESKRIPSI,
          prd_unit as UNIT,
          prd_frac as FRAC,
          prd_kodetag as TAG,
          ISH_KODEPROMOSI AS KODE_PROMOSI,  
          ISH_NAMAPROMOSI AS NAMA_PROMOSI,  
          ISH_JENISPROMOSI AS JENIS_PROMOSI,  
          ISH_TGLAWAL AS TANGGAL_AWAL,  
          ISH_TGLAKHIR AS TANGGAL_AKHIR,  
          ISD_MINPCS as MINBELI,
          ISH_MINSTRUK AS MIN_TOTAL_BELANJA,  
          ISH_MINSPONSOR AS MIN_TOTAL_SPONSOR,   
          ISH_MAXJMLEVENT AS MAX_JUMLAH_EVENT,  
          ISH_MAXFREKEVENT AS MAX_FREQ_EVENT,
          divhdh as DIV_HADIAH,
          ISH_PRDCDHADIAH AS PLU_HADIAH,  
          ISH_JMLHADIAH AS JUMLAH_HADIAH, 
          ISH_QTYALOKASI AS ALOKASI_HADIAH,
          ALOKASIUSED,
          case when ISH_QTYALOKASI !=0 then ISH_QTYALOKASI-ALOKASIUSED else 0 end as SISA_ALOKASI,
          CASE WHEN ISH_REGULER = 1 THEN 'BIRU ' ELSE '' END BIRU, 
          CASE WHEN ISH_REGULERBIRUPLUS = 1 THEN 'BIRU+ ' ELSE '' END BIRUPLUS, 
          CASE WHEN ISH_FREEPASS = 1 THEN 'FREE ' ELSE '' END FREEPASS, 
          CASE WHEN ISH_RETAILER = 1 THEN 'RET ' ELSE '' END RETAILER, 
          CASE WHEN ISH_SILVER = 1 THEN 'SILV ' ELSE '' END SILVER, 
          CASE WHEN ISH_GOLD1 = 1 THEN 'GD1 ' ELSE '' END GOLD1, 
          CASE WHEN ISH_GOLD2 = 1 THEN 'GD2 ' ELSE '' END GOLD2, 
          CASE WHEN ISH_GOLD3 = 1 THEN 'GD3 ' ELSE '' END GOLD3, 
          CASE WHEN ISH_PLATINUM = 1 THEN 'PLAT ' ELSE '' END PLATINUM 
          
        from tbtr_instore_dtl
        left join tbmaster_prodmast     on isd_prdcd=prd_prdcd
        left join tbtr_instore_hdr     on ish_kodepromosi = isd_kodepromosi
        left join (select prd_prdcd as pluhdh,prd_kodedivisi as divhdh from tbmaster_prodmast) on pluhdh=ish_prdcdhadiah
        left join (select kd_promosi,sum(jmlh_hadiah) as alokasiused from m_gift_h group by kd_promosi) on kd_promosi=ish_kodepromosi
        where isd_prdcd is not null     
        $filterstatus $filtertglakhir
        order by DIV,DEP,KAT,DESKRIPSI"
        );

        $instoreperplu = $instoreperplu->getResultArray();
      }

      

      $data =[
        'title' => 'Data '. $tanggalSekarang,
        'jenis' => $judul_jenis,
        'status' => $judul_filterstatus,
        'tglakhir' => $judul_filtertglakhir,
        'kodepromo' => $judul_filterkodepromo,
        'cashback' => $cashBack,
        'cbperplu' => $cbperplu,
        'perolehancb' => $perolehancb,
        'gift' => $gift,
        'giftperplu' => $giftperplu,
        'perolehangift' => $perolehangift,
        'instore' => $instore,
        'instoreperplu' => $instoreperplu
      ];

      if($this->request->getVar('tombol')=="tampil"){
        return view('store/tampildatapromo', $data);
      }elseif ($this->request->getVar('tombol')=="xls") {
        $filename = "datapromo $tanggalSekarang.xls";
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        return view('store/tampilexcel',$data);
      }

    }

    public function diskonminus()
    {
      $dbprod = db_connect('production');
      $diskonMinus = $dbprod->query(
        "SELECT prmd_prdcd as PLU,
        prd_deskripsipanjang as DESKRIPSI,
        prd_kodetag as TAG,
        prd_frac as FRAC,
        prd_unit as UNIT,
        st_saldoakhir as STOK,
        prd_hrgjual as HRG_NORMAL,
        prmd_hrgjual as HRG_PROMO,
        prd_hrgjual-prmd_hrgjual as DISKON
        from tbtr_promomd
        left join tbmaster_prodmast on prmd_prdcd=prd_prdcd
        left join tbmaster_stock on substr(st_prdcd,0,6)=substr(prmd_prdcd,0,6)
        where st_lokasi='01' and  trunc(prmd_tglakhir)>=trunc(sysdate) and prd_hrgjual-prmd_hrgjual<0
        order by deskripsi,plu"
      );

      $diskonMinus = $diskonMinus->getResultArray();

      $data= [
        'title' => 'Diskon Minus',
        'diskonminus'=> $diskonMinus
      ];

      return view('store/diskonminus', $data);
    }

    public function marginminus()
    {
      
    }

    // public function export()
    // {
    //   $spreadsheet = new Spreadsheet();
    //   $activeWorksheet = $spreadsheet->getActiveSheet();

    //   $writer = new Xls($spreadsheet);
    //   $writer->save('Data '.Time::now().'.xls');
    // }
}
