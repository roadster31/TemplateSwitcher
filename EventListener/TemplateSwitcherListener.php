<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace TemplateSwitcher\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use TemplateSwitcher\TemplateSwitcher;
use TemplateSwitcher\Events\TemplateSwitcherEvent;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Core\Template\TemplateHelperInterface;

/**
 * Template switcher event listener
 *
 * @author Franck Allimant <franck@cqfdev.fr>
 */
class TemplateSwitcherListener implements EventSubscriberInterface
{
    /** @var  RequestStack */
    protected $requestStack;

    /** @var  TemplateHelperInterface */
    protected $templateHelper;

    /**
     * TemplateSwitcherListener constructor.
     * @param RequestStack $request
     * @param TemplateHelperInterface $templateHelper
     */
    public function __construct(RequestStack $request, TemplateHelperInterface $templateHelper)
    {
        $this->requestStack = $request;
        $this->templateHelper = $templateHelper;
    }


    public function switchTo(TemplateSwitcherEvent $event)
    {
        // Check template name
        $tplList = $this->templateHelper->getList($event->getTemplateType());

        $requiredTemplateName = $event->getTemplateName();

        /** @var TemplateDefinition $tpl */
        foreach ($tplList as $tpl) {
            if ($tpl->getName() === $requiredTemplateName) {
                $this->requestStack
                    ->getCurrentRequest()
                    ->getSession()
                    ->set(TemplateSwitcher::getActiveTemplateVarName($event->getTemplateType()), $requiredTemplateName);

                break;
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            TemplateSwitcherEvent::SWITCH_TEMPLATE_EVENT => array("switchTo", 128)
        );
    }
}
