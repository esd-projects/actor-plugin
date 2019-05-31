<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/27
 * Time: 17:42
 */

namespace ESD\Plugins\Actor;


use ESD\Server\Co\Server;

trait GetActorRpc
{
    /**
     * @param string $actorName
     * @param bool $oneway
     * @param float $timeOut
     * @return ActorRPCProxy
     * @throws ActorException
     */
    public function callActor(string $actorName, bool $oneway = false, float $timeOut = 5): ActorRPCProxy
    {
        return new ActorRPCProxy($actorName, $oneway, $timeOut);
    }

    /**
     * 只有创建Actor的进程才能用这个监听
     * @param string $actorName
     * @param int $timeOut
     * @throws ActorException
     */
    public function waitActorCreate(string $actorName, $timeOut = 5)
    {
        if (!ActorManager::getInstance()->hasActor($actorName)) {
            $call = Server::$instance->getEventDispatcher()->listen(ActorCreateEvent::ActorCreateReadyEvent . ":" . $actorName, null, true);
            $result = $call->wait($timeOut);
            if ($result == null) {
                throw new ActorException("wait actor create timeout");
            }
        }
    }
}