<?php if (!empty($invoices['line'])): ?>
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
            <?php foreach ($invoices['line'] as $invoice) : ?>
                <tr>
                    <td><?= $invoice['date'] ?></td>
                    <td class="<?= $invoice['price']['class'] ?>"><?= $invoice['price']['value'] ?></td>
                    <td class="text-right"><a class="button main-color no-marg" href="<?= $invoice['url'] ?>">Facture</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td colspan="2"><?= $invoices['total'] ?></td>
            </tr>
        </tfoot>
    </table>
<?php endif; ?>
