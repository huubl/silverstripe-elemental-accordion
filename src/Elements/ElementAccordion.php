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
     * @var array
     */
    private static $owns = [
        'Panels',
    ];

    /**
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

        $labels['Content'] = _t(__CLASS__.'.ContentLabel', 'Intro');
        $labels['Panels'] = _t(__CLASS__ . '.PanelsLabel', 'Panels');

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
                ->setDescription(_t(
                    __CLASS__.'.ContentDescription',
                    'optional. Add introductory copy to your accordion.'
                ))
                ->setRows(5);

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
        $count = $this->Panels()->count();
        $label = _t(
            AccordionPanel::class . '.PLURALS',
            '{count} Accordion Panel|{count} Accordion Panels',
            [ 'count' => $count ]
        );
        return DBField::create_field('HTMLText', $label)->Summary(20);
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
