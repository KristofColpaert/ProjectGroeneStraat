#Data Management

WordPress is powered by a MySQL database backend. This database stores all of the data for your website, including your content, users, links, metadata, settings, and more.

## Database Schema

The database structure of WordPress is designed to be very minimal yet allow for endless flexibility when developing and designing for WordPress.

![Image of Database Scheme](http://i.gyazo.com/a8de27803d25770584e3f0096718dc2d.png)

The most important field in every table is the unique ID field. This field is not always named ID but is an auto-incrementing field used to give each record in the table a unique identifier. For example, each post is geiven a unique ID that can be used to load post-specific information and can also be used as the joining field against other tables in the database.

There is one caveat to this, which has to do with post revisions, attachments and custom post types. Each one of these entries is saved as a new record in the wp_posts table so they each gets its own unique ID, which means your published post IDs may not be sequential. For example, your first post may have an ID of 1, whereas your second post may have an ID of 15.

## Table details

Following is a list of alle WordPress tables and details on what data they store:

* wp_commentmeta: Contains all metadata for comments.
* wp_comments: Contains all comments within WordPress. Individual comments are linked back to posts through a post ID.
* wp_links: Contains all links added via the Link Manager section. The table still exists, but core functionality was deprecated in WordPress 3.5.
* wp_options: Stores all website options defined under the Settings Screen. Also stores plugin options, active plugins and themes, and more.
* wp_postmeta: Contains all post metadata (custom fields).
* wp_posts: Contains posts of all types (default and custom post types), pages, media records and revisions. Under most circumstances, this is the largest table in the database. 
* wp_terms: Contains all taxonomy terms defined for your website, mapping their next descriptions to term numbers that can be used as unique indexes into other tables.
* wp_term_relationships: Joins taxonomy terms with content, providing a membership table. It maps a term such as a tag or category name to the page or post that references it. 
* wp_term_taxonomy: Defines the taxonomy to which each term is assigned. This table allows you to have categories and tags with the same name, placing them in different named taxonomies.
* wp_users: Contains all users created in your website (login, password, email).
* wp_usermeta: Contains all metadata for users (first/last name, nickname, user level and so on).

## WordPress Database Class

WordPress features an object class with method functions for working with the database directly. This database class is called wpdb and is located in wp-includes/wp-db.php. Any time you are queryng the WordPress database in PHP code, you should use the wpdb class. The main reason for using this class is to allow WordPress to execute queries in the safest way possible.

### Simple Database Queries

When using the wpdb class, you must first define $wpdb as a global variable before it will be available for use.

```
global $wpdb
```

One of the most important functions in the wpdb class is the prepare() function. This function is used for escaping variables passed to your SQL queries. This is a critical step in preventing SQL injection attacks on your website.

The prepare() function accepts a minimum of two parameters: the $query parameter is the database query you want to run. The $value parameters are the values you want to replace in the query. You can add additional value paramters as needed. 

Example:

```
<?php
  global $wpdb;
  
  $value1 = "test1";
  $value2 = "test2";
  $prepared = $wpdb->prepare("INSERT INTO $wpdb->my_table (id, value1, value2) VALUES (%d, %s, %s)", 1, $value1, $value2);
  $wpdb->query($prepared);
?>
```

Notice that this example uses $wpdb->my_table to reference the table in WordPress. This translates to wp_my_table if wp_ is the table prefix. This is the proper way to determine the correct table prefix when working with tablrd in the WordPress database.

### Complex Database Operations

#### Select one row

To retrieve an entire table row, you will want to use the get_row() function. This function can return the row data as an obect, an associative array or a numerically indexed array. By default, the row is returned as an object.

```
<?php
  global $wpdb;
  
  $thepost = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID = %d", 1));
  echo $thepost->post_title;
?>
```

Return data as an associative array:

```
<?php
  global $wpdb;
  
  $thepost = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE ID = %d", 1), ARRAY_A);
  print_r($thepost);
?>
```

#### Select multiple rows

The following function returns the SQL result of multiple rows as an array of objects:

```
<?php
  global $wpdb;
  
  $liveposts = $wpdb->get_results($wpdb->prepare("SELECT ID, post_title FROM $wpdb->posts WHERE post_status = %d", 'publish'));
  
  foreach($liveposts as $livepost)
  {
    echo $livepost->post_title;
  }
?>
```

#### Insert data

Here is how the insert() function is structured: 

```
$wpdb->insert($table, $data, $format);
```

* The $table variable is the name of the table you want to insert a value into. 
* The $data variable is an array of field names and data to be inserted into those field names.
* The final parameter defines an array of formats to be mapped to each of the values in $data.

```
<?php
  global $wpdb;

  $wpdb->insert(
    $wpdb->postmeta,
    array(
      'post_id' => '1',
      'meta_key' => 'address',
      'meta_value' => 'Graaf Karel De Goedelaan 1, Kortrijk'
    ),
    array(
      %d,
      %s,
      %s
    )
  );
?>
```
#### Update data

The update() function works very similar to the insert() functiom except you also need to set the $where clause and the $where_format variables so WordPress knows which records to update and how to format:

```
$wpdb->update($table, $data, $where, $format, $where_format);
```

Example:

``` 
<?php
  global $wpdb;
  
  $wpdb->update(
    $wpdb->postmeta,
    array(
      'meta_value' => 'Graaf Karel De Goedelaan 2, Kortrijk'
    ),
    array(
      'post_id' => '1'
      'meta_key' => 'address'
    ),
    array(
      '%s'
    ),
    array(
      '%d',
      '%s'
    )
  );
?>
```

#### Deleta data

The delete() function is used to delete data from a WordPress database table.

```
$wpdb->delete($table, $where, $where_format);
```

Example:

```
$wpdb->delete(
  $wpdb->postmeta,
  array(
    'post_id' => '1',
    'meta_key' => 'address'
  ),
  array(
    '%d',
    '%s'
  )
);
```
