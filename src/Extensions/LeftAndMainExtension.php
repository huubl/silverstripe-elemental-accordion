<?php

namespace Dynamic\Elements\Accordion\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\View\Requirements;

/**
 * Class LeftAndMainExtension.
 */
class LeftAndMainExtension extends Extension
{
    public function init()
    {
        Requirements::css('dynamic/silverstripe-elemental-accordion: icons/icons.css');
    }
}
