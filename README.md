REQUIREMENTS
-------------------

```
1. MySQL >= 5.5
2. PHP >= 7.1 (Yii2)
```

INSTALL
-------------------
<p>STEP 1: download project files or run command</p>

```
git clone https://github.com/Siggard/utip_test.git
```

<p>STEP 2: install composer and run command from project directory</p>

```
composer update 
```

<p>STEP 3: create DB "utip" and run console command from root project directory</p>

```
yii migrate
```

<p>STEP 4: set document roots of your web server:</p>

```
/path/to/yii-application/web/  test.ru
```

<br />
<p><b>15 пункт ТЗ</b></p>
Выбрал Yii2 т.к. хорошо с ним знаком и многие рутинные вещи решены "из коробки". 
Поставил несколько полезных плагинов (через composer) для загрузки файлов и ресайза изображений.
