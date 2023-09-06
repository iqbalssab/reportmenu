<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DATA <?=$nostruk; ?></title>

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
        <link rel="stylesheet" href="<?= base_url('bootstrap/dist/css/bootstrap.min.css'); ?>">
        <!-- Style CSS -->
        <link rel="stylesheet" href="/css/style.css">
        <!-- Kalo gapake Laragon/XAMPP -->
        <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css">
    </head>
    <body>
        <?php if(!empty($sso)): ?>
            <div class="container-fluid mt-3">
                <div class="row mb-2">
                    <div class="col judul-data">
                        <h3 class="fw-bold">DATA SSO</h3>
                        <h5 class="fw-bold">Nomor Struk : <?= $nostruk; ?></h5>
                        <br><br>
                    </div>
                </div>
                <table class="table-bordered table-sm">
                    <thead>
                        <tr>
                            <th class="text-center">NO_STRUK</th>
                            <th class="text-center">SEQ_NO</th>
                            <th class="text-center">PLU</th>
                            <th class="text-center">DESKRIPSI</th>
                            <th class="text-center">HARGA_JUAL</th>
                            <th class="text-center">QTY</th>
                            <th class="text-center">DISCOUNT</th>
                            <th class="text-center">TOTAL</th>
                            <th class="text-center">FLAGBKP1</th>
                            <th class="text-center">FLAGBKP2</th>
                            <th class="text-center">KD_DIVISI</th>
                            <th class="text-center">DIVISI</th>
                            <th class="text-center">FREE_CHARGE</th>
                            <th class="text-center">FRAC</th>
                            <th class="text-center">MIN_JUAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sso as $so) : ?>
                        <tr>
                            <td class="text-center"><?=$so['KODE_TRANS']; ?></td>
                            <td class="text-center"><?=$so['SEQNO']; ?></td>
                            <td class="text-center"><?=$so['PRDCD']; ?></td>
                            <td class="text-start"><?=$so['DESKRIPSIPENDEK']; ?></td>
                            <td class="text-end"><?=number_format($so['HRGJUAL'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($so['QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($so['DISC'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($so['TOTAL'],'0',',','.'); ?></td>
                            <td class="text-center"><?=$so['FLAGBKP1']; ?></td>
                            <td class="text-center"><?=$so['FLAGBKP2']; ?></td>
                            <td class="text-center"><?=$so['KODEDIVISI']; ?></td>
                            <td class="text-center"><?=$so['DIVISI']; ?></td>
                            <td class="text-center"><?=$so['FREE_CHARGE']; ?></td>
                            <td class="text-center"><?=$so['FRAC']; ?></td>
                            <td class="text-end"><?=number_format($so['MIN_JUAL'],'0',',','.'); ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="">
                    <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
                </div>
            </div>
        <?php endif; ?>
    </body>
</html>