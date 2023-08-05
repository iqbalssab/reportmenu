
<?= $this->extend('layout/template');?>

<?= $this->section('content'); ?>
    <h1>Preview Transaksi Kasir</h1>
<div class="container">
    <table class="table mb-3">
  <thead>
      <tr>
          <th scope="col">#</th>
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
      <?php $i=1; ?>
      <?php foreach($transactions->getResultArray() as $transaksi): ?>
    <tr>
      <th scope="row"><?= $i++; ?></th>
      <td><?= $transaksi['NAMA_KASIR']; ?></td> 
      <td><?= $transaksi['KASSA']; ?></td>
      <td>Rp. <?= number_format($transaksi['TOTAL_TRANSAKSI'],'0',',','.') ; ?></td>
      <td>Rp. <?= number_format($transaksi['TUNAI'],'0',',','.') ; ?></td>
      <td><?= $transaksi['KDEBIT']; ?></td>
      <td><?= $transaksi['KKREDIT']; ?></td>
      <td><?= $transaksi['KREDIT']; ?></td>
      <td><?= $transaksi['STATUS']; ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<h2>Member Belanja</h2>
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

<h2>Alokasi Faktur Pajak</h2>
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

    
 
<?= $this->endSection();?>