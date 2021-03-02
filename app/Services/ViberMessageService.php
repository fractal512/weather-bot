<?php


namespace App\Services;


class ViberMessageService extends ViberService
{
    private $keyboard = [
        [
            "Columns" => 3,
            "BgColor" => "#808B96",
            "ActionType" => "reply",
            "ActionBody" => "main",
            "Text" => "First button",
            "TextSize" => "regular"
        ],
        [
            "Columns" => 3,
            "BgColor" => "#808B96",
            "ActionType" => "reply",
            "ActionBody" => "second",
            "Text" => "Second button",
            "TextSize" => "regular"
        ]
    ];

    public function __construct(){
        parent::__construct();

        $this->endpoint = config('viber.message_url');
    }

    public function dispatchRequest($request)
    {
        $viber = json_decode($request->all()[0], false);
        if( !isset($viber->event) ) abort(500);
        switch ($viber->event) {
            case "conversation_started":
                $this->dispatchConversationStartedEvent($viber);
                break;
            case "message":
                $this->dispatchMessageEvent($viber);
                break;
        }
        return $this;
    }

    private function dispatchConversationStartedEvent($viber)
    {
        if( !isset($viber->user->id) ) abort(500);
        $message['receiver'] = $viber->user->id;
        $message['type'] = 'text';
        $message['text'] = __('Hello! Welcome to Weather chat!');
        $message['keyboard'] = [
            "Type" => "keyboard",
            "Revision" => 1,
            "DefaultHeight" => false,
            "Buttons" => $this->keyboard
        ];
        $this->setMessage($message);
    }

    private function dispatchMessageEvent($viber)
    {
        if( !isset($viber->message->text) ) abort(500);
        switch ($viber->message->text) {
            case "main":
                $this->dispatchMessageText($viber, "main");
                break;
            case "second":
                $this->dispatchMessageText($viber, "second");
                break;
        }
    }

    private function dispatchMessageText($viber, $messageText)
    {
        if( !isset($viber->sender->id) ) abort(500);
        $message['receiver'] = $viber->sender->id;
        $message['type'] = 'text';
        $message['text'] = $messageText;
        $message['keyboard'] = [
            "Type" => "keyboard",
            "Revision" => 1,
            "DefaultHeight" => false,
            "Buttons" => $this->keyboard
        ];
        $this->setMessage($message);
    }
}