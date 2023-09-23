<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Retur OMI</title>
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
            $returToko = $returNoDoc = $returItem = $returQty = $returRph     = 0;
            $kodeTokoOMI = $kodeTokoIDM = $$kodePLU = $kodeSupplier = $namaSupplier = $jenisLaporan = $dvs = $dep = $katbr = $kdsup = $nmsup = $tkomi = $tkidm = $noDocIgr = $noDocOmi = "All";
            $no = 1;
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

            if(isset($_GET['tokoOmi'])) {if ($_GET['tokoOmi'] !=""){$kodeTokoOMI = $_GET['tokoOmi']; }}
            foreach($tokoOmi as $tk) :
                if($kodeTokoOMI == $tk['TKO_KODEOMI']) {
                    $tkomi = $tk['TKO_NAMAOMI'];
                }
            endforeach;
            
            if(isset($_GET['tokoIdm'])) {if ($_GET['tokoIdm'] !=""){$kodeTokoIDM = $_GET['tokoIdm']; }}
            foreach($tokoIdm as $id) :
                if($kodeTokoIDM == $id['TKO_KODEOMI']) {
                    $tkidm = $id['TKO_NAMAOMI'];
                }
            endforeach;

            if(isset($_GET['kdsup'])) {if ($_GET['kdsup'] !=""){$kodeSupplier = $_GET['kdsup']; }}
            if ($kodeSupplier != "All" AND $kodeSupplier != "") {
                $kdsup = $kodeSupplier;
            }
            if(isset($_GET['nmsup'])) {if ($_GET['nmsup'] !=""){$namaSupplier = $_GET['nmsup']; }}
            if ($namaSupplier != "All" AND $namaSupplier != "") {
                $nmsup = $namaSupplier;
            }
            if(isset($_GET['plu'])) {if ($_GET['plu'] !=""){$kodePLU = $_GET['plu']; }}
            if ($kodePLU != "All" AND $kodePLU != "") {
                $kodePLU = substr('00000000' . $kodePLU, -7);
            } 
            if(isset($_GET['jenisLaporan'])) {if ($_GET['jenisLaporan'] !=""){$jenisLaporan = $_GET['jenisLaporan']; }}

            if(isset($_GET['noDocIgr'])) {if ($_GET['noDocIgr'] !=""){$noDocIgr = $_GET['noDocIgr']; }}
            if(isset($_GET['noDocOmi'])) {if ($_GET['noDocOmi'] !=""){$noDocOmi = $_GET['noDocOmi']; }}
        ?>

        <?php if($jenisLaporan == "1") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>No Dokumen IGR : <?= $noDocIgr; ?>, No Dokumen OMI : <?= $noDocOmi; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">OMI</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Dokumen</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Item</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Rupiah</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Kode Member</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($returomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['ROM_KODETOKO']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMATOKO']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NODOKUMEN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NETTO'], 0, '.', ','); ?></td>
                            <td class="text-center text-nowrap"><?= $omi['ROM_MEMBER']; ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $returRph     += $omi['ROM_NETTO'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="5" class="text-center fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= number_format($returRph, 0, '.', ','); ?></td>
                        <td colspan="1" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "2") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>No Dokumen IGR : <?= $noDocIgr; ?>, No Dokumen OMI : <?= $noDocOmi; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">#</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Toko</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Dokumen</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Item</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Rupiah</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($returomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['ROM_TGLDOKUMEN']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_KODETOKO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NODOKUMEN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NETTO'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $returRph     += $omi['ROM_NETTO'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="5" class="text-center fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= number_format($returRph, 0, '.', ','); ?></td>
                        <td colspan="2" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "2B") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>No Dokumen IGR : <?= $noDocIgr; ?>, No Dokumen OMI : <?= $noDocOmi; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Tanggal</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">OMI</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Dokumen</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Item</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Qty</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Rupiah</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($returomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['ROM_TGLDOKUMEN']; ?></td>
                            <td class="text-center text-nowrap"><?= $omi['ROM_KODETOKO']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMATOKO']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NODOKUMEN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NETTO'], 0, '.', ','); ?></td>
                        </tr>
                        <?php
                            $returRph     += $omi['ROM_NETTO'];
                            $returQty     += $omi['ROM_QTY'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="6" class="text-center fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= number_format($returQty, 0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($returRph, 0, '.', ','); ?></td>
                        <td class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "3") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>No Dokumen IGR : <?= $noDocIgr; ?>, No Dokumen OMI : <?= $noDocOmi; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Dokumen IGR</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">OMI</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Dokumen OMI</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Item</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Rupiah</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Selisih Hari</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nomor</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nomor</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($returomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['ROM_TGLDOKUMEN']; ?></td>
                            <td class="text-center"><?= $omi['ROM_NODOKUMEN']; ?></td>
                            <td class="text-center text-nowrap"><?= $omi['ROM_KODETOKO']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMATOKO']; ?></td>
                            <td class="text-center"><?= $omi['ROM_TGLREFERENSI']; ?></td>
                            <td class="text-center"><?= $omi['ROM_NOREFERENSI']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NETTO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['SELISIH_HARI'], 0, '.', ','); ?></td>
                        </tr>
                        <?php
                            $returRph     += $omi['ROM_NETTO'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="8" class="text-center fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= number_format($returRph, 0, '.', ','); ?></td>
                        <td colspan="2" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "4") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>No Dokumen IGR : <?= $noDocIgr; ?>, No Dokumen OMI : <?= $noDocOmi; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="3" class="fw-bold text-center bg-info text-no-wrap">Divisi</th>
                        <th colspan="5" class="fw-bold text-center bg-info text-no-wrap">Produk</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Qty</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Rupiah</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Supplier</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Dept</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Katb</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Deskripsi</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Unit</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Frac</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tag</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($returomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['ROM_DIV']; ?></td>
                            <td class="text-center"><?= $omi['ROM_DEPT']; ?></td>
                            <td class="text-center"><?= $omi['ROM_KATB']; ?></td>
                            <td class="text-center text-nowrap"><?= $omi['ROM_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMA_BARANG']; ?></td>
                            <td class="text-center"><?= $omi['ROM_UNIT']; ?></td>
                            <td class="text-center"><?= $omi['ROM_FRAC']; ?></td>
                            <td class="text-center"><?= $omi['ROM_KODETAG']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NETTO'], 0, '.', ','); ?></td>
                            <td class="text-center text-nowrap"><?= $omi['ROM_KODESUPPLIER']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMASUPPLIER']; ?></td>
                        </tr>
                        <?php
                            $returQty     += $omi['ROM_QTY'];
                            $returRph     += $omi['ROM_NETTO'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="9" class="text-center fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= number_format($returQty, 0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($returRph, 0, '.', ','); ?></td>
                        <td colspan="2" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "4B") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>No Dokumen IGR : <?= $noDocIgr; ?>, No Dokumen OMI : <?= $noDocOmi; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Toko</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">No Dokumen IGR</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">NRB OMI</th>
                        <th colspan="3" class="fw-bold text-center bg-info text-no-wrap">Divisi</th>
                        <th colspan="5" class="fw-bold text-center bg-info text-no-wrap">Produk</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Qty</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Rupiah</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Supplier</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nomor</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nomor</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Dept</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Katb</th>
                        <th class="fw-bold text-center bg-info text-nowrap">PLU</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Deskripsi</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Unit</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Frac</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Tag</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($returomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['ROM_KODETOKO']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMATOKO']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_TGLDOKUMEN']; ?></td>
                            <td class="text-center"><?= $omi['ROM_NODOKUMEN']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_TGLREFERENSI']; ?></td>
                            <td class="text-center"><?= $omi['ROM_NOREFERENSI']; ?></td>
                            <td class="text-center"><?= $omi['ROM_DIV']; ?></td>
                            <td class="text-center"><?= $omi['ROM_DEPT']; ?></td>
                            <td class="text-center"><?= $omi['ROM_KATB']; ?></td>
                            <td class="text-center text-nowrap"><?= $omi['ROM_PRDCD']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMA_BARANG']; ?></td>
                            <td class="text-center"><?= $omi['ROM_UNIT']; ?></td>
                            <td class="text-center"><?= $omi['ROM_FRAC']; ?></td>
                            <td class="text-center"><?= $omi['ROM_KODETAG']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_QTY'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NETTO'], 0, '.', ','); ?></td>
                            <td class="text-center text-nowrap"><?= $omi['ROM_KODESUPPLIER']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMASUPPLIER']; ?></td>
                        </tr>
                        <?php
                            $returQty     += $omi['ROM_QTY'];
                            $returRph     += $omi['ROM_NETTO'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="15" class="text-center fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= number_format($returQty, 0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($returRph, 0, '.', ','); ?></td>
                        <td colspan="3" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "5") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>No Dokumen IGR : <?= $noDocIgr; ?>, No Dokumen OMI : <?= $noDocOmi; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Divisi</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Item</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Rupiah</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Toko</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Supplier</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($returomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['ROM_DIV']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMADIVISI']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NETTO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_KODETOKO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_KODESUPPLIER'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $returItem     += $omi['ROM_ITEM'];
                            $returRph     += $omi['ROM_NETTO'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="3" class="text-center fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= number_format($returItem, 0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($returRph, 0, '.', ','); ?></td>
                        <td colspan="2" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "5B") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>No Dokumen IGR : <?= $noDocIgr; ?>, No Dokumen OMI : <?= $noDocOmi; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Divisi</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Departemen</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Item</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Rupiah</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Toko</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Supplier</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Dept</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($returomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['ROM_DIV']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMADIVISI']; ?></td>
                            <td class="text-center"><?= $omi['ROM_DEPT']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMADEPARTEMENT']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NETTO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_KODETOKO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_KODESUPPLIER'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $returItem     += $omi['ROM_ITEM'];
                            $returRph     += $omi['ROM_NETTO'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="5" class="text-center fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= number_format($returItem, 0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($returRph, 0, '.', ','); ?></td>
                        <td colspan="2" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "5C") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>No Dokumen IGR : <?= $noDocIgr; ?>, No Dokumen OMI : <?= $noDocOmi; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Divisi</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Departemen</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Kategori</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Item</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Rupiah</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Toko</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Supplier</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Div</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Dept</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Katb</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($returomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['ROM_DIV']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMADIVISI']; ?></td>
                            <td class="text-center"><?= $omi['ROM_DEPT']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMADEPARTEMENT']; ?></td>
                            <td class="text-center"><?= $omi['ROM_KATB']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMAKATEGORI']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NETTO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_KODETOKO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_KODESUPPLIER'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $returItem     += $omi['ROM_ITEM'];
                            $returRph     += $omi['ROM_NETTO'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="7" class="text-center fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= number_format($returItem, 0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($returRph, 0, '.', ','); ?></td>
                        <td colspan="2" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } else if($jenisLaporan == "6") { ?>
            <h4 class="fw-bold"><?= $jlap; ?> - Divisi : <?= $dvs; ?> - Departemen : <?= $dep; ?> - Kategori : <?= $katbr; ?></h4>
            <h5>Periode : <?= date('d M Y') ?></h5>
            <h6>Toko OMI : <?= $tkomi; ?>, Toko IDM : <?= $tkidm; ?></h6>
            <h6>No Dokumen IGR : <?= $noDocIgr; ?>, No Dokumen OMI : <?= $noDocOmi; ?></h6>
            <h6>PLU : <?= $kodePLU; ?>, Supplier : <?= $kdsup; ?>, Nama Supplier : <?= $nmsup; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">#</th>
                        <th colspan="2" class="fw-bold text-center bg-info text-no-wrap">Supplier</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Item</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Rupiah</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Toko</th>
                        <th rowspan="2" class="fw-bold text-center bg-info text-no-wrap">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center bg-info text-nowrap">Kode</th>
                        <th class="fw-bold text-center bg-info text-nowrap">Nama</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php foreach($returomi as $omi) : ?>
                        <tr>
                            <td class="text-end"><?= $no++; ?></td>
                            <td class="text-center"><?= $omi['ROM_KODESUPPLIER']; ?></td>
                            <td class="text-start text-nowrap"><?= $omi['ROM_NAMASUPPLIER']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_ITEM'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_NETTO'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($omi['ROM_KODETOKO'], 0, '.', ','); ?></td>
                            <td></td>
                        </tr>
                        <?php
                            $returItem     += $omi['ROM_ITEM'];
                            $returRph     += $omi['ROM_NETTO'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="3" class="text-center fw-bold">Total</td>
                        <td class="text-end fw-bold"><?= number_format($returItem, 0, '.', ','); ?></td>
                        <td class="text-end fw-bold"><?= number_format($returRph, 0, '.', ','); ?></td>
                        <td colspan="2" class="text-center fw-bold"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } ?>
    </body>
</html>