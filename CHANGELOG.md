Changelog
=========

1.1.0 (unreleased)
-----

Added / Improved:

* Added optional header indicating the current environment (thanks to @tegansnyder)
* Made header colors configurable per environment
* Added n98-magerun command `ls:aoe:scheduler:set-job-status`

Backwards-incompatible changes:

* Renamed `ls:env:configure:ess:m2epro:set-channel-status` to `ls:ess:m2epro:set-channel-status`
* Renamed `ls:env:configure:ess:m2epro:set-license-key` to `ls:ess:m2epro:set-license-key`

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
