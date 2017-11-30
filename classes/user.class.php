<?php

class User extends Main
{

    /**
     * Get all the payements
     * @return int|float
     */
    public function getPayments()
    {
        $payements = $this->getTotals($this->payment());
        return $payements > 0 ? new Price ($payements) : 0;
    }

    /**
     * get all the bills
     * @return int|float
     */
    public function getBills()
    {
        $bills = $this->getTotals($this->bill());
        return $bills > 0 ? new Price ($bills) : 0;
    }

    /**
     * get all the bills
     * @return int|float
     */
    public function getAdvanced()
    {
        $adv = $this->getTotals($this->advanced());
        return $adv > 0 ? new Price ($adv) : 0;
    }

    /**
     * get a specifique bill
     * @return int|float
     */
    public function getBill(Date $Date)
    {
        $return = null;

        Self::each($this->bill(), function ($Bill) use (&$return, $Date)
        {
            if ($Bill->date() == $Date) {
                // $return = $Bill;
                if (is_array($return)) {
                    $return[] = $Bill;
                } else if (!is_null($return)) {
                    $return = [$return, $Bill];
                } else {
                    $return = $Bill;
                }
            }
        });

        return $return;
    }

    /**
     * get all prices
     * @param  object $Objs
     * @return int|mixed price
     */
    public function getTotals($Objs)
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
    public function update()
    {
        if ($this->admin()) {
            $this->payed( (new Price ())->set( $this->getBills()->get() )->status(Price::payed) );
        } else if ($this->payment()) {

            $payements = $this->getPayments()->get();
            $bills     = $this->getBills()->get();

            if ($payements > $bills) {
                $status = Price::advance;
            } else if ($this->getLast('bill') && $bills - $payements >= $this->getLast('bill')->get()){
                $status = Price::unpayed;
            } else if ($payements != $bills) {
                $status = Price::paying;
            } else {
                $status = Price::payed;
            }

            $this->payed( (new Price ())->set($payements)->status($status) );
            $avance  = $payements - $bills;
            $avance  = $avance > 0 ? $avance : 0;
            $unpayed = $bills - $payements;
            $unpayed = $unpayed > 0 ? $unpayed : 0;

            if ($unpayed > 0) {
                if ($unpayed > $this->getLast('bill')->get()) {
                    $status = Price::unpayed;
                } else {
                    $status = Price::paying;
                }
                $this->unpayed( (new Price ())->set($unpayed)->status($status) );
            }

            if ($avance > 0) {
                $this->advance( (new Price ())->set($avance)->status(Price::advance) );
            }

        } else if ($this->getBills()) {
            $this->unpayed( (new Price ())->set($this->getBills()->get())->status(Price::paying) );
        }

        return $this;
    }
}
