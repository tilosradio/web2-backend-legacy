<?php

namespace Radio\Controller;

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
class BaseController extends AbstractRestfulController {

    use EntityManager;

    public function findEntityObject($type, $id) {
        return $this->getEntityManager()->find($type, $id);
    }

    public function findEntityList($type) {
        return $this->getEntityManager()->getRepository($type)->findAll();
    }

    public function mapEntityListElement($result) {
        return $result;
    }

    public function mapEntity($result) {
        return $result;
    }

    public function getEntity($type, $id) {
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

    public function getEntityList($type) {
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

    public function getServerUrl() {
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
    public function onDispatch(MvcEvent $e) {
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

        if ($action && $method != "options") {
            // Handle arbitrary methods, ending in Action
            $method = static::getMethodFromAction($action);
            if (!method_exists($this, $method)) {
                $method = 'notFoundAction';
            }
            $return = $this->$method();
            $e->setResult($return);
            return $return;
        }

        // RESTful methods

        switch ($method) {
            // Custom HTTP methods (or custom overrides for standard methods)
            case (isset($this->customHttpMethodsMap[$method])):
                $callable = $this->customHttpMethodsMap[$method];
                $routeMatch->setParam('action', $method);;
                $this->checkAccess($e);
                $return = call_user_func($callable, $e);
                break;
            // DELETE
            case 'delete':
                $id = $this->getIdentifier($routeMatch, $request);
                if ($id !== false) {
                    $routeMatch->setParam('action', 'delete');;
                    $this->checkAccess($e);
                    $return = $this->delete($id);
                    break;
                }

                throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
                break;
            // GET
            case 'get':
                $id = $this->getIdentifier($routeMatch, $request);
                if ($id !== false) {
                    $routeMatch->setParam('action', 'get');;
                    $this->checkAccess($e);
                    $return = $this->get($id);
                    break;
                }
                $routeMatch->setParam('action', 'getList');
                $this->checkAccess($e);
                $return = $this->getList();
                break;
            case 'options':
                $routeMatch->setParam('action', 'option');;
                $this->response->setContent("ok");
                $return = $e->getResponse();
                $return->setStatusCode(200);

                break;
            // POST
            case 'post':
                $routeMatch->setParam('action', 'create');;
                $this->checkAccess($e);
                $return = $this->processPostData($request);
                break;
            // PUT
            case 'put':
                $id = $this->getIdentifier($routeMatch, $request);
                $data = $this->processBodyContent($request);

                if ($id !== false) {
                    $routeMatch->setParam('action', 'update');;
                    $this->checkAccess($e);
                    $return = $this->update($id, $data);
                    break;
                }
                throw new Exception\DomainException('Missing route matches; unsure how to retrieve action');
                break;
            // All others...
            default:
                $response = $e->getResponse();
                $response->setStatusCode(405);
                return $response;
        }
        $e->setResult($return);
        return $return;
    }

    function checkAccess(MvcEvent $event) {
        $serviceManager = $this->getServiceLocator();
        $authService = $serviceManager->get('doctrine.authenticationservice.orm_default');
        // identify the user
        $user = $authService->hasIdentity() ? $authService->getIdentity() : null;
        $role = empty($user) ? Role::getDefault() : $user->getRole();
        // get requested resource
        $routeMatch = $event->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
        $recordId = $routeMatch->getParam('id');
        // initialize permission check
        $assertion = new RoleAssertion($user, $recordId);
        $assertion->setServiceLocator($serviceManager);
        try {
            $acl = new Acl($this->getPermissionsConfig(), $assertion);
            // check user permissions
            if (!$acl->hasResource($controller) || !$acl->isAllowed($role->getName(), $controller, $action)) {
                // respond with 401 Unauthorized
                $event->getResponse()->setStatusCode(401)->sendHeaders();
                if (!$acl->hasResource($controller))
                    die("ERROR: No permission rule for controller $controller");
                else if (!$acl->isAllowed($role->getName(), $controller, $action))
                    die('ERROR: Unauthorized');
            }
        } catch (PermissionException $pe) {
            // configuration error
            $event->getResponse()->setStatusCode(500)->sendHeaders();
            die($pe->getMessage());
        }

    }

    private function getPermissionsConfig() {
        return include __DIR__ . '/../../../config/permissions.config.php';
    }
}

?>
