<?php

namespace vendor\psr7;

use vendor\psr7\psr7interface\StreamInterface;

class HttpStream implements StreamInterface
{
    public function __toString()
    {
        // TODO: Implement __toString() method.
    }

    public function read($length)
    {
        // TODO: Implement read() method.
    }
    
    public function getSize()
    {
        // TODO: Implement getSize() method.
    }
    
    public function isWritable()
    {
        // TODO: Implement isWritable() method.
    }
    
    public function tell()
    {
        // TODO: Implement tell() method.
    }
    
    public function getMetadata($key = null)
    {
        // TODO: Implement getMetadata() method.
    }
    
    public function seek($offset, $whence = SEEK_SET)
    {
        // TODO: Implement seek() method.
    }
    
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }
    
    public function isReadable()
    {
        // TODO: Implement isReadable() method.
    }
    
    public function isSeekable()
    {
        // TODO: Implement isSeekable() method.
    }
    
    public function detach()
    {
        // TODO: Implement detach() method.
    }
    
    public function close()
    {
        // TODO: Implement close() method.
    }
    
    public function eof()
    {
        // TODO: Implement eof() method.
    }
    
    public function write($string)
    {
        // TODO: Implement write() method.
    }
    
    public function getContents()
    {
        // TODO: Implement getContents() method.
    }
}