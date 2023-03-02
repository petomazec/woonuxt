<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpaicg_grid_form" id="wpaicg-post-form">
    <div class="wpaicg_grid_form_2">
        <div class="mb-5">
            <input type="text" id="wpai_preview_title" placeholder="Title: e.g. Mobile Phones" class="wpaicg-input" name="_wporg_preview_title" value="<?php 
echo  esc_html( $_wporg_preview_title ) ;
?>">
        </div>
        <div class="mb-5">
            <div class="wpaicg-tabs">
                <ul>
                    <li id="wpaicg-seo-tab-content" data-target="wpaicg-tab-generated-text" class="wpaicg-active">Content</li>
                    <li id="wpaicg-seo-tab-item" data-target="wpaicg-seo-tab" class="<?php 
echo  ( !empty($post->post_excerpt) ? 'wpaicg-has-seo' : '' ) ;
?>">SEO</li>
                </ul>
                <div class="wpaicg-tab-content">
                    <div id="wpaicg-tab-generated-text">
                        <textarea id="wpcgai_preview_box" name="_wporg_generated_text" rows="20" cols="20" class="wpai-content-generator-textarea"></textarea>
                    </div>
                    <div id="wpaicg-seo-tab" style="display: none">
                        <p>Meta Description</p>
                        <textarea id="wpaicg-meta-description" name="_wpaicg_meta_description" rows="20" cols="20"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-5">
            <button type="button" name="get_preview" id="wpcgai_load_plugin_settings" class="button button-primary button-large">Generate</button>
            <button type="button" style="display:none;" name="action_save_draft" id="wpcgai_save_draft_post_action" class="button button-large">Save Draft</button>
        </div>
    </div>
    <div class="wpaicg_grid_form_1">
        <div class="wpaicg-collapse wpaicg-collapse-active">
            <div class="wpaicg-collapse-title"><span>-</span> Language, Style and Tone</div>
            <div class="wpaicg-collapse-content">
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="label_title"><?php 
echo  esc_html( __( "Language", "wp-ai-content-generator" ) ) ;
?></label>
                    <select class="wpaicg-input" name="_wporg_language" id="wpai_language">
                        <option value="en" <?php 
echo  ( esc_html( $_wporg_language ) == 'en' ? 'selected' : '' ) ;
?>>English</option>
                        <option value="ar" <?php 
echo  ( esc_html( $_wporg_language ) == 'ar' ? 'selected' : '' ) ;
?>>Arabic</option>
                        <option value="bg" <?php 
echo  ( esc_html( $_wporg_language ) == 'bg' ? 'selected' : '' ) ;
?>>Bulgarian</option>
                        <option value="zh" <?php 
echo  ( esc_html( $_wporg_language ) == 'zh' ? 'selected' : '' ) ;
?>>Chinese</option>
                        <option value="hr" <?php 
echo  ( esc_html( $_wporg_language ) == 'hr' ? 'selected' : '' ) ;
?>>Croatian</option>
                        <option value="cs" <?php 
echo  ( esc_html( $_wporg_language ) == 'cs' ? 'selected' : '' ) ;
?>>Czech</option>
                        <option value="da" <?php 
echo  ( esc_html( $_wporg_language ) == 'da' ? 'selected' : '' ) ;
?>>Danish</option>
                        <option value="nl" <?php 
echo  ( esc_html( $_wporg_language ) == 'nl' ? 'selected' : '' ) ;
?>>Dutch</option>
                        <option value="et" <?php 
echo  ( esc_html( $_wporg_language ) == 'et' ? 'selected' : '' ) ;
?>>Estonian</option>
                        <option value="fil" <?php 
echo  ( esc_html( $_wporg_language ) == 'fil' ? 'selected' : '' ) ;
?>>Filipino</option>
                        <option value="fi" <?php 
echo  ( esc_html( $_wporg_language ) == 'fi' ? 'selected' : '' ) ;
?>>Finnish</option>
                        <option value="fr" <?php 
echo  ( esc_html( $_wporg_language ) == 'fr' ? 'selected' : '' ) ;
?>>French</option>
                        <option value="de" <?php 
echo  ( esc_html( $_wporg_language ) == 'de' ? 'selected' : '' ) ;
?>>German</option>
                        <option value="el" <?php 
echo  ( esc_html( $_wporg_language ) == 'el' ? 'selected' : '' ) ;
?>>Greek</option>
                        <option value="he" <?php 
echo  ( esc_html( $_wporg_language ) == 'he' ? 'selected' : '' ) ;
?>>Hebrew</option>
                        <option value="hi" <?php 
echo  ( esc_html( $_wporg_language ) == 'hi' ? 'selected' : '' ) ;
?>>Hindi</option>
                        <option value="hu" <?php 
echo  ( esc_html( $_wporg_language ) == 'hu' ? 'selected' : '' ) ;
?>>Hungarian</option>
                        <option value="id" <?php 
echo  ( esc_html( $_wporg_language ) == 'id' ? 'selected' : '' ) ;
?>>Indonesian</option>
                        <option value="it" <?php 
