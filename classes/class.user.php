<?php

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
     * @param  object $Objs
     * @return int|mixed price
     */
    public function getTotals ($Objs)
    {
        $Prices = new Price();

        Self::each($Objs, function ($Obj) use (&$Prices) {
            $Prices->set($Obj->get());
        });

        return $Prices->total();
    }

    /**
     * update service
     * @return void
     */
    public function update ()
    {
        if ($this->getBills() > 0) {
            if ($this->admin()) {
                $this->payed( (new Price ())->set( $this->getBills() ) );
            } else if ($this->payment()) {

                $this->payed( (new Price ())->set( $this->getPayments() ) );
                $avance  = $this->getPayments() - $this->getBills();
                $avance  = $avance > 0 ? $avance : 0;
                $unpayed = $this->getBills() - $this->getPayments();
                $unpayed = $unpayed > 0 ? $unpayed : 0;

                if ($unpayed > 0) {
                    $this->unpayed( (new Price ())->set($unpayed) );
                }
                if ($avance > 0) {
                    $this->advance( (new Price ())->set($avance) );
                }
            } else {
                $this->unpayed( (new Price ())->set($this->getBills()) );
            }
        }
    }
}
