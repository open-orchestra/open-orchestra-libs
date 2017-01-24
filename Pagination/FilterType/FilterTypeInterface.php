<?php

namespace OpenOrchestra\Pagination\FilterType;

/**
 * Interface FilterTypeInterface
 */
interface FilterTypeInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function support($type);

    /**
     * @param string $name
     * @param string $value
     * @param string $documentName
     * @param string $format
     *
     * @return array
     */
    public function generateFilter($name, $value, $documentName='', $format='');

    /**
     * @return string
     */
    public function getName();
}
