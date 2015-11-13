<?php

namespace OpenOrchestra\Pagination\Configuration;

/**
 * Interface PaginationRepositoryInterface
 */
interface PaginationRepositoryInterface extends FilterRepositoryInterface
{
    /**
     * @param PaginateFinderConfiguration $configuration
     *
     * @return array
     */
    public function findForPaginate(PaginateFinderConfiguration $configuration);

    /**
     * @return int
     */
    public function count();

    /**
     * @param FinderConfiguration $configuration
     *
     * @return mixed
     */
    public function countWithFilter(FinderConfiguration $configuration);

}