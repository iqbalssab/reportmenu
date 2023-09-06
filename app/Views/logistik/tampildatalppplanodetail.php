<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title ?></title>

        <link rel="stylesheet" href="<?= base_url('bootstrap/dist/css/bootstrap.min.css'); ?>">

        <!-- Style CSS -->
        <link rel="stylesheet" href="/css/style.css">
        <!-- Kalo gapake Laragon/XAMPP -->
        <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css">
    </head>
    <body>
        <?php if(!empty($detail)) { ?>
            <?php if($versi == "ver1") { ?>
                <div class="container-fluid mt-3">
                    <div class="row mb-2">
                        <div class="col judul-data">
                            <h4 class="fw-bold">STOK LPP vs PLANO - DETAIL <i>Versi.1</i></h4>
                            <h5 class="fw-bold">Divisi : <?=$judul; ?></h5>
                        </div>
                    </div>
                    <table class="table table-bordered border-dark table-sm" style="font-size: 14px;">
                        <thead>
                            <tr>
                                <th class="text-center">DIV</th>
                                <th class="text-center">DEPT</th>
                                <th class="text-center">KATB</th>
                                <th class="text-center">PLU</th>
                                <th class="text-center">DESKRIPSI</th>
                                <th class="text-center">UNIT</th>
                                <th class="text-center">FRAC</th>
                                <th class="text-center">TAG</th>
                                <th class="text-center">ACOST</th>
                                <th class="text-center">STOK_LPP</th>
                                <th class="text-center">STOK_PLANO</th>
                                <th class="text-center">QTY_SELISIH</th>
                                <th class="text-center">RPH_LPP</th>
                                <th class="text-center">RPH_PLANO</th>
                                <th class="text-center">RPH_SELISIH</th>
                                <th class="text-center">PLANO_DISPLAY_TOKO</th>
                                <th class="text-center">PLANO_STORAGE_TOKO</th>
                                <th class="text-center">PLANO_DISPLAY_GUDANG</th>
                                <th class="text-center">PLANO_STORAGE_GUDANG</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-center">JENIS</th>
                                <th class="text-center">DISPLAY_TOKO</th>
                                <th class="text-center">DISPLAY_OMI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($detail as $dt) : ?>
                            <tr>
                                <td class="text-center"><?=$dt['DIV']; ?></td>
                                <td class="text-center"><?=$dt['DEP']; ?></td>
                                <td class="text-center"><?=$dt['KAT']; ?></td>
                                <td class="text-center"><?=$dt['PLU']; ?></td>
                                <td><?=$dt['DESKRIPSI']; ?></td>
                                <td><?=$dt['FRAC']; ?></td>
                                <td><?=$dt['UNIT']; ?></td>
                                <td class="text-center"><?=$dt['TAG']; ?></td>
                                <td class="text-end"><?=number_format($dt['ACOST'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['STOKLPP'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['STOKPLANO'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['QTYSELISIH'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['RPHLPP'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['RPHPLANO'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['RPHSELISIH'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['STOKPLANO_DISPLAY_TOKO'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['STOKPLANO_STORAGE_TOKO'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['STOKPLANO_DISPLAY_GUDANG'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['STOKPLANO_STORAGE_GUDANG'],'0',',','.'); ?></td>
                                <td class="text-center"><?=$dt['FLAG']; ?></td>
                                <td class="text-center"><?=$dt['STATUS']; ?></td>
                                <td><?=$dt['DISPLAY_TOKO']; ?></td>
                                <td><?=$dt['DISPLAY_OMI']; ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="">
                        <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
                    </div>
                </div>
            <?php } elseif($versi == "ver2") { ?>
                <div class="container-fluid mt-3">
                    <div class="row mb-2">
                        <div class="col judul-data">
                            <h4 class="fw-bold">STOK LPP vs PLANO - DETAIL <i>Versi.2</i></h4>
                            <h5 class="fw-bold">Divisi : <?=$judul; ?></h5>
                        </div>
                    </div>
                    <table class="table table-bordered border-dark table-sm" style="font-size: 14px;">
                        <thead>
                            <tr>
                                <th class="text-center">DIV</th>
                                <th class="text-center">DEPT</th>
                                <th class="text-center">KATB</th>
                                <th class="text-center">PLU</th>
                                <th class="text-center">DESKRIPSI</th>
                                <th class="text-center">FRAC</th>
                                <th class="text-center">UNIT</th>
                                <th class="text-center">TAG</th>
                                <th class="text-center">ACOST</th>
                                <th class="text-center">QTY_INTRANSIT</th>
                                <th class="text-center">RPH_INTRANSIT</th>
                                <th class="text-center">QTY_LPP</th>
                                <th class="text-center">RPH_LPP</th>
                                <th class="text-center">QTY_PLANO</th>
                                <th class="text-center">RPH_PLANO</th>
                                <th class="text-center">QTY_SELISIH</th>
                                <th class="text-center">RPH_SELISIH</th>
                                <th class="text-center">KETERANGAN</th>
                                <th class="text-center">DISPLAY_TOKO</th>
                                <th class="text-center">DISPLAY_OMI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($detail as $dt) : ?>
                            <tr>
                                <td class="text-center"><?=$dt['DIV']; ?></td>
                                <td class="text-center"><?=$dt['DEPT']; ?></td>
                                <td class="text-center"><?=$dt['KATB']; ?></td>
                                <td class="text-center"><?=$dt['PLU']; ?></td>
                                <td><?=$dt['DESKRIPSI']; ?></td>
                                <td><?=$dt['FRAC']; ?></td>
                                <td><?=$dt['UNIT']; ?></td>
                                <td class="text-center"><?=$dt['TAG']; ?></td>
                                <td class="text-end"><?=number_format($dt['ACOST'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['LPP_INTRANSIT'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['INTRANSIT_RPH'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['LPP_QTY'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['LPP_RPH'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['PLANO_QTY'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['PLANO_RPH'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['QTYSELISIH'],'0',',','.'); ?></td>
                                <td class="text-end"><?=number_format($dt['RPHSELISIH'],'0',',','.'); ?></td>
                                <td class="text-center"><?=$dt['STATUS']; ?></td>
                                <td><?=$dt['DISPLAY_TOKO']; ?></td>
                                <td><?=$dt['DISPLAY_OMI']; ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="">
                        <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
                    </div>
                </div>
            <?php }?>
        <?php } ?>
    </body>
</html>