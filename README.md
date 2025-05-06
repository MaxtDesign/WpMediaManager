# WordPress Media Manager

A WordPress plugin that automatically removes associated media files when posts, pages, or products are permanently deleted. This plugin helps maintain a clean media library by preventing orphaned media files.

## Features

- Automatically removes associated media files when content is permanently deleted
- Supports standard WordPress attachments
- Integrates with Advanced Custom Fields (ACF) Gallery fields
- Full WooCommerce product image management
  - Handles featured images
  - Removes product gallery images
  - Manages variable product variation images

## Installation

1. Download the plugin files
2. Upload the plugin folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

## How It Works

The plugin hooks into WordPress's `before_delete_post` action, which means:

- Media files are NOT removed when content is moved to trash
- Media files are ONLY removed when content is permanently deleted
- This provides a safety net against accidental media deletion

## Supported Content Types

- Standard WordPress posts and pages
- ACF Gallery fields
- WooCommerce products
  - Simple products
  - Variable products
  - Product variations

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- Optional: Advanced Custom Fields (ACF) for gallery support
- Optional: WooCommerce for product image management

## Security

The plugin uses WordPress core functions for media deletion and includes error logging for failed deletion attempts. All operations are performed through WordPress's built-in security measures.

## Error Handling

Failed deletion attempts are logged to the WordPress error log with specific details about which attachment failed to delete.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This plugin is licensed under the GPL v2 or later.

## Author

Created by Cody Hardman

## Support

For support, please create an issue in the GitHub repository.

## Changelog

### Version 1.2
- Initial public release
- Added support for WooCommerce variable products
- Improved error handling and logging

## Credits

This plugin was developed to solve the common issue of orphaned media files in WordPress installations. It builds upon WordPress core functionality and integrates with popular plugins like ACF and WooCommerce.