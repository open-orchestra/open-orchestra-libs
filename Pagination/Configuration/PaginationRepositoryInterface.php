<?php

namespace OpenOrchestra\Pagination\Configuration;

/**
 * Interface PaginationRepositoryInterface
 */
interface PaginationRepositoryInterface extends FilterRepositoryInterface
{
    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return object The object.
     */
    public function find($id);

    /**
     * @param PaginateFinderConfiguration $configuration
     *
     * @return array
     */
    public function findForPaginate(PaginateFinderConfiguration $configuration);

    /**
     * @param FinderConfiguration|null $configuration
     *
     * @return int
     */
    public function count(FinderConfiguration $configuration = null);

    /**
     * @param FinderConfiguration $configuration
     *
     * @return mixed
     */
    public function countWithFilter(FinderConfiguration $configuration);
}
