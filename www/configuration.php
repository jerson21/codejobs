<?php 
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

date_default_timezone_set(DEFAULT_TIMEZONE);

$Configuration_Model = $Load->model("Configuration_Model");
$data = $Configuration_Model->getConfig();

if (is_array($data)) {
	set("webLanguage", $data[0]["Language"]);	

	if (whichLanguage() === _get("webLanguage")) { 
		set("webLang", $data[0]["Lang"]);
		set("webSlogan", $data[0]["Slogan_". _get("webLanguage")]);
	} else {
		set("webLang", getLang(whichLanguage(), false));
		set("webSlogan", $data[0]["Slogan_". whichLanguage()]);
	}

	if ($data[0]["Situation"] === "Inactive" and segment(0, isLang()) !== "cpanel" and segment(1, isLang()) !== "cpanel") {
		die($data[0]["Message"]);
	}
	
	set("webName", $data[0]["Name"]);
	set("webURL", $data[0]["URL"]);
	set("webTheme", $data[0]["Theme"]);
	set("webValidation", $data[0]["Validation"]);
	set("webActivation", $data[0]["Activation"]);
	set("webEmailRecieve", $data[0]["Email_Recieve"]);
	set("webEmailSend", $data[0]["Email_Send"]);
	set("defaultApplication", $data[0]["Application"]);
	set("defaultEditor", $data[0]["Editor"]);

	if (!_get("modRewrite")) {
		set("webBase", _get("webURL") . SH . INDEX);
	} else {
		set("webBase", _get("webURL"));
	}
}


if (_get("translation") === "gettext") {
	$languageFile = DIR ."/lib/languages/gettext/". whichLanguage(true, true) .".mo";
	
	if (file_exists($languageFile)) { 			
		$Load->library("streams", null, null, "gettext");
		$Gettext_Reader = $Load->library("gettext", "Gettext_Reader", array($languageFile), "gettext");
		$Gettext_Reader->load_tables();
	}
}