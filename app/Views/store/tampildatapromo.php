
<?php $this->extend('layout/template'); ?>

<?php $this->section('content'); ?>

<!-- Tampil Data Cashback -->
    <?php if(!empty($cashback)): ?>
        <div class="container-fluid mt-3">
            <div class="row mb-2">
                <div class="col judul-promo">
                    <h4>Data Promo <?= $jenis; ?></h4>
                    <h5 class="d-inline">Pilihan Data Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $status; ?></span><br>
                    <h5 class="d-inline">Kode Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $kodepromo; ?></span><br>
                    <h5 class="d-inline">Tanggal Akhir Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $tglakhir; ?></span>
                </div>
            </div>
            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Nama Promo</th>
                        <th>Tanggal Awal</th>
                        <th>Tanggal Akhir</th>
                        <th>Jumlah ALokasi</th>
                        <th>Alokasi Keluar</th>
                        <th>Sisa Alokasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach($cashback as $cb): ?>
                    <tr class="">
                        <td><?= $i++; ?></td>
                        <td><?= $cb['CBH_KODEPROMOSI']; ?></td>
                        <td><?= $cb['CBH_NAMAPROMOSI']; ?></td>
                        <td><?= $cb['CBH_TGLAWAL']; ?></td>
                        <td><?= $cb['CBH_TGLAKHIR']; ?></td>
                        <td><?= $cb['CBA_ALOKASIJUMLAH']; ?></td>
                        <td><?= $cb['ALOKASIUSED'] ? $cb['ALOKASIUSED'] : 0; ?></td>
                        <?php if($cb['CBA_ALOKASIJUMLAH'] > 0): ?>
                        <?php $alokasiJumlah = $cb['CBA_ALOKASIJUMLAH']; ?>
                        <?php $alokasiUsed = $cb['ALOKASIUSED']; ?>
                        <?php $alokasiSisa = $alokasiJumlah - $alokasiUsed; ?>
                        <?php $alokasipersen = $alokasiSisa/$alokasiJumlah * 100; ?>
                        <?php if($alokasipersen>=50): ?>
                            <td>
                                <span class="badge rounded-pill text-bg-success">
                                    <?= number_format($alokasiSisa); ?>
                                </span>
                            </td>
                            <?php elseif($alokasipersen>=10): ?>
                                <td>
                                    <span class="badge rounded-pill text-bg-warning">
                                        <?= number_format($alokasiSisa); ?>
                                    </span>
                                </td>
                                <?php else: ?>
                                <td>
                                    <span class="badge rounded-pill text-bg-danger">
                                        <?= number_format($alokasiSisa); ?>
                                    </span>
                                </td>
                        <?php endif; ?> 
                        <?php else: ?>
                            <td>
                                <span class="badge rounded-pill text-bg-primary">
                                    UNLIMITED
                                </span>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<!-- Tampil Data Cashback per PLU -->
    <?php if(!empty($cbperplu)): ?>
        <div class="container-fluid mt-3" style="overflow-x: scroll;">
        <div class="judul-promo">
                    <h4>Data Promo <?= $jenis; ?></h4>
                    <h5 class="d-inline">Pilihan Data Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $status; ?></span><br>
                    <h5 class="d-inline">Kode Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $kodepromo; ?></span><br>
                    <h5 class="d-inline">Tanggal Akhir Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $tglakhir; ?></span>
        </div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>DIV</th>
                    <th>Dep</th>
                    <th>Kat</th>
                    <th>PLU</th>
                    <th>Deskripsi</th>
                    <th>Unit</th>
                    <th>Frac</th>
                    <th>Tag</th>
                    <th>KdPromo</th>
                    <th>NamaPromosi</th>
                    <th>TanggalAwal</th>
                    <th>TanggalAkhir</th>
                    <th>MinSponsor</th>
                    <th>Nilai_CB</th>
                    <th>Nilai_CB_GAB</th>
                    <th>RedeemPoint</th>
                    <th>MinStruk</th>
                    <th>MaxStruk</th>
                    <th>MaxRph</th>
                    <th>Alokasi</th>
                    <th>AlokasiKeluar</th>
                    <th>SisaAlokasi</th>
                    <th>JenisMember</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cbperplu as $cbplu): ?>
                <tr>
                    <td><?= $cbplu['DIV']; ?></td>
                    <td><?= $cbplu['DEP']; ?></td>
                    <td><?= $cbplu['KAT']; ?></td>
                    <td><?= $cbplu['PLU']; ?></td>
                    <td><?= $cbplu['DESKRIPSI']; ?></td>
                    <td><?= $cbplu['UNIT']; ?></td>
                    <td><?= $cbplu['FRAC']; ?></td>
                    <td><?= $cbplu['TAG']; ?></td>
                    <td><?= $cbplu['KDPROMOSI']; ?></td>
                    <td><?= $cbplu['NAMAPROMOSI']; ?></td>
                    <td><?= $cbplu['TGLAWAL']; ?></td>
                    <td><?= $cbplu['TGLAKHIR']; ?></td>
                    <td><?= number_format($cbplu['MIN_SPONSOR'],0,'.',','); ?></td>
                    <td><?= number_format($cbplu['NILAI_CB'],0,'.',','); ?></td>
                    <td><?= number_format($cbplu['NILAI_CB_GAB'],0,'.',','); ?></td>
                    <td><?= $cbplu['REDEEM_POINT']; ?></td>
                    <td><?= $cbplu['MIN_STRUK']; ?></td>
                    <td><?= $cbplu['MAX_STRUK']; ?></td>
                    <td><?= number_format($cbplu['MAX_RPH_PERHARI'],0,'.',','); ?></td>
                    <td><?= $cbplu['ALOKASI_JUMLAH']; ?></td>
                    <td><?= $cbplu['ALOKASI_KELUAR']; ?></td>
                    <td><?= $cbplu['SISA_ALOKASI']; ?></td>
                    <td>
                        <?= $cbplu['BIRU']; ?>
                        <?= $cbplu['BIRUPLUS']; ?>
                        <?= $cbplu['FREEPASS']; ?>
                        <?= $cbplu['RETAILER']; ?>
                        <?= $cbplu['SILVER']; ?>
                        <?= $cbplu['GOLD1']; ?>
                        <?= $cbplu['GOLD2']; ?>
                        <?= $cbplu['GOLD3']; ?>
                        <?= $cbplu['PLATINUM']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
