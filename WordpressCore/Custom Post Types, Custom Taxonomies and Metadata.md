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
