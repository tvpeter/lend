<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleLogMiddleware\Handler\HandlerInterface;
use GuzzleLogMiddleware\Handler\LogLevelStrategy\FixedStrategy;
use GuzzleLogMiddleware\Handler\LogLevelStrategy\LogLevelStrategyInterface;

/**
 * @author Wisdom Ebong <wisdomaebong@gmail.com>
 */
final class GuzzleRequestHandler implements HandlerInterface
{
    /**
     * @var int
     */
    private $truncateSize;
    /**
     * @var int
     */
    private $summarySize;
    /**
     * @var LogLevelStrategyInterface
     */
    protected $logLevelStrategy;

    /**
     * @param LogLevelStrategyInterface|null $logLevelStrategy
     * @param int $truncateSize If the body of the request/response is greater than the size of this integer the body will be truncated
     * @param int $summarySize The size to use for the summary of a truncated body
     */
    public function __construct(LogLevelStrategyInterface $logLevelStrategy = null, int $truncateSize = 7500, int $summarySize = 3500)
    {
        $this->logLevelStrategy = $logLevelStrategy === null ? $this->getDefaultStrategy() : $logLevelStrategy;
        $this->truncateSize = $truncateSize;
        $this->summarySize = $summarySize;
    }
    /**
     * @param LoggerInterface $logger
     * @param RequestInterface $request
     * @param ResponseInterface|null $response
     * @param Exception|null $exception
     * @param TransferStats|null $stats
     * @param array $options
     * @return void
     */
    public function log(
        LoggerInterface $logger,
        RequestInterface $request,
        ?ResponseInterface $response,
        ?Exception $exception,
        ?TransferStats $stats,
        array $options
    ): void {
        $context['request'] = $this->requestContext($request, $options);

        if ($stats !== null) {
            $context['stats'] = $this->statsContext($stats, $options);
        }

        if ($response !== null) {
            $context['response'] = $this->responseContext($response, $options);
            $level = $this->logLevelStrategy->getLevel($response, $options);
            $logger->log($level, "HTTP response for {$context['request']['uri']}", $context);
        } else {
            $context['reason'] = $this->reasonContext($exception, $options);
            if (empty($context['reason'])) {
                $level = $this->logLevelStrategy->getLevel($request, $options);
                $logger->log($level, "HTTP request for {$context['request']['uri']}", $context);
            } else {
                $level = $this->logLevelStrategy->getLevel($exception, $options);
                $logger->log($level,"HTTP exception for {$context['request']['uri']}", $context);
            }
        }
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return array
     */
    private function requestContext(RequestInterface $request, array $options)
    {
        $context['method'] = $request->getMethod();
        $context['headers'] = $request->getHeaders();
        $context['uri'] = $request->getRequestTarget();
        $context['version'] = 'HTTP/' . $request->getProtocolVersion();

        if ($request->getBody()->getSize() > 0) {
            $context['body'] = $this->formatBody($request, $options);
        }

        return $context;
    }

    /**
     * @param ResponseInterface|null $response
     * @param array $options
     * @return array
     */
    private function responseContext(?ResponseInterface $response, array $options)
    {
        $context['headers'] = $response->getHeaders();
        $context['status_code'] = $response->getStatusCode();
        $context['version'] = 'HTTP/' . $response->getProtocolVersion();
        $context['message'] = $response->getReasonPhrase();
        if ($response->getBody()->getSize() > 0) {
            $context['body'] = $this->formatBody($response, $options);
        }

        return $context;
    }

    /**
     * @param Exception|null $exception
     * @param array $options
     * @return array
     */
    private function reasonContext(?Exception $exception, array $options)
    {
        if ($exception === null) {
            return [];
        }

        $context['reason']['code'] = $exception->getCode();
        $context['reason']['message'] = $exception->getMessage();
        $context['reason']['line'] = $exception->getLine();
        $context['reason']['file'] = $exception->getFile();

        return $context;
    }

    /**
     * @param TransferStats|null $stats
     * @param array $options
     * @return array
     */
    private function statsContext(?TransferStats $stats, array $options)
    {
        return [
            'time' => $stats->getTransferTime(),
            'uri' => $stats->getEffectiveUri(),
        ];
    }

    /**
     * @param MessageInterface $message
     * @param array $options
     * @return string|array
     */
    private function formatBody(MessageInterface $message, array $options)
    {
        $stream = $message->getBody();
        if ($stream->isSeekable() === false || $stream->isReadable() === false) {
            return 'Body stream is not seekable/readable.';
        }
        if (isset($options['log']['sensitive']) && $options['log']['sensitive'] === true) {
            return 'Body contains sensitive information therefore it is not included.';
        }
        if ($stream->getSize() >= $this->truncateSize) {
            $summary = $stream->read($this->summarySize) . ' (truncated...)';
            $stream->rewind();
            return $summary;
        }
        $body = $stream->__toString();
        $contentType = $message->getHeader('Content-Type');
        $isJson = preg_grep('/application\/[\w\.\+]*(json)/', $contentType);
        if (!empty($isJson)) {
            $result = json_decode($body, true);
            $stream->rewind();
            return $result;
        }
        $isForm = preg_grep('/application\/x-www-form-urlencoded/', $contentType);
        if (!empty($isForm)) {
            $result = \GuzzleHttp\Psr7\parse_query($body);
            $stream->rewind();
            return $result;
        }
        $stream->rewind();
        return $body;
    }

    /**
     * @return LogLevelStrategyInterface
     */
    protected function getDefaultStrategy()
    {
        return new FixedStrategy(LogLevel::DEBUG, LogLevel::ERROR);
    }
}
