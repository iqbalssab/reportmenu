<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-3" style="width: 250px;">
            <div class="card w-100 mb-2">
                <div class="card-header text-light mb-1" style=" background-color: #0040ff;">
                    <h6 class="text-center fw-bold">Cek Data Member</h6>
                </div>
                <div class="card-body ">
                    <form method="get" action="/member/cekmember">
                        <?= csrf_field(); ?>
                        <select class="form-select form-select-sm mb-3" name="statuscari" aria-label="Small select example" style="font-size: 17px;">
                            <option value="ksg">Search by</option>
                            <option value="nama">Nama</option>
                            <option value="kode">Kode</option>
                            <option value="ktp">No. KTP</option>
                            <option value="hp">No. HP</option>
                        </select>
                        <input type="text" name="cari" id="cari" class="w-100 mb-4 form-control input-sm" value="<?= old('cari'); ?>" style="font-size: 15px;" required autofocus>
                        <button type="submit" name="tombol" value="btncekmbr" class="text-light btn w-30 mb-2 d-block fw-bold" style=" background-color: #6528F7;">Tampil</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- tabel isi -->
        <div class="col-md-8" style="width: 1080px;">
            <?php if(!empty($member)) {?>
                <div class="card w-100 mb-3">
                    <div class="card-header bg-info text-dark">
                        <h6 class="fw-bold">Hasil Pencarian Member Berdasarkan : <?= old('cari'); ?> **</h6>
                    </div>
                    <div class="card-body">
                        <table class="table mb-3" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Nama Member</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">No. Telp / HP</th>
                                    <th class="text-center">Jns. Member</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($member as $mb) : ?>
                                <tr>
                                    <td class="text-center"><?=$mb['KODE']; ?></td>
                                    <td class="text-start"><?=$mb['NAMA_MEMBER']; ?></td>
                                    <td class="text-start"><?=$mb['ALAMAT']; ?></td>
                                    <td class="text-start"><?=$mb['NO_HP']; ?></td>
                                    <td class="text-start">
                                    <?php if($mb['JNS_MBR'] == 'MEMBER MERAH') {?>    
                                            <div class="badge rounded-pill bg-danger"><?=$mb['JNS_MBR']; ?></div>
                                        <?php } else if($mb['JNS_MBR'] == 'MEMBER BIRU') {?>
                                            <div class="badge rounded-pill bg-primary"><?=$mb['JNS_MBR']; ?></div>
                                        <?php } ?>
                                    </td>
                                    <td class="text-start">
                                        <?php if($mb['STATUS'] == 'AKTIF') {?>    
                                            <p class="text-primary"><?=$mb['STATUS']; ?></p>
                                        <?php } else if($mb['STATUS'] == 'NON-AKTIF') {?>
                                            <p class="text-danger"><?=$mb['STATUS']; ?></p>
                                        <?php } ?>  
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    
</div>

<?= $this->endSection(); ?>