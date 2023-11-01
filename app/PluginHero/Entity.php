<?php

namespace BeycanPress\CryptoPayLite\PluginHero;

class Entity
{
    use Helpers;
    
    /**
     * @var object
     */
    public $entity;

    /**
     * @param int|null $entityId
     */
    public function __construct(?int $entityId)
    {
        $this->entity = get_post($entityId);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        if (!$this->entity) return null;
        
        if (isset($this->entity->$key)) {
            return $this->entity->$key;
        } else {
            return get_post_meta($this->entity->ID, $key, true);
        }
    }

    /**
     * @return boolean
     */
    public function isAvailable() : bool
    {
        return $this->entity ? true : false;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getMeta(string $key)
    {
        return get_post_meta($this->entity->ID, $key, true);
    }

    /**
     * @param string $key
     * @param mixed $data
     * @return mixed
     */
    public function setMeta(string $key, $data)
    {
        return update_post_meta($this->entity->ID, $key, $data);
    }
}