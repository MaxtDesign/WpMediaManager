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
- Batch processing for improved performance
- Comprehensive error logging and tracking
- Security measures to prevent unauthorized access
- Memory-optimized for large media libraries

## Installation

1. Download the plugin files
2. Upload the plugin folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

## How It Works

The plugin hooks into WordPress's `before_delete_post` action, which means:

- Media files are NOT removed when content is moved to trash
- Media files are ONLY removed when content is permanently deleted
- This provides a safety net against accidental media deletion
- Processes attachments in batches to prevent memory issues
- Tracks successful and failed deletions for debugging

## Supported Content Types

- Standard WordPress posts and pages
- ACF Gallery fields (requires ACF plugin)
- WooCommerce products (requires WooCommerce plugin)
  - Simple products
  - Variable products and variations
  - Product galleries

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- Optional: Advanced Custom Fields (ACF) for gallery support
- Optional: WooCommerce for product image management

## Security

- Prevents direct file access
- Verifies user capabilities before deletion
- Uses WordPress core functions for secure media deletion
- Includes error logging for failed deletion attempts
- All operations performed through WordPress's built-in security measures

## Error Handling

- Comprehensive tracking of successful and failed deletions
- Detailed error logging with specific attachment IDs
- Summary logging for debugging purposes
- Failed deletion attempts are logged to the WordPress error log
- Debug mode support for detailed operation tracking

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This plugin is licensed under the GPL v2 or later.

## Support

For support, please create an issue in the GitHub repository.

## Changelog

### Version 1.3
- Added batch processing for improved performance
- Implemented comprehensive error tracking
- Added security enhancements
- Improved WooCommerce integration
- Enhanced ACF compatibility
- Added detailed logging system
- Memory optimization for large media libraries
- Fixed WooCommerce detection method

### Version 1.2
- Initial public release
- Added support for WooCommerce variable products
- Improved error handling and logging

## Technical Details

The plugin includes several optimizations and safety features:

- Batch processing of attachments (100 at a time)
- Memory-efficient handling of variable products
- Conditional logging based on WP_DEBUG setting
- Proper handling of post revisions
- Type checking for all operations
- Detailed operation summaries for debugging

## Credits

This plugin was developed to solve the common issue of orphaned media files in WordPress installations. It builds upon WordPress core functionality and integrates with popular plugins like ACF and WooCommerce.
