<?php

class User extends Main
{

    /**
     * Get all the payements
     * @return int|float
     */
    public function getPayments()
    {
        $payements = $this->getTotals('payment');
        $returnPrice = $payements > 0 ? $payements : 0;
        return (new Price ())->set($returnPrice);
    }

    /**
     * get all the bills
     * @return int|float
     */
    public function getInvoices()
    {
        $bills = $this->getTotals('invoice');

        $returnPrice = $bills > 0 ? $bills : 0;
        return (new Price ())->set($returnPrice);
    }

    /**
     * get all the bills
     * @return int|float
     */
    public function getAdvanced()
    {
        $adv = $this->getTotals('advanced');
        $returnPrice = $adv > 0 ? $adv : 0;
        return (new Price ())->set($returnPrice);
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
     * @param  string $var
     * @return int|mixed price
     */
    public function getTotals($var)
    {
        $Prices = new Price();
        Self::each($this->{$var}(), function ($Obj) use (&$Prices, $var) {
            $Prices->{$var}($Obj);
        });

        return $Prices->total($var);
    }

    /**
     * update service
     * @return void
     */
    public function update()
    {
        if ($this->admin()) {
            $this->payed( (new Price ())->set( $this->getInvoices()->get() )->status(Price::payed) );
        } else if ($this->payment()) {

            $payements = $this->getPayments()->get();
            $bills     = $this->getInvoices()->get();

            if ($payements > $bills) {
                $status = Price::advance;
            } else if ($this->getLast('invoice') && $bills - $payements >= $this->getLast('invoice')->get()){
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
                if ($unpayed > $this->getLast('invoice')->get()) {
                    $status = Price::unpayed;
                } else {
                    $status = Price::paying;
                }
                $this->unpayed( (new Price ())->set($unpayed)->status($status) );
            }

            if ($avance > 0) {
                $this->advance( (new Price ())->set($avance)->status(Price::advance) );
            }

        } else if ($this->getInvoices()) {
            $this->unpayed( (new Price ())->set($this->getInvoices()->get())->status(Price::unpayed) );
        }

        return $this;
    }
}
