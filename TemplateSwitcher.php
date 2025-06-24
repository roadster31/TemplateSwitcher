<?php

/*      This file is part of the Thelia package. */

/*      Copyright (c) OpenStudio */
/*      email : dev@thelia.net */
/*      web : http://www.thelia.net */

/*      For the full copyright and license information, please view the LICENSE.txt */
/*      file that was distributed with this source code. */

namespace TemplateSwitcher;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator;
use TemplateSwitcher\Template\SessionTemplateHelper;
use Thelia\Module\BaseModule;

class TemplateSwitcher extends BaseModule
{
    /** @var string */
    public const DOMAIN_NAME = 'templateswitcher';

    public const ACTIVE_TEMPLATE_VAR_PREFIX = 'templateswitcher.';

    /**
     * @param string|number $templateDefinition
     */
    public static function getActiveTemplateVarName($templateDefinition): string
    {
        return self::ACTIVE_TEMPLATE_VAR_PREFIX.$templateDefinition;
    }

    public static function configureServices(ServicesConfigurator $servicesConfigurator): void
    {
        $servicesConfigurator->load(self::getModuleCode().'\\', __DIR__)
            ->exclude([THELIA_MODULE_DIR.ucfirst(self::getModuleCode()).'/I18n/*'])
            ->autowire(true)
            ->autoconfigure(true);
    }

    /**
     * Override thelia.template_helper default service with SessionTemplateHelper.
     */
    public static function configureContainer(ContainerConfigurator $containerConfigurator): void
    {
        $services = $containerConfigurator->services();

        $services->set('thelia.template_helper', SessionTemplateHelper::class);
    }
}
