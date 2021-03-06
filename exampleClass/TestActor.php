<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/31
 * Time: 14:59
 */

namespace ESD\Plugins\Actor\ExampleClass;


use ESD\Plugins\Actor\Actor;
use ESD\Plugins\Actor\ActorMessage;

class TestActor extends Actor
{

    /**
     * @var ChildActor
     */
    private $child;

    public function initData($data)
    {
        $this->child = ChildActor::create("child");
    }

    /**
     * 处理接收到的消息
     * @param $message
     * @return mixed
     */
    protected function handleMessage(ActorMessage $message)
    {
        sleep(2);
        var_dump($message->getData());
    }

    public function test()
    {
        $this->child->startTransaction(function (){
            var_dump($this->child->test());
            var_dump($this->child->test2());
        });
        return "ok";
    }
}