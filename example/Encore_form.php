<?php
 namespace App\Admin\Controllers;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form as ModelForm;

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
        $form = new ModelForm(new User());
        
        /*定义控件*/
        $form->select('column1', 'select')->options(['1'=>'1', '2'=>'2']);
        $form->radio('column2', 'radio')->options(['1'=>'enable', '2'=>'disable']);
        $form->checkbox('column3', 'checkbox')->options(['1'=>'1', '2'=>'2', '3'=>'3']);
        $form->text('column5', 'text');
        $form->divider();
        $form->html('以下是响应事件的控件');
        $form->textarea('column4', 'textarea');
        
        // 添加事件触发器
        $trigger_script = $this->createTriggerScript($form);
       
       // 添加事件响应事件
        $subscriber_script = $this->createSubscriberScript($form, function($builder) {
              // 监听column1的select事件
         $builder->subscribe('column1', 'select', function($event) {
            return <<<EOT
       
                function (data) {
                     $('.column4').val('你选择了'+data.text);
                }
EOT;
           });
       
               // 监听column2的checked事件
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
       
           // 监听column3的checked事件
           $builder->subscribe('column3', 'checked' , function ($event) {
             return <<< EOT
            
                function (data) {
                       $('.column4').val('你选择了 ' + data);
                 }
       
       
EOT;
             });
       
           // 监听column3的unchecked事件
           $builder->subscribe('column3', 'unchecked', function ($event) {
           return <<< EOT
       
            function (data) {
                    $('.column4').val('你选择了 ' + data);
             }
       
EOT;
            });
            // 监听column5的input事件
            $builder->subscribe('column5', 'input', function ($event) {
                return <<< EOT
           
                    function (data) {
                      $('.column4').val(data);
                    }
                   
EOT;
             });
       });
       
       /* 将 trigger_script 和 subscriber_script 注入 form 中 */
        $form->scriptinjecter('name_no_care', $trigger_script, $subscriber_script);
        
        return $form;
    }
}
