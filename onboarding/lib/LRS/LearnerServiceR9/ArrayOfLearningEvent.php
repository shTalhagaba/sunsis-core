<?php

class ArrayOfLearningEvent implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var LearningEvent[] $LearningEvent
     */
    protected $LearningEvent = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return LearningEvent[]
     */
    public function getLearningEvent()
    {
      return $this->LearningEvent;
    }

    /**
     * @param LearningEvent[] $LearningEvent
     * @return ArrayOfLearningEvent
     */
    public function setLearningEvent(array $LearningEvent = null)
    {
      $this->LearningEvent = $LearningEvent;
      return $this;
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset An offset to check for
     * @return boolean true on success or false on failure
     */
    public function offsetExists($offset)
    {
      return isset($this->LearningEvent[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return LearningEvent
     */
    public function offsetGet($offset)
    {
      return $this->LearningEvent[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param LearningEvent $value The value to set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
      if (!isset($offset)) {
        $this->LearningEvent[] = $value;
      } else {
        $this->LearningEvent[$offset] = $value;
      }
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to unset
     * @return void
     */
    public function offsetUnset($offset)
    {
      unset($this->LearningEvent[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return LearningEvent Return the current element
     */
    public function current()
    {
      return current($this->LearningEvent);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
      next($this->LearningEvent);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key()
    {
      return key($this->LearningEvent);
    }

    /**
     * Iterator implementation
     *
     * @return boolean Return the validity of the current position
     */
    public function valid()
    {
      return $this->key() !== null;
    }

    /**
     * Iterator implementation
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind()
    {
      reset($this->LearningEvent);
    }

    /**
     * Countable implementation
     *
     * @return LearningEvent Return count of elements
     */
    public function count()
    {
      return count($this->LearningEvent);
    }

}
