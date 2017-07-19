[![Build Status](https://scrutinizer-ci.com/g/gplcart/webhook/badges/build.png?b=master)](https://scrutinizer-ci.com/g/gplcart/webhook/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gplcart/webhook/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gplcart/webhook/?branch=master)

Web Hook is a [GPL Cart](https://github.com/gplcart/gplcart) module that allows to inform external resources about various system events by sending HTTP POST payloads

Features:

- Configurable triggering hooks
- Configurable payload URL
- Data encryption

Requirements:

- CURL

**Installation**

1. Download and extract to `system/modules` manually or using composer `composer require gplcart/webhook`. IMPORTANT: If you downloaded the module manually, be sure that the name of extracted module folder doesn't contain a branch/version suffix, e.g `-master`. Rename if needed.
2. Go to `admin/module/list` end enable the module
3. Go to `admin/module/settings/webhook` and adjust settings

**Receiving payloads**

Example settings:

- Url: http://domain.com/webhook.php
- Sender: My cool site
- Key: My secret key
- Salt: My salt

In `webhook.php` paste the following code

    if(isset($_POST['sender']) && $_POST['sender'] === 'My cool site'){
    	
		$json = $_POST['data'];
    	
		if($_POST['encrypted']){
    		$secret = hash("sha256", 'My secret key');
    		$hash = substr(hash("sha256", 'My salt'), 0, 16);
    		$json = openssl_decrypt($json, "AES-256-CBC", $secret, 0, $hash);
    	}

    	$payload = json_decode($json, true);
		print_r($payload);
    }