<table class="table ">
    <thead class="thead-inverse">
        <tr>
            <th>Label</th>
            <th>Payé</th>
            <th>Restant à payer</th>
            <th>Avance</th>
            <th class="text-right">Détails</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resume as $key => $view) : ?>
            <tr>
                <td><?= $view['name'] ?></td>
                <td><?= $view['payed'] ?></td>
                <td><?= $view['unpayed'] ?></td>
                <td><?= $view['advance']  ?></td>
                <td class="text-right"><a class="button alert no-marg text-right" href="<?= $view['url']  ?>">Détails</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>Total</td>
            <td><?= $payed ?></td>
            <td><?= $unpayed ?></td>
            <td><?= $advance ?></td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
</table>
