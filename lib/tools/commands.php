<?php

declare(strict_types=1);

namespace Technique102\AiTools\Tools;

use Mcp\Capability\Attribute\McpTool;

final readonly class Commands
{
    #[McpTool(
        name: 'get_commands_list',
        description: 'Возвращает информацию о списке консольных команд.',
    )]
    public function list(): array
    {
        $result = [];

        $modules = \Bitrix\Main\ModuleManager::getInstalledModules();
        foreach ($modules as $moduleId => $_) {
            $config = \Bitrix\Main\Config\Configuration::getInstance($moduleId)->get('console');
            if (
                isset($config['commands']) && is_array($config['commands'])
                && \Bitrix\Main\Loader::includeModule($moduleId)
            ) {
                foreach ($config['commands'] as $commandClass) {
                    if (is_a($commandClass, \Symfony\Component\Console\Command\Command::class, true)) {
                        $command = new $commandClass();
                        $result[] = [
                            'name' => $command->getName(),
                            'description' => $command->getDescription(),
                        ];
                    }
                }
            }
        }

        return $result;
    }
}
