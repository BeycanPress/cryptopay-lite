<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types\Data;

use stdClass;
use BeycanPress\CryptoPayLite\Types\AbstractType;

/**
 * Dynamic data type
 * @since 2.1.0
 */
class DynamicDataType extends AbstractType
{
    /**
     * @var stdClass
     */
    private stdClass $data;

    /**
     * @param mixed<object|array|null> $data
     */
    public function __construct(mixed $data = null)
    {
        $this->data = (object) $data ?? new stdClass();
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set(string $key, mixed $value): self
    {
        $this->data->{$key} = $value;

        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        $keys = explode('.', $key);
        $data = $this->data;

        foreach ($keys as $innerKey) {
            if (is_object($data) && property_exists($data, $innerKey)) {
                $data = $data->{$innerKey};
            } elseif (is_array($data) && array_key_exists($innerKey, $data)) {
                $data = $data[$innerKey];
            } else {
                return null;
            }
        }

        return $data ?? null;
    }

    /**
     * @return stdClass
     */
    public function all(): stdClass
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count((array) $this->data);
    }

    /**
     * $exclude parameter just for compatibility with abstract type
     * @param array<string|string<array>> $exclude
     * @return stdClass
     */
    public function toObject(array $exclude = []): stdClass
    {
        $data = [];

        foreach ($this->data as $key => $value) {
            $data[$key] = $value;
        }

        return (object) $data;
    }

    /**
     * @param stdClass|null $obj
     * @return static
     */
    public static function fromObject(?stdClass $obj): self
    {
        $data = [];

        if (isset($obj->data)) {
            foreach ($obj->data as $key => $value) {
                $data[$key] = $value;
            }
        } elseif ($obj) {
            foreach ($obj as $key => $value) {
                $data[$key] = $value;
            }
        }

        return new static($data);
    }

    /**
     * @param self $data
     * @return self
     */
    public function merge(self $data): self
    {
        $this->data = (object) array_merge((array) $this->data, (array) $data->all());
        return $this;
    }
}
