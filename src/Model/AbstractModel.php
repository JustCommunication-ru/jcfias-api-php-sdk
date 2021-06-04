<?php

namespace JustCommunication\JcFIASSDK\Model;

abstract class AbstractModel
{
    public function mapData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}
