<?php

namespace JustCommunication\JcFIASSDK\Model;

class HouseModel extends AbstractModel
{
    /**
     * @var string
     */
    public $houseguid;

    /**
     * @var string
     */
    public $aoguid;

    /**
     * @var string
     */
    public $housenum;

    /**
     * @var string
     */
    public $buildnum;

    /**
     * @var string
     */
    public $strucnum;

    public function getFullNumber()
    {
        $result = [];

        if ($this->housenum) $result[] = $this->housenum;
        if ($this->buildnum) $result[] = 'к' . $this->buildnum;
        if ($this->strucnum) $result[] = 'с' . $this->strucnum;

        return join('', $result);
    }

    public function __toString()
    {
        return $this->getFullNumber();
    }
}
