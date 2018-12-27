<?php 
namespace Field\Interaction;
use Field\Interaction\SubscribeScriptBuilder;
use Field\Interaction\SubscribeScript;

trait FieldSubscriberTrait {
	public function createSubscriberScript ( $form, $sub_func ) {
		
		$scriptBuilder = new SubscribeScriptBuilder();
		$sub_func($scriptBuilder);
		$script = new SubscribeScript($form, $scriptBuilder);
		return $script;
		
	}
}
?>