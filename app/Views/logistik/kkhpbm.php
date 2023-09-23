<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="container-fluid mt-3 overflow-auto">
    <div class="row justify-content-center">
        <h3 class="fw-bold text-center">Monitoring Kertas Kerja Harian PB Manual</h3>
    </div>
    <br><br>
    <?php
        $hari = date('D'); 
        $no = 1;
        switch($hari){
            case 'Sun':
                $hari_ini = "MINGGU";
            break;
    
            case 'Mon':			
                $hari_ini = "SENIN";
            break;
    
            case 'Tue':
                $hari_ini = "SELASA";
            break;
    
            case 'Wed':
                $hari_ini = "RABU";
            break;
    
            case 'Thu':
                $hari_ini = "KAMIS";
            break;
    
            case 'Fri':
                $hari_ini = "JUMAT";
            break;
            case 'Sat':
                $hari_ini = "SABTU";
            break;
            }
            $besok = mktime (0,0,0, date("m"), date("d")+1,date("Y"));
            echo "<h5 class='fw-bold'>Periode Proses ";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;: "; echo date('d-m-y'); echo"</h5>";
            echo "<h5 class='fw-bold'>Untuk PB Tanggal   : "; echo date('d-m-y',$besok); echo"</h5>";
            echo "<h5 class='fw-bold'>Hari &nbsp ";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";
            echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";
            echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;";echo "&nbsp;: "; echo $hari_ini; echo"</h5>";
    ?>
    <table class="table table-responsive table-striped table-hover table-bordered border-dark">
        <thead class="table-group-divider">
            <tr>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap my-auto">No</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Kode Sup</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Nama Sup</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Div</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Dept</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Katb</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">PLU</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Deskripsi</th>
                <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Satuan</th>
                <th colspan="3" class="fw-bold text-center bg-info text-nowrap">Sales 3 Bulan</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Jml Hari Sales</th>
                <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Avg Sales</th>
                <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Saldo</th>
                <th colspan="2" class="fw-bold text-center bg-info text-nowrap">PO Outstanding</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">LT</th>
                <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Minor</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">PKMT</th>
                <th colspan="2" class="fw-bold text-center bg-info text-nowrap">Max Pallet</th>
                <th rowspan="2" class="fw-bold text-center bg-info text-nowrap">Qty PB Ajuan</th>
            </tr>
            <tr>
                <th class="fw-bold text-center bg-info text-nowrap">Jual</th>
                <th class="fw-bold text-center bg-info text-nowrap">Beli</th>
                <th class="fw-bold text-center bg-info text-nowrap">Bln 1</th>
                <th class="fw-bold text-center bg-info text-nowrap">Bln 2</th>
                <th class="fw-bold text-center bg-info text-nowrap">Bln 3</th>
                <th class="fw-bold text-center bg-info text-nowrap">Bln</th>
                <th class="fw-bold text-center bg-info text-nowrap">Hari</th>
                <th class="fw-bold text-center bg-info text-nowrap">Awal</th>
                <th class="fw-bold text-center bg-info text-nowrap">Saat Ini</th>
                <th class="fw-bold text-center bg-info text-nowrap">Jml PO</th>
                <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                <th class="fw-bold text-center bg-info text-nowrap">Ctn</th>
                <th class="fw-bold text-center bg-info text-nowrap">Pcs</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach($kkhpbm as $pbm) : ?>
                <tr>
                    <td class="text-end"><?= $no++; ?></td>
                    <td class="text-center"><?= $pbm['KOD_SUP']; ?></td>
                    <td class="text-start text-nowrap"><?= $pbm['SUPPLIER']; ?></td>
                    <td class="text-center"><?= $pbm['DIV']; ?></td>
                    <td class="text-center"><?= $pbm['DEP']; ?></td>
                    <td class="text-center"><?= $pbm['KAT']; ?></td>
                    <td class="text-center"><?= $pbm['PLU']; ?></td>
                    <td class="text-start text-nowrap"><?= $pbm['DESK']; ?></td>
                    <td class="text-center"><?= $pbm['JUAL']; ?></td>
                    <td class="text-center"><?= $pbm['BELI']; ?></td>
                    <td class="text-end"><?= number_format($pbm['BULAN1'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['BULAN2'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['BULAN3'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['HARIS_SALES'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['AVG_BULAN'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['AVG_HARI'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['STOCK_AWAL'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['STOCK_AKHIR'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['JML_PO'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['QTY_PO'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['LT'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['MINOR'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['RUPIAH'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['PKMT'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['MAXPALET_CTN'], 0, '.', ','); ?></td>
                    <td class="text-end"><?= number_format($pbm['MAXPALET_PCS'], 0, '.', ','); ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>