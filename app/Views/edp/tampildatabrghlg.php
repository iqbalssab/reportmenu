<?php $this->extend('layout/template'); ?>

<?php $this->section('content'); ?>
    
    <?php if(!empty($barang)): ?>
        <div class="container-fluid mt-3">
            <div class="row mb-2">
                <div class="col judul-data">
                    <h5 class="fw-bold">Pencarian Data Barang Tertinggal Bedasarkan :</h5>
                    <h6>Kassa : <?= $kassa; ?></h6>
                    <h6>Tanggal : <?= date('d M Y',strtotime($tanggal)); ?></h6>
                    <h6>Periode Jam : <?= date('G:i',strtotime($awal)); ?> s/d <?= date('G:i',strtotime($akhir)); ?></h6>
                    <br>
                </div>
            </div>
            <table class="table table-bordered table-hover table-sm border-dark table-responsive">
                <thead class="table-success border-dark">
                    <tr>
                        <th class="text-center">TANGGAL</th>
                        <th class="text-center">JAM</th>
                        <th class="text-center">NO_STRUK</th>
                        <th class="text-center">ID_KASIR</th>
                        <th class="text-center">KASSA</th>
                        <th class="text-center">KD_MEMBER</th>
                        <th class="text-center">DESKRIPSI</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">NAMA_MEMBER</th>
                        <th class="text-center">ALAMAT</th>
                        <th class="text-center">NO_HP</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($barang as $brg) : ?>
                    <tr>
                        <td class="text-center"><?=$brg['TANGGAL']; ?></td>
                        <td class="text-center"><?=$brg['JAM']; ?></td>
                        <td class="text-center"><?=$brg['NO_STRUK']; ?></td>
                        <td class="text-center"><?=$brg['ID_KASIR']; ?></td>
                        <td class="text-center"><?=$brg['KASSA']; ?></td>
                        <td class="text-center"><?=$brg['KODE_MEMBER']; ?></td>
                        <td class="text-start"><?=$brg['NAMA_BARANG']; ?></td>
                        <td class="text-end"><?=$brg['QTY']; ?></td>
                        <td class="text-start"><?=$brg['NAMA_MEMBER']; ?></td>
                        <td class="text-start"><?=$brg['ALAMAT']; ?></td>
                        <td class="text-start"><?=$brg['NO_HP']; ?></td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <div class="">
                <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
            </div>
        </div>
    <?php endif; ?>

<?php $this->endSection(); ?>