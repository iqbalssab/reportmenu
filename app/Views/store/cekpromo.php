<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid mt-3">
    <?php if (session()->getFlashdata('Error')) : ?>
        <div class="alert alert-danger" role="alert">
            <?= session()->getFlashdata('Error'); ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-3">
            <!-- Form Cari berdasarkan PLU -->
            <div class="card w-100 mb-3">
                <div class="card-header bg-primary text-light">
                    <h6 class="">Cek Promo</h6>
                </div>
                <div class="card-body text-center align-content-center justify-content-center">
                    <form method="get" action="cekpromo">
                        <?= csrf_field(); ?>
                        <label class="" for="plu">Input PLU :</label><br>
                        <input type="text" name="plu" id="plu" class="text-center mb-3" value="<?= old('plu'); ?>" required autofocus>
                        <br>
                        <button type="submit" name="tombol" value="btnpromomd" class="btn btn-primary w-100 mb-2 d-block">Cek Promo MD</button>
                        <button type="submit" name="tombol" value="btnpromocb" class="btn btn-success w-100 mb-2 d-block">Cek Promo Cashback</button>
                        <button type="submit" name="tombol" value="btnpromogift" class="btn btn-warning mb-2 w-100 d-block">Cek Promo Gift</button>
                        <button type="submit" name="tombol" value="btnnk" class="btn btn-info w-100 d-block">Cek NK/HJK</button>
                    </form>
                </div>
            </div>
            <!-- Form Cari Berdasarkan Deskripsi -->
            <div class="card w-100 mb-2 mt-2">
                <div class="card-header bg-dark-subtle">
                    <h6 class="">Cari Produk</h6>
                </div>
                <div class="card-body">
                    <form action="cekpromo" method="get">
                        <?= csrf_field(); ?>
                        <label for="desc1">Deskripsi 1 :</label>
                        <input type="text" name="desc1" id="desc1" class="w-100 d-block">
                        <label for="desc2">Deskripsi 2 :</label>
                        <input type="text" name="desc2" id="desc2" class="w-100 d-block">

                        <button type="submit" name="cari" class="btn btn-dark mt-2">Cari</button>
                    </form>
                </div> 
            </div>
        </div>
        <div class="col-md-9">
            <!-- Cek Promo MD -->
            <?php if (!empty($promomd)) { ?>
                <div class="card w-100 mb-3">
                    <div class="card-header bg-primary text-light">
                        <h6>Promo MD</h6>
                    </div>
                    <div class="card-body overflow-auto">
                        <table class="table table-responsive-sm mb-3">
                            <thead>
                                <tr>
                                    <th>PLU</th>
                                    <th>Deskripsi</th>
                                    <th>Frac</th>
                                    <th>Tag IGR</th>
                                    <th>Tag OMI</th>
                                    <th>Act</th>
                                    <th>MinJ</th>
                                    <th>Acost</th>
                                    <th>H.Normal</th>
                                    <th>H.Promo</th>
                                    <th>Periode</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($promomd as $md) : ?>
                                    <tr>
                                        <td><?= $md['PLU']; ?></td>
                                        <td><?= $md['DESKRIPSI']; ?></td>
                                        <td><?= $md['FRAC']; ?></td>
                                        <td><?= $md['TAG_IGR']; ?></td>
                                        <td><?= $md['TAG_OMI']; ?></td>
                                        <td><?= $md['ACT']; ?></td>
                                        <td><?= $md['MIN_JUAL']; ?></td>
                                        <td><?= $md['ACOST']; ?></td>
                                        <td><?= $md['H_NORMAL']; ?></td>
                                        <td><?= $md['H_PROMO']; ?></td>
                                        <td>
                                            <div class="badge rounded-pill bg-success text-light">
                                                <?= $md['TGL_AWAL']; ?>
                                            </div>
                                            <div class="badge rounded-pill bg-danger text-light mt-1">
                                                <?= $md['TGL_AKHIR']; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="mb-1">
                            <span class="badge rounded-3 text-bg-success">Setting Alokasi<i class="fa-solid fa-arrow-right ms-1"></i></span>
                            <?php if(!empty($alokasimd)): ?>
                            <?php foreach($alokasimd as $amd): ?>
                            <span class="text-bg-warning badge rounded-pill"><?= $amd['ALK_MEMBER']." : ".$amd['ALK_QTYALOKASI']; ?></span>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <span class="text-bg-danger badge rounded-pill">Tidak Ada Alokasi yg Disetting</span>    
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="badge rounded-3 text-bg-primary">MAX Transaksi<i class="fa-solid fa-arrow-right ms-1"></i></span>
                            <?php if(!empty($maxtrans)): ?>
                                <?php foreach($maxtrans as $mt): ?>
                                    <span class="badge rounded-pill text-bg-warning me-1">REGBIRU : <?= $mt['MTR_QTYREGULERBIRU']; ?></span>
                                    <span class="badge rounded-pill text-bg-warning me-1">REGBIRU+ :<?= $mt['MTR_QTYREGULERBIRUPLUS']; ?></span>
                                    <span class="badge rounded-pill text-bg-warning me-1">RETMERAH : <?= $mt['MTR_QTYRETAILERMERAH']; ?></span>
                                    <span class="badge rounded-pill text-bg-warning me-1">SILVER : <?= $mt['MTR_QTYSILVER']; ?></span>
                                    <span class="badge rounded-pill text-bg-warning me-1">GOLD1 : <?= $mt['MTR_QTYGOLD1']; ?></span>
                                    <span class="badge rounded-pill text-bg-warning me-1">GOLD2 : <?= $mt['MTR_QTYGOLD2']; ?></span>
                                    <span class="badge rounded-pill text-bg-warning me-1">GOLD3 : <?= $mt['MTR_QTYGOLD3']; ?></span>
                                    <span class="badge rounded-pill text-bg-warning me-1">PLATINUM : <?= $mt['MTR_QTYPLATINUM']; ?></span>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-bg-danger badge rounded-pill">Tidak Ada Maksimal Transaksi</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- Cek Promo cashback -->
            <?php if (!empty($promocb)) { ?>
                <div class="card w-100 mb-3">
                    <div class="card-header bg-success text-light">
                        <h6>Cashback</h6>
                    </div>
                    <div class="card-body">
                        <table class="table mb-3">
                            <thead>
                                <tr>
                                    <th>Kode-Nama Promo</th>
                                    <th>Min.Spsr</th>
                                    <th>Min.Strk</th>
                                    <th>Max.Strk</th>
                                    <th>Max.Rph</th>
                                    <th>CB_Gab</th>
                                    <th>CB_Plu</th>
                                    <th>Member</th>
                                    <th>Periode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($promocb as $cb) : ?>
                                    <tr>
                                        <td>
                                           <a href="" title="<?= $cb['MEKANISME']; ?>" class="text-decoration-none"><?= $cb['KDPROMO']; ?></a>  - <?= $cb['NAMAPROMO']; ?><br>
                                            <div class="badge rounded bg-warning text-dark mb-1">
                                               Alokasi : <?= $cb['ALOKASI']; ?>
                                            </div>
                                            <div class="badge rounded bg-primary">
                                               Used :  <?= $cb['ALKUSED']? $cb['ALKUSED'] : 0; ?>
                                            </div>
                                            <div class="badge rounded bg-secondary">
                                                <?php if($cb['ALOKASI']>0): ?>
                                               Sisa : <?= $cb['ALKUSED']; ?>
                                               <?php else: ?>
                                                Unlimited
                                                <?php endif; ?>
                                            </div>
                                            <div class="badge rounded bg-success">
                                                <i class="fa-solid fa-flag me-1"></i>
                                                <?= $cb['TMI'] ? $cb['TMI'] : ''; ?>
                                                <?= $cb['IGR'] ? $cb['IGR'] : ''; ?>
                                                <?= $cb['KLIK'] ? $cb['KLIK'] : ''; ?>
                                                <?= $cb['SPI'] ? $cb['SPI'] : ''; ?>
                                            </div>
                                        </td>
                                        <td><?= number_format($cb['MINSPONSOR']); ?></td>
                                        <td><?= $cb['MINSTRUK']; ?></td>
                                        <td><?= $cb['MAX_STRK_PERHARI']; ?></td>
                                        <td><?= number_format($cb['MAX_RPH_PERHARI']); ?></td>
                                        <td><?= number_format($cb['CBH']); ?></td>
                                        <td><?= number_format($cb['CBD']); ?></td>
                                        <td>
                                            <div title="Member Biru" class="badge rounded-pill bg-primary mb-1"><?= $cb['MB']; ?></div>
                                            <div title="Member Merah" class="badge rounded-pill bg-danger"><?= $cb['MM']; ?></div>
                                            <div title="Member Platinum" class="badge rounded-pill bg-secondary"><?= $cb['PLT']; ?></div>
                                        </td>
                                        <td>
                                            <div class="badge bg-success mb-1"><?= $cb['TGLAWAL']; ?></div>
                                            <div class="badge bg-danger"><?= $cb['TGLAKHIR']; ?></div>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Table Simulasi Cashback -->
                <div class="row">
                    <div class="col-md-4">
                        <!-- Member Biru -->
                        <div class="card w-auto mb-3">
                            <div class="card-header bg-primary">
                                <h6 class="text-light text-center fw-bold">Simulasi Harga Member Biru</h6>
                            </div>
                            <div class="card-body">
                                <table class="table mb-3">
                                    <thead>
                                        <tr>
                                            <th>Satuan</th>
                                            <th>HrgJual</th>
                                            <th>Cback</th>
                                            <th>HrgNett</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($hargamb as $mb) : ?>
                                            <tr>
                                                <td><?= $mb['UNIT']; ?>/<?= $mb['FRAC']; ?></td>
                                                <td><?= $mb['HRGJUAL']; ?></td>
                                                <td><?= $mb['TTLCASHBACK']; ?></td>
                                                <td><?= $mb['HRGNETT']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Member Merah -->
                        <div class="card w-auto mb-3">
                            <div class="card-header bg-danger">
                                <h6 class="text-light text-center fw-bold">Simulasi Harga Member Merah</h6>
                            </div>
                            <div class="card-body">
                                <table class="table mb-3">
                                    <thead>
                                        <tr>
                                            <th>Satuan</th>
                                            <th>HrgJual</th>
                                            <th>Cback</th>
                                            <th>HrgNett</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($hargamm as $mm) : ?>
                                            <tr>
                                                <td><?= $mb['UNIT']; ?>/<?= $mb['FRAC']; ?></td>
                                                <td><?= $mb['HRGJUAL']; ?></td>
                                                <td><?= $mb['TTLCASHBACK']; ?></td>
                                                <td><?= $mb['HRGNETT']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Member Platinum -->
                        <div class="card w-auto mb-3">
                            <div class="card-header bg-secondary">
                                <h6 class="text-light text-center fw-bold">Simulasi Harga Member Platinum</h6>
                            </div>
                            <div class="card-body">
                                <table class="table mb-3">
                                    <thead>
                                        <tr>
                                            <th>Satuan</th>
                                            <th>HrgJual</th>
                                            <th>Cback</th>
                                            <th>HrgNett</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($hargaplt as $plt) : ?>
                                            <tr>
                                                <td><?= $mb['UNIT']; ?>/<?= $mb['FRAC']; ?></td>
                                                <td><?= $mb['HRGJUAL']; ?></td>
                                                <td><?= $mb['TTLCASHBACK']; ?></td>
                                                <td><?= $mb['HRGNETT']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <!-- Cek Promo Gift -->
            
                <div class="card w-100 mb-4">
                    <div class="card-header bg-primary text-light">
                        <h6>Promo Gift</h6>
                    </div>
                    <div class="card-body">
                        <table class="table mb-2">
                            <thead>
                                <tr>
                                    <th>Kode-Nama Promo</th>
                                    <th>Hadiah</th>
                                    <th>Min.PCS</th>
                                    <th>Min.RPH</th>
                                    <th>Min.Sponsor</th>
                                    <th>Member</th>
                                    <th>Periode</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($promogift)): ?>
                                <?php foreach($promogift as $gift): ?>
                                <tr>
                                    <td>
                                        <a href="" title="<?= $gift['MEKANISME']; ?>"><?= $gift['KDPROMO']; ?></a> - <?= $gift['NAMAPROMO']; ?>
                                        <br>
                                        <span class="badge text-bg-primary mt-1">Alokasi : <?= number_format($gift['ALOKASI']); ?></span>
                                        <span class="badge text-bg-warning">Used : <?= number_format($gift['ALKUSED']); ?></span>
                                        <?php if($gift['ALOKASI']>0): ?>
                                            <span class="badge text-bg-secondary">Sisa : <?= number_format($gift['ALOKASI']-$gift['ALKUSED']) ; ?></span>
                                        <?php else: ?>
                                            <span class="badge text-bg-secondary">Unlimited</span>
                                        <?php endif; ?>
                                        <!-- Jika Ada Pembatalan Promo -->
                                        <?php if($gift['RECID']==1): ?>
                                            <span class="badge text-bg-danger">Batal, Silahkan tanya ke MKT</span>
                                        <?php endif; ?>
                                        <!-- Jika Promo Blm AKtif -->
                                        <?php if($gift['STATUS']=="BLMAKTIF"): ?>
                                            <span class="badge text-bg-success">Promo Belum Aktif (Cek Periode)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $gift['KETHADIAH']." - ".$gift['BRGHADIAH']; ?></td>
                                    <td><?= number_format($gift['MINPCS']); ?></td>
                                    <td><?= number_format($gift['MINRPH']); ?></td>
                                    <td><?= number_format($gift['MINSPONSOR']); ?></td>
                                    <td>
                                        <span class="badge bg-primary rounded-pill"><?= $gift['MB']; ?></span>
                                        <span class="badge bg-danger rounded-pill"><?= $gift['MM']; ?></span>
                                        <span class="badge bg-secondary rounded-pill"><?= $gift['PLT']; ?></span>
                                    </td>
                                    <td>
                                        <div class="badge bg-success rounded mb-1"><?= $gift['TGLAWAL']; ?></div>
                                        <div class="badge bg-danger rounded"><?= $gift['TGLAKHIR']; ?></div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header bg-warning-subtle">
                        <h6 class="text-warning">Promo Instore</h6>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kode - Nama Promo</th>
                                    <th>Hadiah</th>
                                    <th>Jml</th>
                                    <th>Min.PCS</th>
                                    <th>Min.RPH</th>
                                    <th>Min.Sponsor</th>
                                    <th>Member</th>
                                    <th>Periode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($promoinstore)): ?>
                                    <?php foreach($promoinstore as $pi): ?>
                                        <tr>
                                            <td><?= $pi['KDPROMO']." - ".$pi['NAMAPROMO']; ?></td>
                                            <td>
                                                <?= $pi['HADIAH']; ?>
                                                <br>
                                                <span class="badge text-bg-primary">Alokasi : <?= $pi['QTYALK']; ?></span>
                                                <span class="badge text-bg-warning">Terpakai : <?= $pi['QTYOUT']; ?></span>
                                                <span class="badge text-bg-secondary">Sisa : <?= $pi['SISAALK']; ?></span>
                                            </td>
                                            <td><?= number_format($pi['JML']); ?></td>
                                            <td><?= number_format($pi['MINPCS']); ?></td>
                                            <td><?= number_format($pi['MINRPH']); ?></td>
                                            <td><?= number_format($pi['MINSPONSOR']); ?></td>
                                            <td>
                                                <span class="badge text-bg-primary"><?= $pi['MB']; ?></span>
                                                <span class="badge text-bg-danger"><?= $pi['MM']; ?></span>
                                                <span class="badge text-bg-secondary"><?= $pi['PLT']; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge rounded text-bg-success d-block mb-1"><?= $pi['TGLAWAL']; ?></span>
                                                <span class="badge rounded text-bg-danger d-block mb-1"><?= $pi['TGLAKHIR']; ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>            
            <!-- Cek Promo NK dan HJK -->
            <?php if(!empty($promonk) || !empty($promohjk)): ?>
                <div class="card w-100 mb3">
                    <div class="card-header bg-info text-dark">
                        <h6>Promo NK</h6>
                    </div>
                    <div class="card-body">
                        <table class="table mb-3">
                            <thead>
                                <tr>
                                    <th>PLU</th>
                                    <th>Kode - Nama Promo</th>
                                    <th>TglAwal</th>
                                    <th>TglAkhir</th>
                                    <th>MaxQty</th>
                                    <th>QtyPakai</th>
                                    <th>TotalDana</th>
                                    <th>Terpakai</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($promonk as $nk): ?>
                                <tr>
                                    <td><?= $nk['PLU']; ?></td>
                                    <td><?= $nk['KDPROMO']; ?> - <?= $nk['NAMAPROMO']; ?></td>
                                    <td><?= $nk['TGLAWAL']; ?></td>
                                    <td><?= $nk['TGLAKHIR']; ?></td>
                                    <td><?= $nk['MAXQTY']; ?></td>
                                    <td><?= $nk['QTYPAKAI']; ?></td>
                                    <td><?= $nk['TOTALDANA']; ?></td>
                                    <td><?= $nk['TOTAL_RPHPAKAI']; ?></td>
                                    <td><?= $nk['SISA_RPHTOTAL']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- HJK -->
                <div class="card w-100 mb-3">
                    <div class="card-header bg-warning text-dark">
                        <h6>Setting HJK</h6>
                    </div>
                    <div class="card-body">
                        <table class="table mb-3">
                            <thead>
                                <tr>
                                    <th>PLU</th>
                                    <th>Deskripsi</th>
                                    <th>Rupiah HJK</th>
                                    <th>TglAwal</th>
                                    <th>TglAkhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($promohjk as $hjk): ?>
                                <tr>
                                    <td><?= $hjk['HGK_PRDCD']; ?></td>
                                    <td><?= $hjk['PRD_DESKRIPSIPANJANG'];?></td>
                                    <td><?= $hjk['HGK_HRGJUAL'];?></td>
                                    <td><?= $hjk['HGK_TGLAWAL']; ?></td>
                                    <td><?= $hjk['HGK_TGLAKHIR']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(!empty($cariproduk)): ?>
                <div class="card w-100 mb-3">
                    <div class="card-header bg-info text-dark">
                        <h6>Hasil Pencarian dengan keyword = <?= $desk1; ?> + <?= $desk2; ?></h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>PLU</th>
                                    <th>Deskripsi</th>
                                    <th>Unit</th>
                                    <th>Tag</th>
                                    <th>H.Jual</th>
                                    <th>Stok</th>
                                    <th>View Promo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; ?>
                                <?php foreach($cariproduk as $prd): ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $prd['PRD_PRDCD'] ?></td>
                                    <td><?= $prd['PRD_DESKRIPSIPANJANG'] ?></td>
                                    <td><?= $prd['PRD_UNIT'] ?></td>
                                    <td><?= $prd['PRD_KODETAG'] ?></td>
                                    <td><?= $prd['PRD_HRGJUAL'] ?></td>
                                    <td><?= $prd['ST_SALDOAKHIR'] ?></td>
                                    <td>
                                        <a href="cekpromo?plu=<?= $prd['PRD_PRDCD']; ?>&tombol=btnpromocb" class="badge rounded bg-success">CB</a>
                                        <a href="cekpromo?plu=<?= $prd['PRD_PRDCD']; ?>&tombol=btnpromogift" class="badge rounded bg-success">GF</a>
                                        <a href="cekpromo?plu=<?= $prd['PRD_PRDCD']; ?>&tombol=btnnk" class="badge rounded bg-warning">NK</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>