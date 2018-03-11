<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $titles['page'] ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="https:cdn.jsdelivr.net/foundation/6.2.4/foundation.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
        <link rel="stylesheet" href="<?= $options['css'] ?>">
        <meta name="theme-color" content="#141414" />
    </head>
    <body class="container">
        <div class="row">
            <div class="columns">
                <a href="<?= URL ?>"><img src="<?= $options['logo'] ?>" alt="Netflix"></a>
            </div>
            <div class="columns">
                <?php if (isset($titles['header1'])): ?><h1 class="text-center"><?= $titles['header1'] ?></h1><?php endif; ?>
                <?php if (isset($titles['header2'])): ?><h2 class="text-center"><?= $titles['header2'] ?></h2><?php endif; ?>
                <?php if (isset($options['back'])): ?><a class="button main-color" href="<?= $options['back'] ?>">&larr; Retour</a><?php endif; ?>
                <a class="button main-color" href="<?= $options['url'] ?>">Aller sur <?= $options['name'] ?></a>
