<?php

declare(strict_types=1);

namespace SixpennyYard\CustomJoin;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\Config;

use SixpennyYard\CustomJoin\bv;

class CustomJoin extends PluginBase implements Listener
{
    private $config;

    private static $instance;

    public static function getInstance(): CustomJoin
    {
        return self::$instance;
    }

    protected function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this,$this);

        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        if ($this->config->get("popup", false) !== true)
        {
            if ($this->config->get("popup", false) !== false)
            {
                $this->getServer()->getLogger()->error("Error in CustomJoin config: Unknown \"" . $this->config->get("popup") . "\" configuration \n§6[§rCustomJoin§6]§4 Set \"popup\" on true or false ! \n§6[§rCustomJoin§6]§4 Fix it and restart the server to turn the plugin on.");
                $this->getScheduler()->scheduleDelayedTask(new ClosureTask(function(): void{
                    $this->getServer()->getPluginManager()->disablePlugin($this);
                }), 5);
            }
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register('CustomJoin', new bv($this));
        
        self::$instance = $this;
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        if ($player->hasPlayedBefore())
        {
            if ($this->config->get("popup", false) === false)
            {
                $msg = $this->getConfig()->get("join-message");
                $msg = str_replace("{break}", "\n", $msg);
                $msg = str_replace("{player}", $player->getName(), $msg);
                $event->setJoinMessage($msg);
            }
            elseif ($this->config->get("popup", false) === true)
            {
                $event->setJoinMessage("");
                $msg = $this->getConfig()->get("join-message");
                $msg = str_replace("{break}", "\n", $msg);
                $msg = str_replace("{player}", $player->getName(), $msg);
                $this->getServer()->broadcastPopup($msg);
            }
        }else{
            $msg = $this->getConfig()->get("first-join");
            $msg = str_replace("{break}", "\n", $msg);
            $msg = str_replace("{player}", $player->getName(), $msg);
            $event->setJoinMessage($msg);
        }
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        if($this->config->get("popup", false) === false)
        {
            $msg = $this->getConfig()->get("quit-message");
            $msg = str_replace("{break}", "\n", $msg);
            $msg = str_replace("{player}", $player->getName(), $msg);
            $event->setQuitMessage($msg);
        }
        elseif ($this->config->get("popup", false) === true)
        {
            $event->setQuitMessage($msg);
            $msg = $this->getConfig()->get("quit-message");
            $msg = str_replace("{break}", "\n", $msg);
            $msg = str_replace("{player}", $player->getName(), $msg);
            $this->getServer()->broadcastPopup($msg);
        }
    }
    
}
