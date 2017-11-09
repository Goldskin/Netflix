<?php /**
 *
 */
class Price extends Main
{    
    protected $price = 0; // starting price is 0

    /**
     * add to current price
     * @param [type] $price [description]
     */
    public function add ($price)
    {
        $price = new self;
        if (is_object($price) && get_class($price) == get_called_class()) {
            $this->price += $price->get();
        } else {
            $this->price += $price;
        }
        return $this;
    }

}
