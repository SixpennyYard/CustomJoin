<?php

declare(strict_types=1);

namespace SixpennyYard\CustomJoin;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\Server;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use SixpennyYard\CustomJoin\bv;

class CustomJoin extends PluginBase implements Listener {
    
    private static $instance;
    
    const CONFIG = [
    "version" => "1.0.0",
    "Message-Join" => "§2[+]§r {player}",
    "Message-Quit" => "§4[-]§r {player}",
    "First-Join" => "Bienvenue à {player} qui nous a rejoint pour la 1ère fois !",
    "Welcome-Message" => "Bienvenue à toi §3{jplayer}§r sur §5Mozira !"
    ];
    
    
    public static function getInstance(): CustomJoin {
        return self::$instance;
        
    }

    public function onEnable(): void {
        
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        
        $this->saveDefaultConfig();
        $this->fixOldConfig();
        
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register('CustomJoin', new bv($this));
        
        self::$instance = $this;
        
    }
    private function fixOldConfig(): void    {
        if ($this->getConfig()->exists("version")){
            if ($this->getConfig()->get("version") !== "2.0.0"){
                $this->getConfig()->setAll(self::CONFIG);
                $this->getConfig()->save();
                
            }
            
        }else{
            $this->getConfig()->setAll(self::CONFIG);
            $this->getConfig()->save();
            
        }
        
    }
    
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        
        if($player->hasPlayedBefore()){
            
            $event->setJoinMessage("");
            $msg = $this->getConfig()->get("Message-Join");
            $msg = str_replace("{break}", "\n", $msg);
            $msg = str_replace("{player}", $player->getName(), $msg);
            $this->getServer()->broadcastMessage($msg);
        
        }else{
            $event->setJoinMessage("");
            $fmsg = $this->getConfig()->get("First-Join");
            $fmsg = str_replace("{break}", "\n", $fmsg);
            $fmsg = str_replace("{player}", $player->getName(), $fmsg);
            $this->getServer()->broadcastMessage($fmsg);
            
        }
        
    }
    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $event->setQuitMessage("");
        $msg = $this->getConfig()->get("Message-Quit");
        $msg = str_replace("{break}", "\n", $msg);
        $msg = str_replace("{player}", $player->getName(), $msg);
        $this->getServer()->broadcastMessage($msg);
        
    }
    
}
