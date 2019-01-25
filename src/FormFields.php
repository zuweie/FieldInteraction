<?php 
 namespace Field\Interaction;
 
 use Field\Interaction\Base\BaseFieldSet;
 use Encore\Admin\Form;
 use Illuminate\Support\Collection;
 
 class FormFields extends BaseFieldSet{
     
     function __construct(Form $form) {
         $this->form = $form;
     }
     
     public function getFields() : Collection {
         $builder = $this->form->builder();
         return $builder->fields();
     }
     
     protected $form;
 }
 
?>