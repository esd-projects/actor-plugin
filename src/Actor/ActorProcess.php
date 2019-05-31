<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/31
 * Time: 14:26
 */

namespace ESD\Plugins\Actor;


use ESD\Core\Message\Message;
use ESD\Core\Server\Process\Process;
use ESD\Server\Co\Server;

class ActorProcess extends Process
{

    /**
     * 在onProcessStart之前，用于初始化成员变量
     * @return mixed
     */
    public function init()
    {

    }

    public function onProcessStart()
    {
        $call = $this->eventDispatcher->listen(ActorCreateEvent::ActorCreateEvent);
        $call->call(function (ActorCreateEvent $event) {
            $class = $event->getData()[0];
            $name = $event->getData()[1];
            $data = $event->getData()[2] ?? null;
            $actor = new $class($name);
            if ($actor instanceof Actor) {
                $actor->initData($data);
            } else {
                throw new ActorException("$class is not a actor");
            }
            $this->eventDispatcher->dispatchProcessEvent(new ActorCreateEvent(ActorCreateEvent::ActorCreateReadyEvent . ":" . $actor->getName(), null),
                Server::$instance->getProcessManager()->getProcessFromId($event->getProcessId())
            );
        });
    }

    public function onProcessStop()
    {

    }

    public function onPipeMessage(Message $message, Process $fromProcess)
    {

    }
}