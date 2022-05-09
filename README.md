
# Import WordPress.org Support Posts

Import Posts from https://fr.wordpress.org/support inside another site

## How it works
First, terms are imported, then posts are imported.

Deletion is not supported for now.

## How to use
For now :

Uncomment `add_action( 'admin_init', 'wpsp_import_terms' );` for import terms

Uncomment `add_action( 'admin_init', 'wpsp_import_posts' );` for import posts