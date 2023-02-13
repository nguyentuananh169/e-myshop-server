<?php
function URLImgProduct()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api_myshop/images/products/product/';
    
    return $pageURL;
}

function URLImgCatePro()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api_myshop/images/products/category/';
    
    return $pageURL;
}

function URLImgBrandPro()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api_myshop/images/products/brand/';
    
    return $pageURL;
}
function URLImgUser()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api_myshop/images/user/';
    
    return $pageURL;
}
function URLImgBanner()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api_myshop/images/banner/';
    
    return $pageURL;
}
function URLImgNews()
{
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $pageURL = "https://";
    } else {
        $pageURL = 'http://';
    }
    $pageURL .= $_SERVER["SERVER_NAME"].'/api_myshop/images/news/';
    
    return $pageURL;
}

?>