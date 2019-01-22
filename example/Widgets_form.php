<?php
 namespace App\Admin\Controllers;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Widgets\Form as WidgetsForm;

use Field\Interaction\FieldTriggerTrait;
use Field\Interaction\FieldSubscriberTrait;
use App\User;

/** 此代码只做展示，不能运行**/

class UserController extends Controller
{
    // 使用FieldTriggerTrait，和FieldSubscriberTrait
    use HasResourceActions, FieldTriggerTrait, FieldSubscriberTrait;

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        /**
         * Widget form 没有获取内部fields的接口，所以只能在外部将fields收集起来。
         * 
         */
        $form = new WidgetsForm();
        $fields = array();
        
        $f = $form->select('column1', 'select');
        $f->options(['1'=>'1', '2'=>'2']);
        array_push($fields, $f);
        
        $f = $form->radio('column2', 'radio');
        $f->options(['1'=>'enable', '2'=>'disable']);
        array_push($fields, $f);
        
        $f = $form->checkbox('column3', 'checkbox');
        $f->options(['1'=>'1', '2'=>'2', '3'=>'3']);
        array_push($fields, $f);
        
        $f = $form->text('column5', 'text');
        array_push($fields, $f);
        
        $form->divider();
        $form->html('以下是响应事件的控件');
        $f = $form->textarea('column4', 'textarea');
        array_push($fields, $f);
        
        // 因为将收集到fields放入createTriggerScript和createSubscriberScript中生成Script
        
        $trigger_script = $this->createTriggerScript($fields);
        
        $subscriber_script = $this->createSubscriberScript($fields, function($builder) {
            $builder->subscribe('column1', 'select', function($event) {
                return <<<EOT
        
                   function (data) {
                           $('.column4').val('你选择了'+data.text);
                   }
EOT;
            });
                 
                $builder->subscribe('column2', 'checked', function ($event) {
                    return <<< EOT
        
                   function (data) {
                           if (data == '1'){
                               $('.column4').attr('disabled', false);
                           }else{
                               $('.column4').attr('disabled', true);
                           }
                   }
        
EOT;
                });
                     
                     
                    $builder->subscribe('column3', 'checked' , function ($event) {
                        return <<< EOT
        
                   function (data) {
                           $('.column4').val('你选择了 ' + data);
                   }
        
        
EOT;
                    });
                         
                        $builder->subscribe('column3', 'unchecked', function ($event) {
                            return <<< EOT
        
                   function (data) {
                           $('.column4').val('你选择了 ' + data);
                   }
        
EOT;
                        });
                             
                            $builder->subscribe('column5', 'input', function ($event) {
                                return <<< EOT
        
                   function (data) {
                           $('.column4').val(data);
                   }
        
EOT;
                            });
                                 
        });
        $form->scriptinjecter('name_no_care', $trigger_script, $subscriber_script);
        
        return $form;
    }
}
