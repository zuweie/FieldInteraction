<?php 
namespace Field\Interaction;
use Field\Interaction\Base\BaseFieldSet;
use Illuminate\Support\Collection;

class ArrayFields extends BaseFieldSet {
    
    function __construct($fields){
        $this->arr_fields = collect($fields);
    }
    
    public function getFields() : Collection {
        return $this->arr_fields;
    }
    
    protected  $arr_fields;
    
}
?>