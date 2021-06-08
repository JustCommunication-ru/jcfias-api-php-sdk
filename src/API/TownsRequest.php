<?php

namespace JustCommunication\JcFIASSDK\API;

class TownsRequest extends AbstractRequest
{
    const URI = '/api/towns';
    const HTTP_METHOD = 'GET';
    const RESPONSE_CLASS = TownsResponse::class;
}
