<?php

namespace OpenOrchestra\Mapping\Metadata;

use Metadata\PropertyMetadata;

/**
 * Class PropertyCollectionSearchMetadata
 */
class PropertyCollectionSearchMetadata extends PropertyMetadata
{
    public $propertySearchMetadata = array();

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->class,
            $this->name,
            $this->propertySearchMetadata,
        ));
    }

    /**
     * @param string $str
     */
    public function unserialize($str)
    {
        list($this->class, $this->name, $this->propertySearchMetadata) = unserialize($str);

        $this->reflection = new \ReflectionProperty($this->class, $this->name);
        $this->reflection->setAccessible(true);
    }
}
