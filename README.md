Google DFP Bundle for Symfony2 [![Travis-CI Build Status](https://secure.travis-ci.org/nodrew/NodrewDfpBundle.png?branch=master)](http://travis-ci.org/#!/nodrew/NodrewDfpBundle)
=================================================================================================================================================================================

# This Bundle is UNDER DEVELOPMENT. DO NOT USE YET.

## Installation Instructions

1. Download NodrewDfpBundle
2. Configure the Autoloader
3. Enable the Bundle
4. Add your Google DFP API key

### Step 1: Download NodrewDfpBundle

Ultimately, the NodrewDfpBundle files should be downloaded to the
`vendor/bundles/Nodrew/Bundle/DfpBundle` directory.

This can be done in several ways, depending on your preference. The first
method is the standard Symfony2 method.

**Using the vendors script**

Add the following lines in your `deps` file:

```
[NodrewDfpBundle]
    git=http://github.com/nodrew/NodrewDfpBundle.git
    target=/bundles/Nodrew/Bundle/DfpBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

**Using submodules**

If you prefer instead to use git submodules, then run the following:

``` bash
$ git submodule add http://github.com/nodrew/NodrewDfpBundle.git vendor/bundles/Nodrew/Bundle/DfpBundle
$ git submodule update --init
```

### Step 2: Configure the Autoloader

``` php
<?php
// app/autoload.php

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    // ...
    'Nodrew'   => __DIR__.'/../vendor/bundles',
));
```

### Step 3: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Nodrew\Bundle\DfpBundle\NodrewDfpBundle(),
    );
}
```

### Step 4: Add your Google DFP API Key

``` yaml
# app/config/config.yml
nodrew_dfp:
    api_key:   [your api key]
```

