<?php
/*
Plugin Name: Meta Tester
Description: A simple check to see if page titles and descriptions match what is expected.
Version: 1.1
Author: https://github.com/kirilkirkov
*/

add_action('admin_menu', function() {
    add_menu_page('Meta Tester', 'Meta Tester', 'manage_options', 'meta-tester', 'meta_tester_page');
});

function meta_tester_page() {
    $saved_data = get_option('meta_tester_data', '');

    if (is_string($saved_data)) {
        $saved_data = maybe_unserialize($saved_data);
    }

    $meta_data = isset($saved_data['text']) ? $saved_data['text'] : '';
    ?>
    <div class="wrap">
        <h1>Meta Tester</h1>
        <form method="post">
            <?php wp_nonce_field('meta_tester_action'); ?>
            <textarea name="meta_data" rows="10" cols="100" placeholder="Enter one line at a time: URL ||| TITLE ||| DESCRIPTION"><?php echo esc_textarea($meta_data); ?></textarea><br>
            <input type="submit" name="save_meta" class="button" value="💾 Save">
            <input type="submit" name="check_meta" class="button button-primary" value="🔍 Check">
        </form>

        <?php
        if (isset($_POST['save_meta'])) {
            check_admin_referer('meta_tester_action');
            update_option('meta_tester_data', ['text' => wp_unslash($_POST['meta_data'])]);
            wp_redirect(admin_url('admin.php?page=meta-tester&saved=1'));
            exit;
        }

        if (isset($_POST['check_meta'])) {
            check_admin_referer('meta_tester_action');
            
            $lines = explode("\n", trim($_POST['meta_data']));
            echo "<h2>Results:</h2><table class='widefat striped'><thead><tr><th>URL</th><th>Title</th><th>Description</th><th>Status</th></tr></thead><tbody>";

            foreach ($lines as $line) {
                $parts = array_map('trim', explode('|||', $line));
                if (count($parts) < 3) continue;

                list($url, $expected_title, $expected_desc) = $parts;

                $response = wp_remote_get($url);
                if (is_wp_error($response)) {
                    echo "<tr><td colspan='4'>❌ Could not retrieve $url</td></tr>";
                    continue;
                }

                $html = wp_remote_retrieve_body($response);

                preg_match('/<title>(.*?)<\/title>/si', $html, $title_match);
                preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/si', $html, $desc_match);

                $found_title = isset($title_match[1]) ? trim($title_match[1]) : '';
                $found_desc = isset($desc_match[1]) ? trim($desc_match[1]) : '';

                $ok_title = ($found_title === $expected_title);
                $ok_desc = ($found_desc === $expected_desc);

                echo "<tr>
                    <td><a href='$url' target='_blank'>$url</a></td>
                    <td>".esc_html($found_title)."</td>
                    <td>".esc_html($found_desc)."</td>
                    <td>".
                        ($ok_title && $ok_desc
                            ? "<span style='color:green;'>✅ OK</span>"
                            : "<span style='color:red;'>❌ ".(!$ok_title ? "Title " : "").(!$ok_desc ? "Description" : "")."</span>"
                        ).
                    "</td>
                </tr>";
            }

            echo "</tbody></table>";
        }
        ?>
    </div>
    <?php
}
