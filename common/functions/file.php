<?php

function scandir_recursively($directory) {
    $result = [];
    foreach (scandir($directory) as $fileName) {
        if (in_array($fileName, array('.', '..', '__MACOSX'))) {
            continue;
        }
        if (is_dir($directory . DIRECTORY_SEPARATOR . $fileName)) {
            $result = array_merge($result, array_map(function ($f) use ($fileName) {
                return $fileName . DIRECTORY_SEPARATOR . $f;
            }, scandir_recursively($directory . DIRECTORY_SEPARATOR . $fileName)));
        } else {
            $result[] = $fileName;
        }
    }
    return $result;
}
