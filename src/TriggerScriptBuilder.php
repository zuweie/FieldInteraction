<?php 
namespace Field\Interaction;
use Field\Interaction\Base\BaseScriptBuilder;
use Field\Interaction\Base\BaseScript;
use Field\Interaction\FieldformatTrait;


class TriggerScriptBuilder implements BaseScriptBuilder{
    use FieldformatTrait;
    
    public function __construct() {
        
    }
    
    public function addScriptTo(BaseScript $container) {
        foreach ($this->scripts as $key => $func) {
            $container->putScript($key, $func);
        }
    }

    // 不够用自己再加啊
    public function addTrigger ($field_class, $script_func) {
        $this->scripts[$this->formatFieldClazz($field_class)] = $script_func;
    }

    
    protected $scripts = array();
}
?>