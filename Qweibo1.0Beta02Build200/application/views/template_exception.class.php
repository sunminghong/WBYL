<?php
/******************************************************************************
 * Author: michal
 * Last modified: 2010-11-21 04:25
 * Filename: template_exception.class.php
 * Description:	模板异常类 
******************************************************************************/
class TemplateException extends Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0) {
        // some code
    
        // make sure everything is assigned properly
        parent::__construct($message, $code);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
?>
