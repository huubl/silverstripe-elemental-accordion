<?php

namespace Dynamic\Elements\Accordion\Tests;

use Dynamic\Elements\Accordion\Elements\ElementAccordion;
use Dynamic\Elements\Accordion\Model\AccordionPanel;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;

class ElementAccordionTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = '../fixtures.yml';

    /**
     *
     */
    public function testGetCMSFields()
    {
        $object = $this->objFromFixture(ElementAccordion::class, 'one');
        $fields = $object->getCMSFields();
        $this->assertInstanceOf(FieldList::class, $fields);
        $this->assertNotNull($fields->dataFieldByName('Panels'));
    }

    /**
     *
     */
    public function testGetSummary()
    {
        $object = $this->objFromFixture(ElementAccordion::class, 'one');
        $count = $object->Panels()->count();
        $this->assertEquals($object->getSummary(), _t(
            AccordionPanel::class . 'PLURALS',
            '{count} Accordion Panel|{count} Accordion Panels',
            [ 'count' => $count ]
        ));
    }

    /**
     *
     */
    public function testGetType()
    {
        $object = Injector::inst()->create(ElementAccordion::class);
        $this->assertEquals($object->getType(), 'Accordion');
    }
}
