<h3>Hubspot Settings</h3>
<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
    <input type="hidden" name="action" value="cbf_save_hubspot_settings">
    <input type="hidden" name="redirect_to" value="<?= admin_url(sprintf(basename($_SERVER['REQUEST_URI']))); ?>">
    <label for="cbf_hubspot_key">Hubspot Key:</label>
    <input value="<?= $key ?>" id="cbf_hubspot_key" type="text" name="cbf_hubspot_key" required>
    <br><br><br>
    <?php if(!empty($key)){ ?>
        <label for="cbf_hubspot_premium_list">Select Premium List</label>
        <select name="cbf_hubspot_premium_list" id="cbf_hubspot_premium_list">
            <option  value="-1">Choose a list</option>
            <?php foreach ($lists as $list){ ?>
                <option <?= $premium_list == $list['listId'] ? 'selected' : '' ?> value="<?= $list['listId'] ?>"><?= $list['name'] ?></option>
            <?php } ?>
        </select>
        <p>Select a list where the user with a free membership will be added</p>
        <label for="cbf_hubspot_free_list">Select Free List</label>
        <select name="cbf_hubspot_free_list" id="cbf_free_premium_list">
            <option value="-1">Choose a list</option>
		    <?php foreach ($lists as $list){ ?>
                <option <?= $free_list == $list['listId'] ? 'selected' : '' ?> value="<?= $list['listId'] ?>"><?= $list['name'] ?></option>
		    <?php } ?>
        </select>
        <p>Select a list where the user with a free membership will be added</p>
    <?php } ?>
    <button type="submit" name="name" class="btn btn-primary">Save Settings</button>
</form>