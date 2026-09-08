<?php

namespace Cielo\API30\Ecommerce;

/**
 * Class ZeroAuthResponse
 *
 * Resposta da validacao de cartao (Zero Auth). Alem de Valid/ReturnCode/ReturnMessage, traz os
 * identificadores da bandeira: IssuerTransactionId (Visa, Mastercard, Elo e Amex) e
 * TransactionLinkId (somente Mastercard).
 *
 * Os campos Code/Message tambem sao lidos aqui porque a doc da Cielo documenta dois retornos
 * nesse formato (bandeira invalida e restricao cadastral) sem informar o status HTTP deles.
 *
 * @package Cielo\API30\Ecommerce
 */
class ZeroAuthResponse implements CieloSerializable
{

    private $valid;

    private $returnCode;

    private $returnMessage;

    private $issuerTransactionId;

    private $transactionLinkId;

    private $code;

    private $message;

    /**
     * @param $json
     *
     * @return ZeroAuthResponse
     */
    public static function fromJson($json)
    {
        $zeroAuthResponse = new ZeroAuthResponse();
        $zeroAuthResponse->populate(json_decode($json));

        return $zeroAuthResponse;
    }

    /**
     * @param \stdClass $data
     */
    public function populate(\stdClass $data)
    {
        //isset() em vez de !empty() para nao confundir Valid=false com ausencia do campo
        $this->valid               = isset($data->Valid) ? !!$data->Valid : null;
        $this->returnCode          = isset($data->ReturnCode) ? $data->ReturnCode : null;
        $this->returnMessage       = isset($data->ReturnMessage) ? $data->ReturnMessage : null;
        $this->issuerTransactionId = isset($data->IssuerTransactionId) ? $data->IssuerTransactionId : null;
        $this->transactionLinkId   = isset($data->TransactionLinkId) ? $data->TransactionLinkId : null;

        $this->code    = isset($data->Code) ? $data->Code : null;
        $this->message = isset($data->Message) ? $data->Message : null;
    }

    /**
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @return mixed
     */
    public function getReturnCode()
    {
        return $this->returnCode;
    }

    /**
     * @return mixed
     */
    public function getReturnMessage()
    {
        return $this->returnMessage;
    }

    /**
     * @return mixed
     */
    public function getIssuerTransactionId()
    {
        return $this->issuerTransactionId;
    }

    /**
     * @return mixed
     */
    public function getTransactionLinkId()
    {
        return $this->transactionLinkId;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
}