<!-- Perolehan Promo Cashback -->
    <?php if(!empty($perolehancb)): ?>
        <div class="container-fluid mt-3">
        <div class="judul-promo">
                <h4>Data Promo <?= $jenis; ?></h4>
                <h5 class="d-inline">Pilihan Data Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $status; ?></span><br>
                <h5 class="d-inline">Kode Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $kodepromo; ?></span><br>
                <h5 class="d-inline">Tanggal Akhir Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $tglakhir; ?></span>
        </div>
        <table class="table table-responsive table-bordered">
            <thead>
                <tr>
                    <th class="bg-primary text-light">No.</th>
                    <th class="bg-primary text-light">KdPromo</th>
                    <th class="bg-primary text-light">NamaPromo</th>
                    <th class="bg-primary text-light">KdMember</th>
                    <th class="bg-primary text-light">NamaMember</th>
                    <th class="bg-primary text-light">TglTransaksi</th>
                    <th class="bg-primary text-light">NoStruk</th>
                    <th class="bg-primary text-light">Qty_CB</th>
                    <th class="bg-primary text-light">Nilai_CB</th>
                </tr>
            </thead>
            <tbody>
                <?= $i = 1; ?>
                <?php foreach($perolehancb as $olehcb): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $olehcb['CBH_KODEPROMOSI']; ?></td>
                    <td><?= $olehcb['CBH_NAMAPROMOSI']; ?></td>
                    <td><?= $olehcb['CUS_KODEMEMBER']; ?></td>
                    <td><?= $olehcb['CUS_NAMAMEMBER']; ?></td>
                    <td><?= $olehcb['TGL_TRANS']; ?></td>
                    <td><?= $olehcb['NOSTRUK']; ?></td>
                    <td><?= number_format($olehcb['KELIPATAN'],0,',','.'); ?></td>
                    <td><?= number_format($olehcb['CASHBACK'],0,',','.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>

<!-- Data Promo Gift -->
    <?php if(!empty($gift)): ?>
        <div class="container-fluid mt-3">
        <div class="judul-promo">
                <h4>Data <?= $jenis; ?></h4>
                <h5 class="d-inline">Pilihan Data Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $status; ?></span><br>
                <h5 class="d-inline">Kode Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $kodepromo; ?></span><br>
                <h5 class="d-inline">Tanggal Akhir Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $tglakhir; ?></span>
        </div>
        <table class="table table-bordered table-sm table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode</th>
                    <th>Nama Promo</th>
                    <th>Tanggal Awal</th>
                    <th>Tanggal Akhir</th>
                    <th>Jumlah Alokasi</th>
                    <th>Alokasi Keluar</th>
                    <th>Sisa Alokasi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach($gift as $gf): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $gf['GFH_KODEPROMOSI']; ?></td>
                    <td><?= $gf['GFH_NAMAPROMOSI']; ?></td>
                    <td><?= $gf['GFH_TGLAWAL']; ?></td>
                    <td><?= $gf['GFH_TGLAKHIR']; ?></td>
                    <td><?= number_format($gf['GFA_ALOKASIJUMLAH'],0,',','.'); ?></td>
                    <td><?= number_format($gf['ALOKASIUSED'],0,',','.'); ?></td>
                    <!-- hitung alokasi sisa -->
                    <?php if($gf['GFA_ALOKASIJUMLAH'] > 0): 
                        $alokasiJumlah = $gf['GFA_ALOKASIJUMLAH'];
                        $alokasiUsed = $gf['ALOKASIUSED'];
                        $alokasiSisa = $gf['GFA_ALOKASIJUMLAH'] - $gf['ALOKASIUSED'];
                        $alokasiPersen = $alokasiSisa/$alokasiJumlah * 100;
                    ?>
                        <?php if($alokasiPersen>=50): ?>
                        <td>
                            <span class="badge bg-success"><?= number_format($alokasiSisa,0,',','.'); ?></span>
                        </td>
                        <?php elseif($alokasiPersen >= 10): ?>
                        <td>
                            <span class="badge bg-warning"><?= number_format($alokasiSisa,0,',','.'); ?></span>
                        </td>
                        <?php else: ?>
                        <td>
                            <span class="badge bg-danger"><?= number_format($alokasiSisa,0,',','.'); ?></span>
                        </td>
                        <?php endif; ?>
                    <?php else: ?>
                        <td>
                            <span class="badge bg-primary">UNLIMITED</span>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>

<!-- Data Promo Gift Per PLU -->
    <?php if(!empty($giftperplu)): ?>
        <div class="container-fluid mt-3" style="overflow-x: scroll;">
        <div class="judul-promo">
                <h4>Data <?= $jenis; ?></h4>
                <h5 class="d-inline">Pilihan Data Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $status; ?></span><br>
                <h5 class="d-inline">Kode Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $kodepromo; ?></span><br>
                <h5 class="d-inline">Tanggal Akhir Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $tglakhir; ?></span>
        </div>
        <table class="table table-sm table-hover table-bordered">
            <thead>
                <tr>
                    <th>Div</th>
                    <th>Dep</th>
                    <th>Kat</th>
                    <th>PLU</th>
                    <th>Deskripsi</th>
                    <th>Unit</th>
                    <th>Frac</th>
                    <th>Tag</th>
                    <th>KdPromo</th>
                    <th>NamaPromosi</th>
                    <th>JnsPromo</th>
                    <th>All_Item</th>
                    <th>TanggalAwal</th>
                    <th>TanggalAkhir</th>
                    <th>MinBeli</th>
                    <th>MinTotBelanja</th>
                    <th>MinTotSponsor</th>
                    <th>MaxJmlHari</th>
                    <th>MaxFreqHari</th>
                    <th>MaxJMlEvent</th>
                    <th>MaxFreqEvent</th>
                    <th>DivHadiah</th>
                    <th>PLU_Hadiah</th>
                    <th>Hadiah_Rph</th>
                    <th>JumlahHdh</th>
                    <th>AlokasiHdh</th>
                    <th>HadiahKeluar</th>
                    <th>SisaAlokasi</th>
                    <th>JenisMember</th>
                    <th>SKP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($giftperplu as $gpu): ?>
                <tr>
                    <td><?= $gpu['DIV']; ?></td>
                    <td><?= $gpu['DEP']; ?></td>
                    <td><?= $gpu['KAT']; ?></td>
                    <td><?= $gpu['PLU']; ?></td>
                    <td><?= $gpu['DESKRIPSI']; ?></td>
                    <td><?= $gpu['UNIT']; ?></td>
                    <td><?= $gpu['FRAC']; ?></td>
                    <td><?= $gpu['TAG']; ?></td>
                    <td><?= $gpu['KODE_PROMOSI']; ?></td>
                    <td><?= $gpu['NAMA_PROMOSI']; ?></td>
                    <td><?= $gpu['JENIS_PROMOSI']; ?></td>
                    <td><?= $gpu['ALL_ITEM']; ?></td>
                    <td><?= $gpu['TANGGAL_AWAL']; ?></td>
                    <td><?= $gpu['TANGGAL_AKHIR']; ?></td>
                    <td><?= $gpu['MINBELI']; ?></td>
                    <td><?= number_format($gpu['MIN_TOTAL_BELANJA'],0,',','.'); ?></td>
                    <td><?= number_format($gpu['MIN_TOTAL_SPONSOR'],0,',','.'); ?></td>
                    <td><?= $gpu['MAX_JUMLAH_HARI']; ?></td>
                    <td><?= $gpu['MAX_FREQ_HARI']; ?></td>
                    <td><?= $gpu['MAX_JUMLAH_EVENT']; ?></td>
                    <td><?= $gpu['MAX_FREQ_EVENT']; ?></td>
                    <td><?= $gpu['DIV_HADIAH']; ?></td>
                    <td><?= $gpu['PLU_HADIAH']; ?></td>
                    <td><?= number_format($gpu['HADIAH_RUPIAH'],0,',','.'); ?></td>
                    <td><?= number_format($gpu['JUMLAH_HADIAH'],0,',','.'); ?></td>
                    <td><?= number_format($gpu['ALOKASI_HADIAH'],0,',','.'); ?></td>
                    <td><?= number_format($gpu['ALOKASIUSED'],0,',','.'); ?></td>
                    <td><?= $gpu['SISA_ALOKASI']; ?></td>
                    <td>
                        <?= $gpu['BIRU']; ?>
                        <?= $gpu['BIRUPLUS']; ?>
                        <?= $gpu['FREEPASS']; ?>
                        <?= $gpu['RETAILER']; ?>
                        <?= $gpu['SILVER']; ?>
                        <?= $gpu['GOLD1']; ?>
                        <?= $gpu['GOLD2']; ?>
                        <?= $gpu['GOLD3']; ?>
                        <?= $gpu['PLATINUM']; ?>
                    </td>
                    <td>SKP <?= $gpu['KODEPERJANJIAN']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>

<!-- Data Perolehan Promo Gift -->
    <?php if(!empty($perolehangift)): ?>
        <div class="container-fluid mt-3">
        <div class="judul-promo">
                <h4>Data <?= $jenis; ?></h4>
                <h5 class="d-inline">Pilihan Data Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $status; ?></span><br>
                <h5 class="d-inline">Kode Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $kodepromo; ?></span><br>
                <h5 class="d-inline">Tanggal Akhir Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $tglakhir; ?></span>
        </div>
        <table class="table table-bordered table-sm table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>KodePromo</th>
                    <th>KetHadiah</th>
                    <th>KodeMember</th>
                    <th>NamaMember</th>
                    <th>TglTransaksi</th>
                    <th>NoStruk</th>
                    <th>JmlHadiah</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; 
                    $totaljmlhadiah = 0;
                ?>
                <?php foreach($perolehangift as $olehgf): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $olehgf['KD_PROMOSI']; ?></td>
                    <td><?= $olehgf['KET_HADIAH']; ?></td>
                    <td><?= $olehgf['KD_MEMBER']; ?></td>
                    <td><?= $olehgf['CUS_NAMAMEMBER']; ?></td>
                    <td><?= $olehgf['TGL_TRANS']; ?></td>
                    <td><?= $olehgf['NOSTRUK']; ?></td>
                    <td><?= $olehgf['JMLH_HADIAH']; ?></td>
                </tr>
                <?php $totaljmlhadiah = $olehgf['JMLH_HADIAH']; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><b>Total Jumlah Hadiah</b></td>
                    <td></td>
                    <td><?= $totaljmlhadiah; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>

<!-- Data Promo Instore -->
    <?php if(!empty($instore)): ?>
        <div class="container-fluid mt-3">
        <div class="judul-promo">
                <h4>Data <?= $jenis; ?></h4>
                <h5 class="d-inline">Pilihan Data Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $status; ?></span><br>
                <h5 class="d-inline">Kode Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $kodepromo; ?></span><br>
                <h5 class="d-inline">Tanggal Akhir Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $tglakhir; ?></span>
        </div>
        <table class="table table-bordered table-hover table-sm mt-3">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode</th>
                    <th>Nama Promo</th>
                    <th>Keterangan</th>
                    <th>Tanggal Awal</th>
                    <th>Tanggal Akhir</th>
                    <th>Jumlah Alokasi</th>
                    <th>Alokasi Keluar</th>
                    <th>Sisa Alokasi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach($instore as $is): ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $is['ISH_KODEPROMOSI']; ?></td>
                    <td><?= $is['ISH_NAMAPROMOSI']; ?></td>
                    <td><?= $is['ISH_KETERANGAN']; ?></td>
                    <td><?= $is['ISH_TGLAWAL']; ?></td>
                    <td><?= $is['ISH_TGLAKHIR']; ?></td>
                    <td><?= $is['ISH_QTYALOKASI']; ?></td>
                    <td><?= $is['ALOKASIUSED']; ?></td>
                    <?php if($is['ISH_QTYALOKASI'] > 0): ?>
                    <?php 
                            $alokasiJumlah = $is['ISH_QTYALOKASI'];
                            $alokasiUsed = $is['ALOKASIUSED'];
                            $alokasiSisa = $alokasiJumlah - $alokasiUsed;
                            $alokasiPersen = $alokasiSisa/$alokasiJumlah*100; 
                    ?>
                        <?php if($alokasiPersen >= 50): ?>
                            <td>
                                <span class="badge bg-success"><?= number_format($alokasiSisa,0,',','.'); ?></span>
                            </td>
                        <?php elseif($alokasiPersen >= 10): ?>
                            <td>
                                <span class="badge bg-warning"><?= number_format($alokasiSisa,0,',','.'); ?></span>
                            </td>
                        <?php else: ?>
                            <td>
                                <span class="badge bg-danger"><?= number_format($alokasiSisa,0,',','.'); ?></span>
                            </td>
                        <?php endif; ?>
                    <?php else: ?>
                            <td>
                                <span class="badge bg-primary">UNLIMITED</span>
                            </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
<!-- Data Promo Instore per PLU -->
    <?php if(!empty($instoreperplu)): ?>
        <div class="container-fluid mt-3" style="overflow-x: scroll;">
        <div class="judul-promo">
                <h4>Data <?= $jenis; ?></h4>
                <h5 class="d-inline">Pilihan Data Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $status; ?></span><br>
                <h5 class="d-inline">Kode Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $kodepromo; ?></span><br>
                <h5 class="d-inline">Tanggal Akhir Promo :</h5><span class=" d-inline fw-bold fs-6 fst-italic"><?= $tglakhir; ?></span>
        </div>
        <table class="table table-bordered table-hover table-sm mt-3">
            <thead>
                <tr>
                    <th>DIV</th>
                    <th>DEP</th>
                    <th>Kat</th>
                    <th>PLU</th>
                    <th>Deskripsi</th>
                    <th>Unit</th>
                    <th>Frac</th>
                    <th>Tag</th>
                    <th>KdPromo</th>
                    <th>NamaPromosi</th>
                    <th>JnsPromo</th>
                    <th>TanggalAwal</th>
                    <th>TanggalAkhir</th>
                    <th>MinBeli</th>
                    <th>MinTotBelanja</th>
                    <th>MinTotSponsor</th>
                    <th>MaxJMlEvent</th>
                    <th>MaxFreqEvent</th>
                    <th>DIV_Hadiah</th>
                    <th>PLU_Hadiah</th>
                    <th>JumlahHdh</th>
                    <th>AlokasiHdh</th>
                    <th>HadiahKeluar</th>
                    <th>SisaAlokasi</th>
                    <th>JenisMember</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($instoreperplu as $isplu): ?>
                <tr>
                    <td><?= $isplu['DIV']; ?></td>
                    <td><?= $isplu['DEP']; ?></td>
                    <td><?= $isplu['KAT']; ?></td>
                    <td><?= $isplu['PLU']; ?></td>
                    <td><?= $isplu['DESKRIPSI']; ?></td>
                    <td><?= $isplu['UNIT']; ?></td>
                    <td><?= $isplu['FRAC']; ?></td>
                    <td><?= $isplu['TAG']; ?></td>
                    <td><?= $isplu['KODE_PROMOSI']; ?></td>
                    <td><?= $isplu['NAMA_PROMOSI']; ?></td>
                    <td><?= $isplu['JENIS_PROMOSI']; ?></td>
                    <td><?= $isplu['TANGGAL_AWAL']; ?></td>
                    <td><?= $isplu['TANGGAL_AKHIR']; ?></td>
                    <td><?= $isplu['MINBELI']; ?></td>
                    <td><?= $isplu['MIN_TOTAL_BELANJA']; ?></td>
                    <td><?= $isplu['MIN_TOTAL_SPONSOR']; ?></td>
                    <td><?= $isplu['MAX_JUMLAH_EVENT']; ?></td>
                    <td><?= $isplu['MAX_FREQ_EVENT']; ?></td>
                    <td><?= $isplu['DIV_HADIAH']; ?></td>
                    <td><?= $isplu['PLU_HADIAH']; ?></td>
                    <td><?= $isplu['JUMLAH_HADIAH']; ?></td>
                    <td><?= $isplu['ALOKASI_HADIAH']; ?></td>
                    <td><?= $isplu['ALOKASIUSED']; ?></td>
                    <?php $sisaalokasi = 0;
                      if($isplu['ALOKASI_HADIAH'] == 0){
                        $sisaalokasi = 999999;
                        }else {
                        $sisaalokasi = $isplu['ALOKASI_HADIAH'] - $isplu['ALOKASIUSED'];
                        }
                    ?>
                    <td><?= $sisaalokasi; ?></td>
                    <td>
                        <?= $isplu['BIRU']; ?>
                        <?= $isplu['BIRUPLUS']; ?>
                        <?= $isplu['FREEPASS']; ?>
                        <?= $isplu['RETAILER']; ?>
                        <?= $isplu['SILVER']; ?>
                        <?= $isplu['GOLD1']; ?>
                        <?= $isplu['GOLD2']; ?>
                        <?= $isplu['GOLD3']; ?>
                        <?= $isplu['PLATINUM']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>

    <?php $this->endSection(); ?>