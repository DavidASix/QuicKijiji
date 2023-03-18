Okay, so to edit bootstrap,

First, this folder is a node project, but only to download the version of BS with raw SCSS files.

To edit it, edit bootstrap.scss
A lot of the editable variables can be found under node_modules/bootstrap/scss/_variables.scss
put YOUR variables in bootstrap.scss before the bootstrap import

Once you're satisfied, in the /var/www/site/src/css/bootstrap folder run this command:

sass bootstrap.scss bootstrap.css

This will compile and copy your bootstrap into bootstrap.css, which should be imported into your project.


https://getbootstrap.com/docs/5.1/getting-started/contribute/#sass
https://github.com/postcss/autoprefixer#cli
https://sass-lang.com/install
https://getbootstrap.com/docs/5.1/customize/sass/#maps-and-loops

npm install -g sass
npm install postcss-cli autoprefixer
