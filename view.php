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

                        <?php Main::each($Netflix->user(), function ($User) { ?>
                            <tr>
                                <td><?= is_null($User->name()->get()) ?'': $User->name()->get() ?></td>
                                <td><?= is_null($User->getPayments()) ?'': $User->getPayments() ?></td>
                                <td><?= is_null($User->unpayed()) ?'': $User->unpayed()->price()->get() ?></td>
                                <td><?= is_null($User->advance()) ?'': $User->advance()->price()->get()  ?></td>
                            </tr>
                        <?php }); ?>
                    </tbody>
                </table>
            </div>
        </div>

    </body>
</html>