<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class CustomizeFormatter
{
    private $logFormat="[%datetime%] [%channel%.%level_name%]: %message% %context%" . PHP_EOL;
    private $dateFormat='Y-m-d H:i:s.v';
    
    public function __invoke($logging)
    {
        // formatter
        $formatter = new LineFormatter($this->logFormat, $this->dateFormat, true, true);

        foreach ($logging->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
        }
    }
}
