# Herbie Lipsum Plugin

`Lipsum` ist ein [Herbie](http://github.com/getherbie/herbie) Plugin, mit dem du ganz einfach Blindtexte auf deiner Website ausgeben kannst.

## Installation

Das Plugin installierst du via Composer.

	$ composer require getherbie/plugin-lipsum

Danach aktivierst du das Plugin in der Konfigurationsdatei.

    plugins:
        enable:
            - lipsum


## Konfiguration

Unter `plugins.config.lipsum` stehen dir die folgenden Optionen zur Verfügung:

    # enable shortcodes
    shortcode: true
    
    # enable twig functions
    twig: false
    
    
## Anwendung

Die Shortcodes können wie folgt angewendet werden:

    [lipsum_titel]

    [lipsum_text]

    [lipsum_image width="240" height="120" category="abstract"]

Mit dem Aktivieren der Twig-Funktionen kannst du diese auch in Layoutdateien einsetzen:
     
    {{ lipsum_titel() }}    

    {{ lipsum_text() }}    

    {{ lipsum_image(width="240", height="120", category="abstract") }}    
    
    
