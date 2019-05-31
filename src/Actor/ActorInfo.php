<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/27
 * Time: 18:43
 */

namespace ESD\Plugins\Actor;

use ESD\Core\Server\Process\Process;

class ActorInfo
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var Process
     */
    protected $process;
    /**
     * @var string
     */
    protected $className;

    /**
     * @var int
     */
    protected $createTime;
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Process
     */
    public function getProcess(): Process
    {
        return $this->process;
    }

    /**
     * @param Process $process
     */
    public function setProcess(Process $process): void
    {
        $this->process = $process;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName(string $className): void
    {
        $this->className = $className;
    }

    /**
     * @return int
     */
    public function getCreateTime(): int
    {
        return $this->createTime;
    }

    /**
     * @param int $createTime
     */
    public function setCreateTime(int $createTime): void
    {
        $this->createTime = $createTime;
    }
}