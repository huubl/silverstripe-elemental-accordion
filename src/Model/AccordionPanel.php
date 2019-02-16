<?php

namespace Dynamic\Elements\Accordion\Model;

use DNADesign\Elemental\Forms\TextCheckboxGroupField;
use Dynamic\BaseObject\Model\BaseElementObject;
use Dynamic\Elements\Accordion\Elements\ElementAccordion;
use Sheadawson\Linkable\Forms\LinkField;
use Sheadawson\Linkable\Models\Link;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

/**
 * Class AccordionPanel
 * @package Dynamic\Elements\Accordion\Model
 *
 * @property int $Sort
 *
 * @property int AccordionID
 * @method ElementAccordion Accordion()
 */
class AccordionPanel extends BaseElementObject
{
    /**
     * @var array
     */
    private static $db = [
        'Sort' => 'Int',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Accordion' => ElementAccordion::class,
    ];

    /**
     * @var array Show the panel $Title by default
     */
    private static $defaults = [
        'ShowTitle' => true,
    ];

    /**
     * @var string
     */
    private static $default_sort = 'Sort';

    /**
     * @var string Database table name, default's to the fully qualified name
     */
    private static $table_name = 'AccordionPanel';

    /**
     * @return FieldList
     *
     * @throws \Exception
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            /** @var FieldList $fields */
            $fields->removeByName([
                'Sort',
                'AccordionID',
            ]);

            $fields->dataFieldByName('Image')
                ->setFolderName('Uploads/Elements/Accordions');
        });

        return parent::getCMSFields();
    }

    /**
     * @return null
     */
    public function getPage()
    {
        $page = null;

        if ($this->AccordionID) {
            if ($this->Accordion()->hasMethod('getPage')) {
                $page = $this->Accordion()->getPage();
            }
        }

        return $page;
    }
}
