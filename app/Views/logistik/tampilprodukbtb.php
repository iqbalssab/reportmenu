<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DATA STOK PER PERIODE BPB</title>

        <link rel="stylesheet" href="<?= base_url('bootstrap/dist/css/bootstrap.min.css'); ?>">
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

        <!-- Style CSS -->
        <link rel="stylesheet" href="/css/style.css">
        <!-- Kalo gapake Laragon/XAMPP -->
        <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css">
    </head>
    <body>
        <?php if(!empty($perprd)) { ?>
            <h2>DATA PRODUK BARU - Berdasarkan Periode BPB</h2>
            <table>
                <tr>
                    <th class="text-center">BPB_PERTAMA</th>
                    <th class="text-center">QTY_BPB</th>
                    <th class="text-center">DIV</th>
                    <th class="text-center">DEP</th>
                    <th class="text-center">KAT</th>
                    <th class="text-center">PLU_IGR</th>
                    <th class="text-center">PLU_OMI</th>
                    <th class="text-center">DESKRIPSI</th>
                    <th class="text-center">UNIT</th>
                    <th class="text-center">FRAC</th>
                    <th class="text-center">TAG_IGR</th>
                    <th class="text-center">TAG_OMI</th>
                    <th class="text-center">MINOR</th>
                    <th class="text-center">MPLUS</th>
                    <th class="text-center">PKMT</th>
                    <th class="text-center">BARC_PLU0</th>
                    <th class="text-center">BARC_PLU1</th>
                    <th class="text-center">BARC_PLU2</th>
                    <th class="text-center">HRGJUAL_PLU0</th>
                    <th class="text-center">HRGJUAL_PLU1</th>
                    <th class="text-center">HRGJUAL_PLU2</th>
                    <th class="text-center">DISPLAY_TOKO</th>
                    <th class="text-center">DISPLAY_OMI</th>
                </tr>
                <?php foreach($perprd as $pd) : ?>
                <tr>
                    <td class="text-center"><?= $pd['BPB_PERTAMA']; ?></td>
                    <td class="text-center"><?= $pd['QTY_BPB']; ?></td>
                    <td class="text-center"><?= $pd['DIV']; ?></td>
                    <td class="text-center"><?= $pd['DEP']; ?></td>
                    <td class="text-center"><?= $pd['KAT']; ?></td>
                    <td class="text-center"><?= $pd['PLU_IGR']; ?></td>
                    <td class="text-center"><?= $pd['PLU_OMI']; ?></td>
                    <td class="text-center"><?= $pd['DESKRIPSI']; ?></td>
                    <td class="text-center"><?= $pd['UNIT']; ?></td>
                    <td class="text-center"><?= $pd['FRAC']; ?></td>
                    <td class="text-center"><?= $pd['TAG_IGR']; ?></td>
                    <td class="text-center"><?= $pd['TAG_OMI']; ?></td>
                    <td class="text-end"><?= number_format($pd['MINOR'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['MPLUS'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['PKMT'],'0',',','.'); ?></td>
                    <td class="text-center"><?= $pd['BARCODE_PLU0']; ?></td>
                    <td class="text-center"><?= $pd['BARCODE_PLU1']; ?></td>
                    <td class="text-center"><?= $pd['BARCODE_PLU2']; ?></td>
                    <td class="text-end"><?= number_format($pd['HARGAJUAL_PLU0'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['HARGAJUAL_PLU1'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['HARGAJUAL_PLU2'],'0',',','.'); ?></td>
                    <td class="text-center"><?= $pd['DISPLAY_TOKO']; ?></td>
                    <td class="text-center"><?= $pd['DISPLAY_OMI']; ?></td>
                </tr>
                <?php endforeach ?>
            </table>
            <br>
            <div class="">
                <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
            </div>
        <?php } ?>
    </body>
</html>