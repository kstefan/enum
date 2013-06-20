<?php

namespace Enum;

use InvalidArgumentException;
use ReflectionClass;

/**
 * Class AbstractEnum
 * @package Enum
 */
abstract class AbstractEnum implements EnumInterface
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private static $cache = array();

    /**
     * Default value
     * @var mixed
     */
    protected static $default;

    /**
     * @param $value
     */
    public function __construct($value = null)
    {
        if (is_null($value)) {
            $value = static::$default;
        }

        static::initialize();

        $this->setValue($value);
    }

    /**
     * @param $value
     * @return static
     */
    public static function create($value)
    {
        return new static($value);
    }

    /**
     * @return array
     */
    protected static function loadValues()
    {
        $r = new ReflectionClass(get_called_class());

        return $r->getConstants();
    }

    protected static function initialize()
    {
        $className = get_called_class();

        if (!isset(self::$cache[$className]['values'])) {
            self::$cache[$className]['values'] = static::loadValues();
        }
    }

    /**
     * @return mixed
     */
    public static function getValues()
    {
        static::initialize();

        return self::$cache[get_called_class()]['values'];
    }

    /**
     * @return mixed
     */
    public static function getEnums()
    {
        $className = get_called_class();

        if (!isset(self::$cache[$className]['enums'])) {
            foreach (static::getValues() as $value) {
                self::$cache[$className]['enums'][$value] = new static($value);
            }
        }

        return self::$cache[$className]['enums'];
    }

    /**
     * @return array
     */
    protected static function loadLabels()
    {
        $arr = array();

        foreach (static::getEnums() as $enum) {
            $arr[$enum->getValue()] = $enum->getLabel();
        }

        return $arr;
    }

    /**
     * @return mixed
     */
    public static function getLabels()
    {
        $className = get_called_class();

        if (!isset(self::$cache[$className]['labels'])) {
            self::$cache[$className]['labels'] = static::loadLabels();
        }

        return self::$cache[$className]['labels'];
    }

    /**
     * @param $value
     * @return bool
     */
    public static function hasValue($value)
    {
        return in_array($value, static::getValues());
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @throws InvalidArgumentException
     */
    protected function checkValue($value)
    {
        if (!static::hasValue($value)) {
            throw new InvalidArgumentException('Value "' . $value . '" is not defined');
        }
    }

    /**
     * @param $value
     */
    protected function setValue($value)
    {
        $this->checkValue($value);

        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->value;
    }

    /**
     * @param EnumInterface|string $value
     * @return bool
     */
    public function is($value)
    {
        if ($value instanceof EnumInterface) {
            $value = $value->getValue();
        }

        $this->checkValue($value);

        return $value == $this->getValue();
    }

    protected function normalizeValues(array $values)
    {
        return array_map(function($value) {
            if ($value instanceof EnumInterface) {
                $value = $value->getValue();
            }

            $this->checkValue($value);

            return $value;
        }, $values);
    }

    /**
     * @param array $values [Enum::VALUE_1, new Enum(Enum:VALUE_2),]
     * @return bool
     */
    public function in(array $values)
    {
        return in_array($this->getValue(), $this->normalizeValues($values));
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return (string)$this->value;
    }
}
