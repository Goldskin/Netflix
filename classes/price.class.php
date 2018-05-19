<?php

class Price extends Main
{
    const unpayed  = 0;
    const paying   = 1;
    const payed    = 2;
    const advance  = 3;

    /**
     * round up or down the current price
     * @param  int|float $difference price
     * @return int                   price
     */
    static function rounder($price)
    {
        if ($price > 0) {
            $price = ceil($price);
        } else {
            $price = floor($price);
        }
        return (int) $price;
    }

    /**
     * transform current price to int
     * @param float|int $price price given
     * @return int
     */
    static function toInt($price) {
        return (int) ($price * 100);
    }

    /**
     * transform current price to float
     * @param float|int $price price given
     * @return float
     */
    static function toFloat($price) {
        return (float) ($price / 100);
    }

    /**
     * add to current price
     * @return mixed
     */
    public function total($var = null)
    {
        $total = 0;
        
        $val = $this->get($var);
        
        while(is_object($val)) {
            $val = $val->get($var);
        }
        if (is_array($val)) {
            foreach($val as $price) {
                $total += $price->get(); 
            }
            return $total;
        }
        return $val;
    }

    /**
     * format price to current currency
     * @param  string $currency code html
     * @return string           price
     */
    public function format($currency = '&euro;')
    {
        $price = Self::toFloat($this->total());
        return number_format($price, 2 , ",", " ") . '&nbsp;' . $currency;
    }

    /**
     * split current price the most evenly possible
     * @param  int $split split price
     * @return array      [description]
     */
    public function split($split)
    {
        // get approximative price and apply to each one
        $repartitionAprox = Self::rounder($this->get() / $split);

        // add for each users the approximative price
        $userRepartition = [];
        for ($i = 0; $i < $split; $i++) {
            $userRepartition[$i] = Self::rounder($repartitionAprox);
        }

        // get the difference between real price and approximative price
        $difference = $this->get() - array_sum($userRepartition);



        // correct the the approximative price
        if ($difference < 0) {
            for ($i = $split - 1; $i >= 0; $i--) {
                // get the price difference for the current user
                $numberNeeded = Self::rounder($difference / $split);

                // subtrack to the difference
                $difference -= $numberNeeded;

                // add or subtrack the difference
                $userRepartition[$i] += $numberNeeded;
            }
        } else {
            for ($i = 0; $i < $split; $i++) {
                // get the price difference for the current user
                $numberNeeded = Self::rounder($difference / $split);

                // subtrack to the difference
                $difference -= $numberNeeded;

                // add or subtrack the difference
                $userRepartition[$i] += $numberNeeded;
            }
        }

        ksort($userRepartition);

        return $userRepartition;
    }

    /**
     * format to specific class
     * @var [type]
     */
    static public function getStatus($code)
    {
        switch ($code) {
            case Price::advance:
                return 'advance';
                break;
            case Price::payed:
                return 'payed';
                break;
            case Price::paying:
                return 'paying';
                break;
            case Price::unpayed:
                return 'unpayed';
                break;
            default:
                return '';
                break;
        }
    }

}
