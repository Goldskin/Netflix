<h1 class="text-center">Facture du <?= $date ?></h1>
<a class="button alert" href="<?= '//' . $_SERVER['HTTP_HOST'] . WEBROOT ?>">&larr; Retour</a>
<?php if (!empty($lines)): ?>
    <table class="table ">
        <thead class="thead-inverse">
            <tr>
                <th>Date</th>
                <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lines as $line) : ?>
                <tr>
                    <td><?= $line['user'] ?></td>
                    <td><?= $line['bill'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td><?= $total ?></td>
            </tr>
        </tfoot>
    </table>
<?php endif; ?>
