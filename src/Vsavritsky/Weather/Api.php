<?php

namespace Vsavritsky\Weather;

use Vsavritsky\Weather\Response;
use Curl\Curl;

/**
 * Api
 */
class Api
{
    const BASE_URL = 'https://api.weather.yandex.ru/v%s/';
    /** русский (по умолчанию) */
    const LANG_RU = 'ru_RU';
    /**
     * @var string Ключ
     */
    protected $_key = '';
    /**
     * @var string Версия используемого api
     */
    protected $_version = '1';
    /**
     * @var array
     */
    protected $_filters = array();
    /**
     * @var string
     */

    /**
     * @param string $key
     * @param null|string $version
     */
    public function __construct($key, $version = null)
    {
        $this->_key = $key;
        if (!empty($version)) {
            $this->_version = (string)$version;
        }
        $this->clear();
    }

    /**
     * Очистка фильтров
     * @return self
     */
    public function clear()
    {
        $this->_filters = array();
        $this->setLang(self::LANG_RU);
        $this->_response = null;
        return $this;
    }

    /**
     * Testing Weather
     * Returns a list of translation directions supported by the service.
     * @link https://yandex.ru/dev/weather/doc/dg/concepts/forecast-test-docpage/
     *
     * @param string $culture If set, the service's response will contain a list of language codes
     *
     * @return array
     */
    public function forecast()
    {
        $this->_filters['limit'] = 7;
        $this->_filters['hours'] = true;
        $this->_filters['extra'] = true;
        $curl = self::initCurl(__METHOD__);
    }

    /**
     * Testing Weather
     * Returns a list of translation directions supported by the service.
     * @link https://yandex.ru/dev/weather/doc/dg/concepts/forecast-test-docpage/
     *
     * @param string $culture If set, the service's response will contain a list of language codes
     *
     * @return array
     */
    public function informers()
    {
        $curl = self::initCurl(__METHOD__);
    }

    /**
     * Гео-кодирование по координатам
     * @see https://yandex.ru/dev/weather/doc/dg/concepts/forecast-info-docpage/
     * @param float $lan Долгота в градусах
     * @param float $lat Широта в градусах
     * @return self
     */
    public function setPoint($lat, $lon)
    {
        $this->_filters['lon'] = (float)$lon;
        $this->_filters['lat'] = (float)$lat;
        return $this;
    }

    /**
     * Предпочитаемый язык
     * @param string $lang
     * @return self
     */
    public function setLang($lang)
    {
        $this->_filters['lang'] = (string)$lang;
        return $this;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->_response;
    }

    public function initCurl(string $fullMethod)
    {
        $apiUrl = $this->generateUri($fullMethod);
        $curl = new Curl();
        $curl->setHeader('X-Yandex-API-Key', $this->_key);
        $curl->get($apiUrl, $this->_filters);
        if ($curl->error) {
            throw new \Vsavritsky\Exception\CurlException($curl);
        }
        $fp = fopen(BASE_DIR . '/new_weather.json', 'w+');
        fwrite($fp, $curl->response);
        $data = json_decode($curl->response, true);
        if (empty($data)) {
            $msg = sprintf('Can\'t load data by url: %s', $apiUrl);
            throw new \Vsavritsky\BaseException($msg);
        }
        if (!empty($data['error'])) {
            throw new \Vsavritsky\Exception\ErrorException($data['message'], $data['statusCode']);
        }

        $this->_response = new \Vsavritsky\Weather\Response($data);

        return $this;
    }

    public function generateUri($fullMethod)
    {
        $base_uri = sprintf(self::BASE_URL, $this->_version);
        $this->_method = explode('::', $fullMethod)[1];
        $uri = $base_uri . $this->_method;
        return $uri;
    }

}
