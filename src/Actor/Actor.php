<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/23
 * Time: 18:00
 */

namespace ESD\Plugins\Actor;

use DI\Annotation\Inject;
use ESD\Core\Plugins\Event\EventDispatcher;
use ESD\Core\Server\Server;

/**
 * Actor
 * Class ActorRunner
 * @package ESD\Plugins\Actor
 */
abstract class Actor
{
    /**
     * @Inject()
     * @var EventDispatcher
     */
    protected $eventDispatcher;
    /**
     * @var string
     */
    protected $name;


    /**
     * Actor constructor.
     * @param string $name
     * @throws \DI\DependencyException
     * @throws ActorException
     */
    final public function __construct(string $name)
    {
        $this->name = $name;
        Server::$instance->getContainer()->injectOn($this);
        ActorManager::getInstance()->addActor($this);
        //监听
        $call = $this->eventDispatcher->listen(ActorEvent::ALL_COMMAND);
        $call->call(function (ActorEvent $actorEvent) {
            $this->handle($actorEvent);
        });
    }

    /**
     * constructor后附带传递的数据
     * @param $data
     * @return mixed
     */
    abstract public function initData($data);

    protected function handle(ActorEvent $actorEvent)
    {

    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function destroy()
    {
        ActorManager::getInstance()->removeActor($this);
    }

    /**
     * 获取代理
     * @param string $actorName
     * @param bool $oneway
     * @param int $timeOut
     * @return static
     * @throws ActorException
     */
    public static function getProxy(string $actorName, $oneway = false, $timeOut = 5)
    {
        return new ActorRPCProxy($actorName, $oneway, $timeOut);
    }

    /**
     * @param string $actorName
     * @param null $data
     * @param bool $waitCreate
     * @param int $timeOut
     * @return static
     * @throws ActorException
     */
    public static function create(string $actorName, $data = null, $waitCreate = true, $timeOut = 5)
    {
        if (static::class == Actor::class) {
            throw new ActorException("Actor is abstract class");
        }
        $processes = Server::$instance->getProcessManager()->getProcessGroup(ActorConfig::groupName);
        $nowProcess = ActorManager::getInstance()->getAtomic()->add();
        $index = $nowProcess % count($processes->getProcesses());
        Server::$instance->getEventDispatcher()->dispatchProcessEvent(new ActorCreateEvent(
            ActorCreateEvent::ActorCreateEvent,
            [
                static::class, $actorName, $data
            ]), $processes->getProcesses()[$index]);
        if ($waitCreate) {
            if (!ActorManager::getInstance()->hasActor($actorName)) {
                $call = Server::$instance->getEventDispatcher()->listen(ActorCreateEvent::ActorCreateReadyEvent . ":" . $actorName, null, true);
                $result = $call->wait($timeOut);
                if ($result == null) {
                    throw new ActorException("wait actor create timeout");
                }
            }
        }
        return new ActorRPCProxy($actorName, false, $timeOut);
    }
}