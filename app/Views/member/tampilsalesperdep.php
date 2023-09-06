<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<h3>Monitoring Sales <?= $tglawal; ?> s/d <?= $tglakhir; ?></h3>
<h3>Departement : <?=  $departement; ?></h3>

<h5>Sales <?= $jenissales; ?></h5>

<table class="table table-responsive table-bordered">
    <thead>
        <tr>
            <th class="bg-primary text-light">DIV</th>
            <th class="bg-primary text-light">DEP</th>
            <th class="bg-primary text-light">KAT</th>
            <th class="bg-primary text-light">PLU</th>
            <th class="bg-primary text-light">DESKRIPSI</th>
            <th class="bg-primary text-light">UNIT</th>
            <th class="bg-primary text-light">FRAC</th>
            <th class="bg-primary text-light">TAG</th>
            <th class="bg-primary text-light">01</th>
            <th class="bg-primary text-light">02</th>
            <th class="bg-primary text-light">03</th>
            <th class="bg-primary text-light">04</th>
            <th class="bg-primary text-light">05</th>
            <th class="bg-primary text-light">06</th>
            <th class="bg-primary text-light">07</th>
            <th class="bg-primary text-light">08</th>
            <th class="bg-primary text-light">09</th>
            <th class="bg-primary text-light">10</th>
            <th class="bg-primary text-light">11</th>
            <th class="bg-primary text-light">12</th>
            <th class="bg-primary text-light">13</th>
            <th class="bg-primary text-light">14</th>
            <th class="bg-primary text-light">15</th>
            <th class="bg-primary text-light">16</th>
            <th class="bg-primary text-light">17</th>
            <th class="bg-primary text-light">18</th>
            <th class="bg-primary text-light">19</th>
            <th class="bg-primary text-light">20</th>
            <th class="bg-primary text-light">21</th>
            <th class="bg-primary text-light">22</th>
            <th class="bg-primary text-light">23</th>
            <th class="bg-primary text-light">24</th>
            <th class="bg-primary text-light">25</th>
            <th class="bg-primary text-light">26</th>
            <th class="bg-primary text-light">27</th>
            <th class="bg-primary text-light">28</th>
            <th class="bg-primary text-light">29</th>
            <th class="bg-primary text-light">30</th>
            <th class="bg-primary text-light">31</th>
            <th class="bg-primary text-light">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($datas as $data): ?>
        <tr>
            <td><?= $data['DIV']; ?></td>
            <td><?= $data['DEP']; ?></td>
            <td><?= $data['KAT']; ?></td>
            <td><?= $data['PLU']; ?></td>
            <td><?= $data['DESKRIPSI']; ?></td>
            <td><?= $data['UNIT']; ?></td>
            <td><?= $data['FRAC']; ?></td>
            <td><?= $data['TAG']; ?></td>
            <td><?= number_format($data['T01']); ?></td>
            <td><?= number_format($data['T02']); ?></td>
            <td><?= number_format($data['T03']); ?></td>
            <td><?= number_format($data['T04']); ?></td>
            <td><?= number_format($data['T05']); ?></td>
            <td><?= number_format($data['T06']); ?></td>
            <td><?= number_format($data['T07']); ?></td>
            <td><?= number_format($data['T08']); ?></td>
            <td><?= number_format($data['T09']); ?></td>
            <td><?= number_format($data['T10']); ?></td>
            <td><?= number_format($data['T11']); ?></td>
            <td><?= number_format($data['T12']); ?></td>
            <td><?= number_format($data['T13']); ?></td>
            <td><?= number_format($data['T14']); ?></td>
            <td><?= number_format($data['T15']); ?></td>
            <td><?= number_format($data['T16']); ?></td>
            <td><?= number_format($data['T17']); ?></td>
            <td><?= number_format($data['T18']); ?></td>
            <td><?= number_format($data['T19']); ?></td>
            <td><?= number_format($data['T20']); ?></td>
            <td><?= number_format($data['T21']); ?></td>
            <td><?= number_format($data['T22']); ?></td>
            <td><?= number_format($data['T23']); ?></td>
            <td><?= number_format($data['T24']); ?></td>
            <td><?= number_format($data['T25']); ?></td>
            <td><?= number_format($data['T26']); ?></td>
            <td><?= number_format($data['T27']); ?></td>
            <td><?= number_format($data['T28']); ?></td>
            <td><?= number_format($data['T29']); ?></td>
            <td><?= number_format($data['T30']); ?></td>
            <td><?= number_format($data['T31']); ?></td>
            <td><?= number_format($data['TOTAL']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $this->endSection(); ?>