<?php

declare(strict_types=1);

namespace Technique102\AiTools\Tools;

use Mcp\Capability\Attribute\McpTool;

final readonly class Application
{
    #[McpTool(
        name: 'get_application_info',
        description: 'Возвращает информацию о текущем окружении: версия PHP и версия 1С-Битрикс.',
    )]
    public function info(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'bitrix_version' => SM_VERSION,
        ];
    }
}
