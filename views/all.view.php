<?php $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2); ?>
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
        <?php foreach ($views['resume'] as $key => $view) : ?>
            <tr>
                <td><?= $view['name'] ?></td>
                <td><?= $view['payed'] ?></td>
                <td><?= $view['unpayed'] ?></td>
                <td><?= $view['advance']  ?></td>
                <td><a class="button alert no-marg" href="<?= '//' . $_SERVER['HTTP_HOST'] . $uri_parts[0] . '?user=' . strtolower($view['id']) ?>">Détails</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td>Total</td>
            <td><?= $views['total']['payed'] ?></td>
            <td><?= $views['total']['unpayed'] ?></td>
            <td><?= $views['total']['advance'] ?></td>
            <td>&nbsp;</td>

        </tr>
    </tfoot>
</table>
