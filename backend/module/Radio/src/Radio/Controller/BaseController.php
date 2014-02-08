<?php

namespace Radio\Controller;

use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\Controller\AbstractRestfulController;
use Radio\Provider\EntityManager;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use Radio\Permissions\RoleAssertion;
use Radio\Permissions\Acl;
use Radio\Entity\Role;
use Zend\Mvc\Exception;


/**
 * Base class for all of the controllers.
 */
class BaseController extends AbstractController
{

    use EntityManager;

    /**
     * @var int From Zend\Json\Json
     */
    protected $jsonDecodeType = Json::TYPE_ARRAY;

    /**
     * Name of request or query parameter containing identifier
     *
     * @var string
     */
    protected $identifierName = 'id';


    public function findEntityObject($type, $id)
    {
        return $this->getEntityManager()->find($type, $id);
    }

    public function findEntityList($type)
    {
        return $this->getEntityManager()->getRepository($type)->findAll();
    }

    public function mapEntityListElement($result)
    {
        return $result;
    }

    public function mapEntity($result)
    {
        return $result;
    }

    public function getEntity($type, $id)
    {
        try {
            $result = $this->findEntityObject($type, $id);
            if ($result == null) {
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(array("error" => "Not found"));
            } else {
                $a = $this->mapEntity($result);
                return new JsonModel($a);
            }
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function getEntityList($type)
    {
        try {
            // TODO: paging (limit/offset)
            $resultSet = $this->findEntityList($type);
            if (empty($resultSet))
                return new JsonModel(array());
            $return = array();
            foreach ($resultSet as $result) {
                $return[] = $this->mapEntityListElement($result);
            }
            return new JsonModel($return);
        } catch (Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function getServerUrl()
    {
        return "http://" . $this->getRequest()->getServer('HTTP_HOST');
    }

    /**
     * Handle the request
     *
     * @todo   try-catch in "patch" for patchList should be removed in the future
     * @param  MvcEvent $e
     * @return mixed
     * @throws Exception\DomainException if no route matches in event or invalid HTTP method
     */
    public function onDispatch(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();
        if (!$routeMatch) {
            /**
             * @todo Determine requirements for when route match is missing.
             *       Potentially allow pulling directly from request metadata?
             */
            throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
        }

        $request = $e->getRequest();

        // Was an "action" requested?
        $action = $routeMatch->getParam('action', false);
        $method = strtolower($request->getMethod());
        $type = $routeMatch->getParam("tilosRouter", false);

        if ($method == "options") {
            $e->setResult(new JsonModel(array("options" => "true")));
            return $e->getResult();
        } else {
            // Handle arbitrary methods, ending in Action
            $this->checkRouteAccess($e);
            if (!method_exists($this, $action)) {
                $e->getResponse()->setStatusCode(500)->sendHeaders();
                die("Method is missing " . get_class($this) . ":" . $action);
            } else {
                $return = $this->$action($e);
                $e->setResult($return);
                return $return;
            }
        }

    }

    function checkAccess(MvcEvent $event)
    {

        $event->getResponse()->setStatusCode(500)->sendHeaders();
        die("Legacy permission system");


    }

    function getCurrentRole()
    {
        $user = $this->getCurrentUser();
        return empty($user) ? Role::getDefault() : $user->getRole();
    }

    function getCurrentUser()
    {
        $serviceManager = $this->getServiceLocator();
        $authService = $serviceManager->get('doctrine.authenticationservice.orm_default');
        // identify the user
        return $authService->hasIdentity() ? $authService->getIdentity() : null;
    }

    function isAdmin()
    {
        return $this->getCurrentRole()->getName() == "admin";
    }

    function checkRouteAccess(MvcEvent $event)
    {
        $serviceManager = $this->getServiceLocator();
        $authService = $serviceManager->get('doctrine.authenticationservice.orm_default');
        // identify the user
        $user = $authService->hasIdentity() ? $authService->getIdentity() : null;
        $role = empty($user) ? Role::getDefault() : $user->getRole();
        // get requested resource
        $routeMatch = $event->getRouteMatch();
        $permission = $routeMatch->getParam("permission");
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
        $recordId = $routeMatch->getParam('id');
        // initialize permission check

        if ($role->getName() == "admin") {
            return;
        } else if (strpos($permission, '::') !== false) {
            $result = call_user_func($permission, $event);
            if ($result) {
                return;
            }
        }
        $actualRole = $role;
        while ($actualRole != null) {
            if ($permission === $actualRole->getName()) {
                return;
            }
            $actualRole = $actualRole->getParent();
        }
        $this->accessDenied($event);

    }

    public function accessDenied($event)
    {
        $event->getResponse()->setStatusCode(401)->sendHeaders();
        die("Access denied");
    }

    private function getPermissionsConfig()
    {
        return include __DIR__ . '/../../../config/permissions.config.php';
    }

    public function getRawData($e)
    {
        return Json::decode($e->getRequest()->getContent(), $this->jsonDecodeType);
    }

    /**
     * Retrieve the identifier, if any
     *
     * Attempts to see if an identifier was passed in either the URI or the
     * query string, returning it if found. Otherwise, returns a boolean false.
     *
     * @param  \Zend\Mvc\Router\RouteMatch $routeMatch
     * @param  Request $request
     * @return false|mixed
     */
    protected function getIdentifier($routeMatch, $request)
    {
        $identifier = $this->getIdentifierName();
        $id = $routeMatch->getParam($identifier, false);
        if ($id !== false) {
            return $id;
        }

        $id = $request->getQuery()->get($identifier, false);
        if ($id !== false) {
            return $id;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getIdentifierName()
    {
        return $this->identifierName;
    }

}

?>
