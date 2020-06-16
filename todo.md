drivinginstructornorbury@gmail.com
superPa55

# Grav CLI

Seeing `-bash: bi/gpm: Permission denied`
when trying to run Grav CLI command
so did:
`chmod u+x bin/gpm`
as advised in: https://stackoverflow.com/a/18960752
```
Unix and Unix-like systems generally will not execute a program unless it is marked with permission to execute. The way you copied the file from one system to another (or mounted an external volume) may have turned off execute permission (as a safety feature). The command chmod u+x name adds permission for the user that owns the file to execute it.
```

# Begin working

## Backup current version of GRAV

`bin/grav backup`

## Upgrade Grav using GPM

`bin/gpm self-upgrade`

Cannot upgrade as shared host is not on latest PHP required for latest Grav.

Come back to this.

On Lark control panel, under 'more options', PHP version is there and its a select menu simply to change between PHP versions.

I then tried the command again and got this error.

```
[aozfgkeb@eu1 drivingschoolnorbury.com]$ bin/gpm self-upgrade
FATAL: GPM requires PHP Curl module to be installed
```

I thought to enable curl in a more local php.ini file, i.e. in root of website where grav is, in even though phpinfo(); shows it is enabled.

Added just this line: `extension=php_curl.dll`

The advice is to uncomment it in the actual php.ini so I went to find them: src: https://stackoverflow.com/questions/19848055/installing-curl-to-php-cli

BUT

I cannot edit any of the php files founded via `phpinfo(); > Loaded Configuration File` = `/opt/alt/php72/etc/php.ini`
OR `php -i | grep 'php.ini'` = 

```
Configuration File (php.ini) Path => /opt/cpanel/ea-php72/root/etc
Loaded Configuration File => /opt/cpanel/ea-php72/root/etc/php.ini
```

OR `php -r "echo php_ini_loaded_file();"` = `/opt/cpanel/ea-php73/root/etc/php.ini`

NB: `php --ini` result did not match `phpinfo();` and I don't know why.

WHERE `ea-php7x` is just whatever PHP version I had selected on lark at the time.

Then I noticed someone say they can curl via php but NOT via cli. `was working in php files run through apache, but not when called from the cli`
src: https://stackoverflow.com/a/29656078

They highlighted that cli and apache would be using different php.ini files.

