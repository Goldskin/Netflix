<?php
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

require_once 'class.main.php';

$file = file_get_contents('./data.json');
$loutres = json_decode($file);


$users = [];
foreach ($loutres as $data) {
    $User = (new User ())->name($data->name);

    // determine les utilisations
    if (isset($data->use)) {
        foreach ($data->use as $use) {
            $Interval = (new Interval ())->start($use[0]);
            if (isset($use[1])) $Interval->end($use[1]);
            $User->interval($Interval);
        }
    }

    // determine les utilisations
    if (isset($data->payed)) {
        foreach ($data->payed as $payed) {
            $User->payment( (new Payment ())->price($payed[0])->date($payed[1]) );
        }
    }
    $users[] = $User;
}

$netflix = [
    (new Tarif ())->price( 11.99 )->interval( (new Interval ())->end('2018-11-24') ),
    (new Tarif ())->price( 13.99 )->interval( (new Interval ())->start('2018-11-25') )
];

echo '<pre>', var_dump( $users ), '</pre>';
echo '<pre>', var_dump( $netflix ), '</pre>';
