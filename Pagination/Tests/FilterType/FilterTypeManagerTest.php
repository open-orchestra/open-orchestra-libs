<?php

namespace OpenOrchestra\Pagination\Tests\FilterType;

use OpenOrchestra\Pagination\FilterType\FilterTypeManager;
use OpenOrchestra\Pagination\Tests\AbstractBaseTestCase;
use Phake;

/**
 * Class FilterTypeManagerTest
 */
class FilterTypeManagerTest extends AbstractBaseTestCase
{
    /**
     * @var FilterTypeManager
     */
    protected $manager;
    protected $filterStrategie;

    /**
     * Set Up
     */
    public function setUp()
    {
        $this->filterStrategie = Phake::mock('OpenOrchestra\Pagination\FilterType\FilterTypeInterface');
        Phake::when($this->filterStrategie)->support(Phake::anyParameters())->thenReturn(true);
        Phake::when($this->filterStrategie)->generateFilter(Phake::anyParameters())->thenReturn('fakeFilter');

        $this->manager = new FilterTypeManager();
    }

    /**
     * Test generateFilter
     */
    public function testGenerateFilter()
    {
        $filter = $this->manager->generateFilter('fakeType','fakeName','fakeValue', 'fakeDocumentName');
        $this->assertSame(null, $filter);

        $this->manager->addStrategy($this->filterStrategie);
        $filter = $this->manager->generateFilter('fakeType','fakeName','fakeValue', 'fakeDocumentName');
        $this->assertSame('fakeFilter', $filter);
    }
}
