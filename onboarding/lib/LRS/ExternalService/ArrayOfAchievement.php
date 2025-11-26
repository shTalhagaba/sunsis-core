<?php

class ArrayOfAchievement implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var Achievement[] $Achievement
     */
    protected $Achievement = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return Achievement[]
     */
    public function getAchievement()
    {
      return $this->Achievement;
    }

    /**
     * @param Achievement[] $Achievement
     * @return ArrayOfAchievement
     */
    public function setAchievement(array $Achievement = null)
    {
      $this->Achievement = $Achievement;
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
      return isset($this->Achievement[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return Achievement
     */
    public function offsetGet($offset)
    {
      return $this->Achievement[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param Achievement $value The value to set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
      if (!isset($offset)) {
        $this->Achievement[] = $value;
      } else {
        $this->Achievement[$offset] = $value;
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
      unset($this->Achievement[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return Achievement Return the current element
     */
    public function current()
    {
      return current($this->Achievement);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
      next($this->Achievement);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key()
    {
      return key($this->Achievement);
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
      reset($this->Achievement);
    }

    /**
     * Countable implementation
     *
     * @return Achievement Return count of elements
     */
    public function count()
    {
      return count($this->Achievement);
    }

}
