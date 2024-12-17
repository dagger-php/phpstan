# Dagger PHP SDK

## Prerequisites 

## Install dagger PHP SDK on your project, if you haven't already

```
dagger init --sdk=php --source=./dagger
```

This will generate a Dagger Module on your project the `./dagger/src/` directory. If your repository name is named `my-app` such as `path/to/project/my-app`, then you'll have `./dagger/src/MyApp.php`

## Installation

### Step 1.1 - Install phpstan module on your project 

You can install the phpstan dagger module, into your local project's dagger module, and then invoke it from inside the PHP source code

```
dagger install github.com/dagger-php/phpstan
```

## Step 2 - add a phpstan dagger function to your local app

Add this code to your project, to begin calling the the phpstan dagger module from your local app.

Let's say you have a project running on PHP 8.4, with some code located at `./app` directory.

``` php
#[DaggerFunction]
#[Doc('Run phpstan on your codebase')]
public function static(Directory $source): Container
{
    return dag()
        ->phpstan()
        ->analyse(source: $source, pathToTest: './app');
}
```

### Step 2.2 - testing the syntax is all okay

By running `dagger functions` you should see the `static` function in the output list

```
$ dagger functions

Name     Description
static   Run phpstan on your codebase

```

### Step 2.3 - calling your dagger function

```
dagger call static --source=. stdout
```

## Step 2.4 - changing the PHP version to suit your project

We have a `phpVersion()` method. By default it defaults to PHP version `8.4`

``` php
#[DaggerFunction]
#[Doc('Run phpstan with custom php version')]
public function staticCustomPhpVersion(Directory $source): Container
{
    return dag()->phpstan()
        ->phpVersion('8.3')
        ->analyse(source: $source, pathToTest: './app');
}
```

Test the syntax is ok

```
$ dagger functions

Name                        Description
static-custom-php-version   Run phpstan with custom php version

```

Run it

```
dagger call static-custom-php-version --source=. stdout
```

## Step 3 - adding phpstan options

This phpstan() dagger module comes with an expressive method chaining pattern to customise the phpstan options, such as memory limits, levels, debug mode and so on. All CLI arguments on the phpstan CLI script should be supported here.

``` php
#[DaggerFunction]
#[Doc('Run phpstan')]
public function staticWithOptions(Directory $source): Container
{
    return dag()->phpstan()
        ->memoryLimit('2G')
        ->level('5')
        ->analyse(source: $source, pathToTest: './app');}
}
```

Test the syntax is okay

```
$ dagger functions

Name                 Description
static               Run phpstan on your codebase
static-with-options  Run phpstan with custom phpstan options

```

Run it
```
dagger call static-with-options --source=. stdout
```


## Step 3.2 - more phpstan options

Not all of these make sense, togther, such as debug and quiet, but they're all listed here for documentation sakes.

``` php
#[DaggerFunction]
#[Doc('Run phpstan')]
public function staticWithOptions(Directory $source): Container
{
    return dag()->phpstan()
        ->memoryLimit('2G')
        ->level('5')
        ->debug(true)
        ->noProgress(true)
        ->quiet(true)
        ->analyse(source: $source, pathToTest: './app');}
}
```

## Step 4 - adding more parameters for CLI flexibility

Let's parameterize some extra CLI the options of your dagger function. This will save you from modifying the actual code, later, and you can just modify the CLI args.


``` php
#[DaggerFunction]
#[Doc('Run phpstan on your codebase with some arguments')]
public function staticWithArgs(
    Directory $source,
    string $phpVersion = '8.4',
    string $pathToTest = '.'
): Container
{
    return dag()
        ->phpstan()
        ->phpVersion($phpVersion)
        ->analyse(source: $source, pathToTest: './app');
}
```

```
$ dagger functions

Name                  Description
static-with-args      Run phpstan with CLI arguments

```


### Step 4.2 - specifying extra parameters on the CLI

```
dagger call static-with-args --source=. --php-version=8.4 --path-to-test=./app stdout

dagger call static-with-args --source=. --php-version=8.4 --path-to-test=./src stdout

dagger call static-with-args --source=. --php-version=8.4 --path-to-test=./app stdout

dagger call static-with-args --source=. --php-version=8.4 --path-to-test=./app stdout
```

## Support

For support go to the dagger Discord, join the #php channel and ask for @dragoonis (Paul Dragoonis) or perhaps someone else will be around to help you
