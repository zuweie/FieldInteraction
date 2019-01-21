<?php 
namespace Field\Interaction\Base;
use Field\Interaction\Base\BaseScript;

interface BaseScriptBuilder {
    
    public function addScriptTo(BaseScript $container);
    
}
?>