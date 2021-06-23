<?php

namespace JustCommunication\JcFIASSDK\API;

class RegionsRequest extends AbstractRequest
{
    const URI = '/api/regions';
    const HTTP_METHOD = 'GET';
    const RESPONSE_CLASS = RegionsResponse::class;
}
