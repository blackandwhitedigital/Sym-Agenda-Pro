<?php
    global $Agenda;
    $settings = get_option($Agenda->options['settings']);
?>
<div class="wrap">
    <div id="upf-icon-edit-pages" class="icon32 icon32-posts-page"><br/></div>
    <h2><?php _e('Agenda Settings', AGENDA_SLUG);?></h2>
    <div class="tlp-content-holder">
        <div class="tch-left">
            <form id="tlp-settings" onsubmit="agendaSettings(this); return false;">

                <h3><?php _e('General settings',AGENDA_SLUG);?></h3>

                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="primary-color"><?php _e('Primary Color',AGENDA_SLUG);?></label></th>
                        <td class="">
                            <input name="primary_color" id="primary_color" type="text" value="<?php echo (isset($settings['primary_color']) ? ($settings['primary_color'] ? $settings['primary_color'] : '#0367bf') : '#0367bf'); ?>" class="tlp-color">
                            
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="heading-color"><?php _e('Table Heading Color',AGENDA_SLUG);?></label></th>
                        <td class="">
                            <input name="th_color" id="th_color" type="text" value="<?php echo (isset($settings['th_color']) ? ($settings['th_color'] ? $settings['th_color'] : '#3498DB') : '#3498DB'); ?>" class="tlp-color">
                            
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="column-color"><?php _e('Session Colors',AGENDA_SLUG);?></label></th>
                        <td class="">
                            <input name="tr_color" id="tr_color" type="text" value="<?php echo (isset($settings['tr_color']) ? ($settings['tr_color'] ? $settings['tr_color'] : '#f2f2f2') : '#f2f2f2'); ?>" class="tlp-color">
                             <input name="td_color" id="td_color" type="text" value="<?php echo (isset($settings['td_color']) ? ($settings['td_color'] ? $settings['td_color'] : '#fff') : '#fff'); ?>" class="tlp-color">
                            
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="text-color"><?php _e('Text Color,Size & Margin',SPEAKER_SLUG);?></label></th>
                        <td class="">
                            <span style="display:block;"><input name="text_color" id="text_color" type="text" value="<?php echo (isset($settings['text_color']) ? ($settings['text_color'] ? $settings['text_color'] : '#4b5159') : '#4b5159'); ?>" class="tlp-color"></span>
                            <span style="display:block;margin-bottom:5px;"><input name="text_size" id="text_size" type="text" value="<?php echo (isset($settings['text_size']) ? ($settings['text_size'] ? $settings['text_size'] : '14px') : '14px'); ?>"></span>
                            <span style="display:block;"><input name="text_align" id="text_align" type="text" value="<?php echo (isset($settings['text_align']) ? ($settings['text_align'] ? $settings['text_align'] : 'left') : 'left'); ?>"></span>
                            
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="slug"><?php _e('Slug',AGENDA_SLUG);?></label></th>
                        <td class="">
                            <input name="slug" id="slug" type="text" value="<?php echo (isset($settings['slug']) ? ($settings['slug'] ? sanitize_title_with_dashes($settings['slug']) : 'agenda' ) : 'agenda'); ?>" size="15" class="">
                            <p class="description"><?php _e('Slug configuration',AGENDA_SLUG);?></p>
                        </td>
                    </tr>

                    
                    <tr> 
                        <th scope="row"><label for="imgWidth"><?php _e('Image Size',AGENDA_SLUG);?></label></th>
                        <td><input name="imgw" id="imgw" type="text" value="<?php echo (isset($settings['imgw']) ? ($settings['imgw'] ? ($settings['imgw']) : 150 ) : 150); ?>" size="4" class=""> * <input name="imgh" id="imgh" type="text" value="<?php echo (isset($settings['imgh']) ? ($settings['imgh'] ? ($settings['imgh']) : 150 ) : 150); ?>" size="4" class=""> <?php _e('(Width * Height)',AGENDA_SLUG); ?></td>
                        
                    </tr>

                    <tr>
                        <th scope="row"><label for="Square/Rounded image"><?php _e('Square/Rounded image',AGENDA_SLUG);?></label></th>
                        <td class="">
                            <input name="border_radius" id="border_radius" type="text" value="<?php echo (isset($settings['border_radius']) ? ($settings['border_radius'] ? $settings['border_radius'] : '0%') : '0%'); ?>">
                            
                            
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="bullet_point"><?php _e('Styling Bullet Points',AGENDA_SLUG);?></label></th>
                        <td class="">
                            <select name="bullet_point" id="bullet_point" type="text" value="<?php echo (isset($settings['bullet_point']) ? ($settings['bullet_point'] ? $settings['bullet_point'] : 'circle') : 'circle'); ?>">
                            <option value="none">none</option>
                            <option value="circle">circle</option>
                            <option value="square">square</option>
                            <option value="disc">disc</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="css"><?php _e('Custom Css ',AGENDA_SLUG);?></label></th>
                        <td>
                            <textarea name="custom_css" cols="40" rows="6"><?php echo (isset($settings['custom_css']) ? ($settings['custom_css'] ? $settings['custom_css'] : null) : null); ?></textarea>
                        </td>
                    </tr>


                </table>
                <p class="submit"><input type="submit" name="submit" id="SaveButton" class="button button-primary" value="<?php _e('Save Changes', AGENDA_SLUG); ?>"></p>

                <?php wp_nonce_field( $Agenda->nonceText(), 'agenda_nonce' ); ?>
            </form>

            
            
            <div id="response" class="updated"></div>
        </div>
    </div>
    <div class="tlp-help">
        <p style="font-weight: bold"><?php _e('Short Code', AGENDA_SLUG );?> :</p>
        <code>[agenda col="1" eventid="123" orderby="title" order="DESC" layout="isotope"]</code><br>
        <p><?php _e('eventid = Event ID , which you want to show ', AGENDA_SLUG );?></p>
        <p><?php _e('orderby = title,menu_order', AGENDA_SLUG );?></p>
        <p><?php _e('ordr = ASC, DESC', AGENDA_SLUG );?></p>
        <p><?php _e('layout = 1,isotope', AGENDA_SLUG );?></p>
        
    </div>

</div>
