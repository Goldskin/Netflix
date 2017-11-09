<?php /**
 *
 */
class Price extends Main
{

    public function set ($price) {
        $this->price = $price;
    }

    public function add ($price)
    {
        $price = new self;
        if (is_object($price) && get_class($price) == get_called_class()) {
            $this->price += $price->GetPrice();
        } else {
            $this->price += $price;
        }
        return $this;
    }

    public function getPrice ()
    {
        return $this->price;
    }

}