echo  ( esc_html( $_wporg_language ) == 'it' ? 'selected' : '' ) ;
?>>Italian</option>
                        <option value="ja" <?php 
echo  ( esc_html( $_wporg_language ) == 'ja' ? 'selected' : '' ) ;
?>>Japanese</option>
                        <option value="ko" <?php 
echo  ( esc_html( $_wporg_language ) == 'ko' ? 'selected' : '' ) ;
?>>Korean</option>
                        <option value="lv" <?php 
echo  ( esc_html( $_wporg_language ) == 'lv' ? 'selected' : '' ) ;
?>>Latvian</option>
                        <option value="lt" <?php 
echo  ( esc_html( $_wporg_language ) == 'lt' ? 'selected' : '' ) ;
?>>Lithuanian</option>
                        <option value="ms" <?php 
echo  ( esc_html( $_wporg_language ) == 'ms' ? 'selected' : '' ) ;
?>>Malay</option>
                        <option value="no" <?php 
echo  ( esc_html( $_wporg_language ) == 'no' ? 'selected' : '' ) ;
?>>Norwegian</option>
                        <option value="pl" <?php 
echo  ( esc_html( $_wporg_language ) == 'pl' ? 'selected' : '' ) ;
?>>Polish</option>
                        <option value="pt" <?php 
echo  ( esc_html( $_wporg_language ) == 'pt' ? 'selected' : '' ) ;
?>>Portuguese</option>
                        <option value="ro" <?php 
echo  ( esc_html( $_wporg_language ) == 'ro' ? 'selected' : '' ) ;
?>>Romanian</option>
                        <option value="ru" <?php 
echo  ( esc_html( $_wporg_language ) == 'ru' ? 'selected' : '' ) ;
?>>Russian</option>
                        <option value="sr" <?php 
echo  ( esc_html( $_wporg_language ) == 'sr' ? 'selected' : '' ) ;
?>>Serbian</option>
                        <option value="sk" <?php 
echo  ( esc_html( $_wporg_language ) == 'sk' ? 'selected' : '' ) ;
?>>Slovak</option>
                        <option value="sl" <?php 
echo  ( esc_html( $_wporg_language ) == 'sl' ? 'selected' : '' ) ;
?>>Slovenian</option>
                        <option value="es" <?php 
echo  ( esc_html( $_wporg_language ) == 'es' ? 'selected' : '' ) ;
?>>Spanish</option>
                        <option value="sv" <?php 
echo  ( esc_html( $_wporg_language ) == 'sv' ? 'selected' : '' ) ;
?>>Swedish</option>
                        <option value="th" <?php 
echo  ( esc_html( $_wporg_language ) == 'th' ? 'selected' : '' ) ;
?>>Thai</option>
                        <option value="tr" <?php 
echo  ( esc_html( $_wporg_language ) == 'tr' ? 'selected' : '' ) ;
?>>Turkish</option>
                        <option value="uk" <?php 
echo  ( esc_html( $_wporg_language ) == 'uk' ? 'selected' : '' ) ;
?>>Ukranian</option>
                        <option value="vi" <?php 
echo  ( esc_html( $_wporg_language ) == 'vi' ? 'selected' : '' ) ;
?>>Vietnamese</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_writing_style"><?php 
echo  esc_html( __( "Style", "wp-ai-content-generator" ) ) ;
?></label>
                    <select name="_wporg_writing_style" id="wpai_writing_style" class="wpaicg-input">
                        <option value="infor" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'infor' ? 'selected' : '' ) ;
?>>Informative</option>
                        <option value="acade" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'acade' ? 'selected' : '' ) ;
?>>Academic</option>
                        <option value="analy" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'analy' ? 'selected' : '' ) ;
?>>Analytical</option>
                        <option value="anect" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'anect' ? 'selected' : '' ) ;
?>>Anecdotal</option>
                        <option value="argum" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'argum' ? 'selected' : '' ) ;
?>>Argumentative</option>
                        <option value="artic" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'artic' ? 'selected' : '' ) ;
?>>Articulate</option>
                        <option value="biogr" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'biogr' ? 'selected' : '' ) ;
?>>Biographical</option>
                        <option value="blog" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'blog' ? 'selected' : '' ) ;
?>>Blog</option>
                        <option value="casua" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'casua' ? 'selected' : '' ) ;
?>>Casual</option>
                        <option value="collo" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'collo' ? 'selected' : '' ) ;
?>>Colloquial</option>
                        <option value="compa" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'compa' ? 'selected' : '' ) ;
?>>Comparative</option>
                        <option value="conci" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'conci' ? 'selected' : '' ) ;
?>>Concise</option>
                        <option value="creat" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'creat' ? 'selected' : '' ) ;
?>>Creative</option>
                        <option value="criti" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'criti' ? 'selected' : '' ) ;
?>>Critical</option>
                        <option value="descr" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'descr' ? 'selected' : '' ) ;
