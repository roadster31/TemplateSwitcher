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

/**
 * @author Franck Allimant <franck@cqfdev.fr>
 * Creation date: 26/03/2015 16:36
 */
namespace TemplateSwitcher\Template;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\RequestStack;
use TemplateSwitcher\TemplateSwitcher;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Core\Template\TheliaTemplateHelper;
use Thelia\Core\Translation\Translator;

class SessionTemplateHelper extends TheliaTemplateHelper
{
    protected $translationsLoaded = false;

    /**
     * SessionTheliaTemplateHelper constructor.
     * @param RequestStack $requestStack
     * @param $kernelCacheDir
     */
    public function __construct(
        protected RequestStack $requestStack,
        #[Autowire('%kernel.cache_dir%')] protected $kernelCacheDir
    )
    {
        parent::__construct($kernelCacheDir);
    }

    /**
     * Check if a template definition is the current active template
     *
     * @param  TemplateDefinition $tplDefinition
     * @return bool               true is the given template is the active template
     */
    public function isActive(TemplateDefinition $tplDefinition): bool
    {
        return $tplDefinition->getName() === $this
                ->getSessionTplName(TemplateSwitcher::getActiveTemplateVarName($tplDefinition->getType()));
    }

    /**
     * @return TemplateDefinition
     * @throws \Exception
     */
    public function getActiveFrontTemplate(): TemplateDefinition
    {
        return $this->getActiveTemplate(
            TemplateDefinition::FRONT_OFFICE,
            parent::getActiveFrontTemplate()
        );
    }

    /**
     * @return TemplateDefinition
     * @throws \Exception
     */
    public function getActiveMailTemplate(): TemplateDefinition
    {
        return $this->getActiveTemplate(
            TemplateDefinition::EMAIL,
            parent::getActiveMailTemplate()
        );
    }

    /**
     * @return TemplateDefinition
     * @throws \Exception
     */
    public function getActivePdfTemplate(): TemplateDefinition
    {
        return $this->getActiveTemplate(
            TemplateDefinition::PDF,
            parent::getActivePdfTemplate()
        );
    }

    /**
     * @return TemplateDefinition
     * @throws \Exception
     */
    public function getActiveAdminTemplate(): TemplateDefinition
    {
        return $this->getActiveTemplate(
            TemplateDefinition::BACK_OFFICE,
            parent::getActiveAdminTemplate()
        );
    }

    /**
     * @param $templateType
     * @param $default
     * @return TemplateDefinition
     * @throws \Exception
     */
    protected function getActiveTemplate($templateType, $default): TemplateDefinition
    {
        static $activeTemplateCache = [];

        if (null === $sessionTplName = $this
                ->getSessionTplName(TemplateSwitcher::getActiveTemplateVarName($templateType))) {
            return $default;
        }

        if (! isset($activeTemplateCache[$templateType])) {
            $tplDef = new TemplateDefinition(
                $sessionTplName,
                $templateType
            );

            // Etre sur de charger les ressources de langue de ce template, et des templates parent
            if (!$this->translationsLoaded) {
                /** @var TemplateDefinition $parentTemplate */
                foreach ($tplDef->getParentList() as $parentTemplate) {
                    $this->loadTranslation(
                        $parentTemplate->getAbsoluteI18nPath(),
                        $parentTemplate->getTranslationDomain()
                    );
                }

                $this->loadTranslation($tplDef->getAbsoluteI18nPath(), $tplDef->getTranslationDomain());

                $this->translationsLoaded = true;
            }

            if (!is_dir($tplDef->getAbsolutePath())) {
                throw new \InvalidArgumentException("Template directory '$sessionTplName' not found.");
            }

            $activeTemplateCache[$templateType] = $tplDef;
        }

        return $activeTemplateCache[$templateType];
    }

    protected function getSessionTplName($templateVar)
    {
        $request = $this->requestStack->getCurrentRequest();

        // Request maybe null when the container is built.
        return $request?->getSession()?->get($templateVar);
    }

    private function loadTranslation($directory, $domain)
    {
        try {
            $finder = Finder::create()
                ->files()
                ->depth(0)
                ->in($directory);

            /** @var \DirectoryIterator $file */
            foreach ($finder as $file) {
                list($locale, $format) = explode('.', $file->getBaseName(), 2);

                Translator::getInstance()->addResource($format, $file->getPathname(), $locale, $domain);
            }
        } catch (\InvalidArgumentException $ex) {
            // Ignore missing I18n directories
        }
    }
}
