<?php
define('IMAGE_SERVER_NAME', 'http://cdn1.qruto.com');

if (!empty($_POST['image_save'])) {
    $url = $_POST['url'];

    $ch = curl_init($_POST['url']);
    $fp = fopen('./uploads/' . $_POST['type'] . '/' . basename($_POST['url']), 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    echo json_encode(['action' => true, 'url' => IMAGE_SERVER_NAME . '/uploads/' . $_POST['type'] . '/' . basename($_POST['url'])]);
    exit;
}

if (!empty($_POST['image_remove'])) {
    unlink('./uploads/original/' . $_POST['original']);
    unlink('./uploads/thumb90/' . $_POST['thumb90']);
    unlink('./uploads/thumb160/' . $_POST['thumb160']);
    exit;
}