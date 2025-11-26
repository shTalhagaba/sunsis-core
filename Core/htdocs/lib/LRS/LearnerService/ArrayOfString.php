<?php

class ArrayOfString implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var string[] $ULN
     */
    public $ULN = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string[]
     */
    public function getULN()
    {
      return $this->ULN;
    }

    /**
     * @param string[] $ULN
     * @return ArrayOfString
     */
    public function setULN(array $ULN = null)
    {
      $this->ULN = $ULN;
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
      return isset($this->ULN[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return string
     */
    public function offsetGet($offset)
    {
      return $this->ULN[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param string $value The value to set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
      if (!isset($offset)) {
        $this->ULN[] = $value;
      } else {
        $this->ULN[$offset] = $value;
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
      unset($this->ULN[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return string Return the current element
     */
    public function current()
    {
      return current($this->ULN);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
      next($this->ULN);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key()
    {
      return key($this->ULN);
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
      reset($this->ULN);
    }

    /**
     * Countable implementation
     *
     * @return string Return count of elements
     */
    public function count()
    {
      return count($this->ULN);
    }

}
