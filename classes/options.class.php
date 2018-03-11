<?php

class Options extends Main
{
    /**
     * add options to your service
     * @param  array $options options
     * @return object
     */
    public function createOptions($options)
    {
        $options = get_object_vars($options);
        foreach ($options as $key => $value) {
            $this->{$key}($value);
        }
        return $this;
    }
}
