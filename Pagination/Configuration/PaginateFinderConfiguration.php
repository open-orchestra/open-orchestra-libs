<?php

namespace OpenOrchestra\Pagination\Configuration;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaginateFinderConfiguration
 */
class PaginateFinderConfiguration
{
    protected $order = null;
    protected $skip = 0;
    protected $limit = 10;
    protected $search = array();

    /**
     * @param null|array $order
     * @param null|int   $skip
     * @param null|int   $limit
     * @param array      $mapping
     * @apram null|array search
     */
    public function setPaginateConfiguration($order = null, $skip = null, $limit = null, array $mapping, $search = null)
    {
        if (null !== $limit) {
            $this->setLimit($limit);
        }

        $this->setOrder($order, $mapping);

        if (null !== $skip) {
            $this->setSkip($skip);
        }

        $this->setSearch($search);
    }

    /**
     * @param Request $request
     * @param array   $mapping
     *
     * @return PaginateFinderConfiguration
     */
    public static function generateFromRequest(Request $request, array $mapping = array())
    {
        $configuration = new static();

        $configuration->setPaginateConfiguration(
            $request->get('order'),
            $request->get('start'),
            $request->get('length'),
            $mapping,
            $request->get('search')
        );

        return $configuration;
    }

    /**
     * @param null|array $order
     * @param null|int   $skip
     * @param null|int   $limit
     * @param array      $mapping
     * @param null|array $search
     *
     * @return PaginateFinderConfiguration
     */
    public static function generateFromVariable(
        $order = null,
        $skip = null,
        $limit = null,
        array $mapping = array(),
        $search = null
    ) {
        $configuration = new static();

        $configuration->setPaginateConfiguration($order, $skip, $limit, $mapping, $search);

        return $configuration;
    }

    /**
     * @return array order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param null|array $order
     * @param array      $mapping
     */
    public function setOrder($order, $mapping)
    {
        $sort = array();
        if (isset($order['name']) && isset($order['dir']) && isset($mapping[$order['name']])) {
            $sort = array(
                $mapping[$order['name']] => $order['dir'] == 'desc' ? -1 : 1
            );
        }

        $this->order = $sort;
    }

    /**
     * @return int skip
     */
    public function getSkip()
    {
        return $this->skip;
    }

    /**
     * @param int $skip
     */
    public function setSkip($skip)
    {
        $this->skip = $this->getIntOrNull($skip);
    }

    /**
     * @return int limit
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $this->getIntOrNull($limit);
    }

    /**
     * @param int|null $value
     *
     * @return int|null
     */
    protected function getIntOrNull($value)
    {
        return $value === null ? null : (int) $value;
    }

    /**
     * @return null|array
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param string $index
     *
     * @return mixed search
     */
    public function getSearchIndex($index)
    {
        return isset($this->search[$index]) ? $this->search[$index] : null;
    }

    /**
     * @param array $search
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

    /**
     * @param string $column
     * @param mixed  $value
     */
    public function addSearch($column, $value)
    {
        $this->search[$column] = $value;
    }
}
