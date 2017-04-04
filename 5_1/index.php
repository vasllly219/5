<?php
require __DIR__ . '/vendor/autoload.php';
error_reporting(0);
$adress = isset($_GET["adress"]) ? (string)$_GET["adress"] : '';
$get = isset($_GET["id"]) ? (int)$_GET["id"] : false;
$api = new \Yandex\Geo\Api();
$api->setQuery($adress);
// Настройка фильтров
$api
    ->setLimit(1) // кол-во результатов
    ->setLang(\Yandex\Geo\Api::LANG_US) // локаль ответа
    ->load();
$response = $api->getResponse();
$data = [];
// Список найденных точек
$collection = $response->getList();
foreach ($collection as $item) {
    $data[] = ["Latitude" => $item->getLatitude(), "Longitude" => $item->getLongitude()]; // широта
}
$flug = is_int($get) && $get < count($data) && $get >= 0;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Карта</title>
    <?php if ($flug): ?>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        var moscow_map;
        ymaps.ready(function(){
            moscow_map = new ymaps.Map("map", {
                center: [<?= $data[0]['Latitude'] . ', ' .  $data[0]['Longitude'] ?>],
                zoom: 15
            });
        });
    </script>
    <?php endif ?>
</head>
<body>
    <style>
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        table td, table th {
            border: 1px solid #ccc;
            padding: 5px;
        }
        table th {
            background: #eee;
        }
    </style>
    <h1></h1>
    <div>
        <form method="GET">
            <input type="text" name="adress" placeholder="Адрес" value="<?= $adress ?>" />
            <input type="submit" value="Найти" />
        </form>
    </div>
    </br>
    <?php if ($adress !== ''): ?>
    <table>
    <tr>
        <th><?= $adress ?></th>
    </tr>
    <tr>
        <?php foreach ($data as $id => $item): ?>
        <td><a href='?adress=<?= $adress ?>&id=<?= $id ?>'><?= $item['Latitude'] . ', ' .  $item['Longitude'] ?></a></td>
        <?php endforeach ?>
    </tr>
    </table>
    <?php endif ?>
    <?php if ($flug): ?>
    </br>
    <div id="map" style="width:400px; height:300px"></div>
    <?php endif ?>
</body>
</html>
