<h1 class="text-center">Facture du <?= $date ?></h1>
<a class="button alert" href="<?= '//' . $_SERVER['HTTP_HOST'] . WEBROOT ?>">&larr; Retour</a>
<a class="button alert" href="<?= '//www.netflix.com' ?>">Aller sur Netflix</a>
<?php if (!empty($lines)): ?>
    <table class="table ">
        <thead class="thead-inverse">
            <tr>
                <th>Utilisateur</th>
                <th>Prix</th>
                <th class="text-right">Détails</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lines as $line) : ?>
                <tr>
                    <td><?= $line['user'] ?></td>
                    <td class="<?= $line['price']['class'] ?>"><?= $line['price']['value'] ?></td>
                    <td class="text-right"><a class="button alert no-marg" href="<?= $line['url'] ?>">Utilisateur</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td colspan="2"><?= $total ?></td>
            </tr>
        </tfoot>
    </table>
<?php endif; ?>
