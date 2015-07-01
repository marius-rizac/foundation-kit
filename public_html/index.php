<?php

/**
 * https://getcomposer.org/doc/04-schema.md
 */

require_once dirname(__DIR__) . '/app/config/config.php';
require_once SYSTEM . '/vendor/autoload.php';

Twig_Autoloader::register();

$directories = array(
    LAYOUT . '/default/',
    LAYOUT . '/views/',
);

use Symfony\Component\Yaml\Yaml;

$routes = Yaml::parse(file_get_contents(CONFIG . '/routes.yml'));

$route = SdkRouter::getInstance();
$route->registerRoutes($routes);

$twigData = TwigData::getInstance($directories);
$loader = new Twig_Loader_Filesystem($twigData->getDirs());

$twig_config = array();
// enable debug in twig
$twig_config['debug'] = true;

$twig = new Twig_Environment($loader, $twig_config);
$twig->addExtension(new MyTwigExtensions());
$twig->addExtension(new Twig_Extension_Debug());

$twigData->loadScript();

$htmlContent = $twig->render(
    $twigData->getTemplate(),
    $twigData->getOutput()
);

if ( php_sapi_name() != 'cli' ) {
    $parser = new Less_Parser();
    $parser->parseFile(LESS . '/style.less');
    file_put_contents(PUBLIC_DOC . '/css/style.css', $parser->getCss());
}

echo $htmlContent;

if ( isset($_GET['debug']) ) {
    printf('<fieldset style="margin: 10px; padding: 0 5px;"><legend>Loaded Template</legend><b>%s</b></fieldset>', $twigData->getTemplate());
    echo '<pre>';
    print_r($_GET);
    echo '</pre>';
    echo '<fieldset style="margin: 10px; padding: 0 5px;"><legend>Included files</legend>';
    $files = get_included_files();
    foreach ($files as $f) {
        if ( preg_match('/vendor/', $f) ) {
            continue;
        }
        echo $f . "<br>\n";
    }
    echo '</fieldset>';
}
