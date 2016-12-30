<?php

/**
 * Class Autoloader
 * Find and load classes.
 *
 * Example of use:
 * Include class Autoloader in index.php your application.
 * Create object Autoloader class and run method run().
 * If you wont add custom namespace use method addCustomNamespace($namespace string, $folder string).
 */
class Autoloader
{
    /**
     * @var array It contains custom namespace.
     */
    private $customNamenespace = [];
    /**
     * @var string It contains application path.
     */
    private $basePath;

    /**
     * Autoloader constructor.
     * Set base directory application and custom namespace.
     * @param $conf contain array with key = custom namespace and value = folder with class.
     */
    public function __construct($conf = [])
    {
        $this->customNamenespace = $conf['classMap'];
        $this->basePath = dirname(__DIR__);
    }

    /**
     * This method output on screen all custom namespace.
     */
    public function printCustomNamespace()
    {
        echo  '<pre>';
        print_r($this->customNamenespace);
        echo '</pre>';
    }

    /**
     * This method insert new custom namespace.
     * @param $namespace custom namespace.
     * @param $folder folder with class.
     */
    public function addCustomNamespace($namespace, $folder)
    {
        $this->customNamenespace[$namespace] = $folder;
    }

    /**
     * This method started autoloading classes.
     */
    public function run()
    {
        spl_autoload_register(array($this, 'ClassLoad'));
    }

    /**
     * This method searches and connection classes.
     * @param $class name included classes.
     * @return string
     */
    public function ClassLoad($class)
    {
        $namespace = explode('\\', $class);
        $className = $namespace[count($namespace) - 1];
        $namespace = str_replace('\\'.$className, '', $class);
        
        $classFolder = $namespace;


        if(!empty($this->customNamenespace)) {
            foreach ($this->customNamenespace as $key => $value) {
                if ($key === $namespace) {
                    $classFolder = $value;
                    break;
                }
            }
        }

        $classPath = $this->basePath .'\\' . $classFolder . '\\' . $className . '.php';
        if(file_exists($classPath)) {
            return require_once $classPath;
        }

    }
}