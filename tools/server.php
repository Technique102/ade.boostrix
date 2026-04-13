#!/usr/bin/env php
<?php
declare(strict_types=1);

set_time_limit(0);

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define("BX_NO_ACCELERATOR_RESET", true);
define("BX_CRONTAB", true);
define("STOP_STATISTICS", true);
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("NO_AGENT_CHECK", true);

if (empty($_SERVER["DOCUMENT_ROOT"])) {
    $_SERVER['DOCUMENT_ROOT'] = __DIR__.'/../../../..';
}

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!\Bitrix\Main\Loader::includeModule('technique102.aitools')) {
    fwrite(STDERR, "[CRITICAL ERROR] Необходимо установить модуль \"technique102.aitools\"\n");
    exit(1);
}

use Mcp\Server;
use Mcp\Server\Transport\StdioTransport;

try {
    $builder = Server::make()
        ->setServerInfo('Bitrix AiTools MCP', '0.0.1')
        ->setDiscovery(
            basePath: __DIR__,
            scanDirs: ['/../lib/tools'],
        );

    $event = new \Bitrix\Main\Event('technique102.aitools', 'OnBuildMcpServerToolsList');
    $event->send();
    foreach ($event->getResults() as $eventResult) {
        if ($eventResult->getType() !== \Bitrix\Main\EventResult::SUCCESS) {
            continue;
        }
        $params = $eventResult->getParameters();
        if (is_string($params) && $params !== '') {
            $builder->addTool($params);
        } elseif (is_array($params) && isset($params['handler'])) {
            $builder->addTool(
                $params['handler'],
                $params['name'] ?? null,
                $params['description'] ?? null,
            );
        }
    }

    $modules = \Bitrix\Main\ModuleManager::getInstalledModules();
    foreach ($modules as $moduleId => $_) {
        $config = \Bitrix\Main\Config\Configuration::getInstance($moduleId)->get('technique102.aitools');
        if (!isset($config['mcp']['tools']) || !is_array($config['mcp']['tools'])) {
            continue;
        }
        if (!\Bitrix\Main\Loader::includeModule($moduleId)) {
            continue;
        }
        foreach ($config['mcp']['tools'] as $tool) {
            if (is_string($tool) && $tool !== '') {
                $builder->addTool($tool);
            } elseif (is_array($tool) && isset($tool['handler'])) {
                $builder->addTool(
                    $tool['handler'],
                    $tool['name'] ?? null,
                    $tool['description'] ?? null,
                );
            }
        }
    }

    $builder
        ->build()
        ->connect(new StdioTransport());
} catch (\Throwable $e) {
    fwrite(STDERR, "[CRITICAL ERROR] ".$e->getMessage()."\n");
    exit(1);
}
