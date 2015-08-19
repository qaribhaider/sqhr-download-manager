# Download Manager

Wordpress plugin providing count and details for file downloads

> Normal file links are parsed by the plugin, allowing to track downloads with
> force download support. Links can be created using shortcode in wordpress 
> editor or by using helper method in theme files directly

### Version
1.0.0

### Installation

Download and extract files in wordpress plugin directory

## Usage

Following are the instructions for using this plugin 

### Using Helper Method

Download Manager can be used directly in theme files using the following function

```sh
sqhrdm_get_download_link($file_url, $force_download)
```

Arguments : 

```sh
$file_url        string      URL / Link of the file 
$force_download  boolean     Whether to force download file or not
```

### Using Short Code

Short codes are also supported for using the plugin from WP Editor

```sh
[dlink href="URL"]CONTENT[/dlink]
```

Supported Arguments : 

```sh
href        string              URL / Link of the file 
force       string[yes/no]      Whether the file should be force downloaded or not     
*           striing             Extra attributes can be added to shortcode which will be available in the link (See in below example, the id and class attributes)
```
Example : 

```sh
[dlink href="http://www.someweb.com/blog/wp-content/uploads/2015/08/home-01.jpg" class="left main" id="dlink"]Here is the content[/dlink]
```
