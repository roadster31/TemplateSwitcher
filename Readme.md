# Template Switcher

Ce module permet de changer le template front actif à la volée, en utilisant l'URL `/ts/<nom-du-template-front>`,
par exemple http://domain.tld/ts/my_super_template

`<nom-du-template-front>` doit être le nom d'un sous-répertoire de `templates/frontOffice`.

C'est tout.

D'un loint de vue technique, le module definit un template Helper qui va chercher le template actif en session
plutôt qu'en base de données.

