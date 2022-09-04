<?php
function URLImgProduct()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api-ntshop/images/products/product/';
    
    return $pageURL;
}

function URLImgCatePro()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api-ntshop/images/products/category/';
    
    return $pageURL;
}

function URLImgBrandPro()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api-ntshop/images/products/brand/';
    
    return $pageURL;
}
function URLImgUser()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api-ntshop/images/user/';
    
    return $pageURL;
}
function URLImgBanner()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api-ntshop/images/banner/';
    
    return $pageURL;
}
function URLImgNews()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api-ntshop/images/news/';
    
    return $pageURL;
}

?>