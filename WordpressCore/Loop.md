# The WordPress Loop

The Loop refers to how Wordpress determines what content (posts, pages, or custom content) to display on a page you are visiting. The Loop can display a single pice of content or a group of posts and pages that are selected and then displayed by looping throug the content, thus it's called the Loop.

The Loop selects posts form the MySQL database, based on a set of parameters, and those parameters are typically determined by the URL used to access your WordPress website. For example: a category page, accessed via a URL such as: http://example.com/category/halloween, shows only blog posts assigned to that category.

**Dus: Loop wordt gebruikt in de template files om te bepalen welke content er op een pagina mag getoond worden. WordPress gaat de URL omzetten naar een SQL-query en daarmee de juiste content uit de database ophalen**

## The flow of the Loop

The next example features the only required elements for the Loop to function properly:

```
<?php
	if(have_posts());
		while(have_posts())
			the_post();
			//The Loop content (template tags, html, etc)
		endwhile;
	endif;
?>
```

This is the Loop in its simplest form. If you're wondering how the output from the database query got handed to this simple Loop when there are no variables passed as parameters in this example, the answer lies in the global variable $wp_query, which is an instance of WP_QUERY that is referenced by the functions in the simple Loop.

Note that by the time this default Loop is called, WordPress has already called the get_posts() method within the default query object to build the list of appropriate content for the URL being viewed and the Loop in this case is being charged with displaying that list of posts.

**Dus: WordPress zorgt er in de achtergrond al voor dat juiste data wordt opgehaald op basis van de url. Wat we hier doen is gewoon de content weergeven.**

