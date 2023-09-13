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
        <style>
            .container {border:3px solid #666;padding:10px;margin:0 auto;width:500px}
            input {margin:5px;}
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                margin:0 0 10px;
                width:auto;
                font-size:12px;
            }
            th{
            background:#66CCFF;
            padding:5px;
            font-weight:400;
            }
            td{
            padding:2px 5px;
            }
        </style>
    </head>
    <body>
        <?php $no = 1; ?>
        <?php if(!empty($detail)) { ?>
            <?php if($versi == "ver1") { ?>
                <h4 class="fw-bold">STOK LPP vs PLANO - DETAIL <i>Versi.1</i></h4>
                <h5 class="fw-bold">Divisi : <?=$judul; ?></h5>
                <br>
                <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                        <thead class="table-group-divider">
                            <tr>
                                <th rowspan="2" class="text-center fw-bold bg-info py-auto">#</th>
                                <th colspan="3" class="text-center fw-bold bg-info">Divisi</th>
                                <th colspan="6" class="text-center fw-bold bg-info">Produk</th>
                                <th colspan="3" class="text-center fw-bold bg-info">Stok</th>
                                <th colspan="3" class="text-center fw-bold bg-info">Rupiah</th>
                                <th colspan="4" class="text-center fw-bold bg-info">Plano</th>
                                <th rowspan="2" class="text-center fw-bold bg-info py-auto">STATUS</th>
                                <th rowspan="2" class="text-center fw-bold bg-info py-auto">JENIS</th>
                                <th colspan="2" class="text-center fw-bold bg-info">Display</th>
                            </tr>
                            <tr>
                                <th class="text-center fw-bold bg-info">DIV</th>
                                <th class="text-center fw-bold bg-info">DEPT</th>
                                <th class="text-center fw-bold bg-info">KATB</th>
                                <th class="text-center fw-bold bg-info">PLU</th>
                                <th class="text-center fw-bold bg-info">DESKRIPSI</th>
                                <th class="text-center fw-bold bg-info">UNIT</th>
                                <th class="text-center fw-bold bg-info">FRAC</th>
                                <th class="text-center fw-bold bg-info">TAG</th>
                                <th class="text-center fw-bold bg-info">ACOST</th>
                                <th class="text-center fw-bold bg-info text-nowarp">STOK_LPP</th>
                                <th class="text-center fw-bold bg-info text-nowarp">STOK_PLANO</th>
                                <th class="text-center fw-bold bg-info text-nowarp">QTY_SELISIH</th>
                                <th class="text-center fw-bold bg-info text-nowarp">RPH_LPP</th>
                                <th class="text-center fw-bold bg-info text-nowarp">RPH_PLANO</th>
                                <th class="text-center fw-bold bg-info text-nowarp">RPH_SELISIH</th>
                                <th class="text-center fw-bold bg-info text-nowarp">PLANO_DISPLAY_TOKO</th>
                                <th class="text-center fw-bold bg-info text-nowarp">PLANO_STORAGE_TOKO</th>
                                <th class="text-center fw-bold bg-info text-nowarp">PLANO_DISPLAY_GUDANG</th>
                                <th class="text-center fw-bold bg-info text-nowarp">PLANO_STORAGE_GUDANG</th>
                                <th class="text-center fw-bold bg-info text-nowarp">DISPLAY_TOKO</th>
                                <th class="text-center fw-bold bg-info text-nowarp">DISPLAY_OMI</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach($detail as $dt) : ?>
                            <tr>
                                <td class="text-end"><?=$no++; ?></td>
                                <td class="text-center"><?=$dt['DIV']; ?></td>
                                <td class="text-center"><?=$dt['DEP']; ?></td>
                                <td class="text-center"><?=$dt['KAT']; ?></td>
                                <td class="text-center"><?=$dt['PLU']; ?></td>
                                <td class="text-start text-nowrap"><?=$dt['DESKRIPSI']; ?></td>
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
                                <td class="text-start text-nowrap"><?=$dt['FLAG']; ?></td>
                                <td class="text-center"><?=$dt['STATUS']; ?></td>
                                <td class="text-start text-nowrap"><?=$dt['DISPLAY_TOKO']; ?></td>
                                <td class="text-start text-nowrap"><?=$dt['DISPLAY_OMI']; ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="">
                        <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
                    </div>
            <?php } elseif($versi == "ver2") { ?>
                <h4 class="fw-bold">STOK LPP vs PLANO - DETAIL <i>Versi.2</i></h4>
                <h5 class="fw-bold">Divisi : <?=$judul; ?></h5>
                <br>
                <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                    <thead class="table-group-divider">
                        <tr>
                                <th rowspan="2" class="text-center fw-bold bg-info py-auto">#</th>
                                <th colspan="3" class="text-center fw-bold bg-info">Divisi</th>
                                <th colspan="6" class="text-center fw-bold bg-info">Produk</th>
                                <th colspan="2" class="text-center fw-bold bg-info">Intransit</th>
                                <th colspan="2" class="text-center fw-bold bg-info">LPP</th>
                                <th colspan="2" class="text-center fw-bold bg-info">Plano</th>
                                <th colspan="2" class="text-center fw-bold bg-info">Selisih</th>
                                <th rowspan="2" class="text-center fw-bold bg-info">KETERANGAN</th>
                                <th colspan="2" class="text-center fw-bold bg-info">Display</th>
                            </tr>
                            <tr>
                                <th class="text-center fw-bold bg-info">DIV</th>
                                <th class="text-center fw-bold bg-info">DEPT</th>
                                <th class="text-center fw-bold bg-info">KATB</th>
                                <th class="text-center fw-bold bg-info">PLU</th>
                                <th class="text-center fw-bold bg-info">DESKRIPSI</th>
                                <th class="text-center fw-bold bg-info">FRAC</th>
                                <th class="text-center fw-bold bg-info">UNIT</th>
                                <th class="text-center fw-bold bg-info">TAG</th>
                                <th class="text-center fw-bold bg-info">ACOST</th>
                                <th class="text-center fw-bold bg-info">QTY_INTRANSIT</th>
                                <th class="text-center fw-bold bg-info">RPH_INTRANSIT</th>
                                <th class="text-center fw-bold bg-info">QTY_LPP</th>
                                <th class="text-center fw-bold bg-info">RPH_LPP</th>
                                <th class="text-center fw-bold bg-info">QTY_PLANO</th>
                                <th class="text-center fw-bold bg-info">RPH_PLANO</th>
                                <th class="text-center fw-bold bg-info">QTY_SELISIH</th>
                                <th class="text-center fw-bold bg-info">RPH_SELISIH</th>
                                <th class="text-center fw-bold bg-info">DISPLAY_TOKO</th>
                                <th class="text-center fw-bold bg-info">DISPLAY_OMI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($detail as $dt) : ?>
                            <tr>
                                <td class="text-end"><?= $no++; ?></td>
                                <td class="text-center"><?=$dt['DIV']; ?></td>
                                <td class="text-center"><?=$dt['DEPT']; ?></td>
                                <td class="text-center"><?=$dt['KATB']; ?></td>
                                <td class="text-center"><?=$dt['PLU']; ?></td>
                                <td class="text-nowrap"><?=$dt['DESKRIPSI']; ?></td>
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
            <?php }?>
        <?php } ?>
    </body>
</html>