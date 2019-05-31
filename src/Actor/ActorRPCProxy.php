<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/27
 * Time: 17:44
 */

namespace ESD\Plugins\Actor;


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
}