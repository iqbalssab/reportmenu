<?php 
echo $this->extend('layout/template'); ?>

<?php 
echo $this->section('content'); ?>

<div class="row">
	<div class="col-md-9 text-left">
		<h3>IDM Outstanding Retur</h3> 
	</div>
	<div class="col-md-3 text-right">
		<form name="frmSearch" method="get" action="outstandingretur">
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></div>
					<input type="text" class="form-control" id="search" name="search" placeholder="Search" value="<?= old("search"); ?>">
				</div>
			</div>
		</form>
	</div>
</div>
<div class="table-responsive">
    <table class="table table-responsive table-striped table-hover table-bordered border-dark">
        <thead class="table-group-divider">
            <tr>
                <th class="fw-bold text-center bg-info text-nowrap">No</th>
                <th class="fw-bold text-center bg-info text-nowrap">Tipe</th>
                <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                <th class="fw-bold text-center bg-info text-nowrap">Shop</th>
                <th class="fw-bold text-center bg-info text-nowrap">Nama Toko</th>
                <th class="fw-bold text-center bg-info text-nowrap">No Doc</th>
                <th class="fw-bold text-center bg-info text-nowrap">PLU IDM</th>
                <th class="fw-bold text-center bg-info text-nowrap">PLU IGR</th>
                <th class="fw-bold text-center bg-info text-nowrap">Deskripsi</th>
                <th class="fw-bold text-center bg-info text-nowrap">Qty</th>
                <th class="fw-bold text-center bg-info text-nowrap">Harga</th>
                <th class="fw-bold text-center bg-info text-nowrap">Netto</th>
                <th class="fw-bold text-center bg-info text-nowrap">PPN</th>
                <th class="fw-bold text-center bg-info text-nowrap">Gross</th>
                <th class="fw-bold text-center bg-info text-nowrap">Tanggal</th>
                <th class="fw-bold text-center bg-info text-nowrap">Lokasi</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php
                $no = 1;
                foreach ($retur as $rt) :
            ?>
                <tr>
                    <td class="text-end"><?= $no++; ?></td>
                    <td class="text-center"><?= $rt['ISTYPE']; ?></td>
                    <td class="text-center"><?= $rt['TANGGAL']; ?></td>
                    <td class="text-center"><?= $rt['SHOP']; ?></td>
                    <td class="text-start text-nowrap"><?= $rt['TKO_NAMAOMI']; ?></td>
                    <td class="text-center"><?= $rt['DOCNO']; ?></td>
                    <td class="text-center"><?= $rt['PLUIDM']; ?></td>
                    <td class="text-center"><?= $rt['PRD_PRDCD']; ?></td>
                    <td class="text-start text-nowrap"><?= $rt['PRD_DESKRIPSIPANJANG']; ?></td>
                    <td class="text-end"><?= number_format($rt['QTY'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($rt['PRICE'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($rt['NETTO'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($rt['PPN'],'0',',','.'); ?></td>
                    <td class="text-end"><?= number_format($rt['GROSS'],'0',',','.'); ?></td>
                    <td class="text-center"><?= $rt['WT_CREATE_DT']; ?></td>
                    <td class="text-center"><?= $rt['LOKASI']; ?></td>
                </tr>
            <?php    
                endforeach
            ?>
        </tbody>
    </table>
</div>

<?= $this->endSection(); ?>