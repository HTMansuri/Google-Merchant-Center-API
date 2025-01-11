<?php
error_reporting(E_ERROR);
/*	
*	APPROACH/IDEA:-
*	
*	1. Configure the script to access the database uisng access information and PASSWORD.
*   2. Implement password protection feature to protect the script from outside access.
*	3. A csv file will be created in the same folder whee this code file is stored.
    4. This csv file can then be used to maintain up-to-date product data by setting a cron job to update the file at regular intervals.
    5. Provide access to these csv file by using the SSH Connection to Google Merchant Center to fetch on daily basis.
    6. Also the following URL will allow us to access download the csv file. Remember to replace your_password with the password set in the configuration.
*		URL: https://www.company_domain.com/Folder_Name/.../this_file_name?pw=your_password
*	
*/

/*
    Varialbles to change as per your requirements:-
    DB_host_address, DB_user_name, DB_password, DB__name, your_password, prefix_, this_file_name, your_file_name, company_domain, item_condition, google_product_category
    //May Also need to change SQL query to select required data from the database as per the DB design.
*/

//Script Configuration
define ("DB_HOST", "DB_host_address"); //DB host address
define ("DB_USER", "DB_user_name"); //DB user name
define ("DB_PASS","DB_password"); //DB password
define ("DB_NAME","DB__name"); //Database name
define ("PASSWORD", "your_password"); //Script access password (will be used to prevent unauthorized access to the data).
define ("PREFIX", "prefix_"); //Table name prefix (if any). Example: "abc_", "xyz_", etc...

if($_GET["pw"]==PASSWORD){
	
	//Connect to the database and fetch the data
	$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("DB: Couldn't make connection. Please check the database configurations.");
	
	//Fetch the column names
	$columns = mysqli_fetch_fields($setRec);
	foreach($columns as $column){
		$setMainHeader .= $column->name."\t";
	}
	
	//Download headers
	header('Content-Type: text/csv; charset=utf-8'); 
  	header('Content-Disposition: attachment; filename=your_file_name.csv');  
      $output = fopen("your_file_name.csv", "w");  
  	fputcsv($output, array('id', 'title', 'description', 'link', 'canonical_link', 'price', 'image_link', 'mpn', 'brand', 'sell_on_google_quantity', 'availability', 'item_condition', 'google_product_category'), ",");    
      $query = "SELECT
	`".PREFIX."product`.sku AS id,
	`".PREFIX."product_description`.name AS title,
	`".PREFIX."product_description`.description,
	CONCAT('https://company_domain.com/products/',`".PREFIX."seo_url`.keyword) AS link,
	CONCAT('https://company_domain.com/',`".PREFIX."seo_url`.keyword) AS canonical_link,
	CONCAT(`".PREFIX."product`.price, ' USD') AS price,
	CONCAT('https://company_domain.com/image/',`".PREFIX."product`.image) AS image_link,
	`".PREFIX."product`.model AS mpn,
	`".PREFIX."manufacturer`.name AS brand,
    `".PREFIX."product`.quantity AS sell_on_google_quantity,
    CASE WHEN `".PREFIX."product`.quantity > 0 THEN 'in_Stock'
    ELSE 'out_of_stock'
	END AS Availability
FROM `".PREFIX."product` 
LEFT JOIN ".PREFIX."product_description ON `".PREFIX."product`.product_id = `".PREFIX."product_description`.product_id
LEFT JOIN ".PREFIX."seo_url ON `".PREFIX."product`.product_id = SUBSTRING(`".PREFIX."seo_url`.query,12)
LEFT JOIN ".PREFIX."manufacturer ON `".PREFIX."product`.manufacturer_id = `".PREFIX."manufacturer`.manufacturer_id
WHERE `".PREFIX."product`.status > 0
ORDER BY
	`".PREFIX."product`.product_id";
	//Print the table rows as an Excel row with the column name as a header
	$result = mysqli_query($link, $query); 
      while($row = mysqli_fetch_assoc($result))  
      {  
        $decoded = array("&lt;", "&gt;", "&quot;", "&amp;", "&nbsp;");
        $encode   = array("<", ">", '"',"&"," ");

		$row = str_replace($decoded, $encode, $row);
		$row = str_replace($decoded, $encode, $row);
		$row = str_replace($decoded, $encode, $row);
        array_push($row,"item_condition","google_product_category");
		fputcsv($output, $row,",");  
      }
  fclose($output);
}
//Message to display in case of wrong access password
else {
	$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
	echo "Invalid password! Remember to write the URL properly and include your password:<BR>".(isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]".$uri_parts[0]."?pw=type_password_here";
}
//ACCESS SITE - company_domain.com/Folder_Name/.../this_file_name?pass=your_password
?>