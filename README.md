# tyrosfc.com

# Local MySQL

```
sudo /usr/local/mysql/support-files/mysql.server start
```

```
sudo /usr/local/mysql/support-files/mysql.server stop
```

```
sudo /usr/local/mysql/support-files/mysql.server restart
```










# Install NPM (if not already installed)

```
http://npmjs.com
```

# Install Gulp (if not already globally installed)

```
npm install gulp
```

# Install Required Node Modules
```
npm install
```

# While Developing...
To automatically minify CSS and JS files, you must use gulp 
to watch for file changes, which will then trigger building 
the minified files. 

While on local.tyrosfc.com files will load 
from /css/dev-*.css 
while production will load from /css/min/*.css

The only major difference is that dev will produce sourcemaps.

``` 
gulp watch
```

# NOTES
Be sure to update CSS files manually (this has not been built in yet, todo???) 
```
Replace <?=$app['cdn']?> 
With //verysecurewebsite.r.worldssl.net/websites/hillendale.net/
```

# JS NOTES 
When adding new third party JS files, you must manually 
add them to gulpfile.js under `jsThirdPartySource` -- Don't forget 
to run `gulp watch` again, too!

