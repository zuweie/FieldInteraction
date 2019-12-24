 # FieldInteraction
 ### 项目来由
 - laravel是一个很好的框架，使用起来很方便。而其中的**laravel-admin**更是方便我们对一个完整的Web平台搭建。但laravel-admin各个**Field**之间没有互动的功能，或者说是没有互动的接口。这让我想实现一些控件间互动的逻辑的时候，都非得重新自定义一个控件组，让几个控件组合成一个新的控件来实现。
 
 - FieldInteraction 提供一个接口，用于注入javascript，使得各个Field之间有互动的可能。
 
 ### laravel-admin中两个Form
 - laravel-admin中有两个Form类，Encore\Admin\Form与Encore\Admin\Widgets\Form。
 
 - 两者区别在于Encore\Admin\Form自带model，Encore\Admin\Widgets\Form不带model。本项目并不关心其带不带model，只关心Form里面Fields。
 
 - Encore\Admin\Form有公开接口可以获取Form中的Fields，而Encore\Admin\Widgets\Form则没有。于是对于不同的Form，需要有不同调用方式。
 
 ### 演示 gif （如果没反应，请点击观看)
 ![FieldInteraction](http://cdn.qiniu.inetwon.com/field-Interaction.gif "演示，可能不太清晰")
 
 ### 演示的源码
 - [Encore\Admin\Form演示源码](https://github.com/zuweie/FieldInteraction/blob/master/example/Encore_form.php)
 - [Encore\Admin\Widgets\Form演示源码](https://github.com/zuweie/FieldInteraction/blob/master/example/Widgets_form.php)
 
 ### 安装
 - composer require zuweie/field-interaction 
 - 执行以下命令：
 ```
   php artisan vendor:publish --provider="Field\Interaction\InteractionServiceProvider"
 ```
 - 若执行了以上命令却没有发现有任何的JS文件拷贝，则需要手动拷贝JS文件到指定位置：
 
 /vendor/zuweie/field-interaction/resource/assets/js/FieldHub.js ==> /public/vendor/laravel-admin-ext/field-interaction/js/FieldHub.js

至于为什么有时候不会发生copy。这个我也不晓得。咱也不敢说，咱也不敢问，有知道的老铁，请私信一下～～

 ### 用法
 - 在app/Admin/bootstrap.php文件中注册 **scriptinjecter**
 ```
    //.... 一些你自己的代码 ....
    // 这个是例子，和本项目无关
    Encore\Admin\Form::forget(['map', 'dev']); 
    //.... 一些你自己的代码 ....
    
    // 注册scriptinjecter，稍后使用。
    Encore\Admin\Form::extend('scriptinjecter', Field\Interaction\ScriptInjecter::class);
    
 ```
 - 这里以Admin中生成的UserController为例子(关于Admin的controller生成，请参考laravel-admin的文档)，在UserController中引入以下两个Trait。
 ```
   use Field\Interaction\FieldTriggerTrait;
   use Field\Interaction\FieldSubscriberTrait;
   
   class UserController extends Controller 
   {
      use FieldTriggerTrait, FieldSubscriberTrait, ......;
      .....
      ....
      ....
```
- 在Form的函数中(Encore\Admin\Form)：
```
    // UserController 中的 form 函数。
    protected function form(){
        // Encore\Admin\Form
        $form = new Form(new User());
        ... 
        ...
        // 一些控件的定义
        ...
        ...
        
        // 在定义完控件后。。。
        // 弄一个触发事件的Script对象。
        $triggerScript = $this->createTriggerScript($form);
        
        // 弄-个接收并处理事件的Script对象。
        $subscribeScript = $this->createSubscriberScript($form, function($builder){
           // 添加事件响应函数
           $builder->subscribe('column_listen_to', 'event_will_triggered', function($event){
           
           // 这里填写处理事件的javascript脚本，注意：一定要返回一个完整的 javascript function ，否则报错！！！！
               return <<< EOT
               
               // function中的参数data，是事件自带数据，方便做逻辑处理！data会因为事件不同而类型不同，具体可以在chrome中的console中查看。
               function(data){
                  console.log ('catch an event -> {$event}');
                  // 某个控件对于某个事件做出处理， 
                  
                  $('xxx').doSomething();
                  //.... 事件处理 ....
               }
               
     EOT;
           });
        });
        
        // 最后把 $triggerScript 和 $subscribeScript 注入到Form中去。
        // scriptinjecter 第一个参数可以为任何字符，但不能为空！！！！
        $form->scriptinjecter('any_name_but_no_empty', $triggerScript, $subscribeScript);
        
    }
```
- 在Form函数中(Encore\Admin\Widgets\Form)
```
    protected function form(){
    
        // Encore\Admin\Widgets\Form
        $form = new Form();
        
        // 定义一个数组用于收
        $fields = array();
        
        $f = $form->select('xxxx', 'xxx');
        array_push($fields, $f);
        $f->option([...]);
        
        $f = $form->text('xxxx', 'xxx');
        array_push($fields, $f);
        
        
        // 在定义完控件后。。。
        // 弄一个触发事件的Script对象。
        $triggerScript = $this->createTriggerScript($fields);
        
        // 弄-个接收并处理事件的Script对象。
        $subscribeScript = $this->createSubscriberScript($fields, function($builder){
           // 添加事件响应函数
           $builder->subscribe('column_listen_to', 'event_will_triggered', function($event){
           
           // 这里填写处理事件的javascript脚本，注意：一定要返回一个完整的 javascript function ，否则报错！！！！
               return <<< EOT
               
               // function中的参数data，是事件自带数据，方便做逻辑处理！data会因为事件不同而类型不同，具体可以在chrome中的console中查看。
               function(data){
                  console.log ('catch an event -> {$event}');
                  // 某个控件对于某个事件做出处理， 
                  
                  $('xxx').doSomething();
                  //.... 事件处理 ....
               }
               
     EOT;
           });
        });
        
        // 最后把 $triggerScript 和 $subscribeScript 注入到Form中去。
        // scriptinjecter 第一个参数可以为任何字符，但不能为空！！！！
        $form->scriptinjecter('anyname_but_no_empty', $triggerScript, $subscribeScript);
        
    }
```
- **注意**在form使用tab的情况下，脚本注入需要特殊处理。

在form使用了tab函数的情况下，需要把$form->scriptinjecter(...)这个函数写在任意一个tab函数中，代码如下：
```
 protected function form() { 
 
        // UserController form 函数中
        $form = new Form(new User());
        
        // 创建触发脚本
        $trigger = $this->createTriggerScript($form);
        
        // 创建监听和响应的脚本。
        $subscribe = $this->createSubscriberScript($form, function($builder){
            $builder->subscribe('username', 'input', function($event){
                return <<< EOT
                
                function (data) {
                       console.log(data);
                }
EOT;
            });
        });
        
        // 使用了tab的form
        
         $form->tab('Basic info', function ($form) {
        
            $form->text('username');
            $form->email('email');
            
        })->tab('Profile', function ($form)  {
        
            $form->image('avatar');
            $form->text('address');
            $form->mobile('phone');
            
        })->tab('Jobs', function ($form) use ($trigger, $subscribe)  {
                $form->text('company');
                $form->date('start_date');
                $form->date('end_date');
                
                /*** scriptinjecter 函数放在任意一个tab函数中，这里示例代码放在了最后一个tab，也可以放任意前两个tab函数，任君选择 ***/
                /*** scriptinjecter 函数只需在任意一个tab中调用一次即可，不能重复调用！***/
                $form->scriptinjecter('xxx', $trigger, $subscribe);
        });
}
```
### 说明
- $createTriggerScript 返回一个针对原来laravel-admin已有的控件的事件触发脚本。但是很遗憾有一些控件，我是怎么也找不到他们的触发事件，以下给出laravel-admin中的支持触发事件的控件，以及他们的触发的事件

控件类|能否触发|触发事件|说明
--|:--|:--|:--
Text|是|input / change|-
Select|是|select / unselect | -
Radio|是|checked|-
checkbox|是|checked / unchecked| -
Textarea|是|input / change | -
Url|是|input / change|-
Color| 是 | changeColor| -
Email | 是 | input / change | -
Mobile | 是 | input / change | -
File | 是 | change / fileclear | -
Image | 是 | change / fileclear | -
Date | 否 | - | -
Number | 是 | number_change | -
Currency | 是 | change / input | -
SwitchField | 是 | switchchange | -
Tags | 是 | select / unselect | -
Icon | 否 | - | - 
MultipleFile | 是 | change / fileclear | -
MultipleImage | 是 | change / fileclear | -
ListBox | 否 | - | -
Rate | 是 | change / input | -
Password | 否 | - | -
Datetime | 否 | - | -
Time | 否 | - | -
Year | 否 | - | -
Month | 否 | - | -
DateRange | 否 | - | -
DateTimeRange | 否 | - | -
TimeRange | 否 | - | -

- createSubscriberScript 接收事件并处理事件：
*TriggerScript* 是为了控件能触发事件，那么 *SubscriberScript* 就是为了监听和处理事件。监听和处理事件需要在createSubscriberScript函数中的$builder对象添加。下面简要说明如何在$builder添加监听和处理。
   - $builder->subscribe(arg1, arg2, func);
   - arg1 : 需要关注的控件的名称。
   - arg2 : 需要监听的事件，每个控件有一个或者以上的事件，具体查看TriggerScript给出的表格。
   - func : 事件监听后的函数，必须返回一个完整的javascript的function，否则会出现语法错误。
  
  
### 后记
- 因为时间关系，本项目只在chrome浏览器上做过测试，请慎用～～～～
- 此项目基于本人的一些简单项目需求，所以实现的功能不多，有特别需求的可以在iusses开帖，你们的需求，是改进的动力～～～













