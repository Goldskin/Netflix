<h1 class="text-center"><?= $user['name'] ?></h1>
<h2 class="text-center">Résumé</h2>
<a class="button alert" href="<?= '//' . $_SERVER['HTTP_HOST'] . WEBROOT ?>">&larr; Retour</a>
<table class="table">
    <thead class="thead-inverse">
        <tr>
            <th>Payé</th>
            <th>Restant à payer</th>
            <th>Avance</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $user['payed'] ?></td>
            <td><?= $user['unpayed'] ?></td>
            <td><?= $user['advance']  ?></td>
        </tr>
    </tbody>
</table>
<?php if (!empty($user['bill']['line'])): ?>
    <h2 class="text-center">Historique facture</h2>
    <table class="table ">
        <thead class="thead-inverse">
            <tr>
                <th>Date</th>
                <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user['bill']['line'] as $bill) : ?>
                <tr>
                    <td><?= $bill['date'] ?></td>
                    <td><?= $bill['price'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td><?= $user['bill']['total'] ?></td>
            </tr>
        </tfoot>
    </table>
<?php endif; ?>
