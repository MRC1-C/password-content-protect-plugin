<?php
// backend.php

// Tạo bảng tùy chỉnh trong cơ sở dữ liệu khi plugin được kích hoạt

// Đăng ký REST API endpoint cho custom data
add_action('rest_api_init', function () {
    register_rest_route('your-plugin/v1', '/content/', array(
        'methods' => 'POST',
        'callback' => 'create_custom_data_callback',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('your-plugin/v1', '/content/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'read_custom_data_callback',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));

    register_rest_route('your-plugin/v1', '/content/(?P<id>\d+)', array(
        'methods' => 'PUT',
        'callback' => 'update_custom_data_callback',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));

    register_rest_route('your-plugin/v1', '/content/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'delete_custom_data_callback',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        },
    ));

    register_rest_route('your-plugin/v1', '/content/', array(
        'methods' => 'GET',
        'callback' => 'get_all_custom_data_callback',
        'permission_callback' => '__return_true'
    ));
});

// Callback cho lấy tất cả dữ liệu
function get_all_custom_data_callback() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'content';

    $results = $wpdb->get_results("SELECT * FROM $table_name");

    $data = array();

    if ($results) {
        foreach ($results as $result) {
            $data[] = array(
                'id' => $result->id,
                'name' => $result->name,
                'password' => $result->password,
                'type' => $result->type,
                'url' => $result->url,
                'content_short' => $result->content_short,
                'content_long' => $result->content_long,
            );
        }

        return new WP_REST_Response($data, 200);
    } else {
        return new WP_REST_Response(['message' => 'No data found'], 404);
    }
}

// Callback cho tạo mới
function create_custom_data_callback($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'content';

    $name = sanitize_text_field($data['name']);
    $password = sanitize_text_field($data['password']);
    $type = sanitize_text_field($data['type']);
    $url = sanitize_text_field($data['url']);
    $content_short = sanitize_text_field($data['content_short']);
    $content_long = sanitize_text_field($data['content_long']);

    $wpdb->insert(
        $table_name,
        array(
            'name' => $name,
            'password' => $password,
            'type' => $type,
            'url' => $url,
            'content_short' => $content_short,
            'content_long' => $content_long,
        )
    );

    return new WP_REST_Response(['message' => 'Data created successfully'], 201);
}

// Callback cho đọc dữ liệu
function read_custom_data_callback($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'content';

    $post_id = absint($data['id']);
    $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $post_id));

    if ($result) {
        $response = [
            'id' => $result->id,
            'name' => $result->name,
            'password' => $result->password,
            'type' => $result->type,
            'url' => $result->url,
            'content_short' => $result->content_short,
            'content_long' => $result->content_long,
        ];
        return new WP_REST_Response($response, 200);
    } else {
        return new WP_REST_Response(['message' => 'Data not found'], 404);
    }
}

// Callback cho cập nhật
function update_custom_data_callback($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'content';

    $post_id = absint($data['id']);
    $name = sanitize_text_field($data['name']);
    $password = sanitize_text_field($data['password']);
    $type = sanitize_text_field($data['type']);
    $url = sanitize_text_field($data['url']);
    $content_short = sanitize_text_field($data['content_short']);
    $content_long = sanitize_text_field($data['content_long']);

    $wpdb->update(
        $table_name,
        array(
            'name' => $name,
            'password' => $password,
            'type' => $type,
            'url' => $url,
            'content_short' => $content_short,
            'content_long' => $content_long,
        ),
        array('id' => $post_id)
    );

    return new WP_REST_Response(['message' => 'Data updated successfully'], 200);
}

// Callback cho xoá
function delete_custom_data_callback($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'content';

    $post_id = absint($data['id']);

    if ($post_id > 0) {
        $result = $wpdb->delete($table_name, array('id' => $post_id));
        if ($result !== false) {
            return new WP_REST_Response(['message' => 'Data deleted successfully'], 200);
        }
    }

    return new WP_REST_Response(['message' => 'Error occurred or delete unsuccessful'], 500);
}