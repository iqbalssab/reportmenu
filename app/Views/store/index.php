
<?= $this->extend('layout/template');?>

<?= $this->section('content'); ?>
    <h1>Store Menu</h1>
<div class="container">
    <table class="table">
  <thead>
      <tr>
          <th scope="col">#</th>
          <th scope="col">Username</th>
          <th scope="col">Kassa</th>
          <th scope="col">Total Transaksi</th>
          <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>
      <?php $i=1; ?>
     
    <tr>
      <th scope="row"><?= $i++; ?></th>
      
    </tr>
  </tbody>
</table>
</div>
    
 
<?= $this->endSection();?>