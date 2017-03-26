<?php
use Phalcon\Loader;
use Phalcon\Tag;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

require_once "/var/cache/cred.inc";

try {
    // Register an autoloader
    $loader = new Loader();
    $loader->registerDirs(
        array(
            '../app/controllers/',
            '../app/models/'
        )
    )->register();
    // Create a DI
    $di = new FactoryDefault();
    // Set the database service
    $di['db'] = function() {
        return new DbAdapter(array(
            "host"     => mysql_host,
            "username" => mysql_db_user,
            "password" => mysql_db_pass,
            "dbname"   => mysql_default_db,
        ));
    };
    // Setting up the view component
    $di['view'] = function() {
        $view = new View();
        $view->setViewsDir('../app/views/');
        return $view;
    };
    // Setup a base URI so that all generated URIs include the "tutorial" folder
    $di['url'] = function() {
        $url = new Url();
        $url->setBaseUri('/starships/test/');
        return $url;
    };
    // Setup the tag helpers
    $di['tag'] = function() {
        return new Tag();
    };
    // Handle the request
    $application = new Application($di);
    echo $application->handle()->getContent();
} catch (Exception $e) {
    echo "Exception: ", $e->getMessage();
}