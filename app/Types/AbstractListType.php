<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\Types;

use stdClass;

/**
 * Abstract type
 * @since 2.1.0
 */
class AbstractListType extends AbstractType
{
    /**
     * @var string
     */
    protected static string $type;

    /**
     * @var array<mixed>
     */
    protected array $list = [];

    /**
     * @return array<mixed>
     */
    public function all(): array
    {
        return $this->list;
    }

    /**
     * @return mixed
     */
    public function first(): mixed
    {
        return $this->list[0] ?? null;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->list);
    }

    /**
     * @param string $property
     * @return array<mixed>
     */
    public function column(string $property): array
    {
        $column = [];

        foreach ($this->list as $network) {
            if (method_exists($network, 'get' . ucfirst($property))) {
                $column[] = call_user_func([$network, 'get' . ucfirst($property)]);
            }
        }

        return $column;
    }

    /**
     * @param self $list
     * @return static
     */
    public function merge(self $list): self
    {
        $this->list = array_merge($this->list, $list->all());
        return $this;
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback): self
    {
        $this->list = array_map($callback, $this->list);
        return $this;
    }

    /**
     * @param mixed $key
     * @return mixed
     */
    public function get(mixed $key): mixed
    {
        return $this->list[$key] ?? null;
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function filter(callable $callback): self
    {
        $this->list = array_values(array_filter($this->list, $callback));
        return $this;
    }

    /**
     * @param array<string|string<array>> $exclude
     * @return stdClass
     */
    public function toObject(array $exclude = []): stdClass
    {
        $list = [];

        foreach ($this->list as $key => $value) {
            if ($value instanceof AbstractType) {
                $list[$key] = $value->toObject($exclude);
            } else {
                $list[$key] = $value;
            }
        }

        return (object) $list;
    }

    /**
     * @param stdClass|null $obj
     * @return static
     */
    public static function fromObject(?stdClass $obj): self
    {
        $list = [];

        if (isset($obj->list)) {
            foreach ($obj->list as $key => $value) {
                $list[$key] = static::$type::fromObject($value);
            }
        } else {
            foreach ($obj as $key => $value) {
                $list[$key] = static::$type::fromObject($value);
            }
        }

        return new static($list);
    }
}
