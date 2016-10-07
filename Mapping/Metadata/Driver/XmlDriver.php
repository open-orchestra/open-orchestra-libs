<?php

namespace OpenOrchestra\Mapping\Metadata\Driver;

use OpenOrchestra\Mapping\Metadata\PropertyCollectionSearchMetadata;
use ReflectionClass;

/**
 * Class XmlDriver
 */
class XmlDriver extends AbstractFileDriverSearch
{
    /**
     * Parses the content of the file, and converts it to the desired metadata.
     *
     * @param ReflectionClass $class
     * @param string $file
     *
     * @return \Metadata\ClassMetadata|null
     */
    protected function loadMetadataFromFile(ReflectionClass $class, $file)
    {
        $elem = simplexml_load_file($file);
        $classElement = $elem->children();
        $className = $classElement->attributes()->{'name'};
        if (null !== $className && (string)$className == $class->getName()) {
            $classMetadata = $this->mergeableClassMetadataFactory->create($class->getName());

            foreach ($classElement->children() as $fieldElement) {
                $collectionSearchMetadata = $this->getSearchMetadata($fieldElement, $class->getName());
                $classMetadata->addPropertyMetadata($collectionSearchMetadata);
            }

            return $classMetadata;
        }

        return null;
    }

    /**
     * @param \SimpleXMLElement $property
     * @param string            $className
     *
     * @return PropertyCollectionSearchMetadata
     */
    protected function getSearchMetadata(\SimpleXMLElement $property, $className)
    {
        $fieldElementAttributes = $property->attributes();
        $field = (string) $fieldElementAttributes->{'field'};

        $multipleSearchMetadata = $this->propertySearchMetadataFactory->createCollection($className, $field);
        if ($property->count() > 0) {
            foreach ($property->children() as $metadata) {
                $fieldElementAttributes = $metadata->attributes();
                $propertyMetadata = $this->getPropertyMetadata($fieldElementAttributes, $className, $field);
                $multipleSearchMetadata->propertySearchMetadata[] = $propertyMetadata;
            }
        } else {
            $propertyMetadata = $this->getPropertyMetadata($fieldElementAttributes, $className, $field);
            $multipleSearchMetadata->propertySearchMetadata[] = $propertyMetadata;

        }

        return $multipleSearchMetadata;

    }

    /**
     * @param \SimpleXMLElement $fieldElementAttributes
     * @param string            $className
     * @param string            $field
     *
     * @return \OpenOrchestra\Mapping\Metadata\PropertySearchMetadata
     */
    protected function getPropertyMetadata(\SimpleXMLElement $fieldElementAttributes, $className, $field)
    {
        $propertyMetadata = $this->propertySearchMetadataFactory->create($className, $field);

        $type = (string) $fieldElementAttributes->{'type'};
        $key = (string) $fieldElementAttributes->{'key'};
        $propertyMetadata->key = $this->extractKey($key);
        $propertyMetadata->type = ('' !== $type) ? $type : "string";
        $propertyMetadata->field = $field;

        return $propertyMetadata;
    }

    /**
     * @param string $key
     *
     * @return array|string
     */
    protected function extractKey($key)
    {
        $key = array_map('trim', explode(',', $key));
        if (count($key) == 1) {
            return array_shift($key);
        }

        return $key;
    }

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    protected function getExtension()
    {
        return 'xml';
    }
}
