<?php 
namespace Field\Interaction;
use Field\Interaction\FieldformatTrait;
use Encode\Admin\Form;
use Encore\Admin\Form\Field;
use Encore\Admin\Form\Field\Text;
use Encore\Admin\Form\Field\Select;
use Encore\Admin\Form\Field\Radio;
use Encore\Admin\Form\Field\Checkbox;
use Encore\Admin\Form\Field\Textarea;
use Encore\Admin\Form\Field\Ip;
use Encore\Admin\Form\Field\Url;
use Encore\Admin\Form\Field\Color;
use Encore\Admin\Form\Field\Email;
use Encore\Admin\Form\Field\Mobile;
use Encore\Admin\Form\Field\File;
use Encore\Admin\Form\Field\Image;
use Encore\Admin\Form\Field\Date;
use Encore\Admin\Form\Field\Datetime;

use Encore\Admin\Form\Field\Time;
use Encore\Admin\Form\Field\Year;
use Encore\Admin\Form\Field\Month;
use Encore\Admin\Form\Field\DateRange;
use Encore\Admin\Form\Field\DateTimeRange;
use Encore\Admin\Form\Field\TimeRange;

use Encore\Admin\Form\Field\Number;
use Encore\Admin\Form\Field\Currency;
use Encore\Admin\Form\Field\SwitchField;
use Encore\Admin\Form\Field\Tags;
use Encore\Admin\Form\Field\MultipleFile;
use Encore\Admin\Form\Field\MultipleImage;
use Encore\Admin\Form\Field\Slider;
use Encore\Admin\Form\Field\Icon;
use Encore\Admin\Form\Field\Listbox;
use Encore\Admin\Form\Field\Captcha;
use Encore\Admin\Form\Field\Rate;
use Encore\Admin\Form\Field\Password;

trait FieldTriggerTrait {
    
    use FieldformatTrait;
    
    public  function createTriggerScript ($fieldsource,  $scriptbuilder=null ) {
        
        $debug = env('APP_DEBUG', true);
        
        if ($fieldsource instanceof \Encore\Admin\Form) {
            return new TriggerScript(new FormFields($fieldsource), $scriptbuilder? $scriptbuilder : $this->createTriggerScriptBuilder($debug));
        }else if (is_array($fieldsource)) {
            return new TriggerScript(new ArrayFields($fieldsource), $scriptbuilder?$scriptbuilder : $this->createTriggerScriptBuilder($debug));
        }
    }
    
    public  function createTriggerScriptBuilder ($debug=false) {
        $triggerBuilder = new TriggerScriptBuilder();
        $debug = $debug?'true' : 'false';
        
        // text 的 trigger
        $triggerBuilder->addTrigger(Text::class, function(Field $field) use ($debug) {
            return <<<EOT
            
            $("input[name='{$field->column()}']").on('input',function(){
                   var text = $(this).val();
                   
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'input')}', text));
                   
                   
                   FieldHub.publish('{$this->formatFieldEvent($field, 'input')}', text);
            });
                           
            $("input[name='{$field->column()}']").on('change',function(){
                   var text = $(this).val();
                   
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', text));
                   
                   
                   FieldHub.publish('{$this->formatFieldEvent($field, 'change')}', text);
            });
                   
EOT;
        });
        
        // select 的 trigger
        $triggerBuilder->addTrigger(Select::class, function(Field $field) use ($debug){
            return <<<EOT
            
            $("select[name='{$field->column()}']").on('select2:select', function(e){
                    var data = e.params.data;
                    
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'select')}', data));
                    
                    FieldHub.publish('{$this->formatFieldEvent($field, 'select')}', data);
            });
            
            $("select[name='{$field->column()}']").on('select2:unselect', function(e){
                    var data = e.params.data;
                    
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'unselect')}', data));
                    
                    FieldHub.publish('{$this->formatFieldEvent($field, 'unselect')}', data);
            });
            
EOT;
        } );
        
        // raido 的 trigger
        $triggerBuilder->addTrigger(Radio::class, function(Field $field) use ($debug){
            return <<<EOT
            
            $("input[name='{$field->column()}']").on('ifChecked', function(event){
                    var text = $(this).val();
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'checked')}', text));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'checked')}', text);
            });
            
