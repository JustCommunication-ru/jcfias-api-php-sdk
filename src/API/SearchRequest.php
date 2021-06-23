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
            'town' => isset($params['town']) ? $params['town'] : '',
            'region' => isset($params['region']) ? $params['region'] : '',
            'aoguids' => isset($params['aoguids']) ? $params['aoguids'] : [],
            'version' => isset($params['version']) ? $params['version'] : 1,
            'assumptions' => isset($params['assumptions']),
            'autocomplete' => isset($params['autocomplete'])
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
