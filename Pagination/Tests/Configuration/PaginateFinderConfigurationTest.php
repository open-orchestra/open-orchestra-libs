<?php

namespace OpenOrchestra\Tests\Pagination\Configuration;

use OpenOrchestra\Pagination\Configuration\PaginateFinderConfiguration;
use OpenOrchestra\Pagination\Tests\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaginateFinderConfigurationTest
 */
class PaginateFinderConfigurationTest extends AbstractBaseTestCase
{
    /**
     * @param array       $mapping
     * @param array|null  $search
     * @param array|null  $order
     * @param int|null    $limit
     * @param int|null    $skip
     *
     * @dataProvider provideConfigurationCreation
     */
    public function testGenerateFromRequest($mapping, $search, $order, $limit, $skip)
    {
        $request = $this->createRequest($search, $order, $limit, $skip);
        $paginateConfiguration = PaginateFinderConfiguration::generateFromRequest($request, $mapping);
        $this->finderPaginateConfigurationTest($paginateConfiguration, $order, $limit, $skip, $search);
    }

    /**
     * @param array       $mapping
     * @param array|null  $search
     * @param array|null  $order
     * @param int|null    $limit
     * @param int|null    $skip
     *
     * @dataProvider provideConfigurationCreation
     */
    public function testPaginateVarGeneration(array $mapping, $search, $order, $limit, $skip)
    {
        $configuration = PaginateFinderConfiguration::generateFromVariable($order, $skip, $limit, $mapping, $search);
        $this->finderPaginateConfigurationTest($configuration, $order, $limit, $skip, $search);
    }

    /**
     * @return array
     */
    public function provideConfigurationCreation()
    {
        return array(
            array(array(),array(), array(), 0, 1),
            array(array(),array('global' =>'fakeSearch'), array(), null, null),
            array(array(),array('columns' => array()), array(), -1, 0),
        );
    }

    /**
     * @param PaginateFinderConfiguration $configuration
     * @param array|null                  $order
     * @param int|null                    $limit
     * @param int|null                    $skip
     * @param array|null                  $search
     */
    protected function finderPaginateConfigurationTest(PaginateFinderConfiguration $configuration, $order, $limit, $skip, $search)
    {
        $this->isTypeOrNull("is_array", $configuration->getSearch(), $search);
        $this->isTypeOrNull("is_array", $configuration->getOrder(), $order);
        $this->isTypeOrNull("is_int", $configuration->getLimit(), $limit);
        $this->isTypeOrNull("is_int", $configuration->getSkip(), $skip);
    }

    /**
     * @param string $method
     * @param mixed  $valueToTest
     * @param mixed  $testValue
     */
    protected function isTypeOrNull($method, $valueToTest, $testValue)
    {
        if($method($testValue)) {
            $this->assertEquals($valueToTest, $testValue);
        } else {
            $this->assertEquals($valueToTest, null);
        }
    }

    /**
     * @param null|string $search
     * @param null|array  $order
     * @param null|int    $length
     * @param null|int    $start
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function createRequest($search = null, $order = null, $length = null, $start = null)
    {
        $request = new Request();
        if($search !== null) {
            $request->request->set('search', $search);
        }
        if($order !== null) {
            $request->request->set('order', $order);
        }
        if($length !== null) {
            $request->request->set('length', $length);
        }
        if($start !== null) {
            $request->request->set('start', $start);
        }

        return $request;
    }
}