EOT;
        });
        
        // checkbox 的 trigger
        $triggerBuilder->addTrigger(CheckBox::class, function(Field $field) use ($debug){
            return <<<EOT
            
            $("input:checkbox[name='{$field->column()}[]']").on('ifChecked', function(event){
                    var checkedvals = [];
                    $("input[name='{$field->column()}[]']:checked").each(function(){
                        checkedvals.push($(this).val());
                    });
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'checked')}', checkedvals));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'checked')}', checkedvals);
            });
            $("input:checkbox[name='{$field->column()}[]']").on('ifUnchecked', function(event){
                    //console.log(event);
                    var checkedvals = [];
                    $("input[name='{$field->column()}[]']:checked").each(function(){
                        checkedvals.push($(this).val());
                    });
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'unchecked')}', checkedvals));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'unchecked')}', checkedvals);
            });
        
EOT;
        });
        
        // textarea 的 trigger
        $triggerBuilder->addTrigger(Textarea::class, function(Field $field) use ($debug) {
            return <<< EOT
            
            $("textarea[name='{$field->column()}']").on('change', function(){
                    var text = $(this).val();
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', text));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'change')}', text);
            });
            
            $("textarea[name='{$field->column()}']").on('input', function(){
                    var text = $(this).val();
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'input')}', text));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'input')}', text);
            });
            
EOT;
        });
        
        // IP 的 trigger
        $triggerBuilder->addTrigger(Ip::class, function(Field $field) use ($debug){
            return <<< EOT
            
            $("input[name='{$field->column()}']").on('change', function(){
                    var text = $(this).val();
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', text));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'change')}', text);
            });
            
            $("input[name='{$field->column()}']").on('input', function(){
                    var text = $(this).val();
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'input')}', text));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'input')}', text);
            });
                    
            
EOT;
        });
        
        // Url 的 trigger
        $triggerBuilder->addTrigger(Url::class, function (Field $field) use ($debug) {
            return <<< EOT
            
            $("input[name='{$field->column()}']").on('change', function(e){
                    var text = $(this).val();
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', text));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'change')}', text);
            });
            
            $("input[name='{$field->column()}']").on('input', function(e){
                    var text = $(this).val();
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'input')}', text));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'input')}', text);
            });
            
EOT;
        });
        
            // color 的 trigger 这是使用的是旧版的2.3.x的colorpicker。注意留言文档。
            
            $triggerBuilder->addTrigger(Color::class, function(Field $field) use ($debug) {
                return <<< EOT

                $("input[name='{$field->column()}']").parent().on('changeColor', function(e){
                        
                        var color = e.color;
                        ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'changecolor')}', text));
                        FieldHub.publish('{$this->formatFieldEvent($field, 'changecolor')}',color);
                });
EOT;
            });
        
            //email 的 trigger
            $triggerBuilder->addTrigger(Email::class, function(Field $field) use ($debug) {
                return <<< EOT
                
                $("input[name='{$field->column()}']").on('change', function(e){
                        var text = $(this).val();
                        ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', text));
                        FieldHub.publish('{$this->formatFieldEvent($field, 'change')}',text);        
                });
                
                $("input[name='{$field->column()}']").on('input', function(e){
                        var text = $(this).val();
                        ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'input')}', text));
                        FieldHub.publish('{$this->formatFieldEvent($field, 'input')}',text);        
                });
                
EOT;
            });
            
            // mobile 的 trigger
            $triggerBuilder->addTrigger(Mobile::class, function(Field $field) use ($debug) {
                return <<< EOT
            
                $("input[name='{$field->column()}']").on('change', function(e){
                        var text = $(this).val();
                        ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', text));
                        FieldHub.publish('{$this->formatFieldEvent($field, 'change')}',text);        
                });
                
                $("input[name='{$field->column()}']").on('input', function(e){
                        var text = $(this).val();
                        ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'input')}', text));
                        FieldHub.publish('{$this->formatFieldEvent($field, 'input')}',text);        
                });
            
