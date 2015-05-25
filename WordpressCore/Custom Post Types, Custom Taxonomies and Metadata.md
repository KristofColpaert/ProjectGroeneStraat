# Custom Post Types, Custom Taxonomies, and Metadata

## Understanding data in WordPress

When working with various types of data in WordPress, it's important to understand what data is and how it can be customized. WordPress has five predefined post types in a default installation:

* Post: Posts or articles generally orderder by date. 
* Page: Hierarchical, static pages of content.
* Attachment: Media uploaded to WordPress and attached to post type entries such as images and files. 
* Revision: A reivision of a post type used as backup and can be restored if needed. 
* Nav menus: Menu items added to a nav menu using WordPress's menu management feature.

### What is a custom post type? 

A custom post type in WordPress is a custom defined piece of content. Using custom post types, you can define any type of content in WordPress, and you are no longer forced to use just the default post types listed in the previous section.

Potential cusotom post type ideas include, but are not necessarily limited to:
* Products
* Events
* Videos
* Rotator
* Testimonials
* Quotes
* Error logs

### Register custom post types

To create a new custom post type, you'll use the register_post_type() function, as shown here: 

```
<?php register_post_type($post_type, $args); ?>
```

The register_post_type() function accepts two parameters:
* $post type: the name of the post type.
* $args: an array of arguments that define the post type and various options in WordPress. 

There are many different arguments available when registering your custom post type. It's important to understand these arguments to know what's available. 

**Overzicht van alle argumenten is te vinden in het boek op pagina 129.**

```
<?php 
add_action('init', 'prowp_register_my_post_types');

function prowp_register_my_post_types()
{
  register_post_type('products',
    array(
      'labels' => array(
        'name' => 'Products'
      ),
      'public' => true
    )
  );
}
?>
```

### Setting post type labels

When creating a custom post type in WordPress, several text strings are shown throughout the WordPress admin dashboard for your post type. These text string are typically a link, button or extra information about the post type. By default, the term "post" is used for non-hierarchical post types and "page" for hierarchical post types.

Now, for example, you'll notice the text "Add New Post" at the top of the page when you add a now item of your custom post type. Setting the *labels* argument when registering your custom post type, will allow you to define exactly what is shown.

### Working with custom post types

You can use all of the same methods for creating custom Loops with WP_Query, as covered in detail in Loop.md, to display your custom post type content.

```
$args = array(
  'posts_per_page' => '-1',
  'post_type' => 'products'
);

$myProducts = new WP_Query($args);

//The Loop
while($myProducts->have_posts())
  $myProducts->the_post();
  //Do something with content
endwhile;

wp_reset_postdata();
```

### Custom Post Type Templates

Earlier in the chapter, you learned about the has_archive argument when registering a custom post type. Enabling this argument will allow you to create an archive template file that will display all of your custom post type entires by default. The name should be in the form of ```archive_{post_type}.php```. 

WordPress will also recognize a single template for your post type entries. This is the template that is loaded when you visit a single entry for your custom post type. The name should be in the form of ```single_{post_type}.php```.

## WordPress Taxonomy

Taxonomy is defined as a way to group similar items together. This basically adds a relational dimension to your website's content. In the case of WordPress, you use categories and tags to group your posts. By grouping these posts, you are defining the taxonomy of those posts.

### Default taxonomies

By default, WordPress comes loaded with two taxonomies:

* Category: A hierarchical bucket for grouping similar posts together. 
* Tag: A label attached to a post.
* 

### Building your own taxonomies

Creating your own taxonomies has many benefits. Imagine running a food blogging website. When creating new posts, you'll want to label a certain recipe as Asian, but you also may want to label the individual ingredients.

### Creating custom taxonomies

First, you are going to define your new taxonomy using the register_taxonomy() WordPress function. This function allows you to customize how your new taxonomy will work and look. 

```
<?php
  add_action('init', 'prowp_define_product_type_taxonomy');
  
  function prowp_define_product_type_taxonomy()
  {
    register_taxonomy (
      'type',
      'products',
      array (
        'hierarchical' => true,
        'label' => 'Type',
        'query_var' => true,
        'rewrite' => true
      )
    );
  }
?>
```

You can break down the parameters you are sending to the register_taxonomy() function:
 