?>>Descriptive</option>
                        <option value="detai" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'detai' ? 'selected' : '' ) ;
?>>Detailed</option>
                        <option value="dialo" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'dialo' ? 'selected' : '' ) ;
?>>Dialogue</option>
                        <option value="direct" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'direct' ? 'selected' : '' ) ;
?>>Direct</option>
                        <option value="drama" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'drama' ? 'selected' : '' ) ;
?>>Dramatic</option>
                        <option value="emoti" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'emoti' ? 'selected' : '' ) ;
?>>Emotional</option>
                        <option value="evalu" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'evalu' ? 'selected' : '' ) ;
?>>Evaluative</option>
                        <option value="expos" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'expos' ? 'selected' : '' ) ;
?>>Expository</option>
                        <option value="ficti" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'ficti' ? 'selected' : '' ) ;
?>>Fiction</option>
                        <option value="histo" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'histo' ? 'selected' : '' ) ;
?>>Historical</option>
                        <option value="journ" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'journ' ? 'selected' : '' ) ;
?>>Journalistic</option>
                        <option value="metaph" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'metaph' ? 'selected' : '' ) ;
?>>Metaphorical</option>
                        <option value="monol" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'monol' ? 'selected' : '' ) ;
?>>Monologue</option>
                        <option value="lette" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'lette' ? 'selected' : '' ) ;
?>>Letter</option>
                        <option value="lyric" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'lyric' ? 'selected' : '' ) ;
?>>Lyrical</option>
                        <option value="narra" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'narra' ? 'selected' : '' ) ;
?>>Narrative</option>
                        <option value="news" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'news' ? 'selected' : '' ) ;
?>>News</option>
                        <option value="objec" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'objec' ? 'selected' : '' ) ;
?>>Objective</option>
                        <option value="pasto" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'pasto' ? 'selected' : '' ) ;
?>>Pastoral</option>
                        <option value="perso" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'perso' ? 'selected' : '' ) ;
?>>Personal</option>
                        <option value="persu" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'persu' ? 'selected' : '' ) ;
?>>Persuasive</option>
                        <option value="poeti" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'poeti' ? 'selected' : '' ) ;
?>>Poetic</option>
                        <option value="refle" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'refle' ? 'selected' : '' ) ;
?>>Reflective</option>
                        <option value="rheto" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'rheto' ? 'selected' : '' ) ;
?>>Rhetorical</option>
                        <option value="satir" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'satir' ? 'selected' : '' ) ;
?>>Satirical</option>
                        <option value="senso" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'senso' ? 'selected' : '' ) ;
?>>Sensory</option>
                        <option value="simpl" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'simpl' ? 'selected' : '' ) ;
?>>Simple</option>
                        <option value="techn" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'techn' ? 'selected' : '' ) ;
?>>Technical</option>
                        <option value="theore" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'theore' ? 'selected' : '' ) ;
?>>Theoretical</option>
                        <option value="vivid" <?php 
echo  ( esc_html( $_wporg_writing_style ) == 'vivid' ? 'selected' : '' ) ;
?>>Vivid</option>

                        <?php 
?>
                            <!-- add text PREMIUM FEATURES -->
                            <option disabled> -- PREMIUM FEATURES -- </option>
                            <option disabled>Business</option>
                            <option disabled>Report</option>
                            <option disabled>Research</option>
                            <?php 
?>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_writing_tone"><?php 
echo  esc_html( __( "Tone", "wp-ai-content-generator" ) ) ;
?></label>
                    <select name="_wporg_writing_tone" id="wpai_writing_tone" class="wpaicg-input">
                        <option value="formal" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'formal' ? 'selected' : '' ) ;
?>>Formal</option>
                        <option value="asser" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'asser' ? 'selected' : '' ) ;
?>>Assertive</option>
                        <option value="authoritative" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'authoritative' ? 'selected' : '' ) ;
?>>Authoritative</option>
                        <option value="cheer" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'cheer' ? 'selected' : '' ) ;
?>>Cheerful</option>
                        <option value="confident" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'confident' ? 'selected' : '' ) ;
?>>Confident</option>
                        <option value="conve" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'conve' ? 'selected' : '' ) ;
?>>Conversational</option>
                        <option value="factual" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'factual' ? 'selected' : '' ) ;
?>>Factual</option>
                        <option value="friendly" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'friendly' ? 'selected' : '' ) ;
?>>Friendly</option>
                        <option value="humor" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'humor' ? 'selected' : '' ) ;
?>>Humorous</option>
                        <option value="informal" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'informal' ? 'selected' : '' ) ;
?>>Informal</option>
                        <option value="inspi" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'inspi' ? 'selected' : '' ) ;
?>>Inspirational</option>
                        <option value="neutr" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'neutr' ? 'selected' : '' ) ;
?>>Neutral</option>
                        <option value="nostalgic" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'nostalgic' ? 'selected' : '' ) ;
?>>Nostalgic</option>
                        <option value="polite" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'polite' ? 'selected' : '' ) ;
?>>Polite</option>
                        <option value="profe" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'profe' ? 'selected' : '' ) ;
