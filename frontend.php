<?php
if (!class_exists('CreateAdminPage')):

class CreateAdminPage {
  public function __construct()
  {
    if (is_admin()) {
      add_action('admin_menu', [$this, 'admin_menu']);
      add_action('admin_enqueue_scripts', [$this, 'include_home_resources']);
    }
  }

  // メインメニュー
  public function admin_menu()
  {
    add_menu_page(
      'sample', /* ページタイトル*/
      'sample', /* メニュータイトル */
      'manage_options', /* 権限 */
      'sample-plugin', /* ページを開いたときのurl */
      [$this, 'home_page'], /* メニューに紐づく画面を描画するcallback関数 */
      'dashicons-media-text', /* アイコン */
      3, /* 表示位置の優先度 */
    );
    add_submenu_page(
      'sample-plugin', /* Parent slug */
      'Nội dung', /* Page title */
      'Nội dung', /* Menu title */
      'manage_options', /* Capability */
      'sample-plugin-content', /* Menu slug */
      [$this, 'home_page'] /* Callback function to render the submenu page */
  );

  // Thêm submenu "Tạo"
  add_submenu_page(
      'sample-plugin', /* Parent slug */
      'Tạo', /* Page title */
      'Tạo', /* Menu title */
      'manage_options', /* Capability */
      'sample-plugin-create', /* Menu slug */
      [$this, 'home_page'] /* Callback function to render the submenu page */
  );
  }

  // ホーム画面で使用するCSS・JSを読み込ませる
  public function include_home_resources()
  {
    $plugin_url = plugin_dir_url(__FILE__);    
     if (is_admin() && $GLOBALS['pagenow'] === 'admin.php') {
      wp_enqueue_style('sample-plugin-css', $plugin_url . 'dist/index.css', [], wp_rand());
         wp_enqueue_script('sample-plugin-js', $plugin_url . 'dist/index.js', [], wp_rand(), true);
    }

  }

  // ホーム画面
  public function home_page()
  {
    echo '<div id="root"></div>';
  }
}

new CreateAdminPage();

endif;