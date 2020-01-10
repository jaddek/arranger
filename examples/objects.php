<?php

class ResponseObject implements \Jaddek\Arranger\HarmonizedInterface
{
    public const COLLECTION_WRAPPERS = [
        'matches' => MatchObject::class,
    ];

    private $responseData;
    private $quotaFinished;
    private $responseStatus;
    private $responderId;
    private $exceptionCode;
    private $matches;

    /**
     * ResponseObject constructor.
     *
     * @param array $matches
     * @param DataObject  $responseData
     * @param bool        $quotaFinished
     * @param int         $responseStatus
     * @param string      $responderId
     * @param int|null    $exceptionCode
     */
    public function __construct(array $matches, DataObject $responseData, bool $quotaFinished, int $responseStatus, string $responderId, ?int $exceptionCode)
    {
        $this->responseData   = $responseData;
        $this->quotaFinished  = $quotaFinished;
        $this->responseStatus = $responseStatus;
        $this->responderId    = $responderId;
        $this->exceptionCode  = $exceptionCode;
        $this->matches        = $matches;
    }

    /**
     * @return DataObject
     */
    public function getResponseData(): DataObject
    {
        return $this->responseData;
    }

    /**
     * @return bool
     */
    public function getQuotaFinished(): bool
    {
        return $this->quotaFinished;
    }

    /**
     * @return int
     */
    public function getResponseStatus(): int
    {
        return $this->responseStatus;
    }

    /**
     * @return string
     */
    public function getResponderId(): string
    {
        return $this->responderId;
    }

    /**
     * @return int
     */
    public function getExceptionCode(): ?int
    {
        return $this->exceptionCode;
    }
}


class DataObject implements \Jaddek\Arranger\HarmonizedInterface
{
    /**
     * @var string
     */
    private $translatedText;

    /**
     * DataVO constructor.
     *
     * @param string $translatedText
     */
    public function __construct(string $translatedText)
    {
        $this->translatedText = $translatedText;
    }

    /**
     * @return string
     */
    public function getTranslatedText(): string
    {
        return $this->translatedText;
    }
}

class MatchObject implements \Jaddek\Arranger\HarmonizedInterface
{
    private $id;
    private $segment;

    public function __construct(?string $id, ?string $segment)
    {
        $this->id        = $id;
        $this->segment   = $segment;
    }
}