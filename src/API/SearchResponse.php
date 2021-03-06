<?php

namespace JustCommunication\JcFIASSDK\API;

use JustCommunication\JcFIASSDK\Model\AddressModel;
use JustCommunication\JcFIASSDK\Model\HouseModel;
use JustCommunication\JcFIASSDK\Model\ObjectModel;
use JustCommunication\JcFIASSDK\Model\RoomModel;

class SearchResponse extends AbstractResponse
{
    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var AddressModel[]
     */
    protected $addresses = [];

    /**
     * @var AddressModel[]
     */
    protected $assumptions_addresses = [];

    /**
     * @inheritDoc
     */
    public function setResponseData(array $data)
    {
        if (!empty($data['count'])) {
            $this->count = (int)$data['count'];
        }

        if (!empty($data['items'])) {
            $this->addresses = $this->mapAddresses($data['items']);
        }

        if (!empty($data['assumptions'])) {
            $this->assumptions_addresses = $this->mapAddresses($data['assumptions']);
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
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @return array
     */
    public function getAssumptionsAddresses()
    {
        return $this->assumptions_addresses;
    }

    private function mapAddresses($items)
    {
        $addresses = [];

        foreach ($items as $item) {
            $address = new AddressModel();

            if (!empty($item['address_objects'])) {
                foreach ($item['address_objects'] as $address_object) {
                    $object = new ObjectModel();
                    $object->mapData($address_object);

                    $address->setObject($object);
                }
            }

            if (!empty($item['house'])) {
                $house = new HouseModel();
                $house->mapData($item['house']);

                $address->setHouse($house);
            }

            if (!empty($item['room'])) {
                $room = new RoomModel();
                $room->mapData($item['room']);

                $address->setRoom($room);
            }

            if ($address->getObjects()) {
                foreach ($address->getObjects() as $object) {
                    switch (mb_strtolower($object->shortname)) {
                        case '????':
                        case '????.':
                        case '????.':
                        case '??????????':
                        case '??-??':
                        case '??-??':
                        case '??????????':
                        case '??-????':
                        case '????-??':
                        case '????????????':
                        case '??????':
                        case '??????.':
                        case '????????????????????':
                        case '????????':
                        case '??????':
                        case '??????-??':
                        case '??????.':
                        case '??????????????':
                        case '????':
                        case '????.':
                        case '????-??':
                        case '????-????':
                        case '????????????':
                        case '??????????.':
                        case '??????':
                        case '??-??':
                        case '??-??':
                        case '??????':
                        case '??????????':
                        case '????':
                        case '??????':
                        case '??????.':
                        case '????.':
                        case '??':
                        case '??.':
                            $address->setStreet($object);

                            break;
                        case '??':
                        case '??.':
                            $address->setTown($object);

                            break;
                        case '??.??????.':
                        case '??.??????.':
                        case '????':
                        case '????????':
                        case '????????':
                        case '??.??.':
                        case '??.??-??':
                        case '??????':
                        case '??????.':
                        case '??????????':
                        case '??-??':
                        case '????????':
                        case '????????.':
                            $address->setRegion($object);

                            break;
                    }
                }
            }

            $addresses[] = $address;
        }

        return $addresses;
    }
}
