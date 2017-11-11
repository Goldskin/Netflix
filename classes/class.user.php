<?php /**
 *
 */
class User extends Main
{

    /**
     * Get all the payements
     * @return int|float
     */
    public function getPayments ()
    {
        return $this->getTotals($this->payment());
    }

    /**
     * get all the bills
     * @return int|float
     */
    public function getBills ()
    {
        return $this->getTotals($this->bill());
    }

    /**
     * get all prices
     * @param  Obj $Objs
     * @return int|mixed price
     */
    public function getTotals ($Objs) {
        $Prices = new Price();

        Self::each($Objs, function ($Obj) use (&$Prices) {
            $Prices->set($Obj->price()->get());
        });

        return $Prices->total();
    }


    public function update () {
        if ($this->getBills() > 0) {
            if ($this->admin()) {
                $this->payment( (new Payment ())->price( $this->getBills() ) );
            }
            if ($this->payment()) {
                $avance  = $this->getPayments() - $this->getBills();
                $avance  = $avance > 0 ? $avance : 0;
                $unpayed = $this->getBills() - $this->getPayments();
                $unpayed = $unpayed > 0 ? $unpayed : 0;

                $this->unpayed( (new Unpayed ())->price($unpayed) );
                if ($avance > 0) {
                    $this->advance( (new Advance ())->price($avance) );
                }
            } else {
                $this->unpayed( (new Unpayed ())->price($this->getBills()) );
            }
        }
    }
}
