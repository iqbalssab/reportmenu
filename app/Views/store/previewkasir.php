
<?= $this->extend('layout/template');?>

<?= $this->section('content'); ?>
                <?php 
                $tanggal = date('d-m-Y');
                date_default_timezone_set("Asia/Jakarta"); 
                $waktu = date('H:i:s');
                ?>
<div class="container-fluid mt-2 mx-auto fs-6 text-small">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-success">
                <h5 class="text-center text-light fw-bold">Preview Transaksi Kasir || <?= $tanggal; ?> - <?= $waktu; ?></h5>
                </div>
                <div class="card-body">
                <table class="table table-sm mb-3">
    <thead>
      <tr>
          <th scope="col" class="text-center">ID</th>
          <th scope="col" class="text-center">Nama Kasir</th>
          <th scope="col" class="text-center">Kassa</th>
          <th scope="col" class="text-center">Total Transaksi</th>
          <th scope="col" class="text-center">Tunai</th>
          <th scope="col" class="text-center">K Debit</th>
          <th scope="col" class="text-center">K Kredit</th>
          <th scope="col" class="text-center">Kredit</th>
          <th scope="col" class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
      <?php $i=1;?>
      <?php foreach($transactions as $transaksi): ?>
    <tr>
      <th scope="row"><?= $transaksi['ID_KASIR'] ?></th>
      <td><?= $transaksi['NAMA_KASIR']; ?></td> 
      <td class="text-center"><?= $transaksi['KASSA']; ?></td>
      <td class="text-end"><?= number_format($transaksi['TOTAL_TRANSAKSI'],'0',',','.') ; ?></td>
      <td class="text-end"><?= number_format($transaksi['TUNAI'],'0',',','.') ; ?></td>
      <td class="text-end"><?= number_format($transaksi['KDEBIT'],'0',',','.'); ?></td>
      <td class="text-end"><?= number_format($transaksi['KKREDIT'],'0',',','.'); ?></td>
      <td class="text-end"><?= number_format($transaksi['KREDIT'],'0',',','.'); ?></td>
      <td class="text-center">
        <?php if($transaksi['STATUS']=='Aktif'){ ?>
        <span class="badge text-bg-success">
            <?= $transaksi['STATUS']; ?>
        </span>
        <?php }elseif($transaksi['STATUS']=='Closing'){ ?>
            <span class="badge text-bg-danger">
            <?= $transaksi['STATUS']; ?>
        </span>
        <?php } ?>
    </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td></td>
        <td class="fw-bold">Total Transaksi</td>
        <td></td>
        <?php foreach($totalTransaksi as $total): ?>
        <td class="fw-bold text-end"><?= number_format($total,0,',','.'); ?></td>
        <?php endforeach; ?>
    </tr>
    </tbody>
</table>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row ">
                <div class="col">
                <!-- Member Belanja -->
                <div class="card">
                    <div class="card-header bg-warning">
                    <h5>Member Belanja</h5>
                    </div>
                    <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Kategori</th>
                                <th scope="col" class="text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($members->getResultArray() as $member): ?>
                            <tr>
                                <td><?= $member['TIPEMBR']; ?></td>
                                <td class="text-end"><?= number_format($member['JMLMBR'],'0',',','.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <div class="card">
                        <div class="card-header bg-secondary">
                        <h5>Alokasi Faktur Pajak</h5>
                        </div>
                        <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Tahun</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($fakturs->getResultArray() as $faktur): ?>
                                <tr>
                                    <td><?= $faktur['ALK_TAHUNPAJAK']; ?></td>
                                    <td><?= $faktur['STATUS']; ?></td>
                                    <td class="text-end"><?= number_format($faktur['ALOKASI'],'0',',','.'); ?></td>
                                </tr>
                            </tbody>
                            <?php endforeach; ?>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






</div>

    
 
<?= $this->endSection();?>