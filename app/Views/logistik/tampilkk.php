<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kertas Kerja Storage Kecil</title>
        <?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>

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
        <?php 
            $realToko = $realItem = $realQty = $realRph = $realPPN = $realDF = 0;
            $itemOmiDiSk = $pkmLebihKecilDariMaxPallet = "Off";
            $no = 1;
            // $rowStorageBesar      = 130;
            // $rowStorageKecil      = 80;
            $dvs = $dep = $katbr = "ALL";

            if ($kodeDivisi != "All" AND $kodeDivisi != "") {
                echo ' <span class="badge">Divisi: ' . $kodeDivisi . '</span>';
            }
        
            if ($kodeDepartemen != "All" AND $kodeDepartemen != "") {
                echo ' <span class="badge">Departemen: ' . $kodeDepartemen . '</span>';
            }
        
            if ($kodeKategoriBarang != "All" AND $kodeKategoriBarang != "") {
                echo ' <span class="badge">Kategori: ' . $kodeKategoriBarang . '</span>';
            }
        
            if ($statusTag != "All" AND $statusTag != "") {
                echo ' <span class="badge">Kode Tag: ' . $statusTag . '</span>';
            }

            if ($kodeRak != "All" AND $kodeRak != "") {
                echo ' <span class="badge">Kode Rak: ' . $kodeRak . '</span>';
            }

            foreach($divisi as $dv) :
                if($kodeDivisi == $dv['DIV_KODEDIVISI']) {
                    $dvs = $dv['DIV_NAMADIVISI'];
                }
            endforeach;

            foreach($departemen as $dp) :
                if($kodeDepartemen == $dp['DEP_KODEDEPARTEMENT']) {
                    $dep = $dp['DEP_NAMADEPARTEMENT'];
                }
            endforeach;
    
            foreach($kategori as $kt) :
                if($kodeKategoriBarang == $kt['KAT_KODEDEPARTEMENT'].$kt['KAT_KODEKATEGORI'] ) {
                    $katbr = $kt['KAT_NAMAKATEGORI'];
                }
            endforeach;

            if($lap == '1') {
                $jlap = 'LAPORAN per PRODUK';
            }
        ?>

        <?php if($lap == "1") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h6>Periode : <?= date('d M Y') ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info">#</th>
                        <th colspan="5" class="fw-bold text-center bg-info">Lokasi</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Divisi</th>
                        <th colspan="6" class="fw-bold text-center bg-info">Produk</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Item Pareto</th>
                        <th colspan="3" class="fw-bold text-center bg-info text-nowrap">Avg Sales in Pcs</th>
                        <th colspan="10" class="fw-bold text-center bg-info text-nowrap">Data PKM</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Perhitungan</th>
                        <th colspan="2" class="fw-bold text-center bg-info">ROW</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Storage Kecil</th>
                        <th colspan="3" class="fw-bold text-center bg-info">Status</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Item OMI</th>
                        <th colspan="4" class="fw-bold text-center bg-info">Dimensi</th>
                        <th colspan="4" class="fw-bold text-center bg-info">Plano</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Jenis Rak</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Rak Storage</th>
                        <th colspan="10" class="fw-bold text-center bg-info">Pertemanan</th>
                        <th rowspan="2" class="fw-bold text-center bg-info">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info">Rak</th>
                        <th class="fw-bold text-center bg-info">Sub</th>
                        <th class="fw-bold text-center bg-info">Tipe</th>
                        <th class="fw-bold text-center bg-info">Shelving</th>
                        <th class="fw-bold text-center bg-info text-nowrap">No Urut</th>
                        <th class="fw-bold text-center bg-info">Div</th>
                        <th class="fw-bold text-center bg-info">Dept</th>
                        <th class="fw-bold text-center bg-info">Katb</th>
                        <th class="fw-bold text-center bg-info">PLU</th>
                        <th class="fw-bold text-center bg-info">Deskripsi</th>
                        <th class="fw-bold text-center bg-info">Unit</th>
                        <th class="fw-bold text-center bg-info">Frac</th>
                        <th class="fw-bold text-center bg-info">Tag</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Status Tag</th>
                        <th class="fw-bold text-center bg-info">IGR</th>
                        <th class="fw-bold text-center bg-info text-nowrap">OMI / IDM</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Total Avg</th>
                        <th class="fw-bold text-center bg-info">Proses</th>
                        <th class="fw-bold text-center bg-info">Minor</th>
                        <th class="fw-bold text-center bg-info">Mindis</th>
                        <th class="fw-bold text-center bg-info">LT</th>
                        <th class="fw-bold text-center bg-info text-nowrap">SL (%)</th>
                        <th class="fw-bold text-center bg-info">Koef</th>
                        <th class="fw-bold text-center bg-info">PKM</th>
                        <th class="fw-bold text-center bg-info">MPKM</th>
                        <th class="fw-bold text-center bg-info text-nowrap">M-Plus</th>
                        <th class="fw-bold text-center bg-info">PKMT</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PKMT + 50% Minor</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Max Display</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Max Palet</th>
                        <th class="fw-bold text-center bg-info">Storage</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Storage Kecil</th>
                        <th class="fw-bold text-center bg-info text-nowrap">% SK</th>
                        <th class="fw-bold text-center bg-info text-nowrap">% SK in Qty</th>
                        <th class="fw-bold text-center bg-info">Existing</th>
                        <th class="fw-bold text-center bg-info">Rumus</th>
                        <th class="fw-bold text-center bg-info">Rubah</th>
                        <th class="fw-bold text-center bg-info">P</th>
                        <th class="fw-bold text-center bg-info">L</th>
                        <th class="fw-bold text-center bg-info">T</th>
                        <th class="fw-bold text-center bg-info">Volume</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Max Qty Toko</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Min % Toko</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Max Qty OMI</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Min % OMI</th>
                        <th class="fw-bold text-center bg-info">R</th>
                        <th class="fw-bold text-center bg-info">RC</th>
                        <th class="fw-bold text-center bg-info">1</th>
                        <th class="fw-bold text-center bg-info">2</th>
                        <th class="fw-bold text-center bg-info">3</th>
                        <th class="fw-bold text-center bg-info">4</th>
                        <th class="fw-bold text-center bg-info">5</th>
                        <th class="fw-bold text-center bg-info">6</th>
                        <th class="fw-bold text-center bg-info">7</th>
                        <th class="fw-bold text-center bg-info">8</th>
                        <th class="fw-bold text-center bg-info">9</th>
                        <th class="fw-bold text-center bg-info">10</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($datakk as $kk) : ?>
                        <?php 
                            $rowSKPersen = ($rowStorageKecil / $rowStorageBesar) * 100 / 2;
                            $rowSKQty = $kk['KKS_MAX_PALET'] * $rowSKPersen / 100;
     
                            $status = "aneh tapi nyata";
     
                            if ($kk['KKS_PKMT'] + ($kk['KKS_MINOR'] /2 ) > $rowSKQty){
                                    $status = "S";
                            } elseif ($kk['KKS_PKMT'] + ($kk['KKS_MINOR'] /2 ) > $kk['KKS_MAX_DISPLAY'] && $kk['KKS_PKMT'] + ($kk['KKS_MINOR'] /2 )  < $rowSKQty) {
                                    $status = "SK";
                            } elseif ($kk['KKS_PKMT'] + ($kk['KKS_MINOR'] /2 )  <= $kk['KKS_MAX_DISPLAY']) {
                                    $status = "NS";
                            }
                            if ($pkmLebihKecilDariMaxPallet == "ON" AND $status <> "S") {
                                continue;
                            }   
                        ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $kk['KKS_RAK']; ?></td>
                            <td class="text-center"><?= $kk['KKS_SUBRAK']; ?></td>
                            <td class="text-center"><?= $kk['KKS_TIPE']; ?></td>
                            <td class="text-center"><?= $kk['KKS_SHELVING']; ?></td>
                            <td class="text-center"><?= $kk['KKS_NO_URUT']; ?></td>
                            <td class="text-center"><?= $kk['KKS_DIV']; ?></td>
                            <td class="text-center"><?= $kk['KKS_DEPT']; ?></td>
                            <td class="text-center"><?= $kk['KKS_KATB']; ?></td>
                            <td class="text-center"><?= $kk['KKS_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $kk['KKS_NAMA_BARANG']; ?></td>
                            <td class="text-center"><?= $kk['KKS_UNIT']; ?></td>
                            <td class="text-center"><?= $kk['KKS_FRAC']; ?></td>
                            <td class="text-center"><?= $kk['KKS_KODE_TAG']; ?></td>
                            <td class="text-center"><?= $kk['KKS_STATUS_TAG']; ?></td>
                            <td class="text-center"><?= $kk['KKS_ITEM_PARETO']; ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_SLS_QTY_AVG_IGR'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_SLS_QTY_AVG_OMI'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_SLS_QTY_AVG_IGR'] + $kk['KKS_SLS_QTY_AVG_OMI'], 0, '.', ','); ?></td>
                            <td class="text-center"><?= $kk['KKS_PERIODE_PKM']; ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_MINOR'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_MIN_DISPLAY'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_LEADTIME'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_SERVICE_LEVEL'], 0, '.', ','); ?> %</td>
                            <td class="text-end"><?= number_format($kk['KKS_KOEFISIEN'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_PKM'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_MPKM'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_MPLUS'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_PKMT'], 0, '.', ','); ?></td>
                            <td class="text-end bg-warning"><?= number_format($kk['KKS_PKM_MINOR'], 0, '.', ','); ?></td>
                            <td class="text-end bg-warning"><?= number_format($kk['KKS_MAX_DISPLAY'], 0, '.', ','); ?></td>
                            <td class="text-end bg-warning"><?= number_format($kk['KKS_MAX_PALET'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($rowStorageBesar, 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($rowStorageKecil, 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($rowSKPersen, 0, '.', ','); ?> %</td>
                            <td class="text-end"><?= number_format($rowSKQty, 0, '.', ','); ?></td>
                            <td class="text-center bg-warning"><?= $kk['KKS_EXIS_STS']; ?></td>
                            <td class="text-center bg-warning"><?= $status; ?></td>
                            <td class="text-center"> </td>
                            <td class="text-center"><?= $kk['KKS_ITEM_OMI']; ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_DIM_PANJANG'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_DIM_LEBAR'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_DIM_TINGGI'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS_DIM_VOLUME'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS__MAXPLANO_TOKO'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS__MINPCT_TOKO'], 0, '.', ','); ?> %</td>
                            <td class="text-end"><?= number_format($kk['KKS__MAXPLANO_OMI'], 0, '.', ','); ?></td>
                            <td class="text-end"><?= number_format($kk['KKS__MINPCT_OMI'], 0, '.', ','); ?> %</td>
                            <td class="text-center"><?= $kk['KKS_JENIS_RAK']; ?></td>
                            <?php
                                if ($kk['KKS_STORAGE_R'] > 0 && $kk['KKS_STORAGE_C'] > 0) {
                                    echo '<td align="center" style="background-color:yellow;">' .  'Ada' . '</td>';
                                    echo '<td align="center" style="background-color:yellow;">' .  'Ada' . '</td>';
                                } else {
                                    if ($kk['KKS_STORAGE_R'] > 0 ) {
                                        echo '<td align="right">' .  'Ada' . '</td>';
                                    } else {
                                        echo '<td align="center">&nbsp</td>';	
                                    }
        
                                    if ($kk['KKS_STORAGE_C'] > 0 ) {
                                        echo '<td align="right">' . 'Ada' . '</td>';
                                    } else {
                                        echo '<td align="center">&nbsp</td>';	
                                    }
                                }
                            ?>
                            <td class="text-start"><?= $kk['KKS_TEMAN1']; ?></td>
                            <td class="text-start"><?= $kk['KKS_TEMAN2']; ?></td>
                            <td class="text-start"><?= $kk['KKS_TEMAN3']; ?></td>
                            <td class="text-start"><?= $kk['KKS_TEMAN4']; ?></td>
                            <td class="text-start"><?= $kk['KKS_TEMAN5']; ?></td>
                            <td class="text-start"><?= $kk['KKS_TEMAN6']; ?></td>
                            <td class="text-start"><?= $kk['KKS_TEMAN7']; ?></td>
                            <td class="text-start"><?= $kk['KKS_TEMAN8']; ?></td>
                            <td class="text-start"><?= $kk['KKS_TEMAN9']; ?></td>
                            <td class="text-start"><?= $kk['KKS_TEMAN10']; ?></td>
                            <td class="text-start">&nbsp;</td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } ?>
    </body>
</html>