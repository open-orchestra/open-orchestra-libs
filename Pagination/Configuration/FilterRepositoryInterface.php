<?php

namespace OpenOrchestra\Pagination\Configuration;

use OpenOrchestra\Pagination\FilterType\FilterTypeManager;

/**
 * Interface FilterRepositoryInterface
 */
interface FilterRepositoryInterface
{
    /**
     * @param FilterTypeManager $filterTypeManager
     */
    public function setFilterTypeManager(FilterTypeManager $filterTypeManager);
}
