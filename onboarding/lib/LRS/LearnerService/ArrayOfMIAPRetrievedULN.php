<?php

class ArrayOfMIAPRetrievedULN implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var MIAPRetrievedULN[] $VerifiedULN
     */
    protected $VerifiedULN = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return MIAPRetrievedULN[]
     */
    public function getVerifiedULN()
    {
      return $this->VerifiedULN;
    }

    /**
     * @param MIAPRetrievedULN[] $VerifiedULN
     * @return ArrayOfMIAPRetrievedULN
     */
    public function setVerifiedULN(array $VerifiedULN = null)
    {
      $this->VerifiedULN = $VerifiedULN;
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
      return isset($this->VerifiedULN[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to retrieve
     * @return MIAPRetrievedULN
     */
    public function offsetGet($offset)
    {
      return $this->VerifiedULN[$offset];
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset The offset to assign the value to
     * @param MIAPRetrievedULN $value The value to set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
      if (!isset($offset)) {
        $this->VerifiedULN[] = $value;
      } else {
        $this->VerifiedULN[$offset] = $value;
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
      unset($this->VerifiedULN[$offset]);
    }

    /**
     * Iterator implementation
     *
     * @return MIAPRetrievedULN Return the current element
     */
    public function current()
    {
      return current($this->VerifiedULN);
    }

    /**
     * Iterator implementation
     * Move forward to next element
     *
     * @return void
     */
    public function next()
    {
      next($this->VerifiedULN);
    }

    /**
     * Iterator implementation
     *
     * @return string|null Return the key of the current element or null
     */
    public function key()
    {
      return key($this->VerifiedULN);
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
      reset($this->VerifiedULN);
    }

    /**
     * Countable implementation
     *
     * @return MIAPRetrievedULN Return count of elements
     */
    public function count()
    {
      return count($this->VerifiedULN);
    }

}
