<?php /**
 *
 */
class Price extends Main
{

    /**
     * add to current price
     * @param [type] $price [description]
     */
    public function getTotal ()
    {
        $val = $this->get();
        if (is_array($val)) {
            return array_sum($val);
        }
        return $val;
    }

}
