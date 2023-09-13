<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-2 overflow-auto">
    <div class="row">
        <div class="col-md-2" style="width: 200px;">
            <div class="card w-100 mb-1">
                <div class="card-header text-light mb-2 text-center" style="background-color: #0040ff;">
                    <h6 class="fw-bold " style="font-size: 15px;">Inquiry Plano</h6>
                </div>
                <div class="card-body">
                    <form class="form" method="post" role="form" action="livecks">
                        <?= csrf_field(); ?>
                        <div class="fw-bold text-center mb-2" style="font-size: 14px;">.:: Input PLU ::.</div>
                        <input type="text" name="inputplu" id="inputplu" class="mb-3 text-center form-control input-sm" style="font-size: 14px;" value="<?= old('inputplu'); ?>" required autofocus>
                        <button type="submit" name="tombol" value="btnbh" class="btn btn-sm w-100 mb-1 d-block text-light fw-bold" style="background-color: #33cc33;" style="font-size: 14px;">Tampilkan Data</button>
                    </form>
                <hr>
                    <form class="form" role="form" method="post" action="livecks">
                        <?= csrf_field(); ?>
                        <div class="fw-bold text-center mb-2" style="font-size: 14px;">.:: Kode Rak ::.</div>
                        <input type="text" name="koderak" id="koderak" class="mb-3 text-center form-control input-sm" style="font-size: 14px;" value="<?= old('koderak'); ?>" required>
                        <div class="fw-bold text-center mb-2" style="font-size: 14px;">.:: Kode Sub Rak ::.</div>
                        <input type="text" name="kodesubrak" id="kodesubrak" class="mb-3 text-center form-control input-sm" style="font-size: 14px;" value="<?= old('kodesubrak'); ?>" required>
                        <button type="submit" name="tombol" value="btnall" class="btn w-100 mb-1 d-block text-light fw-bold btn-sm" style="background-color: #F94C10;font-size: 14px">ALL</button>
                        <button type="submit" name="tombol" value="btndsp" class="btn w-100 mb-1 d-block text-light fw-bold btn-sm" style="background-color: #6C3428;font-size: 14px">DISPLAY</button>
                        <button type="submit" name="tombol" value="btnstr" class="btn w-100 mb-1 d-block text-light fw-bold btn-sm" style="background-color: #6528F7;font-size: 14px">STORAGE</button>
                    </form>
                </div>
            </div>
            <div class="card w-100 mb-1">
                <div class="card-header text-light mb-2 text-center" style="background-color: #0040ff;">
                    <h6 class="fw-bold " style="font-size: 15px;">Antrian TrfLokasi</h6>
                </div>
                <div class="card-body">
                    <form class="form" method="post" role="form" action="livecks">
                        <?= csrf_field(); ?>
                        <div class="fw-bold text-center mb-2" style="font-size: 14px;">View Antrian</div>
                        <button type="submit" name="tombol" value="btndsp2" class="btn w-100 mb-1 d-block text-light fw-bold btn-sm" style="background-color: #6C3428;font-size: 14px">DISPLAY</button>
                        <button type="submit" name="tombol" value="btnstr2" class="btn w-100 mb-1 d-block text-light fw-bold btn-sm" style="background-color: #6528F7;font-size: 14px">STORAGE</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-10 mx-auto">
            <!-- Data monitoring PLano -->
            <?php if(!empty($display)) {?>
                <div class="card w-100 mb-3">
                    <table class="table table-bordered table-responsive table-condensed" style="font-size: 12px;">
                        <thead>
                            <tr class="active">
                                <th class="text-center">No</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Lokasi</th>
                                <th class="text-center">PLU</th>
                                <th class="text-center">Deskripsi</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Jenis</th>
                                <th class="text-end">Qty_Plano</th>
                                <th class="text-end">Max_Plano</th>
                                <th class="text-end">Max_Dis</th>
                                <th class="text-end">Min_Pct</th>
                                <th class="text-center">Flag_Jual</th>
                                <th class="text-center">Flag_Display</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php $no=1; ?>
                            <?php $kategori = ""; ?>
                            <?php foreach($display as $dp) : ?>
                                <?php $kategori = $dp['KATEGORI'];
                                switch ($kategori) {
                                    case "Display" : echo "<tr class='table-info'>"; break;
                                    case "Storage_C" : echo "<tr class='table-warning'>"; break;
                                    case "Storage_K" : echo "<tr class='table-success'>"; break;
                                    case "Storage_S" : echo "<tr class='table-danger'>"; break;
                                    default : echo "<tr>";
                                } ?>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center"><?= $dp['KATEGORI']; ?></td>
                                    <td class="text-center"><?= $dp['LOKASI']; ?></td>
                                    <td class="text-center"><?= $dp['PLU']; ?></td>
                                    <td class="text-center"><?= $dp['DESKRIPSI']; ?></td>
                                    <td class="text-center"><?= $dp['UNIT']; ?> / <?= $dp['FRAC']; ?></td>
                                    <td class="text-center"><?= $dp['JENISRAK']; ?></td>
                                    <td class="text-end"><?= number_format($dp['QTYPLANO'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($dp['MAXPLANO'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($dp['MAXDIS'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($dp['MINPCT'],0,'.',','); ?></td>
                                    <td class="text-center"><?= $dp['FLAGJUAL']; ?></td>
                                    <td class="text-center"><?= $dp['FLAGDISPLAY']; ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
            <!-- Data antrian trflokasi -->
            <?php if(!empty($trflokasi)) {?>
                <div class="card w-100 mb-3">
                    <table class="table table-bordered table-responsive table-condensed" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th class="table-success text-center" colspan="5">Plano Transfer</th>
                                <th class="table-success text-center" colspan="5">Plano Existing</th>
                            </tr>
                            <tr class="active">
                                <th class="table-success text-center">No</th>
                                <th class="table-success text-center">Lokasi</th>
                                <th class="table-success text-center">PLU</th>
                                <th class="table-success text-center">Deskripsi</th>
                                <th class="table-success text-center">Satuan</th>
                                <th class="table-success text-center">Lokasi</th>
                                <th class="table-success text-center">PLU</th>
                                <th class="table-success text-center">Deskripsi</th>
                                <th class="table-success text-center">Satuan</th>
                                <th class="table-success text-center">Qty_Plano</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php $no=1; ?>
                            <?php foreach($trflokasi as $tl) : ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-start"><?= $tl['LOKASI_TRANSFER']; ?></td>
                                    <td class="text-center"><?= $tl['PLU_TRANSFER']; ?></td>
                                    <td class="text-center"><?= $tl['DESKRIPSI_TRANSFER']; ?></td>
                                    <td class="text-center"><?= $tl['UNIT_TRANSFER']; ?> / <?= $tl['FRAC_TRANSFER']; ?></td>
                                    <td class="text-center"><?= $tl['LOKASI_EXIST']; ?></td>
                                    <td class="text-center"><?= $tl['PLU_EXIST']; ?></td>
                                    <td class="text-end"><?= $tl['DESKRIPSI_EXIST']; ?></td>
                                    <td class="text-end"><?= $tl['UNIT_EXIST']; ?> / <?= $tl['FRAC_EXIST']; ?></td>
                                    <td class="text-end"><?= number_format($tl['QTYPLANO_EXIST'],0,'.',','); ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
            <!--                                    Data Live CKS                                        -->
            <!-- Data LPP -->
            <?php if(!empty($viewlpp)) {?>
            <div class="row">
                <div class='col-xs-12'>
                    <div class="fw-bold"> LPP vs PLANO</div>
                    <table class="table table-bordered table-responsive table-condensed" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th class="table-success text-center">PLU</th>
                                <th class="table-success text-center">Deskripsi</th>
                                <th class="table-success text-center">Tag</th>
                                <th class="table-success text-center">FlagJual</th>
                                <th class="table-success text-center">Unit</th>
                                <th class="table-success text-center">Frac</th>
                                <th class="table-success text-center">Acost</th>
                                <th class="table-success text-center">QtyLPP</th>
                                <th class="table-success text-center">QtyPLANO</th>
                                <th class="table-success text-center">QtySelisih</th>
                                <th class="table-success text-center">RphSelisih</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach($viewlpp as $lp) : ?>
                                <tr>
                                    <td class="text-center"><?= $lp['PLU']; ?></td>
                                    <td class="text-start"><?= $lp['DESKRIPSI']; ?></td>
                                    <td class="text-center"><?= $lp['TAG']; ?></td>
                                    <td class="text-center"><?= $lp['FLAGJUAL']; ?></td>
                                    <td class="text-center"><?= $lp['UNIT']; ?></td>
                                    <td class="text-end"><?= number_format($lp['FRAC'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($lp['ACOST'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($lp['LPP_QTY'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($lp['PLANO_QTY'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($lp['SLSH_QTY'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($lp['SLSH_RPH'],0,'.',','); ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>
            <!-- Data Display -->
            <?php if(!empty($viewdisplay)) {?>
            <div class="row">
                <div class='col-xs-6' style="width: 550px;">
                    <div class="fw-bold"> DISPLAY</div>
                    <table class="table table-bordered table-responsive table-condensed" style="font-size: 12px;">
                        <thead>
                            <tr class="table-warning">
                                <th class="text-center">Alamat Display</th>
                                <th class="text-center">Jenis</th>
                                <th class="text-center">MaxPLano</th>
                                <th class="text-center">MaxDis</th>
                                <th class="text-center">Minpct</th>
                                <th class="text-center">QtyPlano</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach($viewdisplay as $dp) : ?>
                                <tr>
                                    <td class="text-start"><?= $dp['ALAMAT_DISPLAY']; ?></td>
                                    <td class="text-center"><?= $dp['JENISRAK']; ?></td>
                                    <td class="text-end"><?= number_format($dp['MAXPLANO'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($dp['MAXDIS'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($dp['MINPCT'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($dp['QTYPLANO'],0,'.',','); ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <!-- Data Storage -->
                    <?php if(!empty($viewstr)) {?>
                        <div class="fw-bold"> Storage</div>
                        <table class="table table-bordered table-responsive table-condensed" style="font-size: 12px;">
                            <thead>
                                <tr class="table-danger">
                                    <th class="text-center">Lokasi</th>
                                    <th class="text-center">Tipe</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">QtyPlano</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php $lokasirak = $katplano = ""; ?>
                                <?php foreach($viewstr as $st) : ?>
                                    <tr>
                                        <?php $lokasirak=$st['RAK_STORAGE'];
                                        switch ($lokasirak) {
                                            case "D" : echo "<td> RAK D </td>";  break;
                                            case "G" : echo "<td> RAK G </td>";  break;
                                            case "O" : echo "<td> RAK O </td>";  break;
                                            case "R" : echo "<td> RAK R </td>";  break;
                                            default : echo "<td>".$lokasirak."</td>";
                                        } ?>
                                        <?php $katplano=$st['TIPE_STORAGE'];
                                        switch ($katplano) {
                                            case "C" : echo "<td> <b>[ C ]</b> Storage Campur</td>";  break;
                                            case "K" : echo "<td> <b>[ K ]</b> Storage Kecil</td>";  break;
                                            case "S" : echo "<td> <b>[ S ]</b> Storage Besar</td>";  break;
                                            default : echo "<td>".$katplano."</td>";
                                        } ?>
                                        <td class="text-end"><?= number_format($st['JML_STORAGE'],0,'.',','); ?></td>
                                        <td class="text-end"><?= number_format($st['QTYPLANO_STORAGE'],0,'.',','); ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
                <div class='col-xs-6' style="width: 550px;">
                    <!-- Data KKPKM -->
                    <?php if(!empty($viewkkpkm)) {?>
                    <div class="fw-bold"> KKPKM</div>
                    <table class="table table-bordered table-responsive table-condensed" style="font-size: 12px;">
                        <thead>
                            <tr class="table-info">
                                <th class="table-success text-center">PKM</th>
                                <th class="table-success text-center">MPKM</th>
                                <th class="table-success text-center">MPLUS</th>
                                <th class="table-success text-center">PKMT</th>
                                <th class="table-success text-center">N+</th>
                                <th class="table-success text-center">Minor</th>
                                <th class="table-success text-center">MaxPalet</th>
                                <th class="table-success text-center">KKHPBM</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach($viewkkpkm as $kp) : ?>
                                <tr>
                                    <td class="text-end"><?= number_format($kp['PKM'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($kp['MPKM'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($kp['MPLUS'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($kp['PKMT'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($kp['NPLUS'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($kp['MINOR'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($kp['MAXPALET'],0,'.',','); ?></td>
                                    <td class="text-end"><?= $kp['KKHPBM']; ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <?php } ?>
                    <!-- Data RUMUS CKS -->
                    <div class=""><b>Perhitungan CKS</b>
                        <table class="table table-bordered border-dark table-responsive table-condensed" style="font-size: 12px; height: 40px;">
                            <tbody>
                                <tr>
                                    <td colspan="2"><p>Rumus CKS = (PKMT - MAXDIS) vs MAXPALET</p></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Max Palet </td>
                                    <td class="text-start">: <?= $maxpalet; ?> (CTN) = <?= number_format($nilaimaxpalet,0,'.',','); ?> (PCS)</td>
                                </tr>
                                <tr>
                                    <td class="text-start">PKMT </td>
                                    <td class="text-start">: <?= number_format($pkmt,0,'.',','); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Total MaxDis </td>
                                    <td class="text-start">: <?= number_format($ttlmaxdis,0,'.',','); ?></td>
                                </tr>
                                <?php $rumuscks = ($pkmt - $ttlmaxdis) / $nilaimaxpalet *100; ?>
                                <tr>
                                    <td class="text-start">Hasil Perhitungan </td>
                                    <td class="text-start">:<?= number_format($rumuscks,2,'.',','); ?> %</td>
                                </tr>
                                <?php if($rumuscks<=0) {
                                    $statuscks = "<b> NON STORAGE </b>";
                                }elseif($rumuscks<25) {
                                    $statuscks = "<b> [ C ] Storage Campur</b>";
                                }elseif($rumuscks<50) {
                                    $statuscks = "<b> [ K ] Storage Kecil </b>";
                                }else {
                                    $statuscks = "<b> [ S ] Storage Besar </b>";
                                } ?>
                                <tr>
                                    <td>Status CKS </td>
                                    <td>: <?= $statuscks; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>
            <!-- Data SPB -->
            <?php if(!empty($viewspb)) {?>
            <div class="row">
                <div class="col-xs-6" style="width: 550px;">
                    <div class="fw-bold">SPB Aktif</div>
                    <table class="table table-bordered border-dark table-responsive table-condensed" style="font-size: 12px;">
                        <thead>
                            <tr class="table-info">
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">LksAsal</th>
                                <th class="text-center">LksTujuan</th>
                                <th class="text-center">Plano</th>
                                <th class="text-center">QtySPB</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach($viewspb as $vs) : ?>
                                <tr>
                                    <td class="text-center"><?= $vs['SPB_CREATE_DT']; ?></td>
                                    <td class="text-center"><?= $vs['SPB_LOKASIASAL']; ?></td>
                                    <td class="text-center"><?= $vs['SPB_LOKASITUJUAN']; ?></td>
                                    <td class="text-end"><?= number_format($vs['QTY_PLANO'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($vs['QTY_SPB'],0,'.',','); ?></td>
                                    <td class="text-start"><?= $vs['STATUS']; ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <!-- History SPB -->
                    <div class="fw-bold">History SPB</div>
                    <table class="table table-bordered border-dark table-responsive table-condensed" style="font-size: 12px;">
                        <thead>
                            <tr class="table-info">
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">LksAsal</th>
                                <th class="text-center">LksTujuan</th>
                                <th class="text-center">Plano</th>
                                <th class="text-center">QtySPB</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach($historyspb as $hs) : ?>
                                <tr>
                                    <td class="text-center"><?= $hs['SPB_CREATE_DT']; ?></td>
                                    <td class="text-center"><?= $hs['SPB_LOKASIASAL']; ?></td>
                                    <td class="text-center"><?= $hs['SPB_LOKASITUJUAN']; ?></td>
                                    <td class="text-end"><?= number_format($hs['QTY_PLANO'],0,'.',','); ?></td>
                                    <td class="text-end"><?= number_format($hs['QTY_SPB'],0,'.',','); ?></td>
                                    <td class="text-start"><?= $hs['STATUS']; ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <!-- Data SLP -->
                <div class="col-xs-6" style="width: 550px;">
                    <?php if(!empty($viewslp)) {?>
                        <div class="fw-bold">SLP</div>
                        <table class="table table-bordered border-dark table-responsive table-condensed" style="font-size: 12px;">
                            <thead>
                                <tr class="table-info">
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">LksTujuan</th>
                                    <th class="text-center">QtySLP</th>
                                    <th class="text-center">Jenis</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach($viewslp as $sl) : ?>
                                    <tr>
                                        <td class="text-center"><?= $sl['SLP_CREATE_DT']; ?></td>
                                        <td class="text-center"><?= $sl['LOKASITUJUAN']; ?></td>
                                        <td class="text-end"><?= number_format($sl['SLP_QTYPCS'],0,'.',','); ?></td>
                                        <td class="text-center"><?= $sl['SLP_JENIS']; ?></td>
                                        <td class="text-center"><?= $sl['STATUS']; ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
