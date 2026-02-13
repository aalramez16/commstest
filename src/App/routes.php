<?php
declare(strict_types=1);

use Slim\App;
return function (App $app) {
    (require __DIR__ . '/Routes/core.php')($app);
};
