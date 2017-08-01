<?php

/**
 * @package Web Hook
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License 3.0
 */

namespace gplcart\modules\webhook;

use gplcart\core\Module;

/**
 * Main class for Web Hook module
 */
class WebHook extends Module
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Send via HTTP POST request
     * @param string $hook
     * @param array $arguments
     */
    protected function sendPayload($hook, array $arguments)
    {
        $settings = $this->config->module('webhook');

        if (!in_array($hook, $settings['hooks'])//
                || empty($settings['url'])//
                || empty($settings['sender'])) {
            return false;
        }

        $payload = $this->preparePayload($hook, $arguments, $settings);

        /* @var $curl \gplcart\core\helpers\Curl */
        $curl = $this->getHelper('Curl');

        try {
            $response = $curl->post($settings['url'], array('fields' => $payload));
        } catch (\Exception $ex) {
            return false;
        }

        return $response;
    }

    /**
     * Prepare payload data before sending via HTTP POST
     * @param string $hook
     * @param array $arguments
     * @param array $settings
     */
    protected function preparePayload($hook, array $arguments, array $settings)
    {
        $payload = array(
            'encrypted' => false,
            'sender' => $settings['sender'],
            'data' => gplcart_json_encode(array('hook' => $hook, 'arguments' => $arguments))
        );

        if ($settings['key'] !== '' && $settings['salt'] !== '') {
            $payload['encrypted'] = true;
            $key = hash('sha256', $settings['key']);
            $salt = substr(hash('sha256', $settings['salt']), 0, 16);
            $payload['data'] = openssl_encrypt($payload['data'], 'AES-256-CBC', $key, 0, $salt);
        }

        return $payload;
    }

    /**
     * Implements hook "module.install.before"
     * @param string|bool $result
     */
    public function hookModuleInstallBefore(&$result)
    {
        if (!function_exists('curl_init')) {
            $result = 'CURL library is not enabled';
        }

        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "route.list"
     * @param array $routes
     */
    public function hookRouteList(&$routes)
    {
        $routes['admin/module/settings/webhook'] = array(
            'access' => 'module_edit',
            'handlers' => array(
                'controller' => array('gplcart\\modules\\webhook\\controllers\\Settings', 'editSettings')
            )
        );
    }

    /**
     * Implements hook "address.add.before"
     */
    public function hookAddressAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "address.add.after"
     */
    public function hookAddressAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "address.delete.before"
     */
    public function hookAddressDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "address.delete.after"
     */
    public function hookAddressDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "address.update.before"
     */
    public function hookAddressUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "address.update.after"
     */
    public function hookAddressUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "backup.add.before"
     */
    public function hookBackupAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "backup.add.after"
     */
    public function hookBackupAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "backup.delete.before"
     */
    public function hookBackupDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "backup.delete.after"
     */
    public function hookBackupDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.add.product.before"
     */
    public function hookCartAddProductBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.add.product.after"
     */
    public function hookCartAddProductAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.add.before"
     */
    public function hookCartAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.add.after"
     */
    public function hookCartAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.update.before"
     */
    public function hookCartUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.update.after"
     */
    public function hookCartUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.move.wishlist.before"
     */
    public function hookCartMoveWishlistBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.move.wishlist.after"
     */
    public function hookCartMoveWishlistAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.login.before"
     */
    public function hookCartLoginBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.login.after"
     */
    public function hookCartLoginAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.delete.before"
     */
    public function hookCartDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "cart.delete.after"
     */
    public function hookCartDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.add.before"
     */
    public function hookCategoryAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.add.after"
     */
    public function hookCategoryAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.update.before"
     */
    public function hookCategoryUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.update.after"
     */
    public function hookCategoryUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.delete.before"
     */
    public function hookCategoryDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.delete.after"
     */
    public function hookCategoryDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.group.add.before"
     */
    public function hookCategoryGroupAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.group.add.after"
     */
    public function hookCategoryGroupAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.group.delete.before"
     */
    public function hookCategoryGroupDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.group.delete.after"
     */
    public function hookCategoryGroupDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.group.update.before"
     */
    public function hookCategoryGroupUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "category.group.update.after"
     */
    public function hookCategoryGroupUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "city.add.before"
     */
    public function hookCityAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "city.add.after"
     */
    public function hookCityAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "city.delete.before"
     */
    public function hookCityDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "city.delete.after"
     */
    public function hookCityDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "city.update.before"
     */
    public function hookCityUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "city.update.after"
     */
    public function hookCityUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.add.before"
     */
    public function hookCollectionAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.add.after"
     */
    public function hookCollectionAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.delete.before"
     */
    public function hookCollectionDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.delete.after"
     */
    public function hookCollectionDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.update.before"
     */
    public function hookCollectionUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.update.after"
     */
    public function hookCollectionUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.item.add.before"
     */
    public function hookCollectionItemAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.item.add.after"
     */
    public function hookCollectionItemAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.item.delete.before"
     */
    public function hookCollectionItemDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.item.delete.after"
     */
    public function hookCollectionItemDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.item.update.before"
     */
    public function hookCollectionItemUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "collection.item.update.after"
     */
    public function hookCollectionItemUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "compare.add.before"
     */
    public function hookCompareAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "compare.add.after"
     */
    public function hookCompareAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "compare.add.product.before"
     */
    public function hookCompareAddProductBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "compare.add.product.after"
     */
    public function hookCompareAddProductAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "compare.delete.product.before"
     */
    public function hookCompareDeleteProductBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "compare.delete.product.after"
     */
    public function hookCompareDeleteProductAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "compare.delete.before"
     */
    public function hookCompareDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "compare.delete.after"
     */
    public function hookCompareDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "condition.met.before"
     */
    public function hookConditionMetBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "condition.met.after"
     */
    public function hookConditionMetAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "country.add.before"
     */
    public function hookCountryAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "country.add.after"
     */
    public function hookCountryAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "country.update.before"
     */
    public function hookCountryUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "country.update.after"
     */
    public function hookCountryUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "country.delete.before"
     */
    public function hookCountryDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "country.delete.after"
     */
    public function hookCountryDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "currency.update.before"
     */
    public function hookCurrencyUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "currency.update.after"
     */
    public function hookCurrencyUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "currency.delete.before"
     */
    public function hookCurrencyDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "currency.delete.after"
     */
    public function hookCurrencyDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "editor.save.before"
     */
    public function hookEditorSaveBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "editor.save.after"
     */
    public function hookEditorSaveAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.add.before"
     */
    public function hookFieldAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.add.after"
     */
    public function hookFieldAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.delete.before"
     */
    public function hookFieldDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.delete.after"
     */
    public function hookFieldDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.update.before"
     */
    public function hookFieldUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.update.after"
     */
    public function hookFieldUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.value.add.before"
     */
    public function hookFieldValueAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.value.add.after"
     */
    public function hookFieldValueAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.value.update.before"
     */
    public function hookFieldValueUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.value.update.after"
     */
    public function hookFieldValueUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.value.delete.before"
     */
    public function hookFieldValueDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "field.value.delete.after"
     */
    public function hookFieldValueDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "file.add.before"
     */
    public function hookFileAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "file.add.after"
     */
    public function hookFileAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "file.update.before"
     */
    public function hookFileUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "file.update.after"
     */
    public function hookFileUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "file.delete.before"
     */
    public function hookFileDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "file.delete.after"
     */
    public function hookFileDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "file.upload.before"
     */
    public function hookFileUploadBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "file.upload.after"
     */
    public function hookFileUploadAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "file.download.before"
     */
    public function hookFileDownloadBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "file.download.after"
     */
    public function hookFileDownloadAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "filter.update.before"
     */
    public function hookFilterUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "filter.update.after"
     */
    public function hookFilterUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "imagestyle.add.before"
     */
    public function hookImagestyleAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "imagestyle.add.after"
     */
    public function hookImagestyleAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "imagestyle.update.before"
     */
    public function hookImagestyleUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "imagestyle.update.after"
     */
    public function hookImagestyleUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "imagestyle.delete.before"
     */
    public function hookImagestyleDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "imagestyle.delete.after"
     */
    public function hookImagestyleDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "imagestyle.clear.cache.before"
     */
    public function hookImagestyleClearCacheBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "imagestyle.clear.cache.after"
     */
    public function hookImagestyleClearCacheAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "language.add.before"
     */
    public function hookLanguageAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "language.add.after"
     */
    public function hookLanguageAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "language.update.before"
     */
    public function hookLanguageUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "language.update.after"
     */
    public function hookLanguageUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "language.delete.before"
     */
    public function hookLanguageDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "language.delete.after"
     */
    public function hookLanguageDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "mail.send.before"
     */
    public function hookMailSendBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "mail.send.after"
     */
    public function hookMailSendAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "module.enable.before"
     */
    public function hookModuleEnableBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "module.enable.after"
     */
    public function hookModuleEnableAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "module.disable.before"
     */
    public function hookModuleDisableBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "module.disable.after"
     */
    public function hookModuleDisableAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "module.install.after"
     */
    public function hookModuleInstallAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "module.uninstall.before"
     */
    public function hookModuleUninstallBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "module.uninstall.after"
     */
    public function hookModuleUninstallAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "order.update.before"
     */
    public function hookOrderUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "order.update.after"
     */
    public function hookOrderUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "order.delete.before"
     */
    public function hookOrderDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "order.delete.after"
     */
    public function hookOrderDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "order.submit.before"
     */
    public function hookOrderSubmitBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "order.submit.after"
     */
    public function hookOrderSubmitAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "order.add.before"
     */
    public function hookOrderAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "order.add.after"
     */
    public function hookOrderAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "page.add.before"
     */
    public function hookPageAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "page.add.after"
     */
    public function hookPageAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "page.update.before"
     */
    public function hookPageUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "page.update.after"
     */
    public function hookPageUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "page.delete.before"
     */
    public function hookPageDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "page.delete.after"
     */
    public function hookPageDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "price.rule.add.before"
     */
    public function hookPriceRuleAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "price.rule.add.after"
     */
    public function hookPriceRuleAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "price.rule.update.before"
     */
    public function hookPriceRuleUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "price.rule.update.after"
     */
    public function hookPriceRuleUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "price.rule.delete.before"
     */
    public function hookPriceRuleDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "price.rule.delete.after"
     */
    public function hookPriceRuleDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.add.before"
     */
    public function hookProductAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.add.after"
     */
    public function hookProductAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.update.before"
     */
    public function hookProductUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.update.after"
     */
    public function hookProductUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.delete.before"
     */
    public function hookProductDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.delete.after"
     */
    public function hookProductDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.class.add.before"
     */
    public function hookProductClassAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.class.add.after"
     */
    public function hookProductClassAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.class.delete.before"
     */
    public function hookProductClassDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.class.delete.after"
     */
    public function hookProductClassDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.class.update.before"
     */
    public function hookProductClassUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.class.update.after"
     */
    public function hookProductClassUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.class.add.field.before"
     */
    public function hookProductClassAddFieldBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.class.add.field.after"
     */
    public function hookProductClassAddFieldAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.class.delete.field.before"
     */
    public function hookProductClassDeleteFieldBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.class.delete.field.after"
     */
    public function hookProductClassDeleteFieldAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.field.add.before"
     */
    public function hookProductFieldAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.field.add.after"
     */
    public function hookProductFieldAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.field.delete.before"
     */
    public function hookProductFieldDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "product.field.delete.after"
     */
    public function hookProductFieldDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "rating.set.before"
     */
    public function hookRatingSetBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "rating.set.after"
     */
    public function hookRatingSetAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "rating.add.user.before"
     */
    public function hookRatingAddUserBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "rating.add.user.after"
     */
    public function hookRatingAddUserAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "review.add.before"
     */
    public function hookReviewAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "review.add.after"
     */
    public function hookReviewAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "review.get.before"
     */
    public function hookReviewGetBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "review.get.after"
     */
    public function hookReviewGetAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "review.update.before"
     */
    public function hookReviewUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "review.update.after"
     */
    public function hookReviewUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "review.delete.before"
     */
    public function hookReviewDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "review.delete.after"
     */
    public function hookReviewDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "sku.add.before"
     */
    public function hookSkuAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "sku.add.after"
     */
    public function hookSkuAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "sku.delete.before"
     */
    public function hookSkuDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "sku.delete.after"
     */
    public function hookSkuDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "state.add.before"
     */
    public function hookStateAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "state.add.after"
     */
    public function hookStateAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "state.delete.before"
     */
    public function hookStateDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "state.delete.after"
     */
    public function hookStateDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "state.update.before"
     */
    public function hookStateUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "state.update.after"
     */
    public function hookStateUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "store.add.before"
     */
    public function hookStoreAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "store.add.after"
     */
    public function hookStoreAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "store.update.before"
     */
    public function hookStoreUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "store.update.after"
     */
    public function hookStoreUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "store.delete.before"
     */
    public function hookStoreDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "store.delete.after"
     */
    public function hookStoreDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "transaction.add.before"
     */
    public function hookTransactionAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "transaction.add.after"
     */
    public function hookTransactionAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "transaction.delete.before"
     */
    public function hookTransactionDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "transaction.delete.after"
     */
    public function hookTransactionDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "trigger.add.before"
     */
    public function hookTriggerAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "trigger.add.after"
     */
    public function hookTriggerAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "trigger.delete.before"
     */
    public function hookTriggerDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "trigger.delete.after"
     */
    public function hookTriggerDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "trigger.update.before"
     */
    public function hookTriggerUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "trigger.update.after"
     */
    public function hookTriggerUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.add.before"
     */
    public function hookUserAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.add.after"
     */
    public function hookUserAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.update.before"
     */
    public function hookUserUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.update.after"
     */
    public function hookUserUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.delete.before"
     */
    public function hookUserDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.delete.after"
     */
    public function hookUserDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.login.before"
     */
    public function hookUserLoginBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.login.after"
     */
    public function hookUserLoginAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.register.before"
     */
    public function hookUserRegisterBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.register.after"
     */
    public function hookUserRegisterAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.logout.before"
     */
    public function hookUserLogoutBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.logout.after"
     */
    public function hookUserLogoutAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.reset.password.before"
     */
    public function hookUserResetPasswordBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.reset.password.after"
     */
    public function hookUserResetPasswordAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.role.delete.before"
     */
    public function hookUserRoleDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.role.delete.after"
     */
    public function hookUserRoleDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.role.add.before"
     */
    public function hookUserRoleAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.role.add.after"
     */
    public function hookUserRoleAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.role.update.before"
     */
    public function hookUserRoleUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "user.role.update.after"
     */
    public function hookUserRoleUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "wishlist.add.before"
     */
    public function hookWishlistAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "wishlist.add.after"
     */
    public function hookWishlistAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "wishlist.add.product.before"
     */
    public function hookWishlistAddProductBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "wishlist.add.product.after"
     */
    public function hookWishlistAddProductAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "wishlist.delete.product.before"
     */
    public function hookWishlistDeleteProductBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "wishlist.delete.product.after"
     */
    public function hookWishlistDeleteProductAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "wishlist.delete.before"
     */
    public function hookWishlistDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "wishlist.delete.after"
     */
    public function hookWishlistDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "zone.add.before"
     */
    public function hookZoneAddBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "zone.add.after"
     */
    public function hookZoneAddAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "zone.update.before"
     */
    public function hookZoneUpdateBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "zone.update.after"
     */
    public function hookZoneUpdateAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "zone.delete.before"
     */
    public function hookZoneDeleteBefore()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

    /**
     * Implements hook "zone.delete.after"
     */
    public function hookZoneDeleteAfter()
    {
        $this->sendPayload(__FUNCTION__, func_get_args());
    }

}
