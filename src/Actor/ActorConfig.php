<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/30
 * Time: 14:21
 */

namespace ESD\Plugins\Actor;


use ESD\Core\Plugins\Config\BaseConfig;

class ActorConfig extends BaseConfig
{
    const key = "actor";
    const groupName = "ActorGroup";
    /**
     * actor最大数量
     * @var int
     */
    protected $actorMaxCount = 10000;
    /**
     * 有多少种actor class类型
     * @var int
     */
    protected $actorMaxClassCount = 100;
    /**
     * @var int
     */
    protected $actorWorkerCount = 1;

    public function __construct()
    {
        parent::__construct(self::key);
    }

    /**
     * @return int
     */
    public function getActorMaxCount(): int
    {
        return $this->actorMaxCount;
    }

    /**
     * @param int $actorMaxCount
     */
    public function setActorMaxCount(int $actorMaxCount): void
    {
        $this->actorMaxCount = $actorMaxCount;
    }

    /**
     * @return int
     */
    public function getActorMaxClassCount(): int
    {
        return $this->actorMaxClassCount;
    }

    /**
     * @param int $actorMaxClassCount
     */
    public function setActorMaxClassCount(int $actorMaxClassCount): void
    {
        $this->actorMaxClassCount = $actorMaxClassCount;
    }


    /**
     * @return int
     */
    public function getActorWorkerCount(): int
    {
        return $this->actorWorkerCount;
    }

    /**
     * @param int $actorWorkerCount
     */
    public function setActorWorkerCount(int $actorWorkerCount): void
    {
        $this->actorWorkerCount = $actorWorkerCount;
    }
}