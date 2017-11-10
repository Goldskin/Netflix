<?php /**
 *
 */
class User extends Main
{

    protected $name;

    /**
     * GetPayents
     * @return Price
     */
    public function getPayments ()
    {
        $Price = new Price;

        foreach ($this->payments as $payment)
            $Price->add($payment);

        return $Price;
    }

    /**
     * setName
     */
    public function name (string $name)
    {
        $this->name = $name;
        return $this;
    }

}
