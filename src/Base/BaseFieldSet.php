<?php 
namespace Field\Interaction\Base;

use Illuminate\Support\Collection;
use Encore\Admin\Form\Field;

abstract  class BaseFieldSet {
    
      public abstract function getFields() : Collection;
      
      public function getField($name){
          $fields = $this->getFields();
          return $fields->first(function (Field $field) use ($name) {
              return $field->column() == $name;
          });
      }
  
}