### Установка сервиса NCANode

В данной инструкции рассматривается установка приложения в качестве системной службы **systemd** (Debian, Ubuntu).

#### Загрузка приложения на сервер

Актуальная версия на момент реализации - 2.3.0.
Ссылка для скачивания - https://github.com/malikzh/NCANode/releases/download/v2.3.0/NCANode.zip

```shell
cd /tmp && wget https://github.com/malikzh/NCANode/releases/download/v2.3.0/NCANode.zip
mkdir ncanode
mv NCANode.zip ncanode/
cd ncanode && unzip NCANode.zip
rm NCANode.zip
mv ncanode /var/www/
```

Путь к приложению на сервере - `/var/www/ncanode`

#### Создание пользователя и группы и установка права

```shell
sudo groupadd -r appmgr
sudo useradd -r -s /bin/false -g appmgr jvmapps
sudo chown -R jvmapps:appmgr /var/www/ncanode
```

#### Создание системной службы

Необходимо создать файл `ncanode.service` командой

```shell
sudo nano /etc/systemd/system/ncanode.service
```

со следующим содержимым:

```shell
[Unit]
Description=NCANode Service

[Service]
WorkingDirectory=/var/www/ncanode
ExecStart=/usr/bin/java -Xms256m -Xmx512m -jar NCANode.jar
User=jvmapps
Type=simple
Restart=on-failure
RestartSec=10

[Install]
WantedBy=multi-user.target
```

#### Первичная инициализация и запуск

```shell
sudo systemctl daemon-reload
sudo systemctl start ncanode.service
```

#### Проверка статуса

```shell
systemctl status ncanode.service
```

В случае, если служба работает корректно, будет отображена соответствующая информация:

```shell
● ncanode.service - NCANode Service
Loaded: loaded (/etc/systemd/system/ncanode.service; disabled; vendor preset: enabled)
Active: active (running) since Tue 2021-07-06 12:01:56 MSK; 22h ago
Main PID: 188440 (java)
Tasks: 14 (limit: 2248)
Memory: 596.7M
CGroup: /system.slice/ncanode.service
└─188440 /usr/bin/java -Xms256m -Xmx512m -jar NCANode.jar

Jul 06 13:02:26 546691-monitoring.tmweb.ru java[188440]: CRL generation 810CACC7D97B1D1CAA9E1864C4C49A0A0DA4612E.crl memory usage: {"totalFree":"62MB","max":"494MB","free":"62MB","allocated":"494MB"}
Jul 06 13:02:27 546691-monitoring.tmweb.ru java[188440]: CRL generation 219B41DCE04EDEEF359B008652D8D99879142D8B.crl memory usage: {"totalFree":"149MB","max":"494MB","free":"149MB","allocated":"494MB"}
Jul 07 10:24:39 546691-monitoring.tmweb.ru java[188440]: Downloading CRL from: http://crl.pki.gov.kz/nca_rsa.crl ...
Jul 07 10:24:43 546691-monitoring.tmweb.ru java[188440]: Downloading CRL from: http://crl.pki.gov.kz/nca_d_gost.crl ...
Jul 07 10:24:43 546691-monitoring.tmweb.ru java[188440]: Downloading CRL from: http://crl.pki.gov.kz/nca_gost.crl ...
Jul 07 10:24:43 546691-monitoring.tmweb.ru java[188440]: Downloading CRL from: http://crl.pki.gov.kz/nca_d_rsa.crl ...
Jul 07 10:24:49 546691-monitoring.tmweb.ru java[188440]: CRL generation 5B5F404DE4ACD53A3C210288CA4FB1FDB82C5089.crl memory usage: {"totalFree":"3MB","max":"494MB","free":"3MB","allocated":"494MB"}
Jul 07 10:24:50 546691-monitoring.tmweb.ru java[188440]: CRL generation AA2E7A01CBD916FDE1FA5BFCAC8B573393A4F9A6.crl memory usage: {"totalFree":"213MB","max":"494MB","free":"213MB","allocated":"494MB"}
Jul 07 10:24:53 546691-monitoring.tmweb.ru java[188440]: CRL generation 810CACC7D97B1D1CAA9E1864C4C49A0A0DA4612E.crl memory usage: {"totalFree":"65MB","max":"494MB","free":"65MB","allocated":"494MB"}
Jul 07 10:24:54 546691-monitoring.tmweb.ru java[188440]: CRL generation 219B41DCE04EDEEF359B008652D8D99879142D8B.crl memory usage: {"totalFree":"149MB","max":"494MB","free":"149MB","allocated":"494MB"}
```

#### Остановка службы

```shell
systemctl stop ncanode.service
```

#### Перезапуск

```shell
systemctl restart ncanode.service
```
