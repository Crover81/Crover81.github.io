<?php

// URL del tuo sito
$url = 'http://it.rojadirecta.eu/';

// ID del div principale
$divId = 'agendadiv'; // Modificato in minuscolo

// Testo da rimuovere
$textToRemove = 'Rojadirecta';

// Ottieni il contenuto HTML della pagina
$html = file_get_contents($url);

// Crea un oggetto DOMDocument
$dom = new DOMDocument;

// Aggiungi queste linee per visualizzare eventuali errori
libxml_use_internal_errors(true);
libxml_clear_errors();

// Carica l'HTML nel DOMDocument
if (@$dom->loadHTML($html)) {
    // Seleziona il div con l'ID specificato
    $div = $dom->getElementById($divId);

    // Verifica se il div è stato trovato
    if ($div) {
        // Itera sugli elementi span nel div
        $spans = $div->getElementsByTagName('span');
        foreach ($spans as $span) {
            // Rimuovi lo span con classe "es"
            if ($span->getAttribute('class') == 'es') {
                $span->parentNode->removeChild($span);
            }
        }

        // Ottieni il contenuto HTML del div
        $divContent = $dom->saveHTML($div);

        // Rimuovi il testo specificato
        $divContent = str_replace($textToRemove, '', $divContent);

        // Aggiungi un <br> prima di ogni <div class="menutitle"
        $divContent = preg_replace('/<div class="menutitle"[^>]*>/', '<br>$0', $divContent);

        // Modifica il CSS dei menutitle
        $divContent = preg_replace_callback('/<div class="menutitle"[^>]*>.*?<\/div>/', function ($matches) {
            return preg_replace('/<span class="t"[^>]*>/', '<span class="t" style="text-align: left;">', $matches[0]);
        }, $divContent);

        // Centra gli itemprop="name"
        $divContent = preg_replace('/itemprop="name"/', 'itemprop="name" style="text-align: center;"', $divContent);

        // Aumenta la dimensione del testo nei menutitle
        $divContent = preg_replace('/<div class="menutitle"[^>]*>/', '<div class="menutitle" style="font-size: 30px; color: #ffffff;">', $divContent);

        // Modifica il CSS delle celle
        $divContent = preg_replace('/<td[^>]*>/', '<td style="width: 200px; height: 100px; font-size: 18px; font-weight: bold;">', $divContent);

        // Aggiungi attributi alle tabelle con classe "taboastreams"
        $divContent = preg_replace('/<table class="taboastreams"[^>]*>/', '<table class="taboastreams" border="10" cellpadding="10" cellspacing="4" style="margin: 10 auto; text-align: center; background-color: #F5F5F5;">', $divContent);

        // Sostituisci "Guarda" con un pulsante e applica stili CSS
        $divContent = str_replace('<b>Guarda</b>', '<button class="guarda-button">Guarda</button>', $divContent);

        // Aggiungi stili CSS per il pulsante e il wallpaper
        $divContent .= '<style>
          .guarda-button {
            background-color: #4CAF50;
            color: white;
            padding: 25px 30px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 2px;
            cursor: pointer;
          }

          body {
            background-image: url(\'wallpaper.jpg\');
            background-size: cover;
            background-attachment: fixed;
            margin: 0;
          }
        </style>';

        // Stampa l'output formattato
        echo '<div style="text-align: center;">' . $divContent . '</div>';
    } else {
        echo 'Div non trovato';
    }
} else {
    // Stampa gli eventuali errori di caricamento
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        echo $error->message . "<br/>";
    }
    libxml_clear_errors();
}

?>