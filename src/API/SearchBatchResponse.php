<?php

namespace JustCommunication\JcFIASSDK\API;

class SearchBatchResponse extends AbstractResponse
{
    /**
     * @var int
     */
    protected $batchSize = 0;

    /**
     * @var SearchResponse[]
     */
    protected $batch = [];

    /**
     * @inheritDoc
     */
    public function setResponseData(array $data)
    {
        $this->batchSize = count($data);

        if ($this->batchSize) {
            foreach ($data as $datum) {
                $searchResponse = new SearchResponse();
                $searchResponse->setResponseData($datum);

                $this->batch[] = $searchResponse;
            }
        }

        parent::setResponseData($data);
    }

    /**
     * @return int
     */
    public function getBatchSize()
    {
        return $this->batchSize;
    }

    /**
     * @return SearchResponse[]
     */
    public function getBatch()
    {
        return $this->batch;
    }
}
