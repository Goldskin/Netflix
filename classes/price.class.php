<?php

class Price extends Main
{
    const unpayed  = 0;
    const paying   = 1;
    const payed    = 2;
    const advance = 3;

    /**
     * round up or down the current price
     * @param  int|float $difference price
     * @return int                   price
     */
    static function rounder ($difference)
    {
        if ($difference > 0) {
            $difference = ceil( ($difference) * 100) / 100;
        } else {
            $difference = floor( ($difference) * 100) / 100;
        }
        return $difference;
    }

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
        return number_format ( $this->total() , 2 ,  "," ," " ) . ' ' . $currency;
    }

    /**
     * split current price the most even possible
     * @param  [type] $split [description]
     * @return [type]        [description]
     */
    public function split ($split)
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
        $difference = round($difference * 100) / 100;


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
    static public function getStatus ($code)
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
