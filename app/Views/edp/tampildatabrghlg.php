<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tampil Data Barang Hilang</title>

        <link rel="stylesheet" href="<?= $ip; ?>public/bootstrap/dist/css/bootstrap.min.css">
        <style>
            .container {border:3px solid #666;padding:10px;margin:0 auto;width:500px}
            input {margin:5px;}
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
                margin:0 0 10px;
                width:auto;
            }
            th{
                background:#66CCFF;
                padding:5px;
                font-weight:400;
                font-size:12px;
            }
            td{
                padding:2px 5px;
                font-size:12px;
                text-overflow: ellipsis;
            }
            h1, h2, h3, h4, h5, h6{
                font-weight: bold;
            }
            h3, h4 {
                font-size: 18px;
            }
            h5, h6 {
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <h1 class="fw-bold">Pencarian Data Barang Tertinggal Bedasarkan :</h1>
        <h3>Kassa : <?= $kassa; ?></h3>
        <h3>Tanggal : <?= date('d M Y',strtotime($tanggal)); ?></h3>
        <h3>Periode Jam : <?= date('G:i',strtotime($awal)); ?> s/d <?= date('G:i',strtotime($akhir)); ?></h3>
        <br>
        <table class="table table-responsive table-striped table-hover table-bordered border-dark">
            <thead class="table-group-divider">
                <tr>
                    <th class="fw-bold text-center bg-info">TANGGAL</th>
                    <th class="fw-bold text-center bg-info">JAM</th>
                    <th class="fw-bold text-center bg-info text-nowrap">NO STRUK</th>
                    <th class="fw-bold text-center bg-info text-nowrap">ID KASIR</th>
                    <th class="fw-bold text-center bg-info">KASSA</th>
                    <th class="fw-bold text-center bg-info text-nowrap">KD MEMBER</th>
                    <th class="fw-bold text-center bg-info">DESKRIPSI</th>
                    <th class="fw-bold text-center bg-info">QTY</th>
                    <th class="fw-bold text-center bg-info text-nowrap">NAMA MEMBER</th>
                    <th class="fw-bold text-center bg-info">ALAMAT</th>
                    <th class="fw-bold text-center bg-info text-nowrap">NO HP</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php foreach($barang as $brg) : ?>
                    <tr>
                        <td class="text-center text-nowrap"><?=$brg['TANGGAL']; ?></td>
                        <td class="text-center"><?=$brg['JAM']; ?></td>
                        <td class="text-center"><?=$brg['NO_STRUK']; ?></td>
                        <td class="text-center"><?=$brg['ID_KASIR']; ?></td>
                        <td class="text-center"><?=$brg['KASSA']; ?></td>
                        <td class="text-center"><?=$brg['KODE_MEMBER']; ?></td>
                        <td class="text-start text-nowrap"><?=$brg['NAMA_BARANG']; ?></td>
                        <td class="text-end"><?=$brg['QTY']; ?></td>
                        <td class="text-start text-nowrap"><?=$brg['NAMA_MEMBER']; ?></td>
                        <td class="text-start text-nowrap"><?=$brg['ALAMAT']; ?></td>
                        <td class="text-start"><?=$brg['NO_HP']; ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <div class="">
            <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
        </div>
    </body>
</html>