<?php foreach ($views as $key => $view) : ?>
    <h1 class="text-center"><?= $view['name'] ?></h1>
    <h2 class="text-center">Résumé</h2>
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
    <?php if (!empty($view['bill'])): ?>
        <h2 class="text-center">Historique facture</h2>
        <table class="table ">
            <thead class="thead-inverse">
                <tr>
                    <th>Prix</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($view['bill'] as $key => $bill) : ?>
                    <tr>
                        <td><?= $bill['price'] ?></td>
                        <td><?= $bill['date'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php endforeach; ?>
