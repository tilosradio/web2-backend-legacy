<?php


namespace Radio\Util;


use Zend\Mvc\Router\Http\Segment;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Exception;


class CustomSegmentRouter extends \Zend\Mvc\Router\Http\Segment
{
    private $method;
    private $permission;


    /**
     * Create a new regex route.
     *
     * @param  string $route
     * @param  array $constraints
     * @param  array $defaults
     */
    public function __construct($route, array $constraints = array(), array $defaults = array(), $method = "get", $permission)
    {
        $this->defaults = $defaults;
        $this->parts = $this->parseRouteDefinition($route);
        $this->regex = $this->buildRegex($this->parts, $constraints);
        $this->method = strtolower($method);
        $this->permission = $permission;
    }

    public function match(Request $request, $pathOffset = null, array $options = array())
    {
        $realMethod = strtolower($request->getMethod());
        if ($realMethod != $this->method && $realMethod != "options") {
            return null;
        }
        $match = parent::match($request, $pathOffset, $options);
        if ($match != null) {
            $match->setParam("tilosRouter", true);
            $match->setParam("action", $this->defaults['action']);
            $match->setParam("permission", $this->permission);
        }
        return $match;
    }

    /**
     * factory(): defined by RouteInterface interface.
     *
     * @see    \Zend\Mvc\Router\RouteInterface::factory()
     * @param  array|Traversable $options
     * @return Segment
     * @throws Exception\InvalidArgumentException
     */
    public static function factory($options = array())
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        if (!isset($options['route'])) {
            throw new Exception\InvalidArgumentException('Missing "route" in options array');
        }

        if (!isset($options['method'])) {
            throw new Exception\InvalidArgumentException('Missing "method" in options array ' . $options['route']);
        }

        if (!isset($options['permission'])) {
            throw new Exception\InvalidArgumentException('Missing "permission" in options array ' . $options['route']);
        }

        if (!isset($options['defaults']['action'])) {
            throw new Exception\InvalidArgumentException('Missing "action" in options array' . $options['route']);
        }

        if (!isset($options['constraints'])) {
            $options['constraints'] = array();
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        return new static($options['route'], $options['constraints'], $options['defaults'], $options['method'], $options['permission']);
    }

} 