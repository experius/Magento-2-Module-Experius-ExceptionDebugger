<?php


namespace Experius\ExceptionDebugger\Plugin\Magento\Framework\Webapi;

class ErrorProcessor
{
    private $logger = null;

    public function beforeMaskException(
        \Magento\Framework\Webapi\ErrorProcessor $subject,
        \Exception $exception
    ) {
        $realException = $exception;
        $realException = (array)$realException;

        if (!key_exists('raw_message', $realException) ||  strpos($realException['raw_message'], 'An error occurred on the server') !== false) {
            $this->getLogger();
            $this->logger->info('------------------------------------------------------------------------------------------');
            $this->logger->info($realException['raw_message']);
            $this->logger->info('file: ' . $realException['file']);
            $this->logger->info('line: ' . $realException['line']);
            $this->logger->info('Real Exception: ');
            $this->logger->info($realException);
            $this->logger->info("------------------------------------------------------------------------------------------\n\r\n\r\n\r\n\r");
        }

        return [ $exception] ;
    }

    private function getLogger()
    {
        if ($this->logger == null) {
            $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/exception-debugger.log');
            $this->logger = new \Zend\Log\Logger();
            $this->logger->addWriter($writer);
        }
        return $this->logger;
    }

}