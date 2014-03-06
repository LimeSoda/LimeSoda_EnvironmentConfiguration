LimeSoda Environment Configuration
=====================
Enables developers to modify Magento installations (configuration, data, ...) based on the given environment using n98-magerun. 

Facts
-----
- version: 0.0.1
- extension key: LimeSoda_EnvironmentConfiguration
- [extension on GitHub](https://github.com/LimeSoda/LimeSoda_EnvironmentConfiguration)

Description
-----------
Enables developers to modify Magento installations (configuration, data, ...) based on the given environment using n98-magerun.

Requirements
------------
- PHP >= 5.3.0
- Mage_Core
- n98-magerun

Compatibility
-------------
- Magento >= EE 1.13.0.2 (should also work on older and CE versions)

Installation Instructions
-------------------------
1. Install the extension via modman.

Usage
-----

Call n98-magerun like this:

    n98-magerun.phar ls:env:configure [environment]

### Set an environment name

Configure the environment of the environment in your XML. Most of the time you will want to put this in local.xml as this file doesn't get shared between copies of the shop in most setups.

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

Next we create a command. You create a `commands` node beneath your environment node. To add a command, you choose a unique node name and add the n98-magerun command as the value:

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

You can replace hard-coded strings (e.g. URLs) with variables. Add variables for your environment as children of a `variables` node. Then you can insert the values into commands using the notation `${variable_name}`.

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

### Nesting environments

Using variables is nice but the most you will profit if you nest environments. This means you can create a base definition for commands and variables and expand them in other environments. You do this by specifying the parent in your environments base node: `<dev parent="default">`.

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

By specifiying commands and variables on different levels, you can save yourself some typing and maintenance work. In the next example we disable and flush the cache for all environments while setting a different URL for every environment.

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
					<default parent="default">
						<variables>
						</variables>
						<commands>
						</commans>
					</default>
					<dev parent="default">
						<variables>
						</variables>
						<commands>
						</commans>
					</dev>
					<developer1 parent="dev">
						<variables>
						</variables>
						<commands>
						</commans>
					</developer1>
					<testing parent="default">
						<variables>
						</variables>
						<commands>
						</commans>
					</testing>
					<staging parent="default">
						<variables>
						</variables>
						<commands>
						</commans>
					</staging>
					<live parent="default">
						<variables>
						</variables>
						<commands>
						</commans>
					</live>
				</environments>
			</limesoda>
		</global>
	</config>


Uninstallation
--------------
1. Just like any other modman installed extension.

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/LimeSoda/LimeSoda_EnvironmentConfiguration/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

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
(c) 2013 LimeSoda Interactive Marketing GmbH