To find where the actual file is, they gave this command : `find / -name 'curl.so'` (not sure why it's a .so when there are .dll in the .ini file)

I checked in the 2 places I saw the files are `alt` and `cpanel` inside `opt`. This gave me:
```
[aozfgkeb@eu1 opt]$ ls
MegaRAID  alt  bitninja  bitninja-dojo  bitninja-ssl-termination  bitninja-waf  cloudlinux  cpanel  passenger-5.3.7-8.el7.cloudlinux  rh  suphp
[aozfgkeb@eu1 opt]$ find alt/ -name 'curl.so'
find: 'alt/php53/var/cache/php-eaccelerator': Permission denied
find: 'alt/php54/var/cache/php-eaccelerator': Permission denied
find: 'alt/php52/var/cache/php-eaccelerator': Permission denied
find: 'alt/openssl/etc/pki/CA/private': Permission denied
find: 'alt/alt-nodejs10/root/etc/pki/CA/private': Permission denied
find: 'alt/alt-nodejs10/root/root': Permission denied
find: 'alt/alt-nodejs6/root/root': Permission denied
find: 'alt/alt-nodejs8/root/root': Permission denied
find: 'alt/alt-nodejs9/root/root': Permission denied
[aozfgkeb@eu1 opt]$ find cpanel/ -name 'curl.so'
cpanel/ea-php70/root/usr/lib64/php/modules/curl.so
find: 'cpanel/ea-php70/root/usr/var/log/php-fpm': Permission denied
find: 'cpanel/ea-php70/root/usr/var/run/php-fpm': Permission denied
find: 'cpanel/ea-php70/root/etc/php-fpm.d': Permission denied
cpanel/ea-php56/root/usr/lib64/php/modules/curl.so
find: 'cpanel/ea-php56/root/usr/var/log/php-fpm': Permission denied
find: 'cpanel/ea-php56/root/usr/var/run/php-fpm': Permission denied
find: 'cpanel/ea-php56/root/etc/php-fpm.d': Permission denied
cpanel/ea-php55/root/usr/lib64/php/modules/curl.so
find: 'cpanel/ea-php55/root/usr/var/log/php-fpm': Permission denied
find: 'cpanel/ea-php55/root/usr/var/run/php-fpm': Permission denied
find: 'cpanel/ea-php55/root/etc/php-fpm.d': Permission denied
cpanel/ea-php71/root/usr/lib64/php/modules/curl.so
find: 'cpanel/ea-php71/root/usr/var/log/php-fpm': Permission denied
find: 'cpanel/ea-php71/root/usr/var/run/php-fpm': Permission denied
find: 'cpanel/ea-php71/root/etc/php-fpm.d': Permission denied
cpanel/ea-php54/root/usr/lib64/php/modules/curl.so
find: 'cpanel/ea-php54/root/usr/var/log/php-fpm': Permission denied
find: 'cpanel/ea-php54/root/usr/var/run/php-fpm': Permission denied
find: 'cpanel/ea-php54/root/etc/php-fpm.d': Permission denied
cpanel/ea-php53/root/usr/lib64/php/modules/curl.so
find: 'cpanel/ea-php53/root/usr/var/log/php-fpm': Permission denied
find: 'cpanel/ea-php53/root/usr/var/run/php-fpm': Permission denied
```

I prodded around and it turns out php 7.1, 7.2 and 7.3 are MISSING the `curl.so` file ffs!

ADDITIONALLY, I FOUND A FLAW IN LARK PHP VERSIONS

ea-php71 is not actually php 7.1, it's still php 7.0 (which is insufficient for Grav itself, forget the gpm)

ea-php72
```
aozfgkeb@eu1 drivingschoolnorbury.com]$ php -v
PHP 7.2.24 (cli) (built: Oct 30 2019 15:08:25) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies
```
ea-php71
```
[aozfgkeb@eu1 drivingschoolnorbury.com]$ php -v
PHP 7.0.33 (cli) (built: Oct 29 2019 08:37:57) ( NTS )
Copyright (c) 1997-2017 The PHP Group
Zend Engine v3.0.0, Copyright (c) 1998-2017 Zend Technologies
    with the ionCube PHP Loader + ionCube24 v10.3.9, Copyright (c) 2002-2019, by ionCube Ltd.
    with Zend OPcache v7.0.33, Copyright (c) 1999-2017, by Zend Technologies
```
ea-php70
```
[aozfgkeb@eu1 drivingschoolnorbury.com]$ php -v
PHP 7.0.33 (cli) (built: Oct 29 2019 08:52:05) ( NTS )
Copyright (c) 1997-2017 The PHP Group
Zend Engine v3.0.0, Copyright (c) 1998-2017 Zend Technologies
[aozfgkeb@eu1 drivingschoolnorbury.com]$
```

On 7.0 the error is:
```
[aozfgkeb@eu1 drivingschoolnorbury.com]$ bin/gpm self-upgrade

GPM Releases Configuration: Stable

ATTENTION:
   Grav has increased the minimum PHP requirement.
   You are currently running PHP 7.0.33, but PHP 7.1.3 is required.
   Additional information: http://getgrav.org/blog/changing-php-requirements

Selfupgrade aborted.
```

PHP version too low for Grav.

But then the next available on lark is 7.2 and I am back at:

```
[aozfgkeb@eu1 drivingschoolnorbury.com]$ bin/gpm self-upgrade
FATAL: GPM requires PHP Curl module to be installed
```

Bearing in mind the PHP 7.1 and above are missing the `curl` file, I thought let me pick PHP version `ea-php73` via Lark control panel and just copy `curl.so` to it in the file system since it is missing.

```
[aozfgkeb@eu1 cpanel]$ cp ea-php70/root/usr/lib64/php/modules/curl.so ea-php73/root/usr/lib64/php/modules
cp: cannot create regular file 'ea-php73/root/usr/lib64/php/modules/curl.so': Permission denied
```

Ultimately I cannot point to a curl.so either in the php.ini since I don't have permission to edit php.ini.

So again, back to the problem of not having root access.

Person suggests:
```
[curl]
; A default value for the CURLOPT_CAINFO option. This is required to be an
; absolute path.
;curl.cainfo =
extension=/usr/lib/php5/20131226/curl.so
```
src: https://stackoverflow.com/a/29656078

Stating that the path to `curl.so` may be different. It is since I do not have even php inside my `usr/lib`.

But as I know where it is, I thought to modify my local `php.ini` in the root of the website with this location, so now the only line it contains is:

`extension=/opt/cpanel/ea-php70/root/usr/lib64/php/modules/curl.so`

Then I ran it
```
[aozfgkeb@eu1 drivingschoolnorbury.com]$ bin/gpm self-upgrade

GPM Releases Configuration: Stable

Grav v1.6.17 is now available [release date: Thu Nov  7 01:52:04 2019].
You are currently using v1.5.8.
Would you like to read the changelog before proceeding? [y|N] y
```

WOW FINALLY

```
Would you like to upgrade now? [y|N] y

Preparing to upgrade to v1.6.17..
  |- Downloading upgrade [3.9M]...   100%
  |- Installing upgrade...    ok
  '- Success!


Clearing cache

Cleared:  /home/aozfgkeb/public_html/drivingschoolnorbury.com/cache/*
Cleared:  /home/aozfgkeb/public_html/drivingschoolnorbury.com/images/*

Touched: /home/aozfgkeb/public_html/drivingschoolnorbury.com/user/config/system.yaml
```

About effin time. 3hrs+ later (not always on this though, doing other stuff too)

## Use admin panel to move user data across from previous site

1. Move data across and duplicate data
OR
2. Multi-site and power two websites with same data yet different URLs...
3. Maybe the 'user/pages' folder needs to be a symlink in production for both sites, where it is in the root of both sites, and the sites are subdirs yet receiving the web domain is pointing at the subdir.
4. Ask Ricardo?
5. 

# RESTART

## admin setup

`bin/plugin login newuser`

Follow instructions to add the new user.

I made

admin
EchoRules1.
echodriving@gmail.com
Fullname: Driving School Norbury
Title: Instructor
Admin & Site access permissions

## Start filling up site with content via admin

# RESTART

## Fresh install

1.  Fresh install of Grav skeleton big-picture from Grav site
2.  Downloaded local, uploaded to codetasty, extracted on lark, moved files back out to root
3.  