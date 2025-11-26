<?php

class ArrayOfPlrAccessEntryResponse implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var PlrAccessEntryResponse[] $PlrAccessEntryResponse
     */
    protected $PlrAccessEntryResponse = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return PlrAccessEntryResponse[]
     */
    public function getPlrAccessEntryResponse()
    {
      return $this->PlrAccessEntryResponse;
    }

    /**
     * @param PlrAccessEntryResponse[] $PlrAccessEntryResponse
     * @return ArrayOfPlrAccessEntryResponse
     */
    public function setPlrAccessEntryResponse(array $PlrAccessEntryResponse = null)
    {
      $this->PlrAccessEntryResponse = $PlrAccessEntryResponse;
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
      return isset($this->PlrAccessEntryResponse[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return PlrAccessEntryResponse
     */
    public function offsetGet($offset)
    {
      return $this->PlrAccessEntryResponse[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param PlrAccessEntryResponse $value The value to set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
      if (!isset($offset)) {
        $this->PlrAccessEntryResponse[] = $value;
      } else {
        $this->PlrAccessEntryResponse[$offset] = $value;
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
      unset($this->PlrAccessEntryResponse[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return PlrAccessEntryResponse Return the current element
     */
    public function current()
    {
      return current($this->PlrAccessEntryResponse);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
      next($this->PlrAccessEntryResponse);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key()
    {
      return key($this->PlrAccessEntryResponse);
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
      reset($this->PlrAccessEntryResponse);
    }

    /**
     * Countable implementation
     *
     * @return PlrAccessEntryResponse Return count of elements
     */
    public function count()
    {
      return count($this->PlrAccessEntryResponse);
    }

}
