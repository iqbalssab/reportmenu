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
        <?php if(!empty($perdiv)): ?>
            <?php if($divisi == "0") {
            $div = "ALL";
            } else if($divisi == "1") {
                $div = "FOOD";
            } else if($divisi == "2") {
                $div = "NON FOOD";
            } else if($divisi == "3") {
                $div = "GMS";
            } else if($divisi == "4") {
                $div = "PERISHABLE";
            } else if($divisi == "5") {
                $div = "COUNTER & PROMOTION";
            } else if($divisi == "6") {
                $div = "FAST FOOD";
            } ?>

            <?php if($tag == "0") {
                $kd = " ALL";
            } else if($tag == "1") {
                $kd = " DI LUAR TAG [HOAXNT]";
            } else if($tag == "2") {
                $kd = " HANYA TAG [HOAXNT]";
            } ?>
            <h2>DATA PRODUK BARU [<?= $div; ?>] - <?= $kd ?></h2>
            <table>
                <tr>
                    <th colspan="3" class="text-center">Divisi</th>
                    <th colspan="19" class="text-center">Produk</th>
                    <th colspan="2" class="text-center">Supplier</th>
                    <th colspan="13" class="text-center">Sales Bulanan</th>                    
                    <th rowspan="2" class="text-center">PKM</th>
                    <th rowspan="2" class="text-center">MPLUS</th>
                    <th rowspan="2" class="text-center">PKMT</th>
                    <th rowspan="2" class="text-center">LT</th>
                    <th rowspan="2" class="text-center">DSI</th>
                    <th rowspan="2" class="text-center">LAST PO</th>
                    <th rowspan="2" class="text-center">LAST BPB</th>
                    <th rowspan="2" class="text-center">QTY PO</th>
                    <th rowspan="2" class="text-center">JML PO</th>
                    <th rowspan="2" class="text-center">TGL AKTIF</th>
                    <th rowspan="2" class="text-center">DISPLAY REG</th>
                    <th rowspan="2" class="text-center">DISPLAY DPD</th>
                    <th rowspan="2" class="text-center">FLAG JUAL</th>
                </tr>
                <tr>
                    <th class="text-center">DIV</th>
                    <th class="text-center">DEP</th>
                    <th class="text-center">KAT</th>
                    <th class="text-center">PLU IGR</th>
                    <th class="text-center">PLU OMI</th>
                    <th class="text-center">DESKRIPSI</th>
                    <th class="text-center">UNIT</th>
                    <th class="text-center">FRAC</th>
                    <th class="text-center">MINOR</th>
                    <th class="text-center">MINDIS</th>
                    <th class="text-center">BKP</th>
                    <th class="text-center">ACOST</th>
                    <th class="text-center">LCOST</th>
                    <th class="text-center">HARGA JUAL</th>
                    <th class="text-center">HARGA BELI</th>
                    <th class="text-center">DISC1</th>
                    <th class="text-center">DISC2</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">TAG IGR</th>
                    <th class="text-center">TAG OMI</th>
                    <th class="text-center">STOCK</th>
                    <th class="text-center">STOCK RPH</th>
                    <th class="text-center">KODE SUPL</th>
                    <th class="text-center">NAMA SUPL</th>
                    <th class="text-center">SALES JAN</th>
                    <th class="text-center">SALES FEB</th>
                    <th class="text-center">SALES MAR</th>
                    <th class="text-center">SALES APR</th>
                    <th class="text-center">SALES MAY</th>
                    <th class="text-center">SALES JUN</th>
                    <th class="text-center">SALES JUL</th>
                    <th class="text-center">SALES AUG</th>
                    <th class="text-center">SALES SEP</th>
                    <th class="text-center">SALES OCT</th>
                    <th class="text-center">SALES NOV</th>
                    <th class="text-center">SALES DEC</th>
                    <th class="text-center">SALES BLN INI</th>
                </tr>
                <?php foreach($perdiv as $pd) : ?>
                <tr>
                    <td class="text-center"><?= $pd['DIV']; ?></td>
                    <td class="text-center"><?= $pd['DEP']; ?></td>
                    <td class="text-center"><?= $pd['KAT']; ?></td>
                    <td class="text-center"><?= $pd['PLU_IGR']; ?></td>
                    <td class="text-center"><?= $pd['PLU_OMI']; ?></td>
                    <td class="text-center"><?= $pd['DESKRIPSI']; ?></td>
                    <td class="text-center"><?= $pd['UNIT']; ?></td>
                    <td class="text-center"><?= $pd['FRAC']; ?></td>
                    <td class="text-end"><?= number_format($pd['MINOR'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['MINDIS'],'0',',','.'); ?></td>
                    <td class="text-center"><?= $pd['BKP']; ?></td>
                    <td class="text-end"><?= number_format($pd['ACOST'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['LCOST'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['HRGJUAL'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['HRGBELI'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['DISC1'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['DISC2'],'0',',','.'); ?></td>
                    <td class="text-center"><?= $pd['STATUS']; ?></td>
                    <td class="text-center"><?= $pd['TAG_IGR']; ?></td>
                    <td class="text-center"><?= $pd['TAG_OMI']; ?></td>
                    <td class="text-end"><?= number_format($pd['STOCK'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['STOCK_RPH'],'0',',','.'); ?></td>
                    <td class="text-center"><?= $pd['KODESUP']; ?></td>
                    <td class="text-center"><?= $pd['NAMASUPPLIER']; ?></td>
                    <td class="text-end"><?= number_format($pd['JAN'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['PEB'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['MAR'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['APR'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['MEI'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['JUN'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['JUL'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['AGS'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['SEP'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['OKT'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['NOV'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['DES'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['BLN_INI'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['PKM'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['MPLUS'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['PKMT'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['LT'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['DSI'],'0',',','.'); ?></td>
                    <td class="text-center"><?= $pd['LASTPO']; ?></td>
                    <td class="text-center"><?= $pd['LASTBPB']; ?></td>
                    <td class="text-end"><?= number_format($pd['QTY_PO'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($pd['JML_PO'],'0',',','.'); ?></td>
                    <td class="text-center"><?= $pd['PLN_TGLAKTIF']; ?></td>
                    <td class="text-center"><?= $pd['DISPLAY_REG']; ?></td>
                    <td class="text-center"><?= $pd['DISPLAY_DPD']; ?></td>
                    <td class="text-center"><?= $pd['FLAG_JUAL']; ?></td>
                </tr>
                <?php endforeach ?>
            </table>
            <br>
            <div class="">
                <p style="font-size:small"><b><i>** Dicetak pada : <?php echo date('d M Y') ?> **</i></b></p>
            </div>
        <?php endif; ?>
    </body>
</html>