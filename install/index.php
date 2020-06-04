<?
/**
 * Copyright (c) 4/6/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class google_recaptcha extends CModule
{
	var $MODULE_ID = "google.recaptcha";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function google_recaptcha()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("CAPTCHA_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("CAPTCHA_INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = GetMessage("SPER_PARTNER");
		$this->PARTNER_URI = GetMessage("PARTNER_URI");
	}

  function InstallDB()
    {
        RegisterModule("google.recaptcha");
        return true;
    }

    function UnInstallDB()
    {	
		COption::RemoveOption("google.recaptcha");
        UnRegisterModule("google.recaptcha");
        return true;
    }

    function InstallEvents()
    {
		RegisterModuleDependences("main", "OnPageStart", "google.recaptcha", "ReCaptchaTwoGoogle", "OnVerificContent");
		RegisterModuleDependences("main", "OnEndBufferContent", "google.recaptcha", "ReCaptchaTwoGoogle", "OnAddContentCaptcha");
        return true;
    }

    function UnInstallEvents()
    {	
		UnRegisterModuleDependences("main", "OnPageStart", "google.recaptcha", "ReCaptchaTwoGoogle", "OnVerificContent");
		UnRegisterModuleDependences("main", "OnEndBufferContent", "google.recaptcha", "ReCaptchaTwoGoogle", "OnAddContentCaptcha");
        return true;
    }

    function InstallFiles()
    {
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/google.recaptcha/install/js", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js", true, true);
        return true;
    }

    function UnInstallFiles()
    {
		DeleteDirFilesEx("/bitrix/js/google.recaptcha/");
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;

        if (!IsModuleInstalled("google.recaptcha"))
        {
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
        }
    }

    function DoUninstall()
    {
        $this->UnInstallDB();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
    }
}
?>