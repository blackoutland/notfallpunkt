<?php

namespace BlackoutLand\NotfallPunkt\Model;

/**
 * Memcache keys:
 * - chat.index -> integer-based count
 */
class ChatManager
{
    /** @var MemcacheConnection */
    private $memcache;

    /**
     * @var array
     */
    private $mcKeys = [];

    public function __construct()
    {
        $this->memcache = Utils::getMemcacheConnection();
        $mcKeys         = Utils::getAllMemcacheKeys();
        sort($mcKeys);
        foreach ($mcKeys as $key) {
            if (str_starts_with($key, 'chat.')) {
                $this->mcKeys[] = $key;
            }
        }
    }

    public function getMessagesSince($timestamp)
    {
        // Initialize chat keys
        $chatIndex = 0;
        if (!isset($this->mcKeys['chat.index'])) {
            $this->memcache->set('chat.index', 0);
        } else {
            $chatIndex = $this->memcache->get('chat.index');
        }

        $keysToRead = [];
        foreach ($this->mcKeys as $key) {
            if (preg_match("/^chat.msg.([0-9]+).(.*)@(.*)$/", $key, $m)) {
                $ts = (int)$m[1];
                if ($ts > $timestamp) {
                    $keysToRead[] = $key;
                }
            }
        }

        $messages = [];
        if ($keysToRead) {
            foreach ($keysToRead as $key) {
                $messages[] = $this->memcache->get($key);
            }
        }

        return $messages;
    }

    public function addMessage($login, $messageId, $message, $expiration)
    {
        $msg = [
            't' => time(),
            'i' => $messageId,
            'm' => $message,
            'u' => $login
        ];
        return $this->memcache->set('chat.msg.' . time() . '.' . $messageId . '@' . $login, $msg, $expiration);
    }
}