<?php

namespace OpenOrchestra\Pagination\Tests\MongoTrait\FilterTypeStrategy\Strategies;

use OpenOrchestra\Pagination\MongoTrait\FilterTypeStrategy\Strategies\ReferenceFilterStrategy;
use Phake;
use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\Repository\AbstractAggregateRepository;

/**
 * Class ReferenceFilterStrategyTest
 */
class ReferenceFilterStrategyTest extends \PHPUnit_Framework_TestCase
{
    protected $documentManager;
    protected $value = 'fakeValue';
    protected $documentName = 'fakeDocumentName';
    protected $id0 = '000000000000000000000000';
    protected $id1 = 'aaaaaaaaaaaaaaaaaaaaaaaa';
    protected $targetDocument = 'fakeTargetDocument';

    /**
     * @var ReferenceFilterStrategy
     */
    protected $strategy;

    /**
     * Set up
     */
    public function setUp()
    {
        $mapping = array();
        $this->documentManager = Phake::mock('Doctrine\ODM\MongoDB\DocumentManager');
        $searchMappingReader = Phake::mock('OpenOrchestra\Mapping\Reader\SearchMappingReader');
        $aggregationQueryBuilder = Phake::mock('Solution\MongoAggregationBundle\AggregateQuery\AggregationQueryBuilder');
        $filterTypeManager = Phake::mock('OpenOrchestra\Pagination\MongoTrait\FilterTypeStrategy\FilterTypeManager');
        $repository = Phake::mock('OpenOrchestra\Pagination\Tests\MongoTrait\FilterTypeStrategy\Strategies\PhakeRepository');
        $getId0 = Phake::mock('OpenOrchestra\Pagination\Tests\MongoTrait\FilterTypeStrategy\Strategies\PhakeGetIdInterface');
        $getId1 = Phake::mock('OpenOrchestra\Pagination\Tests\MongoTrait\FilterTypeStrategy\Strategies\PhakeGetIdInterface');
        $metadata = Phake::mock('Doctrine\ODM\MongoDB\Mapping\ClassMetadata');
        $referencedDocuments = new ArrayCollection();
        $referencedDocuments->add($getId0);
        $referencedDocuments->add($getId1);
        Phake::when($metadata)->getFieldMapping(Phake::anyParameters())->thenReturn(array('targetDocument' => $this->targetDocument));
        Phake::when($searchMappingReader)->extractMapping($this->targetDocument)->thenReturn($mapping);
        Phake::when($this->documentManager)->getClassMetadata($this->documentName)->thenReturn($metadata);
        Phake::when($this->documentManager)->getRepository($this->targetDocument)->thenReturn($repository);
        Phake::when($getId0)->getId()->thenReturn($this->id0);
        Phake::when($getId1)->getId()->thenReturn($this->id1);
        Phake::when($repository)->findForPaginate(Phake::anyParameters())->thenReturn($referencedDocuments);
        $this->strategy = new ReferenceFilterStrategy($this->documentManager, $searchMappingReader, $aggregationQueryBuilder, $filterTypeManager);
    }

    /**
     * @param string $type
     * @param bool   $expected
     *
     * @dataProvider provideSupport
     */
    public function testSupport($type, $expected)
    {
        $output = $this->strategy->support($type);
        $this->assertEquals($expected, $output);
    }

    /**
     * @return array
     */
    public function provideSupport()
    {
        return array(
            array('boolean', false),
            array('string', false),
            array('integer', false),
            array('', false),
            array(null, false),
            array('reference', true),
        );
    }

    /**
     * @param string   $columnsTree
     * @param int|null $countOrFilter
     *
     * @dataProvider provideGenerateFilter
     */
    public function testGenerateFilter($columnsTree, $countOrFilter = null)
    {
        $filter = $this->strategy->generateFilter($columnsTree, $this->value, $this->documentName);
        if (null === $countOrFilter) {
            $this->assertNull($filter);
        } else {
            $this->assertCount($countOrFilter, $filter['$or']);
        }
    }

    /**
     * @return array
     */
    public function provideGenerateFilter()
    {
        return array(
            array('groups.label', 3),
            array('groups'),
            array('groups.$id.label'),
        );
    }

    /**
     * Test Repository without findForPaginate method
     */
    public function testGenerateFilterWithNoMethodFindForPaginate()
    {
        $columnsTree = 'groups.label';
        $repository = Phake::mock('OpenOrchestra\Pagination\Tests\MongoTrait\FilterTypeStrategy\Strategies\PhakeRepositoryWithoutFindForPaginate');
        Phake::when($this->documentManager)->getRepository($this->targetDocument)->thenReturn($repository);
        $filter = $this->strategy->generateFilter($columnsTree, $this->value, $this->documentName);
        $this->assertNull($filter);
    }

    /**
     * Test get name
     */
    public function testGetName()
    {
        $this->assertEquals('reference_filter', $this->strategy->getName());
    }
}

/**
 * Interface PhakeGetIdInterface
 */
interface PhakeGetIdInterface
{
    /**
     * @return string
     */
    public function getId();
}

/**
 * class PhakeRepository
 */
class PhakeRepository extends AbstractAggregateRepository
{
    public function findForPaginate(){}
}

/**
 * class PhakeRepositoryWithoutFindForPaginate
 */
class PhakeRepositoryWithoutFindForPaginate extends AbstractAggregateRepository
{
}
