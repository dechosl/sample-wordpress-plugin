<?php defined('ABSPATH') or die('No script kiddies please!'); ?>

<div>
    <div class="heading-text">
        An example with Wordpress options
    </div>
    <div>
        <div>
            <label for="nt-setting-1"><h4>Setting 1</h4></label>
            <input type="text" name="nt-setting-1" id="nt-setting-1" value="<?php echo esc_attr($options['nt-setting-1']);?>" />
        </div>
        <div>
            <label for="nt-setting-2"><h4>Setting 2</h4></label>
            <input type="text" name="nt-setting-2" id="nt-setting-2" value="<?php echo esc_attr($options['nt-setting-2']);?>" />
        </div>
    </div>
</div>
<br />