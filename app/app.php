<?php

namespace GSB\App;

use FFMVC\App\Main;
use FFMVC\Helpers as Helpers;
use DebugBar\StandardDebugBar;

/**
 * fat-free framework application
 * Execution en appelant Run();. (public/index.php)
 *
 * @author Vijay Mahrra <vijay@yoyo.org>
 * @copyright (c) Copyright 2013 Vijay Mahrra & Modified by Anicet Réglat Vizzavona for GSB
 * @license GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html)
 */
function Run()
{
    // @see http://fatfreeframework.com/quick-reference#autoload
    $f3 = require_once '../vendor/bcosca/fatfree-core/base.php';
    $f3->set('AUTOLOAD', __dir__.';bcosca/fatfree-core/;../vendor/');

    // Initialise l'application
    Main::start($f3);

    // Messages flash, initialisation de façon à ce que la methode puisse être appelée de façon statique
    $messages = Helpers\Messages::instance();
    $messages->init(true);

    // Paramétrage de la base de donnée
    // @see http://fatfreeframework.com/databases
    $db = null;

    $f3->set('db.dsn', sprintf('%s:host=%s;port=%d;dbname=%s',
        $f3->get('db.driver'),
        $f3->get('db.hostname'),
        $f3->get('db.port'),
        $f3->get('db.name')
    ));

    $db = new \DB\SQL(
        $f3->get('db.dsn'),
        $f3->get('db.username'),
        $f3->get('db.password')
    );

    if($f3->get('application.environment') == 'developpement') {
        // Ajout de la trace SQL pour debugbar
        $debugbar = new StandardDebugBar();
        $pdo = new \DebugBar\DataCollector\PDO\TraceablePDO($db->pdo());
        $debugbar->addCollector(new \DebugBar\DataCollector\PDO\PDOCollector($pdo));
        $f3->set('debugbar', $debugbar);

        \Registry::set('db', $db);
    }


    // Ne pas utiliser de session pour les appels sur l'API
    if (stristr($f3->get('PATH'), '/api') !== false && session_status() !== PHP_SESSION_NONE) {
        session_write_close();
    } else if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Gestionnaire d'erreur customisé
    $f3->set('ONERROR',
        function () use ($f3) {
            // Supprimme tous les buffers de façon recursive
            while (ob_get_level()) {
                ob_end_clean();
            }
            if ($f3->get('ERROR.code') == '404' && stristr($f3->get('PATH'), '/api') == false) {
                include_once 'templates/error/404.phtml';
            } else {
                $debug = $f3->get('DEBUG');
                if (stristr($f3->get('PATH'), '/api') !== false) {
                    $response = Helpers\Response::instance();
                    $data = array(
                        'service' => 'API',
                        'version' => 1,
                        'time' => time(),
                        'method' => $f3->get('VERB')
                    );
                    $e = $f3->get('ERROR');
                    $data['error'] = array(
                        'code' => substr($f3->snakecase(str_replace(' ', '', $e['status'])), 1),
                        'description' => $e['code'] . ' ' . $e['text']
                    );
                    $params = array('http_status' => $e['code']);
                    $return = $f3->get('REQUEST.return');
                    switch ($return) {
                        case 'xml':
                            $response->xml($data, $params);
                            break;

                        default:
                        case 'json':
                        $response->json($data, $params);
                    }
                } else {
                    include_once $debug < 3 ? 'templates/error/error.phtml' :  'templates/error/debug.phtml';
                }
            }
            // @see http://php.net/manual/en/function.ob-end-flush.php
            ob_end_flush();
        });

        // Permet de rendre "propres" tous les inputs utilisateurs par défaut
        $request = array();
        foreach (array('GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'COOKIE') as $var) {
            $input = $f3->get($var);
            if (is_array($input) && count($input)) {
                $cleaned = array();
                foreach ($input as $k => $v) {
                    $k = strtolower(trim($f3->clean($k)));
                    $v = $f3->clean($v);
                    if (empty($v)) {
                        continue;
                    }
                    $cleaned[$k] = $v;
                    $request[$k] = $v;
                }
                ksort($cleaned);
                $f3->set($var, $cleaned);
            }
        }
        ksort($request);
        $f3->set('REQUEST', $request);
        $f3->set('PageHash', md5(json_encode(array_merge($request, $_SERVER, $_ENV))));
        unset($cleaned);
        unset($request);

        // Récupération des routes
        $f3->config('config/routes.ini');
        // Initialisation de la variable menu
        $f3->set('application.menu', include('config/menu.php'));


    $f3->run();

    // Ferme l'application
    Main::finish($f3);
}
