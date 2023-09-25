<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row">
        <div class="col-md-12">
            <h2 class="fw-bold">Member MERAH Belum Aktivasi</h2>    
            <table class="table mb-3 table-striped table-bordered table-responsive border-dark" style="font-size: 15px;">
                <thead class="table-success">
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Alamat</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kelurahan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kota</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode Pos</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Mobile / HP</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Telepon Rumah</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php
                        $no = 0;
                        $no++;
                        foreach($mbrmerah as $mbr) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $mbr['CUS_KODEMEMBER']; ?></td>
                            <td class="text-start text-nowrap"><?= $mbr['CUS_NAMAMEMBER']; ?></td>
                            <td class="text-start text-nowrap"><?= $mbr['CUS_ALAMATMEMBER5']; ?></td>
                            <td class="text-start text-nowrap"><?= $mbr['CUS_ALAMATMEMBER8']; ?></td>
                            <td class="text-start text-nowrap"><?= $mbr['CUS_ALAMATMEMBER6']; ?></td>
                            <td class="text-start text-nowrap"><?= $mbr['CUS_ALAMATMEMBER7']; ?></td>
                            <td class="text-center text-nowrap"><?= $mbr['CUS_HPMEMBER']; ?></td>
                            <td class="text-center text-nowrap"><?= $mbr['CUS_TLPMEMBER']; ?></td>
                            <td class="text-center text-nowrap"></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php 	if ($no == 0){ echo '<p>Data tidak ada!</p>'; } ?>
            <br>
            <h2 class="fw-bold">Member BIRU Belum Aktivasi</h2>  
            <table class="table mb-3 table-striped table-bordered table-responsive border-dark" style="font-size: 15px;">
            <thead class="table-success">
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Alamat</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kelurahan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kota</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode Pos</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Mobile / HP</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Telepon Rumah</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php
                        $no = 0;
                        $no++;
                        foreach($mbrbiru as $mbr) :
                    ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $mbr['CUS_KODEMEMBER']; ?></td>
                            <td class="text-start text-nowrap"><?= $mbr['CUS_NAMAMEMBER']; ?></td>
                            <td class="text-start text-nowrap"><?= $mbr['CUS_ALAMATMEMBER5']; ?></td>
                            <td class="text-start text-nowrap"><?= $mbr['CUS_ALAMATMEMBER8']; ?></td>
                            <td class="text-start text-nowrap"><?= $mbr['CUS_ALAMATMEMBER6']; ?></td>
                            <td class="text-start text-nowrap"><?= $mbr['CUS_ALAMATMEMBER7']; ?></td>
                            <td class="text-center text-nowrap"><?= $mbr['CUS_HPMEMBER']; ?></td>
                            <td class="text-center text-nowrap"><?= $mbr['CUS_TLPMEMBER']; ?></td>
                            <td class="text-center text-nowrap"></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php 	if ($no == 0){ echo '<p>Data tidak ada!</p>'; } ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>