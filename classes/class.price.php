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

}
