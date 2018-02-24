<?php

/**
 * @package Web Hook
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License 3.0
 */

namespace gplcart\modules\webhook\controllers;

use gplcart\core\controllers\backend\Controller;

/**
 * Handles incoming requests and outputs data related to Web Hook module
 */
class Settings extends Controller
{

    /**
     * Route page callback to display the module settings page
     */
    public function editSettings()
    {
        $this->setTitleEditSettings();
        $this->setBreadcrumbEditSettings();

        $this->setData('hooks', $this->getHookNames());
        $this->setData('settings', $this->module->getSettings('webhook'));

        $this->submitSettings();
        $this->outputEditSettings();
    }

    /**
     * Set title on the module settings page
     */
    protected function setTitleEditSettings()
    {
        $title = $this->text('Edit %name settings', array('%name' => $this->text('Web Hook')));
        $this->setTitle($title);
    }

    /**
     * Set breadcrumbs on the module settings page
     */
    protected function setBreadcrumbEditSettings()
    {
        $breadcrumbs = array();

        $breadcrumbs[] = array(
            'text' => $this->text('Dashboard'),
            'url' => $this->url('admin')
        );

        $breadcrumbs[] = array(
            'text' => $this->text('Modules'),
            'url' => $this->url('admin/module/list')
        );

        $this->setBreadcrumbs($breadcrumbs);
    }

    /**
     * Saves the submitted settings
     */
    protected function submitSettings()
    {
        if ($this->isPosted('save') && $this->validateSettings()) {
            $this->updateSettings();
        }
    }

    /**
     * Update module settings
     */
    protected function updateSettings()
    {
        $this->controlAccess('module_edit');
        $this->module->setSettings('webhook', $this->getSubmitted());
        $this->redirect('', $this->text('Settings have been updated'), 'success');
    }

    /**
     * Validate submitted module settings
     */
    protected function validateSettings()
    {
        $this->setSubmitted('settings');

        if ($this->getSubmitted('sender', '') === '') {
            $this->setError('sender', $this->text('Sender is required'));
        }

        if ($this->getSubmitted('key', '') !== '' && $this->getSubmitted('salt', '') === '') {
            $this->setError('salt', $this->text('Salt is required'));
        }

        if (!$this->url->isAbsolute($this->getSubmitted('url'))) {
            $this->setError('url', $this->text('URL does not look valid'));
        }

        return !$this->hasErrors();
    }

    /**
     * Render and output the module settings page
     */
    protected function outputEditSettings()
    {
        $this->output('webhook|settings');
    }

    /**
     * Returns an array of hook names
     * @return array
     */
    protected function getHookNames()
    {
        $class = $this->module->getClass('webhook');
        $excluded = array('hookRouteList');

        $list = array();

        foreach ($this->module->getHooks($class) as $hook) {
            if (!in_array($hook, $excluded)) {
                // Split capitalized parts
                $parts = preg_split('/(?=[A-Z])/', $hook);
                array_shift($parts); // Skip "hook" prefix
                $list[$hook] = implode(' ', $parts);
            }
        }

        return gplcart_array_split($list, 4);
    }

}
