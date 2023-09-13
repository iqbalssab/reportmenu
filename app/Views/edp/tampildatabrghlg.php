<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tampil Data Stock Harian</title>

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
    </head>
    <body>
    <?php if(!empty($barang)): ?>
        <div class="container-fluid mt-3">
            <div class="row mb-2">
                <div class="col judul-data">
                    <h5 class="fw-bold">Pencarian Data Barang Tertinggal Bedasarkan :</h5>
                    <h6>Kassa : <?= $kassa; ?></h6>
                    <h6>Tanggal : <?= date('d M Y',strtotime($tanggal)); ?></h6>
                    <h6>Periode Jam : <?= date('G:i',strtotime($awal)); ?> s/d <?= date('G:i',strtotime($akhir)); ?></h6>
                    <br>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm border-dark table-responsive" style="font-size: 14px;">
                <thead class="table-success border-dark">
                    <tr>
                        <th class="text-center">TANGGAL</th>
                        <th class="text-center">JAM</th>
                        <th class="text-center text-nowrap">NO STRUK</th>
                        <th class="text-center text-nowrap">ID KASIR</th>
                        <th class="text-center">KASSA</th>
                        <th class="text-center text-nowrap">KD MEMBER</th>
                        <th class="text-center">DESKRIPSI</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center text-nowrap">NAMA MEMBER</th>
                        <th class="text-center">ALAMAT</th>
                        <th class="text-center text-nowrap">NO HP</th>
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
        </div>
    <?php endif; ?>
    </body>
</html>