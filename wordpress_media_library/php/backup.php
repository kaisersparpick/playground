<?php

function out($msg, $color, $eol = true) {
    $colorCodes = ['cyan' => 36, 'red' => 31, 'green' => 32, 'yellow' => 33];
    echo "\r" . "\x1b[" . $colorCodes[$color] . 'm' . $msg . "\x1b[0m";
    if ($eol) echo PHP_EOL;
}

function download($file_name) {
    $dest_basedir = __DIR__ . DIRECTORY_SEPARATOR . 'backup';
    $xml = file_get_contents($file_name);
    preg_match_all('/<wp:attachment_url>(.+)<\/wp:attachment_url>/', $xml, $urls);
    $urls = $urls[1];

    foreach($urls as $u) {
        out('CHCK', 'cyan', false);
        echo " > $u";

        $dest_dir = $dest_basedir . str_replace(basename($u), '', parse_url($u)['path']);
        if (!is_dir($dest_dir)) mkdir($dest_dir, 0777, true);
        $dest_file = $dest_dir . DIRECTORY_SEPARATOR . basename($u);

        $remote_mod = strtotime(get_headers($u, 1)['Last-Modified']);
        $local_mod = is_file($dest_file) ? filemtime($dest_file) : 0;

        if ($remote_mod > $local_mod) {
            out('DOWN', 'green');
            $s = fopen($u, 'r');
            $d = fopen($dest_dir . DIRECTORY_SEPARATOR . basename($u), 'w+');
            stream_copy_to_stream($s, $d);
        }
        else {
            out('SKIP', 'yellow');
        }
    }
}

download('export.xml');
