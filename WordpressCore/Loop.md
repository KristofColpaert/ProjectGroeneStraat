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

Let's break this example down to look at the different parts of the Loop:

```
if(have_posts())
```

This line checks if any postst of pages are going to be displayed on the current page you are viewing. If posts of pages exist, the next line will execute:

``` 
while(have_posts())
```

The preceding while statement starts the Loop, essentially looping through all posts and pages to be displayed on the page until ther are no more. The have_posts() function simply checks to see if the list of posts being processed is exhausted, or had no entries to begin with.

```
the_post();
```

Next, the the_post() function is called to load all of the post data. This function must be called inside your loo for the post data to be set correctly. Calling the_post() in turn calls the setup_postdata() function to set up the per-post metadata such as the author and tags of the content you are displaying in the Loop, as well as the content of the post itself. This data is assigned to a global variable each time through the Loop iteration. Specifically calling the_post() advances to the next post in the list.

# Template Tags

PHP functions used in your WordPress theme templates to display Loop content are called template tags. These tags are used to display specific pieces of data about your website and content. This allows you to customize how and where content is displayed on your website.

The most commonly usd templates are: 
* the_permalink(): displays the URL of the post.
* the_title(): displays the title of the post.
* the_ID(): displays the unique ID of the post. 
* the_content(): diplays the full content of the post.
* the_excerpt(): displays the excerpt of the post. If the Excerpt field is filled out on the Post edit screenm that will be used. If not, WordPress wil auto-generate a short excerpt from your post content.
* the_time(): displays the date/time your post was published.
* the_author(): displays the author of the post.
* the_tags(): displays the tags attached to the post.
* the_category(): displays the categories assigned to the post.
* edit_post_link(): displays an edit link that is shown only if you are logged in and allowed to edit the post.
* comment_form(): displays a complete commenting form for your post.

Example:

``` 
<?php
	if(have_posts())
		while(have_posts())
			the_post():
			?>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			<?php
				the_content();
		endwhile;
	endif;
?>
```