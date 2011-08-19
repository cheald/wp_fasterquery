# WP_Fasterquery

This is a simple plugin that solves a rather nasty issue in the Wordpress core. It's probably better as a core patch, but I needed this functionality immediately, and couldn't patch my install core for several reasons. Hence, this plugin.

The plugin itself is very simple. Right now, when you execute some arbitrary WP posts query, Wordpress is going to build a very large potential resultset, likely resulting in a temp table being used if your install is large enough. For example, you might select some posts with some particular term taxonomy. This requires joining the term taxonomies relationships table, and the terms table, and if you have a lot of posts on a given term, you'll end up with an extremely large result set. MySQL writes this large result set to a temp table, does the sorting necessary, and gives you back the two posts you were actually interested in.

This plugin just splits those queries into two queries - one that performs a select wp_posts.ID, and then a second that selects just the posts from the previous ID set. In my tests, against a table with a half million rows in wp_posts, this improved query times by two orders of magnitude.

Smaller databases likely won't see as much benefit (though it is very possible, especially on low-RAM DB machines).