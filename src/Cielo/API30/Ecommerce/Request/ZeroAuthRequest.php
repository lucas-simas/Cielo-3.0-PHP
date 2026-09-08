<?php

namespace Cielo\API30\Ecommerce\Request;

use Cielo\API30\Ecommerce\ZeroAuthResponse;
use Cielo\API30\Environment;
use Cielo\API30\Merchant;
use Psr\Log\LoggerInterface;

/**
 * Class ZeroAuthRequest
 *
 * @package Cielo\API30\Ecommerce\Request
 */
class ZeroAuthRequest extends AbstractRequest
{

    private $environment;

	/**
	 * ZeroAuthRequest constructor.
	 *
	 * @param Merchant $merchant
	 * @param Environment $environment
	 * @param LoggerInterface|null $logger
	 */
    public function __construct(Merchant $merchant, Environment $environment, LoggerInterface $logger = null)
    {
        parent::__construct($merchant, $logger);

        $this->environment = $environment;
    }

    /**
     * @param $zeroAuth
     *
     * @return ZeroAuthResponse
     * @throws \Cielo\API30\Ecommerce\Request\CieloRequestException
     * @throws \RuntimeException
     */
    public function execute($zeroAuth)
    {
        $url = $this->environment->getApiUrl() . '1/zeroauth/';

        return $this->sendRequest('POST', $url, $zeroAuth);
    }

    /**
     * O Zero Auth documenta erros num objeto unico ({"Code":57,"Message":"Bandeira inválida"}),
     * formato que o readResponse da classe base nao cobre (ela trata array e string), o que faria
     * o throw acontecer com null. Trata esse caso aqui e delega o resto para a base.
     *
     * @param $statusCode
     * @param $responseBody
     *
     * @return mixed
     *
     * @throws CieloRequestException
     */
    protected function readResponse($statusCode, $responseBody)
    {
        if ($statusCode === 400) {
            $response = json_decode($responseBody);

            if (is_object($response) && (isset($response->Code) || isset($response->Message))) {
                $cieloError = new CieloError(
                    isset($response->Message) ? $response->Message : null,
                    isset($response->Code) ? $response->Code : null
                );

                $exception = new CieloRequestException('Request Error', $statusCode, null);
                $exception->setCieloError($cieloError);

                throw $exception;
            }
        }

        return parent::readResponse($statusCode, $responseBody);
    }

    /**
     * @param $json
     *
     * @return ZeroAuthResponse
     */
    protected function unserialize($json)
    {
        return ZeroAuthResponse::fromJson($json);
    }
}
