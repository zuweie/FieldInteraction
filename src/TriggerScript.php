<?php 
namespace Field\Interaction;

use Encore\Admin\Form;

use Field\Interaction\Base\BaseScript;
use Field\Interaction\Base\BaseScriptBuilder;
use Field\Interaction\FieldformatTrait;
use Field\Interaction\Base\BaseFieldSet;

/**
 * 这个Script使用Admin\Form 
 * @author zuweie
 *
 */
class TriggerScript extends BaseScript {
    
    use FieldformatTrait;
    
    function __construct(BaseFieldSet $fieldset, BaseScriptBuilder $scriptBuilder) {
        parent::__construct($scriptBuilder);
        $this->fieldset = $fieldset;
    }
    
    public function genScript () {
        
        $this->script_builder->addScriptTo($this);
        
        $fields = $this->fieldset->getFields();
        
        foreach ($fields as $f) {
            $f_class = $this->formatFieldClazz(get_class($f));
            $script_func = isset($this->scripts[$f_class])? $this->scripts[$f_class] : null;
            if (!empty($script_func)) {
                $trigger_script = call_user_func($script_func, $f);
                array_push($this->inject_script, $trigger_script);    
            }
        }
        return $this;
    }
    
    protected $inject_script = [];
    protected $fieldset;
}
