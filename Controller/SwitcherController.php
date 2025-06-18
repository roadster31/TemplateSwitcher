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
 * Date: 09/02/2017 22:45
 */
namespace TemplateSwitcher\Controller;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use TemplateSwitcher\Events\TemplateSwitcherEvent;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Tools\URL;

class SwitcherController extends BaseFrontController
{
    public function __construct(protected EventDispatcherInterface $dispatcher)
    {
    }

    public function set($templateName)
    {
        $this->getDispatcher()->dispatch((new TemplateSwitcherEvent($templateName))
            ->setTemplateType(TemplateDefinition::FRONT_OFFICE), TemplateSwitcherEvent::SWITCH_TEMPLATE_EVENT);

        return $this->generateRedirect(URL::getInstance()->absoluteUrl('/'));
    }

    public function getDispatcher(): EventDispatcherInterface
    {
        return $this->dispatcher;
    }
}
