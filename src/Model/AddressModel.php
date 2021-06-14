<?php

namespace JustCommunication\JcFIASSDK\Model;

class AddressModel extends AbstractModel
{
    /**
     * @var ObjectModel[]
     */
    private $objects = [];

    /**
     * @var HouseModel
     */
    private $house = null;

    /**
     * @var RoomModel
     */
    private $room = null;

    /**
     * @var ObjectModel
     */
    private $region = null;

    /**
     * @var ObjectModel
     */
    private $town = null;

    /**
     * @var ObjectModel
     */
    private $street = null;

    /**
     * @param ObjectModel $object
     */
    public function setObject(ObjectModel $object)
    {
        $this->objects[] = $object;
    }

    /**
     * @return ObjectModel[]
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @return HouseModel
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * @param HouseModel $house
     */
    public function setHouse(HouseModel $house)
    {
        $this->house = $house;
    }

    /**
     * @return RoomModel
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param RoomModel $room
     */
    public function setRoom(RoomModel $room)
    {
        $this->room = $room;
    }

    /**
     * @return ObjectModel
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param ObjectModel $region
     */
    public function setRegion(ObjectModel $region)
    {
        $this->region = $region;
    }

    /**
     * @return ObjectModel
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param ObjectModel $town
     */
    public function setTown(ObjectModel $town)
    {
        $this->town = $town;
    }

    /**
     * @return ObjectModel
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param ObjectModel $street
     */
    public function setStreet(ObjectModel $street)
    {
        $this->street = $street;
    }
}
