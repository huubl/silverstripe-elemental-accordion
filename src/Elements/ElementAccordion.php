<?php

namespace Dynamic\Elements\Accordion\Elements;

use DNADesign\Elemental\Models\BaseElement;
use Dynamic\Elements\Accordion\Model\AccordionPanel;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

/**
 * Class ElementAccordion
 * @package Dynamic\Elements\Accordion\Elements
 *
 * @property string $Content
 *
 * @method \SilverStripe\ORM\HasManyList Panels()
 */
class ElementAccordion extends BaseElement
{
    /**
     * @var string
     */
    private static $icon = 'font-icon-block-content';

    /**
     * @var string
     */
    private static $table_name = 'ElementAccordion';

    /**
     * @var string
     */
    private static $description = 'Display content in collapsable panels.';

    /**
     * @var array
     */
    private static $db = [
        'Content' => 'HTMLText',
    ];

    /**
     * @var array
     */
    private static $has_many = array(
        'Panels' => AccordionPanel::class,
    );

    /**
     * Set to false to prevent an in-line edit form from showing in an elemental area. Instead the element will be
     * clickable and a GridFieldDetailForm will be used.
     *
     * @config
     * @var bool
     */
    private static $inline_editable = false;

    /**
     * @param bool $includerelations
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);
        $labels['Panels'] = _t(__CLASS__ . '.PanelsLabel', 'Accordion panels');

        return $labels;
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {
            /* @var FieldList $fields */
            $fields->removeByName(array(
                'Sort',
            ));

            $fields->dataFieldByName('Content')
                ->setRows(8);

            if ($this->ID) {
                /** @var GridField $panels */
                $panels = $fields->dataFieldByName('Panels');
                $panels->setTitle($this->fieldLabel('Panels'));

                $fields->removeByName('Panels');

                $config = $panels->getConfig();
                $config->addComponent(new GridFieldOrderableRows('Sort'));
                $config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
                $config->removeComponentsByType(GridFieldDeleteAction::class);

                $fields->addFieldToTab('Root.Main', $panels);
            }
        });

        return parent::getCMSFields();
    }

    /**
     * @return DBHTMLText
     */
    public function getSummary()
    {
        if ($this->Panels()->count() == 1) {
            $label = ' panel';
        } else {
            $label = ' panels';
        }
        return DBField::create_field('HTMLText', $this->Panels()->count() . $label)->Summary(20);
    }

    /**
     * @return array
     */
    protected function provideBlockSchema()
    {
        $blockSchema = parent::provideBlockSchema();
        $blockSchema['content'] = $this->getSummary();
        return $blockSchema;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__.'.BlockType', 'Accordion');
    }
}
