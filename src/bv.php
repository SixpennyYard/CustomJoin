<?php

namespace SixpennyYard\CustomJoin;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use SixpennyYard\CustomJoin\Main;

class bv extends Command{
    
    private $plugin;
    
    public function __construct() {
        
        parent::__construct("wc", "send a welcome message", "§cusage: /wc <player> or /bv <player>");
        $this->setPermission('use.wc');
        
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
