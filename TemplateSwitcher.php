<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace TemplateSwitcher;

use Thelia\Module\BaseModule;

class TemplateSwitcher extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'templateswitcher';
    
    const ACTIVE_FRONT_VAR_NAME = 'templateswitcher.front';
}
