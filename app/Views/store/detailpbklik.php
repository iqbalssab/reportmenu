<style>
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
                font-weight:bold;
                font-size:14px;
            }
            td{
                padding:2px 5px;
                font-size:14px;
                text-overflow: ellipsis;
            }
        </style>

<div>
    <h4><?= $judul; ?></h4>
    <?php if(!empty($detail)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>PLU</th>
                    <th>Deskripsi</th>
                    <th>Frac</th>
                    <th>QtyOrder</th>
                    <th>QtyPicking</th>
                    <th>QtySales</th>
                    <th>RphOrder</th>
                    <th>RphPicking</th>
                    <th>RphSales</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($detail as $dtl): ?>
                    <tr>
                        <td><?= $dtl['PLUORDER']; ?></td>
                        <td><?= $dtl['DESKRIPSI']; ?></td>
                        <td><?= $dtl['FRAC']; ?></td>
                        <td><?= $dtl['QTY_ORDER']; ?></td>
                        <td><?= $dtl['QTY_PICKING']; ?></td>
                        <td><?= $dtl['QTY_REALISASI']; ?></td>
                        <td><?= $dtl['RPH_ORDER']; ?></td>
                        <td><?= $dtl['RPH_PICKING']; ?></td>
                        <td><?= $dtl['RPH_REALISASI']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

