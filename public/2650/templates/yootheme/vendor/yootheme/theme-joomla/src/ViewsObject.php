<?php

namespace YOOtheme\Theme\Joomla;

class ViewsObject extends \ArrayObject
{
    /**
     * Returns the value at the specified index.
     *
     * @param string $index
     *
     * @return mixed
     */
    public function offsetGet($index)
    {
        if (!$this->offsetExists($index)) {
            $this->offsetSet($index, new \ArrayObject());
        }

        $views = parent::offsetGet($index);

        if (isset($views['html'])) {
            $views['html'] = array_map([$this, 'wrap'], $views['html']);
        }

        return $views;
    }

    /**
     * Wraps view object into and new object and copies all properties.
     *
     * @param object $view
     *
     * @return object
     */
    protected function wrap($view)
    {
        $class = get_class($view);
        $traits = class_uses($view);
        $wrapper = 'View' . md5($class);

        // is view already wrapped?
        if (in_array(ViewTrait::class, $traits)) {
            return $view;
        }

        // dynamically create view wrapper
        if (!class_exists($wrapper, false)) {
            eval("class {$wrapper} extends {$class} {
                use YOOtheme\\Theme\\Joomla\\ViewTrait;
            }");
        }

        return $this->tap(new $wrapper(['name' => $view->getName()]), function ($wrapper) use ($view) {

            // copy view properties and set context
            $wrapper->setProperties($view->getProperties(false));
            $wrapper->set('context', basename($wrapper->_basePath) . ".{$wrapper->getName()}");

        });
    }

    /**
     * Calls callback in object scope and return object.
     *
     * @param object   $object
     * @param \Closure $callback
     *
     * @return object
     */
    protected function tap($object, \Closure $callback)
    {
        call_user_func($callback->bindTo(null, $object), $object);

        return $object;
    }
}
