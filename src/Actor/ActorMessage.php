<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/6/14
 * Time: 15:35
 */

namespace ESD\Plugins\Actor;


class ActorMessage
{
    /**
     * 消息ID
     * @var int
     */
    protected $msgId;
    /**
     * 来自谁
     * @var string
     */
    protected $form;
    /**
     * 内容
     * @var mixed
     */
    protected $data;

    public function __construct($data, $msgId = null, $form = null)
    {
        $this->msgId = $msgId;
        $this->form = $form;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getMsgId(): int
    {
        return $this->msgId;
    }

    /**
     * @param int $msgId
     */
    public function setMsgId(int $msgId): void
    {
        $this->msgId = $msgId;
    }

    /**
     * @return string
     */
    public function getForm(): string
    {
        return $this->form;
    }

    /**
     * @param string $form
     */
    public function setForm(string $form): void
    {
        $this->form = $form;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }
}