?>>Professional</option>
                        <option value="romantic" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'romantic' ? 'selected' : '' ) ;
?>>Romantic</option>
                        <option value="sarca" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'sarca' ? 'selected' : '' ) ;
?>>Sarcastic</option>
                        <option value="scien" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'scien' ? 'selected' : '' ) ;
?>>Scientific</option>
                        <option value="sensit" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'sensit' ? 'selected' : '' ) ;
?>>Sensitive</option>
                        <option value="serious" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'serious' ? 'selected' : '' ) ;
?>>Serious</option>
                        <option value="sincere" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'sincere' ? 'selected' : '' ) ;
?>>Sincere</option>
                        <option value="skept" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'skept' ? 'selected' : '' ) ;
?>>Skeptical</option>
                        <option value="suspenseful" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'suspenseful' ? 'selected' : '' ) ;
?>>Suspenseful</option>
                        <option value="sympathetic" <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'sympathetic' ? 'selected' : '' ) ;
?>>Sympathetic</option>
                        <?php 
?>
                            <!-- add text PREMIUM FEATURES -->
                            <option disabled> -- PREMIUM FEATURES -- </option>
                            <option value="curio" disabled <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'curio' ? 'selected' : '' ) ;
?>>Curious</option>
                            <option value="disap" disabled <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'disap' ? 'selected' : '' ) ;
?>>Disappointed</option>
                            <option value="encou" disabled <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'encou' ? 'selected' : '' ) ;
?>>Encouraging</option>
                            <option value="optim" disabled <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'optim' ? 'selected' : '' ) ;
?>>Optimistic</option>
                            <option value="surpr" disabled <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'surpr' ? 'selected' : '' ) ;
?>>Surprised</option>
                            <option value="worry" disabled <?php 
echo  ( esc_html( $_wporg_writing_tone ) == 'worry' ? 'selected' : '' ) ;
?>>Worried</option>
                            <?php 
?>


                    </select>
                </div>
            </div>
        </div>
        <div class="wpaicg-collapse">
            <div class="wpaicg-collapse-title"><span>+</span>Headings</div>
            <div class="wpaicg-collapse-content">
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_number_of_heading"><?php 
echo  esc_html( __( "Headings", "wp-ai-content-generator" ) ) ;
?></label>
                    <select id="wpai_number_of_heading" name="_wporg_number_of_heading">
                        <?php 
for ( $i = 1 ;  $i < 16 ;  $i++ ) {
    echo  '<option' . (( $_wporg_number_of_heading == $i ? ' selected' : '' )) . ' value="' . $i . '">' . $i . '</option>' ;
}
?>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_heading_tag"><?php 
echo  esc_html( __( "Heading Tag", "wp-ai-content-generator" ) ) ;
?></label>
                    <select name="_wporg_heading_tag" id="wpai_heading_tag" class="wpaicg-input">
                        <option value="h1" <?php 
echo  ( esc_html( $_wporg_heading_tag ) == 'h1' ? 'selected' : '' ) ;
?>>h1</option>
                        <option value="h2" <?php 
echo  ( esc_html( $_wporg_heading_tag ) == 'h2' ? 'selected' : '' ) ;
?>>h2</option>
                        <option value="h3" <?php 
echo  ( esc_html( $_wporg_heading_tag ) == 'h3' ? 'selected' : '' ) ;
?>>h3</option>
                        <option value="h4" <?php 
echo  ( esc_html( $_wporg_heading_tag ) == 'h4' ? 'selected' : '' ) ;
?>>h4</option>
                        <option value="h5" <?php 
echo  ( esc_html( $_wporg_heading_tag ) == 'h5' ? 'selected' : '' ) ;
?>>h5</option>
                        <option value="h6" <?php 
echo  ( esc_html( $_wporg_heading_tag ) == 'h6' ? 'selected' : '' ) ;
?>>h6</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_modify_headings2"><?php 
echo  esc_html( __( "Modify Headings?", "wp-ai-content-generator" ) ) ;
?></label>
                    <input type="checkbox" id="wpai_modify_headings2" name="_wporg_modify_headings2" class="wpai-content-title-input"
                           value="<?php 
echo  ( esc_html( $_wporg_modify_headings ) == 1 ? "1" : "0" ) ;
?>" <?php 
echo  ( esc_html( $_wporg_modify_headings ) == 1 ? "checked" : "" ) ;
?> />

                    <input type="hidden" id="wpai_modify_headings" name="_wporg_modify_headings" class="wpai-content-title-input" value="<?php 
echo  ( esc_html( $_wporg_modify_headings ) == 1 ? "1" : "0" ) ;
?>" />

                    <input type="hidden" id="hfHeadings" name="hfHeadings" />
                    <input type="hidden" id="is_generate_continue" name="is_generate_continue" value='0' />
                </div>
            </div>
        </div>
        <div class="wpaicg-collapse">
            <div class="wpaicg-collapse-title"><span>+</span>Keywords</div>
            <div class="wpaicg-collapse-content">
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="label_keywords"><?php 
echo  esc_html( __( "Add Keywords?", "wp-ai-content-generator" ) ) ;
?></label>
                    <?php 
