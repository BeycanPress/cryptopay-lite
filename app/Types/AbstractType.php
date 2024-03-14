<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types;

use stdClass;
use ReflectionClass;

/**
 * Abstract type
 * @since 2.1.0
 */
abstract class AbstractType
{
    /**
     * @param array<string|string<array>> $exclude
     * @return stdClass
     */
    public function toObject(array $exclude = []): stdClass
    {
        $obj = new stdClass();
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            if ($property->getValue($this) instanceof AbstractType) {
                $obj->{$property->getName()} = $property->getValue($this)->toObject();
            } else {
                if (!is_null($value = $property->getValue($this))) {
                    $obj->{$property->getName()} = $value;
                }
            }
        }

        $publicDynamicProperties = get_object_vars($this);

        foreach ($publicDynamicProperties as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            $obj->{$key} = $value;
        }

        return $this->excludeProperties($obj, $exclude);
    }

    /**
     * @param stdClass $obj
     * @param array<string|string<array>> $exclude
     * @return stdClass
     */
    protected function excludeProperties(stdClass $obj, array $exclude): stdClass
    {
        if (empty($exclude)) {
            return $obj;
        }

        foreach ($exclude as $key => $value) {
            if (is_array($value)) {
                if (!isset($obj->{$key})) {
                    continue;
                }
                $this->excludeProperties($obj->{$key}, $value);
            } else {
                if (property_exists($obj, $value)) {
                    unset($obj->{$value});
                }
            }
        }

        return $obj;
    }

    /**
     * @param stdClass|null $obj
     * @return static
     */
    public static function fromObject(?stdClass $obj): self
    {
        $instance = new static();

        if (is_null($obj)) {
            return $instance;
        }

        $reflection = new ReflectionClass($instance);

        foreach ($reflection->getProperties() as $property) {
            if (property_exists($obj, $property->getName())) {
                $property->setAccessible(true);
                $propertyName = $property->getName();
                $propertyType = $property->getType();
                $propertyValue = $obj->{$propertyName};

                if ($propertyType) {
                    $propertyType = $propertyType->getName();
                    if (class_exists($propertyType) && method_exists($propertyType, 'fromObject')) {
                        if (is_string($propertyValue) && json_validate($propertyValue)) {
                            $property->setValue($instance, $propertyType::fromJson($propertyValue));
                        } elseif (is_object($propertyValue)) {
                            $property->setValue($instance, $propertyType::fromObject($propertyValue));
                        } elseif (is_array($propertyValue)) {
                            $property->setValue($instance, $propertyType::fromArray($propertyValue));
                        } else {
                            $property->setValue($instance, $propertyValue);
                        }
                    } elseif ('DateTime' == $propertyType) {
                        if (is_object($propertyValue)) {
                            $propertyValue = $propertyValue->date ?? $propertyValue->date_time ?? null;
                        }
                        $property->setValue($instance, new \DateTime($propertyValue));
                    } elseif (enum_exists($propertyType)) {
                        $property->setValue($instance, $propertyType::from($propertyValue));
                    } else {
                        $property->setValue($instance, $propertyValue);
                    }
                } else {
                    $property->setValue($instance, $propertyValue);
                }
            }
        }
        return $instance;
    }

    /**
     * @param bool $valuesToArray
     * @param array<string|string<array>> $exclude
     * @return array<mixed>
     */
    public function toArray(bool $valuesToArray = true, array $exclude = []): array
    {
        $result = json_decode(json_encode($this->toObject($exclude)), $valuesToArray);
        return !$valuesToArray ? (array) $result : $result;
    }

    /**
     * @param array<mixed> $arr
     * @return static
     */
    public static function fromArray(array $arr): self
    {
        return static::fromObject((object)json_decode(json_encode($arr)));
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toObject());
    }

    /**
     * @param string $json
     * @return static
     */
    public static function fromJson(string $json): self
    {
        return static::fromObject(json_decode($json));
    }
}
