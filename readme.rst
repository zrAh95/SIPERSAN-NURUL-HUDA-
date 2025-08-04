###############################################
Sipersan Sistem Perizinan Keluar Masuk Santri
###############################################

Assalamualaikum Warrahmatullahi Wabbarakutuhu

Saya Hafiz Fachreza Firmansyah mahasiswa bina insani semester akhir yang sedang membuat tugas akhir untuk skripsi saya saya membuat project 
ini untuk akses perizinan keluar masuk santri untuk ponpes nurul huda menggunakan RFID berikut penjelasanya jika ponpes bapak/i 
asatidz/asatidzah.

Infrastruktur yang dipakai dalam sistem ini diantaranya sebagai berikut :
1. PHP
2. Mysql (untuk pengelolaan database)
3. whatsapp gateway API (FONNTE) limit pesan notifikasi 900 pesan perbulan
4. framework codeigniter 3
5. C++ untuk esp32
6. javascript bootstrap

adapun untuk rangkaian komponen esp32 nya kabel nya sebagai berikut : 

I. Komponen: RFID-RC522
1. SS_PIN: Pin 21 
2. RST_PIN: Pin 22 
3. SPI Pins:
4. SCK: Pin 18 
5. MISO: Pin 19 
6. MOSI: Pin 23 

II. Komponen: Layar LCD 16x2
1. I2C Address: 0x27 
2. I2C Pins:
3. SDA: Pin 26 
4. SCL: Pin 25 

III. Komponen: LED RGB
1. RGB_R (Merah): Pin 13 
2. RGB_G (Hijau): Pin 12 
3. RGB_B (Biru): Pin 14 

IV. Komponen: Passive Buzzer
1. BUZZER: Pin 4 

V. Komponen: Tombol (Button)
1. BUTTON_PIN: Pin 27

Jadi dengan senang hati saya mengizinkan untuk dipakai di seluruh ponpes di indonesia jika ingin menerapkan sistem ini namun alangkah baiknya izin 
terlebih dahulu kepada pihak (Pondok Pesantren Nurul Huda Setu Cikarageman) jika ingin menerapkanya, dan jika rekan rekan mahasiswa/i jika ingin .
memakai untuk tugas di persilahkan jikalau sistem ini diperjualbelikan 
secara diam diam maka saya tidak ridho dunia akhirat dan saya tuntut dan kejar dimanapun kalian 
berada jika sistem tersebut diperjualbelikan.

hormat saya

HAFIZ FACHREZA FIRMANSYAH

###################
What is CodeIgniter
###################

CodeIgniter is an Application Development Framework - a toolkit - for people
who build web sites using PHP. Its goal is to enable you to develop projects
much faster than you could if you were writing code from scratch, by providing
a rich set of libraries for commonly needed tasks, as well as a simple
interface and logical structure to access these libraries. CodeIgniter lets
you creatively focus on your project by minimizing the amount of code needed
for a given task.

*******************
Release Information
*******************

This repo contains in-development code for future releases. To download the
latest stable release please visit the `CodeIgniter Downloads
<https://codeigniter.com/download>`_ page.

**************************
Changelog and New Features
**************************

You can find a list of all changes for each release in the `user
guide change log <https://github.com/bcit-ci/CodeIgniter/blob/develop/user_guide_src/source/changelog.rst>`_.

*******************
Server Requirements
*******************

PHP version 5.6 or newer is recommended.

It should work on 5.3.7 as well, but we strongly advise you NOT to run
such old versions of PHP, because of potential security and performance
issues, as well as missing features.

************
Installation
************

Please see the `installation section <https://codeigniter.com/userguide3/installation/index.html>`_
of the CodeIgniter User Guide.

*******
License
*******

Please see the `license
agreement <https://github.com/bcit-ci/CodeIgniter/blob/develop/user_guide_src/source/license.rst>`_.

*********
Resources
*********

-  `User Guide <https://codeigniter.com/docs>`_
-  `Contributing Guide <https://github.com/bcit-ci/CodeIgniter/blob/develop/contributing.md>`_
-  `Language File Translations <https://github.com/bcit-ci/codeigniter3-translations>`_
-  `Community Forums <http://forum.codeigniter.com/>`_
-  `Community Wiki <https://github.com/bcit-ci/CodeIgniter/wiki>`_
-  `Community Slack Channel <https://codeigniterchat.slack.com>`_

Report security issues to our `Security Panel <mailto:security@codeigniter.com>`_
or via our `page on HackerOne <https://hackerone.com/codeigniter>`_, thank you.

***************
Acknowledgement
***************

The CodeIgniter team would like to thank EllisLab, all the
contributors to the CodeIgniter project and you, the CodeIgniter user.
