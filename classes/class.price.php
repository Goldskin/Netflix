<?php

class Price extends Main
{

    /**
     * add to current price
     * @return mixed
     */
    public function total ()
    {
        $val = $this->get();
        if (is_array($val)) {
            return array_sum($val);
        }
        return $val;
    }

    /**
     * format price to current currency
     * @param  string $currency code html
     * @return string           price
     */
    public function format ($currency = '&euro;')
    {
        return number_format ( $this->get() , 2 ,  "," ," " ) . ' ' . $currency;
    }

}
