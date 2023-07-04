# Template Switcher

Ce module permet de changer un template front à la volée, en utilisant l'URL `/ts/<nom-du-template-front>`,
par exemple http://domain.tld/ts/my_super_template

`<nom-du-template-front>` doit être le nom d'un sous-répertoire de `templates/frontOffice`.

C'est tout.

D'un loint de vue technique, le module définit un template Helper qui va chercher le template actif en session
plutôt qu'en base de données.

Le module propose un évènement TemplateSwitcherEvent, à dispatcher avec TemplateSwitcherEvent::SWITCH_TEMPLATE_EVENT.
Il permet de changer n'importe quel template, pas seulement le front. Exemple :
 
    $event = new TemplateSwitcherEvent('nom-du-template')
    $event->setTemplateType(TemplateDefinition::BACK_OFFICE)
    $this->getDispatcher()->dispatch(
        TemplateSwitcherEvent::SWITCH_TEMPLATE_EVENT,
        new TemplateSwitcherEvent('nom-du-template')
    );
