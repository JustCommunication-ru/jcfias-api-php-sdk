<?php

namespace JustCommunication\JcFIASSDK\API;

class SearchBatchRequest extends SearchRequest
{
    const RESPONSE_CLASS = SearchBatchResponse::class;

    /**
     * SearchBatchRequest constructor.
     *
     * @param array $string
     */
    public function __construct($string, $params = [])
    {
        if (!is_array($string)) {
            $string = [$string];
        }

        unset($params['autocomplete']);

        parent::__construct($string, $params);
    }
}
