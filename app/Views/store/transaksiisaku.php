<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<?php $now = date('Y-m-d'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card mt-3">
                <div class="card-header bg-warning-subtle">
                    <h6 class="text-center fw-bolder text-primary">Transaksi I-Saku</h6>
                </div>
                <div class="card-body">
                    <form action="transaksiisaku" method="get">
                        <?php csrf_field(); ?>
                        <label for="tglawal" class="fw-bold mb-1">TGL Transaksi :</label>
                        <input class="d-block mb-4 w-100" type="date" name="tglawal" id="tglawal" value="<?= $now; ?>">
                        <button type="submit" class="btn btn-success w-100 d-block mb-1" name="btn" value="cashin" id="btnCashin">Cash In</button>
                        <button type="submit" class="btn btn-danger w-100 d-block mb-1" name="btn" value="cashout" id="btnCashout">Cash Out</button>
                        <button type="submit" class="btn btn-primary w-100 d-block mb-1" name="btn" value="purchase" id="btnPurchase">Purchase</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card mt-3">
                <div class="card-header bg-primary-subtle">
                    <h6 class="text-primary">Data Transaksi i-Saku</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-responsive">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>KASSA</th>
                                <th>ID</th>
                                <th>KASIR</th>
                                <th>TIPE</th>
                                <th>NO_TRANSAKSI</th>
                                <th>AMOUNT</th>
                                <th>FEE</th>
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($cashin)): ?>
                                <?php 
                                $i=1;
                                $totalamount = 0;
                                $totalfee = 0;
                                $grandtotal = 0; 
                                ?>
                                <?php foreach($cashin as $ci): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $ci['KASA']; ?></td>
                                <td><?= $ci['ID_KASIR']; ?></td>
                                <td><?= $ci['NAMAKASIR']; ?></td>
                                <td><?= $ci['TRANS']; ?></td>
                                <td><?= $ci['NO_TRANSAKSI']; ?></td>
                                <td><?= number_format($ci['AMOUNT']) ; ?></td>
                                <td><?= number_format($ci['FEE']) ; ?></td>
                                <td><?= number_format($ci['TOTAL']); ?></td>
                                <?php 
                                $totalamount += $ci['AMOUNT']; 
                                $totalfee += $ci['FEE'];
                                $grandtotal += $ci['TOTAL'];
                                ?>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="fw-bold"><?= number_format($totalamount); ?></td>
                                <td class="fw-bold"><?= number_format($totalfee) ; ?></td>
                                <td class="fw-bold"><?= number_format($grandtotal) ; ?></td>
                            </tr>
                            <?php elseif(!empty($cashout)): ?>
                                <?php 
                                $i = 1;
                                $totalamount = 0;
                                $totalfee = 0;
                                $grandtotal = 0; 
                                ?>
                                <?php foreach($cashout as $co): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $co['KASA']; ?></td>
                                <td><?= $co['ID_KASIR']; ?></td>
                                <td><?= $co['NAMAKASIR']; ?></td>
                                <td><?= $co['TRANS']; ?></td>
                                <td><?= $co['NO_TRANSAKSI']; ?></td>
                                <td><?= number_format($co['AMOUNT']) ; ?></td>
                                <td><?= number_format($co['FEE']); ?></td>
                                <td><?= number_format($co['TOTAL']) ; ?></td>
                                <?php 
                                $totalamount += $co['AMOUNT']; 
                                $totalfee += $co['FEE'];
                                $grandtotal += $co['TOTAL'];
                                ?>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="fw-bold"><?= number_format($totalamount) ; ?></td>
                                <td class="fw-bold"><?= number_format($totalfee) ; ?></td>
                                <td class="fw-bold"><?= number_format($grandtotal); ?></td>
                            </tr>
                            <?php elseif(!empty($purchase)): ?>
                                <?php 
                                $totalamount = 0;
                                $totalfee = 0;
                                $grandtotal = 0; 
                                $i = 1;
                                ?>
                                <?php foreach($purchase as $pur): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $pur['KASA']; ?></td>
                                <td><?= $pur['ID_KASIR']; ?></td>
                                <td><?= $pur['NAMAKASIR']; ?></td>
                                <td><?= $pur['TRANS']; ?></td>
                                <td><?= $pur['NO_TRANSAKSI']; ?></td>
                                <td><?= number_format($pur['AMOUNT']) ; ?></td>
                                <td><?= number_format($pur['FEE']); ?></td>
                                <td><?= number_format($pur['TOTAL']); ?></td>
                                <?php 
                                $totalamount += $pur['AMOUNT']; 
                                $totalfee += $pur['FEE'];
                                $grandtotal += $pur['TOTAL'];
                                ?>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td></td>
                                <td class="fw-bold">Total</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="fw-bold"><?= number_format($totalamount) ; ?></td>
                                <td class="fw-bold"><?= number_format($totalfee) ; ?></td>
                                <td class="fw-bold"><?= number_format($grandtotal); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>