<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<?php $now = date('Y-m-d'); ?>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <h6 class="text-center text-light fw-bolder">Transaksi Topup Mitra</h6>
                </div>
                <div class="card-body py-2">
                    <form action="transaksimitra" method="get">
                        <label for="tglawal">Tgl Awal :</label>
                        <input class="mb-2 w-100" type="date" name="tglawal" id="tglawal" value="<?= $tglawal ? $tglawal : $now; ?>">
                        <label for="tglawal">Tgl Akhir :</label>
                        <input class="mb-2 w-100" type="date" name="tglakhir" id="tglakhir" value="<?= $tglakhir ? $tglakhir : $now; ?>">
                        <label for="jenismember">Jenis Member</label>
                        
                        <select class="form-select form-select-sm" name="jenismember" aria-label="Small select example">
                            <option value="all" selected>ALL MEMBER</option>
                            <option value="mm">MEMBER MERAH</option>
                            <option value="mb">MEMBER BIRU</option>
                        </select>
                        <label for="kodemember">Kode Member</label>
                        <input type="text" name="kodemember" id="kodemember" class="w-100 mb-3" placeholder="A1234">

                        <button class="mt-1 btn btn-success w-100" name="btn" value="detail" type="submit">Detail TOP UP Mitra</button>
                        <button class="mt-1 btn btn-primary w-100" name="btn" value="akumulasi" type="submit">Akumulasi Per-Member</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <h5 class="text-primary">Data Transaksi Topup Mitra</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($detail)): ?>
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>TGL_TOPUP</th>
                                <th>STATION</th>
                                <th>KASIR</th>
                                <th>KODE_MEMBER</th>
                                <th>NAMA_MEMBER</th>
                                <th>JNS_MEMBER</th>
                                <th>NO.HP</th>
                                <th>NILAI_TOPUP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $totalNilai = 0; ?>
                            <?php foreach($detail as $dtl): ?>
                                <tr>
                                    <td><?= $dtl['TANGGAL']; ?></td>
                                    <td><?= $dtl['STATION']; ?></td>
                                    <td><?= $dtl['KASIR']; ?></td>
                                    <td><?= $dtl['DPP_KODEMEMBER']; ?></td>
                                    <td><?= $dtl['CUS_NAMAMEMBER']; ?></td>
                                    <td><?= $dtl['JENIS_MEMBER']; ?></td>
                                    <td><?= $dtl['DPP_NOHP']; ?></td>
                                    <td><?= number_format($dtl['DPP_JUMLAHDEPOSIT']); ?></td>
                                </tr>
                                <?php $totalNilai += $dtl['DPP_JUMLAHDEPOSIT']; ?>
                            <?php endforeach; ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b>Total Nilai</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?= number_format($totalNilai); ?></td>
                                </tr>
                        </tbody>
                    </table>
                    <?php elseif(!empty($akumulasi)): ?>
                        <table class="table table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th>KODE_MEMBER</th>
                                    <th>NAMA_MEMBER</th>
                                    <th>JNS_MEMBER</th>
                                    <th>NO.HP</th>
                                    <th>NILAI_TOPUP</th>
                                    <th>JML_TOPUP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $totalNilai = 0; ?>
                                <?php foreach($akumulasi as $akm): ?>
                                    <tr>
                                        <td><?= $akm['KODEMEMBER']; ?></td>
                                        <td><?= $akm['NAMAMEMBER']; ?></td>
                                        <td><?= $akm['NOHP']; ?></td>
                                        <td><?= $akm['JENIS_MEMBER']; ?></td>
                                        <td><?= $akm['NILAITOPUP']; ?></td>
                                        <td><?= $akm['JUMLAHTOPUP']; ?></td>
                                    </tr>
                                    <?php $totalNilai += $akm['NILAITOPUP']; ?>
                                <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Total Nilai</b></td>
                                        <td><?= number_format($totalNilai); ?></td>
                                        <td></td>
                                    </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>