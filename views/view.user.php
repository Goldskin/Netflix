<?php $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2); ?>
<?php foreach ($views as $key => $view) : ?>
    <h1 class="text-center"><?= $view['name'] ?></h1>
    <h2 class="text-center">Résumé</h2>
    <a class="button alert" href="<?= '//' . $_SERVER['HTTP_HOST'] . $uri_parts[0] ?>">&larr; Retour</a>
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
                <td><?= $view['payed'] ?></td>
                <td><?= $view['unpayed'] ?></td>
                <td><?= $view['advance']  ?></td>
            </tr>
        </tbody>
    </table>
    <?php if (!empty($view['bill']['line'])): ?>
        <h2 class="text-center">Historique facture</h2>
        <table class="table ">
            <thead class="thead-inverse">
                <tr>
                    <th>Prix</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($view['bill']['line'] as $bill) : ?>
                    <tr>
                        <td><?= $bill['date'] ?></td>
                        <td><?= $bill['price'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td><?= $view['bill']['total'] ?></td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
<?php endforeach; ?>
