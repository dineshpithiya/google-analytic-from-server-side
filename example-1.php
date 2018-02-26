```<?php 
```
>> include ss-ga.class

```require('ss-ga.class.php');```
>> Open this URL https://analytics.google.com/analytics/web/ and if you don't have account then create
After create project, click left hand side on top menu icon google analytic suite. now create new project

>> set track id and set your verified domain name

```$ssga = new ssga( 'UA-114573654-1', 'analytic.domain.com' );```
>> Set a pageview
```$ssga->set_page( '/example/index.php' );
$ssga->set_page_title( 'Page Title' );```
>> Send

>> Ecommerce tracking

```/*==set transaction property==*/
$TrackingId='UA-114573654-1';
$URL="analytic.domain.com";
$transaction_id=111111111111111;
$affiliation="dinesh pithiya";
$total=25; 
$tax=1; 
$shipping=5; 
$city="Junagah"; 
$region="Gujarat"; 
$country="india";
/*==end==*/
$step1= new ssga($TrackingId,$URL);
$step1->send_transaction($transaction_id, $affiliation, $total, $tax, $shipping,$city,$region,$country);

/*==create product==*/
$transaction_id=222222222222222; 
$sku= "Dinesh SKU";
$product_name ="My product - tracker";
$variation = "10";
$unit_price ="5000" ;
$quantity="1";
$step2 = new ssga($TrackingId,$URL);
$step2->send_item($transaction_id, $sku,$product_name,$variation,$unit_price,$quantity);
/*==end==*/

echo json_encode("suceess");```
?>```