<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<?php $now = date('Y-m-d'); ?>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="text-light text-center fw-bolder">History Transaksi Klik</h6>
                </div>
                <div class="card-body py-2">
                    <form action="transaksiklik" method="get">
                        <p class="mb-1 fw-bold">PERIODE</p>
                        <label for="tglawal">Tanggal Awal</label>
                        <input type="date" name="tglawal" id="tglawal" class="mb-2 w-100" value="<?= $tglawal ? $tglawal : $now; ?>">
                        <label for="tglakhir">Tanggal Akhir</label>
                        <input type="date" name="tglakhir" id="tglakhir" class="mb-2 w-100" value="<?= $tglakhir ? $tglakhir : $now; ?>">

                        <button type="submit" class="btn btn-primary mt-2 w-100"><i class="fa-solid fa-eye"></i> TAMPIL</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <h5>Transaksi Klik</h5>
                </div>
                <div class="card-body">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tgl_Struk</th>
                                <th>NoStruk</th>
                                <th>Member</th>
                                <th>Nilai</th>
                                <th>Metode Bayar</th>
                                <th>Keterangan PB</th>
                                <th>TXT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach($klik as $k): ?>
                                <?php $metodebayar = $k['TUNAI']." ".$k['KREDIT'].$k['DC']." ".$k['CC1']." ".$k['CC2']." ".$k['ISAKU']." ".$k['VOUCHER']." ".$k['KR_USAHA']." ".$k['TRANSFER']." ".$k['TIPEBAYAR']; ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $k['TGL_STRUK']; ?></td>
                                    <td><?= $k['NO_STRUK']; ?></td>
                                    <td><?= $k['KDMEMBER']; ?> - <?= $k['NAMAMEMBER']; ?></td>
                                    <td><?= $k['TRANSACTIONAMT']; ?></td>
                                    <td><?= $metodebayar; ?></td>
                                    <td><?= $k['TIPE']; ?> | <?= $k['NO_PB']; ?></td>
                                    <?php if($k['TIPE']=="WebMM" || $k['TIPE']=="TMI"): ?>
                                    <td><button class="btn btn-danger btn-sm" disabled="disabled"><i class="fa-solid fa-x"></i></button></td>
                                    <?php else: ?>
                                    <td><a href="/struk" class="btn btn-primary btn-sm" target="_blank"><i class="fa-regular fa-folder"></i></a></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>