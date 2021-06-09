<?php

namespace JustCommunication\JcFIASSDK\API;

class TownsResponse extends AbstractResponse
{
    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @inheritDoc
     */
    public function setResponseData(array $data)
    {
        if (!empty($data['count'])) {
            $this->count = (int)$data['count'];
        }

        if (!empty($data['items'])) {
            $this->items = $data['items'];
        }

        parent::setResponseData($data);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }
}
