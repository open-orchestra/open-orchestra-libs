<?php

namespace OpenOrchestra\Pagination\FilterType;

/**
 * Class FilterTypeManager
 */
class FilterTypeManager
{
    protected $filters = array();

    /**
     * @param FilterTypeInterface $filter
     */
    public function addStrategy(FilterTypeInterface $filter)
    {
        $this->filters[$filter->getName()] = $filter;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $type
     * @param string $documentName
     * @param string $format
     *
     * @return null|array
     */
    public function generateFilter($type, $name, $value, $documentName='', $format='')
    {
        /**
         * @var FilterTypeInterface $filterType
         */
        foreach ($this->filters as $filterType){
            if ($filterType->support($type)) {
                return $filterType->generateFilter($name, $value, $documentName, $format);
            }
        }

        return null;
    }
}
