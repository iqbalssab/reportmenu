<?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Informasi Produk</title>

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
        </style>
    </head>
    <body>
        <?php $no = 1; ?>
        <?php if($seasonal) { ?> 
            <h2>DATA ITEM SEASONAL - <?= date('d M Y',strtotime($awal)); ?> s/d <?= date('d M Y',strtotime($akhir)); ?></h2>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th class="text-center fw-bold bg-info">#</th>
                        <th class="text-center fw-bold bg-info">KODESUP</th>
                        <th class="text-center fw-bold bg-info">NAMASUPPLIER</th>
                        <th class="text-center fw-bold bg-info">DIV</th>
                        <th class="text-center fw-bold bg-info">DEP</th>
                        <th class="text-center fw-bold bg-info">KAT</th>
                        <th class="text-center fw-bold bg-info">PLU</th>
                        <th class="text-center fw-bold bg-info">DESKRIPSI</th>
                        <th class="text-center fw-bold bg-info">UNIT</th>
                        <th class="text-center fw-bold bg-info">FRAC</th>
                        <th class="text-center fw-bold bg-info">FLAGJUAL</th>
                        <th class="text-center fw-bold bg-info">ACOST</th>
                        <th class="text-center fw-bold bg-info">LCOST</th>
                        <th class="text-center fw-bold bg-info">STOK</th>
                        <th class="text-center fw-bold bg-info">RPH_STOK</th>
                        <th class="text-center fw-bold bg-info">LAST_BPB</th>
                        <th class="text-center fw-bold bg-info">LAST_PO</th>
                        <th class="text-center fw-bold bg-info">PO_OUTSTANDING</th>
                        <th class="text-center fw-bold bg-info">DISPLAY</th>
                        <th class="text-center fw-bold bg-info">QTY_DISPLAY</th>
                        <th class="text-center fw-bold bg-info">HRG_PLU0</th>
                        <th class="text-center fw-bold bg-info">HRG_PLU1</th>
                        <th class="text-center fw-bold bg-info">HRG_PLU2</th>
                        <th class="text-center fw-bold bg-info">HRG_PLU3</th>
                        <th class="text-center fw-bold bg-info">HRG_PROMO</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($seasonal as $sea) : ?>
                        <tr>
                            <td class="text-end"><?=$no++; ?></td>
                            <td class="text-center"><?=$sea['KDSUP']; ?></td>
                            <td class="text-start text-nowrap"><?=$sea['NAMASUP']; ?></td>
                            <td class="text-center"><?=$sea['DIV']; ?></td>
                            <td class="text-center"><?=$sea['DEP']; ?></td>
                            <td class="text-center"><?=$sea['KAT']; ?></td>
                            <td class="text-center"><?=$sea['PLU']; ?></td>
                            <td class="text-start text-nowrap"><?=$sea['DESKRIPSI']; ?></td>
                            <td class="text-center"><?=$sea['UNIT']; ?></td>
                            <td class="text-center"><?=$sea['FRAC']; ?></td>
                            <td class="text-center"><?=$sea['FLAGJUAL']; ?></td>
                            <td class="text-end"><?=number_format($sea['ACOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['LCOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['STOK'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['RPHSTOK'],'0',',','.'); ?></td>
                            <td class="text-center text-nowrap"><?=$sea['LASTBPB']; ?></td>
                            <td class="text-center text-nowrap"><?=$sea['LASTPO']; ?></td>
                            <td class="text-end"><?=number_format($sea['QTY_PO_OUTSTANDING'],'0',',','.'); ?></td>
                            <td class="text-start"><?=$sea['ALAMATDISPLAY']; ?></td>
                            <td class="text-end"><?=number_format($sea['LKS_QTY'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['HARGA_PLU0'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['HARGA_PLU1'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['HARGA_PLU2'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['HARGA_PLU3'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['HARGA_PROMO'],'0',',','.'); ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } ?>
    </body>
</html>