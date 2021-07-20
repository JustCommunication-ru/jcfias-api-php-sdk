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
                        case 'ул':
                        case 'ул.':
                        case 'ал.':
                        case 'аллея':
                        case 'б-р':
                        case 'б-г':
                        case 'берег':
                        case 'к-цо':
                        case 'кв-л':
                        case 'кольцо':
                        case 'наб':
                        case 'наб.':
                        case 'набережная':
                        case 'парк':
                        case 'пер':
                        case 'пер-д':
                        case 'пер.':
                        case 'переезд':
                        case 'пл':
                        case 'пл.':
                        case 'пр-д':
                        case 'пр-кт':
                        case 'проезд':
                        case 'проул.':
                        case 'рзд':
                        case 'с-к':
                        case 'с-р':
                        case 'сад':
                        case 'сквер':
                        case 'ст':
                        case 'туп':
                        case 'туп.':
                        case 'ус.':
                        case 'ш':
                        case 'ш.':
                            $address->setStreet($object);

                            break;
                        case 'г':
                        case 'г.':
                            $address->setTown($object);

                            break;
                        case 'а.обл.':
                        case 'а.окр.':
                        case 'ао':
                        case 'аобл':
                        case 'край':
                        case 'м.о.':
                        case 'м.р-н':
                        case 'обл':
                        case 'обл.':
                        case 'округ':
                        case 'р-н':
                        case 'респ':
                        case 'респ.':
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
