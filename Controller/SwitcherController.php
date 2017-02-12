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

use TemplateSwitcher\TemplateSwitcher;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Tools\URL;

class SwitcherController extends BaseFrontController
{
    public function set($templateName)
    {
        $this->getSession()->set(TemplateSwitcher::ACTIVE_FRONT_VAR_NAME, $templateName);
        
        return $this->generateRedirect(URL::getInstance()->absoluteUrl('/'));
    }
}
