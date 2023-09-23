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
        $promocb = $hargamb = $hargamm = $hargaplt = $promogift = $promonk = $promohjk = $cariProduk = $alokasimd = $maxtrans = [];  
    
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

                if($aksi == "btnpromomd" && strlen($isiplu) < 8){
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

                $alokasimd = $dbProd->query(
                  "SELECT alk_prdcd,alk_member,alk_qtyalokasi from tbtr_promomd_alokasi 
                  left join tbtr_promomd on prmd_prdcd=alk_prdcd
                  where alk_prdcd='$pluCari' and trunc(prmd_tglakhir)>=trunc(sysdate) order by alk_member"
                );

                $maxtrans = $dbProd->query(
                  "SELECT * from tbtabel_maxtransaksi where mtr_prdcd='$pluCari' "
                );
                
                $promomd = $promomd->getResultArray();                
                $alokasimd = $alokasimd->getResultArray();                
                $maxtrans = $maxtrans->getResultArray();  

                }elseif($aksi == "btnpromocb" && strlen($isiplu) < 8){
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
					case when cbh_tglawalreg is null then '0' else '1' end as REGTERTENTU,
          CASE WHEN cbh_flagtmi='Y' THEN 'TMI' ELSE '' END AS TMI,
          CASE WHEN cbh_flagigr='Y' THEN 'IGR' ELSE '' END AS IGR,
          CASE WHEN cbh_flagklik='Y' THEN 'KLIK' ELSE '' END AS KLIK,
          CASE WHEN cbh_flagspi='Y' THEN 'SPI' ELSE '' END AS SPI
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

                }elseif($aksi== "btnpromogift" && strlen($isiplu) < 8){
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
                }elseif($aksi == "btnnk" && strlen($isiplu) < 8){
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
            'alokasimd' => $alokasimd,
            'maxtrans' => $maxtrans,
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
        $ip = $_SERVER['SERVER_NAME']."/store/";
        $data = [
          'title' => 'Monitoring Promo IGR',
          'ip' => $ip,
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
          if($jenis=="cb"){
            $filterkodepromo = "";
          }else{
            $filterkodepromo = "kd_promosi='00000' ";
          }
          $judul_filterkodepromo = " belum diinput! ";
        }else{
          if($jenis=="cb"){
            $filterkodepromo = "AND kd_promosi='$kdPromosi' ";
          }else{
            $filterkodepromo = " kd_promosi='$kdPromosi' ";
          }
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
      $dbProd = db_connect('production');
      $marginminus = $dbProd->query(
        "SELECT * from (
          select DIV,
          DEP,
          KAT,
          PLU,
          DESKRIPSI,
          TAG,
          FRAC,
          UNIT,
          STOK,
          (ACOSTPCS*FRAC) as ACOST,
          FLAGBKP1,
          FLAGBKP2,
          HRG_NORMAL,
          HRG_PROMO,
          HARGA_JUAL,
          case when flagbkp2='Y' then round((HARGA_JUAL / (1 + PRD_PPN/100))-(ACOSTPCS*FRAC)) else round(HARGA_JUAL-(ACOSTPCS*FRAC)) end as MARGIN,
          case when flagbkp2='Y' then round(((HARGA_JUAL / (1 + PRD_PPN/100))-(ACOSTPCS*FRAC))/(HARGA_JUAL / (1 + PRD_PPN/100) )*100,2) else round((HARGA_JUAL-(ACOSTPCS*FRAC))/(HARGA_JUAL)*100,2) end as MRG
          from (
          select 
          prd_kodedivisi as DIV,
          prd_kodedepartement as DEP,
          prd_kodekategoribarang as KAT,
          prd_prdcd as PLU,
          prd_deskripsipanjang as DESKRIPSI,
          prd_kodetag as TAG,
          prd_frac as FRAC,
          prd_unit as UNIT,
          st_saldoakhir as STOK,
          case when prd_unit = 'KG' then st_avgcost/1000 else st_avgcost end as ACOSTPCS,
          prd_flagbkp1 as FLAGBKP1,
          prd_flagbkp2 as FLAGBKP2,
          prd_ppn,
          prd_hrgjual as HRG_NORMAL,
          prmd_hrgjual as HRG_PROMO,
          case 
            when prmd_hrgjual is null then prd_hrgjual
            when prmd_hrgjual<prd_hrgjual then prmd_hrgjual
            when prmd_hrgjual>prd_hrgjual then prd_hrgjual
          end as HARGA_JUAL
          from tbmaster_prodmast 
          left join tbmaster_stock on st_prdcd=substr(prd_prdcd,0,6)||'0'
          left join (select * from tbtr_promomd where trunc(prmd_tglakhir)>=trunc(sysdate)) on prmd_prdcd=prd_prdcd
          where st_lokasi='01' and prd_kodetag not in ('N','X'))
          order by div,dep,kat,deskripsi,plu)
          where margin<0 and stok>0"
      );
      $marginminus = $marginminus->getResultArray();

      $data= [
        'title' => 'Margin Minus',
        'marginminus' => $marginminus
      ];
      return view('store/marginminus', $data);
    }

    public function transaksiisaku()
    {
      $tglTrans = $this->request->getVar('tglawal');
      $cashIn = $cashOut = $purchase = [];
      $dbProd = db_connect('production');

      // BTN Cash IN
      if ($this->request->getVar('btn')=="cashin") {
        $cashIn = $dbProd->query(
          "SELECT  
          VIR_CASHIERSTATION AS KASA,  
          VIR_CASHIERID AS ID_KASIR,  
          USERNAME as NAMAKASIR, 
          VIR_TYPE as TRANS,
          VIR_TRANSACTIONNO AS NO_TRANSAKSI,  
          VIR_AMOUNT AMOUNT,  
          VIR_FEE FEE,  
          VIR_TOTAL TOTAL,
          'file:///s:/GROSIR/STRUK/'||to_char(VIR_TRANSACTIONDATE,'yyyymmdd')||'/'||VIR_CASHIERSTATION as folder							
          FROM TBTR_VIRTUAL  
          LEFT JOIN TBMASTER_USER on USERID=VIR_CASHIERID 
          WHERE TRUNC (VIR_TRANSACTIONDATE) = to_date('$tglTrans','YYYY-MM-DD')
          AND VIR_TRANSACTIONTYPE = 'CI' 
          order by KASA,ID_KASIR"
        );

        $cashIn = $cashIn->getResultArray();
      }elseif($this->request->getVar('btn')=="cashout"){
        $cashOut = $dbProd->query(
          "SELECT  
          VIR_CASHIERSTATION AS KASA,  
          VIR_CASHIERID AS ID_KASIR,  
          USERNAME as NAMAKASIR, 
          VIR_TYPE as TRANS,
          VIR_TRANSACTIONNO AS NO_TRANSAKSI,  
          VIR_AMOUNT AMOUNT,  
          VIR_FEE FEE,  
          VIR_TOTAL TOTAL,
          'file:///s:/GROSIR/STRUK/'||to_char(VIR_TRANSACTIONDATE,'yyyymmdd')||'/'||VIR_CASHIERSTATION as folder							
          FROM TBTR_VIRTUAL  
          LEFT JOIN TBMASTER_USER on USERID=VIR_CASHIERID 
          WHERE TRUNC (VIR_TRANSACTIONDATE) = to_date('$tglTrans','YYYY-MM-DD')
          AND VIR_TRANSACTIONTYPE = 'CO' 
          order by KASA,ID_KASIR"
        );

        $cashOut = $cashOut->getResultArray();
      }elseif ($this->request->getVar('btn')=="purchase") {
        $purchase = $dbProd->query(
          "SELECT  
          VIR_CASHIERSTATION AS KASA,  
          VIR_CASHIERID AS ID_KASIR,  
          USERNAME as NAMAKASIR, 
          VIR_TYPE as TRANS,
          VIR_TRANSACTIONNO AS NO_TRANSAKSI,  
          VIR_AMOUNT AMOUNT,  
          VIR_FEE FEE,  
          VIR_TOTAL TOTAL  
          FROM TBTR_VIRTUAL  
          LEFT JOIN TBMASTER_USER on USERID=VIR_CASHIERID 
          WHERE TRUNC (VIR_TRANSACTIONDATE) = to_date('$tglTrans','YYYY-MM-DD')
          AND VIR_TRANSACTIONTYPE = 'S' 
          order by KASA,ID_KASIR"
        );

        $purchase = $purchase->getResultArray();
      }

      $data =[
        'title' => 'Transaksi I-Saku',
        'cashin' => $cashIn,
        'purchase' => $purchase,
        'cashout' => $cashOut
      ];

      return view('store/transaksiisaku', $data);
    }

    public function transaksimypoint()
    {
     $tglTrans = $this->request->getVar('tglawal');
     $perolehan = $penukaran = [];
     $dbProd = db_connect('production');
     $judul = "TRANSAKSI MYPOIN";

     if ($this->request->getVar('btn')=="perolehan") {
      $judul = "PEROLEHAN MYPOIN";
      $perolehan = $dbProd->query(
        "SELECT to_char(por_create_dt,'yyyy-mm-dd hh24:mi:ss') as TRXDATE,
        por_kodetransaksi,
        por_kodemember,
        por_perolehanpoint,
        por_deskripsi
				from tbtr_perolehanmypoin where trunc(por_create_dt) = to_date('$tglTrans','YYYY-MM-DD')
				order by por_create_dt"
      );

      $perolehan = $perolehan->getResultArray();
     }elseif ($this->request->getVar('btn')=="penukaran") {
      $judul = "PENUKARAN MYPOIN";
      $penukaran = $dbProd->query(
        "SELECT to_char(pot_create_dt,'yyyy-mm-dd hh24:mi:ss') as TRXDATE,
        pot_kodetransaksi,
        pot_kodemember,
        pot_penukaranpoint   
				from tbtr_penukaranmypoin where trunc(pot_create_dt)  = to_date('$tglTrans','YYYY-MM-DD')
				order by pot_create_dt"
      );

      $penukaran = $penukaran->getResultArray();
     }

     $data = [
      'title' => 'Transaksi MyPoin',
      'perolehan' => $perolehan,
      'penukaran' => $penukaran,
      'judul' => $judul
     ];

      return view('store/transmypoint', $data);
    }

    public function transaksimitra()
    {
      $dbProd = db_connect('production');
      $tglAwal = $this->request->getVar('tglawal');
      $tglAkhir = $this->request->getVar('tglakhir');
      $jenisMember = $this->request->getVar('jenismember');
      $kodeMember = $this->request->getVar('kodemember');
      $detail = $akumulasi = [];

      if ($jenisMember=="MM") {
        $filterMember = "and nvl(cus_flagmemberkhusus,'T')='Y' ";
      }elseif ($jenisMember == "MB") {
        $filterMember = "and nvl(cus_flagmemberkhusus,'T')!='Y' ";
      }else{
        $filterMember = "";
      }

      if ($kodeMember != '') {
        $filterkdmember = " and cus_kodemember='$kodeMember' ";
      }else{
        $filterkdmember = "";
      }

      if ($this->request->getVar('btn')=="detail") {
        $detail = $dbProd->query(
          "SELECT DPP_CREATE_DT as TANGGAL,
          substr(dpp_stationkasir,0,2) as STATION,
          substr(dpp_stationkasir,3,3) as KASIR,
          dpp_kodemember,
          cus_namamember,
          case when nvl(cus_flagmemberkhusus,'T')='Y' then 'MBR MERAH' else 'MBR BIRU' end as JENIS_MEMBER,
          dpp_nohp,
          dpp_jumlahdeposit 
          FROM TBTR_DEPOSIT_MITRAIGR 
          LEFT JOIN TBMASTER_CUSTOMER ON CUS_KODEMEMBER = DPP_KODEMEMBER
          WHERE trunc(DPP_CREATE_DT) between to_date('$tglAwal','YYYY-MM-DD') and to_date('$tglAkhir','YYYY-MM-DD')
          $filterMember
          $filterkdmember
          order by DPP_CREATE_DT,dpp_stationkasir,dpp_kodemember"
        );
        $detail = $detail->getResultArray();

      }elseif($this->request->getVar('btn')=="akumulasi"){
        $akumulasi = $dbProd->query(
          "SELECT dpp_kodemember as KODEMEMBER,
          cus_namamember as NAMAMEMBER,
          dpp_nohp as NOHP,
				case when nvl(cus_flagmemberkhusus,'T')='Y' then 'MBR MERAH' else 'MBR BIRU' end as JENIS_MEMBER,
				sum(dpp_jumlahdeposit) as NILAITOPUP,
        count(dpp_notransaksi) as JUMLAHTOPUP
				FROM TBTR_DEPOSIT_MITRAIGR 
				LEFT JOIN TBMASTER_CUSTOMER ON CUS_KODEMEMBER = DPP_KODEMEMBER
				WHERE trunc(DPP_CREATE_DT) between to_date('$tglAwal','YYYY-MM-DD') and to_date('$tglAkhir','YYYY-MM-DD')
				$filterMember
        $filterkdmember
				group by dpp_kodemember, cus_namamember, dpp_nohp,case when nvl(cus_flagmemberkhusus,'T')='Y' then 'MBR MERAH' else 'MBR BIRU' end
				order by nilaitopup desc"
        );

        $akumulasi = $akumulasi->getResultArray();
      }      

      $data = [
        'title' => 'Data Transaksi Mitra',
        'detail' => $detail,
        'akumulasi' => $akumulasi,
        'tglawal' => $tglAwal,
        'tglakhir' => $tglAkhir,
      ];
      return view('store/transaksimitra', $data);
    }

    public function transaksiklik()
    {
      $dbProd = db_connect('production');
      $tglAwal = $this->request->getVar('tglawal');
      $tglAkhir = $this->request->getVar('tglakhir');

      $klik = $dbProd->query(
        "SELECT 
        obi_tglpb as TGL_PB,obi_nopb as NO_PB,obi_attribute2 as TIPE,
        obi_tglstruk as TGL_STRUK,
        obi_cashierid||'.'||obi_kdstation||'.'||obi_nostruk as NO_STRUK,
        obi_kdmember as KDMEMBER,cus_namamember as NAMAMEMBER,jh_transactionamt as TRANSACTIONAMT,
        case when nvl(jh_transactioncashamt,0)>0 then 'Tunai' end as TUNAI,
        case when nvl(jh_transactioncreditamt,0)>0 then 'Kredit' end as KREDIT,
        case when nvl(jh_debitcardamt,0)>0 then 'DebitCard' end as DC,
        case when nvl(jh_ccamt1,0)>0 then 'CreditCard' end as CC1,
        case when nvl(jh_ccamt2,0)>0 then 'CreditCard' end as CC2,
        case when nvl(jh_isaku_amt,0)>0 then 'Isaku' end as ISAKU,
        case when nvl(jh_voucheramt,0)>0 then 'Vouvher' end as VOUCHER,
        case when nvl(jh_kmmamt,0)>0 then 'KreditUsaha' end as KR_USAHA,
        case when nvl(jh_transferamt,0)>0 then 'Transfer' end as TRANSFER,
        obi_tipebayar as TIPEBAYAR
        from tbtr_obi_h 
        left join tbmaster_customer on cus_kodemember=obi_kdmember
        left join tbtr_jualheader 
          on trunc(obi_tglstruk)=trunc(jh_transactiondate)
          and obi_kdmember=jh_cus_kodemember
          and obi_cashierid=jh_cashierid
          and obi_kdstation=jh_cashierstation
          and obi_nostruk=jh_transactionno
          and obi_tipe=jh_transactiontype
        where trunc(obi_tglstruk) between to_date('$tglAwal','YYYY-MM-DD') and to_date('$tglAkhir','YYYY-MM-DD') 
        order by obi_tglstruk,no_struk"
      );
      $klik = $klik->getResultArray();
      $data = [
        'title' => 'History Transaksi Klik',
        'klik' => $klik,
        'tglawal' => $tglAwal,
        'tglakhir' => $tglAkhir,
      ];
      return view('store/transaksiklik', $data);
    }

    public function transaksiproduk()
    {
      $data = [
        'title' => 'History Transaksi Produk'
      ];
      return view('store/transaksiproduk', $data);
    }

    // Part of History Transaksi Produk
    public function transaksi()
    {
      $dbProd = db_connect('production');
      
      $isiplu = $this->request->getVar('plu');
      $tglAwal = $this->request->getVar('tglawal');
      $tglAkhir = $this->request->getVar('tglakhir');
      $jenisMember = $this->request->getVar('jenismember');
      $kodeMember = strtoupper($this->request->getVar('kodemember'));

      $filtermember = $filtermember2 = "";
      
      if (!empty($isiplu)) {
        if (is_numeric($isiplu)) {
            switch (strlen($isiplu)) {
                case 1:
                    redirect()->to('/store/transaksiproduk')->with('Error', 'Data yang anda masukkan tidak valid!');
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
                    redirect()->to('/store/transaksiproduk')->with('Error', 'PLU maksimal 7 Digit!!')->withInput();
                    break;
                default:
                    redirect()->to('/store/transaksiproduk')->with('Error', 'Data yang anda masukkan tidak valid!!')->withInput();
                    break;
            }

            $plu0 = substr($pluplusnol, 0, 6).'0';
            $plu1 = substr($pluplusnol, 0, 6).'1';
            $plu2 = substr($pluplusnol, 0, 6).'2';
            $plu3 = substr($pluplusnol, 0, 6).'3';
            
          }
          
        }else{
          return redirect()->to('/store/transaksiproduk')->with('Error', 'Masukkan PLU!!')->withInput();
        }

        if($jenisMember=="mm") {
          $filtermember="and cus_flagmemberkhusus='Y'";
        }elseif($jenisMember=="mb"){
          $filtermember="and nvl(cus_flagmemberkhusus,'T')='T'";
        }elseif($jenisMember=="all") {
          $filtermember="";
        }

        if($kodeMember!="") {
          $filtermember2="and trjd_cus_kodemember='$kodeMember'";
        }else{
          $filtermember2="";
        }

      $transaksi = $dbProd->query(
        "SELECT 
        trjd_transactiondate, 
        trjd_create_by,
        trjd_cashierstation,
        trjd_transactionno,
        trjd_prdcd,
        trjd_prd_deskripsipendek,
        prd_frac,
        trjd_quantity,
        trjd_unitprice,
        trjd_nominalamt,
        trjd_baseprice,
        trjd_transactiontype,
        trjd_cus_kodemember,
        cus_namamember,
        cus_flagmemberkhusus,
        cus_kodeoutlet,
        cus_kodesuboutlet,
        crm_idsegment
        from tbtr_jualdetail 
        left join tbmaster_prodmast on trjd_prdcd=prd_prdcd
        left join tbmaster_customer on trjd_cus_kodemember=cus_kodemember
        left join tbmaster_customercrm on trjd_cus_kodemember=crm_kodemember
        where trunc(trjd_transactiondate) between to_date('$tglAwal','yyyy-mm-dd') and to_date('$tglAkhir','yyyy-mm-dd')
        and trjd_prdcd in ('$plu0','$plu1','$plu2','$plu3') 
        $filtermember 
        $filtermember2
        order by trjd_transactiondate,trjd_create_by,trjd_cashierstation,trjd_transactionno"
      );

      $transaksi = $transaksi->getResultArray();

      $data = [
        'title' => 'transaksi produk',
        'transaksi' => $transaksi
      ];
      return view('store/transaksi', $data);
    }

    public function salesmember()
    {
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
      return view('store/salesmember', $data);
    }

    public function tampilsalesmember()
    {
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

      $tipeoutlet = $permember = $produk = $bulan = $struk = [];
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
    }elseif($jenisLaporan=="member"){
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
        return view('store/tampilsalesmember', $data);
      }elseif($this->request->getVar('btn')=="xls"){
        $tanggalSekarang = $this->tglsekarang;
        $filename = "datapromo $tanggalSekarang.xls";
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        return view('store/tampilexcelsalesmember', $data);
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
      return view('store/salesperdep',$data);
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
      
      return view('store/tampilsalesperdep', $data);
    }

    public function kompetisikasir()
    {
      $data=[
        'title' => 'Monitoring Kompetisi Kasir'
      ];
      return view('store/kompetisikasir', $data);
    }

    public function tampilkompkasir()
    {
      $dbProd = db_connect('production');
      $plu = $this->request->getVar('plu');
      $tglawal = $this->request->getVar('tglawal');
      $tglakhir = $this->request->getVar('tglakhir');
      $jenismember = $this->request->getVar('jenismember');
      $minstruk = $this->request->getVar('minstruk');
      $button = $this->request->getVar('btn');
      $pluFokus = "";

      $itemfokus = $kompetisi = $rekapkasir = $rinciankasir = $memberbelanja = $rekapplu = $rekapplutotal = [];
      if($plu==""){
        echo "Masukkan PLU terlebih dahulu";
      }else{
        $pluex = explode(",",$plu);
        foreach ($pluex as $plu0) {
          $plu0 = sprintf("%07s",$plu0);
          $plu123 = "'".substr($plu0,0,6)."0'".",'".substr($plu0,0,6)."1'".",'".substr($plu0,0,6)."2'".",'".substr($plu0,0,6)."3',";
          $pluFokus .= $plu123;
          $panjangstr = strlen($pluFokus)-1;
        }
        $plugab = substr($pluFokus,0,$panjangstr);
      }

      // pilih jenis member
      if($jenismember=="MM"){
        $filtermember= " and nvl(cus_flagmemberkhusus,'N')='Y' ";
        $judullap2 = "MEMBER MERAH";
      }else if($jenismember=="MB"){
        $filtermember= " and nvl(cus_flagmemberkhusus,'N')!='Y' ";
        $judullap2 = "MEMBER BIRU";
      }else if($jenismember="ALL"){
        $filtermember= "";
        $judullap2 = "ALL MEMBER";
      }

      if ($button=="viewrinci") {
        $judullap1 = "KOMPETISI KASIR";
        $itemfokus = $dbProd->query(
          "SELECT * from tbmaster_prodmast 
          where prd_prdcd in ($plugab) and prd_prdcd like '%0'"
        );
        $itemfokus = $itemfokus->getResultArray();

        $kompetisi = $dbProd->query(
          "SELECT IDKASIR,NAMAKASIR,STRUKALL,STRUKFOKUS,JMLMEMBER,JMLITEM,QTYSALES,RPHSALES 
          from (
          -- hitung struk all
          select distinct jh_cashierid as IDKASIR,username as NAMAKASIR,count(jh_transactionno) as STRUKALL
          from tbtr_jualheader 
          left join tbmaster_user on userid=jh_cashierid
          left join tbmaster_customer on jh_cus_kodemember=cus_kodemember
          where jh_transactiontype='S'
          and trunc(jh_transactiondate) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
          and cus_flagmemberkhusus='Y'
          group by jh_cashierid, username)
          left join (
          -- hitung strukfokus dan jmlmember
          select idkasir1,count(distinct strukfokus) as STRUKFOKUS, 
          count(distinct trjd_cus_kodemember) as JMLMEMBER,
          count(distinct substr(trjd_prdcd,0,6)) as JMLITEM,
          sum(QTYSALES) as QTYSALES,
          sum(RPHSALES) as RPHSALES
          from(
          select trjd_create_by as idkasir1,trjd_cus_kodemember,
                 concat(trjd_create_by,concat(trjd_cashierstation,concat(trjd_transactionno,concat('.',trjd_transactiondate)))) as STRUKFOKUS,
                 trjd_prdcd,trjd_prd_deskripsipendek,trjd_quantity*prd_frac as QTYSALES,trjd_nominalamt as RPHSALES
          from tbtr_jualdetail
          left join tbmaster_customer on trjd_cus_kodemember=cus_kodemember
          left join tbmaster_prodmast on prd_prdcd=trjd_prdcd
          where trunc(trjd_transactiondate)  between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
          and trjd_transactiontype='S'
          and trjd_nominalamt>=$minstruk
          $filtermember
          and trjd_prdcd in ($plugab))group by idkasir1)on idkasir=idkasir1
          order by idkasir"
        );

        $kompetisi = $kompetisi->getResultArray();
      }elseif($button=="viewrekapkasir"){
        $judullap1 = "REKAP PENCAPAIAN PERKASIR";
        $itemfokus = $dbProd->query(
          "SELECT * from tbmaster_prodmast 
          where prd_prdcd in ($plugab) and prd_prdcd like '%0'"
        );
        $itemfokus = $itemfokus->getResultArray();

        $rekapkasir = $dbProd->query(
          "SELECT idkasir,namakasir,count(distinct kdmember) as JML_MEMBER,sum(qty_pcs) as QTY_SALES 
          from (
            SELECT 
              trjd_transactiondate as tgltrans,
              to_char(trjd_transactiondate,'yyyymmdd')||'-'||trjd_create_by||'.'||trjd_cashierstation||'.'||trjd_transactionno as STRUK,
              trjd_create_by as idkasir,
              username as namakasir,
              trjd_cus_kodemember as kdmember,
              cus_namamember as namamember,
              sum(trjd_quantity*prd_frac) as qty_pcs
            from tbtr_jualdetail
            left join tbmaster_prodmast on prd_prdcd=trjd_prdcd
            left join tbmaster_customer on cus_kodemember=trjd_cus_kodemember
            left join tbmaster_user on userid=trjd_create_by
            where trunc(trjd_transactiondate)  between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
              and trjd_transactiontype='S'
              $filtermember
              and trjd_prdcd in ($plugab)
            group by trjd_transactiondate, to_char(trjd_transactiondate,'yyyymmdd')||'-'||trjd_create_by||'.'||trjd_cashierstation||'.'||trjd_transactionno, trjd_create_by, username, trjd_cus_kodemember, cus_namamember
            ) 
          where qty_pcs>=$minstruk 
          group by idkasir, namakasir
          order by idkasir"
                  );
            $rekapkasir = $rekapkasir->getResultArray();
      }elseif($button=="viewrinciankasir"){
        $judullap1 = "RINCIAN STRUK PERKASIR";
        $itemfokus = $dbProd->query(
          "SELECT * from tbmaster_prodmast 
          where prd_prdcd in ($plugab) and prd_prdcd like '%0'"
        );
        $itemfokus = $itemfokus->getResultArray();

        $rinciankasir = $dbProd->query(
          "SELECT * 
          from (
            select 
              trjd_transactiondate as tgltrans,
              to_char(trjd_transactiondate,'yyyymmdd')||'-'||trjd_create_by||'.'||trjd_cashierstation||'.'||trjd_transactionno as STRUK,
              trjd_create_by as idkasir,
              username as namakasir,
              trjd_cus_kodemember as kdmember,
              cus_namamember as namamember,
              sum(trjd_quantity*prd_frac) as qty_pcs
            from tbtr_jualdetail
            left join tbmaster_prodmast on prd_prdcd=trjd_prdcd
            left join tbmaster_customer on cus_kodemember=trjd_cus_kodemember
            left join tbmaster_user on userid=trjd_create_by
            where trunc(trjd_transactiondate)  between to_date('$tglawal', 'YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
              and trjd_transactiontype='S'
              $filtermember
              and trjd_prdcd in ($plugab)
            group by trjd_transactiondate,to_char(trjd_transactiondate,'yyyymmdd')||'-'||trjd_create_by||'.'||trjd_cashierstation||'.'||trjd_transactionno, trjd_create_by, username, trjd_cus_kodemember, cus_namamember
            ) 
          where qty_pcs>=$minstruk 
          order by idkasir,namamember,struk"
        );
        $rinciankasir = $rinciankasir->getResultArray();
      }elseif($button=="viewdatamember"){
        $judullap1 = "DATA MEMBER BELANJA";
        $itemfokus = $dbProd->query(
          "SELECT * from tbmaster_prodmast 
          where prd_prdcd in ($plugab) and prd_prdcd like '%0'"
        );
        $itemfokus = $itemfokus->getResultArray();

        $memberbelanja = $dbProd->query(
          "SELECT kdmember,namamember,count(struk) as count_struk,sum(qty_pcs) as sum_qty_pcs 
          from (
            select 
              trjd_transactiondate as tgltrans,
              to_char(trjd_transactiondate,'yyyymmdd')||'-'||trjd_create_by||'.'||trjd_cashierstation||'.'||trjd_transactionno as STRUK,
              trjd_create_by as idkasir,
              username as namakasir,
              trjd_cus_kodemember as kdmember,
              cus_namamember as namamember,
              sum(trjd_quantity*prd_frac) as qty_pcs
            from tbtr_jualdetail
            left join tbmaster_prodmast on prd_prdcd=trjd_prdcd
            left join tbmaster_customer on cus_kodemember=trjd_cus_kodemember
            left join tbmaster_user on userid=trjd_create_by
            where trunc(trjd_transactiondate)  between to_date('$tglawal', 'YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
              and trjd_transactiontype='S'
              $filtermember
              and trjd_prdcd in ($plugab)
            group by trjd_transactiondate, to_char(trjd_transactiondate,'yyyymmdd')||'-'||trjd_create_by||'.'||trjd_cashierstation||'.'||trjd_transactionno, trjd_create_by, username, trjd_cus_kodemember, cus_namamember
            ) 
          where qty_pcs>=$minstruk 
          group by kdmember, namamember 
          order by namamember"
        );

        $memberbelanja = $memberbelanja->getResultArray();
      }elseif($button=="viewglobal"){
        $judullap1 = "REKAP PER-PLU";

        $rekapplu = $dbProd->query(
          "SELECT concat(rpad(trjd_prdcd,6),'0') as PLU, 
          prd_deskripsipanjang as DESKRIPSI, 
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
              when (trjd_transactiontype='S')and(cus_kodeoutlet='4') and(trjd_flagtax1='Y') then trjd_nominalamt*1.11 
              when trjd_transactiontype='S' then trjd_nominalamt*1 
              when trjd_transactiontype='R' then trjd_nominalamt*(-1) 
              end) as RPH_GROSS, 
          /*Sales Nett*/   
            sum(case   
              /*non omi */   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1.11   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1   
              when (trjd_transactiontype='R')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1.11)   
              when (trjd_transactiontype='R')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1)   
              /*omi */   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N' )='Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1   
              end) as S_NETT,   
            
           /*Margin*/   
           sum(case   
              /*non omi */   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))   
              when (trjd_transactiontype='R')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))*(-1)   
              when (trjd_transactiontype='R')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))*(-1)   
              /*pengecualian untuk unit KG*/   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity/1000))   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))   
              /*omi */   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)   
              end) as MARGIN, 
              count(distinct TO_DATE(TRJD_TRANSACTIONDATE,'DD-MM-YYYY')) as HARISALES 
     from tbtr_jualdetail 
     left join tbmaster_prodmast on trjd_prdcd=prd_prdcd 
     left join tbmaster_customer on trjd_cus_kodemember=cus_kodemember 
     where trunc(trjd_transactiondate)  between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
     $filtermember
     and trjd_prdcd in ($plugab)
     and trjd_nominalamt>=$minstruk 
     group by concat(rpad(trjd_prdcd,6),'0'), prd_deskripsipanjang
     order by plu"
        );

        $rekapplu = $rekapplu->getResultArray();

        $rekapplutotal = $dbProd->query(
          "SELECT trjd_kodeigr,cab_namacabang,
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
              when (trjd_transactiontype='S')and(cus_kodeoutlet='4') and(trjd_flagtax1='Y') then trjd_nominalamt*1.11 
              when trjd_transactiontype='S' then trjd_nominalamt*1 
              when trjd_transactiontype='R' then trjd_nominalamt*(-1) 
              end) as RPH_GROSS, 
          /*Sales Nett*/   
            sum(case   
              /*non omi */   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1.11   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/1   
              when (trjd_transactiontype='R')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1.11)   
              when (trjd_transactiontype='R')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') then trjd_nominalamt/(-1)   
              /*omi */   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N' )='Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')='4')  then trjd_nominalamt*1   
              end) as S_NETT,   
            
           /*Margin*/   
           sum(case   
              /*non omi */   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))   
              when (trjd_transactiontype='R')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity))*(-1)   
              when (trjd_transactiontype='R')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit<>'KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity))*(-1)   
              /*pengecualian untuk unit KG*/   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1.11 - (trjd_baseprice * trjd_quantity/1000))   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6')<>'4') and (prd_unit ='KG') then (trjd_nominalamt/1   - (trjd_baseprice * trjd_quantity/1000))   
              /*omi */   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N') ='Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)   
              when (trjd_transactiontype='S')and(nvl(trjd_flagtax1,'N')<>'Y') and (nvl(cus_kodeoutlet,'6') ='4')                      then trjd_nominalamt*1 - (trjd_baseprice * trjd_quantity)   
              end) as MARGIN, 
              count(distinct TO_DATE(TRJD_TRANSACTIONDATE,'DD-MM-YYYY')) as HARISALES 
     from tbtr_jualdetail 
     left join tbmaster_prodmast on trjd_prdcd=prd_prdcd 
     left join tbmaster_customer on trjd_cus_kodemember=cus_kodemember 
     left join tbmaster_cabang on cab_kodecabang=trjd_kodeigr
     where trunc(trjd_transactiondate)  between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
     $filtermember
     and trjd_prdcd in ($plugab)
     and trjd_nominalamt>=$minstruk
     group by trjd_kodeigr,cab_namacabang"
        );

        $rekapplutotal = $rekapplutotal->getResultArray();
      }

      $data=[
        'title' => 'Monitoring Kompetisi Kasir',
        'tglawal' => $tglawal,
        'tglakhir' => $tglakhir,
        'plu' => $plu,
        'jenismember' => $jenismember,
        'judul1' => $judullap1,
        'judul2' => $judullap2,
        'itemfokus' => $itemfokus,
        'minstruk' => $minstruk,
        'kompetisi' => $kompetisi,
        'plugab' => $plugab,
        'rekapkasir' => $rekapkasir,
        'rinciankasir' => $rinciankasir,
        'memberbelanja' => $memberbelanja,
        'rekapplu' => $rekapplu,
        'rekapplutotal' => $rekapplutotal,
      ];
      return view('store/tampilkompkasir',$data);
    }

    public function detailitemfokus()
    {
      $dbProd = db_connect('production');
      $tglawal = $this->request->getGet('tglawal');
      $tglakhir = $this->request->getGet('tglakhir');
      $plugab = $this->request->getGet('plugab');
      $idkasir = $this->request->getGet('idkasir');
      $namakasir = $this->request->getGet('namakasir');

      if ($idkasir=="all") {
        $filteridkasir = "";
      }else{
        $filteridkasir="and (trjd_create_by='$idkasir')";
      }

      if($namakasir=="all") {
        $filternamakasir="";
      }else{
        $filternamakasir="ALL";
      }

      $jualdetail = $dbProd->query(
        "SELECT trjd_create_by, 
        trjd_cashierstation, 
        trjd_transactionno, 
        trjd_transactiondate, 
        trjd_transactiontype,
        trjd_prdcd,
        trjd_prd_deskripsipendek, 
        trjd_quantity, 
        trjd_nominalamt, 
        trjd_cus_kodemember,
        cus_flagmemberkhusus
        from tbtr_jualdetail left join tbmaster_customer on trjd_cus_kodemember=cus_kodemember
        where trunc(trjd_transactiondate) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
           and trjd_transactiontype='S'
           $filteridkasir
           and (trjd_prdcd IN($plugab))
          order by trjd_create_dt,trjd_transactionno"
      );

      $jualdetail = $jualdetail->getResultArray();

      $data = [
        "title" => 'Detail Item Fokus',
        'jualdetail' => $jualdetail,
        'idkasir' => $idkasir,
        'namakasir' => $namakasir
      ];
      return view('store/detailitemfokus', $data);
    }

    public function monitoringklik()
    {
      $dbProd = db_connect('production');
      $tglawal = $this->request->getVar('tglawal');
      $tglakhir = $this->request->getVar('tglakhir');
      $tgltrans = $this->request->getVar('tgltrans');
      $btn = $this->request->getVar('btn');

      $semua = $proses = $pertanggal = $detailtanggal = $selisih = [];

      if($btn=="semua") {
        $filterdata = "";
        $semua =  $dbProd->query(
          "SELECT ATTRIBUT,RECID,KETERANGAN,count(nopb) as JUMLAHPB from (
            select 
            obi_tgltrans as TGLTRANS,
            obi_notrans as NOTRANS,
            obi_nopb as NOPB,
            case 
              when obi_attribute2='KlikIGR' and cus_jenismember='T' then 'TMI'
              when obi_attribute2='KlikIGR' and nvl(cus_flagmemberkhusus,'N')='Y' then 'Member Merah'
              when obi_attribute2='KlikIGR' and nvl(cus_flagmemberkhusus,'N')!='Y' then 'Member Umum'
              else obi_attribute2 
            end as ATTRIBUT,
            obi_kdmember as KDMEMBER,
            obi_ttlorder as NILAIORDER,
            obi_itemorder as ITEMORDER,
            obi_sendpick as SENDHH,
            obi_selesaipick as PICKING,
            obi_selesaiscan as PACKING,
            obi_draftstruk as DRAFTSTRUK,
            obi_tglstruk as STRUK,
            substr(obi_recid,0,1) as RECID,
            case 
              when obi_recid is null then 'Siap Send HH'
              when obi_recid='0' then 'Siap Send HH'
              when obi_recid='1' then 'Siap Picking'
              when obi_recid='2' then 'Siap Packing'
              when obi_recid='3' then 'Siap Draft Struk'
              when obi_recid='4' then 'Konfirmasi Pembayaran'
              when obi_recid='5' then 'Siap Struk'
              when obi_recid='6' then 'Selesai Struk'
              when obi_recid='7' then 'Set Ongkir'
              when obi_recid like '%B%' then 'BATAL'
            end as KETERANGAN
            from tbtr_obi_h obih
            left join tbmaster_customer on cus_kodemember=obi_kdmember
            where trunc(obih.obi_tgltrans) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD') 
            $filterdata
            order by obi_tgltrans,obi_notrans
            ) group by attribut,recid, keterangan
            order by 1,2"
        );
        $semua = $semua->getResultArray();
      }elseif($btn=="proses"){
        $filterdata = " and nvl(obi_recid,0) in ('0','1','3','4','5','7') ";
        $proses = $dbProd->query(
          "SELECT ATTRIBUT,RECID,KETERANGAN,count(nopb) as JUMLAHPB from (
            select 
            obi_tgltrans as TGLTRANS,
            obi_notrans as NOTRANS,
            obi_nopb as NOPB,
            case 
              when obi_attribute2='KlikIGR' and cus_jenismember='T' then 'TMI'
              when obi_attribute2='KlikIGR' and nvl(cus_flagmemberkhusus,'N')='Y' then 'Member Merah'
              when obi_attribute2='KlikIGR' and nvl(cus_flagmemberkhusus,'N')!='Y' then 'Member Umum'
              else obi_attribute2 
            end as ATTRIBUT,
            obi_kdmember as KDMEMBER,
            obi_ttlorder as NILAIORDER,
            obi_itemorder as ITEMORDER,
            obi_sendpick as SENDHH,
            obi_selesaipick as PICKING,
            obi_selesaiscan as PACKING,
            obi_draftstruk as DRAFTSTRUK,
            obi_tglstruk as STRUK,
            substr(obi_recid,0,1) as RECID,
            case 
              when obi_recid is null then 'Siap Send HH'
              when obi_recid='0' then 'Siap Send HH'
              when obi_recid='1' then 'Siap Picking'
              when obi_recid='2' then 'Siap Packing'
              when obi_recid='3' then 'Siap Draft Struk'
              when obi_recid='4' then 'Konfirmasi Pembayaran'
              when obi_recid='5' then 'Siap Struk'
              when obi_recid='6' then 'Selesai Struk'
              when obi_recid='7' then 'Set Ongkir'
              when obi_recid like '%B%' then 'BATAL'
            end as KETERANGAN
            from tbtr_obi_h obih
            left join tbmaster_customer on cus_kodemember=obi_kdmember
            where trunc(obih.obi_tgltrans) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD') 
            $filterdata
            order by obi_tgltrans,obi_notrans
            ) group by attribut,recid, keterangan
            order by 1,2"
        );
        $proses = $proses->getResultArray();
      }elseif($btn=="pertanggal"){
        $pertanggal = $dbProd->query(
          "SELECT obi_tgltrans as TGLTRANS,
          count(obi_nopb) as JMLPB,
          count(obi_sendpick) as SUDAH_SENDHH,
          count(obi_selesaipick) as SUDAH_PICKING,
          count(obi_selesaiscan) as SUDAH_PACKING,
          count(obi_draftstruk) as SUDAH_DRAFTSTRUK,
          count(obi_tglstruk) as SUDAH_STRUK
          from tbtr_obi_h 
          where trunc(obi_tgltrans) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
          and nvl(obi_recid,0) in ('0','1','2','3','4','5','6','7')
          group by obi_tgltrans
          order by obi_tgltrans desc"
        );
        $pertanggal = $pertanggal->getResultArray();
      }elseif($btn=="selisih"){
        $selisih = $dbProd->query(
          "SELECT TGL, PLU, DESK,NOTRANS,QTYORDER, QTYREAL, SELISIH 
          FROM (SELECT OBI_TGLTRANS TGL, OBI_PRDCD PLU, PRD_DESKRIPSIPANJANG DESK, OBI_NOTRANS NOTRANS,OBI_QTYORDER QTYORDER, 
          OBI_QTYREALISASI QTYREAL, (OBI_QTYORDER-OBI_QTYREALISASI) SELISIH 
          FROM TBTR_OBI_D 
          LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = OBI_PRDCD
          WHERE trunc(OBI_TGLTRANS) BETWEEN to_date('$tglawal', 'YYYY-MM-DD') AND to_date('$tglakhir', 'YYYY-MM-DD'))
          ORDER BY TGL"
        );
        $selisih = $selisih->getResultArray();
      }

      if (!empty($tgltrans)) {
        $detailtanggal = $dbProd->query(
          "SELECT 
          OBI_TGLTRANS as TGLTRANS,
          OBI_NOTRANS as NOTRANS,
          case 
            when obi_attribute2='KlikIGR' and cus_jenismember='T' then 'TMI'
            when obi_attribute2='KlikIGR' and nvl(cus_flagmemberkhusus,'N')='Y' then 'Mbr Merah'
            when obi_attribute2='KlikIGR' and nvl(cus_flagmemberkhusus,'N')!='Y' then 'Mbr Umum'
            else obi_attribute2 
          end as ATTRIBUT,
          OBI_KDMEMBER||' - '||CUS_NAMAMEMBER as KODEMEMBER,
          OBI_TTLORDER as RPHORDER,
          OBI_ITEMORDER as ITEMORDER,
          OBI_SENDPICK as SENDHH,
          OBI_SELESAIPICK as SELESAIPICK,
          OBI_SELESAISCAN as SELESAISCAN,
          case 
            when obi_kdekspedisi like '%SAMEDAY%' then 'Sameday' 
            when obi_kdekspedisi like '%Ambil di Toko%' then 'Ambil Toko' 
            else 'Nextday' end as SERVICE,
          case 
            when nvl(obi_recid,0)='0' then 'Siap Send HH'
            when nvl(obi_recid,0)='1' then 'Siap Picking'
            when nvl(obi_recid,0)='2' then 'Siap Packing'
            when nvl(obi_recid,0)='3' then 'Siap Draft Struk'
            when nvl(obi_recid,0)='4' then 'Konfirmasi Pembayaran'
            when nvl(obi_recid,0)='5' then 'Siap Struk'
            when nvl(obi_recid,0)='6' then 'Selesai Struk'
            when nvl(obi_recid,0)='7' then 'Set Ongkir'
            when nvl(obi_recid,0) like '%B%' then 'BATAL'
          end as KETERANGAN,
          case
            when obi_tipebayar ='COD' then 'COD'
          end as TIPEBAYAR
          from tbtr_obi_h 
          left join tbmaster_customer on cus_kodemember=obi_kdmember
          where trunc(obi_tgltrans) = '$tgltrans' 
          and substr(nvl(obi_recid,0),0,1) not in ('6','B')
          order by obi_tgltrans,SERVICE desc,obi_notrans"
        );
        $detailtanggal = $detailtanggal->getResultArray();
      }


      $data = [
        'title' => 'Monitoring Klik IGR',
        'tglawal' => $tglawal,
        'tglakhir' => $tglakhir,
        'semua' => $semua,
        'proses' => $proses,
        'pertanggal' => $pertanggal,
        'tgltrans' => $tgltrans,
        'detailtanggal' => $detailtanggal,
        'selisih' => $selisih
      ];
      d($data);

      redirect()->to('monitoringklik')->withInput();
      return view('store/monitoringklik',$data);
    }

    public function slklik()
    {

      $data = [
        'title' => 'Service Level KLIK'
      ];

      return view('store/slklik', $data);
    }

    public function tampilslklik()
    {
      $dbProd = db_connect('production');

      $tglawal = $this->request->getVar('tglawal');
      $tglakhir = $this->request->getVar('tglakhir');
      $jenis = $this->request->getVar('jenis');
      $kodemember = $this->request->getVar('kdmember');
      $btn = $this->request->getVar('btn');

      //set filter data
      if($kodemember=="") {
        $filterkodemember       = "";
        $judul_filterkodemember = "ALL";
      }else{
        $filterkodemember       = " and cus_kodemember='$kodemember' ";
        $judul_filterkodemember = "KODE MEMBER : $kodemember";
      }

      $perpb = $perproduk = [];

      if($jenis=="rekapnopb"){
        $judul = "Rekap Order Klik - Per Nomor PB | Periode : $tglawal s/d $tglakhir";
        $perpb = $dbProd->query(
          "SELECT 
          KDMEMBER,
          NAMAMEMBER,
          HPMEMBER,
          JENISMEMBER,
          NOMORPB,
          PENGIRIMAN,
          TIPEBAYAR,
          STATUSPB,
          TGLTRANS,
          NOTRANS,
          count(ITEM_ORDER) as ITEM_ORDER,
          sum(QTY_ORDER) as QTY_ORDER,
          sum(RPH_ORDER) as RPH_ORDER,
          count(ITEM_REALISASI) as ITEM_REALISASI,
          sum(QTY_REALISASI) as QTY_REALISASI,
          sum(RPH_REALISASI) as RPH_REALISASI,
          PICKER,
          PBMASUK,
          SELESAIPICK,
          SELESAIPACK,
          TGLSTRUK,
          TGLAWB,
          NOAWB
        from (
        SELECT 
          obih.obi_kdmember as KDMEMBER,
          cus_namamember as NAMAMEMBER,
          cus_hpmember as HPMEMBER,
          case 
            when nvl(cus_flagmemberkhusus,'T') ='Y' and nvl(cus_jenismember,'0')!='T' then 'MM' 
            when nvl(cus_flagmemberkhusus,'T') ='Y' and nvl(cus_jenismember,'0') ='T' then 'MM-TMI' 
            when nvl(cus_flagmemberkhusus,'T') !='Y' then 'MB' 
           end as JENISMEMBER,
          obih.obi_attribute2 as KRITERIA,
          obih.obi_nopb as NOMORPB,
          obih.obi_kdekspedisi as PENGIRIMAN,
          obih.obi_tipebayar as TIPEBAYAR,
          case 
            when nvl(obih.obi_recid,0)='0' then 'Siap Send HH'
            when nvl(obih.obi_recid,0)='1' then 'Siap Picking'
            when nvl(obih.obi_recid,0)='2' then 'Siap Packing'
            when nvl(obih.obi_recid,0)='3' then 'Siap Draft Struk'
            when nvl(obih.obi_recid,0)='4' then 'Konfirmasi Pembayaran'
            when nvl(obih.obi_recid,0)='5' then 'Siap Struk'
            when nvl(obih.obi_recid,0)='6' then 'Selesai Struk'
            when nvl(obih.obi_recid,0)='7' then 'Set Ongkir'
            when nvl(obih.obi_recid,0) like '%B%' then 'BATAL'
          end as STATUSPB,
          obih.obi_tgltrans as TGLTRANS,
          obih.obi_notrans as NOTRANS,
          to_char(obih.obi_createdt,'dd-MON-YYYY hh24:mi:ss') as PBMASUK,
          to_char(obih.obi_selesaipick,'dd-MON-YYYY hh24:mi:ss') as SELESAIPICK,
          to_char(obih.obi_selesaiscan,'dd-MON-YYYY hh24:mi:ss') as SELESAIPACK,
          to_char(obih.obi_tglstruk,'dd-MON-YYYY hh24:mi:ss') as TGLSTRUK,
          obid.obi_picker as PICKER,
          obid.obi_prdcd as PLUORDER,
          trjd_prd_deskripsipendek as DESKRIPSIPENDEK,
          prd_frac as FRAC,
          obi_hargasatuan as HRGSATUAN,
          obi_ppn as PPN,
          (obi_prdcd) as ITEM_ORDER,
          (obi_qtyorder) as QTY_ORDER,
          ((obi_hargasatuan-obi_diskon)*obi_qtyorder) as RPH_ORDER,
          (obi_ppn*obi_qtyorder) as PPN_ORDER,
          (trjd_prdcd) as ITEM_REALISASI,
          case 
            when prd_unit='KG' then (trjd_quantity/prd_frac) 
            else (trjd_quantity*prd_frac) 
          end as QTY_REALISASI,
          case 
            when prd_unit='KG' then (trjd_nominalamt-(round(obi_ppn)*(trjd_quantity/prd_frac))) 
            else (trjd_nominalamt-(round(obi_ppn)*(trjd_quantity*prd_frac))) 
          end as RPH_REALISASI,
          case 
            when prd_unit='KG' then (round(obi_ppn)*(trjd_quantity/prd_frac))
            else (round(obi_ppn)*(trjd_quantity*prd_frac)) 
          end as PPN_REALISASI,
          AWI_NOAWB as NOAWB,
          to_char(AWI_CREATE_DT,'dd-MON-yyyy hh24:mi:ss') as TGLAWB
          
        FROM igrbgr.tbtr_obi_h obih
        LEFT JOIN igrbgr.tbtr_obi_d obid on trunc(obih.obi_tgltrans)=trunc(obid.obi_tgltrans) and obih.obi_notrans=obid.obi_notrans
        LEFT JOIN igrbgr.tbtr_awb_ipp on awi_nopb=obih.obi_nopb
        left join tbtr_jualdetail
          ON trjd_cus_kodemember         =obih.obi_kdmember
          AND trjd_cashierstation        =obih.obi_kdstation     
          AND TRUNC(trjd_transactiondate)=TRUNC(obih.obi_tglstruk)     
          AND trjd_transactionno         =obih.obi_nostruk     
          AND trjd_create_by             =obih.obi_modifyby  
          AND trjd_prdcd                 =obid.obi_prdcd
        LEFT JOIN tbmaster_prodmast on prd_prdcd=obi_prdcd
        LEFT JOIN tbmaster_customer on obih.obi_kdmember=cus_kodemember
        WHERE TRUNC(obih.obi_tgltrans) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
        $filterkodemember
        
        )group by KDMEMBER, NAMAMEMBER, HPMEMBER, JENISMEMBER, NOMORPB, PENGIRIMAN, TIPEBAYAR, STATUSPB, TGLTRANS, NOTRANS, PICKER, PBMASUK, SELESAIPICK, SELESAIPACK, TGLSTRUK, TGLAWB, NOAWB
        ORDER BY TGLTRANS,NOTRANS"
        );

        $perpb = $perpb->getResultArray();
      }elseif($jenis=="detailplu"){
        $judul = "PB vs REALISASI KLIK PER-PLU | Periode : $tglawal s/d $tglakhir";
        $perproduk = $dbProd->query(
          "SELECT 
          prd_kodedivisi as DIV,
          prd_kodedepartement as DEP,
          prd_kodekategoribarang as KAT,
          prd_prdcd as PLU,
          prd_deskripsipanjang as DESKRIPSI,
          prd_frac as FRAC,
          prd_unit as UNIT,
          count(ITEM_ORDER) as JML_PB,
          count(ITEM_REALISASI) as JML_REALISASI,
          count(ITEM_ORDER) - count(ITEM_REALISASI) as JML_PBVSREALISASI,
          sum(nvl(QTY_ORDER,0)) as QTY_ORDER,
          sum(nvl(QTY_REALISASI,0)) as QTY_REALISASI,
          sum(nvl(QTY_ORDER,0)) - sum(nvl(QTY_REALISASI,0)) as QTY_PBVSREALISASI,
          sum(nvl(RPH_ORDER,0)) as RPH_ORDER,
          sum(nvl(RPH_REALISASI,0)) as RPH_REALISASI,
          sum(nvl(RPH_ORDER,0)) - sum(nvl(RPH_REALISASI,0)) as RPH_PBVSREALISASI
        FROM tbmaster_prodmast
        LEFT JOIN (
        SELECT 
          obih.obi_kdmember as KDMEMBER,
          cus_namamember as NAMAMEMBER,
          cus_hpmember as HPMEMBER,
          case 
            when nvl(cus_flagmemberkhusus,'T') ='Y' and nvl(cus_jenismember,'0')!='T' then 'MM' 
            when nvl(cus_flagmemberkhusus,'T') ='Y' and nvl(cus_jenismember,'0') ='T' then 'MM-TMI' 
            when nvl(cus_flagmemberkhusus,'T') !='Y' then 'MB' 
           end as JENISMEMBER,
          obih.obi_attribute2 as KRITERIA,
          obih.obi_nopb as NOPB,
          obih.obi_tgltrans as TGLTRANS,
          obih.obi_notrans as NOTRANS,
          to_char(obih.obi_createdt,'dd-MON-YYYY hh24:mi:ss') as PBMASUK,
          to_char(obih.obi_selesaipick,'dd-MON-YYYY hh24:mi:ss') as SELESAIPICK,
          to_char(obih.obi_selesaiscan,'dd-MON-YYYY hh24:mi:ss') as SELESAIPACK,
          to_char(obih.obi_tglstruk,'dd-MON-YYYY hh24:mi:ss') as TGLSTRUK,
          obid.obi_picker as PICKER,
          obid.obi_prdcd as PLUORDER,
          trjd_prd_deskripsipendek as DESKRIPSIPENDEK,
          prd_frac as FRAC,
          obi_hargasatuan as HRGSATUAN,
          obi_ppn as PPN,
          (obi_prdcd) as ITEM_ORDER,
          (obi_qtyorder) as QTY_ORDER,
          ((obi_hargasatuan-obi_diskon)*obi_qtyorder) as RPH_ORDER,
          (obi_ppn*obi_qtyorder) as PPN_ORDER,
          (trjd_prdcd) as ITEM_REALISASI,
          case 
            when prd_unit='KG' then (trjd_quantity/prd_frac) 
            else (trjd_quantity*prd_frac) 
          end as QTY_REALISASI,
          case 
            when prd_unit='KG' then (trjd_nominalamt-(round(obi_ppn)*(trjd_quantity/prd_frac))) 
            else (trjd_nominalamt-(round(obi_ppn)*(trjd_quantity*prd_frac))) 
          end as RPH_REALISASI,
          case 
            when prd_unit='KG' then (round(obi_ppn)*(trjd_quantity/prd_frac))
            else (round(obi_ppn)*(trjd_quantity*prd_frac)) 
          end as PPN_REALISASI
          
        FROM tbtr_obi_h obih
        LEFT JOIN igrbgr.tbtr_obi_d obid on trunc(obih.obi_tgltrans)=trunc(obid.obi_tgltrans) and obih.obi_notrans=obid.obi_notrans
        left join tbtr_jualdetail
          ON trjd_cus_kodemember         =obih.obi_kdmember
          AND trjd_cashierstation        =obih.obi_kdstation     
          AND TRUNC(trjd_transactiondate)=TRUNC(obih.obi_tglstruk)     
          AND trjd_transactionno         =obih.obi_nostruk     
          AND trjd_create_by             =obih.obi_modifyby  
          AND trjd_prdcd                 =obid.obi_prdcd
        LEFT JOIN tbmaster_prodmast on prd_prdcd=obi_prdcd
        LEFT JOIN tbmaster_customer on obih.obi_kdmember=cus_kodemember
        
        WHERE TRUNC(obih.obi_tgltrans) between to_date('$tglawal','YYYY-MM-DD') and to_date('$tglakhir','YYYY-MM-DD')
        $filterkodemember
        
        ) on prd_prdcd =substr(pluorder,0,6)||'0'
        WHERE NOPB is not null 
        GROUP BY prd_kodedivisi, prd_kodedepartement, prd_kodekategoribarang, prd_prdcd, prd_deskripsipanjang, prd_frac, prd_unit
        ORDER BY DESKRIPSI "
        );
        $perproduk = $perproduk->getResultArray();
      }

      $data = [
        'title' => 'Data '.$this->tglsekarang,
        'perpb' => $perpb,
        'perproduk' => $perproduk,
        'judul1' => $judul,
        'judul2' => $judul_filterkodemember,
      ];

      if($btn=="tampil"){
        return view('store/tampilslklik', $data);
      }elseif($btn=="xls"){
        $tanggalSekarang = $this->tglsekarang;
        $filename = "SL KLIK $tanggalSekarang.xls";
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");
        return view('store/tampilexcelslklik', $data);
      }
    }

    public function detailpbklik()
    {
      $dbProd = db_connect('production');
      $nopb = $this->request->getGet('nopb');
      
      $judul = "Nomor PB : $nopb";
      $detail = $dbProd->query(
        "SELECT 
        obih.obi_kdmember as KDMEMBER,
        cus_namamember as NAMAMEMBER,
        cus_hpmember as HPMEMBER,
        case 
          when nvl(cus_flagmemberkhusus,'T') ='Y' and nvl(cus_jenismember,'0')!='T' then 'MM' 
          when nvl(cus_flagmemberkhusus,'T') ='Y' and nvl(cus_jenismember,'0') ='T' then 'MM-TMI' 
          when nvl(cus_flagmemberkhusus,'T') !='Y' then 'MB' 
         end as JENISMEMBER,
        obih.obi_attribute2 as KRITERIA,
        obih.obi_nopb as NOMORPB,
        obih.obi_kdekspedisi as PENGIRIMAN,
        obih.obi_tipebayar as TIPEBAYAR,
        case 
          when nvl(obih.obi_recid,0)='0' then 'Siap Send HH'
          when nvl(obih.obi_recid,0)='1' then 'Siap Picking'
          when nvl(obih.obi_recid,0)='2' then 'Siap Packing'
          when nvl(obih.obi_recid,0)='3' then 'Siap Draft Struk'
          when nvl(obih.obi_recid,0)='4' then 'Konfirmasi Pembayaran'
          when nvl(obih.obi_recid,0)='5' then 'Siap Struk'
          when nvl(obih.obi_recid,0)='6' then 'Selesai Struk'
          when nvl(obih.obi_recid,0)='7' then 'Set Ongkir'
          when nvl(obih.obi_recid,0) like '%B%' then 'BATAL'
        end as STATUSPB,
        obih.obi_tgltrans as TGLTRANS,
        obih.obi_notrans as NOTRANS,
        to_char(obih.obi_createdt,'dd-MON-YYYY hh24:mi:ss') as PBMASUK,
        to_char(obih.obi_selesaipick,'dd-MON-YYYY hh24:mi:ss') as SELESAIPICK,
        to_char(obih.obi_selesaiscan,'dd-MON-YYYY hh24:mi:ss') as SELESAIPACK,
        to_char(obih.obi_tglstruk,'dd-MON-YYYY hh24:mi:ss') as TGLSTRUK,
        obid.obi_picker as PICKER,
        obid.obi_prdcd as PLUORDER,
        prd_deskripsipanjang as DESKRIPSI,
        prd_frac as FRAC,
        obi_hargasatuan as HRGSATUAN,
        obi_ppn as PPN,
        (obi_qtyorder) as QTY_ORDER,
        (obi_qtyrealisasi) as QTY_PICKING,
        ((obi_hargasatuan-obi_diskon)*obi_qtyorder) as RPH_ORDER,
        (obi_ppn*obi_qtyorder) as PPN_ORDER,
        ((obi_hargasatuan-obi_diskon)*obi_qtyrealisasi) as RPH_PICKING,
        (trjd_prdcd) as ITEM_REALISASI,
        case 
          when prd_unit='KG' then (trjd_quantity/prd_frac) 
          else (trjd_quantity*prd_frac) 
        end as QTY_REALISASI,
        case 
          when prd_unit='KG' then (trjd_nominalamt-(round(obi_ppn)*(trjd_quantity/prd_frac))) 
          else (trjd_nominalamt-(round(obi_ppn)*(trjd_quantity*prd_frac))) 
        end as RPH_REALISASI,
        case 
          when prd_unit='KG' then (round(obi_ppn)*(trjd_quantity/prd_frac))
          else (round(obi_ppn)*(trjd_quantity*prd_frac)) 
        end as PPN_REALISASI,
        AWI_NOAWB as NOAWB,
        to_char(AWI_CREATE_DT,'dd-MON-yyyy hh24:mi:ss') as TGLAWB
        
      FROM igrbgr.tbtr_obi_h obih
      LEFT JOIN igrbgr.tbtr_obi_d obid on trunc(obih.obi_tgltrans)=trunc(obid.obi_tgltrans) and obih.obi_notrans=obid.obi_notrans
      LEFT JOIN igrbgr.tbtr_awb_ipp on awi_nopb=obih.obi_nopb
      left join tbtr_jualdetail
        ON trjd_cus_kodemember         =obih.obi_kdmember
        AND trjd_cashierstation        =obih.obi_kdstation     
        AND TRUNC(trjd_transactiondate)=TRUNC(obih.obi_tglstruk)     
        AND trjd_transactionno         =obih.obi_nostruk     
        AND trjd_create_by             =obih.obi_modifyby  
        AND trjd_prdcd                 =obid.obi_prdcd
      LEFT JOIN tbmaster_prodmast on prd_prdcd=obi_prdcd
      LEFT JOIN tbmaster_customer on obih.obi_kdmember=cus_kodemember
      WHERE obih.obi_nopb='$nopb' "
      );
      $detail = $detail->getResultArray();

      $data = [
        'title' => 'Detail PB KLIK',
        'detail' => $detail,
        'nopb' => $nopb,
        'judul' => $judul,
      ];

      return view('store/detailpbklik', $data);
    }
   
    public function promoperrak()
    {
      $dbProd = db_connect('production');
      $koderak = $this->request->getVar('rak');
      $jenis = $this->request->getVar('jenis');
      $cbrak = $giftrak = [];

      $rak = $dbProd->query(
        "SELECT DISTINCT LKS_KODERAK FROM TBMASTER_LOKASI
        WHERE SUBSTR(LKS_KODERAK,1,1) in ('R','O','I') 
        AND LKS_TIPERAK != 'S'
        ORDER BY LKS_KODERAK ASC"
      );
      $rak = $rak->getResultArray();


      if($koderak!="" && $koderak!="all"){
        $filterrak = "AND RAK = '$koderak'";
      }elseif($koderak=="all"){
        $filterrak = " ";
      }

      if (!empty($this->request->getVar('btn')) && $jenis=="cb") {
        $cbrak = $dbProd->query(
          "SELECT DISTINCT CBD_PRDCD PLU,
          DESK,
          CBD_KODEPROMOSI||'-'||CBH_NAMAPROMOSI PROMO,
          CBH_TGLAWAL TGLAWAL,
          CBH_TGLAKHIR TGLAKHIR,
          RAK||'.'||SUBRAK||'.'||TIPERAK||'.'||SHELVING||'.'||NOURUT LOK
          FROM TBTR_CASHBACK_HDR
          LEFT JOIN (SELECT CBD_PRDCD,CBD_KODEPROMOSI,PRD_DESKRIPSIPANJANG DESK,
                      LKS_KODERAK RAK, LKS_KODESUBRAK SUBRAK, LKS_TIPERAK TIPERAK,
                      LKS_SHELVINGRAK SHELVING,LKS_NOURUT NOURUT FROM TBTR_CASHBACK_DTL
                      LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = CBD_PRDCD
                      LEFT JOIN TBMASTER_LOKASI ON LKS_PRDCD = CBD_PRDCD
                      WHERE SUBSTR(LKS_KODERAK,1,1) in ('R','O','I') AND LKS_TIPERAK != 'S') ON CBH_KODEPROMOSI = CBD_KODEPROMOSI
          WHERE TRUNC(SYSDATE) BETWEEN TRUNC(CBH_TGLAWAL) AND TRUNC(CBH_TGLAKHIR)
          AND CBH_RECORDID IS NULL 
          $filterrak
          ORDER BY LOK ASC"
        );
        $cbrak = $cbrak->getResultArray();
      }elseif(!empty($this->request->getVar('btn')) && $jenis=="gift"){
        $giftrak = $dbProd->query(
          " SELECT DISTINCT GFD_PRDCD PLU,
          DESK,
          GFD_KODEPROMOSI||'-'||GFH_NAMAPROMOSI PROMO,
          GFH_TGLAWAL TGLAWAL,
          GFH_TGLAKHIR TGLAKHIR,
          RAK||'.'||SUBRAK||'.'||TIPERAK||'.'||SHELVING||'.'||NOURUT LOK
          FROM TBTR_GIFT_HDR
          LEFT JOIN (SELECT GFD_PRDCD,GFD_KODEPROMOSI,PRD_DESKRIPSIPANJANG DESK,
                      LKS_KODERAK RAK, LKS_KODESUBRAK SUBRAK, LKS_TIPERAK TIPERAK,
                      LKS_SHELVINGRAK SHELVING,LKS_NOURUT NOURUT FROM TBTR_GIFT_DTL
                      LEFT JOIN TBMASTER_PRODMAST ON PRD_PRDCD = GFD_PRDCD
                      LEFT JOIN TBMASTER_LOKASI ON LKS_PRDCD = GFD_PRDCD
                      WHERE SUBSTR(LKS_KODERAK,1,1) in ('R','O','I') AND LKS_TIPERAK != 'S') ON GFH_KODEPROMOSI = GFD_KODEPROMOSI
          WHERE TRUNC(SYSDATE) BETWEEN TRUNC(GFH_TGLAWAL) AND TRUNC(GFH_TGLAKHIR)
          AND GFH_RECORDID IS NULL 
          $filterrak
          ORDER BY TGLAWAL"
        );
        $giftrak = $giftrak->getResultArray();
      }

      $data = [
        'title' => 'Promo per Rak',
        'rak' => $rak,
        'koderak' => $koderak,
        'cbrak' => $cbrak,
        'giftrak' => $giftrak,
      ];

      return view('store/promoperrak', $data);
    }

}
