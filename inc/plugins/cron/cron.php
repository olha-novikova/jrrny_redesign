<?php

function plc_get_cronjob($where = NULL, $limit = NULL)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_cron';
    $table_posts = $wpdb->prefix . 'posts AS p';

    $sql = "SELECT * ";
    $sql .= "FROM " . $table_name . " AS c ";
    $sql .= "LEFT JOIN " . $table_posts . " ON c.post_id=p.ID ";
    if ($where) {
        $sql .= "WHERE " . $where . " ";
    }
    if ($limit) {
        $sql .= "LIMIT 1 ";
        $result = $wpdb->get_row($sql);
    }
    else {
        $result = $wpdb->get_results($sql);
    }
    return $result;
}

function plc_save_cron($data, $id = NULL)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_cron';

    if ($id) {
        $wpdb->update($table_name, $data, array('id' => $id));
    }
    else {
        $wpdb->insert($table_name, $data);
    }
    $result = $wpdb->insert_id;

    return $result;
}

function plc_remove_cron($id)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'jrrny_cron';
    $wpdb->delete($table_name, array('id' => $id));
}

function ajax_plc_save_cron()
{
    header('Content-Type: application/json');

    $id = $_POST['id'];

    $post = $_POST['post'];
    $recurrence = $_POST['recurrence'];
    $date = $_POST['datetime'];

    $data = array(
        'post_id' => $post,
        'recurrence' => $recurrence,
        'date' => $date
    );
    plc_save_cron($data, $id);

    if ($id) {
        $response['msg'] = 'Your settings are updated';
    }
    else {
        $response['msg'] = 'Successfull added to cron job list';
        $response['status'] = 'ok';
    }

    echo json_encode($response);
    die();
}

add_action('wp_ajax_ajax_plc_save_cron', 'ajax_plc_save_cron');
add_action('wp_ajax_nopriv_ajax_plc_save_cron', 'ajax_plc_save_cron');

function ajax_plc_remove_cron()
{
    header('Content-Type: application/json');

    $id = $_POST['id'];
    if ($id) {
        plc_remove_cron($id);

        $response['msg'] = 'Successfull removed from cron job list';
        $response['status'] = 'ok';
    }
    else {
        $response['msg'] = 'This autosend can\'t be deleted';
    }

    echo json_encode($response);
    die();
}

add_action('wp_ajax_ajax_plc_remove_cron', 'ajax_plc_remove_cron');
add_action('wp_ajax_nopriv_ajax_plc_remove_cron', 'ajax_plc_remove_cron');

function plc_cron_create_db()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $views_cron_table = $wpdb->prefix . 'jrrny_cron';
    $posts_table = $wpdb->prefix . 'posts';

    $sql = "CREATE TABLE IF NOT EXISTS $views_cron_table ( ";
    $sql .= "id bigint(20) unsigned AUTO_INCREMENT, ";
    $sql .= "post_id bigint(20) unsigned NOT NULL, ";
    $sql .= "recurrence varchar(20) NULL, ";
    $sql .= "date date NOT NULL, ";
    $sql .= "PRIMARY KEY (id), ";
    $sql .= "KEY fk_post (post_id), ";

    $sql .= "CONSTRAINT " . $views_cron_table . "_ibfk_1  FOREIGN KEY (post_id) REFERENCES $posts_table(ID) ";
    $sql .= "ON DELETE CASCADE ";
    $sql .= "ON UPDATE CASCADE ";
    $sql .= ") $charset_collate;";

    $wpdb->get_results($sql);
}

//add_action('init', 'plc_cron_create_db');

function plc_check_cron()
{
    $now = date("Y-m-d");
    $cronjobs = plc_get_cronjob();

    if ($cronjobs) {
        foreach ($cronjobs as $cronjob) {
            $cron_date = date('Y-m-d', strtotime($cronjob->date));

            if ($cronjob->recurrence === 'once' && $now === $cron_date) {
                plc_send_newsletter($cronjob->post_id);
                plc_remove_cron($cronjob->id);
            }
            elseif ($cronjob->recurrence !== 'once') {
                if (plc_get_interval($cronjob->recurrence, $now, $cron_date)) {
                    plc_save_cron(array('date' => $now), $cronjob->id);
                    plc_send_newsletter($cronjob->post_id);
                }
                elseif ($now === $cron_date) {
                    plc_send_newsletter($cronjob->post_id);
                }
            }
        }
    }
}

function plc_get_interval($type, $now, $date)
{
    $datetime1 = new DateTime($date);
    $datetime2 = new DateTime($now);
    $interval = $datetime1->diff($datetime2);

    if ($type === 'weekly' && $interval->days % 7 == 0) {
        return true;
    }
    elseif ($type === 'monthly' && $interval->m > 0) {
        return true;
    }
    return false;
}

add_action('init', 'plc_check_cron');


if (!wp_next_scheduled('plc_check_cron')) {
    wp_schedule_event(strtotime('29-08-2016 04:00'), 'daily', 'plc_check_cron');
}
