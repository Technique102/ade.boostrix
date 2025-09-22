<?php

declare(strict_types=1);

namespace Ade\Boostrix\Tools;

use Mcp\Capability\Attribute\McpTool;

final readonly class Routes
{
    #[McpTool(
        name: 'get_routes_list',
        description: 'Возвращает информацию о списке роутов.',
    )]
    public function list(): array
    {
        $application = \Bitrix\Main\Application::getInstance();

        $result = [];
        foreach ($application->getRouter()->getRoutes() as $route) {
            $result[] = [
                'uri' => $route->getUri(),
                'controller' => !is_callable($route->getController()) ? $route->getController()[0].'::'.$route->getController()[1] : '',
                'methods' => $route->getOptions()->getMethods(),
            ];
        }

        return $result;
    }
}