?>
                        <input type="text" class="wpcgai_input" disabled placeholder="Available in Pro">
                        <?php 
?>
                    <p class="wpaicg-help-text"><?php 
echo  esc_html( __( '(Use comma to seperate keywords)', 'wp-ai-content-generator' ) ) ;
?></p>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="label_words_to_avoid"><?php 
echo  esc_html( __( "Keywords to Avoid?", "wp-ai-content-generator" ) ) ;
?></label>
                    <?php 
?>
                        <input type="text" class="wpcgai_input" disabled placeholder="Available in Pro">
                        <?php 
?>
                    <p class="wpaicg-help-text"><?php 
echo  esc_html( __( '(Use comma to seperate keywords)', 'wp-ai-content-generator' ) ) ;
?></p>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="label_keywords_bold"><?php 
echo  esc_html( __( "Make Keywords Bold?", "wp-ai-content-generator" ) ) ;
?></label>
                    <?php 
?>
                        <input type="checkbox" disabled id="wpai_add_keywords_bold" class="wpai-content-title-input" name="_wporg_add_keywords_bold" value="0">Available in Pro
                        <?php 
?>
                </div>
            </div>
        </div>
        <div class="wpaicg-collapse">
            <div class="wpaicg-collapse-title"><span>+</span>Image</div>
            <div class="wpaicg-collapse-content">
                <?php 
$wpaicg_pexels_api = get_option( 'wpaicg_pexels_api', '' );
$wpaicg_image_source = get_option( 'wpaicg_image_source', '' );
$wpaicg_featured_image_source = get_option( 'wpaicg_featured_image_source', '' );
$wpaicg_pexels_orientation = get_option( 'wpaicg_pexels_orientation', '' );
$wpaicg_pexels_size = get_option( 'wpaicg_pexels_size', '' );
?>
                <div class="mb-5">
                    <label class="wpaicg-form-label">Image</label>
                    <select class="regular-text" id="wpaicg_image_source" name="wpaicg_image_source" >
                        <option value="">None</option>
                        <option<?php 
echo  ( $wpaicg_image_source == 'dalle' || $wpaicg_image_source == 'pexels' && empty($wpaicg_pexels_api) ? ' selected' : '' ) ;
?> value="dalle">DALL-E</option>
                        <option<?php 
echo  ( !empty($wpaicg_pexels_api) && $wpaicg_image_source == 'pexels' ? ' selected' : '' ) ;
echo  ( empty($wpaicg_pexels_api) ? ' disabled' : '' ) ;
?> value="pexels">Pexels</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label">Featured Image</label>
                    <select class="regular-text" id="wpaicg_featured_image_source" name="wpaicg_featured_image_source" >
                        <option value="">None</option>
                        <option<?php 
echo  ( $wpaicg_featured_image_source == 'dalle' || $wpaicg_featured_image_source == 'pexels' && empty($wpaicg_pexels_api) ? ' selected' : '' ) ;
?> value="dalle">DALL-E</option>
                        <option<?php 
echo  ( !empty($wpaicg_pexels_api) && $wpaicg_featured_image_source == 'pexels' ? ' selected' : '' ) ;
echo  ( empty($wpaicg_pexels_api) ? ' disabled' : '' ) ;
?> value="pexels">Pexels</option>
                    </select>
                </div>
                <p><b><u>DALL-E</u></b></p>
                <div class="mb-5">
                    <?php 
$_wporg_img_size = $result->img_size;
?>
                    <label class="wpaicg-form-label" for="_wporg_img_size"><?php 
echo  esc_html( __( "Image Size", "wp-ai-content-generator" ) ) ;
?></label>
                    <select class="regular-text" id="_wporg_img_size" name="_wporg_img_size" >
                        <option value="256x256"<?php 
echo  ( esc_html( $_wporg_img_size ) == '256x256' ? ' selected' : '' ) ;
?>>Small (256x256)</option>
                        <option value="512x512"<?php 
echo  ( esc_html( $_wporg_img_size ) == '512x512' ? ' selected' : '' ) ;
?>>Medium (512x512)</option>
                        <option value="1024x1024"<?php 
echo  ( esc_html( $_wporg_img_size ) == '1024x102' ? ' selected' : '' ) ;
?>>Big (1024x1024)</option>
                    </select>
                </div>
                <div class="mb-5">
                    <?php 
$_wporg_img_style = get_option( '_wpaicg_image_style', '' );
?>
                    <label class="wpaicg-form-label" for="_wporg_img_style"><?php 
echo  esc_html( __( "Image Style", "wp-ai-content-generator" ) ) ;
?></label>
                    <select class="regular-text" id="_wporg_img_style" name="_wporg_img_style" >
                        <option value="">None</option>
                        <option<?php 