* type: the taxonomy name.
* products: the object type.
* arguments:
  * hierarchical: defines whether or not your custom taxonomy can support nested taxonomies, forming a hierarchy. 
  * label: used to set the name of your taxonomy for use in the admin pages within WordPress.
  * query_var: if this argument is set to false, then no queries can be made against the taxonomy. If true, then the taxonomy name is used as a query variable in URL strings. Specifying a string value for the query_var overrides the default.
  * rewrite: this tells WordPress whether or not you want a pretty permalink when viewing your custom taxonomy. By setting this to true, you can access your custom taxonomy posts such as example.com/type/weapons rather than the ugly method of example.com/?type=weapons.

Now you've created a custom taxanomy and you can see it in your administration panel.

As with custom post types, you can set a variety of different arguments when registering a custom taxonomy. 

**Zie pagina 142 en verder voor een overzicht van alle mogelijke argumenten bij custom taxonomies.**

### Setting custom taxonomy labels

Similar to creating a custom post type in WordPress, custom taxonomies feature several text strings that are shown throughout the WordPress admin dashboard for your taxonomy. These text strings are typically a link, button, or extra information about the custom taxonomy. By default, the term "Tag" is used for non-hierarchical taxonomies and "Category" for hierarchical taxonomies.

**Zie pagina 144 en verder voor een overzicht van alle mogelijke labels.**

### Using your custom taxonomy

As with custom post types, there are several methods that let you work with your custom taxonomies:

* wp_tag_cloud(): displays a tag cloud showing your custom taxonomy terms.
* Custom WP_Query: customize him to only display items for a specific taxonomy term.
* get_the_term_list(): displays taxonomy terms assigned to a post.
* get_terms(): retrieve an array with your taxonomy values.

## Metadata

In this chapter, you've learned how to create custom post types to add to the basic content types managed by WordPress, and custom taxonomies to organize and collect those content types. This chapter wraps up with a look at extending the content management descriptors of a post with custom metadata.

### What is metadata? 

Metadata in WordPress refers to additional pieces of data attached to a post. For example, your products custom post type might need a price stored with each Product entered. The price could be stored as metadata and easily displayed on the Product detail page.

WordPress adds a custom fields meta box on the post-editing screen by default. If a custom post type has the custom-fields value defined for the supports argument, this meta box will also appear.

### Adding metadata

WordPress features a simple function to add new post metadata called add_post_meta().

```
<?php add_post_meta($post_id, $meta_key, $meta_value, $unique); ?>
```

This function accepts the following parameters:

* $post_id: the ID of the post to add metadata.
* $meta_key: the name of the metadata field.
* $meta_value: the value of the metadata field.
* $unique: a value identifying whether or not the key should be unique. The default value is false.

### Updating metadata

You can also update metadata using the update_post_meta() function:

```
<?php update_post_meta($post_id, $meta_key, $meta_value, $prev_value ); ?>
```

This function accepts the following parameters:

* $post_id: the ID of the post to update metadata.
* $meta_key: the name of the metadata field.
* $meta_value: the value of the metadata field.
* $prev_value: The old value of the metadata field to update. This is to differentiate between several fields with the same key and is an optional field.

### Deleting metadata

To delete post metadata, you'll use the delete_post_meta() function.

```
<?php delete_post_meta($post_id, $meta_key, $meta_value); ?>
```

This function accepts the following parameters:

* $post_id: the ID of the post to delete metadata.
* $meta_key: the name of the metadata field.
* $meta_value: the value of the metadata field. This is to differentiate between several fields with the same kay and is an optional field.

### Retrieving metadata

WordPress makes it easy to retrieve post metadata for display or use in other code. A good place to use this code is within a Loop to display custom metadata for a particular piece of content. 

To retrieve metadata, you'll use the get_post_meta() function:

```
<?php $meta_values = get_post_meta($post_id, $key, $single); ?>
```

This function accepts the following parameters:

* $post_id: the ID of the post to retrieve metadata for.
* $meta_key: the name of the metadata field.
* $single: a value identifying whether to return a single meta value field (true) or return an array of values (false). By default, this parameter is set to false.

Another powerfull function for retrieving post metadata is the get_post_custom() function. This function returns a multidimensional array of all metadata for a particular post.

```
<?php get_post_custom($post_id); ?>
```
