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

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\RequestStack;
use TemplateSwitcher\TemplateSwitcher;
use Thelia\Core\Template\TemplateDefinition;
use Thelia\Core\Template\TheliaTemplateHelper;
use Thelia\Core\Translation\Translator;

class SessionTemplateHelper extends TheliaTemplateHelper
{
    /** @var  RequestStack */
    protected $requestStack;
    
    protected $translationsLoaded = false;
    
    /**
     * SessionTheliaTemplateHelper constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    /**
     * Check if a template definition is the current active template
     *
     * @param  TemplateDefinition $tplDefinition
     * @return bool               true is the given template is the active template
     */
    public function isActive(TemplateDefinition $tplDefinition)
    {
        if ($tplDefinition->getType() === TemplateDefinition::FRONT_OFFICE) {
            return $tplDefinition->getName() == $this->getSessionTplName();
        } else {
            return parent::isActive($tplDefinition);
        }
    }
    
    /**
     * @return TemplateDefinition
     */
    public function getActiveFrontTemplate()
    {
        if (null === $sessionTplName = $this->getSessionTplName()) {
            return parent::getActiveFrontTemplate();
        }
        
        $tplDef = new TemplateDefinition(
            $sessionTplName,
            TemplateDefinition::FRONT_OFFICE
        );
        
        // Etre sur de charger les ressources de langue de ce template
        if (! $this->translationsLoaded) {
            $this->loadTranslation($tplDef->getAbsoluteI18nPath(), $tplDef->getTranslationDomain());
            
            $this->translationsLoaded = true;
        }
    
        if (! is_dir($tplDef->getAbsolutePath())) {
            throw new \InvalidArgumentException("Template directory '$sessionTplName' not found.");
        }
        
        return $tplDef;
    }
    
    protected function getSessionTplName()
    {
        $request = $this->requestStack->getCurrentRequest();
        
        // Request maybe null when the container is built.
        if (null === $request) {
            return null;
        }
        
        return $request->getSession()->get(TemplateSwitcher::ACTIVE_FRONT_VAR_NAME, null);
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
