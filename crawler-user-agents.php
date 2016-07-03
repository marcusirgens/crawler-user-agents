<?php declare(strict_types=1);

namespace marcuspi/Crawlers

class Crawlers
{
    public $crawlers = [];
    
    private function __construct() {
        $this->crawlers = json_decode(file_get_contents("crawler-user-agents.json"), true);
    }
    
    public function getCrawlers() {
        return $this->crawlers;
    }
    
    public static function isCrawler($agent = null) : bool
    {
        if(is_null($agent)) {
            if(!isset($_SERVER['HTTP_USER_AGENT'])) {
                throw new \Exception("Could not find the user agent"); 
            }
            $agent = $_SERVER['HTTP_USER_AGENT'];
        }
        
        $patterns = array_map(function($v) {
            return $v['pattern'];
        }, (new static())->getCrawlers());
        
        foreach($patterns as $pattern) {
            if(preg_match('/' . $pattern . '/', $pattern)) return true;
        }
        
        return false;
    }
