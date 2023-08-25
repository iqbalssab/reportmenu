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
        <?php if(!empty($sso)): ?>
            <div class="container-fluid mt-3">
                <div class="row mb-2">
                    <div class="col judul-data">
                        <h3 class="fw-bold">DATA SSO</h3>
                        <h5 class="fw-bold">Nomor Struk : <?= $nostruk; ?></h5>
                        <br><br>
                    </div>
                </div>
                <table class="table table-bordered border-dark table-sm">
                    <thead>
                        <tr>
                            <th>NO_STRUK</th>
                            <th>SEQ_NO</th>
                            <th>PLU</th>
                            <th>DESKRIPSI</th>
                            <th>HARGA_JUAL</th>
                            <th>QTY</th>
                            <th>DISCOUNT</th>
                            <th>TOTAL</th>
                            <th>FLAGBKP1</th>
                            <th>FLAGBKP2</th>
                            <th>KD_DIVISI</th>
                            <th>DIVISI</th>
                            <th>FREE_CHARGE</th>
                            <th>FRAC</th>
                            <th>MIN_JUAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sso as $so) : ?>
                        <tr>
                            <td><?=$so['KODE_TRANS']; ?></td>
                            <td><?=$so['SEQNO']; ?></td>
                            <td><?=$so['PRDCD']; ?></td>
                            <td><?=$so['DESKRIPSIPENDEK']; ?></td>
                            <td><?=$so['HRGJUAL']; ?></td>
                            <td><?=$so['QTY']; ?></td>
                            <td><?=$so['DISC']; ?></td>
                            <td><?=$so['TOTAL']; ?></td>
                            <td><?=$so['FLAGBKP1']; ?></td>
                            <td><?=$so['FLAGBKP2']; ?></td>
                            <td><?=$so['KODEDIVISI']; ?></td>
                            <td><?=$so['DIVISI']; ?></td>
                            <td><?=$so['FREE_CHARGE']; ?></td>
                            <td><?=$so['FRAC']; ?></td>
                            <td><?=$so['MIN_JUAL']; ?></td>
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