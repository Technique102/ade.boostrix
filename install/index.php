<?php

declare(strict_types=1);

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (class_exists('technique102.aitools'))
{
    return;
}

class technique102_aitools extends \CModule
{
    public $MODULE_ID = 'technique102.aitools';

    public $MODULE_VERSION;

    public $MODULE_VERSION_DATE;

    public $MODULE_NAME;

    public $MODULE_DESCRIPTION;

    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . '/version.php');

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }

        $this->MODULE_NAME = Loc::getMessage('TECHNIQUE102_AITOOLS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('TECHNIQUE102_AITOOLS_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('TECHNIQUE102_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('TECHNIQUE102_PARTNER_URL');
    }

    public function doInstall(): void
    {
        if (!IsModuleInstalled($this->MODULE_ID)) {
            RegisterModule($this->MODULE_ID);
        }
    }

    public function doUninstall(): void
    {
        Option::delete($this->MODULE_ID);
        UnRegisterModule($this->MODULE_ID);
    }

    public function installFiles(): bool
    {
        return true;
    }

    public function uninstallFiles(): bool
    {
        return true;
    }
}
