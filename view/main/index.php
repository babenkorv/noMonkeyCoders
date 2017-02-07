<?php

\vendor\components\AssetManager::setAsset(
    $this->viewUniqueName, [
        'css' => [
            'indexPage.css'
        ],
        'js' => [

        ]
    ]
);

\vendor\components\AssetManager::register($this->viewUniqueName);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<h1>Rom4ik FW</h1>
</body>
</html>