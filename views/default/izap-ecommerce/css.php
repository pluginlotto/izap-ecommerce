<?php
/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * @version 1.0
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */
global $IZAP_ECOMMERCE;
?>


.izap-product-description{padding: 2px; margin: 2px}

.izap-product-float-left {
float:left;
}

.izap-contentWrapper:hover{
background-color:#EEEEEE;
}


.izap-product-float-right{float:right}
.listing-image {float: left; padding: 2px; margin: 2px; width: 16%}
.listing-description {float: left; padding: 2px; margin: 2px; width: 59%}
.listing-options {float: right; padding: 2px; margin: 2px; width: 20%}

.izap-product-buy-rate{width:45%;padding:8px;}
.izap-product-buy-price{width:35%;padding:12px;margin:10px;font-size:15px;
color:red;
border:1px solid black;}
.izap-product-remove-from-cart{}
.izap-product-cart .even {background-color: #E6EEC2}
.izap-product-cart .odd {background-color: #FAF1D7}
.izap-product-cart a {text-decoration: none}
.izap-order-detait-odd{padding: 4px; background-color: #ECE9E9}
.izap-order-detait-even{padding: 4px}
.izap-order-detait-total{border-top: 1px solid #000000; padding-right: 90px}
.izap-cart-title{margin-top: 5px; padding:2px 2px 2px 2px; border-top: #C0BEBE 1px solid; color:#FFFFFF; background-color:#4690D6}
.izap-cart-partial{padding:3px; border-bottom: #C0BEBE 1px solid}
.izap-partial-cart-product {color: #000000}
.izap-partial-cart-product a{color: #4690D6}
.izap-partial-cart-product .izap-product-remove-from-cart:hover{color: #E01F1F}
.izap-product-buy-buynow a.button{padding:4.5px; -moz-border-radius: 4px; -webkit-border-radius: 4px; background-color: #4690D6; color: #FFFFFF; text-decoration: none}
.izap-product-buy-buynow a.button:hover{background-color: #0054A8}
.river_object_izap_ecommerce_created{ background: url("<?php echo $IZAP_ECOMMERCE->_graphics; ?>river_post.png") no-repeat scroll left -1px transparent}
.river_object_izap_ecommerce_updated{ background: url("<?php echo $IZAP_ECOMMERCE->_graphics; ?>river_post.png") no-repeat scroll left -1px transparent}
.izap_ecommerce_widget_view{float: left; margin:2px; padding: 2px; border: 1px solid #DEDEDE; background-color: #DEDEDE}
.izap_ecommerce_widget_view:hover a {color:#FFFFFF; text-decoration: none}
.izap_ecommerce_widget_view:hover {
border: 1px solid #000000;
background-color: #000000;
}

.izap_products_terms {
background-color: #FFFFFF;
margin-left:30px;
padding:5px;
width:90%;
height: 150px;
overflow: auto;
border:2px solid #DEDEDE;
}

.izap_ecommerce_product_gallery:hover a{
color: #FFFFFF;
}

.izap-product-cart-descrption {
margin: 0px 0px 0px 10px;
width: 70%;
}

.order_detail_owner .icon {
float:left;
margin-right: 10px;
}

.order_detail_owner .info {

}


.confirmed_order {
background-color: #036100;
color: #FFFFFF;
-moz-border-radius:8px 8px 8px 8px;
-webkit-border-radius:8px 8px 8px 8px;
margin:0 10px 10px;
padding:10px;
}

.confirmed_order:hover {
background-color: #0B5109;
}

.confirmed_order a{
background-color: #036100;
color: #FFFFFF;
text-decoration:none;
}

.confirmed_order a:hover{
background-color: #0B5109;
color: #FFFFFF;
text-decoration:none;
}




.unconfirmed_order {
background-color: #FF0000;
color: #FCED00;
-moz-border-radius:8px 8px 8px 8px;
-webkit-border-radius:8px 8px 8px 8px;
margin:0 10px 10px;
padding:10px;
}

.unconfirmed_order:hover {
background-color: #C80000;
}

.unconfirmed_order a{
background-color: #FF0000;
color: #FCED00;
text-decoration:none;
}

.unconfirmed_order a:hover{
background-color: #C80000;
color: #FCED00;
text-decoration:none;
}

.screeshot_thumb {
float:left;
margin:4px;
}

.add_new_version {

float: right;
padding: 10px;
margin:10px 10px 0px 10px;
font-size:1em;
}

.add_new_version:hover {
background-color: #02417F;
}

.add_new_version a{
color: #FFFFFF;
font-weight: bold;
}

.add_new_version a:hover{
text-decoration: none;
color: #FFFFFF;
font-weight: bold;
}

.old_version_download {
float: right;
position: relative;
top: 65px;
}

.old_version_download a{
font-weight: bold;
font-size: 15px;
}


/***********************New layout for prodct page*****************************/

.izap-product-info {
}
.izap-product-info .left {
width: 55%;
}


.izap-product-info .right {
}

.izap-product-image {
padding:5px;
border:2px solid black;
}

.download {
}

.download a {
float:left;
}

.download a.img {
}

.comingsoon, .comingsoon:hover {
display: block;
margin: 30px;
font-size:38px;
font-weight:bolder;
font-family:arial;
color:#AE7D3D;
text-decoration: none;
}


.izap-product-extra {
  font-size: 16px;
  font-weight:bold;
  margin:10px;
}

.wishlist {
float:right;

}

.wishlist a {
text-decoration:none;
}

.download a.text{
margin: 15px 0px 0px 5px;
font-size:38px;
font-weight:bolder;
font-family:arial;
color:#AE7D3D;
text-decoration: none;
}

.download_desc {
font-size:25px;
}

.add_attrib {
margin:10px;
padding:5px;
}


.add_attrib fieldset {
border: 2px #BCDEFD solid;
padding: 10px;
background-color: #E4F2FF;
-moz-border-radius: 8px;
-webkit-border-radius: 8px;
margin-bottom: 10px;
}

.add_attrib fieldset legend {
font-size: 1.2em;
font-weight: bold;
margin-left: 8px;
-moz-border-radius: 8px;
-webkit-border-radius: 8px;
padding: 2px 5px 2px 5px;
background-color: #E4F2FF;
border: 2px #BCDEFD solid;
}

.add_group {
border:1px black solid;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
padding:10px;
margin:3px;
}

.attrib_list th{
font-size:14px;
font-weight:bold;
width:60px
margin:5px,15px,15px,15px;
padding:3px;
}

.attrib_list td{
font-size:12px;
width:60px
margin:5px,15px,15px,15px;
padding:3px;
}
.elgg-table-alt th {
	background-color: #eee;
	font-weight: bold;
}


.attrib_list ul{
margin-left:20px;
}

.izap-product-buy {
width:385px;
height:80px;
background-color: #FBFBF5;
border: 2px solid #dadada;
-moz-border-radius: 8px;
-webkit-border-radius: 8px;
padding: 5px;
margin:2px,0px,2px,0px;
}
.izap-product-info  fieldset {
border: 2px #BCDEFD solid;
padding: 10px;
background-color: #E4F2FF;
-moz-border-radius: 8px;
-webkit-border-radius: 8px;
margin-bottom: 10px;
}

.izap-product-info fieldset legend {
font-size: 1.2em;
font-weight: bold;
margin: 8px;
-moz-border-radius: 8px;
-webkit-border-radius: 8px;
padding: 2px 5px 2px 5px;
background-color: #E4F2FF;
border: 2px #BCDEFD solid;
}

.izap-product-info .right fieldset label {
font-weight: normal;
}

.izap-product-info .description {
padding: 5px;
margin-top: 10px;
margin-bottom: 10px;
border: 2px #DADADA solid;
background: #FBFBF5;
-moz-border-radius: 8px;
-webkit-border-radius: 8px;
}

.add_attrib_button {
position:relative;
top:80px;
cursor: pointer;
float: right;
}

#izap_product_attrib_group_form {
position:relative;
top:110px;
display:none;
border:1px black solid;
padding:10px;
background-color:#FBFBF5;
}

.izap-product_attrib_form {
display: none;
background: #DEDEDE;
position:relative;
top:50px;
}

#izap-product_attrib_form table{
width: 100%;
}


/*Tool tip*/
#tooltip {
position:absolute;
z-index:9999;
color:#fff;
font-size:10px;
width:300px;
}

#tooltip div{
text-decoration: none;
font-size: 14px;
}

#tooltip .tipHeader {
height:8px;
}

/* IE hack */
*html #tooltip .tipHeader {margin-bottom:-6px;}

#tooltip .tipBody {
background-color:#000;
padding:5px 5px 5px 15px;
}

#tooltip .tipFooter {
height:8px;
}

a.izap_pro_menu {
background: #AE7D3D;
color: #FFFFFF;
}

a.izap_pro_menu:hover {
background: #FFFFFF;
color: #AE7D3D;
}