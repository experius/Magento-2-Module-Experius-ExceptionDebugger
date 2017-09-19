<?php


namespace Experius\ExceptionDebugger\Plugin\Magento\Framework\Webapi;

class ErrorProcessor
{
    private $logger = null;

    public function beforeMaskException(
        \Magento\Framework\Webapi\ErrorProcessor $subject,
        \Exception $exception
    ) {
        if ((!method_exists($exception, 'getRawMessage') && !(strpos($exception->getMessage(), 'An error occurred on the server') !== false)) ||  strpos($exception->getRawMessage(), 'An error occurred on the server') !== false) {
            $this->getLogger();
            $this->logger->info('------------------------------------------------------------------------------------------');
            $this->logger->info((method_exists($exception, 'getRawMessage')) ? $exception->getRawMessage() : $exception->getMessage());
            $this->logger->info('file: ' . $exception->getFile());
            $this->logger->info('line: ' . $exception->getLine());
            $this->logger->info('Real Exception: ');
            $this->logger->info($exception);
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