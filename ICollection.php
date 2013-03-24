<?php
/*
* @package concurrent
* 
* @name ICollection
* @author pthreads
* 
* This interface defines the basic functionality of all collections
* All implementations will hopefully implement this interface for a uniform experience
*/
interface ICollection {
    /*
    * Should return a member by name
    * @param $key the name of the member
    */
    public function get($key);
    
    /*
    * Should set a member by name
    * @param $key the name of the member
    * @param $value the value of member
    */
    public function set($key, $value);
    
    /*
    * Should remove a member by name
    * @param $key the name of the member
    */
    public function del($key);
    
    
    /*
    * Should provide a set of keys
    */
    public function keySet();
    
    /*
    * Should provide a mapped set of key=>value
    * A basic implementation of this can be achieved by casting to array
    */
    public function entrySet();
    
    /*
    * Should execute the closure on every member
    * The closure should have the prototype
    *   public function mapfunc($key, $value)
    * The return value of the closure should be ignored
    */
    public function map(Closure $closure);
}
?>
