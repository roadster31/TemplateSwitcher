<?php
/*************************************************************************************/
/*      Copyright (c) Franck Allimant, CQFDev                                        */
/*      email : thelia@cqfdev.fr                                                     */
/*      web : http://www.cqfdev.fr                                                   */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE      */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

/**
 * Created by Franck Allimant, CQFDev <franck@cqfdev.fr>
 * Date: 26/04/2017 11:04
 */
namespace TemplateSwitcher\Events;

use Thelia\Core\Event\ActionEvent;

class TemplateSwitcherEvent extends ActionEvent
{
    const SWITCH_TEMPLATE_EVENT = "templateswitcher.switch-to";

    /** @var  string */
    protected $templateName;

    /**
     * TemplateSwitcherEvent constructor.
     * @param string $templateName
     */
    public function __construct($templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * @param string $templateName
     * @return $this
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
        return $this;
    }
}
