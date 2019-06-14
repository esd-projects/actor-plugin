<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/23
 * Time: 18:00
 */

namespace ESD\Plugins\Actor;

use DI\Annotation\Inject;
use ESD\Core\Channel\Channel;
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
     * @var Channel
     */
    protected $channel;
    /**
     * @Inject()
     * @var EventDispatcher
     */
    protected $eventDispatcher;
    /**
     * @Inject()
     * @var ActorConfig
     */
    protected $actorConfig;
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

        $this->channel = DIGet(Channel::class, [$this->actorConfig->getActorMailboxCapacity()]);
        //循环处理邮箱内的信息
        goWithContext(function () {
            while (true) {
                $message = $this->channel->pop();
                $this->handleMessage($message);
            }
        });
    }

    /**
     * constructor后附带传递的数据
     * @param $data
     * @return mixed
     */
    abstract public function initData($data);

    /**
     * 处理接收到的消息
     * @param $message
     * @return mixed
     */
    abstract protected function handleMessage(ActorMessage $message);

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

    //这个是提供给代理使用的,这里会接受到一个消息,扔到邮箱中去
    public function sendMessage(ActorMessage $message)
    {
        $this->channel->push($message);
    }

    //这个是提供给代理使用的
    public function startTransaction(callable $call)
    {

    }
}