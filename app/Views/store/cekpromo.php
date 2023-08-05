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
            <div class="card w-100 mb-3">
                <div class="card-header bg-primary text-light">
                    <h6 class="">Cek Promo</h6>
                </div>
                <div class="card-body text-center align-content-center justify-content-center">
                    <form method="post" action="/store/cekpromo">
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
        </div>
        <div class="col-md-9">
            <!-- Cek Promo MD -->
            <?php if (!empty($promomd)) { ?>
                <div class="card w-100 mb-3">
                    <div class="card-header bg-primary text-light">
                        <h6>Promo MD</h6>
                    </div>
                    <div class="card-body">
                        <table class="table mb-3">
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
                                            <?= $cb['KDPROMO']; ?> - <?= $cb['NAMAPROMO']; ?><br>
                                            <div class="badge rounded bg-warning">
                                                <?= $cb['ALOKASI']; ?>
                                            </div>
                                            <div class="badge rounded bg-info">
                                                <?= $cb['ALKUSED']; ?>
                                            </div>
                                            <div class="badge rounded bg-secondary">
                                                <?= $cb['ALOKASIPLU']; ?>
                                            </div>
                                        </td>
                                        <td><?= number_format($cb['MINSPONSOR']); ?></td>
                                        <td><?= $cb['MINSTRUK']; ?></td>
                                        <td><?= $cb['MAX_STRK_PERHARI']; ?></td>
                                        <td><?= number_format($cb['MAX_RPH_PERHARI']); ?></td>
                                        <td><?= number_format($cb['CBH']); ?></td>
                                        <td><?= number_format($cb['CBD']); ?></td>
                                        <td>
                                            <div class="badge rounded-pill bg-primary"><?= $cb['MB']; ?></div>
                                            <div class="badge rounded-pill bg-danger"><?= $cb['MM']; ?></div>
                                            <div class="badge rounded-pill bg-secondary"><?= $cb['PLT']; ?></div>
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
                            <div class="card-header bg-info">
                                <h6>Simulasi Harga Member Biru</h6>
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
                                <h6>Simulasi Harga Member Merah</h6>
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
                                <h6>Simulasi Harga Member Platinum</h6>
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
            <?php if(!empty($promogift)): ?>
                <div class="card w-100 mb3">
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
                                <?php foreach($promogift as $gift): ?>
                                <tr>
                                    <td><?= $gift['KODE']; ?> - <?= $gift['NAMA_PROMO']; ?></td>
                                    <td><?= $gift['HADIAH']; ?></td>
                                    <td><?= $gift['MIN_PCS']; ?></td>
                                    <td><?= $gift['MIN_RPH']; ?></td>
                                    <td><?= $gift['MIN_SPONSOR']; ?></td>
                                    <td>
                                        <div class="badge bg-primary rounded-pill"><?= $gift['GFA_REGULER']; ?></div>
                                        <div class="badge bg-danger rounded-pill"><?= $gift['GFA_RETAILER']; ?></div>
                                        <div class="badge bg-secondary rounded-pill"><?= $gift['GFA_PLATINUM']; ?></div>
                                    </td>
                                    <td>
                                        <div class="badge bg-success rounded mb-1"><?= $gift['GFH_TGLAWAL']; ?></div>
                                        <div class="badge bg-danger rounded"><?= $gift['GFH_TGLAKHIR']; ?></div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(!empty($promonk) or !empty($promohjk)): ?>
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
        </div>
    </div>
</div>

<?= $this->endSection(); ?>