<?php

declare(strict_types=1);

namespace Passionweb\OpenAiApi\Controller;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class AiController extends ActionController
{
    protected array $extConf;
    protected RequestFactory $requestFactory;
    protected LoggerInterface $logger;

    public function __construct(
        array $extConf,
        RequestFactory $requestFactory,
        LoggerInterface $logger
    )
    {
        $this->extConf = $extConf;
        $this->requestFactory = $requestFactory;
        $this->logger = $logger;
    }

    public function indexAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }
    public function sendPromptAction(): ResponseInterface
    {
        try {
            $prompt = $_POST['tx_openaiapi_example']['prompt'];
            $jsonContent = [
                "model" => $this->extConf['openAiModel'],
                "temperature" => (float)$this->extConf['openAiTemperature'],
                "max_tokens" => (int)$this->extConf['openAiMaxTokens'],
                "top_p" => (float)$this->extConf['openAiTopP'],
                "frequency_penalty" => (float)$this->extConf['openAiFrequencyPenalty'],
                "presence_penalty" => (float)$this->extConf['openAiPresencePenalty']
            ];

            if ($this->extConf['openAiModel'] === 'gpt-3.5-turbo') {
                $jsonContent["messages"][] = [
                    'role' => 'user',
                    'content' => $prompt
                ];
            } else {
                $jsonContent["prompt"] = $prompt;
            }

            $response = $this->requestFactory->request(
                $this->extConf['openAiModel'] === 'gpt-3.5-turbo' ?
                    'https://api.openai.com/v1/chat/completions' : 'https://api.openai.com/v1/completions',
                'POST',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->extConf['openAiApiKey']
                    ],
                    'json' => $jsonContent
                ]
            );

            $resJsonBody = $response->getBody()->getContents();
            $resBody = json_decode($resJsonBody, true);

            $aiResponseText = $this->extConf['openAiModel'] === 'gpt-3.5-turbo' ?
                $resBody['choices'][0]['message']['content'] : $resBody['choices'][0]['text'];
            $this->view->assign('aiResponseText', $aiResponseText);
        } catch(GuzzleException $e) {
            $this->logger->error($e->getMessage());
            $this->view->assign('error', $e->getMessage());
        } catch(Exception $e) {
            $this->logger->error($e->getMessage());
            $this->view->assign('error', $e->getMessage());
        }
        return $this->htmlResponse();
    }
}
