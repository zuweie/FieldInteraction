<?php 
namespace Field\Interaction\Base;
use Field\Interaction\Base\BaseScriptBuilder;

abstract class BaseScript {
    
    public function __construct(BaseScriptBuilder $builder) {
        
        if (empty($builder)) {
            throw new \Exception('ScriptBuilder不能为空，空的还build个啥script啊，你大爷的！');
        }
        $this->script_builder = $builder;
    }
    
    public abstract function genScript();
        
    public function __toString(){
        $ss = '';
        foreach ($this->inject_script as $script) {
            $ss .= $script;
        }
        return $ss;
    }
    
    public function getBuilder () {
        return $this->$scriptBuilder;
    }
    
    public function putScript ($key, $script) {
        $this->scripts[$key] = $script;
    }
    
    protected $script_builder;
    protected $scripts = array();
    protected $inject_script = array();
}
?>