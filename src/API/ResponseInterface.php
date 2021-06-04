<?php

namespace JustCommunication\JcFIASSDK\API;

interface ResponseInterface
{
    /**
     * @param array $data
     * @return void
     *
     * @throws JcFIASAPIException
     */
    public function setResponseData(array $data);
}
