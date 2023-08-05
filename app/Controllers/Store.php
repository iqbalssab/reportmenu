<?php

namespace App\Controllers;

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
        $tglHariIni = $this->tglsekarang->toDateString();

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
        WHERE trunc(js_transactiondate)=to_date('$tglHariIni','YYYY-MM-DD')
        ORDER BY status,js_cashierstation,js_cashierid");

        
        

        $koneksiFaktur = $dbProd->query("SELECT alk_tahunpajak, case when alk_used='Y' then 'USED' else 'AVAILABLE' end as STATUS,  
        count(alk_taxnum) as ALOKASI
        from tbtr_alokasitax
        where alk_tahunpajak>=to_char(sysdate,'YY') group by alk_tahunpajak,case when alk_used='Y' then 'USED' else 'AVAILABLE' end
        order by alk_tahunpajak,status");

        $data = [
            'title' => 'Preview Transaksi Kasir',
            'transactions' => $koneksiKasir,
            'members' => $koneksiMember,
            'fakturs' => $koneksiFaktur
        ];

        return view('store/previewkasir', $data);
    }

    public function cekpromo()
    {
        $isiplu = $this->request->getVar('plu');
        $aksi = $this->request->getVar('tombol');
        $pluplusnol = ""; // Inisialisasi $pluplusnol
        $promomd = []; 
        $promocb = $hargamb = $hargamm = $hargaplt = $promogift = $promonk = $promohjk = [];  
    
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
     
        d($promonk);
        d($promohjk);
        $data = [
            'title' => 'Cek Promo',
            'promomd' => $promomd,
            'promocb' => $promocb,
            'hargamb' => $hargamb,
            'hargamm' => $hargamm,
            'hargaplt' => $hargaplt,
            'promogift' => $promogift,
            'promonk' => $promonk,
            'promohjk' => $promohjk
        ];
        redirect()->to('/store/cekpromo')->withInput();
        return view('store/cekpromo', $data);
    }
    

    public function promomd()
    {
        
    }
}
