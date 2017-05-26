<?php
/**
 * @package Web Hook
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License 3.0
 */
?>
<form method="post" class="form-horizontal">
  <input type="hidden" name="token" value="<?php echo $_token; ?>">
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="form-group required<?php echo $this->error('url', ' has-error'); ?>">
        <label class="col-md-2 control-label"><?php echo $this->text('Url'); ?></label>
        <div class="col-md-8">
          <input name="settings[url]" class="form-control" maxlength="255" value="<?php echo $this->e($settings['url']); ?>">
          <div class="help-block">
            <?php echo $this->error('url'); ?>
            <div class="text-muted">
              <?php echo $this->text('An absolute URL to receive POST payload'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group required<?php echo $this->error('sender', ' has-error'); ?>">
        <label class="col-md-2 control-label"><?php echo $this->text('Sender'); ?></label>
        <div class="col-md-8">
          <input name="settings[sender]" maxlength="255" class="form-control" value="<?php echo $this->e($settings['sender']); ?>">
          <div class="help-block">
            <?php echo $this->error('sender'); ?>
            <div class="text-muted">
              <?php echo $this->text('A unique ID to identify payload sender'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-md-2 control-label"><?php echo $this->text('Key'); ?></label>
        <div class="col-md-8">
          <input name="settings[key]" maxlength="255" class="form-control" value="<?php echo $this->e($settings['key']); ?>">
          <div class="help-block"><?php echo $this->text('An optional key to encrypt payload data. If not specified, plain JSON will be send'); ?></div>
        </div>
      </div>
      <div class="form-group<?php echo $this->error('salt', ' has-error'); ?>">
        <label class="col-md-2 control-label"><?php echo $this->text('Salt'); ?></label>
        <div class="col-md-8">
          <input name="settings[salt]" maxlength="255" class="form-control" value="<?php echo $this->e($settings['salt']); ?>">
          <div class="help-block">
            <?php echo $this->error('salt'); ?>
            <div class="text-muted">
              <?php echo $this->text('An additional hash to improve security. Required if "Key" is specified'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-body">
      <p><?php echo $this->text('Select hooks to trigger payloads. WARNING: Some hooks may expose sensitive data!'); ?></p>
      <div class="form-group">
        <?php foreach ($hooks as $chunks) { ?>
        <div class="col-md-3">
          <?php foreach ($chunks as $hook => $name) { ?>
          <div class="checkbox">
            <label>
              <input name="settings[hooks][]" value="<?php echo $this->e($hook); ?>" type="checkbox"<?php echo in_array($hook, $settings['hooks']) ? ' checked' : ''; ?>> <?php echo $this->e($name); ?>
            </label>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
      <div class="form-group">
        <div class="col-md-4">
          <div class="btn-toolbar">
            <a href="<?php echo $this->url("admin/module/list"); ?>" class="btn btn-default"><?php echo $this->text("Cancel"); ?></a>
            <button class="btn btn-default save" name="save" value="1"><?php echo $this->text("Save"); ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>