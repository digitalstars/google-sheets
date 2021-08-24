## Предисловие
Библиотека для удобной работы с гугл таблицами с использованием Service Account
Библиотека имеет зависимость от https://github.com/googleapis/google-api-php-client

## Установка
`composer require digitalstars/google-sheets`
> У библиотеки есть [баг(issue)](https://github.com/googleapis/google-api-php-client/issues/1893), что с первого раза не отрабатывает какой-то очищающий скрипт. Поэтому команду установки нужно выполнить 2 РАЗА!!, иначе не появится `vendor/autoload.php`

## Перед началом работы
1) [Вот по этому гайду](https://pocketadmin.tech/ru/%D1%80%D0%B0%D0%B1%D0%BE%D1%82%D0%B0-%D1%81-4-%D0%B2%D0%B5%D1%80%D1%81%D0%B8%D0%B5%D0%B9-api-google-%D1%82%D0%B0%D0%B1%D0%BB%D0%B8%D1%86%D1%8B-%D0%BD%D0%B0-php/) создаете .json конфиг и почту сервисного аккаунта
```php
$spreadsheet_id = '1FpDbHUknChjWzioeTrddMur-d_tSl7E_-tKCqn9xW6o';
$config_path = 'fire-322212-c5306b491ecc.json';
```
2) Заходите в гугл таблицу и выдаете сервисной почте права редактора

## Примеры работы

### Подключение
```php
<?php
require_once 'vendor/autoload.php';
use DigitalStars\Sheets\DSheets;

$spreadsheet_id = '1FpDbHUknChjWzioeTrddMur-d_tSl7E_-tKCqn9xW6o';
$config_path = 'fire-322212-c5306b491ecc.json';

$sheet = DSheets::create($spreadsheet_id, $config_path)->setSheet('Лист');
```

### Выгрузка данных
```php
$data = $sheet->get(); //выгрузит все данные в листе
print_r($data)
// [
//   ['id', 'name', 'mail'],
//   ['1', 'name1', 'mail1'],
//   ['2', 'name1', 'mail2']
// ]

$data = $sheet->setSheet('Лист2')->get('A:A') //выгрузит весь столбец А из Лист2
print_r($data)
// [
//   ['id'],
//   ['1']
//   ['2']
// ]

$data = $sheet->get('A2:B3'); //выгрузит диапазон A2:B3
print_r($data)
// [
//   ['1', 'name1'],
//   ['2', 'name1']
// ]
```

### Добавление в конец листа
Этот метод добавляет данные в конец листа, где встречена пустота
```php
$sheet->append([['Имя', 'Фамилия', 'Возраст']]); //добавит в конец A по максимальной используемой строке всех букв
//Если C6-C8 пустые, то добавит в них. Иначе в самый конец A ориентируясь по максимальной используемой строке всех букв
$sheet->setSheet('Лист2')->append([['Имя', 'Фамилия', 'Возраст']], 'C6'); 
```

### Обновление/добавление данных
```php
$sheet->update([['Имя', 'Фамилия', 'Возраст']]); //добавит в A1-C1
$sheet->setSheet('Лист2')->update([['Имя', 'Фамилия', 'Возраст']], 'A3'); //добавит в A3-C3 даже если они заполнены
```

### Использование оригинального Google_Service_Sheets
```php
$service = $sheet->getService(); //получаем
$service->spreadsheets->...
$service->spreadsheets_sheets->...
$service->spreadsheets_values->...
$client = $service->getClient();
$sheet->setService($service); //устанавливаем обратно если надо
```