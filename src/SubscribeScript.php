<?php 
namespace Field\Interaction;

use Field\Interaction\Base\BaseScript;
use Field\Interaction\Base\BaseScriptBuilder;

use Encore\Admin\Form;

class SubscribeScript extends BaseScript{
    
    public function __construct(Form $form, BaseScriptBuilder $scriptBuilder){
        
        parent::__construct($scriptBuilder);
        $this->form = $form;
    }
    
    public function genScript () {
        $this->script_builder->addScriptTo($this);
        foreach ($this->scripts as $key => $func) {
            $script = call_user_func($func,  $key);
            $script = $this->subscript_open($key).$script.$this->subscript_close();
            array_push($this->inject_script, $script);
        }
        return $this;
    } 
    
    public function getForm() {
        return $this->form;
    }
    
    protected function subscript_open ($event) {
        return <<<EOT
        FieldHub.subscribe('{$event}', 
EOT;
    }
    
    protected function subscript_close () {
        return <<<EOT
        );
EOT;
    }
    
    protected  $form;
    
}
?>