<?php /**
 * 
 */
class Personne
{
    public $start;
    public $payed;
    public $total;
    public $since;
    function __construct($dateTime)
    {
        $this->start = $dateTime;
        echo '<pre>', var_dump( $this->start ), '</pre>';
        echo '<pre>', var_dump( $this->payed ), '</pre>';
    }
    
    
}
?>
