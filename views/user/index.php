<table class="table">
    <thead class="thead-inverse">
        <tr>
            <th>Facturé</th>
            <th>Payé</th>
            <th>Restant à payer</th>
            <th>Avance</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="<?= $user['billed']['class'] ?>"><?= $user['billed']['value'] ?></td>
            <td class="<?= $user['payed']['class'] ?>"><?= $user['payed']['value'] ?></td>
            <td class="<?= $user['unpayed']['class'] ?>"><?= $user['unpayed']['value'] ?></td>
            <td class="<?= $user['advance']['class'] ?>"><?= $user['advance']['value'] ?></td>
        </tr>
    </tbody>
</table>
