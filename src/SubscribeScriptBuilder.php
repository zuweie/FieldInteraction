<?php 
namespace Field\Interaction;
use Field\Interaction\Base\BaseScriptBuilder;
use Field\Interaction\Base\BaseScript;
use Field\Interaction\FieldformatTrait;

class SubscribeScriptBuilder implements BaseScriptBuilder {
    use FieldformatTrait;
    public function addScriptTo(BaseScript $container) {
        
        $fieldset = $container->getFieldset();
        
        foreach ($this->scripts as $s) {
            
            $field = $fieldset->getField($s[0]);
            
            if (!empty($field)) {
                $key = $this->formatFieldEvent($field, $s[1]);
                $container->putScript($key, $s[2]);
            }
        }
    }
    
    public function subscribe($column, $event, $scipt_func) {
        array_push($this->scripts, [$column, $event, $scipt_func]);
    }
    
    protected $scripts = array();
}
?>