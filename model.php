<?php 
require_once 'class.main.php';
/**
 * get model
 * @return Service get netflix
 */
function model () {
    $file    = file_get_contents('./user.json');
    $loutres = json_decode($file);
    $file    = file_get_contents('./price.json');
    $prices  = json_decode($file);
    
    
    $Service = new Service ();
    foreach ($loutres as $Data) {
        $User = (new User ())->name($Data->name);
    
        // determine les utilisations
        if (isset($Data->use)) {
            foreach ($Data->use as $Use) {
                $Interval = (new Interval ())->start( $Use->start );
                
                if (isset($Use->end)) {
                    $Interval->end( $Use->end );
                }
                
                $User->interval($Interval);
            }
        }
    
        // determine les utilisations
        if (isset($Data->payed)) {
            foreach ($Data->payed as $Payment) {
                $User->payment( (new Payment ())->price( $Payment->price )->date( $Payment->date ) );
            }
        }
        
        $Service->user($User);
    }
    
    // determine les tarif de netflix
    foreach ($prices as $Data) {
        $Interval = (new Interval ())->start($Data->start);
        
        if (isset($Data->end)) {
            $Interval->end($Data->end);
        }
        
        $Tarif = (new Tarif ())->price( $Data->price )->interval( $Interval );
        
        $Service->tarif($Tarif);
    }
    
    return $Service;
}
