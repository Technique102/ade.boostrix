<?php

declare(strict_types=1);

namespace Technique102\AiTools\Tools;

use Bitrix\Main\Loader;
use Mcp\Capability\Attribute\McpTool;

final readonly class IBlock
{
    #[McpTool(
        name: 'get_iblock_list',
        description: 'Возвращает список инфоблоков с ID, кодом, названием, типом, сайтом и статусом активности.',
    )]
    public function list(): array
    {
        Loader::includeModule('iblock');

        $result = [];

        $res = \CIBlock::GetList(
            ['SORT' => 'ASC'],
            ['CHECK_PERMISSIONS' => 'N'],
        );

        while ($iblock = $res->Fetch()) {
            $result[] = [
                'id' => (int)$iblock['ID'],
                'code' => $iblock['CODE'],
                'name' => $iblock['NAME'],
                'type' => $iblock['IBLOCK_TYPE_ID'],
                'site_id' => $iblock['LID'],
                'active' => $iblock['ACTIVE'] === 'Y',
            ];
        }

        return $result;
    }

    #[McpTool(
        name: 'get_iblock_detail',
        description: 'Возвращает детальную информацию об инфоблоке по ID, коду или названию, включая список всех свойств.',
    )]
    public function detail(string $iblockQuery): array
    {
        Loader::includeModule('iblock');

        $id = 0;

        if (is_numeric($iblockQuery)) {
            $id = (int)$iblockQuery;
        } else {
            $res = \CIBlock::GetList(
                [],
                [
                    'CHECK_PERMISSIONS' => 'N',
                    'CODE' => $iblockQuery,
                ],
            );
            if ($iblock = $res->Fetch()) {
                $id = (int)$iblock['ID'];
            }

            if ($id <= 0) {
                $res = \CIBlock::GetList(
                    [],
                    [
                        'CHECK_PERMISSIONS' => 'N',
                        'NAME' => $iblockQuery,
                    ],
                );
                if ($iblock = $res->Fetch()) {
                    $id = (int)$iblock['ID'];
                }
            }
        }

        if ($id <= 0) {
            return [];
        }

        $res = \CIBlock::GetList(
            [],
            [
                'CHECK_PERMISSIONS' => 'N',
                'ID' => $id,
            ],
        );

        $iblock = $res->Fetch();
        if (!$iblock) {
            return [];
        }

        $result = [
            'id' => (int)$iblock['ID'],
            'code' => $iblock['CODE'],
            'name' => $iblock['NAME'],
            'type' => $iblock['IBLOCK_TYPE_ID'],
            'site_id' => $iblock['LID'],
            'active' => $iblock['ACTIVE'] === 'Y',
            'properties' => [],
        ];

        $resProps = \CIBlockProperty::GetList(
            ['SORT' => 'ASC', 'NAME' => 'ASC'],
            ['IBLOCK_ID' => $id, 'CHECK_PERMISSIONS' => 'N'],
        );

        while ($prop = $resProps->Fetch()) {
            $result['properties'][] = [
                'id' => (int)$prop['ID'],
                'code' => $prop['CODE'],
                'name' => $prop['NAME'],
                'property_type' => $prop['PROPERTY_TYPE'],
                'user_type' => $prop['USER_TYPE'],
                'multiple' => $prop['MULTIPLE'] === 'Y',
                'required' => $prop['IS_REQUIRED'] === 'Y',
            ];
        }

        return $result;
    }
}
