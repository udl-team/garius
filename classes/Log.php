<?php

class Udl_Exception extends Exception
{
	protected $severity;
   
    public function __construct($message, $code, $severity, $filename, $lineno) 
    {
        $this->message = $message;
        $this->code = $code;
        $this->severity = $severity;
        $this->file = $filename;
        $this->line = $lineno;
    }
   
    public function getSeverity() 
    {
        return $this->severity;
    }
	
	function write_log($service, $priority = LOG_INFO) 
	{
		
		if (openlog($service, LOG_ODELAY, LOGDIR)) 
		{
			syslog($priority, strtok($this->message, "\n"));
			while ($t = strtok("\n")) 
			{
				syslog($priority, $t);
			}
			return true;
		}
		return false;
	}
}

?>