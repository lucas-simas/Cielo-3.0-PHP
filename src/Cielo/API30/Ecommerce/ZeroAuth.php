<?php

namespace Cielo\API30\Ecommerce;

/**
 * Class ZeroAuth
 *
 * Payload da validacao de cartao (Zero Auth): simula uma autorizacao de valor zero para
 * verificar se o cartao esta valido, sem cobrar. A resposta devolve os identificadores da
 * bandeira (IssuerTransactionId e, em Mastercard, TransactionLinkId).
 *
 * @package Cielo\API30\Ecommerce
 */
class ZeroAuth implements \JsonSerializable
{

    const CARDONFILE_USAGE_FIRST = 'First';

    const CARDONFILE_USAGE_USED = 'Used';

    const CARDONFILE_REASON_RECURRING = 'Recurring';

    private $cardNumber;

    private $holder;

    private $expirationDate;

    private $securityCode;

    private $brand;

    private $cardToken;

    private $saveCard;

    private $cardOnFileUsage;

    private $cardOnFileReason;

    /**
     * ZeroAuth constructor.
     *
     * @param null $cardNumber
     * @param null $holder
     * @param null $expirationDate
     * @param null $brand
     */
    public function __construct($cardNumber = null, $holder = null, $expirationDate = null, $brand = null)
    {
        $this->cardNumber     = $cardNumber;
        $this->holder         = $holder;
        $this->expirationDate = $expirationDate;
        $this->brand          = $brand;
    }

    /**
     * Emite as chaves com o nome exato do contrato da Cielo (PascalCase) e omite o que estiver
     * nulo. O no CardOnFile e opcional, entao so e montado quando ha Usage ou Reason.
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $data = [
            //Cartao aberto
            'CardNumber'     => $this->cardNumber,
            'Holder'         => $this->holder,
            'ExpirationDate' => $this->expirationDate,
            'SecurityCode'   => $this->securityCode,
            //Cartao tokenizado (Cartao Protegido): CardToken + SaveCard
            'CardToken'      => $this->cardToken,
            'SaveCard'       => $this->saveCard,
            'Brand'          => $this->brand,
        ];

        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });

        $cardOnFile = array_filter([
            'Usage'  => $this->cardOnFileUsage,
            'Reason' => $this->cardOnFileReason,
        ], function ($value) {
            return $value !== null && $value !== '';
        });

        if (count($cardOnFile) > 0) {
            $data['CardOnFile'] = $cardOnFile;
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param $cardNumber
     *
     * @return $this
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHolder()
    {
        return $this->holder;
    }

    /**
     * @param $holder
     *
     * @return $this
     */
    public function setHolder($holder)
    {
        $this->holder = $holder;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param $expirationDate
     *
     * @return $this
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecurityCode()
    {
        return $this->securityCode;
    }

    /**
     * Nas validacoes de cartao tokenizado pela bandeira, este campo transporta o criptograma.
     *
     * @param $securityCode
     *
     * @return $this
     */
    public function setSecurityCode($securityCode)
    {
        $this->securityCode = $securityCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param $brand
     *
     * @return $this
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardToken()
    {
        return $this->cardToken;
    }

    /**
     * Validacao de cartao tokenizado (Cartao Protegido): CardToken em vez dos dados do cartao.
     *
     * @param $cardToken
     *
     * @return $this
     */
    public function setCardToken($cardToken)
    {
        $this->cardToken = $cardToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSaveCard()
    {
        return $this->saveCard;
    }

    /**
     * Enviado junto do CardToken. A doc da Cielo mostra esse campo como string ("false"), por isso
     * o valor e repassado como veio, sem cast.
     *
     * @param $saveCard
     *
     * @return $this
     */
    public function setSaveCard($saveCard)
    {
        $this->saveCard = $saveCard;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardOnFileUsage()
    {
        return $this->cardOnFileUsage;
    }

    /**
     * @param $cardOnFileUsage
     *
     * @return $this
     */
    public function setCardOnFileUsage($cardOnFileUsage)
    {
        $this->cardOnFileUsage = $cardOnFileUsage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardOnFileReason()
    {
        return $this->cardOnFileReason;
    }

    /**
     * @param $cardOnFileReason
     *
     * @return $this
     */
    public function setCardOnFileReason($cardOnFileReason)
    {
        $this->cardOnFileReason = $cardOnFileReason;

        return $this;
    }

    /**
     * @param $usage
     * @param $reason
     *
     * @return $this
     */
    public function cardOnFile($usage, $reason = null)
    {
        $this->cardOnFileUsage  = $usage;
        $this->cardOnFileReason = $reason;

        return $this;
    }
}
