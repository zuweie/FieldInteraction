<?php 
namespace Field\Interaction;
use Field\Interaction\SubscribeScriptBuilder;
use Field\Interaction\SubscribeScript;
use Encore;

trait FieldSubscriberTrait {
    public function createSubscriberScript ( $form, $sub_func ) {
        
        $scriptBuilder = new SubscribeScriptBuilder();
        $sub_func($scriptBuilder);
        
        if ($form instanceof Encore\Admin\Form) {
            $script = new SubscribeScript(new FormFields($form), $scriptBuilder);
            
        } else if ( is_array($form) ) {
            $script = new SubscribeScript(new ArrayFields($form), $scriptBuilder);
        }
        return $script;
        
    }
}
?>