echo  ( esc_html( $_wporg_img_style ) == 'abstract' ? ' selected' : '' ) ;
?> value="abstract">Abstract</option>
                        <option<?php 
echo  ( esc_html( $_wporg_img_style ) == 'modern' ? ' selected' : '' ) ;
?> value="modern">Modern</option>
                        <option<?php 
echo  ( esc_html( $_wporg_img_style ) == 'impressionist' ? ' selected' : '' ) ;
?> value="impressionist">Impressionist</option>
                        <option<?php 
echo  ( esc_html( $_wporg_img_style ) == 'popart' ? ' selected' : '' ) ;
?> value="popart">Pop Art</option>
                        <option<?php 
echo  ( esc_html( $_wporg_img_style ) == 'cubism' ? ' selected' : '' ) ;
?> value="cubism">Cubism</option>
                        <option<?php 
echo  ( esc_html( $_wporg_img_style ) == 'surrealism' ? ' selected' : '' ) ;
?> value="surrealism">Surrealism</option>
                        <option<?php 
echo  ( esc_html( $_wporg_img_style ) == 'contemporary' ? ' selected' : '' ) ;
?> value="contemporary">Contemporary</option>
                        <option<?php 
echo  ( esc_html( $_wporg_img_style ) == 'cantasy' ? ' selected' : '' ) ;
?> value="cantasy">Fantasy</option>
                        <option<?php 
echo  ( esc_html( $_wporg_img_style ) == 'graffiti' ? ' selected' : '' ) ;
?> value="graffiti">Graffiti</option>
                    </select>
                </div>
                <p><u><b>Pexels</b></u></p>
                <div class="mb-5">
                    <label class="wpaicg-form-label">Orientation</label>
                    <select class="regular-text" id="wpaicg_pexels_orientation" name="wpaicg_pexels_orientation" >
                        <option value="">None</option>
                        <option<?php 
echo  ( $wpaicg_pexels_orientation == 'landscape' ? ' selected' : '' ) ;
?> value="landscape">Landscape</option>
                        <option<?php 
echo  ( $wpaicg_pexels_orientation == 'portrait' ? ' selected' : '' ) ;
?> value="portrait">Portrait</option>
                        <option<?php 
echo  ( $wpaicg_pexels_orientation == 'square' ? ' selected' : '' ) ;
?> value="square">Square</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label">Size</label>
                    <select class="regular-text" id="wpaicg_pexels_size" name="wpaicg_pexels_size" >
                        <option value="">None</option>
                        <option<?php 
echo  ( $wpaicg_pexels_size == 'large' ? ' selected' : '' ) ;
?> value="large">Large</option>
                        <option<?php 
echo  ( $wpaicg_pexels_size == 'medium' ? ' selected' : '' ) ;
?> value="medium">Medium</option>
                        <option<?php 
echo  ( $wpaicg_pexels_size == 'small' ? ' selected' : '' ) ;
?> value="small">Small</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="wpaicg-collapse">
            <div class="wpaicg-collapse-title"><span>+</span>Additional Content</div>
            <div class="wpaicg-collapse-content">
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_add_tagline2"><?php 
echo  esc_html( __( "Add Tagline?", "wp-ai-content-generator" ) ) ;
?></label>
                    <input type="checkbox" id="wpai_add_tagline2"  name="_wporg_add_tagline2" class="wpai-content-title-input"
                           value="<?php 
echo  ( esc_html( $_wporg_add_tagline ) == 1 ? "1" : "0" ) ;
?>" <?php 
echo  ( esc_html( $_wporg_add_tagline ) == 1 ? "checked" : "" ) ;
?> />
                    <input type="hidden" id="wpai_add_tagline" name="_wporg_add_tagline" class="wpai-content-title-input" value="<?php 
echo  ( esc_html( $_wporg_add_tagline ) == 1 ? "1" : "0" ) ;
?>" />
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_add_intro2"><?php 
echo  esc_html( __( "Add Introduction?", "wp-ai-content-generator" ) ) ;
?></label>
                    <input type="checkbox" id="wpai_add_intro2" name="_wporg_add_intro2" class="wpai-content-title-input"
                           value="<?php 
echo  ( esc_html( $_wporg_add_intro ) == 1 ? "1" : "0" ) ;
?>" <?php 
echo  ( esc_html( $_wporg_add_intro ) == 1 ? "checked" : "" ) ;
?> />
                    <input type="hidden" id="wpai_add_intro" name="_wporg_add_intro" class="wpai-content-title-input"
                           value="<?php 
echo  ( esc_html( $_wporg_add_intro ) == 1 ? "1" : "0" ) ;
?>" />
                </div>
                <!-- wpaicg_intro_title_tag -->
                <div class="mb-5">
                    <?php 
$wpaicg_intro_title_tag = get_option( 'wpaicg_intro_title_tag', 'h2' );
?>
                    <label class="wpaicg-form-label" for="wpaicg_intro_title_tag"><?php 
echo  esc_html( __( "Intro Title Tag", "wp-ai-content-generator" ) ) ;
?></label>
                    <select name="wpaicg_intro_title_tag" id="wpaicg_intro_title_tag">
                        <option value="h1" <?php 
