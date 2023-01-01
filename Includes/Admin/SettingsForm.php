<div id="kirilkirkov" class="wrap">
    <form method="post" action="options.php">
        <div class="header p-4 flex items-center space-between">
            <div class="flex items-center">
                <span class="dashicons dashicons-analytics"></span>
                <h2><?php esc_html_e( 'Google Analytics', 'kirilkirkov-google-analytics' ) ?> — <?php esc_html_e( 'Page: Settings', 'kirilkirkov-google-analytics' ) ?></h2>    
            </div>
            
            <button type="submit" class="button-primary"><?php esc_html_e( 'Save' ) ?></button>
        </div>
    
        <div class="flex flex-wrap">
            <div class="w-full md:w-3/4">
                <div class="section-header p-4">
                    <strong><?php esc_html_e( 'Google Analytics', 'kirilkirkov-google-analytics' ) ?></strong>
                    <p>
                        <?php esc_html_e('
                            Google Analytics is a web analytics service that provides 
                            statistics and basic analytical tools for search engine 
                            optimization (SEO) and marketing purposes. The service is part 
                            of the Google Marketing Platform and is available for free to 
                            anyone with a Google account.
                            This plugin integrates Google Analytics into the header or footer of your theme.
                            If your theme doesn’t include default header or footer, then I’d recommend 
                            getting another theme.
                        ', 'kirilkirkov-google-analytics' ) ?>
                    </p>
                </div>

                <div class="section-body">
                    <div class="p-4">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row" class="align-middle">
                                    <div class="th-div">
                                        <span class="mr-5"><?php esc_html_e( 'Google Analytics', 'kirilkirkov-google-analytics' ); ?></span>
                                        <a class="show-info" data-info="<?php esc_attr_e( 'To find your Google Analytics Tracking code follow the instructions from google https://support.google.com/analytics/answer/9539598?hl=en', 'kirilkirkov-google-analytics' ) ?>" href="javascript:;"></a>
                                    </div>
                                </th>
                                <td colspan="2">
                                    <label><?php esc_html_e( 'Tracking code', 'kirilkirkov-google-analytics' ); ?></label>
                                    <input type="text" placeholder="<?php esc_attr_e('UA-XXXXXXXX-XX'); ?>" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>google_analytics_code" value="<?php esc_attr_e(get_option( $Config::INPUTS_PREFIX.'google_analytics_code' )); ?>" />
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row" class="align-middle">
                                    <div class="th-div">
                                        <span class="mr-5"><?php esc_html_e( 'Exclude pages', 'kirilkirkov-google-analytics' ); ?></span>
                                        <a class="show-info" data-info="<?php esc_attr_e( 'Which pages wants to exlude from tracking', 'kirilkirkov-google-analytics' ) ?>" href="javascript:;"></a>
                                    </div>
                                </th>
                                <td colspan="2">
                                    <label><?php esc_html_e( 'Current pages', 'kirilkirkov-google-analytics' ); ?></label>
                                    <?php if(get_pages() && count(get_pages())) {
                                        foreach(get_pages() as $page) {
                                            $checked = '';
                                            if($this->is_excluded($page->ID)) {
                                                $checked = 'checked'; // html attribute
                                            }
                                        ?>
                                        <div>
                                            <label>
                                                <input type="checkbox" <?php esc_attr_e($checked); ?> value="<?php esc_attr_e($page->ID); ?>" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>exclude_pages[]">
                                                <?php esc_html_e($page->post_title); ?>
                                            </label>
                                        </div>
                                    <?php } 
                                        } else { ?>
                                        <p><?php esc_html_e( 'There are no pages available', 'kirilkirkov-google-analytics') ?></p>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row" class="align-middle">
                                    <div class="th-div">
                                        <span class="mr-5"><?php esc_html_e( 'Where to load', 'kirilkirkov-google-analytics' ); ?></span>
                                        <a class="show-info" data-info="<?php esc_attr_e( 'Where wants the script to be loaded - Header or Footer?<br>My recommendation is to put it on head part', 'kirilkirkov-google-analytics' ) ?>" href="javascript:;"></a>
                                    </div>
                                </th>
                                <td colspan="2">
                                    <?php 
                                        $load_html_part = get_option($Config::INPUTS_PREFIX.'load_html_part');
                                    ?>
                                    <label for="kirilkirkov_google_analytics_head">
                                        <input type="radio" id="kirilkirkov_google_analytics_head" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>load_html_part" value="head" <?php if(!$load_html_part || $load_html_part === 'head') { echo 'checked="checked"'; }; ?>>
                                        <?php esc_html_e( 'Head') ?>
                                    </label>
                                    <label for="kirilkirkov_google_analytics_footer">
                                        <input type="radio" id="kirilkirkov_google_analytics_footer" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>load_html_part" value="footer" <?php if($load_html_part && $load_html_part === 'footer') { echo 'checked="checked"'; }; ?>>
                                        <?php esc_html_e( 'Footer') ?>
                                    </label>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row" class="align-middle">
                                    <div class="th-div">
                                        <span class="mr-5"><?php esc_html_e( 'Disabled IP\'s', 'kirilkirkov-google-analytics' ); ?></span>
                                        <a class="show-info" data-info="<?php esc_attr_e( 'Enter ip addresses separated by comma to which wants to disable google analytics. Can be helpful for administrator ip addresses. Eg. 192.168.0.1,192.168.0.2', 'kirilkirkov-google-analytics' ) ?>" href="javascript:;"></a>
                                    </div>
                                </th>
                                <td colspan="2">
                                    <label><?php esc_html_e( 'Disabled ips (IPv4)(one or multiple separated by comma)', 'kirilkirkov-google-analytics' ); ?></label>
                                    <input type="text" placeholder="<?php esc_attr_e( 'No disabled ips', 'kirilkirkov-google-analytics' ); ?>" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>disabled_ips" value="<?php esc_attr_e(get_option( $Config::INPUTS_PREFIX.'disabled_ips' )); ?>" />
                                </td>
                            </tr>
                            
                            <tr valign="top">
                                <th scope="row" class="align-middle">
                                    <div class="th-div">
                                        <span class="mr-5"><?php esc_html_e( 'Disable for roles', 'kirilkirkov-google-analytics' ); ?></span>
                                        <a class="show-info" data-info="<?php esc_attr_e( 'Which type of users dont wants to track by roles/permissions.', 'kirilkirkov-google-analytics' ) ?>" href="javascript:;"></a>
                                    </div>
                                </th>
                                <td colspan="2">
                                    <?php
                                    $tracking_roles = get_option($Config::INPUTS_PREFIX.'track_roles');
                                    foreach (get_editable_roles() as $role => $details) {  ?>
                                        <label>
                                            <input type="checkbox" <?php echo is_array($tracking_roles) && in_array(esc_attr($role), $tracking_roles) ? 'checked' : '' ?> value="<?php esc_attr_e($role); ?>" name="<?php esc_attr_e($Config::INPUTS_PREFIX); ?>track_roles[]">
                                            <?php echo translate_user_role($details['name']) ?>
                                        </label>
                                    <?php } ?>
                                </td>
                            </tr>

                            <input type="hidden" name="action" value="update" />
                            <input type="hidden" name="page_options" value="<?php esc_attr_e($Config::MAIN_UPDATE_OPTIONS); ?>" />

                            <?php settings_fields($Config::MAIN_UPDATE_OPTIONS); ?>
                        </table>
                        <div class="flex justify-end">
                            <button type="submit" class="button-primary"><?php esc_html_e( 'Save' ) ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/4 ad-col">
                <div class="p-4">
                    <div class="ad-box p-4 mb-4 flex flex-wrap items-center justify-between">
                        <a href="https://github.com/Wordpress-Plugins-World" class="accent-button w-full" target="_blank"><?php esc_html_e( 'Find Us', 'kirilkirkov-google-analytics' ); ?></a>
                    </div>

                    <div class="ad-box mb-4 p-4 flex flex-wrap items-center justify-between">
                        <p class="m-0 mb-4 text-center"><?php esc_html_e( 'Get Powerful WordPress Invoices Generator', 'kirilkirkov-spotify-search' ); ?></p>
                        <a href="https://codecanyon.net/item/wp-invoices-pdf-electronic-invoicing-system/36891583" class="w-full" target="_blank">
                            <img class="w-full" src="<?php echo plugins_url('Assets/Img/274x300.png', __FILE__ ); ?>" alt="<?php echo esc_attr( 'WordPress Invoices' ) ?>" />
                        </a>
                    </div>

                    <div class="ad-box p-4 flex flex-wrap items-center justify-between">
                        <p class="m-0 mb-4 text-center"><?php esc_html_e( 'Get Powerful Project Management System', 'kirilkirkov-wp-invoices' ); ?></p>
                        <a href="https://codecanyon.net/item/agile-scrum-project-issue-management/36720961" class="w-full" target="_blank">
                            <img class="w-full" src="<?php echo plugins_url('/Assets/Img/banner.jpg', __FILE__ ); ?>" alt="<?php echo esc_attr( 'Agile Scrum' ) ?>" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
 
    <!-- Start Modal -->
    <div class="ft-modal google-analytics-info">
        <div class="ft-modal-content">
            <div class="ft-modal-header">
                <div class="header">
                    <h3 class="text-center"><?php esc_html_e( 'Helpful information', 'kirilkirkov-google-analytics' ); ?></h3>
                </div>
            </div>	
            <div class="ft-modal-body">
                <p class="info-box"></p>
                <hr>			
            </div>
            <div class="ft-modal-footer">
                <a class="ft-modal-close" href="javascript:;">[&#10006;] <?php esc_html_e( 'Close Modal', 'kirilkirkov-google-analytics' ); ?></a>
            </div>
        </div>
    </div>
    <!-- End Modal -->
</div>