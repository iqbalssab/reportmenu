<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Member Tidak Berbelanja</title>
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
            $jumlahMember = $jumlahPoin = $jumlahHari = $no          = 0;
            $jenisMember          = "Merah"; 
            $aktivasiKartu  = $jenisLaporan   	    = "All"; 
            if(isset($_GET['awal'])) {if ($_GET['awal'] !=""){$tanggalMulai = $_GET['awal']; }}
            if(isset($_GET['akhir'])) {if ($_GET['akhir'] !=""){$tanggalSelesai = $_GET['akhir']; }}
            if(isset($_GET['jenisMember'])) {if ($_GET['jenisMember'] !=""){$jenisMember = $_GET['jenisMember']; }}
            if(isset($_GET['aktivasiKartu'])) {if ($_GET['aktivasiKartu'] !=""){$aktivasiKartu = $_GET['aktivasiKartu']; }}
            if(isset($_GET['jenisLaporan'])) {if ($_GET['jenisLaporan'] !=""){$jenisLaporan = $_GET['jenisLaporan']; }}
        ?>

        <?php if($jenisLaporan == "1") { ?>
            <h4 class="fw-bold"><?= $jlap; ?></h4>
            <h5>Terakir Beli : <?= date('d M Y',strtotime($tanggalSelesai)) ?></h5>
            <h6>Member : <?= $jenisMember; ?>, Status Aktivasi : <?= $aktivasiKartu; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Outlet</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Member</th>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Poin</th>
                        <th colspan="4" class="fw-bold text-center text-nowrap bg-info">Kunjungan</th>
                        <th colspan="4" class="fw-bold text-center text-nowrap bg-info">Alamat</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Contact Number</th>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Sub</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Pertama</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Terakhir</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Repeat</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Hari</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Alamat</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kelurahan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kota</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode Pos</th>
                        <th class="fw-bold text-center text-nowrap bg-info">HP / Mobile</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Home</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php
                        $no++;
                        foreach($member as $mb) : ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $mb['CUS_KODEOUTLET']; ?></td>
                            <td class="text-center text-nowrap"><?= $mb['CUS_KODESUBOUTLET']; ?></td>
                            <td class="text-center text-nowrap"><?= $mb['CUS_KODEMEMBER']; ?></td>
                            <td class="text-start text-nowrap"><?= $mb['CUS_NAMAMEMBER']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mb['POC_SALDOPOINT'], 0, '.', ','); ?></td>
                            <td class="text-center text-nowrap"><?= $mb['KUN_PERTAMA']; ?></td>
                            <td class="text-center text-nowrap"><?= $mb['KUN_TERAKHIR']; ?></td>
                            <td class="text-center text-nowrap"><?= $mb['KUN_JUMLAH']; ?></td>
                            <td class="text-center text-nowrap"><?= $mb['KUN_HARI']; ?></td>
                            <td class="text-start text-nowrap"><?= $mb['CUS_ALAMATMEMBER5']; ?></td>
                            <td class="text-start text-nowrap"><?= $mb['CUS_ALAMATMEMBER8']; ?></td>
                            <td class="text-start text-nowrap"><?= $mb['CUS_ALAMATMEMBER6']; ?></td>
                            <td class="text-start text-nowrap"><?= $mb['CUS_ALAMATMEMBER7']; ?></td>
                            <td class="text-start text-nowrap"><?= $mb['CUS_HPMEMBER']; ?></td>
                            <td class="text-start text-nowrap"><?= $mb['CUS_TLPMEMBER']; ?></td>
                            <td class="text-start text-nowrap"></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php } else if($jenisLaporan == "2") { ?>
            <h4 class="fw-bold"><?= $jlap; ?></h4>
            <h5>Terakir Beli : <?= date('d M Y',strtotime($tanggalSelesai)) ?></h5>
            <h6>Member : <?= $jenisMember; ?>, Status Aktivasi : <?= $aktivasiKartu; ?></h6>
            <br>
            <table class="table table-responsive table-striped table-hover table-bordered border-dark">
                <thead class="table-group-divider">
                    <tr>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">#</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Outlet</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Sub Outlet</th>
                        <th colspan="3" class="fw-bold text-center text-nowrap bg-info">Jumlah</th>
                        <th colspan="2" class="fw-bold text-center text-nowrap bg-info">Wilayah</th>
                        <th rowspan="2" class="fw-bold text-center text-nowrap bg-info">Keterangan</th>
                    </tr>
                    <tr>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kode</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Nama</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Member</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Point</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Avg Hari</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kelurahan</th>
                        <th class="fw-bold text-center text-nowrap bg-info">Kota</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php
                        $no++;
                        foreach($member as $mb) : ?>
                        <tr>
                            <td class="text-end text-nowrap"><?= $no++; ?></td>
                            <td class="text-center text-nowrap"><?= $mb['CUS_KODEOUTLET']; ?></td>
                            <td class="text-start text-nowrap"><?= $mb['OUT_NAMAOUTLET']; ?></td>
                            <td class="text-center text-nowrap"><?= $mb['CUS_KODESUBOUTLET']; ?></td>
                            <td class="text-start text-nowrap"><?= $mb['SUB_NAMASUBOUTLET']; ?></td>
                            <td class="text-start text-nowrap"><?= $mb['JUMLAH_MEMBER']; ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mb['JUMLAH_POIN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mb['JUMLAH_HARI'] / $mb['JUMLAH_MEMBER'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mb['JUMLAH_KELURAHAN'], 0, '.', ','); ?></td>
                            <td class="text-end text-nowrap"><?= number_format($mb['JUMLAH_KOTA'], 0, '.', ','); ?></td>
                            <td class="text-start text-nowrap"></td>
                        </tr>
                        <?php 
                            $jumlahMember         += $mb['JUMLAH_MEMBER'];
                            $jumlahPoin           += $mb['JUMLAH_POIN'];
                            $jumlahHari           += $mb['JUMLAH_HARI'];
                        ?>
                    <?php endforeach ?>
                </tbody>
                <tfoot class="table-group-divider">
                    <tr>
                        <td colspan="5" class="fw-bold text-center">Total</td>
                        <?php
							if ($jumlahMember > 0) {
								echo '<td class="fw-bold text-end">'  . number_format($jumlahMember, 0, '.', ',') . '</td>';						
								echo '<td class="fw-bold text-end">'  . number_format($jumlahPoin, 0, '.', ',') . '</td>';
								echo '<td class="fw-bold text-end">'  . number_format(round($jumlahHari/$jumlahMember), 0, '.', ',') . '</td>';
							}
						?>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        <?php } ?>
    </body>
</html>