EOT;
            });
            
            $triggerBuilder->addTrigger(Slider::class, function(Field $field) use ($debug) {
                return <<< EOT
                
                ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(Slider::class)} no support', '', ''));
                
EOT;
            });
            
            // file 的 trigger
            // http://plugins.krajee.com/file-input/plugin-events
            $triggerBuilder->addTrigger(File::class, function (Field $field) use ($debug) {
                return <<< EOT
                
                $("input[name='{$field->column()}']").on('change', function(e){
                         var files = $(this).prop('files');
                         ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', files));
                         FieldHub.publish('{$this->formatFieldEvent($field, 'change')}', files);
                });
                        
                $("input[name='{$field->column()}']").on('filecleared', function(e){
                        var files = $(this).prop('files');
                         ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'filecleared')}', files));
                         FieldHub.publish('{$this->formatFieldEvent($field, 'filecleared')}', files);
                });        
                // 多做几个事件
                    
EOT;
            });
            
            // image 的 tigger
            $triggerBuilder->addTrigger(Image::class, function(Field $field) use ($debug){
                return <<< EOT
                
                $("input[name='{$field->column()}']").on('change', function(e){
                         var files = $(this).prop('files');
                         ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', files));
                         FieldHub.publish('{$this->formatFieldEvent($field, 'change')}', files);
                });
                        
                $("input[name='{$field->column()}']").on('filecleared', function(e){
                        var files = $(this).prop('files');
                         ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'filecleared')}', files));
                         FieldHub.publish('{$this->formatFieldEvent($field, 'filecleared')}', files);
                });    
                
EOT;
            });
            
               //日期都没办法搞到事件不知道如何搞到
                $triggerBuilder->addTrigger(Date::class, function(Field $field) use ($debug) {
                    return <<< EOT
                    
                    
                    ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(Date::class)} no support !','',''));

EOT;
                });
            
            
            // number 的 trigger 这个控件没有事件支持
            // 官网 https://github.com/wpic/bootstrap-number-input
            $triggerBuilder->addTrigger(Number::class, function(Field $field) use ($debug) {
                return <<< EOT
                
                ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(Number::class)} no support!', '', '')); 
                
EOT;
            });
            
            // currency 的 trigger
            $triggerBuilder->addTrigger(Currency::class, function(Field $field) use ($debug) {
                return <<< EOT
                
                $("input[name='{$field->column()}']").on('input', function(e){
                        var text = $(this).val();
                         ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'input')}', text));
                         FieldHub.publish('{$this->formatFieldEvent($field, 'input')}', text);
                });
                
                $("input[name='{$field->column()}']").on('change', function(e){
                        var text = $(this).val();
                         ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', text));
                         FieldHub.publish('{$this->formatFieldEvent($field, 'change')}', text);
                });
                
EOT;
                
            });
            
            //switch trigger
            $triggerBuilder->addTrigger(SwitchField::class, function(Field $field)  use ($debug) {
                return <<< EOT
                
                $("input[name='{$field->column()}']").prev().children().children('input.la_checkbox').on('switchChange.bootstrapSwitch', function(e, data){
                        var text = $("input[name='{$field->column()}']").val();
                        ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'switchchange')}', text));
                        FieldHub.publish('{$this->formatFieldEvent($field, 'switchchange')}', text);
                });
                
EOT;
            });
            
            // tags trigger
            $triggerBuilder->addTrigger(Tags::class, function(Field $field) use ($debug) {
                return <<< EOT
                
                $("select[name='{$field->column()}[]']").on('select2:select', function(e){
                    var data = e.params.data;
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'select')}', data));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'select')}', data);
                });
                    
                $("select[name='{$field->column()}[]']").on('select2:unselect', function(e){
                    var data = e.params.data;
                    ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'select')}', data));
                    FieldHub.publish('{$this->formatFieldEvent($field, 'unselect')}', data);
                });
