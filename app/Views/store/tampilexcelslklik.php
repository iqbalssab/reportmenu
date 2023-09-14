<!DOCTYPE html>
<html lang="en">

<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/fontawesome.css">
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/brands.css">
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/solid.css">
    <link rel="stylesheet" href="<?= $ip; ?>public/fontawesome/css/regular.css">
    <!-- Laragon -->
    <link rel="stylesheet" href="<?= $ip; ?>public/bootstrap/dist/css/bootstrap.min.css">

    <!-- Style CSS -->
    <link rel="stylesheet" href="<?= $ip; ?>public/css/style.css">
    <!-- Kalo gapake Laragon/XAMPP -->
    <!-- <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css"> -->

        <!-- Favicons -->
    <link href="<?= $ip; ?>public/assets/img/igr2.png" rel="icon">

</head>

<body>

<div class="container-fluid mt-2 overflow-auto">
    <h4><?= $judul1; ?></h4>
    <p>Member : <?= $judul2; ?></p>
    <?php if(!empty($perpb)): ?>
    <table class="table table-hover">
        <thead class="border border-top border-secondary">
            <tr>
                <th class="bg-primary text-light">KdMember</th>
                <th class="bg-primary text-light">NamaMember</th>
                <th class="bg-primary text-light">HPMember</th>
                <th class="bg-primary text-light">JnsMember</th>
                <th class="bg-primary text-light">NomorPB</th>
                <th class="bg-primary text-light">Pengiriman</th>
                <th class="bg-primary text-light">TipeBayar</th>
                <th class="bg-primary text-light">StatusPB</th>
                <th class="bg-primary text-light">TglTrans</th>
                <th class="bg-primary text-light">NoTrans</th>
                <th class="bg-primary text-light">ItemOrder</th>
                <th class="bg-primary text-light">QtyOrder</th>
                <th class="bg-primary text-light">RphOrder</th>
                <th class="bg-primary text-light">ItemReal</th>
                <th class="bg-primary text-light">QtyReal</th>
                <th class="bg-primary text-light">RphReal</th>
                <th class="bg-primary text-light">Picker</th>
                <th class="bg-primary text-light">PBMasuk</th>
                <th class="bg-primary text-light">SelesaiPick</th>
                <th class="bg-primary text-light">SelesaiPack</th>
                <th class="bg-primary text-light">TglStruk</th>
                <th class="bg-primary text-light">TglAWB</th>
                <th class="bg-primary text-light">NomorAWB</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach($perpb as $ppb): ?>
                    <tr>
                        <td><?= $ppb['KDMEMBER']; ?></td>
                        <td><?= $ppb['NAMAMEMBER']; ?></td>
                        <td><?= $ppb['HPMEMBER']; ?></td>
                        <td><?= $ppb['JENISMEMBER']; ?></td>
                        <td><?= $ppb['NOMORPB']; ?></td>
                        <td><?= $ppb['PENGIRIMAN']; ?></td>
                        <td><?= $ppb['TIPEBAYAR']; ?></td>
                        <td><?= $ppb['STATUSPB']; ?></td>
                        <td><?= $ppb['TGLTRANS']; ?></td>
                        <td><?= $ppb['NOTRANS']; ?></td>
                        <td><?= $ppb['ITEM_ORDER']; ?></td>
                        <td><?= $ppb['QTY_ORDER']; ?></td>
                        <td><?= $ppb['RPH_ORDER']; ?></td>
                        <td><?= $ppb['ITEM_REALISASI']; ?></td>
                        <td><?= $ppb['QTY_REALISASI']; ?></td>
                        <td><?= $ppb['RPH_REALISASI']; ?></td>
                        <td><?= $ppb['PICKER']; ?></td>
                        <td><?= $ppb['PBMASUK']; ?></td>
                        <td><?= $ppb['SELESAIPICK']; ?></td>
                        <td><?= $ppb['SELESAIPACK']; ?></td>
                        <td><?= $ppb['TGLSTRUK']; ?></td>
                        <td><?= $ppb['TGLAWB']; ?></td>
                        <td><?= $ppb['NOAWB']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif($perproduk): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="bg-success text-light">Div</th>
                    <th class="bg-success text-light">Dep</th>
                    <th class="bg-success text-light">Kat</th>
                    <th class="bg-success text-light">PLU</th>
                    <th class="bg-success text-light">Deskripsi</th>
                    <th class="bg-success text-light">Frac</th>
                    <th class="bg-success text-light">Unit</th>
                    <th class="bg-success text-light">JmlOrder</th>
                    <th class="bg-success text-light">JmlReal</th>
                    <th class="bg-success text-light">JmlSelisih</th>
                    <th class="bg-success text-light">QtyOrder</th>
                    <th class="bg-success text-light">QtyReal</th>
                    <th class="bg-success text-light">QtySelisih</th>
                    <th class="bg-success text-light">RphOrder</th>
                    <th class="bg-success text-light">RphReal</th>
                    <th class="bg-success text-light">RphSelisih</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($perproduk as $ppr): ?>
                    <tr>
                        <td><?= $ppr['DIV']; ?></td>
                        <td><?= $ppr['DEP']; ?></td>
                        <td><?= $ppr['KAT']; ?></td>
                        <td><?= $ppr['PLU']; ?></td>
                        <td><?= $ppr['DESKRIPSI']; ?></td>
                        <td><?= $ppr['FRAC']; ?></td>
                        <td><?= $ppr['UNIT']; ?></td>
                        <td><?= $ppr['JML_PB']; ?></td>
                        <td><?= $ppr['JML_REALISASI']; ?></td>
                        <td><?= $ppr['JML_PBVSREALISASI']; ?></td>
                        <td><?= number_format($ppr['QTY_ORDER']); ?></td>
                        <td><?= number_format($ppr['QTY_REALISASI']); ?></td>
                        <td><?= number_format($ppr['QTY_PBVSREALISASI']); ?></td>
                        <td><?= number_format($ppr['RPH_ORDER']); ?></td>
                        <td><?= number_format($ppr['RPH_REALISASI']); ?></td>
                        <td><?= number_format($ppr['RPH_PBVSREALISASI']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

    <script src="<?= $ip; ?>public/jquery-3.7.0.min.js"></script>
    <!-- <script src="jquery-3.7.0.min.js"></script> -->
    <script src="<?=$ip?>public/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->

    <script>
        $(function() {
           $('.dropdown').hover(function(){
            $(this).addClass('open');
           },
           function(){
            $(this).removeClass('open');
           }); 
        });
    </script>
</body>
</html>