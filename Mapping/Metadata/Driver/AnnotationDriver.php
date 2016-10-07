<?php

namespace OpenOrchestra\Mapping\Metadata\Driver;

use Doctrine\Common\Annotations\AnnotationReader;
use Metadata\Driver\DriverInterface;
use OpenOrchestra\Mapping\Annotations\Search;
use OpenOrchestra\Mapping\Metadata\MergeableClassMetadataFactoryInterface;
use OpenOrchestra\Mapping\Metadata\PropertyCollectionSearchMetadata;
use OpenOrchestra\Mapping\Metadata\PropertySearchMetadataFactoryInterface;
use ReflectionProperty;
use ReflectionClass;

/**
 * Class AnnotationDriver
 */
class AnnotationDriver implements DriverInterface
{
    protected $reader;
    protected $propertySearchMetadataFactory;
    protected $mergeableClassMetadataFactory;
    protected $annotationClass;

    /**
     * @param AnnotationReader                       $reader
     * @param PropertySearchMetadataFactoryInterface $propertySearchMetadataFactory
     * @param MergeableClassMetadataFactoryInterface $mergeableClassMetadataFactory
     * @param string                                 $annotationClass
     */
    public function __construct(
        AnnotationReader $reader,
        PropertySearchMetadataFactoryInterface $propertySearchMetadataFactory,
        MergeableClassMetadataFactoryInterface $mergeableClassMetadataFactory,
        $annotationClass
    )
    {
        $this->reader = $reader;
        $this->propertySearchMetadataFactory = $propertySearchMetadataFactory;
        $this->mergeableClassMetadataFactory = $mergeableClassMetadataFactory;
        $this->annotationClass = $annotationClass;
    }

    /**
     * @param ReflectionClass $class
     *
     * @return \Metadata\ClassMetadata|null
     */
    public function loadMetadataForClass(ReflectionClass $class)
    {
        $classMetadata = $this->mergeableClassMetadataFactory->create($class->getName());
        $existAnnotation = false;
        foreach ($class->getProperties() as $reflectionProperty) {

            $annotations = $this->reader->getPropertyAnnotations(
                $reflectionProperty,
                $this->annotationClass
            );

            if (!empty($annotations)) {
                $metadata = $this->getPropertyMetadataSearch($annotations, $class, $reflectionProperty);
                if (null !== $metadata) {
                    $existAnnotation = true;
                    $classMetadata->addPropertyMetadata($metadata);
                }
            }
        }

        return (true === $existAnnotation) ? $classMetadata : null;
    }

    /**
     * @param array              $annotations
     * @param ReflectionClass    $class
     * @param ReflectionProperty $reflectionProperty
     *
     * @return null|PropertyCollectionSearchMetadata
     */
    protected function getPropertyMetadataSearch(array $annotations, ReflectionClass $class, \ReflectionProperty $reflectionProperty) {

        $listPropertyMetadata = array();
        /** @var Search $annotation */
        foreach ($annotations as $annotation) {
            if (get_class($annotation) == $this->annotationClass) {
                $propertyMetadata = $this->propertySearchMetadataFactory->create($class->getName(), $reflectionProperty->getName());
                $propertyMetadata->key = $annotation->getKey();
                $propertyMetadata->type = $annotation->getType();
                $propertyMetadata->field = (null === $annotation->getField()) ? $reflectionProperty->getName() : $annotation->getField() ;

                $listPropertyMetadata[] = $propertyMetadata;
            }
        }

        if (empty($listPropertyMetadata)) {
            return null;
        }

        $multipleSearchMetadata = $this->propertySearchMetadataFactory->createCollection($class->getName(), $reflectionProperty->getName());
        $multipleSearchMetadata->propertySearchMetadata = $listPropertyMetadata;

        return $multipleSearchMetadata;

    }
}
