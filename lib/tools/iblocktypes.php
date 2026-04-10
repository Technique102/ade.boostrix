<?php

declare(strict_types=1);

namespace Technique102\AiTools\Tools;

use Bitrix\Main\Loader;
use Mcp\Capability\Attribute\McpTool;

final readonly class IBlockTypes
{
    #[McpTool(
        name: 'get_iblock_type_list',
        description: 'Возвращает список типов инфоблоков с их ID и названиями на всех языках.',
    )]
    public function list(): array
    {
        Loader::includeModule('iblock');

        $result = [];

        $res = \CIBlockType::GetList(['SORT' => 'ASC'], []);

        while ($type = $res->Fetch()) {
            $langNames = [];

            $langRes = \CIBlockType::GetByIDLang($type['ID']);
            while ($lang = $langRes->Fetch()) {
                $langNames[$lang['LID']] = $lang['NAME'];
            }

            $result[] = [
                'id' => $type['ID'],
                'sort' => (int)$type['SORT'],
                'active' => $type['ACTIVE'] === 'Y',
                'lang' => $langNames,
            ];
        }

        return $result;
    }
}
