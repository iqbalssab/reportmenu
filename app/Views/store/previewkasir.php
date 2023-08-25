
<?= $this->extend('layout/template');?>

<?= $this->section('content'); ?>
                <?php 
                $tanggal = date('d-m-Y');
                date_default_timezone_set("Asia/Jakarta"); 
                $waktu = date('H:i:s');
                ?>
<div class="container-fluid mt-2 mx-auto fs-6 text-small">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success">
                <h5 class="text-center text-light fw-bold">Preview Transaksi Kasir || <?= $tanggal; ?> - <?= $waktu; ?></h5>
                <?php $ip = "http://".$_SERVER['SERVER_NAME']."/reportmenu/"; ?>
                <p><?= $ip." DAN ". base_url(); ?></p>
                </div>
                <div class="card-body">
                <table class="table table-sm mb-3">
    <thead>
      <tr>
          <th scope="col">ID</th>
          <th scope="col">Nama Kasir</th>
          <th scope="col">Kassa</th>
          <th scope="col">Total Transaksi</th>
          <th scope="col">Tunai</th>
          <th scope="col">K Debit</th>
          <th scope="col">K Kredit</th>
          <th scope="col">Kredit</th>
          <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
      <?php $i=1;?>
      <?php foreach($transactions as $transaksi): ?>
    <tr>
      <th scope="row"><?= $transaksi['ID_KASIR'] ?></th>
      <td><?= $transaksi['NAMA_KASIR']; ?></td> 
      <td><?= $transaksi['KASSA']; ?></td>
      <td>Rp. <?= number_format($transaksi['TOTAL_TRANSAKSI'],'0',',','.') ; ?></td>
      <td>Rp. <?= number_format($transaksi['TUNAI'],'0',',','.') ; ?></td>
      <td><?= $transaksi['KDEBIT']; ?></td>
      <td><?= $transaksi['KKREDIT']; ?></td>
      <td><?= $transaksi['KREDIT']; ?></td>
      <td>
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
        <td class="fw-bold">Rp. <?= number_format($total,0,',','.'); ?></td>
        <?php endforeach; ?>
    </tr>
    </tbody>
</table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
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
                                <th scope="col">Kategori</th>
                                <th scope="col">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($members->getResultArray() as $member): ?>
                            <tr>
                                <td><?= $member['TIPEMBR']; ?></td>
                                <td><?= $member['JMLMBR']; ?></td>
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
                                    <th scope="col">Tahun</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($fakturs->getResultArray() as $faktur): ?>
                                <tr>
                                    <td><?= $faktur['ALK_TAHUNPAJAK']; ?></td>
                                    <td><?= $faktur['STATUS']; ?></td>
                                    <td><?= $faktur['ALOKASI']; ?></td>
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