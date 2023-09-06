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

        <!-- Style CSS -->
        <link rel="stylesheet" href="/css/style.css">
        <!-- Kalo gapake Laragon/XAMPP -->
        <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.min.css">
    </head>
    <body>
        <?php if($seasonal) { ?> 
            <h2>DATA ITEM SEASONAL - <?= date('d M Y',strtotime($awal)); ?> s/d <?= date('d M Y',strtotime($akhir)); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th class="text-center">KODESUP</th>
                        <th class="text-center">NAMASUPPLIER</th>
                        <th class="text-center">DIV</th>
                        <th class="text-center">DEP</th>
                        <th class="text-center">KAT</th>
                        <th class="text-center">PLU</th>
                        <th class="text-center">DESKRIPSI</th>
                        <th class="text-center">UNIT</th>
                        <th class="text-center">FRAC</th>
                        <th class="text-center">FLAGJUAL</th>
                        <th class="text-center">ACOST</th>
                        <th class="text-center">LCOST</th>
                        <th class="text-center">STOK</th>
                        <th class="text-center">RPH_STOK</th>
                        <th class="text-center">LAST_BPB</th>
                        <th class="text-center">LAST_PO</th>
                        <th class="text-center">PO_OUTSTANDING</th>
                        <th class="text-center">DISPLAY</th>
                        <th class="text-center">QTY_DISPLAY</th>
                        <th class="text-center">HRG_PLU0</th>
                        <th class="text-center">HRG_PLU1</th>
                        <th class="text-center">HRG_PLU2</th>
                        <th class="text-center">HRG_PLU3</th>
                        <th class="text-center">HRG_PROMO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($seasonal as $sea) : ?>
                        <tr>
                            <td class="text-center"><?=$sea['KDSUP']; ?></td>
                            <td class="text-center"><?=$sea['NAMASUP']; ?></td>
                            <td class="text-center"><?=$sea['DIV']; ?></td>
                            <td class="text-center"><?=$sea['DEP']; ?></td>
                            <td class="text-center"><?=$sea['KAT']; ?></td>
                            <td class="text-center"><?=$sea['PLU']; ?></td>
                            <td class="text-start"><?=$sea['DESKRIPSI']; ?></td>
                            <td class="text-center"><?=$sea['UNIT']; ?></td>
                            <td class="text-center"><?=$sea['FRAC']; ?></td>
                            <td class="text-center"><?=$sea['FLAGJUAL']; ?></td>
                            <td class="text-end"><?=number_format($sea['ACOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['LCOST'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['STOK'],'0',',','.'); ?></td>
                            <td class="text-end"><?=number_format($sea['RPHSTOK'],'0',',','.'); ?></td>
                            <td class="text-center"><?=$sea['LASTBPB']; ?></td>
                            <td class="text-center"><?=$sea['LASTPO']; ?></td>
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