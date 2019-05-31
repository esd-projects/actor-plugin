<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/29
 * Time: 10:17
 */

namespace ESD\Plugins\Actor;


use ESD\Core\Context\Context;
use ESD\Core\PlugIn\AbstractPlugin;
use ESD\Core\PlugIn\PluginInterfaceManager;
use ESD\Core\Server\Server;
use ESD\Plugins\ProcessRPC\ProcessRPCPlugin;

class ActorPlugin extends AbstractPlugin
{

    /**
     * @var ActorConfig|null
     */
    private $actorConfig;

    /**
     * @var ActorManager
     */
    protected $actorManager;

    /**
     * ActorPlugin constructor.
     * @param ActorConfig|null $actorConfig
     * @throws \ReflectionException
     */
    public function __construct(?ActorConfig $actorConfig = null)
    {
        parent::__construct();
        if ($actorConfig == null) {
            $actorConfig = new ActorConfig();
        }
        $this->actorConfig = $actorConfig;
        $this->atAfter(ProcessRPCPlugin::class);
    }

    public function onAdded(PluginInterfaceManager $pluginInterfaceManager)
    {
        parent::onAdded($pluginInterfaceManager);
        $pluginInterfaceManager->addPlug(new ProcessRPCPlugin());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return "Actor";
    }

    /**
     * 初始化
     * @param Context $context
     * @throws \ESD\Core\Plugins\Config\ConfigException
     * @throws \ReflectionException
     */
    public function beforeServerStart(Context $context)
    {
        $this->actorConfig->merge();
        for ($i = 0; $i < $this->actorConfig->getActorWorkerCount(); $i++) {
            Server::$instance->addProcess("actor-$i", ActorProcess::class, ActorConfig::groupName);
        }
        $this->actorManager = ActorManager::getInstance();
        return;
    }

    /**
     * 在进程启动前
     * @param Context $context
     */
    public function beforeProcessStart(Context $context)
    {
        $this->ready();
    }
}