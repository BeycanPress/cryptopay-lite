<?php
    use BeycanPress\CryptoPayLite\Types\Enums\TransactionStatus as Status;
    $verified = Status::VERIFIED;
    $failed = Status::FAILED;
    $pending = Status::PENDING;
?>

<form>
    <?php 
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) { 
                ?> <input type="hidden" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>"/> <?php 
            }
        } 
    ?>

    <select name="status">
        <option value=""><?php echo esc_html__('Filter by status', 'cryptopay_lite'); ?></option>
        <option value="<?php echo esc_attr($verified->getValue()); ?>" <?php echo isset($_GET['status']) && $_GET['status'] == $verified->getValue() ? 'selected' : null ?>>
            <?php echo esc_html__('Verified', 'cryptopay_lite'); ?>
        </option>
        <option value="<?php echo esc_attr($failed->getValue()); ?>" <?php echo isset($_GET['status']) && $_GET['status'] == $failed->getValue() ? 'selected' : null ?>>
            <?php echo esc_html__('Failed', 'cryptopay_lite'); ?>
        </option>
        <option value="<?php echo esc_attr($pending->getValue()); ?>" <?php echo isset($_GET['status']) && $_GET['status'] == $pending->getValue() ? 'selected' : null ?>>
            <?php echo esc_html__('Pending', 'cryptopay_lite'); ?>
        </option>
    </select>

    <select name="code">
        <option value="all"><?php echo esc_html__('Filter by network', 'cryptopay_lite'); ?></option>
        <?php foreach ($codes as $value) : ?>
            <option value="<?php echo esc_attr($value); ?>" <?php echo isset($_GET['code']) && $_GET['code'] == $value ? 'selected' : null ?>>
                <?php echo esc_html(ucfirst($value)); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button class="button" type="submit"><?php echo esc_html__('Filter', 'cryptopay_lite'); ?></button>
    <a href="<?php echo esc_url($pageUrl) ?>" class="button"><?php echo esc_html__('Reset', 'cryptopay_lite'); ?></a>

</form>