echo  ( esc_html( $wpaicg_intro_title_tag ) == 'h1' ? 'selected' : '' ) ;
?>>h1</option>
                        <option value="h2" <?php 
echo  ( esc_html( $wpaicg_intro_title_tag ) == 'h2' ? 'selected' : '' ) ;
?>>h2</option>
                        <option value="h3" <?php 
echo  ( esc_html( $wpaicg_intro_title_tag ) == 'h3' ? 'selected' : '' ) ;
?>>h3</option>
                        <option value="h4" <?php 
echo  ( esc_html( $wpaicg_intro_title_tag ) == 'h4' ? 'selected' : '' ) ;
?>>h4</option>
                        <option value="h5" <?php 
echo  ( esc_html( $wpaicg_intro_title_tag ) == 'h5' ? 'selected' : '' ) ;
?>>h5</option>
                        <option value="h6" <?php 
echo  ( esc_html( $wpaicg_intro_title_tag ) == 'h6' ? 'selected' : '' ) ;
?>>h6</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_add_conclusion2"><?php 
echo  esc_html( __( "Add Conclusion?", "wp-ai-content-generator" ) ) ;
?></label>
                    <input type="checkbox" id="wpai_add_conclusion2" name="_wporg_add_conclusion2" class="wpai-content-title-input"
                           value="<?php 
echo  ( esc_html( $_wporg_add_conclusion ) == 1 ? "1" : "0" ) ;
?>" <?php 
echo  ( esc_html( $_wporg_add_conclusion ) == 1 ? "checked" : "" ) ;
?> />
                    <input type="hidden" id="wpai_add_conclusion" name="_wporg_add_conclusion" class="wpai-content-title-input" value="<?php 
echo  ( esc_html( $_wporg_add_conclusion ) == 1 ? "1" : "0" ) ;
?>" />
                </div>
                <!-- wpaicg_conclusion_title_tag -->
                <div class="mb-5">
                    <?php 
$wpaicg_conclusion_title_tag = get_option( 'wpaicg_conclusion_title_tag', 'h2' );
?>
                    <label class="wpaicg-form-label" for="wpaicg_conclusion_title_tag"><?php 
echo  esc_html( __( "Conclusion Title Tag", "wp-ai-content-generator" ) ) ;
?></label>
                    <select name="wpaicg_conclusion_title_tag" id="wpaicg_conclusion_title_tag">
                        <option value="h1" <?php 
echo  ( esc_html( $wpaicg_conclusion_title_tag ) == 'h1' ? 'selected' : '' ) ;
?>>h1</option>
                        <option value="h2" <?php 
echo  ( esc_html( $wpaicg_conclusion_title_tag ) == 'h2' ? 'selected' : '' ) ;
?>>h2</option>
                        <option value="h3" <?php 
echo  ( esc_html( $wpaicg_conclusion_title_tag ) == 'h3' ? 'selected' : '' ) ;
?>>h3</option>
                        <option value="h4" <?php 
echo  ( esc_html( $wpaicg_conclusion_title_tag ) == 'h4' ? 'selected' : '' ) ;
?>>h4</option>
                        <option value="h5" <?php 
echo  ( esc_html( $wpaicg_conclusion_title_tag ) == 'h5' ? 'selected' : '' ) ;
?>>h5</option>
                        <option value="h6" <?php 
echo  ( esc_html( $wpaicg_conclusion_title_tag ) == 'h6' ? 'selected' : '' ) ;
?>>h6</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="label_faq"><?php 
echo  esc_html( __( "Add Q&A?", "wp-ai-content-generator" ) ) ;
?></label>
                    <?php 
?>
                        <input type="checkbox" value="0" disabled>Available in Pro
                        <?php 
?>
                </div>
                <div class="mb-5">
                    <?php 
$wpaicg_toc = get_option( 'wpaicg_toc', false );
?>
                    <label class="wpaicg-form-label" for="wpaicg_toc"><?php 
echo  esc_html( __( "Add Table of Contents?", "wp-ai-content-generator" ) ) ;
?></label>
                    <input<?php 
echo  ( $wpaicg_toc ? ' checked' : '' ) ;
?> type="checkbox" value="1" name="wpaicg_toc" id="wpaicg_toc">
                </div>
                <div class="mb-5">
                    <?php 
$wpaicg_toc_title = get_option( 'wpaicg_toc_title', 'Table of Contents' );
?>
                    <label class="wpaicg-form-label" for="wpaicg_toc_title"><?php 
echo  esc_html( __( "ToC Title", "wp-ai-content-generator" ) ) ;
?></label>
                    <input type="text" class="regular-text" value="<?php 
echo  esc_html( $wpaicg_toc_title ) ;
?>" name="wpaicg_toc_title" id="wpaicg_toc_title">
                </div>
                <div class="mb-5">
                    <?php 
