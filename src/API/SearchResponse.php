<?php

namespace JustCommunication\JcFIASSDK\API;

class SearchResponse extends AbstractResponse
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
     * @var array
     */
    protected $assumptions = [];

    /**
     * @inheritDoc
     */
    public function setResponseData(array $data)
    {
        if (!empty($data['count'])) {
            $this->count = (int)$data['count'];
        }

        if (!empty($data['items'])) {
//            new AddressObject();
//            new House();
//            new Room();

            $this->items = $data['items'];
        }

        if (!empty($data['assumptions'])) {
//            new AddressObject();
//            new House();
//            new Room();

            $this->assumptions = $data['assumptions'];
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

    /**
     * @return array
     */
    public function getAssumptions()
    {
        return $this->assumptions;
    }
}
