=== Full of Stars ===
Contributors: AppSynergy
Tested up to: 4.9.5

Full of Stars is a WordPress plugin which provides you with a back-end to implement a star rating system.

It's made up of:

* A database table to store your ratings.
* The `get_ratings_summary()` function to help populate your templates.
* Two [REST API](https://developer.wordpress.org/rest-api/) endpoints to read, create or update ratings.

## API Endpoints

Both methods require a `post_id` parameter.

Users may only rate once per post.

### POST example.com/wp-json/stars/rating HTTP/1.1

The POST method adds or updates a rating.

WordPress cookie authentication is used to identify the user, so it also requires a [wp nonce](https://codex.wordpress.org/Function_Reference/wp_create_nonce). Set the action name to `wp_rest`.

**Example:**

````
$.post({
    url: "http://example.com/wp-json/stars/rating",
    data: {
        rating: 5,
        post_id: 42,
        _wpnonce: "xxxxxx"
    }
})
````

### GET example.com/wp-json/stars/rating HTTP/1.1

The GET method retrieves a summary of the ratings so far.

**Example:**

````
$.get({
    url: "http://example.com/wp-json/stars/rating",
    data: {
        post_id: 42
    }
})
````

A full jQuery example is available in the `/examples` directory.

`get_ratings_summary()` provides the same response as the GET method.
