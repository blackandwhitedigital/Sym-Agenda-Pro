<?php
global $Agenda;
$settings = get_option($Agenda->options['settings']);
?>
<div class="wrap">
    <div id="upf-icon-edit-pages" class="icon32 icon32-posts-page"><br/></div>
    <h2 class="page-heading"><?php _e('Agenda Settings', AGENDA_SLUG); ?></h2>
    <div class="tlp-content-holder">
        <div class="tch-left-setting">
            <form id="tlp-settings" onsubmit="agendaSettings(this); return false;">

                <h3 class="content-heading"><?php _e('General settings', AGENDA_SLUG); ?></h3>

                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="primary-color"><?php _e('Leisure Background & Color', AGENDA_SLUG); ?></label>
                        </th>
                        <td class="">
                            <input name="primary_color" id="primary_color" type="text"
                                   value="<?php echo(isset($settings['primary_color']) ? ($settings['primary_color'] ? $settings['primary_color'] : '#4b4b4b') : '#4b4b4b'); ?>"
                                   class="tlp-color">

                            <input name="ltext_color" id="ltext_color" type="text"
                                   value="<?php echo(isset($settings['ltext_color']) ? ($settings['ltext_color'] ? $settings['ltext_color'] : '#fff') : '#fff'); ?>"
                                   class="tlp-color">

                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="table-color"><?php _e('Table', AGENDA_SLUG); ?></label>
                        </th>
                        <td class="">
                            <input name="table_color" id="table_color" type="text"
                                   value="<?php echo(isset($settings['table_color']) ? ($settings['table_color'] ? $settings['table_color'] : '') : ''); ?>"
                                   class="tlp-color">

                        </td>
                    </tr>


                    <tr>
                        <th scope="row"><label for="slug"><?php _e('Slug', AGENDA_SLUG); ?></label></th>
                        <td class="">
                            <input name="slug" id="slug" type="text"
                                   value="<?php echo(isset($settings['slug']) ? ($settings['slug'] ? sanitize_title_with_dashes($settings['slug']) : 'agenda') : 'agenda'); ?>"
                                   size="15" class="">
                            <p class="description"><?php _e('Slug configuration', AGENDA_SLUG); ?></p>
                        </td>
                    </tr>


                   <!--  <tr>
                        <th scope="row"><label for="text-color"><?php _e('Session Title Color,Text Size & Margin',SPEAKER_SLUG);?></label></th>
                        <td class="">
                            <span style="display:block;"><input name="text_color" id="text_color" type="text" value="<?php echo (isset($settings['text_color']) ? ($settings['text_color'] ? $settings['text_color'] : '#4a4a4a') : '#4a4a4a'); ?>" class="tlp-color"></span>
                            <span style="display:block;margin-bottom:5px;"><input name="text_size" id="text_size" type="text" value="<?php echo (isset($settings['text_size']) ? ($settings['text_size'] ? $settings['text_size'] : '15px') : '15px'); ?>"></span>
                            <span style="display:block;"><input name="text_align" id="text_align" type="text" value="<?php echo (isset($settings['text_align']) ? ($settings['text_align'] ? $settings['text_align'] : 'none') : 'none'); ?>"></span>
                            
                        </td>
                    </tr> -->
                    <!-- start-->
                     <tr>
                        <th scope="row"><label for="text-color"><?php _e('Text Color',SPEAKER_SLUG);?></label></th>
                        <td class="">
                            <!-- <div class="settingCss">
                                <span>Session Title</span>
                                <span>Description</span>
                                <span>Session Speaker</span>
                                <span>Speaker Role</span>
                                <span>Speaker Organisation</span>
                            </div> -->
                            <span style="display:block;">
                                <div class="textColor st">
                                    <span class="Title">Session Title</span>
                                    <input name="heading_color" id="text_color" type="text" value="<?php echo (isset($settings['heading_color']) ? ($settings['heading_color'] ? $settings['heading_color'] : '#000') : '#000'); ?>" class="tlp-color">
                                </div>
                                <div class="textColor des">
                                    <span class="Title">Description</span>
                                    <input name="descr_color" id="descr_color" type="text" value="<?php echo (isset($settings['descr_color']) ? ($settings['descr_color'] ? $settings['descr_color'] : '') : ''); ?>" class="tlp-color">
                                </div>
                                <div class="textColor sp">
                                    <span class="Title">Session Speaker</span>
                                    <input name="speaker_color" id="speaker_color" type="text" value="<?php echo (isset($settings['speaker_color']) ? ($settings['speaker_color'] ? $settings['speaker_color'] : '') : ''); ?>" class="tlp-color">
                                </div>
                                <div class="textColor sr">
                                    <span class="Title">Speaker Role</span>
                                    <input name="speaker_rolecolor" id="speaker_rolecolor" type="text" value="<?php echo (isset($settings['speaker_rolecolor']) ? ($settings['speaker_rolecolor'] ? $settings['speaker_rolecolor'] : '') : ''); ?>" class="tlp-color">
                                </div>
                                <div class="textColor so">
                                    <span class="Title">Speaker Organisation</span>
                                    <input name="speaker_orgcolor" id="speaker_orgcolor" type="text" value="<?php echo (isset($settings['speaker_orgcolor']) ? ($settings['speaker_orgcolor'] ? $settings['speaker_orgcolor'] : '') : ''); ?>" class="tlp-color">
                                </div>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="text-color"><?php _e('Text Size',SPEAKER_SLUG);?></label></th>
                        <td>
                            <span class="asmin-color-three" style="display:block;margin-bottom:8px;">
                                <input name="heading_size" id="heading_size" size="10" type="text" value="<?php echo (isset($settings['heading_size']) ? ($settings['heading_size'] ? $settings['heading_size'] : '20px') : '20px'); ?>">
                                <input name="descr_size" id="descr_size" size="10" type="text" value="<?php echo (isset($settings['descr_size']) ? ($settings['descr_size'] ? $settings['descr_size'] : '15px') : '15px'); ?>">
                                <input name="speaker_size" id="speaker_size" size="10" type="text" value="<?php echo (isset($settings['speaker_size']) ? ($settings['speaker_size'] ? $settings['speaker_size'] : '15px') : '15px'); ?>">
                                <input name="speaker_rolesize" id="speaker_rolesize" size="10" type="text" value="<?php echo (isset($settings['speaker_rolesize']) ? ($settings['speaker_rolesize'] ? $settings['speaker_rolesize'] : '15px') : '15px'); ?>">
                                <input name="speaker_orgsize" id="speaker_orgsize" size="10" type="text" value="<?php echo (isset($settings['speaker_orgsize']) ? ($settings['speaker_orgsize'] ? $settings['speaker_orgsize'] : '15px') : '15px'); ?>">
                            </span>
                        </td>
                    </tr>

                     <tr>
                        <th scope="row"><label for="text-color"><?php _e('Text Style',SPEAKER_SLUG);?></label></th>
                        <td>
                            <span class="asmin-color-three" style="display:block;">
                                 <select name="textstylehead" id="textstylehead" type="text"
                                        value="<?php echo(isset($settings['textstylehead']) ? ($settings['textstylehead'] ? $settings['textstylehead'] : 'normal') : 'normal'); ?>">
                                    <option value=<?php $settings = get_option($Agenda->options['settings']);
                                    (isset($settings['textstylehead']) ? ($settings['textstylehead'] ? $settings['textstylehead'] : 'normal' ) : 'normal');?>><?php echo (isset($settings['textstylehead']) ? ($settings['textstylehead'] ? $settings['textstylehead'] : 'normal') : 'normal');?></option>
                                    <option value="bold">bold</option>
                                    <option value="italic">italic</option>
                                    <option value="underline">underline</option>
                                </select>
                                <select name="textstyledesg" id="textstyledesg" type="text"
                                        value="<?php echo(isset($settings['textstyledesg']) ? ($settings['textstyledesg'] ? $settings['textstyledesg'] : 'normal') : 'normal'); ?>">
                                   <option value=<?php
                                    (isset($settings['textstyledesg']) ? ($settings['textstyledesg'] ? $settings['textstyledesg'] : 'normal' ) : 'normal');?>><?php echo (isset($settings['textstyledesg']) ? ($settings['textstyledesg'] ? $settings['textstyledesg'] : 'normal') : 'normal');?></option>
                                    <option value="bold">bold</option>
                                    <option value="italic">italic</option>
                                    <option value="underline">underline</option>
                                </select>
                                <select name="textstylespeaker" id="textstylespeaker" type="text"
                                        value="<?php echo(isset($settings['textstylespeaker']) ? ($settings['textstylespeaker'] ? $settings['textstylespeaker'] : 'normal') : 'normal'); ?>">
                                    <option value=<?php
                                    (isset($settings['textstylespeaker']) ? ($settings['textstylespeaker'] ? $settings['textstylespeaker'] : 'normal' ) : 'normal');?>><?php echo (isset($settings['textstylespeaker']) ? ($settings['textstylespeaker'] ? $settings['textstylespeaker'] : 'normal') : 'normal');?></option>
                                    <option value="bold">bold</option>
                                    <option value="italic">italic</option>
                                    <option value="underline">underline</option>
                                </select>
                                <select name="textstylerole" id="textstylerole" type="text"
                                        value="<?php echo(isset($settings['textstylerole']) ? ($settings['textstylerole'] ? $settings['textstylerole'] : 'normal') : 'normal'); ?>">
                                    <option value=<?php
                                    (isset($settings['textstylerole']) ? ($settings['textstylerole'] ? $settings['textstylerole'] : 'normal' ) : 'normal');?>><?php echo (isset($settings['textstylerole']) ? ($settings['textstylerole'] ? $settings['textstylerole'] : 'normal') : 'normal');?></option>
                                    <option value="bold">bold</option>
                                    <option value="italic">italic</option>
                                    <option value="underline">underline</option>
                                </select>
                                <select name="textstyleorg" id="textstyleorg" type="text"
                                        value="<?php echo(isset($settings['textstyleorg']) ? ($settings['textstyleorg'] ? $settings['textstyleorg'] : 'normal') : 'normal'); ?>">
                                    <option value=<?php
                                    (isset($settings['textstyleorg']) ? ($settings['textstyleorg'] ? $settings['textstyleorg'] : 'normal' ) : 'normal');?>><?php echo (isset($settings['textstyleorg']) ? ($settings['textstyleorg'] ? $settings['textstyleorg'] : 'normal') : 'normal');?></option>
                                    <option value="bold">bold</option>
                                    <option value="italic">italic</option>
                                    <option value="underline">underline</option>
                                </select>
                            </span>
                        </td>
                    </tr>

                    <!-- <tr>
                        <th scope="row"><label for="text-color"><?php _e('Text Align',SPEAKER_SLUG);?></label></th> 
                        <td>
                            <span class="asmin-color-three" style="display:block;">
                                <input name="heading_align" size="10" id="heading_align" type="text" value="<?php echo (isset($settings['heading_align']) ? ($settings['heading_align'] ? $settings['heading_align'] : 'none') : 'none'); ?>">
                                <input name="descr_align" id="descr_align" size="10" type="text" value="<?php echo (isset($settings['descr_align']) ? ($settings['descr_align'] ? $settings['descr_align'] : 'none') : 'none'); ?>">
                                <input name="speaker_align" id="speaker_align" size="10" type="text" value="<?php echo (isset($settings['speaker_align']) ? ($settings['speaker_align'] ? $settings['speaker_align'] : 'none') : 'none'); ?>">
                            </span>  
                        </td>
                    </tr> -->

                   
                    <!-- end-->

                    <tr>
                        <th scope="row"><label
                                for="bullet_point"><?php _e('Styling Bullet Points', AGENDA_SLUG); ?></label></th>
                        <td class="">
                           <select name="bullet_point" id="bullet_point" type="text"
                                    value="<?php echo(isset($settings['bullet_point']) ? ($settings['bullet_point'] ? $settings['bullet_point'] : 'circle') : 'circle'); ?>">
                                <option value=<?php 
                                (isset($settings['bullet_point']) ? ($settings['bullet_point'] ? $settings['bullet_point'] : 'none' ) : 'none');?>><?php echo (isset($settings['bullet_point']) ? ($settings['bullet_point'] ? $settings['bullet_point'] : 'none') : 'none');?></option>
                                    <option value="none" <?php selected( $bullet_point, none ); ?>>none</option>
                                <option value="circle">circle</option>
                                <option value="square">square</option>
                                <option value="disc">disc</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="css"><?php _e('Custom Css ', AGENDA_SLUG); ?></label></th>
                        <td>
                            <textarea name="custom_css" cols="40"
                                      rows="6"><?php echo(isset($settings['custom_css']) ? ($settings['custom_css'] ? $settings['custom_css'] : null) : null); ?></textarea>
                        </td>
                    </tr>


                </table>
                <p class="submit"><input type="submit" name="submit" id="SaveButton" class="button button-primary"
                                         value="<?php _e('Save Changes', AGENDA_SLUG); ?>"></p>

                <?php wp_nonce_field($Agenda->nonceText(), 'agenda_nonce'); ?>
            </form>

            <div id="response" class="updated"></div>
        </div>
    </div>

    <!-- <div class="tlp-help">
        <p style="font-weight: bold"><?php _e('Short Code', AGENDA_SLUG );?> :</p>
        <code>[agenda col="1" eventid="123" orderby="time" order="DESC" layout="2"]</code><br>
        <p><?php _e('eventid = Event ID , which you want to show ', AGENDA_SLUG );?></p>
        <p><?php _e('orderby = time,speaker', AGENDA_SLUG );?></p>
        <p><?php _e('ordr = ASC, DESC', AGENDA_SLUG );?></p>
        <p><?php _e('layout = 1,2', AGENDA_SLUG );?></p>
        
    </div> -->

</div>
