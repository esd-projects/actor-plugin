<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/31
 * Time: 14:41
 */

namespace ESD\Plugins\Actor;


use ESD\Core\Plugins\Event\Event;

class ActorCreateEvent extends Event
{
    const ActorCreateEvent = "ActorCreateEvent";
    const ActorCreateReadyEvent = "ActorCreateReadyEvent";

    public function __construct($type, $data)
    {
        parent::__construct($type, $data);
    }
}