EOT;
            });
            
            // icon trigger 没有事件支持
            // https://github.com/farbelous/fontawesome-iconpicker
            $triggerBuilder->addTrigger(Icon::class, function (Field $field) use ($debug) {
                return <<< EOT
                
                ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(Icon::class)} no support!', '', ''));
                
EOT;
                
            });
            
            $triggerBuilder->addTrigger(MultipleFile::class, function(Field $field) use ($debug) {
                return <<< EOT
                
                    $("input[name='{$field->column()}[]']").on('change', function(e){
                         var files = $(this).prop('files');
                         ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', files));
                         FieldHub.publish('{$this->formatFieldEvent($field, 'change')}', files);
                    });
                        
                    $("input[name='{$field->column()}[]']").on('filecleared', function(e){
                        var files = $(this).prop('files');
                         ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'filecleared')}', files));
                         FieldHub.publish('{$this->formatFieldEvent($field, 'filecleared')}', files);
                    });        
                
EOT;
            });
            
            $triggerBuilder->addTrigger(MultipleImage::class, function(Field $field) use ($debug) {
                
                return <<< EOT
                
                $("input[name='{$field->column()}[]']").on('change', function(e){
                         var files = $(this).prop('files');
                         ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', files));
                         FieldHub.publish('{$this->formatFieldEvent($field, 'change')}', files);
                });
                        
                $("input[name='{$field->column()}[]']").on('filecleared', function(e){
                        var files = $(this).prop('files');
                        ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'filecleared')}', files));
                        FieldHub.publish('{$this->formatFieldEvent($field, 'filecleared')}', files);
                });        
                        
EOT;
                
            });
            
            $triggerBuilder->addTrigger(ListBox::class, function(Field $field) use ($debug) {
                
                return <<< EOT
                
                ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(ListBox::class)} no support!', '', ''));
                
EOT;
                
            });
            
            $triggerBuilder->addTrigger(Captcha::class, function (Field $field) use ($debug) {
                return <<< EOT
                
                ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(Captcha::class)} no support!', '', ''));
                        
EOT;
            });
            
            
            $triggerBuilder->addTrigger(Rate::class, function (Field $field) use ($debug) {
                return <<< EOT
                
                $("input[name='{$field->column()}']").on('change', function(e){
                        var text = $(this).val();
                        ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'change')}', text));
                        FieldHub.publish('{$this->formatFieldEvent($field, 'change')}',text);        
                });
                
                $("input[name='{$field->column()}']").on('input', function(e){
                        var text = $(this).val();
                        ({$debug}) && console.log(FieldHub.triggerlog('triggering an event', '{$this->formatFieldEvent($field, 'input')}', text));
                        FieldHub.publish('{$this->formatFieldEvent($field, 'input')}',text);        
                });
                
EOT;
            });
            
            
            $triggerBuilder->addTrigger(Password::class, function (Field $field) use ($debug) {
                return <<< EOT
                
                ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(Password::class)} no support!', '', ''));
                
EOT;
            });
            
            
            $triggerBuilder->addTrigger(Datetime::class, function (Field $field) use ($debug) {
                
                return <<< EOT
                
                ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(Datetime::class)} no support!', '', ''));
                
EOT;
                
            });
            
            $triggerBuilder->addTrigger(Time::class, function (Field $field) use ($debug) {
                
                return <<< EOT
                
                ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(Time::class)} no support!', '', ''));
                
EOT;
                });

            $triggerBuilder->addTrigger(Year::class, function (Field $field) use ($debug) {
                
                return <<< EOT
                
                ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(Year::class)} no support!', '', ''));
                
EOT;
            });
            
            $triggerBuilder->addTrigger(Month::class, function (Field $field) use ($debug) {
                
                    return <<< EOT
                
                    ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(Month::class)} no support!', '', ''));
                
EOT;
            });
                
            
                $triggerBuilder->addTrigger(DateRange::class, function (Field $field) use ($debug) {
                
                    return <<< EOT
                
                    ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(DateRange::class)} no support!', '', ''));
                
EOT;
                });    
                

                $triggerBuilder->addTrigger(DateTimeRange::class, function (Field $field) use ($debug) {
                    
                    return <<< EOT
                    
                    ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(DateTimeRange::class)} no support!', '', ''));
                    
EOT;
                    });
                
                

                    $triggerBuilder->addTrigger(TimeRange::class, function (Field $field) use ($debug) {
                            
                        return <<< EOT
            
                        ({$debug}) && console.log(FieldHub.triggerlog('{$this->formatFieldClazz(TimeRange::class)} no support!', '', ''));
            
EOT;
                    });
                
                
        return $triggerBuilder;
    }
    
}
?>