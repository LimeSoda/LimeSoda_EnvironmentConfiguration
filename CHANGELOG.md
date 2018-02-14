Changelog
=========

1.3.0 (14.02.2018)
-----

Added / Improved:

* Show environment notice on frontend too, allow custom labels (thanks to @avstudnitz) (#12).
* Added CLI option to override values in the configuration XML. 
* Make display of environment name for frontend configurable in system
* Change develop branch to 'develop'
* Update scrutinizer badge

Fixed:

* Corrected closing tags in the README (thanks to @EliasKotlyar) (#9).
* Added compatibility to Magento patch SUPEE-6285 (validating admin ACL (thanks to @gfobe) (#11).
* It wasn't possible to set config values starting with a `-` when using the `system_configuration` node (thanks to
  @Qronicle for noticing) (#16).
* Fix README formating on GitHub

1.2.0 (31.05.2015)
-----

Added / Improved:

* Allow encrypted configuration values in <system_configuration> (thanks to @schmengler) (#6). 

Fixed:

* Avoid errors in backend when environment name is not defined (thanks to @andreaspenz) (#4).
* Colors are not set for environment configuration admin header in Chrome.
* Fixed error in documentation (thanks to @avoelkl) (#7).

1.1.0 (18.03.2015)
-----

Added / Improved:

* Added optional header indicating the current environment (thanks to @tegansnyder)
* Made header colors configurable per environment
* Added n98-magerun command `ls:aoe:scheduler:job:status`

Backwards-incompatible changes:

* Renamed `ls:env:configure:ess:m2epro:channel-status` to `ls:ess:m2epro:channel:status`
* Renamed `ls:env:configure:ess:m2epro:set-license-key` to `ls:ess:m2epro:license:key`

1.0.0 (30.11.2014)
-----

Added / Improved:

* Added node <system_configuration> which allows specifying system configuration settings in a more readable manner.
* Added backend overview which:
  * shows the environments as a hierarchy
  * shows the commands to be executed in a selected environment
  * highlights missing variables in the selected environment
* Added new command stages 'pre_configure' and 'post_configure'.
* Added n98-magerun command db:truncate.
* Added n98-magerun command ls:env:reset. 

0.0.1 (09.04.2014)
-----
* Initial release
