<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<div class="container-fluid mt-3 mx-1">
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header bg-primary">
                    <p class="fw-bold text-center text-light">Monitoring Klik IGR</p>
                </div>
                <div class="card-body">
                    <form action="monitoringklik" method="get">
                        <p for="" class="fw-bold d-block">PERIODE :</p>
                        <label for="tglawal" class="d-block">Tanggal Awal</label>
                        <input type="date" name="tglawal" id="tglawal" class="w-100 me-2 mb-2" value="<?= old('tglawal') ? old('tglawal') : $now; ?>">
                        <label for="tglakhir" class="d-block">Tanggal Akhir</label>
                        <input type="date" name="tglakhir" id="tglakhir" class="w-100 me-2 mb-3" value="<?= old('tglakhir') ? old('tglakhir') : $now; ?>">
                        <button type="submit" name="btn" value="semua" class="btn btn-primary text-light w-100 mb-1"><i class="fa-solid fa-circle-check me-1"></i>Tampilkan Semua</button>
                        <button type="submit" name="btn" value="proses" class="btn btn-danger text-light w-100 mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i>Masih Proses</button>
                        <button type="submit" name="btn" value="pertanggal" class="btn btn-success text-light w-100 mb-1"><i class="fa-solid fa-list me-1"></i>Tampil Pertanggal</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary-subtle">
                    <p class="fw-bold">MONITORING PROSES KLIK & TMI | Periode : <?= $tglawal." s/d ".$tglakhir; ?> </p>
                </div>
                <div class="card-body">
                    <?php if(!empty($semua)): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>KATEGORI</th>
                                    <th>KETERANGAN</th>
                                    <th>JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($semua as $sm): ?>
                                    <tr>
                                <?php  
                                switch ($sm['ATTRIBUT']) {
                                    case "Corp" : echo "<td class='bg-success-subtle'>"; break;
                                    case "Member Umum" : echo "<td class='bg-info-subtle'>"; break;
                                    case "Member Merah" : echo "<td class='bg-danger-subtle'>"; break;
                                    case "TMI" : echo "<td class='bg-warning-subtle'>"; break;
                                    default : echo "<td>";
                                }
                                ?>
                                <?= $sm['ATTRIBUT']; ?></td>
                                <?php  
                                switch ($sm['ATTRIBUT']) {
                                    case "Corp" : echo "<td class='bg-success-subtle'>"; break;
                                    case "Member Umum" : echo "<td class='bg-info-subtle'>"; break;
                                    case "Member Merah" : echo "<td class='bg-danger-subtle'>"; break;
                                    case "TMI" : echo "<td class='bg-warning-subtle'>"; break;
                                    default : echo "<td>";
                                }
                                ?>
                                <?= $sm['KETERANGAN']; ?></td>
                                <?php  
                                switch ($sm['ATTRIBUT']) {
                                    case "Corp" : echo "<td class='bg-success-subtle'>"; break;
                                    case "Member Umum" : echo "<td class='bg-info-subtle'>"; break;
                                    case "Member Merah" : echo "<td class='bg-danger-subtle'>"; break;
                                    case "TMI" : echo "<td class='bg-warning-subtle'>"; break;
                                    default : echo "<td>";
                                }
                                ?>
                                <?= $sm['JUMLAHPB']; ?></td>
                                    </tr>
                                <?php endforeach;; ?>
                            </tbody>
                        </table>
                    <?php elseif(!empty($proses)): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>KATEGORI</th>
                                    <th>KETERANGAN</th>
                                    <th>JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($proses as $pr): ?>
                                    <tr>
                                <?php  
                                switch ($pr['ATTRIBUT']) {
                                    case "Corp" : echo "<td class='bg-success-subtle'>"; break;
                                    case "Member Umum" : echo "<td class='bg-info-subtle'>"; break;
                                    case "Member Merah" : echo "<td class='bg-danger-subtle'>"; break;
                                    case "TMI" : echo "<td class='bg-warning-subtle'>"; break;
                                    default : echo "<td>";
                                }
                                ?>
                                <?= $pr['ATTRIBUT']; ?></td>
                                <?php  
                                switch ($pr['ATTRIBUT']) {
                                    case "Corp" : echo "<td class='bg-success-subtle'>"; break;
                                    case "Member Umum" : echo "<td class='bg-info-subtle'>"; break;
                                    case "Member Merah" : echo "<td class='bg-danger-subtle'>"; break;
                                    case "TMI" : echo "<td class='bg-warning-subtle'>"; break;
                                    default : echo "<td>";
                                }
                                ?>
                                <?= $pr['KETERANGAN']; ?></td>
                                <?php  
                                switch ($pr['ATTRIBUT']) {
                                    case "Corp" : echo "<td class='bg-success-subtle'>"; break;
                                    case "Member Umum" : echo "<td class='bg-info-subtle'>"; break;
                                    case "Member Merah" : echo "<td class='bg-danger-subtle'>"; break;
                                    case "TMI" : echo "<td class='bg-warning-subtle'>"; break;
                                    default : echo "<td>";
                                }
                                ?>
                                <?= $pr['JUMLAHPB']; ?></td>
                                    </tr>
                                <?php endforeach;; ?>
                            </tbody>
                        </table>
                    <?php elseif(!empty($pertanggal)): ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>TANGGAL</th>
                                    <th>JML PB</th>
                                    <th>SendHH</th>
                                    <th>Picking</th>
                                    <th>Packing</th>
                                    <th>Sales</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pertanggal as $pt): ?>
                                    <tr>
                                        <td><?= $pt['TGLTRANS']; ?></td>
                                        <td><?= $pt['JMLPB']; ?></td>
                                        <?php if($pt['JMLPB']==$pt['SUDAH_SENDHH']): ?>
                                            <td>Ok</td>
                                        <?php else: ?>
                                            <td><?= $pt['SUDAH_SENDHH']."/".$pt['JMLPB']; ?></td>
                                        <?php endif; ?>
                                        <?php if($pt['SUDAH_PICKING']==$pt['SUDAH_SENDHH'] && $pt['SUDAH_PICKING']!=0): ?>
                                            <td>Clear</td>
                                        <?php else: ?>
                                            <td><?= $pt['SUDAH_PICKING']."/".$pt['SUDAH_SENDHH']; ?></td>
                                        <?php endif; ?>
                                        <?php if($pt['SUDAH_PACKING']==$pt['SUDAH_PICKING'] && $pt['SUDAH_PICKING']!=0): ?>
                                            <td>Clear</td>
                                        <?php else: ?>
                                            <td><?= $pt['SUDAH_PACKING']."/".$pt['SUDAH_PICKING']; ?></td>
                                        <?php endif; ?>
                                        <td><?= $pt['SUDAH_STRUK']; ?></td>
                                        <?php if($pt['JMLPB']==$pt['SUDAH_STRUK']): ?>
                                            <td>Selesai</td>
                                        <?php else: ?>
                                            <?php $belumclear = $pt['JMLPB']-$pt['SUDAH_STRUK']; ?>
                                            <td><a href="monitoringklik?tgltrans=<?= $pt['TGLTRANS']; ?>">[<?= $belumclear; ?>] PB BELUM SELESAI</a></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>