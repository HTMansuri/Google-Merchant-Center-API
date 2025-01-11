# Google Merchant Center API Integration

## Overview
This script is designed to generate a CSV file containing product data from a database. The generated CSV file can be used to maintain up-to-date product information for integration with Google Merchant Center. The script ensures secure access and allows for automated updates via cron jobs or manual download using a provided URL.

## Features
1. **Secure Access**: Password-protected URL ensures only authorized users can access the data.
2. **CSV Generation**: Dynamically fetches product data from the database and outputs a CSV file.
3. **Cron Job Compatibility**: Designed for automated periodic updates.
4. **Google Merchant Center Integration**: The CSV file contains fields required by Google Merchant Center, such as `id`, `title`, `price`, `availability`, and `google_product_category`.
5. **Easy Customization**: Flexible configuration for database connection and query customization.

## Setup Instructions

### 1. Configuration
Update the following constants in the script to suit your environment:
- **Database Configuration**:
  ```php
  define ("DB_HOST", "DB_host_address"); // Database host
  define ("DB_USER", "DB_user_name");   // Database username
  define ("DB_PASS", "DB_password");     // Database password
  define ("DB_NAME", "DB__name");       // Database name
  ```
- **Access Password**:
  ```php
  define ("PASSWORD", "your_password"); // Access password for the script
  ```
- **Table Prefix**:
  ```php
  define ("PREFIX", "prefix_"); // Table prefix, if any
  ```

### 2. File Placement
Place the script file in the desired folder on your server. Ensure the folder has write permissions to allow the script to generate the CSV file.

### 3. URL Access
Access the script via the following URL structure, replacing placeholders:
```
https://www.company_domain.com/Folder_Name/.../this_file_name?pw=your_password
```

### 4. Cron Job (Optional)
Set up a cron job to automate the periodic generation of the CSV file. For example:
```bash
0 2 * * * /usr/bin/php /path/to/script.php
```
This example runs the script daily at 2:00 AM.

## CSV File Details
The generated CSV file includes the following columns:
- `id`
- `title`
- `description`
- `link`
- `canonical_link`
- `price`
- `image_link`
- `mpn`
- `brand`
- `sell_on_google_quantity`
- `availability`
- `item_condition`
- `google_product_category`

## Security
Ensure the password is strong and not shared publicly. Access to the script should be limited to authorized personnel.

## Troubleshooting
- **Invalid Password**: Ensure the `pw` parameter in the URL matches the `PASSWORD` constant in the script.
- **Database Connection Issues**: Verify database credentials and connectivity.
- **File Permissions**: Ensure the script has write permissions for the folder.

## Notes
- Customize the SQL query in the script to match your database schema and requirements.
- The script can be extended to include additional fields or functionalities as needed.

## Example Usage
Access the script using the URL:
```
https://www.company_domain.com/path/to/this_file_name.php?pw=your_password
```
Download the generated CSV file or automate the updates with a cron job.
