<?php if (!empty($bills['line'])): ?>
    <h2 class="text-center">Historique facture</h2>
    <table class="table ">
        <thead class="thead-inverse">
            <tr>
                <th>Date</th>
                <th>Prix</th>
                <th class="text-right">Facture</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bills['line'] as $bill) : ?>
                <tr>
                    <td><?= $bill['date'] ?></td>
                    <td class="<?= $bill['price']['class'] ?>"><?= $bill['price']['value'] ?></td>
                    <td class="text-right"><a class="button alert no-marg" href="<?= $bill['url'] ?>">Facture</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td colspan="2"><?= $bills['total'] ?></td>
            </tr>
        </tfoot>
    </table>
<?php endif; ?>
