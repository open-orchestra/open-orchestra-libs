<?php

namespace OpenOrchestra\Mapping\Metadata\Driver;

use OpenOrchestra\Mapping\Metadata\PropertyCollectionSearchMetadata;
use Symfony\Component\Yaml\Yaml;
use ReflectionClass;

class YamlDriver extends AbstractFileDriverSearch
{
    /**
     * @param ReflectionClass $class
     * @param string           $file
     *
     * @return \Metadata\ClassMetadata
     */
    protected function loadMetadataFromFile(ReflectionClass $class, $file)
    {
        $data = Yaml::parse($file);
        if (isset($data[$class->getName()]) && isset($data[$class->getName()]["properties"])) {
            $classMetadata = $this->mergeableClassMetadataFactory->create($class->getName());

            foreach ($data[$class->getName()]["properties"] as $field => $property) {
                $collectionSearchMetadata = $this->getSearchMetadata($property, $class->getName(), $field);
                $classMetadata->addPropertyMetadata($collectionSearchMetadata);
            }

            return $classMetadata;
        }

        return null;
    }

    /**
     * @param array  $property
     * @param string $className
     * @param string $field
     *
     * @return PropertyCollectionSearchMetadata
     */
    protected function getSearchMetadata(array $property, $className, $field)
    {
        $multipleSearchMetadata = $this->propertySearchMetadataFactory->createCollection($className, $field);

        if (is_array(current($property)) && !isset($property['key'])) {
            foreach ($property as $metadata) {
                $propertyMetadata = $this->getPropertyMetadata($metadata, $className, $field);
                $multipleSearchMetadata->propertySearchMetadata[] = $propertyMetadata;
            }
        } else {
            $propertyMetadata = $this->getPropertyMetadata($property, $className, $field);
            $multipleSearchMetadata->propertySearchMetadata[] = $propertyMetadata;
        }

        return $multipleSearchMetadata;

    }

    /**
     * @param array  $metadata
     * @param string $className
     * @param string $field
     *
     * @return \OpenOrchestra\Mapping\Metadata\PropertySearchMetadata
     */
    protected function getPropertyMetadata(array $metadata, $className, $field)
    {
        $propertyMetadata = $this->propertySearchMetadataFactory->create($className, $field);
        $propertyMetadata->key = $metadata["key"];
        $propertyMetadata->type = isset($metadata["type"])? $metadata["type"]: "string";
        $propertyMetadata->field = isset($metadata["fields"])? $metadata["fields"]: $field;

        return $propertyMetadata;
    }

    /**
     * @return string
     */
    protected function getExtension()
    {
        return 'yml';
    }
}
