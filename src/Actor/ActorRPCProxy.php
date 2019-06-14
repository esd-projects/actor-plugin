<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/27
 * Time: 17:44
 */

namespace ESD\Plugins\Actor;


use ESD\Core\Server\Server;
use ESD\Plugins\ProcessRPC\ProcessRPCCallMessage;
use ESD\Plugins\ProcessRPC\RPCProxy;

class ActorRPCProxy extends RPCProxy
{
    /**
     * ActorRPCProxy constructor.
     * @param string $actorName
     * @param bool $oneway
     * @param float $timeOut
     * @throws ActorException
     */
    public function __construct(string $actorName, bool $oneway, float $timeOut = 0)
    {
        $actorInfo = ActorManager::getInstance()->getActorInfo($actorName);
        if ($actorInfo == null) {
            throw new ActorException("actor:$actorName not exist");
        }
        parent::__construct($actorInfo->getProcess(), $actorInfo->getClassName() . ":" . $actorInfo->getName(), $oneway, $timeOut);
    }

    /**
     * 发给Actor一个消息
     * @param $message
     */
    public function sendMessage(ActorMessage $message)
    {
        $message = new ProcessRPCCallMessage($this->className, "sendMessage", [$message], true);
        Server::$instance->getProcessManager()->getCurrentProcess()->sendMessage($message, $this->process);
    }
}