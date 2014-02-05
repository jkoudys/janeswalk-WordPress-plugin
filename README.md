# Jane's Walk WordPress Plugin

Include Jane's Walk directly in your blog! Walks, city information, maps and even lists of walks can all be easily loaded directly in your page.

## Features

* Load a map of any walk route
* Show your basic city information
* List all the walks in a certain city, even search for certain walks
* Show the full route of the walk on a map

## Installation

1. Copy the `janeswalk` directory into your `wp-content/plugins` directory
2. Navigate to the *Plugins* dashboard page
3. Locate the menu item that reads *TODO*
4. Click on *Activate*

This will activate the WordPress Plugin.

## Recommended Tools

## License

The WordPress Plugin is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

## Important Notes

### Includes

Note that if you include your own classes, or third-party libraries, there are three locations in which said files may go:

1. `janeswalk/includes` is where shared functionality should be placed between `public` and `admin`
2. `janeswalk/admin/includes` is where dashboard-specific classes and dependencies should be placed
3. `janeswalk/public/includes` is where public-specific classes and dependencies should be placed

## Assets

The assets directory provides two files that are used to represent plugin header images.

When committing your work to the WordPress Plugin Repository, these files should reside in their own `assets` directory, not in the root of the plugin. The initial repository will contain three directories:

1. `branches`
2. `tags`
3. `trunk`

You'll need to add an `assets` directory into the root of the repository. So the final directory structure should include *four* directories:

1. `assets`
2. `branches`
3. `tags`
4. `trunk`

Next, copy the contents of the `assets` directory that are bundled with the Boilerplate into the root of the repository. This is how the WordPress Plugin Repository will retrieve the plugin header image.

Of course, you'll want to customize the header images from the place holders that are provided with the Boilerplate.

For more, in-depth information about this, read [this post](http://make.wordpress.org/plugins/2012/09/13/last-december-we-added-header-images-to-the/) by [Otto](https://twitter.com/Otto42).

Plugin screenshots can be saved to one of two locations:

1. The old way is to keep them in the root of the plugin directory. This will increase the size of the download of the plugin, but make the images accessible for those who install it. This is deprecated in the WordPress Plugin Boilerplate
2. With the alternative way, you can save the screenshots in the `assets` directory, as well. The repository will look here for the screenshot files as well; however, they will not be included in the plugin download thus reducing the size of the plugin. As of its latest version, the WordPress Plugin Boilerplate now follows this convention.
