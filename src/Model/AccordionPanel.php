<?php

namespace Dynamic\Elements\Accordion\Model;

use DNADesign\Elemental\Forms\TextCheckboxGroupField;
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

class AccordionPanel extends DataObject
{
    /**
     * @var string
     */
    private static $singular_name = 'Accordion Panel';

    /**
     * @var string
     */
    private static $plural_name = 'Accordion Panels';

    /**
     * @var string
     */
    private static $description = 'A panel for a Accordion widget';

    /**
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar(255)',
        'ShowTitle' => 'Boolean',
        'Content' => 'HTMLText',
        'Sort' => 'Int',
    ];
    /**
     * @var array
     */
    private static $has_one = [
        'Accordion' => ElementAccordion::class,
        'Image' => Image::class,
        'ElementLink' => Link::class,
    ];

    /**
     * @var array Related objects to be published recursively on AccordionPanel::publishRecursively()
     */
    private static $owns = [
        'Image',
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

            $fields->removeByName('ShowTitle');
            $fields->replaceField(
                'Title',
                TextCheckboxGroupField::create(
                    TextField::create('Title', _t(__CLASS__ . '.TitleLabel', 'Title (displayed if checked)')),
                    CheckboxField::create('ShowTitle', _t(__CLASS__ . '.ShowTitleLabel', 'Displayed'))
                )
                    ->setName('TitleAndDisplayed')
            );

            // because it defaults to a dropdown menu
            $fields->replaceField('ElementLinkID',
                LinkField::create('ElementLinkID', _t(__CLASS__ . 'LinkLabel', 'Link'))
            );

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

    /**
     * Basic permissions, defaults to page perms where possible.
     *
     * @param Member $member
     * @return boolean
     */
    public function canView($member = null)
    {
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }

        if ($page = $this->getPage()) {
            return $page->canView($member);
        }

        return (Permission::check('CMS_ACCESS', 'any', $member)) ? true : null;
    }

    /**
     * Basic permissions, defaults to page perms where possible.
     *
     * @param Member $member
     *
     * @return boolean
     */
    public function canEdit($member = null)
    {
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }

        if ($page = $this->getPage()) {
            return $page->canEdit($member);
        }

        return (Permission::check('CMS_ACCESS', 'any', $member)) ? true : null;
    }

    /**
     * Basic permissions, defaults to page perms where possible.
     *
     * Uses archive not delete so that current stage is respected i.e if a
     * element is not published, then it can be deleted by someone who doesn't
     * have publishing permissions.
     *
     * @param Member $member
     *
     * @return boolean
     */
    public function canDelete($member = null)
    {
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }

        if ($page = $this->getPage()) {
            return $page->canArchive($member);
        }

        return (Permission::check('CMS_ACCESS', 'any', $member)) ? true : null;
    }

    /**
     * Basic permissions, defaults to page perms where possible.
     *
     * @param Member $member
     * @param array $context
     *
     * @return boolean
     */
    public function canCreate($member = null, $context = array())
    {
        $extended = $this->extendedCan(__FUNCTION__, $member);
        if ($extended !== null) {
            return $extended;
        }

        return (Permission::check('CMS_ACCESS', 'any', $member)) ? true : null;
    }
}
