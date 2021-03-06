<?php

/*
* The class Context is an abstraction layer for Threads.
* It introduces a global access to the thread base class at any time when passing $this to Data::init() when the thread is started.
* The major change vs. a normal Thread is that you have to use a main() method instead of a run() method.
*
* The class Data is here a global shared variables storage. An same instance of it should be passed to every Context.
* So you can access from everywhere the global shared variables, simply by using getData()->property_name
*
* An usage example would be not to have to pass the Data / Thread instance everywhere.
* For example you have an often called static method. Then you don't want to pass every time your thread instance and pollute your code with loads of ", $this->data" in function / static method calls.
*
* Another advantage is that you can be sure of your structure throughout your code: $this->data / getData() is the global shared variable container; getThread() the thread-local variable container.
*/

abstract class Context extends Thread {
	// container of the Stackable
	public $data;

	/*
	* main initialisation
	* @param Data $stack an object Data or extending Data
	* @param mixed ... optional arguments for an optional init function of the class extending this class
	*/
	public final function __construct (Data $data) {
		$args = func_get_args();
		$this->data = array_shift($args);
		call_user_func_array(array("static", "init"), $args);
	}

	/*
	* Called with late static binding
	* If some initialisation is needed, create an init([...]) function
	*/
	public function init () {}

	/*
	* start method of the thread
	* calls the init() method of the stackable
	* calls the main() method where the principal code of the thread should run
	*/
	public final function run () {
		$this->data->init($this);
		$this->main();
	}

	/*
	* The thread method ;)
	*/
	abstract public function main ();
}

class Data extends Stackable {
	/*
	* shitty dummy function because Stackable::run() is declared as abstract...
	*/
	public function run () {}

	// insert here your other properties

	/*
	* static property containing the actual thread instance
	* accessible by Data::$instance or getThread()
	* to access this class use Data::$instance->data or getStack()
	* @var Context (some extended class from class Context)
	*/
	public static $instance;

	/*
	* init function of the Stackable, initializes self::$instance
	* @param Context $class an object extending Context
	*/
	public function init (Context $class) {
		self::$instance = $class;
	}
}

/*
* returns the actual thread instance
* @return Context|null thread instance or, if in main thread, null
*/
function getThread () {
	if (Data::$instance instanceof Context)
		return Data::$instance;
}

/*
* returns the Data instance (the Data isntance should be stored in $data)
* @return Data|null Data instance, or, if in main thread and not yet initialized, null
*/
function getData () {
	if (!is_null($thread = getThread()))
		return $thread->data;
	global $data;
	return $data;
}
