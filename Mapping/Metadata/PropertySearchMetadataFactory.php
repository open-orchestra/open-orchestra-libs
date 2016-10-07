<?php

namespace OpenOrchestra\Mapping\Metadata;

/**
 * Class PropertySearchMetadataFactory
 */
class PropertySearchMetadataFactory implements PropertySearchMetadataFactoryInterface
{
    /**
     * @param mixed  $class
     * @param string $name
     *
     * @return PropertySearchMetadata
     */
    public function create($class, $name){
        return new PropertySearchMetadata($class, $name);
    }

    /**
     * @param mixed  $class
     * @param string $name
     *
     * @return PropertyCollectionSearchMetadata
     */
    public function createCollection($class, $name){
        return new PropertyCollectionSearchMetadata($class, $name);
    }
}
