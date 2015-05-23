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

**Volledige lijst van Template Tags en hun parameters op: http://codex.wordpress.org/Template_Tags**

# Customizing the loop

The opening discussion of Loop flow of control mentioned that the main workhorse for data selection is the get_posts() method of the WP_Query object. In most cases, if you want to build a custom Loop (display the pages you want to display), you will build your own WP_Query object and reference it explicitly.

**Dus: ophalen data gebeurt door de klasse WP_Query. WordPress voorziet voor ons een instantie van die klasse die alles voor ons automatisch regelt. Willen we toch dingen customizen (bv 5 posts per pagina, of enkel posts van die categorie), dan moeten we een eigen instantie aanmaken van WP_Query en daarop queries sturen.**

## Using the WP_Query Object

Once WordPress is handed a URL to parse by the web server, it goed to work disassembling the tokens in that URL and converting them into parameters for a database query. Here's a bit more detail on what happens when manipulating your own WP_Query.

WP_Query is a class defined in WordPress that makes it easy to create your own custom Loops. Custom Loops can be used anywhere in your theme template files to display different types of content; they must build on separate instances of a WP_Query variable.

When you creqte a new WP_Query objectm it's instantiated with some default function for building queries, executing the query to get posts, and parsing parameters out of a URL. However, you can use these built-in object methods to construct your own parameter strings, creating custom loops that extract whatever particular content you need for that point in your Loop.

The following is an example of a custom Loop displaying the five most recent posts on your website:

```
<?php
	$myPosts = new WP_Query('posts_per_page=5');
	while($myPosts->have_posts)
		$myPosts->the_post();
		//Do something
	endwhile;
?>
```

**Dus: om zelf te kiezen wat we willen weergeven op de pagina, gebruiken we niet zoals bij het bovenstaande voorbeeld de globale instantie van WP_Query, maar maken we een eigen instantie aan met een query. De methodes have_posts() en the_post() worden dan ook opgeroepen op dat eigen object. Een overzicht van alle parameters die we kunnen meegeven aan is te vinden op http://codex.wordpress.org/Class_Reference/WP_Query#Parameters.**

## Using query_posts()

The query_posts() method is an alternative for a WP_Query object and is used to easily modify the content returned for the default WordPress Loop. Specifically, you can modify the content returned after the default database query has executed, fine-tune the query parameters, and re-excecute the query using query_posts(). 

Explicitly calling query_posts() overwrites the original post content extracted for the Loop. This means any content you were excpecting to be returned before using query_posts() will not be returned. For example, if the URL passed to WordPress is for a category page at http://example.com/category/zombie/, none of the zombie category posts will be in the post list after query_posts() has been called unless one is in the five most recent posts. You explicitly overwrite the query parameters established by the URL parsing and default processing when you pass the query string to query_posts().

To avaid losing your original Loop content, you can save the parsed query parameters by using the $query_string global variable:

```
//Initialize the global query_string variable
global $query_string;

//Keep original Loop content and change the sort order
query_posts($query_string . "&orderby=title&order=ASC");
```
In the preceding example, you would still see all of your zombie category posts, but they woul be ordered alphabetically by ascending title. This technique is used to modify the orginal Loop content without losing that original content.

## Reseting a query

To avoid problems, always reset the query after you customized the Loop:
* Use *wp_reset_postdata()* after you made a custom instance of WP_Query.
* Use *wp_reset_query()* after you used query_posts().

```
$myPosts = new WP_Query('posts_per_page=1&orderby=rand');
while($myPosts->have_posts())
	$myPosts->the_post();
	//Do something
endwhile;
wp_reset_postdata();
```

Or

```
query_posts('posts_per_page=5');
if(have_posts())
	while(have_posts())
		the_post();
		//Do something
	endwhile;
endif;
wp_reset_query();
```

## Nested loops

Nested Loops can be created inside your theme templates using a combination of the main Loop and seperate WP_Query instances. For example, you can create a nested Loop to display related posts based on post tags. The following is an example of creating a nested Loop inside the main Loop to display related posts based on tags:

