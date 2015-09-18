LimeSoda Environment Configuration
=====================
Enables developers to modify Magento installations (configuration, data, ...) based on the given environment using
n98-magerun.

Build Status
---
**Latest Release**

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LimeSoda/LimeSoda_EnvironmentConfiguration/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/LimeSoda/LimeSoda_EnvironmentConfiguration/?branch=master)
 
**Development Branch**

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LimeSoda/LimeSoda_EnvironmentConfiguration/badges/quality-score.png?b=dev)](https://scrutinizer-ci.com/g/LimeSoda/LimeSoda_EnvironmentConfiguration/?branch=dev)

Facts
-----
- version: 1.2.0
- extension key: LimeSoda_EnvironmentConfiguration
- [extension on GitHub](https://github.com/LimeSoda/LimeSoda_EnvironmentConfiguration)

Requirements
------------
- PHP >= 5.3.0
- Mage_Core
- [n98-magerun](https://github.com/netz98/n98-magerun)

Compatibility
-------------
- Magento >= EE 1.13.0.2 (should also work on older and CE versions)

Installation Instructions
-------------------------
1. Install the extension via [modman](https://github.com/colinmollenhour/modman) or
   [Composer](https://getcomposer.org/).
2. Add names to your environments.
3. Configure environments.

Usage
-----
After configuring your environments (see below) call n98-magerun like this:

    n98-magerun.phar ls:env:configure [environment]

### Set an environment name

Configure the environment of the environment in your XML. Most of the time you will want to put this in local.xml as
this file doesn't get shared between copies of the shop in most setups.

    <config>
        <global>
            <limesoda>
                <environment>
                    <name>dev</name>
                </environment>
            </limesoda>
        </global>
    </config>

### Adding environments

Create a new extension (or use an existing one) and add a `global > limesoda >  environments` node to your `config.xml`:

    <?xml version="1.0"?>
    <config>
        <global>
            <limesoda>
                <environments>
                    <default />
                </environments>
            </limesoda>
        </global>
    </config>

Congratulations, you created your "default" environment!

Calling

    n98-magerun.phar ls:env:configure default

will execute the actions you specified for this environment.

### Adding commands

Next we create a command. You create a `commands` node beneath your environment node. To add a command, you choose a
unique node name and add the n98-magerun command as the value:

    <?xml version="1.0"?>
    <config>
        <global>
            <limesoda>
	            <environments>
	                <default>
	                    <commands>
	                        <cfg_wubu>config:set "web/unsecure/base_url" "http://www.domain.tld/"</cfg_wubu>
	                    </commands>
	                </default>
	            </environments>
	        </limesoda>
        </global>
    </config>

### Using variables

You can replace hard-coded strings (e.g. URLs) with variables. Add variables for your environment as children of a
`variables` node. Then you can insert the values into commands using the notation `${variable_name}`.

    <?xml version="1.0"?>
    <config>
        <global>
            <limesoda>
	            <environments>
	                <default>
	                    <variables>
	                        <unsecure_base_url><![CDATA[http://www.domain.tld/]]></unsecure_base_url>
	                    </variables>
	                    <commands>
	                        <cfg_wubu>config:set "web/unsecure/base_url" "${unsecure_base_url}"</cfg_wubu>
	                    </commands>
	                </default>
	            </environments>
            </limesoda>
        </global>
    </config>

### Add values to 'System > Configuration'

In the first two examples we set a value for `System > Configuration` using the normal `config:set` syntax. If you have
to do this for many values and different scopes this can get confusing.

Alternatively you can use a special `system_config` node for setting values. It supports scopes the same way you know
it from the `default`, `websites` and `stores` nodes in `config.xml`.

Use `encrypt="true"` to set an encrypted configuration value.

    <?xml version="1.0"?>
    <config>
        <global>
            <limesoda>
	            <environments>
	                <default>
	                    <variables>
	                        <unsecure_base_url><![CDATA[http://www.domain.tld/]]></unsecure_base_url>
	                    </variables>
	                    <system_configuration>
	                        <default>
                                <web>
                                    <unsecure>
                                        <base_url>${unsecure_base_url}</base_url>
                                    </unsecure>
                                </web>
                            </default>
	                    </system_configuration>
	                </default>
	            </environments>
            </limesoda>
        </global>
    </config>
 
Define values on website and store view scopes the same way. You can use the website and store code instead of the
 numeric ids. If you for whatever reason prefer the ID you also can use this one instead.

    <?xml version="1.0"?>
    <config>
        <global>
            <limesoda>
	            <environments>
	                <default>
	                    <variables>
	                        <unsecure_base_url><![CDATA[http://www.domain.tld/]]></unsecure_base_url>
	                    </variables>
	                    <system_configuration>
	                        <default>
                                <web>
                                    <unsecure>
                                        <base_url>${unsecure_base_url}</base_url>
                                    </unsecure>
                                </web>
                            </default>
                            <websites>
                                <first_website>
                                    <web>
                                        <unsecure>
                                            <base_url>${unsecure_base_url}</base_url>
                                        </unsecure>
                                    </web>
                                </first_website>
                                <second_website>
                                    <web>
                                        <unsecure>
                                            <base_url>http://seconddomain.tld/</base_url>
                                        </unsecure>
                                    </web>
                                <second_website>
                            </websites>
                            <stores>
                                <third_store>
                                    <web>
                                        <unsecure>
                                            <base_url>http://thirddomain.tld/</base_url>
                                        </unsecure>
                                    </web>
                                </third_store>
                                <4>
                                    <web>
                                        <unsecure>
                                            <base_url>http://fourthdomain.tld/</base_url>
                                        </unsecure>
                                    </web>
                                <4>
                            </stores>
	                    </system_configuration>
	                </default>
	            </environments>
            </limesoda>
        </global>
    </config>

### Command stages

In the first two examples all commands were placed in the `commands` node. As we just mentioned you can use
`system_configuration` to put all system configuration settings in its own node and make big configurations clearer.
 
You still may have many commands left which have to be specified in `commands` and have a hard time sorting them in the
right way by being creative with the names of the XML nodes.

To help a little bit with that you can use two custom stages, `pre_configure` and `post_configure`, which are executed
before and after the operations in `commands` are processed.

    <?xml version="1.0"?>
    <config>
        <global>
            <limesoda>
	            <environments>
	                <default>
	                    <variables>
	                        <unsecure_base_url><![CDATA[http://www.domain.tld/]]></unsecure_base_url>
	                    </variables>
	                    <commands>
	                        <cfg_wubu>config:set "web/unsecure/base_url" "${unsecure_base_url}"</cfg_wubu>
	                    </commands>
	                    <post_configure>
                            <cd>cache:disable</cd>
                            <cf>cache:flush</cf>
	                    </post_configure>
	                </default>
	            </environments>
            </limesoda>
        </global>
    </config>

The settings from `system_configuration` are applied in the `commands` stage. This means the commands will be added in
the following order:

* pre_configure
* commands
* system_configuration
* post_configure

### Nesting environments

Using variables is nice but the most you will profit if you nest environments. This means you can create a base
definition for commands and variables and expand them in other environments. You do this by specifying the parent in
your environments base node: `<dev parent="default">`.

A typical setup could be:

* default
    * dev
      * developer 1
      * developer 2
      * developer 3
   * test
   * qa
   * staging
   * live

If you want to re-build this setup for the environment configuration, your XML will look like this:

    <?xml version="1.0"?>
    <config>
        <global>
            <limesoda>
	            <environments>
	                <default />
	                <dev parent="default" />
	                <dev01 parent="dev" />
	                <dev02 parent="dev" />
	                <dev03 parent="dev" />
	                <test parent="default" />
	                <qa parent="default" />
	                <staging parent="default" />
	                <live parent="default" />
	            </environments>
	        </limesoda>
        </global>
    </config>
    
If you define a command or variable in a parent environment, the child environment will inherit them.

By specifying commands and variables on different levels, you can save yourself some typing and maintenance work. In the
next example we disable and flush the cache for all environments while setting a different URL for every environment.

    <?xml version="1.0"?>
    <config>
        <global>
            <limesoda>
	            <environments>
	                <default>
	                    <variables>
	                        <unsecure_base_url><![CDATA[http://www.domain.tld/]]></unsecure_base_url>
	                    </variables>
	                    <commands>
	                        <cfg_wubu>config:set "web/unsecure/base_url" "${unsecure_base_url}"</cfg_wubu>
	                    </commands>
	                </default>
	                <dev parent="default">
	                    <variables>
	                        <unsecure_base_url><![CDATA[http://dev.domain.tld/]]></unsecure_base_url>
	                    </variables>
	                    <commands>
	                        <cd>cache:disable</cd>
	                        <cf>cache:flush</cf>
	                    </commands>
	                </dev>
	                <dev01 parent="dev">
	                    <variables>
	                        <unsecure_base_url><![CDATA[http://dev01.domain.tld/]]></unsecure_base_url>
	                    </variables>
	                </dev01>
	                <dev02 parent="dev">
	                    <variables>
	                        <unsecure_base_url><![CDATA[http://dev02.domain.tld/]]></unsecure_base_url>
	                    </variables>
	                </dev02>
	                <dev03 parent="dev">
	                    <variables>
	                        <unsecure_base_url><![CDATA[http://dev03.domain.tld/]]></unsecure_base_url>
	                    </variables>
	                </dev03>
	            </environments>
            </limesoda>
        </global>
    </config>

### Configuration skeleton

You can use this `config.xml` skeleton as a starting point for your environment configuration.

	<?xml version="1.0" encoding="UTF-8"?>
	<config>
		<modules>
			<YourCompany_YourModule>
				<version>0.0.1</version>
			</YourCompany_YourModule>
		</modules>
		
		<global>
			<limesoda>
				<environments>
					<default>
						<variables>
						</variables>
						<commands>
						</commands>
					</default>
					<dev parent="default">
						<variables>
						</variables>
						<commands>
						</commands>
					</dev>
					<developer1 parent="dev">
						<variables>
						</variables>
						<commands>
						</commands>
					</developer1>
					<testing parent="default">
						<variables>
						</variables>
						<commands>
						</commands>
					</testing>
					<staging parent="default">
						<variables>
						</variables>
						<commands>
						</commands>
					</staging>
					<live parent="default">
						<variables>
						</variables>
						<commands>
						</commands>
					</live>
				</environments>
			</limesoda>
		</global>
	</config>

Built-in commands
-----------------

###ls:aoe:scheduler:job:status

Enables and disables cron jobs as used by [Aoe_Scheduler](https://github.com/AOEpeople/Aoe_Scheduler) >= 1.0.0.

    <commands>
      <example>ls:aoe:scheduler:job:status "[jobcode]" "[status]"</example>
    </commands>

All arguments are required.

* jobcode: The Magento cron job code (e.g. `core_email_queue_send_all`)
* status: `0` for inactive, `1` for active.

Example:

    <commands>
      <example>ls:aoe:scheduler:job:status "core_email_queue_send_all" "0"</example>
    </commands>

You will get an output like `Job 'example_cron': set status to '0'.` which makes it easier to identify changes to the
crons.

###ls:ess:m2epro:channel:status

Sets the channel status for Ess_M2ePro. The extension has to be installed and enabled.

    <commands>
      <example>ls:ess:m2epro:channel:status "[name]" "[status]"</example>
    </commands>

###ls:ess:m2epro:license:key

Sets the license key for Ess_M2ePro. The extension has to be installed and enabled.

    <commands>
      <example>ls:ess:m2epro:license:key "[key]"</example>
    </commands>

Backend Overview
----------------
Navigate to `System > Environment Configuration` to get a list of all configured environments.

Click on an environment to get a list of the commands that will be executed. Variables not defined for the environment
are highlighted.

Displaying the environment name in the header
---------------------------------------------
You can enable an header bar in `System > Configuration > Advanced > Admin > Environment Configuration > Display
environment name above admin header`.

To make it easier for you to differentiate between environments you can configure the font and background color per
environment:

    <environments>
        <mz>
            <settings>
            	<label>Dev Environment M.Zeis</label>
                <color>#fff</color>
                <background_color>#090</background_color>
            </settings>
        </mz>
    </environments>

You can also display a label, see the `<label>` node above. If no label is set, the environment name is displayed.

Uninstallation
--------------
Just like any other modman or Composer installed extension. No database tables or other additional files are created.

Support
-------
If you have any issues with this extension, open an issue on
[GitHub](https://github.com/LimeSoda/LimeSoda_EnvironmentConfiguration/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a
[pull request on GitHub](https://help.github.com/articles/using-pull-requests). Please create your pull request against
the `dev` branch.

Developer
---------
Matthias Zeis  
[http://www.limesoda.com](http://www.limesoda.com)  
[@mzeis](https://twitter.com/mzeis)

License
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2014-2015 LimeSoda Interactive Marketing GmbH
