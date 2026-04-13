## Введение
Модуль предоставляет MCP-сервер с набором специализированных инструментов для упрощения AI-разработки, а так же необходимый контекст и структуру для генерации качественного кода под экосистему 1С-Битрикс.

## Требования
- PHP >=8.2
- 1С-Битрикс >= 24.0.0

## Установка
1. Скопируйте папку модуля `technique102.aitools` в директорию `/local/modules/` вашего проекта 1С-Битрикс.
2. В административной панели 1С-Битрикс перейдите в раздел "Marketplace" → "Установленные решения".
3. Найдите модуль "technique102.aitools" и выполните установку, следуя инструкциям мастера.
4. После установки убедитесь, что модуль активен.

## Регистрация MCP-сервера
Для регистрации сервера в выбранном вами редакторе используйте следующие данные:

Через docker:
```json
{
    "mcpServers": {
        "Technique102AiTools": {
            "command": "docker",
            "args": [
              "exec", "-i",
              "#Имя контейнера#",
              "php", "#Путь до корня проекта#/local/technique102.aitools/tools/server.php"
            ]
        }
    }
}
```

Без docker, php работает локально:
```json
{
    "mcpServers": {
        "Technique102AiTools": {
            "command": "php",
            "args": ["#Путь в системе до корня проекта#/local/technique102.aitools/tools/server.php"]
        }
    }
}
```

## Доступные MCP-инструменты
| Название               | Описание                             |
|------------------------|--------------------------------------|
| `get_application_info` | Версия PHP и 1С-Битрикс              |
| `get_route_list`       | Список роутов проекта                |
| `get_command_list`     | Список консольных команд             |
| **Инфоблоки**          |                                      |
| `get_iblock_list`      | Список инфоблоков                    |
| `get_iblock_detail`    | Детали инфоблока со всеми свойствами |
| `get_iblock_type_list` | Список типов инфоблоков              |
| **HL-блоки**           |                                      |
| `get_hlblock_list`     | Список HL-блоков                     |
| `get_hlblock_detail`   | Детали HL-блока с полями             |
| **Агенты**             |                                      |
| `get_agent_list`       | Список агентов (cron-задач)          |

## Добавление собственных MCP-инструментов

Любой установленный модуль может расширять MCP-сервер своими инструментами двумя способами.

### Через конфиг модуля

В файле `.settings.php` вашего модуля добавьте секцию `technique102.aitools`:

```php
// local/modules/my.module/.settings.php
return [
    'technique102.aitools' => [
        'value' => [
            'mcp' => [
                'tools' => [
                    // Простой вариант — FQCN класса с методами #[McpTool]
                    \My\Module\Tools\MyTool::class,

                    // Расширенный вариант — с явными метаданными
                    [
                        'handler'     => \My\Module\Tools\AnotherTool::class,
                        'name'        => 'custom_tool_name',
                        'description' => 'Описание инструмента',
                    ],
                ],
            ],
        ],
    ],
];
```

### Через событие

**Шаг 1.** Создайте класс обработчика событий:

```php
// local/modules/my.module/lib/EventHandlers.php
namespace My\Module;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

class EventHandlers
{
    public static function onBuildMcpServerToolsList(Event $event): EventResult
    {
        return new EventResult(
            EventResult::SUCCESS,
            \My\Module\Tools\MyTool::class,
        );

        // Или с явными метаданными:
        // return new EventResult(
        //     EventResult::SUCCESS,
        //     [
        //         'handler'     => \My\Module\Tools\MyTool::class,
        //         'name'        => 'custom_tool_name',
        //         'description' => 'Описание инструмента',
        //     ],
        // );
    }
}
```

**Шаг 2.** Зарегистрируйте обработчик в `install/index.php` вашего модуля:

```php
// local/modules/my.module/install/index.php
function DoInstall()
{
    RegisterModuleDependences(
        'technique102.aitools',
        'OnBuildMcpServerToolsList',
        'my.module',
        \My\Module\EventHandlers::class,
        'onBuildMcpServerToolsList',
    );
    // ...
}

function DoUninstall()
{
    UnRegisterModuleDependences(
        'technique102.aitools',
        'OnBuildMcpServerToolsList',
        'my.module',
        \My\Module\EventHandlers::class,
        'onBuildMcpServerToolsList',
    );
    // ...
}
```

Зарегистрированные через `RegisterModuleDependences` обработчики хранятся в базе данных и срабатывают автоматически при загрузке модуля.

### Создание класса инструмента

Инструмент — это обычный PHP-класс, методы которого помечены атрибутом `#[McpTool]`:

```php
namespace My\Module\Tools;

use Mcp\Capability\Attribute\McpTool;

final readonly class MyTool
{
    #[McpTool(
        name: 'my_tool_action',
        description: 'Описание того, что делает инструмент.',
    )]
    public function action(string $param): array
    {
        return ['result' => $param];
    }
}
```

## План развития модуля
- Добавление MCP-инструментов для модулей из коробки 1С-Битрикс
- Добавление гайдлайнов для работы с 1С-Битрикс и сопутствующих инструментов/технологий для AI-агентов
- Добавление скилов и субагентов
- Добавление демонстрации работы модуля
- ...
