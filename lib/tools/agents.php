<?php

declare(strict_types=1);

namespace Technique102\AiTools\Tools;

use Mcp\Capability\Attribute\McpTool;

final readonly class Agents
{
    #[McpTool(
        name: 'get_agent_list',
        description: 'Возвращает список зарегистрированных агентов 1С-Битрикс.',
    )]
    public function list(): array
    {
        $result = [];

        $res = \CAgent::GetList(
            ['MODULE_ID' => 'ASC', 'NAME' => 'ASC'],
            [],
        );

        while ($agent = $res->Fetch()) {
            $result[] = [
                'id' => (int)$agent['ID'],
                'name' => $agent['NAME'],
                'module_id' => $agent['MODULE_ID'],
                'period' => (int)$agent['AGENT_INTERVAL'],
                'next_exec' => $agent['NEXT_EXEC'],
                'active' => $agent['ACTIVE'] === 'Y',
            ];
        }

        return $result;
    }
}
