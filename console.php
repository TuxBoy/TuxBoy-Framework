#!/usr/bin/env php
<?php
use Symfony\Component\Console\Output\OutputInterface;

require __DIR__ . '/vendor/autoload.php';

$app = new \Silly\Application;

/**
 * la commande init, permet d'initialiser le prohet en clonant le Core dans le bon dossier et il installe aussi
 * les dépendance du projet
 *
 * @example php console.php init
 */
$app->command('init', function (OutputInterface $output) {

    chdir(__DIR__ . "/src/");
    shell_exec("git clone https://github.com/TuxBoy/Core.git");
    shell_exec("composer install -o");
    $output->writeln("Le projet a été initialié.");

});

/**
 * Met à jour le framework dans le src/Core + fait un composer update du projet.
 */
$app->command('update', function (OutputInterface $output) {
    shell_exec("composer update");
    chdir(__DIR__ . "/src/Core");
    shell_exec("git pull");
    $output->writeln("Le projet et les dépendances ont bien été mis à jour.");
});

/** * Cette commande permet de lancer  un serveur de développement
 * @param $port int Le port sur le quel lancer le serveur (par défaut il se lance sur le port 8000)
 *
 * @example php console.php server 8080
 */
$app->command('server [port]', function ($port, OutputInterface $output) {
    system("php -S localhost:{$port} -t public -d display_errors=1 -d xdebug.remote_enable=1 -d xdebug.remote_autostart=1");
    $output->writeln("Le server écoute sur le port {$port}");
})->defaults(['port' => 8000]);

/**
 * Permet de ce créer un nouveau module + en créant l'Application.php et le dossier des vues (views).
 * @param $name string Le nom de votre module.
 *
 * @example php console.php application:new Demo
 */
$app->command('application:new name', function (string $name, OutputInterface $output) {
    $name = ucfirst($name);
    $applicationDir = __DIR__ . '/src/Application/' . $name;
    $twigNamespace = strtolower($name);
    if (!is_dir($applicationDir)) {
        mkdir($applicationDir,  0755, true);
    }
    if (!is_dir($applicationDir . '/views')) {
        mkdir($applicationDir . '/views',  0755, true);
    }
    $routerParam = '$router';
    file_put_contents($applicationDir . '/Application.php', <<<EOT
<?php
namespace App\\$name;

use TuxBoy\ApplicationInterface;
use TuxBoy\Router\Router;
use function DI\add;

/**
 * The $name application
 */
class Application implements ApplicationInterface
{
    /**
     * Définie les routes de l'application.
     *
     * @param Router $routerParam
     */
    public function getRoutes(Router $routerParam): void
    {
        // ...
    }

    /**
     * Pour ajouter la configuration au container de son application.
     *
     * @return array
     */
    public function addConfig(): array
    {
        return [
            'twig.path' => add([
               $twigNamespace  => __DIR__ . '/views/'
            ]),
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return '$name';
    }
}

EOT
    );
    $output->writeln("Votre application $name a été créé");
});

$app->command('migrate [force]', function ($force, OutputInterface $output) {
    // @TODO Si l'Aspect est désactivé, il faut pourvoir lancer la migration en ligne de commande
})->defaults(['force' => false]);

$app->run();