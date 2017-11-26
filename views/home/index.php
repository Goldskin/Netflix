<table class="table ">
    <thead class="thead-inverse">
        <tr>
            <th>Label</th>
            <th>Facturé</th>
            <th>Payé</th>
            <th>Restant à payer</th>
            <th>Avance</th>
            <th class="text-right">Détails</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user['name'] ?></td>
                <td class="<?= $user['billed']['class'] ?>"><?= $user['billed']['value'] ?> </td>
                <td class="<?= $user['payed']['class'] ?>"><?= $user['payed']['value'] ?></td>
                <td class="<?= $user['unpayed']['class'] ?>"><?= $user['unpayed']['value'] ?></td>
                <td class="<?= $user['advance']['class'] ?>"><?= $user['advance']['value']  ?></td>
                <td class="text-right"><a class="button alert no-marg text-right" href="<?= $user['url']  ?>">Détails</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>Total</td>
            <td><?= $billed ?></td>
            <td><?= $payed ?></td>
            <td class="unpayed"><?= $unpayed ?></td>
            <td colspan="2" class="payed"><?= $advance ?></td>
        </tr>
    </tfoot>
</table>