// $wpaicg_toc_title_tag
$wpaicg_toc_title_tag = get_option( 'wpaicg_toc_title_tag', 'h2' );
?>
                    <label class="wpaicg-form-label" for="wpaicg_toc_title_tag"><?php 
echo  esc_html( __( "ToC Title Tag", "wp-ai-content-generator" ) ) ;
?></label>
                    <select name="wpaicg_toc_title_tag" id="wpaicg_toc_title_tag">
                        <option value="h1" <?php 
echo  ( esc_html( $wpaicg_toc_title_tag ) == 'h1' ? 'selected' : '' ) ;
?>>h1</option>
                        <option value="h2" <?php 
echo  ( esc_html( $wpaicg_toc_title_tag ) == 'h2' ? 'selected' : '' ) ;
?>>h2</option>
                        <option value="h3" <?php 
echo  ( esc_html( $wpaicg_toc_title_tag ) == 'h3' ? 'selected' : '' ) ;
?>>h3</option>
                        <option value="h4" <?php 
echo  ( esc_html( $wpaicg_toc_title_tag ) == 'h4' ? 'selected' : '' ) ;
?>>h4</option>
                        <option value="h5" <?php 
echo  ( esc_html( $wpaicg_toc_title_tag ) == 'h5' ? 'selected' : '' ) ;
?>>h5</option>
                        <option value="h6" <?php 
echo  ( esc_html( $wpaicg_toc_title_tag ) == 'h6' ? 'selected' : '' ) ;
?>>h6</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="wpaicg-collapse">
            <div class="wpaicg-collapse-title"><span>+</span>Links</div>
            <div class="wpaicg-collapse-content">
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_anchor_text"><?php 
echo  esc_html( __( "Anchor Text?", "wp-ai-content-generator" ) ) ;
?></label>
                    <input type="text" id="wpai_anchor_text" placeholder="e.g. battery life" class="wpaicg-input" name="_wporg_anchor_text" value="<?php 
echo  esc_html( $_wporg_anchor_text ) ;
?>">
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_target_url"><?php 
echo  esc_html( __( "Target URL?", "wp-ai-content-generator" ) ) ;
?></label>
                    <input type="url" id="wpai_target_url" placeholder="https://..." class="wpaicg-input" name="_wporg_target_url" value="<?php 
echo  esc_html( $_wporg_target_url ) ;
?>">
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_target_url_cta"><?php 
echo  esc_html( __( "Add Call-to-Action?", "wp-ai-content-generator" ) ) ;
?></label>
                    <input type="url" id="wpai_target_url_cta" placeholder="https://..." class="wpaicg-input" name="_wporg_target_url_cta" value="<?php 
echo  esc_html( $_wporg_target_url_cta ) ;
?>">
                    <p class="wpaicg-help-text"><?php 
echo  esc_html( __( 'Enter target URL.', 'wp-ai-content-generator' ) ) ;
?></p>
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpai_cta_pos"><?php 
echo  esc_html( __( "CTA Position?", "wp-ai-content-generator" ) ) ;
?></label>
                    <select class="wpaicg-input" name="_wporg_cta_pos" id="wpai_cta_pos">
                        <option value="beg" <?php 
echo  ( esc_html( $_wporg_cta_pos ) == 'beg' ? 'selected' : '' ) ;
?>>Beginning</option>
                        <option value="end" <?php 
echo  ( esc_html( $_wporg_cta_pos ) == 'end' ? 'selected' : '' ) ;
?>>End</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="wpaicg-collapse">
            <div class="wpaicg-collapse-title"><span>+</span>SEO</div>
            <div class="wpaicg-collapse-content">
                <div class="mb-5">
                    <?php 
$_wpaicg_seo_meta_desc = get_option( '_wpaicg_seo_meta_desc', false );
?>
                    <label class="wpaicg-form-label" for="wpaicg_seo_meta_desc"><?php 
echo  esc_html( __( "Meta Description?", "wp-ai-content-generator" ) ) ;
?></label>
                    <input<?php 
echo  ( $_wpaicg_seo_meta_desc ? ' checked' : '' ) ;
?> type="checkbox" name="wpaicg_seo_meta_desc" id="wpaicg_seo_meta_desc" class="wpai-content-title-input" value="1" />
                </div>
                <div class="mb-5">
                    <label class="wpaicg-form-label" for="wpaicg_seo_meta_desc"><?php 
echo  esc_html( __( "Tags", "wp-ai-content-generator" ) ) ;
?></label>
                    <input style="width: 100%;" type="text" name="wpaicg_post_tags" id="wpaicg_post_tags" class="wpcgai_input" value="" />
                    <p class="wpaicg-help-text">(Use comma to seperate tags)</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function ($){
        $('.wpaicg-collapse-title').click(function (){
            if(!$(this).hasClass('wpaicg-collapse-active')){
                $('.wpaicg-collapse').removeClass('wpaicg-collapse-active');
                $('.wpaicg-collapse-title span').html('+');
                $(this).find('span').html('-');
                $(this).parent().addClass('wpaicg-collapse-active');
            }
        })
    })
</script>
