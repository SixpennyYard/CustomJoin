<?php

namespace SixpennyYard\CustomJoin;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use SixpennyYard\CustomJoin\Main;

class bv extends Command implements PuginOwned{
    
    private $plugin;
    
    public function __construct() {
        
        parent::__construct("wc", "send a welcome message", "§cusage: /wc <player> or /bv <player>");
        $this->setPermission('customjoin.wc');
        
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)    {
        
        if(empty($args[0])){
            $sender->sendMessage("§cusage: /wc player or /bv player");
            return false;
                
        }

        $bvmsg = CustomJoin::getInstance()->getConfig()->get("Welcome-Message");
        $bvmsg = str_replace("{jplayer}", $args[0], $bvmsg);
        CustomJoin::getInstance()->getServer()->broadcastMessage($bvmsg);
        
    }
    
}
