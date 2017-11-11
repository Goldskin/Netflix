<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Netflix</title>
        <link rel="stylesheet" href="https:cdn.jsdelivr.net/foundation/6.2.4/foundation.min.css">
        <style media="screen">
            img {
                max-width: 400px;
                display: block;
                margin: 50px auto;
            }
            h1 {
                font-size: 2rem;
                text-align: center;
            }
        </style>
    </head>
    <body class="container">
        <div class="row">
            <div class="columns">
                <img src="https:cdn.worldvectorlogo.com/logos/netflix-2.svg" alt="Netflix">
            </div>
            <div class="columns">
                <table class="table ">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Personnes</th>
                            <th>Payé</th>
                            <th>Restant Ã  payer</th>
                            <th>Avance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($views as $key => $view) : ?>
                            <tr>
                                <td><?= $view['name'] ?></td>
                                <td><?= $view['payed'] ?></td>
                                <td><?= $view['unpayed'] ?></td>
                                <td><?= $view['advance']  ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>