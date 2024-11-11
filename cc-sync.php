<?php
/**
 * Plugin Name:     CC Sync
 * Description:     Sync chambe-carnet workflow
 * Author:          Jerome Fath
 * Text Domain:     cc-sync
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Cc_Sync
 */

/**
 * Register a custom menu page.
 */
function cc_sync_admin_menu_page(){
    add_menu_page(
        'Chambé-Carnet synchro to Weezevent',
        'Weezevent',
        'create_users',
        'ccsync-page',
        'cc_sync_admin_page',
		'dashicons-tickets',
        6
    );
}
add_action( 'admin_menu', 'cc_sync_admin_menu_page' );

/**
 * Display a custom menu page
 */
function cc_sync_admin_page() {
    require_once(WP_PLUGIN_DIR.'/cc-sync/ccsync-admin.php');
}

/*
 * Display list of users for one event
 */
function weezevent_users($params){
    ob_start();
    if (!empty($params['id_event'])) {
        require __DIR__.'/vendor/autoload.php';
        $utils = new \ChambeCarnet\Utils();
        $listIds = $utils->getUsersByEvent($params['id_event']);
        if (!empty($listIds)) {
        ?>
            <ul class="participants">
            <?php
            foreach ($listIds as $id) {
                $user = get_user_by('id', $id);
                ?>
                <li>
                    <span>
                        <?php echo get_avatar(get_the_author_meta('user_email', $user->ID), 80); ?>
                    </span>
                    <strong><?php echo $user->display_name?></strong>
                    <?php if(!empty(get_the_author_meta('profession', $user->ID))): ?>
                        <br /><?php the_author_meta('profession', $user->ID); ?>
                    <?php endif ?>
                    <?php if(!empty(get_the_author_meta('entreprise', $user->ID))): ?>
                        <?php if(!empty(get_the_author_meta('sitewebentreprise', $user->ID))): ?>
                            <a href="<?php the_author_meta('sitewebentreprise', $user->ID); ?>"><?php the_author_meta('entreprise', $user->ID); ?></a>
                        <?php endif ?>
                        <?php if(empty(get_the_author_meta('sitewebentreprise', $user->ID))): ?>
                            <?php the_author_meta('entreprise', $user->ID); ?>
                        <?php endif ?>
                    <?php endif ?>
                    <?php if(!empty(get_the_author_meta('url', $user->ID))):
                        $url_name = str_replace("http://", '',get_the_author_meta('url', $user->ID));
                        $url_name = str_replace('www.', '', $url_name);
                        $url_name = str_replace('/', '', $url_name);
                    ?>
                        <br />Site web : <a href="<?php the_author_meta('url', $user->ID); ?>" target="_blank"><?php echo $url_name; ?></a>
                    <?php endif ?>
                    <br />
                    <?php if(!empty(get_the_author_meta('linkedin', $user->ID))): ?>
                        <a href="<?php the_author_meta('linkedin', $user->ID); ?>">
                            <img class="alignleft size-full wp-image-931" title="linkedin" src="https://www.chambe-carnet.com/wp-content/uploads/2011/01/linkedin.png" alt="linkedin" width="18" height="18" />
                        </a>
                    <?php endif ?>
                    <?php if(!empty(get_the_author_meta('viadeo', $user->ID))): ?>
                        <a href="<?php the_author_meta('viadeo', $user->ID); ?>">
                            <img class="alignleft size-full wp-image-934" title="viadeo" src="https://www.chambe-carnet.com/wp-content/uploads/2011/01/viadeo.png" alt="viadeo" width="18" height="18" />
                        </a>
                    <?php endif ?>
                    <?php if(!empty(get_the_author_meta('twitter', $user->ID))): ?>
                        <a href="<?php the_author_meta('twitter', $user->ID); ?>">
                            <img class="alignleft size-full wp-image-933" title="twitter" src="https://www.chambe-carnet.com/wp-content/uploads/2011/01/twitter.png" alt="twitter" width="18" height="18" />
                        </a>
                    <?php endif ?>
                </li>
                <?php
            }
            ?>
            </ul>
        <?php
        }
    }
    return ob_get_clean();
}
add_shortcode('we_participants', 'weezevent_users');
