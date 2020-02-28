<?php

namespace Vsavritsky\Weather;

/**
 * Class Response
 * @package Yandex\Weather
 * @license The MIT License (MIT)
 */
class Response
{
    /**
     * @var array
     */
    protected $_data;

    function __construct(array $data)
    {
        $this->_data = $data;
    }

    /**
     * Исходные данные
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    public function getNow()
    {
        return $this->_data['now'];
    }

    public function getNowDt()
    {
        return $this->_data['now_dt'];
    }

    public function getInfo()
    {
        return $this->_data['info'];
    }

    public function getFact()
    {
        foreach ($this->_data['fact'] as $key => $value) {
            if ($key == 'icon') $this->_data['fact'][$key] = str_replace("__icon__", $value, 'https://yastatic.net/weather/i/icons/blueye/color/svg/__icon__.svg');
            if ($key == 'condition') $this->_data['fact'][$key] = self::getCondition($value);
            if ($key == 'wind_dir') $this->_data['fact'][$key] = self::getWindDir($value);
            if ($key == 'prec_type') $this->_data['fact'][$key] = self::getPrecType($value);
            if ($key == 'prec_strength') $this->_data['fact'][$key] = self::getPrecStrength($value);
            if ($key == 'season') $this->_data['fact'][$key] = self::getSeason($value);
            if ($key == 'cloudness') $this->_data['fact'][$key] = self::getCloudness($value);
        }
        return $this->_data['fact'];
    }

    public function getForecasts()
    {
        return $this->_data['forecasts'];
    }

    public function getCondition(string $value)
    {
        //"Текущее состояние погоды"
        $array = [
            "clear" => "ясно",
            "partly-cloudy" => "малооблачно",
            "cloudy" => "облачно с прояснениями",
            "overcast" => "пасмурно",
            "partly-cloudy-and-light-rain" => "небольшой дождь",
            "partly-cloudy-and-rain" => "дождь",
            "overcast-and-rain" => "сильный дождь",
            "overcast-thunderstorms-with-rain" => "сильный дождь, гроза",
            "cloudy-and-light-rain" => "небольшой дождь",
            "overcast-and-light-rain" => "небольшой дождь",
            "cloudy-and-rain" => "дождь",
            "overcast-and-wet-snow" => "дождь со снегом",
            "partly-cloudy-and-light-snow" => "небольшой снег",
            "partly-cloudy-and-snow" => "снег",
            "overcast-and-snow" => "снегопад",
            "cloudy-and-light-snow" => "небольшой снег",
            "overcast-and-light-snow" => "небольшой снег",
            "cloudy-and-snow" => "снег",
        ];
        if (!array_key_exists($value, $array)) {
            return false;
        }
        return $array[$value];
    }

    public function getWindDir(string $value)
    {
        $array = [
            "nw" => "северо-западное",
            "n" => "северное",
            "ne" => "северо-восточное",
            "e" => "восточное",
            "se" => "юго-восточное",
            "s" => "южное",
            "sw" => "юго-западное",
            "w" => "западное",
            "c" => "штиль",
        ];
        if (array_key_exists($value, $array)) {
            return $array[$value];
        }
    }

    public function getPrecType(int $value)
    {
        //Тип осадков. Возможные значения
        $array = [
            "без осадков",
            "дождь",
            "дождь со снегом",
            "снег",
        ];
        if (array_key_exists($value, $array)) {
            return $array[$value];
        }
    }

    public function getPrecStrength(int $value)
    {
        //Сила осадков. Возможные значения
        $array = [
            "без осадков",
            "слабый дождь/слабый снег",
            "дождь/снег",
            "сильный дождь/сильный снег",
            "сильный ливень/очень сильный снег",
        ];
        if (array_key_exists($value, $array)) {
            return $array[$value];
        }
    }

    public function getSeason(string $value)
    {
        $array = [
            "summer" => "лето",
            "autumn" => "осень",
            "winter" => "зима",
            "spring" => "весна",
        ];
        if (array_key_exists($value, $array)) {
            return $array[$value];
        }
    }

    public function getCloudness(int $value)
    {
        //Облачность. Возможные значения.
        $array = [
            "ясно",
            "малооблачно",
            "облачно с прояснениями",
            "облачно с прояснениями",
            "пасмурно",
        ];
        if (array_key_exists($value, $array)) {
            return $array[$value];
        }
    }
}
