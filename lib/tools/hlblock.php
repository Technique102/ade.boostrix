<?php

declare(strict_types=1);

namespace Technique102\AiTools\Tools;

use Bitrix\Highloadblock\HighloadBlockLangTable;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Mcp\Capability\Attribute\McpTool;

final class HlBlock
{
    #[McpTool(
        name: 'get_hlblock_list',
        description: 'Возвращает список HL-блоков.',
    )]
    public function list(): array
    {
        Loader::includeModule('highloadblock');

        $result = [];

        $res = HighloadBlockTable::getList();
        while ($hlBlock = $res->fetch()) {
            $langNames = $this->getLangNames((int)$hlBlock['ID']);

            $result[] = [
                'id' => $hlBlock['ID'],
                'name' => $hlBlock['NAME'],
                'table_name' => $hlBlock['TABLE_NAME'],
                'lang' => $langNames,
            ];
        }

        return $result;
    }

    #[McpTool(
        name: 'get_hlblock_detail',
        description: 'Возвращает детальную информацию о хл-блоке по ID, коду, названию таблицы.',
    )]
    public function detail(string $hlblockQuery): array
    {
        Loader::includeModule('highloadblock');

        $result = [];
        $id = 0;

        $highLoadBlockCursor = HighloadBlockTable::getList([
            'filter' => [
                'LOGIC' => 'OR',
                ['TABLE_NAME' => $hlblockQuery],
                ['NAME' => $hlblockQuery],
                ['ID' => $hlblockQuery],
            ],
            'select' => [
                'ID',
            ],
            'limit' => 1,
            'cache' => [
                'ttl' => 3600,
            ],
        ]);
        if ($ob = $highLoadBlockCursor->fetch()) {
            $id = (int)$ob['ID'];
        }

        if ($id <= 0) {
            return $result;
        }

        $aHlBlock = HighloadBlockTable::getById($id)->fetch();

        $obEntity = HighloadBlockTable::compileEntity($aHlBlock);

        $langNames = $this->getLangNames($id);

        $result = [
            'id' => $aHlBlock['ID'],
            'name' => $aHlBlock['NAME'],
            'table_name' => $aHlBlock['TABLE_NAME'],
            'lang' => $langNames,
            'fields' => [],
        ];

        $userTypeManager = new \CUserTypeManager();
        $ufFields = $userTypeManager->GetUserFields('HLBLOCK_' . $id, 0, LANGUAGE_ID);

        $rsFields = $obEntity->getFields();
        foreach ($rsFields as $field) {
            $fieldName = $field->getName();
            $label = $ufFields[$fieldName]['EDIT_FORM_LABEL']
                ?? $ufFields[$fieldName]['LIST_COLUMN_LABEL']
                ?? '';

            $result['fields'][] = [
                'name' => $fieldName,
                'code' => $field->getTitle(),
                'type' => $field->getDataType(),
                'label' => $label,
            ];
        }

        return $result;
    }

    private function getLangNames(int $id): array
    {
        $langNames = [];

        $langRes = HighloadBlockLangTable::getList([
            'filter' => ['=ID' => $id],
            'select' => ['LID', 'NAME'],
        ]);

        while ($lang = $langRes->fetch()) {
            $langNames[$lang['LID']] = $lang['NAME'];
        }

        return $langNames;
    }
}
