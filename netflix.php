<?php
ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

require_once 'class.main.php';
$debut = new DateTime('2017-07-28');
$today = new DateTime();

$loutres = [
    'Charles' => [
        'use' => [
            [$debut, '2017-12-28'],
            [$debut]
        ]
    ],
    'Régis' => [
        'start' => $debut,
        'payed' => [
            '24/09/2017' => 44.99
        ]
    ],
    'Gabriel' => [
        'start' => new DateTime('2018-01-28')
    ]
];

$users = [];
foreach ($loutres as $name => $data) {
    $User = (new User())->name($name);

    // determine les utilisations
    if (isset($data['use'])) {
        foreach ($data['use'] as $use) {
            $Interval = (new Interval)->start($use[0]);
            if (isset($use[1])) $Interval->end($use[1]);
            $User->interval($Interval);
        }
    }

    // determine les utilisations
    if (isset($data['payed'])) {
        foreach ($data['payed'] as $date => $payed) {
            $Payment = (new Payment)->price($payed)->date($date);
            $User->payment($Payment);
        }
    }
    $users[] = $User;
}

$netflix = [
    (new Tarif)->price(11.99)->interval( (new Interval)->End('2018-11-24') ),
    (new Tarif)->price(13.99)->interval( (new Interval)->Start('2018-11-25') )
];
