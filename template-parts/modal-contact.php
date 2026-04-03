<?php $shortcode = mbt_get_theme_mod_string('mbt_contact_form_shortcode', ''); ?>
<div class="mbt-modal" id="mbt-contact-modal" aria-hidden="true">
    <div class="mbt-modal__overlay" data-modal-close></div>
    <div class="mbt-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="mbt-modal-title">
        <button type="button" class="mbt-modal__close" aria-label="<?php esc_attr_e('Close dialog', 'my-business-theme'); ?>" data-modal-close>&times;</button>
        <h2 id="mbt-modal-title"><?php echo esc_html(mbt_get_theme_mod_string('mbt_modal_title', __('Get in touch', 'my-business-theme'))); ?></h2>
        <div class="mbt-modal__content">
            <?php if ($shortcode) : ?>
                <?php echo do_shortcode($shortcode); ?>
            <?php else : ?>
                <p><?php esc_html_e('Add a contact form shortcode in the Customizer to display your form here.', 'my-business-theme'); ?></p>
                <form class="mbt-demo-form">
                    <p><label><?php esc_html_e('Name', 'my-business-theme'); ?><br><input type="text" placeholder="<?php esc_attr_e('Your name', 'my-business-theme'); ?>"></label></p>
                    <p><label><?php esc_html_e('Email', 'my-business-theme'); ?><br><input type="email" placeholder="<?php esc_attr_e('Your email', 'my-business-theme'); ?>"></label></p>
                    <p><label><?php esc_html_e('Message', 'my-business-theme'); ?><br><textarea rows="5" placeholder="<?php esc_attr_e('How can we help?', 'my-business-theme'); ?>"></textarea></label></p>
                    <p><button type="submit" class="mbt-button"><?php esc_html_e('Send enquiry', 'my-business-theme'); ?></button></p>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
