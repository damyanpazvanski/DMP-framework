<?php

namespace DMP;

use DMP\DatabaseManager\DatabaseManager;

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Templates.php';
require_once __DIR__ . '/Logger.php';
require_once __DIR__ . '/DatabaseManager/DatabaseManager.php';

class DMP
{
    /**
     * @var Config $config
     */
    private $config;

    /**
     * @var Router $router
     */
    private $router;

    /**
     * @var DatabaseManager $db
     */
    private $db;

    /**
     * @var Logger $logger
     */
    private $logger;

    public function __construct()
    {
        $this->config = new Config();
        $this->router = new Router();
        $this->db = new DatabaseManager($this->config->getConnections());
        $this->logger = new Logger();
    }

    public function run() {
        try {
            $path = $_SERVER['REQUEST_URI'];
            $method = $_SERVER['REQUEST_METHOD'];

            $action = $this->router->findAction($path, $method);
            $components = explode('/', $action['action']);

            $controller_path =  __DIR__ . '/../Controllers/' . $components[0] . '/' . $components[1] . 'Controller.php';
            if (!file_exists($controller_path)) {
                throw new \ErrorException("The controller with name $components[1] doesn\'t exist!");
            }

            require_once $controller_path;

            $controller_name = $components[1] . 'Controller';
            $controller = new $controller_name();

            $request = $_REQUEST;
            $requester = new Request($request);

            $action_name = $components[2] . 'Action';
            $returns = $controller->$action_name($requester);

            $params = [];
            if (isset($returns[1]) && is_array($returns[1])) {
                $params = $returns[1];
            }

            if (!isset($returns[0])) {
                return;
            }

            $template = new Templates($returns[0], $this->config->getTemplateRoot(), $params);
            $template->render();

        } catch (\mysqli_sql_exception $mysqli_sql_exception) {
            $this->logger->log($mysqli_sql_exception);
        } catch (\HttpInvalidParamException $httpInvalidParamException) {
            $this->logger->log($httpInvalidParamException);
        } catch (\Error $error) {
            $this->logger->log($error);
        } catch (\ErrorException $errorException) {
            $this->logger->log($errorException);
        } catch (\Exception $exception) {
            $this->logger->log($exception);
        }
    }
}