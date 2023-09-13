<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MONITORING PERUBAHAN STATUS</title>

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
        <?php if($status == "0") {
            $sts = "BELUM UBAH STATUS";
        } else if($status == "1") {
            $sts = "SUDAH UBAH STATUS";
        } else if($status == "2") {
            $sts = "ALL";
        } ?>
        <h2>Monitoring Perubahan Status [<?= $sts; ?>]</h2>
        <table>
            <thead>
                <tr>
                    <th colspan="8" class="text-center">DETAIL PRODUK</th>
                    <th colspan="5" class="text-center">SORTIR BARANG</th>
                    <th colspan="5" class="text-center">PERUBAHAN STATUS</th>
                </tr>
                <tr>
                    <th class="text-center">DIV</th>
                    <th class="text-center">DEP</th>
                    <th class="text-center">KAT</th>
                    <th class="text-center">PLU</th>
                    <th class="text-center">DESKRIPSI</th>
                    <th class="text-center">FRAC</th>
                    <th class="text-center">UNIT</th>
                    <th class="text-center">TAG</th>
                    <th class="text-center">TGL. SORTIR</th>
                    <th class="text-center">NO. SORTIR</th>
                    <th class="text-center">KETERANGAN</th>
                    <th class="text-center">QTY</th>
                    <th class="text-center">ACOST</th>
                    <th class="text-center">TANGGAL</th>
                    <th class="text-center">NO. DOK</th>
                    <th class="text-center">QTY</th>
                    <th class="text-center">DARI</th>
                    <th class="text-center">KE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ubahstatus as $st) : ?>
                    <tr>
                        <td class="text-center"><?=$st['DIV']; ?></td>
                        <td class="text-center"><?=$st['DEP']; ?></td>
                        <td class="text-center"><?=$st['KAT']; ?></td>
                        <td class="text-center"><?=$st['PLU']; ?></td>
                        <td class="text-start"><?=$st['DESKRIPSI']; ?></td>
                        <td class="text-center"><?=$st['FRAC']; ?></td>
                        <td class="text-center"><?=$st['UNIT']; ?></td>
                        <td class="text-center"><?=$st['TAG']; ?></td>
                        <td class="text-center"><?=$st['SRT_TGLSORTIR']; ?></td>
                        <td class="text-center"><?=$st['SRT_NOSORTIR']; ?></td>
                        <td class="text-start"><?=$st['SRT_KETERANGAN']; ?></td>
                        <td class="text-end"><?=number_format($st['SRT_QTY'],'0',',','.'); ?></td>
                        <td class="text-end"><?=number_format($st['ACOST_PCS'],'0',',','.'); ?></td>
                        <td class="text-center"><?=$st['MSTD_TGLDOC']; ?></td>
                        <td class="text-center"><?=$st['MSTD_NODOC']; ?></td>
                        <td class="text-end"><?=number_format($st['MSTD_QTY'],'0',',','.'); ?></td>
                        <td class="text-center"><?=$st['STATUS_DARI']; ?></td>
                        <td class="text-center"><?=$st['STATUS_KE']; ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </body>
</html>