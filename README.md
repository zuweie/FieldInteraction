 # FieldInteraction
 ### 介绍
 - laravel是一个很好的框架，使用起来很方便。而其中的**laravel-admin**更是方便我们对一个完整的Web平台搭建。然后laravel-admin有个不足，让本人使用十分不爽，这个是laravel-admin各个**Field**之间没有互动的功能，或者说是没有互动的接口。这让本人想实现一些控件间互动的逻辑的时候，都非得重新自定义一些控件组，让几个控件集合成一个新的控件来实现.
 - FieldInteraction 提供一个接口，用于注入javascript，使得各个Field之间有互动的可能。
 ### 安装
 - composer require zuweie/field-action 
 - 在laravel中的 config/app 的provider加入 Field\Interaction\InteractionServiceProvider 如下：
 ```
   'providers' => [
      //...
      App\Providers\AppServiceProvider::class,
      App\Providers\AuthServiceProvider::class，
      App\Providers\EventServiceProvider::class,
      App\Providers\RouteServiceProvider::class,
      //...
      Field\Interaction\InteractionServiceProvider::class,
    ]
 ```
 - 在laravel根目录下执行：
 ```
   php artisan vedor:publish --provider="Field\Interaction\InteractionServiceProvider"
 ```
 在public/vendor/interaction/发现有FieldHub.js即安装成功。若不成功，可手动复制vendor/zuweie/field-interaction/resource/js/FieldHub.js 到public/vendor/interaction/下。
 
 ### 使用
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
- 在Form的函数中：
```
    // UserController 中的 form 函数。
    protected function form(){
        $form = new Form(new User());
        ... 
        ...
        // 一些控件的定义
        ...
        ...
        
        // 在定义完控件后。。。
        // new 一个触发事件的Script对象。
        $triggerScript = $this->createTriggerScript($form);
        
        // new -个接收并处理事件的Script对象。
        $subscribeScript = $this->createSubscribeScript($form, function($builder){
           // 添加事件响应函数
           $builder->subscribe('column_listen_to', 'event_of_column_will_triggered', function($event){
           // 这里填写处理事件的javascript函数。
               return <<< EOT
               
               function(data){
                  console.log ('catch an event -> {$event}');
                  // 某个控件对于某个事件做出处理， 
                  $('xxx').doSomething();
                  .... 事件处理 ....
               }
               
     EOT;
           });
        });
        
        // 最后把 $triggerScript 和 $subscribeScript 注入到Form中去。
        $form->scriptinjecter('name_no_care', $triggerScript, $subscribeScript);
        
    }
```
