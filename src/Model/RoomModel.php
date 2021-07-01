<?php

namespace JustCommunication\JcFIASSDK\Model;

class RoomModel extends AbstractModel
{
    /**
     * @var string
     */
    public $roomguid;

    /**
     * @var string
     */
    public $houseguid;

    /**
     * @var string
     */
    public $flatnumber;

    public function __toString()
    {
        return $this->flatnumber;
    }
}
