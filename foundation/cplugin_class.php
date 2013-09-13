<?php
Abstract class Plugin_Frame_Abstract
{
    public $pluginPath = false;
    private $_header = false;
    private $_footer = false;
    public $gvar = false;
    public $do = false;
    
    /**
    * @desc constructor
    */
    public function __construct()
    {
        
        $this->init();
    }
    
    /**
    * @desc init the plugin framework
    */
    public function init()
    {
    }
    
    /**
    * @desc set the include files
    */
    public function setInclude()
    {
    }
    
    /**
    * @desc the dispatcher of the main page
    */
    public function dispatch()
    {
        $this->setInclude();
        if($this->_header)require($this->_header);
        $this->_dispatchUrl();
        if($this->_footer)require($this->_footer);
    }
    
    /**
    * @desc the the do container
    */
    public function setDo($array)
    {
        $this->do = $array;
    }
    
    /**
    * @desc set the global vars.
    */
    public function setGvar($array)
    {
        $this->gvars = $array;
    }
    
    /**
    * @desc set the header of the page
    */
    public function setHeader($url)
    {
        $this->_header = $url;
    }
    
    /**
    * @desc set the footer of the page
    */
    public function setFooter($url)
    {
        $this->_footer = $url;
    }
    
    /**
    * @desc the url dispatcher
    * @access private
    */
    private function _dispatchUrl()
    {
        if($this->do)
        {
            $URL_KEY='';
            $URL_STR=$_SERVER['QUERY_STRING'];
            $URL_ARRAY=explode("&",$URL_STR);

            if(isset($URL_ARRAY[1])){
                if(array_key_exists($URL_ARRAY[1],$this->do)){
                    $URL_KEY=$URL_ARRAY[1];
                }    
            }

            if(isset($URL_ARRAY[2])){
                if(array_key_exists($URL_ARRAY[1].'/'.$URL_ARRAY[2],$this->do)){
                    $URL_KEY=$URL_ARRAY[1].'/'.$URL_ARRAY[2];
                }    
            }
            /**
            * @desc add the func access mode
            * @author by leon
            */
            if($_GET['app'] == 'act' || $_GET['app'] == 'view')
            {
                if(array_key_exists($URL_KEY,$this->do)){
                    if((strpos($this->do[$URL_KEY],"admin/") || strpos($this->do[$URL_KEY],"action/"))){
                        echo 'error';exit;
                    }
                    require($this->pluginPath . $this->do[$URL_KEY]);
                }else{
                    echo "Undefined Url Target"; exit;
                }
            }else if($_GET['app'] == 'func'){
                $className = $URL_ARRAY[1];
                $function = $URL_ARRAY[2];
                if(array_key_exists($className,$this->do) && (array_search($function,$this->do[$className]) !== false))
                {
                    require($this->pluginPath.'action/'. $URL_ARRAY[1] . ".php");
                    if(class_exists($URL_ARRAY[1]))
                    {
                        $action = new $className();
                        if(method_exists($action,$function))
                        {
                            $action->$function();
                        }else{
                            echo "$className::$function undefined";
                        }   
                    }else{
                        echo 'app=func class name undefined';
                    }
                }else{
                    echo 'app=func invalide access mode';
                }
            }
        }
        else{
            echo "Invalide access mode!";
        }
    }
    /**
    * @desc action return function
    * @copy the action_return function in the foundation directory
    */
    public function action_return($state=1,$retrun_mess="",$activeUrl="")
    {
        if($state==2){echo $retrun_mess;exit;}
        echo "<script language='javascript'>";
        if(trim($retrun_mess)!=''){
           echo "alert('".$retrun_mess."');";
        }
        $setUrl='';
        if($activeUrl!=''){
        $setUrl=$activeUrl;
        }else{
          $setUrl=$this->do[1];
        }
        if($setUrl=='-1'){
            echo "history.go(-1);";
        }else if($setUrl=='0'){
            echo "window.close();";
        }else{
            echo "location.href='".$setUrl."';";
        }
            echo "</script>";exit();
    }
}

/**
* @desc the action class 
*/
Abstract class Plugin_Action_Abstract
{
    public $data = false;
    public function __construct()
    {
    }
    
    /**
    * @desc load the template
    */
    function render($template)
    {
        if(file_exists($template))
        {
            require($template);
        }else{
            echo "can not find the templates : $template";
            exit();
        }
    }
}
?>