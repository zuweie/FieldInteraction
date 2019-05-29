<?php 
namespace Field\Interaction;

use Encore\Admin\Admin;
use Encore\Admin\Form\Field;

class ScriptInjecter extends Field{
    
    protected static $js = [
            '/vendor/laravel-admin-ext/field-interaction/js/FieldHub.js',
    ];
    
    public function __construct($column, $scripts) {
        $this->scripts = $scripts;
    }
    
    public function render () {
        $script_output = '';
        foreach ($this->scripts as $script) {
            $script->genScript();
            $script_output .= $script;
        }
        Admin::script($script_output);
        return '';
    }
    
    protected $scripts;
}
?>