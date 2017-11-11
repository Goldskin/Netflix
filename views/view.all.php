<?php $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2); ?>
<table class="table ">
    <thead class="thead-inverse">
        <tr>
            <th>Personnes</th>
            <th>Payé</th>
            <th>Restant à payer</th>
            <th>Avance</th>
            <th>Détails</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($views as $key => $view) : ?>
            <tr>
                <td><?= $view['name'] ?></td>
                <td><?= $view['payed'] ?></td>
                <td><?= $view['unpayed'] ?></td>
                <td><?= $view['advance']  ?></td>
                <td><a href="<?= '//' . $_SERVER['HTTP_HOST'] . $uri_parts[0] . '?user=' . strtolower($view['name']) ?>">Détail</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
