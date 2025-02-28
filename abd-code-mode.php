<?php
/**
 * Plugin Name: ABD Code Mode
 * Plugin URI:  https://wpdebugging.com/
 * Description: Adds a VS Code-style syntax highlighter with a copy button for code snippets.
 * Version:     1.0.2
 * Author:      Abdul Wajid
 * Author URI:  https://debugmasters.com/
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue Scripts & Styles
function abd_enqueue_code_mode_assets() {
    // Prism.js core and languages
    wp_enqueue_style('prism-css', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css');
    wp_enqueue_script('prism-js', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js', array(), null, true);
    wp_enqueue_script('prism-php', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js', array('prism-js'), null, true);
    wp_enqueue_script('prism-bash', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js', array('prism-js'), null, true);
    wp_enqueue_script('prism-js-lang', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js', array('prism-js'), null, true);
    wp_enqueue_script('prism-html', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js', array('prism-js'), null, true);
    wp_add_inline_script('prism-js', "document.addEventListener('DOMContentLoaded', function() {
        Prism.highlightAll();
    });");
    
    // Copy-to-Clipboard Script
    wp_add_inline_script('prism-js', "
        function abdCopyCode(element) {
            let codeBlock = element.closest('.abd-code-container').querySelector('code');
            navigator.clipboard.writeText(codeBlock.textContent).then(() => {
                element.innerHTML = '<i class=\"dashicons dashicons-yes\"></i> Copied!';
                setTimeout(() => element.innerHTML = '<i class=\"dashicons dashicons-clipboard\"></i> Copy', 2000);
            });
        }

        // Ensure Prism highlights dynamically loaded content
        document.addEventListener('DOMContentLoaded', function() {
            Prism.highlightAll();
        });
    ");
}
add_action('wp_enqueue_scripts', 'abd_enqueue_code_mode_assets');

// Custom CSS for styling
function abd_enqueue_code_mode_styles() {
    $custom_css = "
        .abd-code-container {
            position: relative;
            background: #252526;
            border-radius: 5px;
            padding: 10px;
            margin: 20px 0;
            overflow: auto;
        }
        .abd-code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #1e1e1e;
            padding: 5px 10px;
            font-size: 14px;
            color: #ccc;
            border-radius: 5px 5px 0 0;
        }
        .abd-copy-btn {
            cursor: pointer;
            display: flex;
            align-items: center;
            color: #ccc;
            font-size: 12px;
            padding: 2px 8px;
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
        }
        .abd-copy-btn i {
            margin-right: 5px;
        }
        .abd-copy-btn:hover {
            color: #fff;
            background: rgba(255,255,255,0.2);
        }
        pre {
            margin: 0;
            padding: 10px;
            background: #252526;
            border-radius: 0 0 5px 5px;
        }
        code {
            font-family: Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace;
            white-space: pre-wrap;
            display: block;
        }
    ";
    wp_add_inline_style('prism-css', $custom_css);
}
add_action('wp_enqueue_scripts', 'abd_enqueue_code_mode_styles');

// Add Settings Menu
function abd_code_mode_menu() {
    add_options_page('ABD Code Mode', 'ABD Code Mode', 'manage_options', 'abd-code-mode', 'abd_code_mode_settings_page');
}
add_action('admin_menu', 'abd_code_mode_menu');

// Create Settings Page
function abd_code_mode_settings_page() {
    ?>
    <div class="wrap">
        <h1>ABD Code Mode Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('abd_code_mode_options');
            do_settings_sections('abd-code-mode');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register Settings
function abd_register_code_mode_settings() {
    register_setting('abd_code_mode_options', 'abd_code_mode_enabled');
    add_settings_section('abd_code_mode_main', 'Main Settings', null, 'abd-code-mode');
    add_settings_field('abd_code_mode_toggle', 'Enable Code Mode', 'abd_code_mode_toggle_field', 'abd-code-mode', 'abd_code_mode_main');
}
add_action('admin_init', 'abd_register_code_mode_settings');

// Toggle Field
function abd_code_mode_toggle_field() {
    $enabled = get_option('abd_code_mode_enabled', 'yes');
    ?>
    <input type="checkbox" name="abd_code_mode_enabled" value="yes" <?php checked($enabled, 'yes'); ?> />
    Enable VS Code-style Code Mode
    <?php
}

// Shortcode for Code Blocks
function abd_code_shortcode($atts, $content = null) {
    if (!$content) return '';

    // Get shortcode attributes (default: bash)
    $atts = shortcode_atts(array(
        'lang' => 'bash',
    ), $atts, 'code');

    // Preserve line breaks properly
    $formatted_code = trim(htmlspecialchars_decode(strip_tags($content)));
    //$formatted_code = nl2br($formatted_code); // Convert newlines to <br>
    $formatted_code = preg_replace('/\r\n|\r|\n/', "\n", $formatted_code);

    return '<div class="abd-code-container">
                <div class="abd-code-header">
                    <span>Code (' . esc_html($atts['lang']) . ')</span>
                    <span class="abd-copy-btn" onclick="abdCopyCode(this)">
                        <i class="dashicons dashicons-clipboard"></i> Copy
                    </span>
                </div>
                <pre><code class="language-' . esc_attr($atts['lang']) . '">' . $formatted_code . '</code></pre>
            </div>';
}
add_shortcode('code', 'abd_code_shortcode');
