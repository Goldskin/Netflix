<table class="table ">
    <thead class="thead-inverse">
        <tr>
            <th>Label</th>
            <th>Payé</th>
            <th>Restant à payer</th>
            <th>Avance</th>
            <th>Détails</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resume as $key => $view) : ?>
            <tr>
                <td><?= $view['name'] ?></td>
                <td><?= $view['payed'] ?></td>
                <td><?= $view['unpayed'] ?></td>
                <td><?= $view['advance']  ?></td>
                <td><a class="button alert no-marg" href="<?= URL . '/user/' . strtolower($view['id']) ?>">Détails</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>Total</td>
            <td><?= $total['payed'] ?></td>
            <td><?= $total['unpayed'] ?></td>
            <td><?= $total['advance'] ?></td>
            <td>&nbsp;</td>

        </tr>
    </tfoot>
</table>
