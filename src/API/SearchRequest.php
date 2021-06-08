<?php

namespace JustCommunication\JcFIASSDK\API;

class SearchRequest extends AbstractRequest
{
    const URI = '/api/search';
    const HTTP_METHOD = 'GET';
    const RESPONSE_CLASS = SearchResponse::class;

    /**
     * @var string
     */
    protected $string;

    protected $options;

    /**
     * SearchRequest constructor.
     *
     * @param int $string
     */
    public function __construct($string, $params = [])
    {
        $this->options = [
            'aoguids' => isset($params['aoguids']) ? $params['aoguids'] : [],
            'autocomplete' => isset($params['autocomplete']),
            'assumptions' => isset($params['assumptions'])
        ];

        $this->string = $string;
    }

    /**
     * @inheritDoc
     */
    public function createHttpClientParams()
    {
        return [
            'query' => array_merge(
                ['string' => $this->string],
                $this->options
            )
        ];
    }
}
