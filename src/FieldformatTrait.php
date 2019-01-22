<?php 
namespace Field\Interaction;
use Encore\Admin\Form\Field;

trait FieldformatTrait {
    public function formatFieldClazz ($field_class) {
        $string_class = strval($field_class);
        $string_class = str_replace('\\', '_', $string_class);
        return $string_class;
    }
    public function formatFieldId (Field $field) {
        $f_class = get_class($field);
        $f_class = $this->formatFieldClazz($f_class);
        $f_id = $f_class.'_'.$field->column();
        return $f_id;
    }
    
    public function formatFieldEvent (Field $field, $event) {
        $f_event = $this->formatFieldId($field).':'.$event;
        return $f_event;
    }
        
}
?>