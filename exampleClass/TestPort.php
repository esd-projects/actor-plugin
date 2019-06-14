<?php
/**
 * Created by PhpStorm.
 * User: administrato
 * Date: 2019/5/15
 * Time: 13:48
 */

namespace ESD\Plugins\Actor\ExampleClass;


use ESD\Core\Server\Beans\Request;
use ESD\Core\Server\Beans\Response;
use ESD\Core\Server\Beans\WebSocketFrame;
use ESD\Core\Server\Port\ServerPort;
use ESD\Plugins\Actor\ActorMessage;

class TestPort extends ServerPort
{
    public function onTcpConnect(int $fd, int $reactorId)
    {
        // TODO: Implement onTcpConnect() method.
    }

    public function onTcpClose(int $fd, int $reactorId)
    {
        // TODO: Implement onTcpClose() method.
    }

    public function onWsClose(int $fd, int $reactorId)
    {
        // TODO: Implement onWsClose() method.
    }

    public function onTcpReceive(int $fd, int $reactorId, string $data)
    {
        // TODO: Implement onTcpReceive() method.
    }

    public function onUdpPacket(string $data, array $clientInfo)
    {
        // TODO: Implement onUdpPacket() method.
    }

    public function onHttpRequest(Request $request, Response $response)
    {
        $actor1 = TestActor::create("actor1");
        $actor1->sendMessage(new ActorMessage("message"));
        $actor1->sendMessage(new ActorMessage("message"));
        $actor1->sendMessage(new ActorMessage("message"));
        $actor1->sendMessage(new ActorMessage("message"));
        goWithContext(function ()use ($actor1){
            var_dump($actor1->test());
        });
        goWithContext(function ()use ($actor1){
            var_dump($actor1->test());
        });
    }

    public function onWsMessage(WebSocketFrame $frame)
    {
        // TODO: Implement onWsMessage() method.
    }

    public function onWsPassCustomHandshake(Request $request): bool
    {
        // TODO: Implement onWsPassCustomHandshake() method.
    }

    public function onWsOpen(Request $request)
    {
        // TODO: Implement onWsOpen() method.
    }
}